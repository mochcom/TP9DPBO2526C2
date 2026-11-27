<?php // Membuka blok kode PHP
require_once __DIR__ . '/KontrakPresenterLowongan.php'; // Memuat interface KontrakPresenterLowongan sebagai kontrak Presenter
require_once __DIR__ . '/../models/KontrakModelLowongan.php'; // Memuat interface KontrakModelLowongan agar bergantung pada kontrak, bukan kelas konkret
require_once __DIR__ . '/../models/KontrakModel.php'; // Memuat interface KontrakModel untuk mengambil daftar kandidat pengisi dropdown relasi
require_once __DIR__ . '/../views/KontrakViewLowongan.php'; // Memuat interface KontrakViewLowongan untuk merender tampilan pasif

class PresenterLowongan implements KontrakPresenterLowongan { // Mendefinisikan kelas PresenterLowongan yang mengimplementasikan KontrakPresenterLowongan
    private KontrakModelLowongan $modelLowongan; // Mendeklarasikan properti privat bertipe interface KontrakModelLowongan untuk akses data lowongan
    private KontrakModel $modelKandidat; // Mendeklarasikan properti privat bertipe interface KontrakModel untuk mengambil opsi kandidat
    private KontrakViewLowongan $view; // Mendeklarasikan properti privat bertipe interface KontrakViewLowongan untuk merender HTML

    public function __construct( // Mendefinisikan konstruktor dengan dependency injection tiga kontrak interface
        KontrakModelLowongan $modelLowongan, // Parameter objek Model lowongan yang memenuhi KontrakModelLowongan
        KontrakModel $modelKandidat, // Parameter objek Model kandidat yang memenuhi KontrakModel
        KontrakViewLowongan $view // Parameter objek View lowongan yang memenuhi KontrakViewLowongan
    ) { // Membuka badan konstruktor
        $this->modelLowongan = $modelLowongan; // Menyimpan objek Model lowongan ke properti kelas
        $this->modelKandidat = $modelKandidat; // Menyimpan objek Model kandidat ke properti kelas
        $this->view = $view; // Menyimpan objek View lowongan ke properti kelas
    } // Menutup badan konstruktor

    private function buildRowHtml(int $no, Lowongan $lowongan): string { // Membuat helper privat untuk merakit 1 baris HTML tabel lowongan
        $id = (int)$lowongan->getId(); // Mengambil ID lowongan dan memastikannya bertipe integer
        $judul = htmlspecialchars($lowongan->getJudulLowongan(), ENT_QUOTES, 'UTF-8'); // Mengamankan string judul lowongan dari celah XSS
        $perusahaan = htmlspecialchars($lowongan->getPerusahaan(), ENT_QUOTES, 'UTF-8'); // Mengamankan string nama perusahaan dari celah XSS
        $pic = htmlspecialchars($lowongan->getPicRekruter(), ENT_QUOTES, 'UTF-8'); // Mengamankan string nama PIC rekruter dari celah XSS
        $gaji = 'Rp ' . number_format($lowongan->getGajiJuta()) . ' Juta'; // Memformat angka gaji menjadi teks mata uang yang mudah dibaca
        $statusAsli = $lowongan->getStatus(); // Mengambil nilai teks status lowongan dari objek entitas
        $statusAman = htmlspecialchars($statusAsli, ENT_QUOTES, 'UTF-8'); // Mengamankan teks status dari celah XSS sebelum dirender
        $badgeClass = ($statusAsli === 'Dibuka') ? 'badge badge-open' : 'badge badge-closed'; // Menentukan kelas warna hijau untuk Dibuka atau merah untuk Ditutup

        $namaKandidatRaw = $lowongan->getNamaKandidat(); // Mengambil nama kandidat hasil LEFT JOIN (bisa bernilai null)
        if ($namaKandidatRaw !== null && $namaKandidatRaw !== '') { // Mengecek apakah lowongan memiliki kandidat unggulan yang terhubung
            $kandidatHtml = htmlspecialchars($namaKandidatRaw, ENT_QUOTES, 'UTF-8'); // Mengamankan nama kandidat agar aman dirender ke HTML
        } else { // Cabang jika idKandidat bernilai NULL atau belum dipilih
            $kandidatHtml = "<span style='color:#6b7280;font-style:italic;'>Belum ditentukan</span>"; // Menampilkan teks redup miring sebagai penanda belum ada kandidat terpilih
        } // Menutup percabangan format tampilan kandidat terpilih

        $html = "<tr>"; // Membuka elemen baris tabel HTML
        $html .= "<td class='col-id'>{$no}</td>"; // Menambahkan sel nomor urut baris
        $html .= "<td><strong>{$judul}</strong></td>"; // Menambahkan sel judul lowongan berhuruf tebal
        $html .= "<td>{$perusahaan}</td>"; // Menambahkan sel nama perusahaan
        $html .= "<td>{$pic}</td>"; // Menambahkan sel nama PIC rekruter
        $html .= "<td>{$gaji}</td>"; // Menambahkan sel estimasi gaji terformat
        $html .= "<td><span class='{$badgeClass}'>{$statusAman}</span></td>"; // Menambahkan sel status berbentuk badge berwarna
        $html .= "<td>{$kandidatHtml}</td>"; // Menambahkan sel nama kandidat terpilih hasil LEFT JOIN
        $html .= "<td class='col-actions'>"; // Membuka sel kolom aksi Edit dan Hapus
        $html .= "<a href='index.php?modul=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>"; // Menambahkan tombol tautan ke form edit lowongan berdasarkan ID
        $html .= "<form method='post' action='index.php?modul=lowongan' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">"; // Membuka form POST inline untuk hapus dengan dialog konfirmasi
        $html .= "<input type='hidden' name='modul' value='lowongan'>"; // Menyertakan identitas modul lowongan pada payload POST
        $html .= "<input type='hidden' name='action' value='hapus'>"; // Menyertakan aksi hapus untuk dibaca oleh index.php
        $html .= "<input type='hidden' name='id' value='{$id}'>"; // Menyertakan ID lowongan yang akan dihapus
        $html .= "<button type='submit' class='btn btn-delete'>Hapus</button>"; // Menambahkan tombol submit penghapusan data
        $html .= "</form>"; // Menutup form penghapusan inline
        $html .= "</td>"; // Menutup sel kolom aksi
        $html .= "</tr>"; // Menutup baris tabel HTML
        return $html; // Mengembalikan potongan HTML satu baris tabel lengkap
    } // Menutup helper buildRowHtml

    private function buildStatusOptionsHtml(string $selectedStatus = 'Dibuka'): string { // Membuat helper privat untuk merakit tag <option> dropdown status di Presenter
        $daftarStatus = ['Dibuka', 'Ditutup']; // Mendefinisikan daftar opsi status lowongan yang valid
        $optionsHtml = ''; // Menginisialisasi string penampung seluruh tag <option> status
        foreach ($daftarStatus as $status) { // Melakukan perulangan untuk setiap pilihan status
            $selected = ($status === $selectedStatus) ? ' selected' : ''; // Menambahkan atribut selected jika opsi cocok dengan nilai saat ini
            $aman = htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); // Mengamankan teks opsi dari celah XSS
            $optionsHtml .= "<option value='{$aman}'{$selected}>{$aman}</option>"; // Merakit dan menggabungkan tag <option> ke string hasil
        } // Menutup perulangan foreach status
        return $optionsHtml; // Mengembalikan kumpulan tag <option> status siap pakai ke View
    } // Menutup helper buildStatusOptionsHtml

    private function buildKandidatOptionsHtml(?int $selectedIdKandidat = null): string { // Membuat helper privat untuk merakit tag <option> dropdown relasi kandidat di Presenter
        $listKandidat = $this->modelKandidat->getAllKandidat(); // Mengambil seluruh daftar objek Kandidat melalui kontrak Model kandidat
        $emptySelected = ($selectedIdKandidat === null) ? ' selected' : ''; // Menandai opsi kosong sebagai terpilih jika belum ada kandidat yang dikaitkan
        $optionsHtml = "<option value=''{$emptySelected}>-- Belum Ada Kandidat --</option>"; // Menambahkan opsi default bernilai kosong (akan disimpan sebagai NULL)

        foreach ($listKandidat as $kandidat) { // Melakukan perulangan pada setiap objek Kandidat untuk membentuk opsi dropdown
            $idKandidat = (int)$kandidat->getId(); // Mengambil ID kandidat sebagai nilai atribut value pada option
            $nama = htmlspecialchars($kandidat->getNama(), ENT_QUOTES, 'UTF-8'); // Mengamankan nama kandidat dari celah XSS
            $posisi = htmlspecialchars($kandidat->getPosisi(), ENT_QUOTES, 'UTF-8'); // Mengamankan posisi kandidat sebagai keterangan tambahan pada label dropdown
            $selected = ($selectedIdKandidat === $idKandidat) ? ' selected' : ''; // Menambahkan atribut selected saat ID cocok dengan data prefill edit
            $optionsHtml .= "<option value='{$idKandidat}'{$selected}>{$nama} ({$posisi})</option>"; // Merakit tag <option> berisi nama dan posisi kandidat
        } // Menutup perulangan foreach kandidat

        return $optionsHtml; // Mengembalikan kumpulan tag <option> kandidat siap pakai ke View
    } // Menutup helper buildKandidatOptionsHtml

    public function tampilkanLowongan(): string { // Mendefinisikan method untuk menyiapkan dan menampilkan halaman daftar lowongan
        $listLowongan = $this->modelLowongan->getAllLowongan(); // Meminta seluruh daftar objek Lowongan (termasuk hasil LEFT JOIN) dari Model
        $totalLowongan = count($listLowongan); // Menghitung jumlah total lowongan di Presenter agar View tetap pasif

        $rowsHtml = ''; // Menginisialisasi string penampung seluruh baris tabel HTML
        if (empty($listLowongan)) { // Mengecek apakah data lowongan di database masih kosong
            $rowsHtml = '<tr><td colspan="8" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data lowongan.</td></tr>'; // Menyiapkan satu baris pesan kosong selebar 8 kolom
        } else { // Cabang jika terdapat minimal satu data lowongan
            $no = 1; // Menginisialisasi nomor urut baris tabel mulai dari 1
            foreach ($listLowongan as $lowongan) { // Melakukan perulangan untuk setiap objek Lowongan
                $rowsHtml .= $this->buildRowHtml($no, $lowongan); // Merakit baris HTML lewat helper lalu menggabungkannya ke $rowsHtml
                $no++; // Menaikkan nomor urut untuk baris berikutnya
            } // Menutup perulangan foreach
        } // Menutup percabangan if-else

        return $this->view->renderDaftar($rowsHtml, $totalLowongan); // Mengirim baris HTML dan total data ke ViewLowongan untuk dirender
    } // Menutup method tampilkanLowongan

    public function tampilkanFormLowongan(?int $id = null): string { // Mendefinisikan method untuk menampilkan form tambah atau ubah lowongan
        if ($id !== null) { // Mengecek apakah pemanggilan ditujukan untuk mode edit data lowongan
            $lowongan = $this->modelLowongan->getLowonganById($id); // Mengambil detail objek Lowongan berdasarkan ID dari Model
            if ($lowongan === null) { // Mengecek apakah lowongan dengan ID tersebut tidak ditemukan
                header("Location: index.php?modul=lowongan"); // Mengarahkan ulang browser ke daftar lowongan jika ID tidak valid
                exit(); // Menghentikan eksekusi skrip segera setelah redirect
            } // Menutup pengecekan data null

            $formData = [ // Menyiapkan array data sederhana (bukan objek Entity) untuk prefill input form
                'id'            => $lowongan->getId(), // Memasukkan ID lowongan ke array form
                'judulLowongan' => $lowongan->getJudulLowongan(), // Memasukkan judul lowongan ke array form
                'perusahaan'    => $lowongan->getPerusahaan(), // Memasukkan nama perusahaan ke array form
                'picRekruter'   => $lowongan->getPicRekruter(), // Memasukkan nama PIC rekruter ke array form
                'gajiJuta'      => $lowongan->getGajiJuta(), // Memasukkan estimasi gaji ke array form
            ]; // Menutup array $formData
            $statusOptionsHtml = $this->buildStatusOptionsHtml($lowongan->getStatus()); // Merakit opsi dropdown status dengan nilai aktif sesuai data database
            $kandidatOptionsHtml = $this->buildKandidatOptionsHtml($lowongan->getIdKandidat()); // Merakit opsi dropdown kandidat dengan pilihan terpilih sesuai Foreign Key
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData, $statusOptionsHtml, $kandidatOptionsHtml); // Memerintahkan View merender form edit lengkap dengan data prefill dan dropdown
        } // Menutup blok mode edit

        $statusOptionsHtml = $this->buildStatusOptionsHtml('Dibuka'); // Merakit opsi dropdown status dengan nilai bawaan 'Dibuka' untuk mode tambah
        $kandidatOptionsHtml = $this->buildKandidatOptionsHtml(null); // Merakit opsi dropdown kandidat dengan pilihan awal kosong untuk mode tambah
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", [], $statusOptionsHtml, $kandidatOptionsHtml); // Memerintahkan View merender form tambah lowongan kosong
    } // Menutup method tampilkanFormLowongan

    public function tambahLowongan( // Memulai definisi method untuk memproses penambahan lowongan baru
        string $judulLowongan, // Parameter judul lowongan
        string $perusahaan, // Parameter nama perusahaan
        string $picRekruter, // Parameter nama PIC rekruter
        int $gajiJuta, // Parameter estimasi gaji dalam satuan juta
        string $status, // Parameter status lowongan
        ?int $idKandidat // Parameter ID kandidat terpilih (boleh null)
    ): void { // Membuka badan method tambahLowongan
        $this->modelLowongan->addLowongan($judulLowongan, $perusahaan, $picRekruter, $gajiJuta, $status, $idKandidat); // Memerintahkan Model menyimpan data lowongan baru ke database
        header("Location: index.php?modul=lowongan"); // Mengarahkan ulang browser ke halaman daftar lowongan setelah berhasil menyimpan
        exit(); // Menghentikan eksekusi skrip segera setelah redirect
    } // Menutup method tambahLowongan

    public function ubahLowongan( // Memulai definisi method untuk memproses perubahan data lowongan
        int $id, // Parameter ID lowongan target
        string $judulLowongan, // Parameter judul lowongan baru
        string $perusahaan, // Parameter nama perusahaan baru
        string $picRekruter, // Parameter nama PIC rekruter baru
        int $gajiJuta, // Parameter estimasi gaji baru
        string $status, // Parameter status lowongan baru
        ?int $idKandidat // Parameter ID kandidat baru (boleh null)
    ): void { // Membuka badan method ubahLowongan
        $this->modelLowongan->updateLowongan($id, $judulLowongan, $perusahaan, $picRekruter, $gajiJuta, $status, $idKandidat); // Memerintahkan Model memperbarui data lowongan di database
        header("Location: index.php?modul=lowongan"); // Mengarahkan ulang browser ke halaman daftar lowongan setelah berhasil memperbarui
        exit(); // Menghentikan eksekusi skrip segera setelah redirect
    } // Menutup method ubahLowongan

    public function hapusLowongan(int $id): void { // Mendefinisikan method untuk memproses penghapusan data lowongan
        $this->modelLowongan->deleteLowongan($id); // Memerintahkan Model menghapus data lowongan berdasarkan ID
        header("Location: index.php?modul=lowongan"); // Mengarahkan ulang browser ke halaman daftar lowongan setelah berhasil menghapus
        exit(); // Menghentikan eksekusi skrip segera setelah redirect
    } // Menutup method hapusLowongan
} // Menutup definisi kelas PresenterLowongan
// Akhir file PresenterLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)