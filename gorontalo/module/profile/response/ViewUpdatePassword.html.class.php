<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppProfile.class.php';

class ViewUpdatePassword extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir( GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/profile/template');
      $this->SetTemplateFile('ubah_password.html');
   }
   
   function ProcessRequest() {
     $profileObj = new AppProfile();
     $msg = Messenger::Instance()->Receive(__FILE__);
     $dataUserByUsername=$profileObj->GetDataUserByUsername($_SESSION['username']);
     $return['dataId']=$dataUserByUsername[0]['user_id'];
     $return['pesan']=$msg[0][1];
     $return['data']=$msg[0][0];   
     return $return;
   }

   function ParseTemplate($data = NULL) {

		if ($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
		}
      $this->mrTemplate->AddVar('content','DATA_ID',$data['dataId']);
      $this->mrTemplate->AddVar('content', 'RETURN_PAGE', $_GET['returnPage']);
      $this->mrTemplate->AddVar('content', 'URL_EDIT', Dispatcher::Instance()->GetUrl('profile', 'updatePassword', 'do', 'html'));
   }
}
?>
