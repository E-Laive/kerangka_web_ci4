<div class="table-footer kit-table-footer">
    <small data-table-result role="status" aria-live="polite">Menampilkan 4 dari 4 data</small>
    <label class="kit-page-size">Baris per halaman <select class="form-select form-select-sm" data-page-size><option value="2">2</option><option value="4" selected>4</option></select></label>
    <nav class="kit-pagination" aria-label="Halaman <?= esc($title) ?>"><button type="button" class="btn btn-soft btn-sm" data-page-step="-1" disabled>Sebelumnya</button><span data-page-label aria-live="polite">1 / 1</span><button type="button" class="btn btn-soft btn-sm" data-page-step="1" disabled>Berikutnya</button></nav>
</div>
<p class="table-no-results" data-table-empty role="status" hidden>Tidak ada data yang sesuai. Ubah atau reset filter.</p>
<p class="demo-note">Sorting dan halaman berlaku pada data contoh di browser. Ekspor mencakup semua baris yang sesuai filter, termasuk di halaman lain.</p>
<details class="kit-table-options"><summary>Kolom dan contoh kondisi tabel</summary><div class="kit-column-options" data-column-options></div><label class="form-label">Kondisi contoh <select class="form-select" data-table-state><option value="ready">Data tersedia</option><option value="loading">Sedang memuat</option><option value="error">Gagal memuat</option></select></label></details>
<div class="kit-table-state" data-table-state-message role="status" hidden></div>
