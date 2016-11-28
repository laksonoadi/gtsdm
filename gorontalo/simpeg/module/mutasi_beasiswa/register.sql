INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','Pegawai','view','html',NULL,'Exclusive','No',NULL,NULL,'300');
INSERT INTO `gtfw_menu`(`MenuId`,`MenuParentId`,`MenuName`,`MenuDefaultModuleId`,`IsShow`,`IconPath`,`MenuOrder`,`ApplicationId`) VALUES ( NULL,'39','Riwayat Beasiswa',( SELECT MAX(ModuleId) FROM `gtfw_module` ),'Yes','bimbingan.gif','0','300');
INSERT INTO `gtfw_group_menu`(`MenuId`,`MenuName`,`GroupId`,`ParentMenuId`,`MenuOrder`,`MenuMenuId`) VALUES ( NULL,'Riwayat Beasiswa','49','3704','0',(SELECT MAX(MenuId) FROM `gtfw_menu`));
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','MutasiBeasiswa','view','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','addMutasiBeasiswa','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','addMutasiBeasiswa','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','updateMutasiBeasiswa','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','updateMutasiBeasiswa','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','deleteMutasiBeasiswa','do','html',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));
INSERT INTO `gtfw_module`(`ModuleId`,`Module`,`LabelModule`,`SubModule`,`Action`,`Type`,`Description`,`Access`,`Show`,`IconPath`,`MenuId`,`ApplicationId`) VALUES ( NULL,'mutasi_beasiswa','[300] ','deleteMutasiBeasiswa','do','json',NULL,'Exclusive','No',NULL,(SELECT MAX(MenuId) FROM `gtfw_menu`),'300');
INSERT INTO `gtfw_group_module`(`GroupId`,`ModuleId`) VALUES ( '49',( SELECT MAX(ModuleId) FROM `gtfw_module` ));

CREATE TABLE `sdm_beasiswa` (                                                                                                                        
                `beasiswaId` int(11) NOT NULL AUTO_INCREMENT,                                                                                                      
                `beasiswaPegKode` bigint(20) DEFAULT NULL,                                                                                                         
                `beasiswaTahunDiterima` int(4) DEFAULT NULL,                                                                                                       
                `beasiswaPendId` tinyint(4) DEFAULT NULL,                                                                                                          
                `beasiswaNama` varchar(255) DEFAULT NULL,                                                                                                          
                `beasiswaAslDnrId` char(2) DEFAULT NULL,                                                                                                           
                `beasiswaTahun` int(4) DEFAULT NULL,                                                                                                               
                `beasiswaBulan` int(4) DEFAULT NULL,                                                                                                               
                `beasiswaKet` text,                                                                                                                                
                `beasiswaUpload` varchar(255) DEFAULT NULL,                                                                                                        
                PRIMARY KEY (`beasiswaId`),                                                                                                                        
                KEY `beasiswaPegId` (`beasiswaPegKode`),                                                                                                           
                KEY `FK_sdm_beasiswa` (`beasiswaPendId`),                                                                                                          
                KEY `FK_sdm_beasiswa_1` (`beasiswaAslDnrId`),                                                                                                      
                CONSTRAINT `FK_sdm_beasiswa` FOREIGN KEY (`beasiswaPendId`) REFERENCES `pub_ref_pendidikan` (`pendId`) ON DELETE SET NULL ON UPDATE CASCADE,       
                CONSTRAINT `FK_sdm_beasiswa_1` FOREIGN KEY (`beasiswaAslDnrId`) REFERENCES `sdm_ref_asal_dana` (`asldnrId`) ON DELETE SET NULL ON UPDATE CASCADE,  
                CONSTRAINT `sdm_beasiswa_ibfk_1` FOREIGN KEY (`beasiswaPegKode`) REFERENCES `pub_pegawai` (`pegId`) ON UPDATE CASCADE                              
              ) ENGINE=InnoDB;