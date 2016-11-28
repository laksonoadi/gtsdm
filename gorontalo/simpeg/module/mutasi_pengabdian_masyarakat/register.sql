INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] view pegawai','pegawai','view','html',NULL,'Exclusive','No',NULL,NULL,'300');
INSERT INTO `gtfw_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDefaultModuleId`,`IsShow`,`IconPath`,`MenuOrder`,`ApplicationId`) VALUES ( NULL,'39','Riwayat Pengabdian Masyarakat',( SELECT MAX(ModuleId) FROM `gtfw_module` ),'Yes','pendadaran.gif','0','300');
INSERT INTO `gtfw_group_menu`(`MenuId`,`MenuName`,`GroupId`,`ParentMenuId`,`MenuOrder`,`MenuMenuId`) VALUES ( NULL,'Riwayat Pengabdian Masyarakat','49','3704','0',(SELECT MAX(MenuId) FROM `gtfw_menu`));
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] view mutasi','mutasiPengabdian','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] detail mutasi','detailMutasi','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do add ','addMutasiPengabdian','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do add json','addMutasiPengabdian','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do update ','updateMutasiPengabdian','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do update json','updateMutasiPengabdian','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do delete','deleteMutasiPengabdian','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_pengabdian_masyarakat','[300] do delete json','deleteMutasiPengabdian','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));

CREATE TABLE `sdm_pengabdian_masyarakat` (
  `pemasyId` int(11) NOT NULL AUTO_INCREMENT,
  `pemasyPegId` int(11) DEFAULT NULL,
  `pemasyNama` varchar(250) DEFAULT NULL,
  `pemasyJenis` varchar(250) DEFAULT NULL,
  `pemasyTempat` varchar(50) DEFAULT NULL,
  `pemasyLamaWaktu` int(4) DEFAULT NULL,
  `pemasyMulai` date DEFAULT '0000-00-00',
  `pemasySelesai` date DEFAULT '0000-00-00',
  `pemasyAslDnrId` int(2) DEFAULT NULL,
  `pemasyBesarDana` decimal(20,2) DEFAULT NULL,
  `pemasyKet` varchar(250) DEFAULT NULL,
  `pemasyUpload` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`pemasyId`),
  KEY `aslDnrId` (`pemasyAslDnrId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1