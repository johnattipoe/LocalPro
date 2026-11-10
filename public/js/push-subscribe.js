const VAPID_PUBLIC_KEY = document.querySelector('meta[name="vapid-public-key"]')?.content ?? '';

async function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return new Uint8Array([...rawData].map((char) => char.charCodeAt(0)));
}

async function postSubscription(path, body) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const response = await fetch(path, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        throw new Error(`Push subscription request failed with status ${response.status}.`);
    }
}

async function registerPush() {
    if (!('PushManager' in window)) {
        alert('Push notifications are not supported in this browser.');
        return;
    }
    if (!VAPID_PUBLIC_KEY) {
        alert('Push notifications are not configured on this server.');
        return;
    }

    const registration = await navigator.serviceWorker.ready;
    const existing = await registration.pushManager.getSubscription();
    if (existing) {
        alert('Already subscribed to push notifications.');
        return;
    }

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: await urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
    });

    const keys = subscription.toJSON().keys;
    if (!keys?.p256dh || !keys.auth) {
        await subscription.unsubscribe();
        throw new Error('The browser did not provide valid push subscription keys.');
    }

    try {
        await postSubscription('/notifications/push/subscribe', {
            endpoint: subscription.endpoint,
            p256dh_key: keys.p256dh,
            auth_key: keys.auth,
        });
    } catch (error) {
        await subscription.unsubscribe();
        throw error;
    }

    location.reload();
}

async function unregisterPush() {
    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.getSubscription();
    if (!subscription) return;

    await postSubscription('/notifications/push/unsubscribe', { endpoint: subscription.endpoint });
    await subscription.unsubscribe();
    location.reload();
}

function handlePushError(error) {
    console.error('Push notification setup failed:', error);
    alert('Push notifications could not be updated. Please try again.');
}

document.getElementById('push-subscribe')?.addEventListener('click', () => {
    registerPush().catch(handlePushError);
});
document.getElementById('push-unsubscribe')?.addEventListener('click', () => {
    unregisterPush().catch(handlePushError);
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch((err) => {
        console.warn('Service worker registration failed:', err);
    });
}
