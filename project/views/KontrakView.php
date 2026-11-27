<?php // Membuka blok kode PHP
interface KontrakView { // Mendefinisikan interface kontrak Strict Passive View untuk modul kandidat
    public function renderDaftar(string $tableRowsHtml, int $totalData): string; // Kontrak method untuk merender halaman tabel daftar kandidat dari baris HTML dan total data siap pakai
    public function renderForm(string $title, string $action, array $formData = []): string; // Kontrak method untuk merender halaman form tambah atau ubah kandidat dengan data prefill opsional
} // Menutup definisi interface KontrakView
// Akhir file KontrakView.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)