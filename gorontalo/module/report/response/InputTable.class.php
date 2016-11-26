<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class InputTable extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_add_table.html');
   }

   function ProcessRequest() {
		$rep = new Report();
		
      $data['query'] = $rep->GetQuery();
      $data['tabel'] = $rep->GetTableById($_GET['tab_id']);
		
      return $data;
   }

   function ParseTemplate($data = NULL) {
      $a = 7;
      foreach($data['query'] as $row => $value) {
			$str .= "d.add($a,6,'<a href=\"#\" onClick=\"clickTable(this.innerHTML)\">".$value['query_koneksi'].' : '.
            $value['query_id'].' : '.$value['query_nama']."</a>');\n";
         $a++;
      }
      $this->mrTemplate->addVar("content", "TABEL", $str);
      $this->mrTemplate->addVar("content", "ACTION", Dispatcher::Instance()->GetUrl('report', 'onlyRunTable', 'view', 
         'html'));

      if (isset($_GET['tab_id'])) {
         if ($data['tabel']['table_is_graphic']==1) $data['tabel']['select'] = 'selected';
         $this->mrTemplate->addVars("content", $data['tabel']);
         $subModule = 'updateTable';
         $subJudul = 'Edit';
      } else {
         $subJudul = 'Tambah';
         $subModule = 'addTable';
      }
      $this->mrTemplate->addVar('content', 'JUDUL', $subJudul);
      $this->mrTemplate->addVar("content", "URL_ACTION", Dispatcher::Instance()->GetUrl('report', $subModule, 'do', 'html'));

      if (isset($_GET['err'])){
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', 'Nama dan kode php harus diisi');
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', 'notebox-warning');
      }    
   }
}
?>
