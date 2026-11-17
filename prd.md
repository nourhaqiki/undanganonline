# ROLE & EXECUTION RULES
Anda adalah Principal Full-Stack Laravel 10 Architect. Bangun aplikasi web **Generator Undangan Online** (mengadopsi standar UX/UI interaktif undangan digital modern seperti Invitos) yang dilengkapi **Arsitektur Tema Extensible** serta **Panel Administrator** di dalam proyek **Laravel 10** (`C:\laragon\www\undanganonline`).

**ATURAN EKSEKUSI KETAT (WAJIB DIPATUHI):**
1. **Zero-Garbage-Code & Minimal Bloat:** Tulis kode yang ringkas, modular, DRY (*Don't Repeat Yourself*), dan efisien. **JANGAN** menginstal package pihak ketiga yang berat (seperti Filament, Breeze, Spatie, atau Voyager). Gunakan fitur bawaan Laravel 10 + Blade + Tailwind CSS + Alpine.js CDN.
2. **Aturan Sintaks & Struktur Laravel 10 (WAJIB):**
   - Backend menggunakan **Laravel 10 (PHP 8.2+)** dan **MySQL (Laragon)**.
   - Pada Model, wajib gunakan properti **`protected $casts = ['event_data' => 'array'];`** (JANGAN gunakan method `protected function casts()` milik Laravel 11).
   - Daftarkan semua route publik dan admin hanya di **`routes/web.php`**.
3. **Lock Frontend Stack (Tailwind CSS v4 + Vite):**
   - Gunakan **Blade + Tailwind CSS v4 (`@tailwindcss/vite`) + Alpine.js v3 (CDN)**.
   - Pastikan `vite.config.js` memakai plugin `@tailwindcss/vite` dan `resources/css/app.css` berisi `@import "tailwindcss";`. **JANGAN** membuat file `tailwind.config.js` atau `postcss.config.js`.
4. **100% Mobile-First & Cross-Device Responsive:**
   - Wajib responsif sempurna tanpa *horizontal scroll* (`overflow-x-hidden`) di **Android, iPhone (termasuk notch & dynamic address bar), Tablet/iPad, Laptop, dan Desktop**.
   - Gunakan `h-dvh` (bukan `100vh`) untuk layar sampul fullscreen, area sentuh tombol minimal `min-h-[44px]`, dan tambahkan ruang bawah (`pb-24`) pada kontainer utama undangan agar *floating navigation bar* tidak menutupi konten/tombol paling bawah.

---

# 1. SKEMA DATABASE & SEEDER

Buat 3 tabel aplikasi + 1 Seeder awal:
1. **`users` (Bawaan Laravel):** Untuk autentikasi Administrator (tanpa fitur registrasi publik).
2. **`themes` (Sistem Tema Extensible):**
   - `id`
   - `name` (string — contoh: `Adat Jawa Klasik`)
   - `slug` (string, unique — contoh: `jawa`, merujuk ke `resources/views/themes/{slug}.blade.php`)
   - `category` (string — `pernikahan`, `tunangan`, `bisnis`, `semua`)
   - `accent_color` (string — kode hex preview tema, contoh: `#D4AF37`)
   - `description` (string, nullable)
   - `is_active` (boolean, default `true`)
   - `timestamps`
3. **`invitations`:**
   - `id`
   - `slug` (string, unique — contoh: `tasya-zun-x7k9`)
   - `edit_token` (string, unique — 24 karakter acak untuk tautan edit mandiri pembuat undangan tanpa login)
   - `category` (string — `pernikahan`, `tunangan`, `bisnis`, `lainnya`)
   - `theme_slug` (string — relasi ke `themes.slug`)
   - `title` (string — judul utama undangan)
   - `event_data` (JSON — menyimpan data terstruktur: `greeting` / salam pembuka, `quote` / doa atau deskripsi acara, `primary_name` & `secondary_name` beserta gelar & nama orang tua/jabatan & Instagram, `session_1` [title, date, time, venue, address, maps_url], `session_2` [opsional: title, date, time, venue, address, maps_url], `rundown` [array judul & waktu/kisah opsional], `cover_image`, `gallery_images` [array max 4 foto], `gift_accounts` [array bank/ewallet, account_number, account_name], `music_mode` [`none` / `ambient` / `custom`], `music_file` [opsional])
   - `timestamps`
4. **`invitation_wishes`:**
   - `id`, `invitation_id` (foreignId `constrained()->cascadeOnDelete()`), `guest_name`, `attendance` (`hadir` / `tidak_hadir`), `message`, `timestamps`.
5. **Seeder (`DatabaseSeeder`):**
   - Buat 1 akun Admin: Email `admin@undangan.test`, Password `password123`.
   - Isi 5 tema awal ke tabel `themes`: `jawa`, `sunda`, `palembang`, `bisnis-mewah`, dan `bisnis-simple`.

---

# 2. ALUR PENGGUNA PUBLIK & PEMBUAT UNDANGAN

## A. Landing Page & Multi-Step Builder (`/`)
- Hero section ringkas, keunggulan platform, dan tombol CTA langsung ke Builder.
- Form bertahap berbasis **Alpine.js** (dengan indikator langkah yang jelas, validasi input yang ramah pengguna, serta mempertahankan `old()` input jika terjadi error validasi):
  - **Langkah 1 — Kategori & Tema:** Pilih kategori (`Pernikahan`, `Tunangan`, `Bisnis`, `Lainnya`). Kartu tema aktif dari database otomatis terfilter sesuai kategori dan menampilkan pratinjau palet warna serta deskripsi gaya.
  - **Langkah 2 — Profil & Sambutan:** Input dinamis menyesuaikan kategori:
    - *Pernikahan / Tunangan:* Nama Panggilan & Lengkap Mempelai 1 & 2, Nama Orang Tua masing-masing, Instagram opsional, Salam Pembuka & Kutipan/Doa.
    - *Bisnis / Lainnya:* Nama Acara/Perusahaan, Nama Penyelenggara/Pembicara Utama, Jabatan/Instansi, Dress Code, Deskripsi & Agenda Singkat.
  - **Langkah 3 — Waktu & Lokasi Acara:** Input Sesi 1 (Akad Nikah / Acara Utama: Tanggal, Jam Mulai–Selesai, Nama Gedung/Tempat, Alamat Lengkap, Link Google Maps) + tombol toggle opsional untuk menambah Sesi 2 (Resepsi / Gala Dinner / Ramah Tamah).
  - **Langkah 4 — Galeri, Kado Digital & Musik (Opsional):**
    - Upload Foto Sampul & maksimal 4 Foto Galeri (`image|mimes:jpg,jpeg,png,webp|max:2048` disimpan ke `storage/app/public/invitations`).
    - Tambah/hapus dinamis rekening Amplop Digital / Kado (Nama Bank/E-Wallet, No. Rekening, Atas Nama).
    - Pilihan Backsound (`Tanpa Musik`, `Melodi Ambient Bawaan`, atau `Upload MP3 Sendiri` max 5MB).

## B. Halaman Sukses & Generator Link Tamu (`/sukses/{slug}`)
- Menampilkan **Link Undangan Publik** (`{{ url('/u/' . $invitation->slug) }}`).
- **Generator Nama Tamu & Teks WhatsApp Instan:** Input nama tamu (contoh: `Nour Haqiki`) yang secara *real-time* menghasilkan link personal `?to=Nour+Haqiki` beserta template pesan pengantar WhatsApp formal yang rapi. Tersedia tombol **1-Klik Salin Link**, **Salin Teks Pengantar**, **Kirim Langsung via WhatsApp (`https://wa.me/?text=...`)**, dan **Buka Undangan**.
- **Kotak Tautan Kelola Mandiri:** Menampilkan URL rahasia `/kelola/{slug}/{edit_token}` dengan tombol salin agar pembuat undangan dapat mengedit data undangan atau melihat rekap RSVP kapan saja tanpa login.

## C. Halaman Edit Mandiri Pembuat (`/kelola/{slug}/{edit_token}`)
- Memvalidasi kecocokan `slug` dan `edit_token` (404 jika tidak valid).
- Menampilkan form edit seluruh data undangan (termasuk ganti tema atau tambah/hapus foto) serta daftar ucapan & rekap jumlah tamu yang konfirmasi **Hadir** vs **Tidak Hadir**.

## D. Halaman Publik Undangan (`/u/{slug}` — Standar UX Invitos)
Setiap tema wajib menggunakan engine interaktif bersama (`themes/partials/engine.blade.php`) yang menyediakan:
1. **Layar Sampul Terkunci (*Cover Screen*):** Fullscreen (`h-dvh overflow-hidden fixed inset-0 z-50`) sebelum dibuka. Menampilkan judul acara, nama utama, kotak sapaan personal dari query `?to=` (*"Kepada Yth. Bapak/Ibu/Saudara/i [Nama Tamu]"*), dan tombol **"Buka Undangan"**.
2. **Transisi Buka & Floating Controls:**
   - Klik **"Buka Undangan"** mengangkat sampul dengan animasi halus, melepas kunci *scroll*, dan memutar musik latar (jika mode `custom` MP3 atau `ambient` Web Audio synth aktif).
   - **Floating Music Disc Button** (pojok kanan atas) berputar saat musik aktif dan dapat diklik untuk Play/Pause.
   - **Floating Bottom Navigation Bar** (di layar mobile/tablet, melayang rapi di bawah) dengan tombol lompat halus (*smooth scroll*) ke bagian: *Sampul, Profil, Acara, Galeri, Kado, Ucapan*.
3. **Scroll-Reveal Animations:** Gunakan `IntersectionObserver` ringan agar setiap kartu bagian muncul lembut (*fade-up*) saat digulir tamu.
4. **Hero, Countdown Timer & Simpan Kalender:**
   - Foto sampul (atau bingkai ornamen SVG jika user tidak mengunggah foto), nama utama, tanggal acara, **Countdown Timer** *real-time* (Hari, Jam, Menit, Detik), serta tombol **"Simpan ke Google Calendar"**.
5. **Profil, Jadwal Dual-Sesi & Google Maps:**
   - Salam pembuka, profil mempelai/pembicara + tautan Instagram, kartu jadwal Sesi 1 & Sesi 2 dengan tombol **"Buka Google Maps"**.
6. **Galeri Lightbox & Amplop Digital:**
   - Grid galeri responsif dengan modal *lightbox* saat foto diklik, serta kartu **Amplop Digital** dengan tombol **1-Klik Salin Nomor Rekening** (notifikasi *"Nomor Rekening Tersalin!"*).
7. **RSVP & Buku Tamu Live (AJAX Tanpa Reload):**
   - Kolom nama tamu otomatis terisi dari `?to=`, pilihan kehadiran (*Hadir / Tidak Hadir*), dan kolom ucapan.
   - Submit menggunakan `fetch()` POST ke endpoint ucapan sehingga ucapan langsung ditambahkan ke daftar teratas secara *real-time* tanpa me-reload halaman maupun menutup ulang sampul.

---

# 3. PANEL ADMINISTRATOR (`/admin`)

Dilindungi middleware `auth` dengan tampilan bersih dan responsif di HP maupun Desktop:
1. **Login Admin (`/admin/login`):** Halaman login khusus admin (tanpa link register, dilengkapi pesan error validasi).
2. **Dashboard (`/admin`):** Kartu statistik (Total Undangan, Total Tamu Hadir, Total Ucapan, Total Tema Aktif) + daftar undangan terbaru.
3. **Manajemen Undangan & Ucapan (`/admin/invitations`):**
   - Pencarian berdasarkan judul/slug dan filter berdasarkan kategori.
   - Tombol **Lihat Live (`/u/{slug}`)**, tombol **Lihat & Moderasi Ucapan** (admin dapat menghapus ucapan spam/tidak pantas), dan tombol **Hapus Undangan** (menghapus record database beserta seluruh file gambar/audio terkait di `storage`).
4. **Manajemen Tema Extensible (`/admin/themes`):**
   - Daftar tema dengan tombol toggle **Aktif / Nonaktif** instan.
   - **Form Tambah Tema Baru:** Input *Nama Tema*, *Slug Nama File* (contoh: `minang`), *Kategori*, *Warna Aksen (Hex)*, *Deskripsi*, serta pilihan **Kloning Struktur Dari Tema** (`_base-template` atau salah satu tema yang sudah ada).
   - Saat disimpan, controller otomatis menyalin file Blade sumber menjadi `resources/views/themes/{slug}.blade.php` sehingga tema baru langsung bisa digunakan tanpa error dan bebas dikustomisasi desainnya kapan saja.

---

# 4. ARSITEKTUR TEMA DRY & 5 TEMA AWAL (`resources/views/themes/`)

Buat struktur file tema yang rapi agar mudah dikembangkan:
- `resources/views/themes/partials/engine.blade.php` (berisi seluruh Alpine.js state & logic untuk Cover Lock, Audio Player/Synth, Countdown, Lightbox, Copy Rekening, AJAX RSVP, dan Floating Nav).
- `resources/views/themes/_base-template.blade.php` (kerangka dasar bersih yang memanggil `@include('themes.partials.engine')`).
- **5 Tema Awal dengan Ornamen SVG Ringan & Google Fonts Serasi:**
  1. **`jawa.blade.php` (Adat Jawa):** Cokelat sogan (`#3E2723`), krem gading (`#FAF3E0`), aksen emas (`#D4AF37`), font `Playfair Display` & `Lora`, ornamen gunungan/batik minimalis.
  2. **`sunda.blade.php` (Adat Sunda):** Hijau zamrud lembut (`#1B4332`), putih gading (`#F8F9FA`), aksen emas hangat (`#C59B27`), ornamen sulur/kujang minimalis.
  3. **`palembang.blade.php` (Adat Palembang):** Merah marun (`#6B0F1A`), hitam elegan (`#18181B`), aksen emas songket (`#E0A938`), ornamen geometris songket Sriwijaya.
  4. **`bisnis-mewah.blade.php` (Bisnis Luxury / Eksekutif):** Obsidian gelap (`#0F172A`), aksen emas sampanye (`#D4AF37`), tata letak formal VIP untuk gala dinner, peluncuran produk, atau rapat eksklusif.
  5. **`bisnis-simple.blade.php` (Bisnis Simple & Sederhana):** Putih bersih (`#FFFFFF`), abu-abu slate (`#1E293B`), aksen biru modern (`#2563EB`), tipografi `Plus Jakarta Sans` & `Inter`.

---

# URUTAN EKSEKUSI & VERIFIKASI
Kerjakan secara berurutan dan pastikan setiap tahap berjalan tanpa error di Laravel 10:
1. Buat migrasi (`themes`, `invitations`, `invitation_wishes`), Model (`Theme`, `Invitation` dengan `protected $casts`, `InvitationWish`), dan `DatabaseSeeder`. Jalankan `php artisan migrate --seed` dan `php artisan storage:link`.
2. Buat `InvitationController` (landing builder, store, success, edit/update via `edit_token`, public show, dan storeWish AJAX) serta `AdminController` (auth, dashboard, kelola undangan + moderasi ucapan, kelola tema + auto-clone file Blade).
3. Daftarkan seluruh route publik dan group `/admin` di `routes/web.php`.
4. Bangun tampilan publik (`welcome.blade.php`, `success.blade.php`, `manage.blade.php`), halaman admin (`admin/login.blade.php`, `admin/dashboard.blade.php`, `admin/invitations.blade.php`, `admin/themes.blade.php`), serta `themes/partials/engine.blade.php`, `_base-template.blade.php`, dan ke-5 file tema.
5. Jalankan `npm run build` dan lakukan pengecekan menyeluruh terhadap seluruh route, sintaks PHP/Blade, serta responsivitas tampilan.