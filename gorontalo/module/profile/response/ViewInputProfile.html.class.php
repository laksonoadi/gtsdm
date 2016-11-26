<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppProfile.class.php';

class ViewInputProfile extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/profile/template');
      $this->SetTemplateFile('input_profile.html');
   }
   
   function ProcessRequest() {
     $profileObj = new AppProfile();
     $dataUser = $profileObj->GetDataUserByUsername($_SESSION['username']);  
     $msg = Messenger::Instance()->Receive(__FILE__);
     $return['pesan']=$msg[0][1];
     $return['data']=$msg[0][0];   
     $return['dataUser']=$dataUser;
     return $return;
   }

   function ParseTemplate($data = NULL) {

		if ($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
		}

      $dataUser=$data['dataUser'];
      $this->mrTemplate->AddVar('content', 'URL_ACTION',  Dispatcher::Instance()->GetUrl('profile', 'updateProfile', 'do', 'html'));
      $this->mrTemplate->AddVar('content', 'REALNAME', empty($dataUser[0]['real_name'])?$data['data']['realname']:$dataUser[0]['real_name']);
      $this->mrTemplate->AddVar('content', 'DESKRIPSI', empty($dataUser[0]['description'])?$data['data']['deskripsi']:$dataUser[0]['description']);
      $this->mrTemplate->AddVar('content','DATA_ID',$dataUser[0]['user_id']);

   }
}
?>
