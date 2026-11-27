<?php // Membuka blok kode PHP
require_once __DIR__ . '/KontrakViewLowongan.php'; // Memuat interface KontrakViewLowongan (tanpa memuat Model atau Entity apa pun)

class ViewLowongan implements KontrakViewLowongan { // Mendefinisikan kelas ViewLowongan pasif yang mengimplementasikan KontrakViewLowongan
    private string $skinPath; // Mendeklarasikan properti privat untuk menyimpan path file template daftar lowongan
    private string $formPath; // Mendeklarasikan properti privat untuk menyimpan path file template form lowongan

    public function __construct(string $skinPath = '', string $formPath = '') { // Mendefinisikan konstruktor dengan path template opsional
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_lowongan.html'; // Menetapkan path bawaan template daftar lowongan jika parameter kosong
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_lowongan.html'; // Menetapkan path bawaan template form lowongan jika parameter kosong
    } // Menutup konstruktor kelas

    public function renderDaftar(string $tableRowsHtml, int $totalData): string { // Mendefinisikan method untuk merender tampilan tabel daftar lowongan
        $template = file_get_contents($this->skinPath); // Membaca seluruh isi file template HTML skin_lowongan.html sebagai string
        $tokens = [ // Menyiapkan pemetaan antara placeholder template dan nilai penggantinya
            '{{TABLE_ROWS}}' => $tableRowsHtml, // Menyisipkan string baris tabel <tr>...</tr> siap pakai dari Presenter
            '{{TOTAL_DATA}}' => (string)$totalData, // Menyisipkan angka total data lowongan yang sudah dihitung oleh Presenter
        ]; // Menutup deklarasi array $tokens
        return str_replace(array_keys($tokens), array_values($tokens), $template); // Mengganti seluruh token di template dan mengembalikan HTML hasil akhir
    } // Menutup method renderDaftar

    public function renderForm( // Memulai definisi method untuk merender tampilan form tambah atau ubah lowongan
        string $title, // Parameter judul form
        string $action, // Parameter aksi form ('tambah' atau 'ubah')
        array $formData = [], // Parameter array nilai prefill input form
        string $statusOptionsHtml = '', // Parameter string HTML <option> status dari Presenter
        string $kandidatOptionsHtml = '' // Parameter string HTML <option> kandidat dari Presenter
    ): string { // Membuka badan method renderForm
        $template = file_get_contents($this->formPath); // Membaca seluruh isi file template HTML form_lowongan.html sebagai string
        $tokens = [ // Menyiapkan array pasangan placeholder form dan nilainya
            '{{FORM_TITLE}}'       => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan judul form
            '{{ACTION}}'           => htmlspecialchars($action, ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai aksi POST
            '{{ID}}'               => htmlspecialchars((string)($formData['id'] ?? ''), ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan ID lowongan (kosong jika mode tambah)
            '{{JUDUL_LOWONGAN}}'   => htmlspecialchars($formData['judulLowongan'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill judul lowongan
            '{{PERUSAHAAN}}'       => htmlspecialchars($formData['perusahaan'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill nama perusahaan
            '{{PIC_REKRUTER}}'     => htmlspecialchars($formData['picRekruter'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill nama PIC rekruter
            '{{GAJI_JUTA}}'        => htmlspecialchars((string)($formData['gajiJuta'] ?? 0), ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill gaji dalam satuan juta
            '{{STATUS_OPTIONS}}'   => $statusOptionsHtml, // Menyisipkan tag <option> status siap pakai dari Presenter (sudah di-escape di Presenter)
            '{{KANDIDAT_OPTIONS}}' => $kandidatOptionsHtml, // Menyisipkan tag <option> kandidat siap pakai dari Presenter (sudah di-escape di Presenter)
        ]; // Menutup deklarasi array $tokens
        return str_replace(array_keys($tokens), array_values($tokens), $template); // Mengganti seluruh placeholder pada template form dan mengembalikan HTML final
    } // Menutup method renderForm
} // Menutup definisi kelas ViewLowongan
// Akhir file ViewLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)