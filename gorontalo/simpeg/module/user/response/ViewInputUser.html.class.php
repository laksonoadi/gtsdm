<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewInputUser extends HtmlResponse{

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/user/template');
      $this->SetTemplateFile('input_user.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $return['Pesan'] = $msg[0][1];
      $return['Data'] = $msg[0];
      $decUsr = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      if ($decUsr == '')
         $decUsr = Dispatcher::Instance()->Decrypt($return['Data']['0']['usr']);
      
      $applicationId = GTFWConfiguration::GetValue('application', 'application_id');

      $userObj = new AppUser();
      $this->kerja = new SatuanKerja();

      $dataUser = $userObj->GetDataUserById($decUsr);
      

      $dataUserunit = $userObj->GetListUnitGroup($decUsr);
      $return['userunit'] = $dataUserunit;
      $listunit = explode(',', $dataUser['0']['uGroupList']);
      $return['listunit'] = $listunit;
		#	print_r($dataUser);
      $dataUnitKerja = $userObj->GetComboUnitKerja($applicationId);
      $dataUnitKerjaName = $userObj->GetComboUnitKerjaName($applicationId);

      if (isset($dataUser['0']['unit_kerja_id']))
         $unit_selected = $dataUser['0']['unit_kerja_id'];
      else
         $unit_selected = $return['Data']['0']['unit_kerja'];
		 
	  if (isset($dataUser['0']['satuan_kerja_id']))
         $satuan_selected = $dataUser['0']['satuan_kerja_id'];
      else
         $satuan_selected = $return['Data']['0']['satuan_kerja'];
		 
	  $dataSatuanKerja = $this->kerja->GetComboSatuanKerja($unit_selected);

      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
         array('unit_kerja',$dataUnitKerja,'1','false','onChange="updateGroup();"'), Messenger::CurrentRequest);
		 
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satuan_kerja', 
         array('satuan_kerja',$dataSatuanKerja,$satuan_selected,'false',''), Messenger::CurrentRequest);

      // if (isset($unit_selected)) {
      //    $data_combo_group = $userObj->GetDataGroupByUnitId("", $unit_selected, $applicationId);
      //    if ($_REQUEST['usr']=='')
      //       $group_selected = $return['Data']['0']['group'];
      //    else
      //       $group_selected = $dataUser['0']['group_id'];
      // } else {
      //    $data_combo_group = null;
      // }
    $data_combo_group = $userObj->GetDataGroupByUnitId("", '1', $applicationId);

     if (isset($_GET['satkerId'])){
         $satker_id = $_GET['satkerId']->Integer()->Raw();
         $return['satker_detail'] = $this->kerja->GetSatKerDetail($satker_id);//print_r($return['satker_detail']);
      }
     $return['list'] = $this->kerja->GetListSatKer();
	  
	  $list_unit = $userObj->GetListUnit();
	  $user_satker = $userObj->GetSatkerByUserId($decUsr);
	  $data_unit = array();
	  foreach($user_satker as $item) {
			$data_unit[] = $item['id'];
	  }
	  $return['treeUnit'] = $this->getUnitTree($list_unit, 0, $data_unit);
      
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'group', 
         array('group',$data_combo_group,$dataUser['0']['group_id'],'false',''), Messenger::CurrentRequest);

      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_satuan_kerja', 
         array('unit_satuan_kerja',$dataUnitKerjaName,$dataUser['0']['satker_nama'],'false',''), Messenger::CurrentRequest);
      
      $return['dataUser'] = $dataUser;
      return $return;
   }

   function GetParentLevel($level){
      $return=$this->kerja->GetSatKerLevel($level);
     return $return['satkerId'];
   }

   function ParseTemplate($data = NULL) {
      $dataUser = $data['dataUser'];
      $dataUserUbah = $data['Data']; 
      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         
         $status='checked="checked"';
         $nstatus='';
         if (isset($dataUserUbah[0]['status']) && $dataUserUbah[0]['status']!='Yes') {
            $status='';
            $nstatus='checked="checked"';
         }
         $this->mrTemplate->AddVar('content', 'USERNAME', $dataUserUbah[0]['username']);
         $this->mrTemplate->AddVar('content', 'USR', $dataUserUbah[0]['usr']);
         $this->mrTemplate->AddVar('content', 'CARI', $_GET['cari']);
         $this->mrTemplate->AddVar('content', 'REALNAME', $dataUserUbah[0]['realname']);
         $this->mrTemplate->AddVar('content', 'DESKRIPSI', $dataUserUbah[0]['deskripsi']);
         $this->mrTemplate->AddVar('content', 'USER_ID', $dataUserUbah[0]['user_id']);
      } else {
         $status='checked="checked"';
         $nstatus='';
         if (isset($dataUser[0]['is_active']) && $dataUser[0]['is_active']!='Yes') {
            $status='';
            $nstatus='checked="checked"';
         }
   
         $this->mrTemplate->AddVar('content', 'USERNAME', $dataUser[0]['user_name']);
         $this->mrTemplate->AddVar('content', 'USR', Dispatcher::Instance()->Encrypt($dataUser[0]['user_id']));
         $this->mrTemplate->AddVar('content', 'CARI', $_GET['cari']);
         $this->mrTemplate->AddVar('content', 'REALNAME', $dataUser[0]['real_name']);
         $this->mrTemplate->AddVar('content', 'DESKRIPSI', $dataUser[0]['description']);
         $this->mrTemplate->AddVar('content', 'USER_ID', $dataUser[0]['user_id']);
         
         /*$dataUser[0]['user_name'] = Dispatcher::Instance()->Encrypt($dataUser[0]['user_name']);
         $this->mrTemplate->AddVars('content',$dataUser);*/
      }

      if(!empty($data['satker_detail'])){
       
        $this->mrTemplate->SetAttribute('satker_detail', 'visibility', 'visible');
       $this->mrTemplate->AddVar('satker_detail', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_kerja','inputSatuanKerja', 'view','html').'&satkerId='.$data['satker_detail']['satkerId']);
       $this->mrTemplate->AddVar('satker_detail', 'UNIT', $data['satker_detail']['UnitName']);
       $this->mrTemplate->AddVar('satker_detail', 'NAMA', $data['satker_detail']['satkerNama']);
       $this->mrTemplate->AddVar('satker_detail', 'ID', $data['satker_detail']['satkerId']);
       $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE', $url_delete.
          "&id=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerNama']));
       $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_kerja', 'deleteSatuanKerja', 'do', 'html'));   
     }

      if ($dataUser[0]['user_name']=='') {
         $this->mrTemplate->SetAttribute('view_password', 'visibility', 'visible');
         $url="addUser";
         $tambah="Add";
      } else {
         $url="updateUser";
         $tambah="Update";  
      }
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      
      $this->mrTemplate->AddVar('content', 'STATUS', $status);
      for ($i=0; $i < 500; $i++) { 
        // print_r($data['listunit'][$i]);
        // $scriptcombo .= "document.getElementById('id'".$data['listunit'][$i]."').checked = true;" 
      }
      $dataunitUser = $data['userunit'];
      /* $scriptcombo = '';
      foreach ($dataunitUser as $key => $value) {
        
        $scriptcombo .= "document.getElementById('id".$value['satkerId']."').checked = true;"; 
      } */
		
      if (!empty($data['treeUnit'])) $this->mrTemplate->addVar('content', 'UNIT_TREE', $data['treeUnit']);
      
      // $this->mrTemplate->AddVar('content', 'SCRIPTJAVA', $scriptcombo);
      
      $this->mrTemplate->AddVar('content', 'UNITGRUP', $status);

      $this->mrTemplate->AddVar('content', 'NSTATUS', $nstatus);
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('user', $url, 'do', 'json') );
      $this->mrTemplate->AddVar('content', 'URL_VIEW', Dispatcher::Instance()->GetUrl('user', 'user', 'view', 'html') );
   }
	
	/**
     * generate block ul menu
     * @param array $items(id, parent_id, title, link, position)
     * 
     **/
    function getUnitTree($items, $root_id = 0, $data = array())
    {
        $this->html = array();
        $this->items = $items;

        foreach ($this->items as $item)
			{
				if(empty($item['parent_id']))
					$item['parent_id']=0;
				
					$children[$item['parent_id']][] = $item;
			}
        // loop will be false if the root has no children (i.e., an empty menu!)
        $loop = !empty($children[$root_id]);

        // initializing $parent as the root
        $parent = $root_id;
        $parent_stack = array();

        // HTML wrapper for the menu (open)
        $this->html[] = '<ul>';
        
        $actionLabel = "Aksi";

        while ($loop && (($option = each($children[$parent])) || ($parent > $root_id))) {
				if ($option === false) {
                $parent = array_pop($parent_stack);

                // HTML for menu item containing childrens (close)
                $this->html[] = str_repeat("\t", (count($parent_stack) + 1) * 2) . '</ul>';
                $this->html[] = str_repeat("\t", (count($parent_stack) + 1) * 2 - 1) . '</li>';
            } elseif (!empty($children[$option['value']['id']])) {
                $tab = str_repeat("\t", (count($parent_stack) + 1) * 2 - 1);

                // HTML for menu item containing childrens (open)
                $this->html[] = $tab
                                .'<li id="unit_item_'.$option['value']['id'].'" class="" data-id="'.$option['value']['id'].'">'
                                .'<a>'
                                .$option['value']['name']
                                .'</a>';
                $this->html[] = $tab . "\t" . '<ul class="sub">';

                array_push($parent_stack, $option['value']['parent_id']);
                $parent = $option['value']['id'];
            } else {// HTML for menu item with no children (aka "leaf")
                $tab = str_repeat("\t", (count($parent_stack) + 1) * 2 - 1);
                $this->html[] = $tab
                                .'<li id="unit_item_'.$option['value']['id'].'" class="'.(in_array($option['value']['id'], $data)?'jstree-checked':'jstree-unchecked').'" data-id="'.$option['value']['id'].'">'
                                .'<a data-menuid="'.$option['value']['id'].'">'
                                .$option['value']['name']
                                .'</a>'
                                .'</li>';
            }
        }

        // HTML wrapper for the menu (close)
        $this->html[] = '</ul>';

        return implode("\r\n", $this->html);
    }
}
?>

