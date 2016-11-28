<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/group/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppGroup.class.php';

class ViewInputGroup extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
        'module/group/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('input_group.html');
   }
   
   function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		$return['Pesan'] = $msg[0][1];
		$return['Data'] = $msg[0][0];
		
		$applicationId = GTFWConfiguration::GetValue( 'application', 'application_id');

		$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['grp']);
		if ($idDec == '')
         $idDec = Dispatcher::Instance()->Decrypt($return['Data']['0']['grp']);
			
      $groupObj = new AppGroup();
      $menuGroup = $groupObj->GetAllPrivilege($idDec, $applicationId);      
      $return['menuGroup'] = $menuGroup;

      $dataGroup = $groupObj->GetDataGroupById($idDec);
      $dataUnitKerja = $groupObj->GetComboUnitKerja($applicationId);

      if (isset($dataGroup['0']['group_unit_id']))
         $unit_selected = $dataGroup['0']['group_unit_id'];
      else
         $unit_selected = $return['Data']['0']['unit_kerja'];

      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
         array('unit_kerja',$dataUnitKerja,'1','false'), Messenger::CurrentRequest);

		$return['decDataId'] = $idDec;
		$return['dataGroup'] = $dataGroup;
		return $return;
	}

   function ParseTemplate($data = NULL) {
		$dataGroup = $data['dataGroup'];
      if ($data['Pesan']) {
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      }
        
      if ($_REQUEST['grp']=='') {
         $url="addGroup";
         $tambah="Add";
      } else {
         $url="updateGroup";
         $tambah="Update";
      }       
      
      //print_r($data['dataGroup']);
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVar('content', 'GROUPNAME', empty($dataGroup[0]['groupname'])?$data['Data']['groupname']:$dataGroup[0]['groupname']);
      $this->mrTemplate->AddVar('content', 'DESKRIPSI', empty($dataGroup[0]['description'])?$data['Data']['description']:$dataGroup[0]['description']);
      
      if(!empty($dataGroup[0]['guidefile'])) {
         $this->mrTemplate->AddVar('guidefile_old', 'FILENAME', $dataGroup[0]['guidefile']);
         
         $path_panduan = GTFWConfiguration::GetValue('application', 'panduan_group_path');
         $this->mrTemplate->AddVar('guidefile_old', 'URL', $path_panduan.$dataGroup[0]['guidefile']);
      }
      
      $this->mrTemplate->AddVar('content', 'GRP', $_REQUEST['grp']);
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('group', $url, 'do', 'html'));
      $menu = explode('|',$dataGroup[0]['menu_menu_id']);
      $menuGroup = $data['menuGroup'];
      
      $len = sizeOf($menuGroup);
      $mlen = sizeOf($menu);
      for ($i=0;$i<$len;$i++) {  
         if ($menuGroup[$i]['menu_parent_id']==0) {
            $parent=$menuGroup[$i]['menu_name'];
            $this->mrTemplate->addVar('menu', 'MENU_PARENT', 'YES');
            $this->mrTemplate->addVar('menu', 'PARENT_MENU', $parent);
            $this->mrTemplate->parseTemplate('menu', 'a'); 
            for ($j=$i;$j<$len;$j++) { 
               if ($menuGroup[$j]['menu_parent_id']==$menuGroup[$i]['menu_id']) {
                  $idmenu=$menuGroup[$j]['menu_id'];
                  $menu_name=$menuGroup[$j]['menu_name'];             
                  for ($k=0;$k<$mlen;$k++) { 
                     if ($menuGroup[$j]['menu_id']==$menu[$k]) {
                     // if (!empty($menuGroup[$j]['MenuName'])) {
                         $this->mrTemplate->addVar('menu', 'CHECK', 'checked'); 
                         break;
                     } else $this->mrTemplate->addVar('menu', 'CHECK', '');
                  } 
                  $this->mrTemplate->addVar('menu', 'MENU_PARENT', 'NO');
                  $this->mrTemplate->addVar('menu', 'IDMENU', $idmenu);  
                  $this->mrTemplate->addVar('menu', 'MENU', $menu_name);       
                  $this->mrTemplate->parseTemplate('menu', 'a');
               } 
            } 
        }
     }
     
   }
}
?>
