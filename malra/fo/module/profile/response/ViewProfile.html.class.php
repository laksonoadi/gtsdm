<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppProfile.class.php';
class ViewProfile extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/profile/template');
      $this->SetTemplateFile('view_profile.html');
   }
   
   function ProcessRequest() {
      $profileObj = new AppProfile();    
      $dataUser = $profileObj->GetDataUserByUsername($_SESSION['username']);
      $dataGroup = $profileObj->GetDataGroupById($dataUser[0]['group_id']);
		$msg = Messenger::Instance()->Receive(__FILE__);
		
      $return['pesan']=$msg[0][1];
      $return['css']=$msg[0][2];
      $return['dataUser'] = $dataUser;
      $return['dataGroup'] = $dataGroup;
      return $return;
   }

   function ParseTemplate($data = NULL) {
      $dataUser = $data['dataUser'];
      $dataGroup = $data['dataGroup'];
      $instUser = $data['instUser'];
      $this->mrTemplate->AddVar('content', 'PENGGUNA', $dataUser[0]['real_name']);
      $this->mrTemplate->AddVar('content', 'NO_PEGAWAI', $dataUser[0]['no_pegawai']);
      $this->mrTemplate->AddVar('content', 'URL_UPDATE_PASSWORD', Dispatcher::Instance()->GetUrl('profile', 'updatePassword', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'PROFILE_URL_EDIT', Dispatcher::Instance()->GetUrl('profile', 'inputProfile', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'USERNAME', $dataUser[0]['user_name']);
      $this->mrTemplate->AddVar('content', 'DESKRIPSI', $dataUser['0']['description']);
      
      if ($dataUser[0]['is_active'] == 'Yes') {
         $dataUser[0]['status'] = 'aktif';
      } else {
         $dataUser[0]['status'] = 'tidak aktif';
      }
      $this->mrTemplate->AddVar('content', 'STATUS', $dataUser[0]['status']);
      $this->mrTemplate->AddVar('content', 'NAMA_GROUP', $dataUser[0]['group_name']);
     
      $menuId = explode('|', $dataGroup[0]['menu_id']);                    
      $menuName = explode('|', $dataGroup[0]['menu_name']);
      $parentMenu = explode('|', $dataGroup[0]['parent_menu']);
      $mlen=sizeof($menuId);
      $s=0;

      for ($m=0;$m<$mlen;$m++) {    
         if ($parentMenu[$m]==0) {
         $menuBaru[$s]='<b>'.$menuName[$m].'</b><br>';
         for ($mm=0;$mm<$mlen;$mm++) {
           if ($menuId[$m]==$parentMenu[$mm]) $menuBaru[$s]=$menuBaru[$s].'&nbsp;&nbsp;'.$menuName[$mm].'<br>';
         }
         $s++;
         }
      }

      for ($k=0;$k<$s;$k++) {
         $this->mrTemplate->AddVar('list_hak_akses', 'HAK_AKSES', $menuBaru[$k]);
         $this->mrTemplate->parseTemplate('list_hak_akses', 'a');
      }      
            
		if($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
   }
}
?>
