const messageHistory = document.querySelector<HTMLElement>('[data-message-history]');
if (messageHistory) {
    messageHistory.scrollTop = messageHistory.scrollHeight;

    const pollUrl = messageHistory.dataset.pollUrl;
    const currentUserId = Number(messageHistory.dataset.currentUser);
    const connectionStatus = document.querySelector<HTMLElement>('[data-message-connection]');
    let lastMessageId = Array.from(messageHistory.querySelectorAll<HTMLElement>('[data-message-id]'))
        .reduce((latest, message) => Math.max(latest, Number(message.dataset.messageId) || 0), 0);
    let polling = false;

    async function fetchMessages(): Promise<void> {
        if (!pollUrl || polling || document.hidden) return;
        polling = true;

        try {
            const url = new URL(pollUrl, window.location.href);
            url.searchParams.set('after', String(lastMessageId));
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Message refresh failed');

            const result = await response.json() as { messages: Array<{ id: number; sender_id: number; sender_name: string; body: string; created_at: string }> };
            const wasNearBottom = messageHistory.scrollHeight - messageHistory.scrollTop - messageHistory.clientHeight < 80;

            result.messages.forEach((message) => {
                if (!Number.isFinite(message.id) || message.id <= lastMessageId) return;
                const bubble = document.createElement('article');
                bubble.className = `message-bubble ${message.sender_id === currentUserId ? 'message-mine' : 'message-theirs'}`;
                bubble.dataset.messageId = String(message.id);
                bubble.dataset.senderId = String(message.sender_id);

                const heading = document.createElement('small');
                heading.textContent = `${message.sender_name} · ${message.created_at}`;
                const body = document.createElement('p');
                body.textContent = message.body;
                bubble.append(heading, body);
                messageHistory.querySelector('[data-message-empty]')?.remove();
                messageHistory.append(bubble);
                lastMessageId = message.id;
            });

            if (wasNearBottom) messageHistory.scrollTop = messageHistory.scrollHeight;
            if (connectionStatus) connectionStatus.textContent = 'Messages refresh while this page is open.';
        } catch {
            if (connectionStatus) connectionStatus.textContent = 'Connection interrupted. New messages will appear when you reconnect.';
        } finally {
            polling = false;
        }
    }

    window.setInterval(fetchMessages, 5000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) void fetchMessages();
    });
}

const messageForm = document.querySelector<HTMLFormElement>('.message-compose');
messageForm?.addEventListener('submit', () => {
    const submit = messageForm.querySelector<HTMLButtonElement>('button[type="submit"]');
    if (submit) submit.disabled = true;
});

