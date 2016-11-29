<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewDetailTable extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_detail_table.html');
   }

   function ProcessRequest() {
      $rep = new Report();
		
		$data = $rep->GetTableById($_GET['tab_id']);
      return $data;
   }

   function ParseTemplate($data = NULL) {      
      if ($data['TABLE_IS_GRAPHIC']==0) $data['table_jenis'] = 'Tabel';
      else $data['table_jenis'] = 'Grafik';
      $data['balik'] = Dispatcher::Instance()->GetUrl('report', 'table', 'view', 'html');
		$this->mrTemplate->addVars('content', $data);
   }
}
?>
