const CACHE_NAME = 'localpro-v1';

self.addEventListener('install', (event) => {
    event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', (event) => {
    if (!event.data) return;

    const data = event.data.json();
    const title = data.title || 'LocalPro';
    const options = {
        body: data.body || '',
        icon: '/logo/localpro-mark.png',
        badge: '/logo/localpro-mark.png',
        data: { url: data.url || '/notifications' },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/notifications';
    event.waitUntil(clients.openWindow(url));
});
