<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class InputQuery extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_add_query.html');
   }

   function ProcessRequest() {
		$rep = new Report();
		
      $data['query'] = $rep->GetQueryById($_GET['id']);
      return $data;
   }

   function ParseTemplate($data = NULL) {
		$rep = new Report();
      $a = 0;
      $this->mrTemplate->addVar("content", "ACTION", Dispatcher::Instance()->GetUrl('report', 'onlyRunQuery', 'view', 
         'html'));

      if (isset($_GET['id'])) {
         $this->mrTemplate->addVars("content", $data['query']);
         $subJudul = 'Ubah';
         $subModule = 'updateQuery';
      } else {
         $subJudul = 'Tambah';
         $subModule = 'addQuery';
      }
      $this->mrTemplate->addVar("content", "URL_ACTION", Dispatcher::Instance()->GetUrl('report', $subModule, 'do', 
         'html'));
      $this->mrTemplate->addVar('content', 'JUDUL', $subJudul);
      
      if (isset($_GET['err'])){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', 'Nama dan query harus diisi');
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', 'notebox-warning');
      }    
   }
}
?>
