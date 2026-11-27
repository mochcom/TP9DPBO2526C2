<?php // Membuka blok kode PHP
interface KontrakPresenterLowongan { // Mendefinisikan interface kontrak method untuk layer Presenter modul lowongan
    public function tampilkanLowongan(): string; // Kontrak method untuk menyiapkan dan merender halaman tabel daftar lowongan
    public function tampilkanFormLowongan(?int $id = null): string; // Kontrak method untuk merender form tambah (id null) atau form ubah (id terisi)
    public function tambahLowongan( // Memulai deklarasi kontrak method untuk memproses penambahan lowongan baru
        string $judulLowongan, // Parameter judul posisi lowongan yang ditambahkan
        string $perusahaan, // Parameter nama perusahaan penyelenggara lowongan
        string $picRekruter, // Parameter nama PIC rekruter penanggung jawab
        int $gajiJuta, // Parameter estimasi gaji bulanan dalam satuan juta rupiah
        string $status, // Parameter status lowongan ('Dibuka' atau 'Ditutup')
        ?int $idKandidat // Parameter Foreign Key ID kandidat terpilih (boleh bernilai null)
    ): void; // Menutup deklarasi kontrak tambahLowongan tanpa nilai kembalian
    public function ubahLowongan( // Memulai deklarasi kontrak method untuk memproses pembaruan data lowongan
        int $id, // Parameter ID lowongan yang akan diubah
        string $judulLowongan, // Parameter judul posisi lowongan baru
        string $perusahaan, // Parameter nama perusahaan baru
        string $picRekruter, // Parameter nama PIC rekruter baru
        int $gajiJuta, // Parameter estimasi gaji baru dalam satuan juta
        string $status, // Parameter status lowongan baru
        ?int $idKandidat // Parameter Foreign Key ID kandidat baru (boleh bernilai null)
    ): void; // Menutup deklarasi kontrak ubahLowongan tanpa nilai kembalian
    public function hapusLowongan(int $id): void; // Kontrak method untuk menghapus satu data lowongan berdasarkan ID
} // Menutup definisi interface KontrakPresenterLowongan
// Akhir file KontrakPresenterLowongan.php (tanpa tag penutup PHP agar aman dari whitespace sebelum header redirect)