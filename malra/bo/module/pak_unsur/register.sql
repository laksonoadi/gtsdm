INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] view Pak','pak','view','html',NULL,'Exclusive','No',NULL,NULL,'300');
INSERT INTO `gtfw_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDefaultModuleId`,`IsShow`,`IconPath`,`MenuOrder`,`ApplicationId`) VALUES ( NULL,'16','Referensi PAK Unsur Kegiatan',( SELECT MAX(ModuleId) FROM `gtfw_module` ),'Yes','data-referensi.gif','0','300');
INSERT INTO `gtfw_group_menu`(`MenuId`,`MenuName`,`GroupId`,`ParentMenuId`,`MenuOrder`,`MenuMenuId`) VALUES ( NULL,'Referensi PAK Unsur Kegiatan','49','3525','0',(SELECT MAX(MenuId) FROM `gtfw_menu`));
UPDATE `gtfw_module` SET `MenuId`=(SELECT MAX(MenuId) FROM `gtfw_menu`) WHERE `ModuleId`=( SELECT MAX(ModuleId) FROM `gtfw_module` );
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] view input pak','inputPak','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do add','addPak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do add json','addPak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do update ','updatePak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do update json','updatePak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do delete','deletePak','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'pak_unsur','[300] do delete json','deletePak','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));

CREATE TABLE `sdm_ref_pak_unsur` (
  `unsurId` int(11) NOT NULL AUTO_INCREMENT,
  `unsurNama` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `unsurJenis` enum('Utama','Penunjang') COLLATE latin1_general_ci DEFAULT NULL,
  `unsurKeterangan` text COLLATE latin1_general_ci,
  `unsurCreatedUserId` bigint(20) DEFAULT NULL,
  `unsurCreatedDate` date DEFAULT NULL,
  `unsurModifiedUserId` bigint(20) DEFAULT NULL,
  `unsurModifiedDate` date DEFAULT NULL,
  PRIMARY KEY (`unsurId`),
  KEY `unsurCreatedId` (`unsurCreatedUserId`),
  KEY `unsurModifiedId` (`unsurModifiedUserId`),
  CONSTRAINT `FK_sdm_ref_pak_unsur_created_user_id` FOREIGN KEY (`unsurCreatedUserId`) REFERENCES `gtfw_user` (`UserId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_sdm_ref_pak_unsur_modified_user_id` FOREIGN KEY (`unsurModifiedUserId`) REFERENCES `gtfw_user` (`UserId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB;
