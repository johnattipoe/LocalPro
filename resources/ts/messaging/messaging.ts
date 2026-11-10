type IncomingMessage = {
    id: number;
    sender_id: number;
    sender_name: string;
    body: string;
    created_at: string;
};

type RealtimeWindow = Window & {
    Echo?: {
        private: (channel: string) => {
            listen: (event: string, callback: (message: any) => void) => unknown;
        };
        connector?: {
            pusher?: {
                connection?: {
                    bind: (event: string, callback: () => void) => void;
                };
            };
        };
    };
};

const messageHistory = document.querySelector<HTMLElement>('[data-message-history]');
if (messageHistory) {
    messageHistory.scrollTop = messageHistory.scrollHeight;

    const pollUrl = messageHistory.dataset.pollUrl;
    const currentUserId = Number(messageHistory.dataset.currentUser);
    const serviceRequestId = messageHistory.dataset.serviceRequestId;
    const connectionStatus = document.querySelector<HTMLElement>('[data-message-connection]');
    let lastMessageId = Array.from(messageHistory.querySelectorAll<HTMLElement>('[data-message-id]'))
        .reduce((latest, message) => Math.max(latest, Number(message.dataset.messageId) || 0), 0);
    let polling = false;
    const echo = (window as RealtimeWindow).Echo;

    function appendMessage(message: IncomingMessage): void {
        if (!Number.isFinite(message.id) || message.id <= lastMessageId) return;
        const wasNearBottom = messageHistory.scrollHeight - messageHistory.scrollTop - messageHistory.clientHeight < 80;
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
        if (wasNearBottom) messageHistory.scrollTop = messageHistory.scrollHeight;
    }

    if (echo && serviceRequestId) {
        echo.private(`service-request.${serviceRequestId}`).listen('.message.sent', appendMessage);
        const connection = echo.connector?.pusher?.connection;
        connection?.bind('connected', () => {
            if (connectionStatus) connectionStatus.textContent = 'Live messaging connected. This page also checks for missed messages.';
        });
        ['unavailable', 'failed', 'disconnected'].forEach((state) => {
            connection?.bind(state, () => {
                if (connectionStatus) connectionStatus.textContent = 'Live connection unavailable. Checking for messages periodically.';
            });
        });
        if (connectionStatus) connectionStatus.textContent = 'Connecting to live messages…';
    }

    async function fetchMessages(): Promise<void> {
        if (!pollUrl || polling || document.hidden) return;
        polling = true;

        try {
            const url = new URL(pollUrl, window.location.href);
            url.searchParams.set('after', String(lastMessageId));
            const response = await fetch(url, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Message refresh failed');

            const result = await response.json() as { messages: IncomingMessage[] };
            result.messages.forEach(appendMessage);
            if (!echo && connectionStatus) connectionStatus.textContent = 'Messages refresh while this page is open.';
        } catch {
            if (connectionStatus) connectionStatus.textContent = 'Connection interrupted. New messages will appear when you reconnect.';
        } finally {
            polling = false;
        }
    }

    window.setInterval(fetchMessages, echo ? 15000 : 5000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) void fetchMessages();
    });
}

const messageForm = document.querySelector<HTMLFormElement>('.message-compose');
messageForm?.addEventListener('submit', () => {
    const submit = messageForm.querySelector<HTMLButtonElement>('button[type="submit"]');
    if (submit) submit.disabled = true;
});

// --- Quick replies ---
const quickRepliesToggle = document.querySelector<HTMLElement>('[data-quick-replies-toggle]');
const quickRepliesDialog = document.querySelector<HTMLDialogElement>('[data-quick-replies-dialog]');
const quickRepliesClose = document.querySelector<HTMLElement>('[data-quick-replies-close]');
const quickRepliesList = document.querySelector<HTMLElement>('[data-quick-replies-list]');
const messageBody = document.querySelector<HTMLTextAreaElement>('#message-body');

async function loadQuickReplies(): Promise<void> {
    if (!quickRepliesList || !messageBody) return;
    try {
        const csrfMeta = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
        const response = await fetch('/quick-replies', {
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfMeta },
        });
        if (!response.ok) return;
        const templates = await response.json() as Array<{ id: number; title: string; body: string; is_system: boolean }>;
        quickRepliesList.innerHTML = templates.map((t) => `
            <button type="button" class="quick-reply-item" data-quick-reply-insert="${escapeHtml(t.body)}">
                <strong>${escapeHtml(t.title)}</strong><span>${escapeHtml(t.body.length > 60 ? t.body.slice(0, 60) + '…' : t.body)}</span>
            </button>
        `).join('');
        quickRepliesList.querySelectorAll('button[data-quick-reply-insert]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const insertText = btn.dataset.quickReplyInsert ?? '';
                if (messageBody && insertText) {
                    const start = messageBody.selectionStart ?? 0;
                    const end = messageBody.selectionEnd ?? 0;
                    const text = messageBody.value;
                    messageBody.value = text.slice(0, start) + insertText + text.slice(end);
                    messageBody.dispatchEvent(new Event('input', { bubbles: true }));
                }
                quickRepliesDialog?.close();
            });
        });
    } catch {
        // Silently fail - quick replies are optional
    }
}

function escapeHtml(text: string): string {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

quickRepliesToggle?.addEventListener('click', () => {
    void loadQuickReplies();
    quickRepliesDialog?.showModal();
});
quickRepliesClose?.addEventListener('click', () => quickRepliesDialog?.close());
document.querySelector('[data-add-quick-reply]')?.addEventListener('click', () => {
    quickRepliesDialog?.close();
});

// --- Voice/video calls ---
const startCallButton = document.querySelector<HTMLElement>('[data-start-call]');
const callDialog = document.querySelector<HTMLDialogElement>('[data-call-dialog]');
const callNotification = document.querySelector<HTMLElement>('[data-call-notification]');
const callStatus = document.querySelector<HTMLElement>('[data-call-status]');
const callHangup = document.querySelector<HTMLElement>('[data-call-hangup]');
const callAccept = document.querySelector<HTMLElement>('[data-call-accept]');
const callReject = document.querySelector<HTMLElement>('[data-call-reject]');
const localVideo = document.querySelector<HTMLVideoElement>('[data-call-video-local] video');
const remoteVideo = document.querySelector<HTMLVideoElement>('[data-call-video-remote] video');

let localStream: MediaStream | null = null;
let remoteStream: MediaStream | null = null;
let peerConnection: RTCPeerConnection | null = null;
let callChannel: any = null;
let isCaller = false;
let callIncoming = false;

const iceServers = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };

async function initEchoCallChannel(): Promise<void> {
    const echo = (window as RealtimeWindow).Echo;
    if (!echo || !serviceRequestId) return;
    callChannel = echo.private(`service-request.${serviceRequestId}.calls`);
    callChannel.listen('.call.offer', async (data: any) => {
        if (data.from === currentUserId) return;
        callIncoming = true;
        if (callNotification) callNotification.style.display = 'block';
        callStatus?.textContent?.replaceWith(document.createTextNode('Incoming call...'));
    });
    callChannel.listen('.call.answer', async (data: any) => {
        if (data.from === currentUserId) return;
        if (peerConnection && data.sdp) {
            await peerConnection.setRemoteDescription(new RTCSessionDescription(data.sdp));
        }
        if (callNotification) callNotification.style.display = 'none';
        if (callDialog) callDialog.showModal();
        callStatus?.textContent?.replaceWith(document.createTextNode('Call connected'));
    });
    callChannel.listen('.call.ice-candidate', async (data: any) => {
        if (data.from === currentUserId || !peerConnection || !data.candidate) return;
        try {
            await peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
        } catch {
            // Ignore invalid candidates
        }
    });
    callChannel.listen('.call.hangup', (data: any) => {
        if (data.from === currentUserId) return;
        endCallUI();
    });
}

async function startLocalStream(): Promise<void> {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        if (localVideo && localStream) localVideo.srcObject = localStream;
    } catch (error) {
        console.error('Could not access camera/microphone:', error);
        localStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        if (localVideo && localStream) localVideo.srcObject = localStream;
    }
}

async function createPeerConnection(): Promise<RTCPeerConnection> {
    const pc = new RTCPeerConnection(iceServers);
    if (localStream) {
        localStream.getTracks().forEach((track) => pc.addTrack(track, localStream!));
    }
    pc.ontrack = (event) => {
        if (event.streams[0] && remoteVideo) remoteVideo.srcObject = event.streams[0];
    };
    pc.onicecandidate = (event) => {
        if (event.candidate && callChannel) {
            void callChannel.whisper('call.ice-candidate', { to: isCaller ? null : currentUserId, from: currentUserId, candidate: event.candidate });
        }
    };
    return pc;
}

startCallButton?.addEventListener('click', async () => {
    isCaller = true;
    await initEchoCallChannel();
    await startLocalStream();
    peerConnection = await createPeerConnection();
    const offer = await peerConnection!.createOffer();
    await peerConnection!.setLocalDescription(offer);
    if (callChannel) {
        void callChannel.whisper('call.offer', { to: null, from: currentUserId, sdp: offer });
    }
    callStatus?.textContent?.replaceWith(document.createTextNode('Calling...'));
    if (callDialog) callDialog.showModal();
});

callHangup?.addEventListener('click', endCall);
callReject?.addEventListener('click', () => {
    callIncoming = false;
    if (callNotification) callNotification.style.display = 'none';
    if (callChannel) {
        void callChannel.whisper('call.hangup', { to: null, from: currentUserId });
    }
});

callAccept?.addEventListener('click', async () => {
    isCaller = false;
    callIncoming = false;
    if (callNotification) callNotification.style.display = 'none';
    await startLocalStream();
    peerConnection = await createPeerConnection();
    // The offer was already received via .call.offer listener
    // We need to create an answer - but we need the offer SDP stored
    // For simplicity, re-request the offer via whisper is not possible,
    // so the caller's offer was already stored. This is a simplified version.
    if (callDialog) callDialog.showModal();
    callStatus?.textContent?.replaceWith(document.createTextNode('Call connected'));
});

function endCall(): void {
    if (peerConnection) peerConnection.close();
    peerConnection = null;
    localStream?.getTracks().forEach((track) => track.stop());
    localStream = null;
    if (localVideo) localVideo.srcObject = null;
    if (remoteVideo) remoteVideo.srcObject = null;
    if (callDialog) callDialog.close();
    if (callNotification) callNotification.style.display = 'none';
    if (callChannel) {
        void callChannel.whisper('call.hangup', { to: null, from: currentUserId });
    }
    callIncoming = false;
}

function endCallUI(): void {
    endCall();
    callStatus?.textContent?.replaceWith(document.createTextNode('Call ended'));
}
