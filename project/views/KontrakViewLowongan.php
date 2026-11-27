<?php // Membuka blok kode PHP
interface KontrakViewLowongan { // Mendefinisikan interface kontrak Strict Passive View khusus modul lowongan
    public function renderDaftar(string $tableRowsHtml, int $totalData): string; // Kontrak method untuk merender halaman tabel daftar lowongan dari string baris HTML dan total data
    public function renderForm( // Memulai deklarasi kontrak method untuk merender halaman form tambah atau ubah lowongan
        string $title, // Parameter judul halaman form yang ditampilkan di tag h1
        string $action, // Parameter nilai aksi POST ('tambah' atau 'ubah')
        array $formData = [], // Parameter array data sederhana untuk mengisi nilai awal (prefill) input form
        string $statusOptionsHtml = '', // Parameter potongan HTML <option> status yang sudah dirakit oleh Presenter
        string $kandidatOptionsHtml = '' // Parameter potongan HTML <option> daftar kandidat yang sudah dirakit oleh Presenter
    ): string; // Menutup deklarasi kontrak renderForm yang mengembalikan string HTML lengkap
} // Menutup definisi interface KontrakViewLowongan
// Akhir file KontrakViewLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)