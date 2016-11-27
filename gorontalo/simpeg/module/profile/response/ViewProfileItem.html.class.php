<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/profile/business/AppProfile.class.php';
class ViewProfileItem extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/profile/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_profile_item.html');
   }
   
   function ProcessRequest() {
      $params = $this->mComponentParameters;
      $get = $_GET->AsArray();
      
      $display = isset($params['item']) ? $params['item'] : (isset($get['item']) ? $get['item'] : 'username');
      
      $profileObj = new AppProfile();
      
      $user = $profileObj->GetDataUserByUsername($_SESSION['username']);
      
      return compact('user', 'display');
   }

   function ParseTemplate($data = NULL) {
      extract($data);
      $user = $user[0];
      
      switch($display) {
          case 'name':
          case 'real_name':
          case 'realname':
              $data = $user['real_name'];
              break;
          case 'group':
          case 'group_name':
          case 'groupname':
              $data = $user['group_name'];
              break;
          case 'desc':
          case 'description':
              $data = $user['description'];
              break;
          default:
              $data = $user['user_name'];
              break;
      }
      
      $this->mrTemplate->addVar('content', 'DATA', $data);
   }
}
?>
