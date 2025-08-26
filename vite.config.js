import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: '0.0.0.0', // Agar bisa diakses dari perangkat lain
    port: 8000,       // Sesuaikan port yang Anda gunakan
    hmr: {
      host: '192.168.0.18', // IP Laptop 1 jika menggunakan HMR
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
});
