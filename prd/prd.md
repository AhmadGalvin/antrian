# Product Requirements Document (PRD) - Sistem Antrian Terintegrasi BPR (Web-Based)

Dokumen ini berisi spesifikasi teknis, alur operasional, dan arsitektur pengembangan Sistem Antrian Terintegrasi PT. BPR BKK WONOGIRI (Perseroda). Sistem dirancang modern menggunakan arsitektur web terpusat dengan dukungan penuh untuk Kiosk (Pencetakan Tiket), Dashboard Petugas, dan Layar Display TV yang dilengkapi *Slideshow Media* serta *Synchronized Text-to-Speech (TTS)* dengan kemampuan *Audio Ducking*.

## 1. Alur Logika (Flowchart Logic)

Sistem membagi akses menjadi tiga titik utama: **Kiosk (Nasabah)**, **Petugas (Operator)**, dan **Layar Display (TV)**.

### A. Alur Nasabah (Kiosk Akses Publik)
1. **Mulai:** Nasabah mendatangi PC Kiosk layar sentuh di pintu masuk. Akses ini bersifat publik (tanpa *password/login*).
2. **Pemilihan Layanan:**
   * Jika cabang memiliki Admin (`has_admin = true`), Kiosk menampilkan 3 pilihan: **Teller**, **Customer Service**, dan **Administrasi**.
   * Jika cabang tidak memiliki Admin, Kiosk hanya menampilkan 2 pilihan: **Teller** dan **Customer Service** (layanan Administrasi secara otomatis dialihkan dan ditangani oleh CS).
3. **Pilih Keperluan (Sub-Menu):** Nasabah memilih keperluan spesifik (misal: Setoran Tunai, Buka Rekening, dll).
4. **Cetak Tiket:** Sistem menghasilkan nomor urut dengan *prefix* otomatis (contoh: T-001 untuk Teller, CS-001 untuk Customer Service). Kiosk langsung mencetak tiket via *printer thermal* secara *silent print* tanpa kotak dialog.
5. **Menunggu:** Nasabah duduk menunggu sambil melihat Layar Display TV yang menampilkan *slideshow* media (gambar/video) dan panggilan nomor antrean berjalan.

### B. Alur Petugas (Superadmin, Admin Cabang, Teller, CS)
1. **Standby:** Petugas masuk melalui panel *login* menggunakan akun yang sesuai (*role-based access*).
2. **Monitoring Real-Time:** Dashboard petugas otomatis memperbarui daftar tunggu (status `pending`) tanpa perlu *refresh* menggunakan AJAX *polling*.
3. **Proses Panggilan (Call & Recall):**
   * Petugas menekan **"Panggil Antrean"** (*Call Next*). Status berubah menjadi `in_process`.
   * Sistem mengirim instruksi panggilan ke Layar Display TV. Layar TV akan mengeksekusi suara *Text-to-Speech* secara sinkron.
   * Petugas dapat memanggil ulang (*Recall*) jika nasabah belum mendekat (jumlah *recall* tercatat dalam `recall_count`).
4. **Penyelesaian Transaksi:**
   * Jika selesai dilayani, tekan **"Selesai"** (status `finished`).
   * Jika nasabah tidak hadir setelah beberapa panggilan, tekan **"Lewati"** (status `skipped`).

### C. Alur Layar Display (Akses Publik TV)
1. **Tampilan Cerdas:** Layar TV menampilkan antrean yang sedang dilayani saat ini (termasuk nomor loket/meja). Layar memutar *playlist* media promosi (video/gambar) yang diunggah oleh Superadmin. Terdapat juga *Running Text* di bagian bawah layar.
2. **Synchronized Queue Calling:** Jika ada beberapa petugas menekan tombol panggil bersamaan, Layar TV menggunakan antrean memori (*Queue System*) sehingga suara panggilan tidak tumpang tindih. Suara akan dibaca bergantian dengan jeda 1 detik.
3. **Audio Ducking:** Saat *Text-to-Speech* memanggil nomor antrean, volume video di layar TV akan otomatis ditekan (*ducking*) turun menjadi 15% agar suara panggilan terdengar sangat jelas. Volume video kembali 100% saat semua panggilan antrean selesai.

## 2. Rancangan Arsitektur Sistem

Menggunakan arsitektur **Centralized Multi-Tenant Web Application**.

* **Tech Stack:** Laravel 11, PHP 8.2+, MySQL, Alpine.js, Tailwind CSS (Vanilla CSS untuk desain kustom yang dinamis).
* **Server Pusat (Cloud/VPS):** Seluruh kode aplikasi dan *database* berjalan pada satu peladen terpusat. Dipergunakan bersama oleh berbagai kantor cabang.
* **Public Endpoints (Kiosk & Display):** Mesin di cabang tidak perlu memelihara akun pengguna (*user management*). Cukup memilih cabang dari daftar publik, dan sistem langsung terkunci pada cabang tersebut secara *passwordless*.
* **Audio Engine (Client-Side TTS):** Suara diproduksi murni melalui *Web Speech API* di sisi *browser* Layar Display TV, menghilangkan beban pemrosesan *server*.

## 3. Fitur Detail & Implementasi Khusus

1. **Role-Based Access Control (RBAC):** `superadmin` (pengelola seluruh cabang, media, user), `admin` (opsional di cabang tertentu), `teller`, dan `cs`.
2. **Media & Playlist Management:** Superadmin dapat mengunggah hingga puluhan gambar/video yang akan diputar otomatis pada Layar TV setiap cabang secara bergantian berdasarkan durasi yang ditentukan. Algoritma deteksi otomatis *onended* menjamin video tidak akan terpotong walau melebihi durasi rata-rata *slideshow*.
3. **Auto-Route Services:** Mengantisipasi keterbatasan staf cabang. Layanan "Administrasi" fleksibel dipisahkan atau digabung ke meja CS sesuai konfigurasi (kolom boolean `has_admin` pada cabang).
4. **Tampilan Premium UI/UX:** Sistem menggunakan palet warna korporat mewah (*glassmorphism*, gradasi warna biru emas, *micro-animations* CSS) memberikan kesan bank nasional kelas atas. Hirarki penamaan dipastikan seragam di semua platform dengan standar utama "PT. BPR BKK WONOGIRI (Perseroda)".

## 4. Kebutuhan Perangkat Keras (Rekomendasi per Cabang)

### A. Area Kiosk (Pintu Masuk)
* **1 Unit Mesin Kiosk Layar Sentuh:** Tablet atau PC *All-in-One* (minimal 10 inci) terhubung internet.
* **1 Unit Printer Thermal (USB/LAN):** Printer nota (58mm atau 80mm) yang mendukung *auto-cutter*.

### B. Area Display TV (Ruang Tunggu)
* **1 Unit Smart TV / Monitor TV Besar:** Menggantung menghadap nasabah.
* **1 Unit PC Mini / Android Box:** Terhubung internet, dicolokkan ke TV via HDMI. Menjalankan *browser* (Chrome/Edge) *fullscreen* di halaman `/display/{branch_id}`.
* **1 Set Speaker Eksternal:** Tersambung ke PC Mini Display untuk memancarkan suara panggilan *Text-to-Speech* agar terdengar lantang ke seluruh ruangan.

### C. Area Petugas (Meja Teller / CS)
* **PC / Laptop Existing:** Cukup menggunakan PC yang sudah dipakai sehari-hari untuk operasional perbankan (berbekal koneksi internet dan peramban web modern).

## 5. Rancangan Database (Skema Relasional Saat Ini)

Berikut adalah skema tabel inti (*Multi-Tenant Architecture*):

### Tabel: `branches`
Menyimpan data fisik cabang kantor BPR BKK.
* `id` (BIGINT, PK)
* `code` (VARCHAR) - Kode cabang (BPR-KEC-01)
* `name` (VARCHAR) - Nama cabang
* `address` (TEXT, Nullable)
* `running_text` (TEXT, Nullable) - Teks berjalan khusus untuk TV cabang
* `has_admin` (BOOLEAN) - Status ketersediaan petugas *admin* di cabang tersebut
* `is_active` (BOOLEAN) - Status operasional

### Tabel: `users`
Mencatat akun petugas manusia yang memiliki akses sistem ke dasbor.
* `id` (BIGINT, PK)
* `branch_id` (BIGINT, FK -> branches.id)
* `name`, `username`, `password`
* `role` (ENUM: `'superadmin'`, `'admin'`, `'teller'`, `'cs'`)
* `counter_number` (TINYINT, Nullable) - Angka penempatan loket/meja (Loket 1, Loket 2, dll).

### Tabel: `branch_media`
Penyimpanan *playlist* konten layar TV tiap cabang.
* `id` (BIGINT, PK)
* `branch_id` (BIGINT, FK -> branches.id)
* `type` (ENUM: `'image'`, `'video'`)
* `title` (VARCHAR)
* `file_path` (VARCHAR)
* `duration_seconds` (INT)
* `order_index` (INT) - Urutan putar
* `is_active` (BOOLEAN)

### Tabel: `queues`
Data utama seluruh aktivitas transaksi antrean (dilengkapi indeks performa per peruntukan status).
* `id` (BIGINT, PK)
* `branch_id` (BIGINT, FK -> branches.id)
* `queue_number` (VARCHAR) - Contoh: `T-001`, `CS-004`
* `service_type` (ENUM: `'teller'`, `'cs'`, `'admin'`) - Indikasi loket yang dituju
* `customer_note` (VARCHAR) - Rincian tujuan (setoran tunai, buka rekening, dll)
* `status` (ENUM: `'pending'`, `'in_process'`, `'finished'`, `'skipped'`)
* `counter_number` (TINYINT, Nullable) - Mencatat loket yang memanggil
* `served_by` (BIGINT, FK -> users.id, Nullable) - Petugas yang menekan panggilan
* `recall_count` (INT, Default: 0) - Riwayat seberapa sering tiket ini dipanggil ulang
* `called_at`, `finished_at`, `created_at` (TIMESTAMP)
