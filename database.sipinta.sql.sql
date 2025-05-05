CREATE TABLE `kampus` (
  `kampus_id` INT PRIMARY KEY AUTO_INCREMENT,
  `kampus_kode` Varchar(10) NOT NULL,
  `kampus_nama` Varchar(10) NOT NULL
);

CREATE TABLE `jurusan` (
  `jurusan_id` INT PRIMARY KEY AUTO_INCREMENT,
  `jurusan_kode` Varchar(10) NOT NULL,
  `jurusan_nama` varchar(20) NOT NULL,
  `kampus_id` int
);

CREATE TABLE `prodi` (
  `prodi_id` INT PRIMARY KEY AUTO_INCREMENT,
  `prodi_kode` Varchar(10) NOT NULL,
  `prodi_nama` varchar(50) NOT NULL,
  `jurusan_id` INT
);

CREATE TABLE `admin` (
  `admin_id` INT PRIMARY KEY AUTO_INCREMENT,
  `admin_nama` varchar(50) NOT NULL,
  `no_telp` varchar(15)
);

CREATE TABLE `mahasiswa` (
  `mahasiswa_id` int PRIMARY KEY AUTO_INCREMENT,
  `nim` varchar(10) UNIQUE,
  `mahasiswa_nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `alamat_asal` varchar(255) NOT NULL,
  `alamat_sekarang` varchar(255) NOT NULL,
  `angkatan` varchar(4) NOT NULL,
  `no_telp` varchar(15),
  `jenis_kelamin` enum(Laki-laki,Perempuan) NOT NULL,
  `status` enum(aktif,alumni) NOT NULL,
  `keterangan` enum(gratis,berbayar) NOT NULL,
  `prodi_id` int
);

CREATE TABLE `dosen` (
  `dosen_id` int PRIMARY KEY AUTO_INCREMENT,
  `nidn` varchar(20) UNIQUE,
  `dosen_nama` varchar(60) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `alamat_asal` varchar(255),
  `alamat_sekarang` varchar(255),
  `no_telp` varchar(15),
  `jenis_kelamin` enum(Laki-laki,Perempuan) NOT NULL,
  `jurusan_id` int
);

CREATE TABLE `tendik` (
  `tendik_id` int PRIMARY KEY AUTO_INCREMENT,
  `nip` varchar(20) UNIQUE,
  `tendik_nama` varchar(60) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `alamat_asal` varchar(255),
  `alamat_sekarang` varchar(255),
  `no_telp` varchar(15),
  `jenis_kelamin` enum(Laki-laki,Perempuan) NOT NULL,
  `kampus_id` int
);

CREATE TABLE `user` (
  `user_id` int PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(60) UNIQUE NOT NULL,
  `username` varchar(20) UNIQUE NOT NULL,
  `password` varchar(10) NOT NULL,
  `profile` varchar(255),
  `role` enum(admin,mahasiswa,dosen,tendik) NOT NULL,
  `admin_id` int,
  `mahasiswa_id` int,
  `dosen_id` int,
  `tendik_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `jadwal` (
  `jadwal_id` int PRIMARY KEY AUTO_INCREMENT,
  `tanggal_pelaksanaan` datetime,
  `jam_mulai` time,
  `jam_selesai` time,
  `keterangan` text
);

CREATE TABLE `pendaftaran` (
  `pendaftaran_id` int PRIMARY KEY AUTO_INCREMENT,
  `pendaftaran_kode` varchar(10) NOT NULL,
  `tanggal_pendaftaran` datetime,
  `scan_ktp` varchar(255) NOT NULL,
  `scan_ktm` varchar(255) NOT NULL,
  `pas_foto` varchar(255) NOT NULL,
  `mahasiswa_id` int,
  `jadwal_id` int,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `detail_pendaftaran` (
  `detail_id` int PRIMARY KEY AUTO_INCREMENT,
  `pendaftaran_id` int UNIQUE,
  `status` enum(menunggu,diterima,ditolak) NOT NULL,
  `catatan` text,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `ujian` (
  `ujian_id` int PRIMARY KEY AUTO_INCREMENT,
  `ujian_kode` varchar(10) NOT NULL,
  `jadwal_id` int,
  `pendaftaran_id` int
);

CREATE TABLE `hasil_ujian` (
  `hasil_id` int PRIMARY KEY AUTO_INCREMENT,
  `nilai_total` decimal(5,2) NOT NULL,
  `nilai_listening` decimal(5,2) NOT NULL,
  `nilai_reading` decimal(5,2) NOT NULL,
  `status_lulus` enum(lulus,tidak lulus),
  `catatan` text,
  `jadwal_id` int,
  `user_id` int
);

CREATE TABLE `informasi` (
  `informasi_id` int PRIMARY KEY AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL,
  `isi` text NOT NULL,
  `admin_id` int
);

ALTER TABLE `jurusan` ADD FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`kampus_id`);

ALTER TABLE `prodi` ADD FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`jurusan_id`);

ALTER TABLE `mahasiswa` ADD FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`prodi_id`);

ALTER TABLE `dosen` ADD FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`jurusan_id`);

ALTER TABLE `tendik` ADD FOREIGN KEY (`kampus_id`) REFERENCES `kampus` (`kampus_id`);

ALTER TABLE `user` ADD FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

ALTER TABLE `user` ADD FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`mahasiswa_id`);

ALTER TABLE `user` ADD FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`dosen_id`);

ALTER TABLE `user` ADD FOREIGN KEY (`tendik_id`) REFERENCES `tendik` (`tendik_id`);

ALTER TABLE `pendaftaran` ADD FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`mahasiswa_id`);

ALTER TABLE `pendaftaran` ADD FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`);

ALTER TABLE `detail_pendaftaran` ADD FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`pendaftaran_id`);

ALTER TABLE `ujian` ADD FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`);

ALTER TABLE `ujian` ADD FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`pendaftaran_id`);

ALTER TABLE `hasil_ujian` ADD FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`jadwal_id`);

ALTER TABLE `hasil_ujian` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

ALTER TABLE `informasi` ADD FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);
