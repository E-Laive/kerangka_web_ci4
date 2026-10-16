# Kerangka Login dan Katalog Template SPA

Starter mandiri berbasis CodeIgniter 4.7.5 dengan login database, session,
dashboard SPA, sidebar responsif, serta katalog komponen tema terang dan gelap.
Project ini tidak memakai database maupun runtime aplikasi SIPROSIR utama.

## Kebutuhan

- Untuk lockfile saat ini: PHP 8.3+ 64-bit, dengan `intl`, `mbstring`, `mysqli`,
  `dom`, `gd`, `xml`, `xmlreader`, `xmlwriter`, `zip`, dan `zlib`.
- Composer 2.

## Menjalankan Pertama Kali

```powershell
composer install
php spark migrate
php spark app:provision-admin
```

Buka `https://kerangkaweb.test/`. Nginx diarahkan ke `public/` dengan TLS domain yang valid.
Migration memakai credential deployment terpisah dari koneksi web. Administrator
pertama dibuat melalui CLI interaktif dengan password tersembunyi; setup web ditutup.
Tidak ada username/password bawaan. Lihat [panduan deployment](deploy/SECURITY.md).

## Halaman

- `/setup`: informasi provisioning CLI; GET/POST ditolak 403.
- `/login`: autentikasi akun database.
- `/dashboard`: dashboard utama.
- `/katalog/{slug}`: halaman katalog berdasarkan konfigurasi
  `app/Config/TemplateCatalog.php`.

Katalog dikelompokkan menjadi:

- Fondasi: warna/tema, tipografi, ikon, spacing, dan grid.
- Komponen: button, badge, card, media, progress, spinner, dan skeleton.
- Form: input, select, choice, upload, validasi, dan kondisi field.
- Data: tabel, filter, list, timeline, dan detail data.
- Chart: line, area, bar, doughnut, dan mini chart berbasis SVG/CSS lokal.
- Navigasi: breadcrumb, tabs, dropdown, dan pagination.
- Feedback: alert, toast lokal kanan atas, dialog konfirmasi Bootstrap,
  modal, tooltip, loading, dan empty state.
- Template Halaman: dashboard, daftar/CRUD, form, detail, dan pengaturan.

Setiap tabel contoh berisi empat baris data. Seluruh komponen menggunakan token
tema bersama dan tidak bergantung pada CDN saat runtime.

Tipografi memakai Inter Variable lokal. Aturan berada di `public/assets/css/app.css`:
judul halaman 24 px, judul bagian/modal 18 px, judul komponen/pengantar 16 px,
isi 14 px, label 13 px, dan keterangan 12 px (semua didefinisikan dalam rem).
Ketebalan dibatasi ke 400/500/600/700; komponen menggunakan token regular,
medium, semibold, dan bold. Angka tabel, ringkasan, dan label grafik menggunakan
tabular numerals. Contoh aktif tersedia di `/katalog/tipografi-ikon`.

Menu Feedback memiliki Galeri Modal: kecil (konfirmasi produk), sedang (form
kategori dengan validasi), dan besar (detail transaksi dengan empat item).
Semua contoh memakai backdrop statis dan mengikuti tema. Modal besar menjadi
layar penuh pada ponsel; form kategori mempertahankan isian saat ditutup dan
tidak ditutup oleh Escape. Aksi simpan/nonaktifkan hanya simulasi tanpa perubahan
database.

Dashboard memakai Fetch API dan History API. Link tetap dapat dibuka langsung
atau dimuat ulang karena setiap halaman juga memiliki route server normal.

Menu akun pada topbar menampilkan identitas, role, status sesi, konteks aplikasi,
akses ke modal Pengaturan Tampilan, serta logout POST ber-CSRF. Modal menyediakan
mode Ikuti Sistem dan Kustom. Mode Kustom mengatur sidebar, topbar, konten, dan
footer secara independen; Reset ke Sistem mengembalikan seluruh region ke tema
Windows/browser. Apply menyimpan perubahan, sedangkan Batal tidak mengubah tema
aktif.

Tombol Semua Terang dan Semua Gelap pada Pengaturan Tampilan memilih mode Kustom
dan mengisi keempat pilihan region sekaligus. Perubahan tetap berupa pilihan
sementara hingga Terapkan ditekan; Batal membuang perubahan tersebut. Pilihan
masing-masing region masih dapat disesuaikan setelah menggunakan tombol ini.

Konfigurasi tersimpan sebagai satu object versi-1 pada key lokal
`kerangka-theme-config-v1`. `theme-init.js` memigrasikan key tema lama,
menerapkan konfigurasi sebelum render, dan merespons perubahan preferensi warna
sistem ketika mode Ikuti Sistem aktif. Style akun diisolasi dengan namespace
`.profile-dropdown` agar contoh dropdown katalog tidak menimpa shell.

## Database dan keamanan

Database aplikasi adalah `dbkerangka`. Koneksi web memakai akun `kerangka_app`
dengan SELECT/INSERT/UPDATE hanya pada tabel users; credential acak disimpan di
.env dengan ACL terbatas. Credential migration/DDL dipisahkan. .env.example
berisi baseline produksi tanpa credential asli.

- HTTPS, Secure/HttpOnly/SameSite cookie, CSRF session dan CSP script self.
- Hash password Argon2id bila runtime mendukung; fallback bcrypt membatasi byte.
- Akun/status/role/versi keamanan diperiksa ulang; idle 30 menit, maksimum 8 jam.
- Permission server: Administrator penuh, Kasir katalog/dokumen/ekspor bisnis,
  Gudang katalog/ekspor produk. Pengguna/ekspor pengguna khusus Administrator.
- Login dibatasi per IP dan akun dengan file lock atomik; storage gagal menolak akses.
- Audit event ber-allowlist; password/token tidak masuk old input atau log.
- Ekspor maksimal 5.000 baris XLSX / 1.000 PDF, 10 per menit per user, 2 renderer.
- `php spark app:revoke-sessions ID` mencabut sesi; perubahan keamanan melalui model
  juga menaikkan versi sesi. `php spark app:security-check` memeriksa konfigurasi.

Baseline ditujukan untuk satu host. Tenant, CRUD/POS nyata, pagination server untuk
dataset besar, shared limiter/session dan job queue mengikuti kebutuhan modul
operasional. Template tetap berisi contoh yang dilabeli.

## Struktur Penting

```text
app/Controllers/Auth.php         kontrak HTTP login/logout; setup web ditolak
app/Services/AuthService.php     autentikasi, principal aktif, versi/timeout sesi
app/Services/SetupService.php    provisioning CLI ter-serialisasi
app/Services/PageRenderer.php    kontrak shell/fragmen AJAX bersama
app/Services/AccessPolicy.php    permission dan menu berdasarkan peran
app/Services/SecurityRateLimiter.php  limiter atomik/fail-closed
app/Services/AuditLogger.php     audit event tanpa secret
app/Services/ExportGuard.php     limit baris/rate/konkurensi ekspor
app/Commands/                   provisioning, revoke, security-check
app/Controllers/Dashboard.php    dashboard utama
app/Controllers/Showcase.php     halaman katalog dan response fragmen SPA
app/Config/TemplateCatalog.php   sumber pengelompokan sidebar dan metadata halaman
app/Filters/AuthFilter.php       proteksi route
app/Models/UserModel.php         akses tabel users
app/Views/auth/                  login dan setup
app/Views/layout/                shell, topbar, dan sidebar
app/Views/pages/catalog/         seluruh contoh komponen dan template halaman
public/assets/css/               CSS login, shell, dan katalog
public/assets/fonts/inter/       Inter Variable lokal dan lisensi OFL
public/assets/js/                login, tema, SPA, serta interaksi katalog
```

Font global menggunakan Inter Variable lokal untuk bobot `100–900`, termasuk
italic, dengan fallback `Segoe UI`, `system-ui`, dan `sans-serif`. Runtime tidak
memuat font dari CDN atau layanan eksternal.

## Pengembangan Menu Baru

1. Tambahkan metadata halaman ke `app/Config/TemplateCatalog.php`.
2. Buat view fragmen di `app/Views/pages/catalog`.
3. Gunakan komponen serta token dari `app.css` dan `catalog.css`.
4. Hindari warna tetap pada komponen agar tema terang/gelap tetap konsisten.
5. Jika menambahkan tabel contoh, sertakan tepat empat baris data realistis.

## Verifikasi

```powershell
php spark routes
php spark migrate:status
composer test
node --test tests/frontend/theme.test.cjs
node --test tests/frontend/ui-model.test.cjs
composer check-platform-reqs
composer audit --locked
```

## Ekspor Excel dan PDF

Halaman Tabel Data dan Template Daftar & CRUD menyediakan tombol Excel/PDF.
Ekspor memakai PhpSpreadsheet 5.10 dan Dompdf 3.1; versinya dikunci Composer.
Semua file berisi data contoh katalog, bukan transaksi atau akun database aktif.

- `/ekspor/produk/excel` dan `/ekspor/produk/pdf`: filter `q` dan `status`.
- `/ekspor/pelanggan/excel` dan `/ekspor/pelanggan/pdf`: seluruh pelanggan contoh.
- `/ekspor/transaksi/excel` dan `/ekspor/transaksi/pdf`: seluruh transaksi contoh.
- `/ekspor/pengguna/excel` dan `/ekspor/pengguna/pdf`: filter `q` dan `role`.

Seluruh endpoint dilindungi filter login. Format/dataset dibatasi di server;
klien tidak mengirim HTML, SQL, atau nama file bebas. File tidak dicache.
Excel menyimpan uang/kuantitas sebagai angka dan teks sebagai string eksplisit.
PDF memakai template laporan terpisah, tanpa akses resource remote.

`app/Services/CatalogData.php` adalah sumber data bersama untuk tabel dan ekspor.
`app/Services/ReportExporter.php` menangani format file. Untuk modul operasional,
pasokan dataset yang sudah dibatasi izin dan tenant ke exporter; jangan mengganti
data contoh dengan query bebas dari parameter request.

Tema Bootstrap disinkronkan dengan tema konten dan region melalui pengelola
`KerangkaTheme` yang sama. Login/setup juga memakai token global. Form pengaturan
tema tidak ditutup oleh klik backdrop; tombol Tutup/Batal tetap tersedia.

Pemeriksaan regresi backend menggunakan SQLite in-memory (`ENVIRONMENT=testing`),
termasuk validitas file, nilai numerik, filter, proteksi login, dan teks formula.

## Pola interaktif katalog

Katalog memiliki 18 halaman. Tiga tambahan berada pada Form Lanjutan, Status &
Pemulihan, dan Invoice & Struk. Komponen Bootstrap yang sudah terpasang dipakai
untuk dialog, panel samping dan accordion; tidak ada dependensi frontend baru.

| Pola | Status dan batas contoh |
|---|---|
| Login/logout | Database, principal aktif, versi/timeout sesi; setup melalui CLI |
| Pencarian menu | Pencarian lokal metadata katalog, Ctrl/Cmd K, Enter atau Tab |
| Tema | Disimpan lewat manager tema yang sama |
| Tabel | Sorting seluruh dataset contoh sebelum pagination, ukuran 2/4 baris, filter, pilihan kolom, empty/loading/error/retry |
| Seleksi pelanggan | Pilih header untuk halaman terlihat; pilihan halaman lain dipertahankan; aksi massal berupa simulasi |
| Tambah/edit produk | Daftar menuju form; validasi, draft sesi tab, ringkasan hasil; bukan CRUD database |
| Guard form | Konfirmasi saat navigasi SPA, Back, logout atau Batal jika isian berubah; browser memberi peringatan saat reload/tutup tab |
| Form lanjutan | Date/time, rentang tanggal, nominal rupiah, saran datalist, multiselect native, validasi/preview gambar lokal |
| Status & Pemulihan | Simulasi error/retry, empty/no-results, offline, sesi, akses dan konflik; tidak mengubah sesi/izin nyata |
| Invoice/struk | Sumber contoh bersama; HTML cetak dan PDF A4/58/80 mm melalui route berlogin |
| Aksi visual lainnya | Respons simulasi menyatakan bahwa tidak ada data operasional disimpan |

`ui-model.js` menyediakan transformasi murni untuk sorting/filter/pagination,
format rupiah dan batas gambar. `workspace.js` menangani pencarian, guard form,
contoh editor dan advanced input; `catalog.js` tetap menjadi pengelola tabel,
chart, notifikasi dan lifecycle SPA. Form yang perlu guard memakai
`data-dirty-form`; setelah berhasil disimpan panggil
`KerangkaWorkspace.markClean(form)`. Dialog keputusan bersama tersedia melalui
`KerangkaWorkspace.confirm({ title, message, accept, cancel })`, hasilnya boolean.
Gunakan teks, jangan memasukkan HTML dari input pengguna ke notifikasi/dialog.

`KerangkaCatalog.init(root)` dan `dispose(root)` dipanggil saat navigasi SPA.
Dispose membebaskan tooltip, modal/panel samping, timer dan URL preview gambar.
Jika menambah widget, hubungkan lifecycle-nya ke jalur yang sama.

Ekspor tabel mengikuti q/status/role dan mencakup semua hasil filter, bukan hanya
halaman yang terlihat. Sorting/pilihan kolom hanya mengubah tampilan browser.
Contoh produk yang disimpan dari editor tidak mengubah dataset ekspor. Koneksi
CRUD operasional, scope otorisasi per outlet/tenant, transaksi POS nyata dan reset password perlu
diintegrasikan secara terpisah ketika template digunakan sebagai aplikasi.

Dokumen cetak tersedia pada `/dokumen/{a4,struk58,struk80}` dan PDF pada route
yang sama dengan akhiran `/pdf`. Format dibatasi controller. Seluruh nilai berasal
dari `SampleInvoice`; jangan memasukkan HTML/SQL/nama view dari query pengguna.
Pilih ukuran kertas yang benar pada dialog cetak dan uji printer fisik sebelum
memakai struk pada outlet. PDF memakai DejaVu Sans untuk dukungan renderer;
HTML tetap Inter. Halaman error 400/403/404/500 memakai tampilan bersama tanpa
menampilkan rincian teknis exception.

## Pengerasan 15 Oktober 2026

Rencana dan bukti tersedia di build/verification. Panduan deployment/permission,
sertifikat lokal, batas file lock satu host, CSP style inline, dan risiko regenerasi
vhost Devaron dijelaskan di deploy/SECURITY.md. Katalog mempertahankan Bootstrap,
Inter dan tema; modul browser dipisah untuk guard form, draft per akun, menu,
notifikasi, tabel, field lanjutan dan kontrak HTTP/CSRF.
