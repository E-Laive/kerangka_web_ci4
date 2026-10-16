<section class="content-grid">
<article class="content-card welcome-card">
<div class="card-heading"><span><small>KATALOG ANTARMUKA</small><h2>Selamat datang, <?= esc($user['name']) ?></h2></span></div>
<p>Jelajahi komponen dan pola halaman untuk menyusun aplikasi SIPROSIR yang konsisten. Semua data pada katalog ditandai sebagai contoh.</p>
<div class="catalog-overview"><span><strong><?= config(\Config\TemplateCatalog::class)->pageCount() ?></strong> halaman komponen</span><span>Tema terang, gelap, dan sistem</span><span>Excel dan PDF siap dicoba</span></div>
<div class="feature-list"><div><i class="bi bi-table"></i><span><strong>Tabel dan ekspor</strong><small>Uji pencarian, filter, dan unduh data contoh dengan format angka yang dapat dihitung.</small></span></div><div><i class="bi bi-palette"></i><span><strong>Tampilan yang konsisten</strong><small>Warna, tipografi, dan fokus mengikuti tema bersama.</small></span></div><div><i class="bi bi-keyboard"></i><span><strong>Interaksi yang dapat diakses</strong><small>Jelajahi navigasi, form, dan dialog dengan keyboard.</small></span></div></div>
</article>
<aside class="content-card quick-card"><div class="card-heading"><span><small>MULAI DARI SINI</small><h2>Katalog Utama</h2></span></div>
<a class="quick-link spa-link" href="<?= site_url('katalog/tabel-data') ?>"><i class="bi bi-table"></i><span><strong>Tabel Data</strong><small>Filter, seleksi, Excel, dan PDF</small></span><i class="bi bi-arrow-right"></i></a>
<a class="quick-link spa-link" href="<?= site_url('katalog/button-badge') ?>"><i class="bi bi-ui-radios-grid"></i><span><strong>Button & Badge</strong><small>Variasi aksi dan status</small></span><i class="bi bi-arrow-right"></i></a>
<a class="quick-link spa-link" href="<?= site_url('katalog/chart') ?>"><i class="bi bi-graph-up-arrow"></i><span><strong>Koleksi Chart</strong><small>Grafik dan alternatif data</small></span><i class="bi bi-arrow-right"></i></a>
<a class="quick-link spa-link" href="<?= site_url('katalog/feedback') ?>"><i class="bi bi-chat-square-dots"></i><span><strong>Feedback</strong><small>Notifikasi dan konfirmasi</small></span><i class="bi bi-arrow-right"></i></a>
</aside></section>
