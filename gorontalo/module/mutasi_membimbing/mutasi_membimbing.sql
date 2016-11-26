CREATE TABLE `sdm_dosen_membimbing` (
  `dosenMembimbingId` INT(11) NOT NULL AUTO_INCREMENT,
  `dosenMembimbingPegKode` BIGINT(20) DEFAULT NULL,
  `dosenMembimbingJenis` VARCHAR(255) DEFAULT NULL,
  `dosenMembimbingSemester` VARCHAR(255) DEFAULT NULL,
  `dosenMembimbingNimMahasiswa` VARCHAR(255) DEFAULT NULL,
  `dosenMembimbingNamaMahasiswa` VARCHAR(255) DEFAULT NULL,
  `dosenMembimbingJudulTa` VARCHAR(20) DEFAULT NULL,
  `dosenMembimbingStatus` ENUM('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif',
  `dosenMembimbingUpload` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`dosenMembimbingId`),
  KEY `dosenMembimbingPegId` (`dosenMembimbingPegKode`),
  CONSTRAINT `sdm_dosen_membimbing_ibfk_1` FOREIGN KEY (`dosenMembimbingPegKode`) REFERENCES `pub_pegawai` (`pegId`) ON UPDATE CASCADE
) ENGINE=InnoDB;