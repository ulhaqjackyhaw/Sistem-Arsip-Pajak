
## Sistem Arsip Bukti Potong Pajak

Sistem Arsip Bukti Potong Pajak adalah aplikasi web berbasis Laravel untuk mengelola, menyimpan, dan mendistribusikan dokumen bukti potong pajak secara terpusat. Aplikasi ini dibuat untuk memudahkan koordinasi antara admin, tax officer, dan vendor dalam proses unggah, pencarian, pengarsipan, serta pengunduhan dokumen pajak.

## Tujuan Sistem

Sistem ini membantu proses administrasi bukti potong pajak agar lebih rapi, aman, dan mudah ditelusuri. Setiap dokumen disimpan berdasarkan vendor dan periode, sehingga pencarian arsip menjadi lebih cepat dan risiko kehilangan file dapat dikurangi.

## Peran Pengguna

### 1. Admin
Admin berfungsi sebagai pengelola data master dan akun pengguna. Admin juga memiliki seluruh akses operasional tax officer. Admin dapat:

- melihat daftar vendor
- menambah, mengubah, dan menghapus data vendor
- membuat akun vendor
- mereset password akun vendor
- mengelola impor dan ekspor data vendor
- melihat detail vendor
- mengunggah dokumen bukti potong pajak
- menghapus dokumen yang tidak diperlukan
- melakukan unggah dokumen secara massal
- mengunduh dokumen sesuai kebutuhan operasional

### 2. Tax Officer
Tax officer berfungsi sebagai petugas operasional dokumen. Tax officer dapat:

- melihat daftar vendor dan detail vendor
- mengunggah dokumen bukti potong pajak
- menghapus dokumen yang tidak diperlukan
- melakukan unggah dokumen secara massal
- mengunduh dokumen sesuai kebutuhan operasional

### 3. Vendor
Vendor adalah pihak yang menerima dan mengunduh dokumen bukti potong pajak miliknya sendiri. Vendor dapat:

- login menggunakan akun vendor
- melihat daftar dokumen yang terkait dengan NPWP atau vendor miliknya
- mencari dokumen berdasarkan nama file atau periode
- mengunduh satu dokumen atau beberapa dokumen sekaligus dalam bentuk ZIP

## Alur Kerja Sistem

1. Admin atau tax officer menambahkan data vendor ke dalam sistem.
2. Sistem membuat akun vendor berdasarkan data tersebut.
3. Admin atau tax officer mengunggah dokumen bukti potong pajak ke vendor yang sesuai.
4. Dokumen disimpan di storage privat berdasarkan vendor dan periode.
5. Vendor login untuk melihat arsip dokumen miliknya.
6. Vendor dapat mengunduh dokumen satu per satu atau dalam bentuk file ZIP.

## Fitur Utama

- autentikasi pengguna dengan pembagian peran
- proteksi akses berdasarkan role, dengan admin mendapat akses operasional tax officer
- manajemen data vendor
- upload dokumen bukti potong pajak
- penyimpanan dokumen privat
- pencarian dokumen berdasarkan NPWP, nama, atau periode
- unduh dokumen per file
- unduh banyak dokumen sekaligus dalam format ZIP
- halaman landing yang memisahkan akses admin dan vendor

## Teknologi yang Digunakan

- Laravel 12
- PHP 8.3
- MySQL
- Vite
- Tailwind CSS

## Struktur Singkat Data

- `users` menyimpan akun login dan role pengguna
- `vendors` menyimpan data vendor dan NPWP
- `documents` menyimpan metadata file bukti potong pajak

.....