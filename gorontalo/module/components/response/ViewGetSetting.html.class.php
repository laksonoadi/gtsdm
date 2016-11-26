<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/components/business/GetSetting.class.php';

class ViewGetSetting extends HtmlResponse {
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/components/template');
      $this->SetTemplateFile('view_get_setting.html');
   }

   function ProcessRequest() {
      $objGetSetting = new GetSetting();
      $params = $this->mComponentParameters;
      
      if(!isset($params['key'])) {
			exit;
		}
      
		$value = $objGetSetting->GetValueByKey($params['key']);
      
      return $value;
   }

   function ParseTemplate($data = NULL) {
		$this->mrTemplate->addVar('content', 'VALUE', $data);
   }
}
?>
