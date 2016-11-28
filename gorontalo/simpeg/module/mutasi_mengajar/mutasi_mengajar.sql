CREATE TABLE `sdm_dosen_mengajar` (
  `dosenMengajarId` int(11) NOT NULL AUTO_INCREMENT,
  `dosenMengajarPegKode` bigint(20) DEFAULT NULL,
  `dosenMengajarSemester` varchar(255) DEFAULT NULL,
  `dosenMengajarKodeMataKuliah` varchar(255) DEFAULT NULL,
  `dosenMengajarNamaMataKuliah` varchar(255) DEFAULT NULL,
  `dosenMengajarSks` int(11) DEFAULT NULL,
  `dosenmengajarKelas` varchar(20) DEFAULT NULL,
  `dosenMengajarStatus` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif',
  `dosenMengajarUpload` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`dosenMengajarId`),
  KEY `dosenMengajarPegId` (`dosenMengajarPegKode`),
  CONSTRAINT `sdm_dosen_mengajar_ibfk_1` FOREIGN KEY (`dosenMengajarPegKode`) REFERENCES `pub_pegawai` (`pegId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;