<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class InputLayoutTable extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_add_layout.html');
   }

   function ProcessRequest() {
		$rep = new Report();
		
      $data['tabel'] = $rep->GetTable();
      
      $data['subMenu'] = $rep->GetSubMenu();
      $data['layout'] = $rep->GetLayoutById($_GET['lay_id']);
      //echo '<pre>';print_r($data);//exit;
      return $data;
   }

   function ParseTemplate($data = NULL) {
      foreach($data['subMenu'] as $row => $value) {
         if ($value['dmmenuid']==$data['layout']['dmmenuparentid']) $value['select'] = 'selected'; 
         else $value['select'] = '';
         $this->mrTemplate->addVars("list_menu", $value);
         $this->mrTemplate->parseTemplate('list_menu', 'a');
      }
      foreach($data['tabel'] as $row => $value) {
         if ($value['table_id']==$data['layout']['layout_template']) $value['select'] = 'selected'; 
         else $value['select'] = '';
         $this->mrTemplate->addVars("list_layout", $value);
         $this->mrTemplate->parseTemplate('list_layout', 'a');
      }
      if (isset($_GET['lay_id'])) {
         $data['layout']['PATH'] = GTFWConfiguration::GetValue( 'application', 'domain') . 'images/icons/';
         $this->mrTemplate->addVars("content", $data['layout']);
         $subJudul = 'Edit';
         $subModule = 'updateLayoutTable';
      } else {
         $subJudul = 'Tambah';
         $subModule = 'addLayoutTable';
      }
      $this->mrTemplate->addVar('content', 'SUB', $subJudul);
      $this->mrTemplate->addVar('content', 'SUB_MODULE', $subModule);

	   if (isset($_GET['err'])) $this->mrTemplate->setAttribute('err', 'visibility', 'show');
   }
}
?>
