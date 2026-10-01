self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('consegsa-cache-v1').then((cache) => {
            return cache.addAll([
                '/',
                '/css/app.css',
                '/js/app.js',
                '/images/consegsa.png',
                '/images/consegsa.png'
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});