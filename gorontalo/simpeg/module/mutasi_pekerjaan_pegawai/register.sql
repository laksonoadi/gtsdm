INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','Pegawai','view','html',NULL,'Exclusive','No',NULL,NULL,'300');
INSERT INTO `gtfw_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDefaultModuleId`,`IsShow`,`IconPath`,`MenuOrder`,`ApplicationId`) VALUES ( NULL,'39','Pengalaman kerja pegawai',( SELECT MAX(ModuleId) FROM `gtfw_module` ),'Yes','bimbingan.gif','0','300');
INSERT INTO `gtfw_group_menu`(`MenuId`,`MenuName`,`GroupId`,`ParentMenuId`,`MenuOrder`,`MenuMenuId`) VALUES ( NULL,'Pengalaman kerja pegawai','49','3704','0',(SELECT MAX(MenuId) FROM `gtfw_menu`));
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','MutasiPekerjaanPegawai','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','addMutasiPekerjaanPegawai','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','addMutasiPekerjaanPegawai','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','updateMutasiPekerjaanPegawai','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','updateMutasiPekerjaanPegawai','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','deleteMutasiPekerjaanPegawai','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pekerjaan_pegawai','[300] ','deleteMutasiPekerjaanPegawai','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));

CREATE TABLE `sdm_pekerjaan` (
  `pekId` int(11) NOT NULL AUTO_INCREMENT,
  `pekPegKode` bigint(20) NOT NULL DEFAULT '0',
  `pekNama` varchar(100) NOT NULL DEFAULT '',
  `pekJabatan` varchar(255) NOT NULL DEFAULT '',
  `pekTanggungJawab` varchar(255) NOT NULL DEFAULT '',
  `pekTahunMulai` year(4) DEFAULT NULL,
  `pekTahunSelesai` year(4) DEFAULT NULL,
  `pekStatus` enum('aktif','tidak aktif') DEFAULT 'tidak aktif',
  `pekUpload` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pekId`,`pekPegKode`),
  KEY `pekPegKode` (`pekPegKode`)
) ENGINE=InnoDB