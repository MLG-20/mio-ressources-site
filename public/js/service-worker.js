/**
 * Service Worker pour MIO Ressources
 * 
 * Gère :
 * - Cache des assets (CSS, JS, images)
 * - Fonctionnement offline
 * - Synchronisation en arrière-plan
 */

const CACHE_VERSION = 'mio-v1';
const CACHE_ASSETS = `${CACHE_VERSION}-assets`;
const CACHE_PAGES = `${CACHE_VERSION}-pages`;
const CACHE_API = `${CACHE_VERSION}-api`;

// Assets à pré-cacher (statiques)
const ASSETS_TO_CACHE = [
    '/',
    '/index.php',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

/**
 * Installation du Service Worker
 */
self.addEventListener('install', (event) => {
    console.log('🔧 Service Worker installing...');
    
    event.waitUntil(
        caches.open(CACHE_ASSETS).then((cache) => {
            console.log('📦 Pré-cache des assets...');
            return cache.addAll(ASSETS_TO_CACHE).catch((err) => {
                console.warn('⚠️ Erreur pré-cache:', err);
                // Continuer même si le pré-cache échoue
                return Promise.resolve();
            });
        })
    );
    
    // Force l'activation immédiate
    self.skipWaiting();
});

/**
 * Activation du Service Worker
 */
self.addEventListener('activate', (event) => {
    console.log('✅ Service Worker activated');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    // Supprimer les anciennes versions
                    if (cacheName !== CACHE_ASSETS && cacheName !== CACHE_PAGES && cacheName !== CACHE_API) {
                        console.log(`🗑️ Suppression du cache: ${cacheName}`);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    // Claim les clients
    return self.clients.claim();
});

/**
 * Fetch Handler - Stratégie Cache First / Network Fallback
 */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // 1️⃣ API Calls - Network First (toujours chercher la version actuelle)
    if (url.pathname.startsWith('/api/')) {
        return event.respondWith(
            fetch(request)
                .then((response) => {
                    // Mettre en cache si succès
                    if (response.status === 200) {
                        const clone = response.clone();
                        caches.open(CACHE_API).then((cache) => {
                            cache.put(request, clone);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Si erreur réseau, chercher en cache
                    return caches.match(request).then((cached) => {
                        return cached || new Response(
                            JSON.stringify({ error: 'Offline - pas de cache' }),
                            { status: 503, headers: { 'Content-Type': 'application/json' } }
                        );
                    });
                })
        );
    }

    // 2️⃣ Pages HTML - Network First
    if (request.mode === 'navigate' || request.headers.get('Accept')?.includes('text/html')) {
        return event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        const clone = response.clone();
                        caches.open(CACHE_PAGES).then((cache) => {
                            cache.put(request, clone);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Fallback à l'offline page si disponible
                    return caches.match(request).then((cached) => {
                        return cached || new Response(
                            '<h1>Mode Hors Ligne</h1><p>Vous êtes actuellement hors ligne. Reconnectez-vous pour continuer.</p>',
                            { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                        );
                    });
                })
        );
    }

    // 3️⃣ Assets (CSS, JS, Images) - Cache First
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    ) {
        return event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) {
                    console.log(`📦 Cache hit: ${url.pathname}`);
                    return cached;
                }

                return fetch(request).then((response) => {
                    if (response.status === 200 && (response.type === 'basic' || response.type === 'cors')) {
                        const clone = response.clone();
                        caches.open(CACHE_ASSETS).then((cache) => {
                            cache.put(request, clone);
                        });
                    }
                    return response;
                }).catch(() => {
                    // Fallback pour les images
                    if (request.destination === 'image') {
                        return new Response(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect fill="#ddd" width="100" height="100"/></svg>',
                            { headers: { 'Content-Type': 'image/svg+xml' } }
                        );
                    }
                    throw new Error('Offline');
                });
            })
        );
    }

    // 4️⃣ Other requests - Network First
    return event.respondWith(
        fetch(request)
            .then((response) => response)
            .catch(() => {
                return caches.match(request) || new Response('Erreur réseau', { status: 503 });
            })
    );
});

/**
 * Gestion des messages (optionnel - pour les notifications push)
 */
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

console.log('🚀 Service Worker loaded and ready!');
