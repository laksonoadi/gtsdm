<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/components/business/Setting.class.php';

class ViewGetSetting extends HtmlResponse {
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/components/template');
      $this->SetTemplateFile('view_get_setting.html');
   }

   function ProcessRequest() {
      $ObjSetting = new Setting();
      $params = $this->mComponentParameters;
      
      if(!isset($params['key'])) {
			exit;
		}
      
		$value = $ObjSetting->GetValueByKey($params['key']);
      
      return $value;
   }

   function ParseTemplate($data = NULL) {
		$this->mrTemplate->addVar('content', 'VALUE', $data);
   }
}
?>
