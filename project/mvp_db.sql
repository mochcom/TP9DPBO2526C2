USE `mvp_kandidat_db`; -- Memilih database mvp_kandidat_db sebagai database aktif

DROP TABLE IF EXISTS `lowongan`; -- Menghapus tabel lowongan jika sebelumnya sempat terbuat sebagian

CREATE TABLE `lowongan` ( -- Memulai pembuatan struktur tabel lowongan
  `id` int(11) NOT NULL AUTO_INCREMENT, -- Mendefinisikan kolom ID lowongan sebagai bilangan bulat otomatis
  `judulLowongan` varchar(100) NOT NULL, -- Mendefinisikan kolom judul posisi lowongan maksimal 100 karakter
  `perusahaan` varchar(100) NOT NULL, -- Mendefinisikan kolom nama perusahaan penyelenggara lowongan
  `picRekruter` varchar(100) NOT NULL, -- Mendefinisikan kolom nama PIC rekruter (bertema artis kelas dunia)
  `gajiJuta` int(11) NOT NULL DEFAULT 0, -- Mendefinisikan kolom estimasi gaji bulanan dalam satuan juta rupiah
  `status` varchar(30) NOT NULL DEFAULT 'Dibuka', -- Mendefinisikan kolom status ketersediaan lowongan
  `idKandidat` int(11) DEFAULT NULL, -- Mendefinisikan kolom Foreign Key ke kandidat unggulan yang boleh bernilai NULL
  PRIMARY KEY (`id`), -- Menetapkan kolom id sebagai Primary Key tabel lowongan
  KEY `fk_lowongan_kandidat` (`idKandidat`), -- Membuat indeks pada kolom idKandidat untuk mempercepat query JOIN
  CONSTRAINT `fk_lowongan_kandidat` FOREIGN KEY (`idKandidat`) REFERENCES `kandidat` (`id`) ON DELETE SET NULL ON UPDATE CASCADE -- Mengikat relasi Foreign Key ke tabel kandidat(id) dengan aturan ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; -- Menggunakan engine InnoDB agar Foreign Key aktif dengan encoding utf8mb4

INSERT INTO `lowongan` (`id`, `judulLowongan`, `perusahaan`, `picRekruter`, `gajiJuta`, `status`, `idKandidat`) VALUES -- Memulai pengisian 5 data awal tabel lowongan
(1, 'Senior Backend Engineer', 'Swift Tech Studios', 'Taylor Swift', 25, 'Dibuka', 1), -- Menambahkan lowongan ke-1 dengan PIC Taylor Swift berelasi ke kandidat ID 1
(2, 'Lead UI/UX Designer', 'Zendaya Creative Lab', 'Zendaya', 22, 'Dibuka', 2), -- Menambahkan lowongan ke-2 dengan PIC Zendaya berelasi ke kandidat ID 2
(3, 'Frontend Architect', 'Reeves Cyber Systems', 'Keanu Reeves', 24, 'Dibuka', 4), -- Menambahkan lowongan ke-3 dengan PIC Keanu Reeves berelasi ke kandidat ID 4
(4, 'Data Science Lead', 'DiCaprio Analytics', 'Leonardo DiCaprio', 28, 'Ditutup', 3), -- Menambahkan lowongan ke-4 dengan PIC Leonardo DiCaprio berelasi ke kandidat ID 3
(5, 'QA Automation Engineer', 'Mars Digital Works', 'Bruno Mars', 18, 'Dibuka', NULL); -- Menambahkan lowongan ke-5 dengan PIC Bruno Mars tanpa kandidat terpilih (NULL)