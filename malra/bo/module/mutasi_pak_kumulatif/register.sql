INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] view pegawai','pegawai','view','html',NULL,'Exclusive','No',NULL,NULL,'300');
INSERT INTO `gtfw_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDefaultModuleId`,`IsShow`,`IconPath`,`MenuOrder`,`ApplicationId`) VALUES ( NULL,'39','Pengajuan Angka Kredit',( SELECT MAX(ModuleId) FROM `gtfw_module` ),'Yes','pendadaran.gif','0','300');
INSERT INTO `gtfw_group_menu`(`MenuId`,`MenuName`,`GroupId`,`ParentMenuId`,`MenuOrder`,`MenuMenuId`) VALUES ( NULL,'Pengajuan Angka Kredit','49','3704','0',(SELECT MAX(MenuId) FROM `gtfw_menu`));
UPDATE `gtfw_module` SET `MenuId`=(SELECT MAX(MenuId) FROM `gtfw_menu`) WHERE `ModuleId`=( SELECT MAX(ModuleId) FROM `gtfw_module` );
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] view mutasi','mutasiPak','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] view popup Kegiatan','popupKegiatan','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] detail mutasi','detailMutasi','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do add ','addMutasiPak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do add json','addMutasiPak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do update ','updateMutasiPak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do update json','updateMutasiPak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do delete','deleteMutasiPak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pak_kumulatif','[300] do delete json','deleteMutasiPak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));

CREATE TABLE `sdm_pak_kumulatif` (
  `pakkumId` bigint(20) NOT NULL AUTO_INCREMENT,
  `pakkumNomor` varchar(25) DEFAULT NULL,
  `pakkumPegId` int(11) DEFAULT NULL,
  `pakkumPejabat` varchar(50) DEFAULT NULL,
  `pakkumTanggalPenetapan` date DEFAULT NULL,
  `pakkumPeriodeAwal` date DEFAULT NULL,
  `pakkumPeriodeAkhir` date DEFAULT NULL,
  `pakkumNextJabatanId` int(11) DEFAULT NULL,
  `pakkumIsApproved` tinyint(1) DEFAULT '0',
  `pakkumDateApproved` date DEFAULT NULL,
  `pakkumCreatedUserId` bigint(20) DEFAULT NULL,
  `pakkumCreatedDate` date DEFAULT NULL,
  `pakkumModifiedUserId` bigint(20) DEFAULT NULL,
  `pakkumModifiedDate` date DEFAULT NULL,
  PRIMARY KEY (`pakkumId`),
  KEY `FK_sdm_pak_kumulatif_pegId` (`pakkumPegId`)
) ENGINE=InnoDB;
CREATE TABLE `sdm_pak_kumulatif_detail` (
  `pakkumdetId` bigint(20) NOT NULL AUTO_INCREMENT,
  `pakkumdetPakkumId` bigint(20) DEFAULT NULL,
  `pakkumdetKegiatanId` int(11) DEFAULT NULL,
  `pakkumdetAngkaKredit` double(20,2) DEFAULT NULL,
  `pakkumdetKeterangan` text,
  `pakkumdetPeran` varchar(100) DEFAULT NULL,
  `pakkumdetLokasi` varchar(100) DEFAULT NULL,
  `pakkumdetWaktu` varchar(100) DEFAULT NULL,
  `pakkumdetBuktiFisik` varchar(100) DEFAULT NULL,
  `pakkumdetLampiran` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pakkumdetId`)
) ENGINE=InnoDB;
