/*
 * Service worker tuned for slow / unreliable connections.
 *
 * - Static, versioned files (CSS, JS, fonts, logo, ID card templates) are served from the
 *   browser cache, so after the first visit pages only download their HTML.
 * - Pages, Livewire requests and patient files are NEVER cached: they contain private
 *   patient data and must always be fresh.
 * - If a page cannot be loaded because the connection is down, an offline notice is shown
 *   instead of the browser's error page.
 */
// Bump this when replacing a file under /images or /fonts (e.g. a new logo or stamp),
// otherwise browsers keep showing the cached copy. /build files are hashed and need no bump.
const CACHE_NAME = 'hemophilia-static-v2';
const OFFLINE_URL = '/offline.html';

const PRECACHE = [
  OFFLINE_URL,
  '/fonts/vazirmatn.css',
  '/fonts/vazirmatn/Vazirmatn-wght.woff2',
  '/images/logo.png',
  '/icon-192.png',
];

// Paths that hold versioned / rarely changing static files
const STATIC_PATTERNS = [
  /^\/build\//,
  /^\/fonts\//,
  /^\/images\//,
  /^\/icon-\d+\.png$/,
  /^\/favicon\.ico$/,
  /^\/livewire-[0-9a-f]+\/livewire(\.min)?\.js$/,
  /^\/wireui\/assets\//,
  /^\/wireui\/icons\//,
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const request = event.request;
  if (request.method !== 'GET') return;

  const url = new URL(request.url);
  if (url.origin !== self.location.origin) return;

  // Static assets: cache first, then network (and remember the result)
  if (STATIC_PATTERNS.some((pattern) => pattern.test(url.pathname))) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) return cached;

        return fetch(request).then((response) => {
          if (response.ok) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return response;
        });
      })
    );
    return;
  }

  // Page navigations: always from the network; show the offline notice if that fails
  if (request.mode === 'navigate') {
    event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
  }
});
