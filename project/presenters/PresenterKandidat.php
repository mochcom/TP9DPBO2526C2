<?php // Membuka blok kode PHP
require_once __DIR__ . '/KontrakPresenter.php'; // Memuat interface KontrakPresenter sebagai kontrak method Presenter
require_once __DIR__ . '/../models/KontrakModel.php'; // Memuat interface KontrakModel agar Presenter bergantung pada kontrak, bukan kelas konkret
require_once __DIR__ . '/../views/KontrakView.php'; // Memuat interface KontrakView untuk komunikasi ke layer View

class PresenterKandidat implements KontrakPresenter { // Mendefinisikan kelas PresenterKandidat yang mengimplementasikan KontrakPresenter
    private KontrakModel $model; // Mendeklarasikan properti privat bertipe interface KontrakModel untuk mengakses data
    private KontrakView $view; // Mendeklarasikan properti privat bertipe interface KontrakView untuk merender tampilan

    public function __construct(KontrakModel $model, KontrakView $view) { // Mendefinisikan konstruktor dengan dependency injection Model dan View
        $this->model = $model; // Menyimpan objek Model yang diterima ke properti $this->model
        $this->view = $view; // Menyimpan objek View yang diterima ke properti $this->view
    } // Menutup konstruktor kelas

    private function buildRowHtml(int $no, Kandidat $kandidat): string { // Membuat helper privat untuk merakit potongan HTML 1 baris tabel kandidat
        $id = (int)$kandidat->getId(); // Mengambil ID kandidat dan memastikannya bertipe integer
        $nama = htmlspecialchars($kandidat->getNama(), ENT_QUOTES, 'UTF-8'); // Mengamankan string nama kandidat dari celah XSS sebelum ditampilkan ke HTML
        $posisi = htmlspecialchars($kandidat->getPosisi(), ENT_QUOTES, 'UTF-8'); // Mengamankan string posisi kandidat dari celah XSS
        $domisili = htmlspecialchars($kandidat->getDomisili(), ENT_QUOTES, 'UTF-8'); // Mengamankan string domisili kandidat dari celah XSS
        $skorTes = number_format($kandidat->getSkorTes()) . ' / 100'; // Memformat angka skor tes dengan imbuhan satuan "/ 100"
        $pengalaman = number_format($kandidat->getPengalamanTahun()) . ' Tahun'; // Memformat angka pengalaman kerja dengan imbuhan satuan "Tahun"

        $html = "<tr>"; // Membuka tag baris tabel HTML
        $html .= "<td class='col-id'>{$no}</td>"; // Menambahkan sel nomor urut baris pada tabel
        $html .= "<td><strong>{$nama}</strong></td>"; // Menambahkan sel nama kandidat dengan huruf tebal
        $html .= "<td>{$posisi}</td>"; // Menambahkan sel posisi yang dilamar kandidat
        $html .= "<td>{$domisili}</td>"; // Menambahkan sel kota domisili kandidat
        $html .= "<td>{$skorTes}</td>"; // Menambahkan sel nilai skor tes terformat
        $html .= "<td>{$pengalaman}</td>"; // Menambahkan sel pengalaman kerja terformat
        $html .= "<td class='col-actions'>"; // Membuka sel kolom aksi (Edit dan Hapus)
        $html .= "<a href='index.php?screen=edit&id={$id}' class='btn btn-edit'>Edit</a>"; // Menambahkan tombol tautan untuk membuka halaman form edit kandidat
        $html .= "<form method='post' action='index.php' style='display:inline;' onsubmit=\"return confirm('Hapus kandidat ini?')\">"; // Membuka form POST inline untuk aksi hapus dengan konfirmasi browser
        $html .= "<input type='hidden' name='action' value='hapus'>"; // Menambahkan input tersembunyi berisi aksi 'hapus' untuk dibaca oleh index.php
        $html .= "<input type='hidden' name='id' value='{$id}'>"; // Menambahkan input tersembunyi berisi ID kandidat yang akan dihapus
        $html .= "<button type='submit' class='btn btn-delete'>Hapus</button>"; // Menambahkan tombol submit untuk mengeksekusi penghapusan data
        $html .= "</form>"; // Menutup elemen form penghapusan
        $html .= "</td>"; // Menutup sel kolom aksi
        $html .= "</tr>"; // Menutup tag baris tabel HTML
        return $html; // Mengembalikan potongan string HTML satu baris tabel lengkap
    } // Menutup helper buildRowHtml

    public function tampilkanKandidat(): string { // Mendefinisikan method untuk menyiapkan dan menampilkan halaman daftar kandidat
        $listKandidat = $this->model->getAllKandidat(); // Meminta seluruh daftar objek Kandidat dari layer Model
        $totalKandidat = count($listKandidat); // Menghitung jumlah total kandidat di Presenter agar View tetap pasif

        $rowsHtml = ''; // Menginisialisasi string penampung seluruh baris HTML tabel
        if (empty($listKandidat)) { // Mengecek apakah data kandidat di database masih kosong
            $rowsHtml = '<tr><td colspan="7" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data kandidat.</td></tr>'; // Menyiapkan satu baris pesan informasi jika data kosong
        } else { // Cabang jika terdapat minimal satu data kandidat
            $no = 1; // Menginisialisasi nomor urut tampilan tabel mulai dari 1
            foreach ($listKandidat as $kandidat) { // Melakukan perulangan pada setiap objek Kandidat
                $rowsHtml .= $this->buildRowHtml($no, $kandidat); // Merakit baris HTML lewat helper lalu menggabungkannya ke $rowsHtml
                $no++; // Menambah nilai nomor urut untuk baris berikutnya
            } // Menutup blok perulangan foreach
        } // Menutup percabangan if-else

        return $this->view->renderDaftar($rowsHtml, $totalKandidat); // Mengirim string baris HTML dan total data ke View untuk dirender ke template
    } // Menutup method tampilkanKandidat

    public function tampilkanFormKandidat(?int $id = null): string { // Mendefinisikan method untuk menampilkan form tambah (id null) atau form edit (id terisi)
        if ($id !== null) { // Mengecek apakah pemanggilan ditujukan untuk mode edit data kandidat
            $kandidat = $this->model->getKandidatById($id); // Mengambil detail objek Kandidat berdasarkan ID dari layer Model
            if ($kandidat === null) { // Mengecek apakah kandidat dengan ID tersebut tidak ditemukan di database
                header("Location: index.php"); // Mengarahkan ulang browser kembali ke halaman utama daftar kandidat
                exit(); // Menghentikan eksekusi skrip segera setelah mengirim header redirect
            } // Menutup percabangan pengecekan data null

            $formData = [ // Menyiapkan array data sederhana (bukan objek Entity) untuk mengisi nilai awal form edit
                'id'              => $kandidat->getId(), // Memasukkan nilai ID kandidat ke array form
                'nama'            => $kandidat->getNama(), // Memasukkan nilai nama kandidat ke array form
                'posisi'          => $kandidat->getPosisi(), // Memasukkan nilai posisi dilamar ke array form
                'domisili'        => $kandidat->getDomisili(), // Memasukkan nilai domisili ke array form
                'skorTes'         => $kandidat->getSkorTes(), // Memasukkan nilai skor tes ke array form
                'pengalamanTahun' => $kandidat->getPengalamanTahun(), // Memasukkan nilai pengalaman kerja ke array form
            ]; // Menutup deklarasi array $formData
            return $this->view->renderForm("Ubah Data Kandidat", "ubah", $formData); // Memerintahkan View merender form mode ubah beserta data prefill
        } // Menutup blok mode edit

        return $this->view->renderForm("Tambah Kandidat Baru", "tambah", []); // Memerintahkan View merender form kosong untuk mode tambah kandidat baru
    } // Menutup method tampilkanFormKandidat

    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void { // Mendefinisikan method untuk memproses penambahan kandidat baru
        $this->model->addKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun); // Memerintahkan Model menyimpan data kandidat baru ke database
        header("Location: index.php"); // Mengarahkan ulang browser ke halaman daftar kandidat setelah berhasil menyimpan
        exit(); // Menghentikan eksekusi skrip agar tidak memproses kode lanjutan setelah redirect
    } // Menutup method tambahKandidat

    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void { // Mendefinisikan method untuk memproses perubahan data kandidat
        $this->model->updateKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun); // Memerintahkan Model memperbarui data kandidat berdasarkan ID di database
        header("Location: index.php"); // Mengarahkan ulang browser ke halaman daftar kandidat setelah berhasil memperbarui
        exit(); // Menghentikan eksekusi skrip segera setelah redirect
    } // Menutup method ubahKandidat

    public function hapusKandidat(int $id): void { // Mendefinisikan method untuk memproses penghapusan data kandidat
        $this->model->deleteKandidat($id); // Memerintahkan Model menghapus data kandidat berdasarkan ID dari database
        header("Location: index.php"); // Mengarahkan ulang browser ke halaman daftar kandidat setelah berhasil menghapus
        exit(); // Menghentikan eksekusi skrip segera setelah redirect
    } // Menutup method hapusKandidat
} // Menutup definisi kelas PresenterKandidat
// Akhir file PresenterKandidat.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)