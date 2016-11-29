<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';

class DoLogin extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {
    
      if (Security::Instance()->Login($_REQUEST['username'].'', $_REQUEST['password'].'', $_REQUEST['hashed'].'' == 1)) {
	     $this->userObj = new AppUser();
		 $pernahLogin = $this->userObj->PernahLogin($_REQUEST['username']);
		 //$pernahLogin = $this->userObj->GetDataUserByUsername($_REQUEST['username']);
		 
		 // redirect to proper place
		 if ($pernahLogin===true){
			$module = 'home';
	        $submodule = 'home';
	        $action = 'view';
	        $type = 'html';
		 }else{
			$module = 'ganti_password';
	        $submodule = 'changePassword';
	        $action = 'view';
	        $type = 'html';
		 }
         
         $this->RedirectTo(Dispatcher::Instance()->GetUrl($module, $submodule, $action, $type));
         return;
      } else {
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('login_default', 'login', 'view', 'html') . '&fail=1');
         return;
      }
      return NULL;
   }

   function ParseTemplate($data = NULL) {
   }
}
?>
