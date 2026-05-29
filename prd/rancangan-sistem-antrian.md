# Rancangan Sistem Antrian Terintegrasi BPR (Web-Based)

Dokumen ini berisi spesifikasi teknis dan alur operasional untuk pengembangan Sistem Antrian Terintegrasi BPR yang mencakup 12 kantor cabang kecamatan. Sistem dirancang tanpa menggunakan layar TV antrian dan berfokus pada notifikasi audio serta efisiensi perangkat keras.

## 1. Alur Logika (Flowchart Logic)

Alur sistem dibagi menjadi dua bagian utama: **Sisi Nasabah (Kiosk)** dan **Sisi Petugas (Teller/Admin)**.

### A. Alur Nasabah (di Pintu Masuk/Kiosk)

1. **Mulai:** Nasabah mendatangi PC Kiosk (layar sentuh) di area pintu masuk.
2. **Pilih Layanan Utama:** Layar menampilkan dua kategori utama:
   * **TELLER** (Kode Prefix: A)
   * **CUSTOMER SERVICE** (Kode Prefix: B)
3. **Pilih Keperluan (Sub-Menu ENUM):**
   * *Jika memilih Teller:* Muncul opsi (Setoran Tunai, Penarikan Tunai, Pemindahbukuan).
   * *Jika memilih CS:* Muncul opsi (Buka Rekening, Pengaduan, Ganti Kartu, Lainnya).
4. **Proses & Cetak:**
   * Sistem men-*generate* nomor urut baru untuk layanan yang dipilih.
   * Printer Thermal secara otomatis mencetak struk antrian (Nomor, Jenis Layanan, Keperluan, Tanggal/Waktu).
5. **Menunggu:** Nasabah duduk di area tunggu dan mendengarkan panggilan suara.

### B. Alur Petugas (Teller & Admin)

1. **Standby:** Petugas login ke sistem melalui browser di PC masing-masing.
2. **Monitoring Real-time:** Daftar antrian dengan status `pending` muncul di *dashboard* petugas secara otomatis (tanpa perlu *refresh* halaman).
3. **Panggilan (Call):**
   * Petugas menekan tombol **"Panggil"** pada antrian teratas.
   * **Sistem:** Mengubah status dari `pending` menjadi `processing`.
   * **Output Audio:** Browser petugas menjalankan *Text-to-Speech* yang diteruskan ke *speaker* eksternal: *"Nomor Antrian, A, Kosong, Kosong, Lima. Silakan Menuju Teller."*
4. **Pelayanan:** Petugas melayani transaksi nasabah.
5. **Aksi Penyelesaian:**
   * Transaksi selesai: Petugas menekan tombol **"Selesai"** (Status berubah menjadi `finished`).
   * Nasabah tidak hadir: Petugas menekan tombol **"Lewati"** (Status berubah menjadi `skipped`).

## 2. Rancangan Arsitektur Sistem

Sistem menggunakan arsitektur **Centralized Multi-Tenant Web Application**.

* **Server Pusat (Cloud/VPS):** Menyimpan *source code* dan *database* utama. Diakses secara bersamaan oleh 12 cabang.
* **Protokol:** HTTP/HTTPS untuk akses web.
* **Komunikasi Real-time:** WebSockets (Pusher/Socket.io/Soketi) untuk memastikan data antrian baru langsung muncul di layar petugas dan memicu fungsi audio pemanggilan tanpa latensi.
* **Audio Engine:** *Web Speech API* (bawaan browser klien). Pemrosesan suara dilakukan di PC petugas, bukan di server, untuk mengurangi beban server dan memastikan audio keluar di cabang yang tepat.

## 3. Fitur Detail & Implementasi Khusus

* **Role 'Kiosk' Khusus:** Akun khusus untuk login di mesin Kiosk. Akun ini memiliki *session* yang tidak pernah kadaluarsa (*never expire*) dan antarmukanya dikunci hanya pada mode *fullscreen* penginputan antrian.
* **Silent Printing:** Implementasi pencetakan tiket tanpa kotak dialog *print preview* menggunakan ekstensi browser (contoh: Chrome `--kiosk-printing`) atau *Web API to Thermal Printer* agar pengalaman nasabah mulus.
* **Daily Auto-Reset:** Penjadwalan (*Cron Job*) di server pusat yang berjalan setiap pukul 00:00 untuk mengatur ulang nomor antrian kembali ke 0 untuk operasional hari berikutnya, tanpa menghapus data historis.
* **Laporan Kinerja (*Dashboard* Admin):** Menyajikan analitik berupa rata-rata waktu layanan per nasabah, jumlah nasabah harian per cabang, dan distribusi persentase layanan berdasarkan kolom ENUM `customer_note`.

## 4. Kebutuhan Perangkat Keras (Spesifikasi per Cabang)

Karena layar TV (Monitor Display) dihilangkan, sistem berfokus pada kualitas audio untuk memandu nasabah.

### A. Area Kiosk (Pintu Masuk)

* **1 Unit PC Layar Sentuh (*Touchscreen*):** Komputer *All-in-One* atau tablet (minimal 10 inci) dengan dudukan statis (*stand*). Terhubung ke internet cabang.
* **1 Unit Printer Thermal:** Lebar kertas 80mm atau 58mm dengan fitur *auto-cutter*. Dihubungkan ke PC Kiosk via USB atau LAN.

### B. Area Petugas (Meja Teller/CS)

* **PC/Laptop Existing:** Menggunakan komputer petugas yang sudah ada (hanya perlu membuka *tab* browser baru).
* **1 Unit Speaker Aktif Eksternal:** *Speaker* dengan daya volume yang memadai, dihubungkan langsung ke *port audio* PC Petugas dan diarahkan ke ruang tunggu nasabah.

## 5. Rancangan Database (Skema Relasional)

Berikut adalah struktur tabel yang mengimplementasikan pemisahan cabang (*Multi-Tenant*) dan batasan tipe data (*ENUM*).

### Tabel: `branches`
*(Data identitas 12 kantor cabang kecamatan)*
* `id` (INT, Primary Key)
* `nama_cabang` (VARCHAR)
* `kode_cabang` (VARCHAR)
* `alamat` (TEXT)

### Tabel: `users`
*(Manajemen akses petugas dan mesin Kiosk)*
* `id` (INT, Primary Key)
* `branch_id` (INT, Foreign Key -> branches.id)
* `username` (VARCHAR)
* `password` (VARCHAR, Hashed)
* `role` (ENUM: `'superadmin'`, `'admin'`, `'teller'`, `'kiosk'`)

### Tabel: `services`
*(Kategori layanan utama untuk membedakan nomor urut)*
* `id` (INT, Primary Key)
* `nama_layanan` (VARCHAR: "Teller", "Customer Service")
* `kode_prefix` (CHAR: "A", "B")

### Tabel: `queues`
*(Pusat data transaksi antrian)*
* `id` (BIGINT, Primary Key)
* `branch_id` (INT, Foreign Key -> branches.id)
* `service_id` (INT, Foreign Key -> services.id)
* `user_id` (INT, Foreign Key -> users.id, Nullable) - *Petugas yang melayani.*
* `queue_number` (INT) - *Angka urut (1, 2, 3...)*
* `queue_code` (VARCHAR) - *Kode lengkap (A-001)*
* `customer_note` (ENUM):
  * `'setoran_tunai'`
  * `'penarikan_tunai'`
  * `'pemindahbukuan'`
  * `'buka_rekening'`
  * `'pengaduan'`
  * `'ganti_kartu'`
  * `'lainnya'`
* `status` (ENUM):
  * `'pending'`
  * `'processing'`
  * `'finished'`
  * `'skipped'`
* `created_at` (TIMESTAMP) - *Waktu tiket diambil.*
* `called_at` (TIMESTAMP, Nullable) - *Waktu pemanggilan pertama.*
* `finished_at` (TIMESTAMP, Nullable) - *Waktu layanan selesai.*
