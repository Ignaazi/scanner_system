const CACHE_NAME = 'siix-scanner-v2'; // Diubah ke v2 agar logo baru terdeteksi
const urlsToCache = [
  '/',
  'index.php',
  'admin/users.php',
  'assets/css/bootstrap.min.css',
  'notifikasi.php',
  'manifest.json',           // Tambahkan ini
  'assets/img/profile/iconapk.png'   // Tambahkan logo baru Anda di sini
];

// Install Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('Mendaftarkan cache baru...');
      return cache.addAll(urlsToCache);
    })
  );
  // Memaksa service worker baru untuk segera aktif
  self.skipWaiting();
});

// Bersihkan cache lama agar tidak menumpuk
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            console.log('Menghapus cache lama...');
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// Jalankan saat Offline
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});