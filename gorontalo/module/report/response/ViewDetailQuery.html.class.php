<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewDetailQuery extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_detail_query.html');
   }

   function ProcessRequest() {
      $rep = new Report();
		
		$data = $rep->GetQueryById($_GET['que_id']);
      return $data;
   }

   function ParseTemplate($data = NULL) {
      $data['balik'] = Dispatcher::Instance()->GetUrl('report', 'query', 'view', 'html');;
		$this->mrTemplate->addVars('content', $data);
   }
}
?>
