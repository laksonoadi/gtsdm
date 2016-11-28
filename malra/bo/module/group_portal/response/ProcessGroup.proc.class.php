<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/group_portal/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppGroup.class.php';
class ProcessGroup {
   
   var $_POST;
   var $groupObj;
   var $pageView;
   var $pageInput;
   //css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;
	
	var $applicationId;
   
   function __construct () {
      $this->groupObj = new AppGroup();
      $this->_POST = $_POST->AsArray();
      $this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['grp']);
      $this->encId = Dispatcher::Instance()->Encrypt($this->decId);
      $this->pageView = Dispatcher::Instance()->GetUrl('group_portal', 'group', 'view', 'html');
      $this->pageInput = Dispatcher::Instance()->GetUrl('group_portal', 'inputGroup', 'view', 'html');
		  $this->applicationId = GTFWConfiguration::GetValue( 'application', 'application_portal_id');
   }
   
   function IsEmpty($formName, $label, $sub_modul) {
      if (isset($_POST['btnsimpan'])) {
         if(trim($this->_POST[$formName]) == "") {
            $this->SendAlert("Isian $label tidak boleh kosong.", $sub_modul);
            return true;
         } else {
            return false;
         }
      }
   }
  
   function SendAlert($alert, $sub_modul, $css='') {
      Messenger::Instance()->Send('group_portal', $sub_modul, 'view', 'html', array($this->_POST, $alert, $css),Messenger::NextRequest);
   }
   
   function Add() {
      $cek_group = $this->IsEmpty('groupname', 'Nama Group', 'inputGroup');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Unit Kerja', 'inputGroup');
      if ($cek_group || $cek_unit) {
         return $this->pageInput;
      }elseif (isset($_POST['btnbatal'])) {
         return $this->pageView;
      }elseif (isset($_POST['btnsimpan'])) {
         $arrMenu = array();
         if ($_POST['groupname']!="") {
            $menu = array();
            $addMenu = true;
            if (isset($_POST['menu'] )) {
               $addMenu = false;
               foreach($_POST['menu'] as $value) {
                  $menu[] =  $value ;
               }
               $dataMenu = $this->groupObj->GetPrivilegeByArrayId($menu);
               $len = sizeof($dataMenu);

               for ($i=0; $i<$len; $i++) {
                  if (!isset($arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'])) {
                     $tmp = $this->groupObj->GetPrivilegeById($dataMenu[$i]['menu_parent_id']);
                     $arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'] = $tmp[0];
                  }
                  $arrMenu[$dataMenu[$i]['menu_parent_id']]['child'][] = $dataMenu[$i];
               } 
            }
            
            $this->groupObj->StartTrans();
         
            $addGroup = $this->groupObj->DoAddGroup($this->applicationId,$_POST['groupname'], $_POST['description'], $_POST['unit_kerja']);         
            $addModule = $this->groupObj->DoAddGroupModule('', 904, true);
            $addModulePesan = $this->groupObj->DoAddGroupModuleByModuleName('collaboration');         
            if ($addGroup && $addModule && $addModulePesan) {
               if (!empty($arrMenu)) {
                  foreach($arrMenu as $key=>$value) {
                     //add ParentMenu                    
                     $addMenu = $this->groupObj->DoAddGroupMenuForNewGroup($value['parent']['menu_name'], 
                        $value['parent']['default_module_id'], 0, $value['parent']['is_show'], $value['parent']['menu_id']);
                     $parentId = $this->groupObj->GetMaxMenuId();
                     if ($addMenu && $parentId) {
                        // add anak2nya
                        $len= sizeof($value['child']) ;
                        for ($i=0; $i<$len; $i++) {
                           $addMenu = $this->groupObj->DoAddGroupMenuForNewGroup($value['child'][$i]['menu_name'], 
                              $value['child'][$i]['default_module_id'], $parentId, $value['child'][$i]['is_show'], $value['child'][$i]['menu_id']);
                           $addModule = $this->groupObj->DoAddGroupModuleFromGtfwMenu($value['child'][$i]['menu_id']);
                           $addMenu = $addMenu && $addModule;
                           if (!$addMenu) {
                              break;
                              break;
                           }
                        }
                     } else {
                        break;
                     }
                  }
               }
               $this->groupObj->EndTrans($addMenu); 
            }
            //exit();
         if ($addMenu == true) {
            $this->SendAlert('Data added successfully', 'group', $this->cssDone);
			} else {
			   $this->SendAlert('Data addition failed', 'group', $this->cssFail);
			}
      }
         return $this->pageView;
      }
   }
   
   function Update() {
      $idDec = Dispatcher::Instance()->Decrypt($_POST['grp']);
      $cek_group = $this->IsEmpty('groupname', 'Nama Group', 'inputGroup');
      $cek_unit = $this->IsEmpty('unit_kerja', 'Unit Kerja', 'inputGroup');
      if ($cek_group || $cek_unit) {
         return $this->pageInput;
      }elseif (isset($_POST['btnbatal'])) {
         return $this->pageView;
      }elseif (isset($_POST['btnsimpan'])) {
         if ($_POST['groupname']!="") {
            $updateMenu = true;
            if (isset($_POST['menu'] )) {
               $updateMenu = false;
               foreach($_POST['menu'] as $value) {
                  $menu[] =  $value ;
               }
					
               $dataMenu = $this->groupObj->GetPrivilegeByArrayId($menu);
					
               $len = sizeof($dataMenu);
               
               for ($i=0; $i<$len; $i++) {
                  if (!isset($arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'])) {
                     $tmp = $this->groupObj->GetPrivilegeById($dataMenu[$i]['menu_parent_id']);
                     $arrMenu[$dataMenu[$i]['menu_parent_id']]['parent'] = $tmp[0];
                  }
                  $arrMenu[$dataMenu[$i]['menu_parent_id']]['child'][] = $dataMenu[$i];
               } 
            }

            $this->groupObj->StartTrans(); 
            $updateGroup = $this->groupObj->DoUpdateGroup($this->applicationId, $_POST['groupname'], $_POST['description'],$_POST['unit_kerja'], $idDec);
            $updateMenu = false;
            $deleteMenu = $this->groupObj->DoDeleteGroupMenu($idDec);
            $deleteModule = $this->groupObj->DoDeleteGroupModule($idDec);      
            $updateDelete = $updateGroup && $deleteMenu && $deleteModule;
            $addModule = $this->groupObj->DoAddGroupModule($idDec,'904');
            $addModulePesan = $this->groupObj->DoAddGroupModuleByModuleName('collaboration', $idDec);

            if ($updateDelete) {
               if (!empty($arrMenu)) {
                  foreach($arrMenu as $key=>$value) {
                     //add ParentMenu
                     //print_r($value);
                     $addMenu = $this->groupObj->DoAddGroupMenu($value['parent']['menu_name'], $idDec, 
                        $value['parent']['default_module_id'], 0, $value['parent']['is_show'], $value['parent']['menu_id']);
                     $parentId = $this->groupObj->GetMaxMenuId();

                     if ($addMenu && $parentId) {
                        // add anak2nya
                        $len = sizeof($value['child']) ;
								
                        for ($i=0; $i<$len; $i++) {
                           $addMenu = $this->groupObj->DoAddGroupMenu($value['child'][$i]['menu_name'], $idDec,
                              $value['child'][$i]['default_module_id'], $parentId, $value['child'][$i]['is_show'], $value['child'][$i]['menu_id']);
                           $addModule = $this->groupObj->DoAddGroupModuleFromGtfwMenu($value['child'][$i]['menu_id'], $idDec);
                           $updateMenu = $addMenu && $addModule;

                           if (!$updateMenu) {
                              break;
                              break;
                           }
                        }              
                     } else {
                        break;
                     } 
                  }
               }
               $this->groupObj->EndTrans($updateMenu); 
            } 
         if ($updateMenu === true) {
            $this->SendAlert('Data updated successfully', 'group', $this->cssDone);
			} else {
			   $this->SendAlert('Data update failed', 'group', $this->cssFail);
			}
      }
      return $this->pageView;
      }
   }
   
   function Delete() {
      $idDec = Dispatcher::Instance()->Decrypt($_REQUEST['idDelete']);
      $label_sukses = 'Data deleted successfully';
      $label_gagal = 'Data delete failed';
      
      $deleteData = $this->groupObj->DoDeleteGroup($idDec);
      
      if ($deleteData === true) {
         $this->SendAlert($label_sukses, 'group', $this->cssDone);
      } else {
         $this->SendAlert($label_gagal, 'group', $this->cssDone);
      }
      return $this->pageView;
   }
}
?>
