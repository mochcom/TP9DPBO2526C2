<?php // Membuka blok kode PHP
require_once __DIR__ . '/KontrakView.php'; // Memuat interface KontrakView (tanpa memuat kelas Model atau Entity apa pun)

class ViewKandidat implements KontrakView { // Mendefinisikan kelas ViewKandidat pasif yang mengimplementasikan KontrakView
    private string $skinPath; // Mendeklarasikan properti privat untuk menyimpan path file template halaman daftar kandidat
    private string $formPath; // Mendeklarasikan properti privat untuk menyimpan path file template halaman form kandidat

    public function __construct(string $skinPath = '', string $formPath = '') { // Mendefinisikan konstruktor dengan parameter path template opsional
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin.html'; // Menetapkan path bawaan template skin.html jika parameter $skinPath kosong
        $this->formPath = $formPath ?: __DIR__ . '/../template/form.html'; // Menetapkan path bawaan template form.html jika parameter $formPath kosong
    } // Menutup konstruktor kelas

    public function renderDaftar(string $tableRowsHtml, int $totalData): string { // Mendefinisikan method untuk merender halaman tabel daftar kandidat
        $template = file_get_contents($this->skinPath); // Membaca seluruh isi file template skin.html sebagai string

        $tokens = [ // Menyiapkan array pasangan placeholder template dan nilai penggantinya
            '{{TABLE_ROWS}}' => $tableRowsHtml, // Menyisipkan potongan string baris tabel <tr>...</tr> yang sudah dirakit Presenter
            '{{TOTAL_DATA}}' => (string)$totalData, // Menyisipkan angka total data kandidat (dikonversi ke string) yang dihitung Presenter
        ]; // Menutup deklarasi array $tokens

        return str_replace(array_keys($tokens), array_values($tokens), $template); // Mengganti seluruh placeholder pada template dan mengembalikan HTML final
    } // Menutup method renderDaftar

    public function renderForm(string $title, string $action, array $formData = []): string { // Mendefinisikan method untuk merender halaman form tambah atau ubah kandidat
        $template = file_get_contents($this->formPath); // Membaca seluruh isi file template form.html sebagai string

        $tokens = [ // Menyiapkan array pasangan placeholder form dan nilai prefill yang telah diamankan
            '{{FORM_TITLE}}'       => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan teks judul form
            '{{ACTION}}'           => htmlspecialchars($action, ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai aksi POST ('tambah' atau 'ubah')
            '{{ID}}'               => htmlspecialchars((string)($formData['id'] ?? ''), ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan ID kandidat (kosong saat mode tambah)
            '{{NAMA}}'             => htmlspecialchars($formData['nama'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill nama kandidat
            '{{POSISI}}'           => htmlspecialchars($formData['posisi'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill posisi dilamar
            '{{DOMISILI}}'         => htmlspecialchars($formData['domisili'] ?? '', ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill kota domisili
            '{{SKOR_TES}}'         => htmlspecialchars((string)($formData['skorTes'] ?? 0), ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill skor tes (default 0)
            '{{PENGALAMAN_TAHUN}}' => htmlspecialchars((string)($formData['pengalamanTahun'] ?? 0), ENT_QUOTES, 'UTF-8'), // Mengamankan dan menyisipkan nilai prefill pengalaman kerja (default 0)
        ]; // Menutup deklarasi array $tokens

        return str_replace(array_keys($tokens), array_values($tokens), $template); // Mengganti seluruh placeholder pada template form dan mengembalikan HTML final
    } // Menutup method renderForm
} // Menutup definisi kelas ViewKandidat
// Akhir file ViewKandidat.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)