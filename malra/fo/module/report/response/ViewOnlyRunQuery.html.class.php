<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewOnlyRunQuery extends HtmlResponse {

   function TemplateBase() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'main/template/');  
      $this->SetTemplateFile('layout-common-blank.html');
   }

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_only_run_query.html');
   }

   function ProcessRequest() {
   }

   function ParseTemplate($data = NULL) {
      $rep = new Report();
      $temp = stripslashes($_POST['temp']);
      $param = explode(',', stripslashes($_POST['param']));
      if ($temp!='') {
         $array = $rep->RunQuery($temp, $param);
   
         $data['tabel'] = $rep->HeaderTableForRunQuery($array);
         $data['tabel'] .= $rep->DataTable($array);
         $data['tabel'] .= $rep->CloseTable($array);
      } else $data['tabel'] = 'Tidak terdapat query';
      $this->mrTemplate->addVar('content', 'HASIL', $data['tabel']);
   }
}
?>
