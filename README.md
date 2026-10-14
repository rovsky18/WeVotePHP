# WeVote PHP (tanpa JavaScript)

Aplikasi pemilihan sekolah berbasis PHP 8+, MySQL/MariaDB, HTML, dan CSS. Tidak ada file atau kode JavaScript. Semua aksi formulir memuat ulang halaman melalui PHP.

## Fitur
- Homepage dengan informasi pemilihan, status jadwal, dan tautan scroll ke daftar kandidat.
- Kandidat dengan foto, visi-misi, serta form voting.
- Login siswa/pegawai dengan NIS/NIP/ID dan password hash.
- Batas satu suara per akun yang dijaga oleh unique constraint di database.
- Hasil pemilihan berupa bar chart CSS.
- Panel admin: pengaturan sekolah/pemilihan, CRUD kandidat, pengelolaan akun, logo, dan tiga background.
- Upload gambar JPG/PNG/WebP maksimal 5 MB.
- Responsive layout dan scroll behavior menggunakan CSS.

## Instalasi (XAMPP di Windows)
1. Salin folder `WeVote-PHP` ke `C:\xampp\htdocs\WeVote-PHP`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka phpMyAdmin (`http://localhost/phpmyadmin`). Import file `schema.sql`. File ini membuat database `wevote_php`, tabel, pengaturan default, dan tiga kandidat demo.
4. Edit `config.php` jika user/password MySQL Anda berbeda. Default XAMPP biasanya user `root` dan password kosong.
5. Buka `http://localhost/WeVote-PHP/seed.php` satu kali untuk membuat akun demo dengan password yang di-hash. Setelah selesai, jangan jalankan seed berulang tanpa alasan.
6. Buka `http://localhost/WeVote-PHP/`.

## Akun demo
- Admin: `admin` / `Admin123!`
- Superadmin: `superadmin` / `SuperAdmin123!`
- Siswa: `2026001` / `Siswa123!`
- Pegawai: `1987001` / `Pegawai123!`

Segera ganti password demo dari menu Pemilih & Akun setelah login admin.

## Catatan
- Jadwal awal demo diatur ke 1 Januari 2030. Ubah jadwal melalui panel admin sebelum pengujian voting.
- Karena tidak memakai JavaScript, countdown tidak berdetak real-time; status dihitung di server ketika halaman dimuat. Background dapat diunggah dan dipilih, tetapi rotasi otomatis setiap 10 detik tidak dilakukan.
- Untuk produksi, gunakan HTTPS, ganti semua password demo, batasi akses admin, atur backup database, dan tinjau konfigurasi upload/server.
- PHP extensions `pdo_mysql`, `fileinfo`, dan GD/dukungan image PHP sebaiknya aktif.
