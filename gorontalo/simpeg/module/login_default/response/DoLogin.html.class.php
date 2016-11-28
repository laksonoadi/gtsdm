<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class DoLogin extends HtmlResponse {

   function TemplateModule() {
   }

   function ProcessRequest() {
    
      if (Security::Instance()->Login($_REQUEST['username'].'', $_REQUEST['password'].'', $_REQUEST['hashed'].'' == 1)) {
	     $this->Obj = new SatuanKerja();
	     $result = $this->Obj->GetSatuanKerjaByUserId();
		 $_SESSION['unit_id']=$result[0]['unitId'];
		 $_SESSION['unit_kerja']=$result[0]['id'];
		 $_SESSION['unit_kerja_name']=$result[0]['name'];
		 
         // redirect to proper place
         $home = GtfwCfg('application', 'default_page');
        $urlHome = Dispatcher::Instance()->GetUrl($home['mod'], $home['sub'], $home['act'], $home['typ']);
         
         $this->RedirectTo($urlHome);
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
