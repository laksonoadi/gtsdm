<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';

class ViewChangePassword extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir( GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/user/template');
      $this->SetTemplateFile('change_password.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $return['Pesan'] = $msg[0][1];
      $return['Data'] = $msg[0];

      $return['usr'] = Dispatcher::Instance()->Decrypt($_REQUEST['usr']);
      return $return;
   }

   function ParseTemplate($data = NULL) {

      $this->mrTemplate->AddVar('content', 'USER_ID', $data['usr']);

      if ($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         
         $this->mrTemplate->AddVar('content', 'USER_ID', $data['Data'][0]['usr']);
      }

      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('user', 'updatePassword', 'do', 'html') );
   }
}
?>
