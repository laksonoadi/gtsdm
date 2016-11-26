<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class InputLayoutGraphic extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_add_layout_graphic.html');
   }

   function ProcessRequest() {
		$rep = new Report();
		
      $data['tabel'] = $rep->GetTable();
      $data['layout'] = $rep->GetLayout();
      $data['graphic'] = $rep->GetGraphicById($_GET['graph_id']);
      //echo '<pre>';print_r($data);
      return $data;
   }

   function ParseTemplate($data = NULL) {

      foreach($data['tabel'] as $row => $value) {
         if ((int)$value['table_id']==(int)$data['graphic']['graphic_table_id']) $value['select'] = 'selected';
         else $value['select'] = '';
         $this->mrTemplate->addVars("list_table", $value);
         $this->mrTemplate->parseTemplate('list_table', 'a');
      }
      foreach($data['layout'] as $row => $value) {
         if ((int)$value['layout_id']==(int)$data['graphic']['graphic_layout_id']) $value['select'] = 'selected'; 
         else $value['select'] = '';
         $this->mrTemplate->addVars("list_layout", $value);
         $this->mrTemplate->parseTemplate('list_layout', 'a');
      }
      if (isset($_GET['graph_id'])) {
         $this->mrTemplate->addVars("content", $data['graphic']);
         $subJudul = 'Edit';
         $subModule = 'updateLayoutGraphic';
      } else {
         $subJudul = 'Tambah';
         $subModule = 'addLayoutGraphic';
      }
      $this->mrTemplate->addVar('content', 'SUB', $subJudul);
      $this->mrTemplate->addVar('content', 'SUB_MODULE', $subModule);

	   if (isset($_GET['err'])) $this->mrTemplate->setAttribute('err', 'visibility', 'show');
   }
}
?>
