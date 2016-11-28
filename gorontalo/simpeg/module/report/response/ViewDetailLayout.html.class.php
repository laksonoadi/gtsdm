<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/bo_report/business/Report.class.php';

class ViewDetailLayout extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/bo_report/template');
      $this->SetTemplateFile('view_detail_layout.html');
   }

   function ProcessRequest() {
      $rep = new Report();
		
		$data = $rep->GetLayoutById($_GET['lay_id']);
      return $data;
   }

   function ParseTemplate($data = NULL) {
      
		$this->mrTemplate->addVars('content', $data);

 	   $this->mrTemplate->addVar('body', 'navigation', '&gt; <a href="'.$this->mrDispatcher->GetUrl('bo_report', 
         'query', 'view', 'html').'">Query</a> &gt; Detil Tabel/Grafik');
   }
}
?>
