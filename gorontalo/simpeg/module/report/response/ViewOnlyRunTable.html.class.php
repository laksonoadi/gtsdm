<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewOnlyRunTable extends HtmlResponse {

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
      $pol = $_POST;
      
      $rep = new Report();
      $table = stripslashes($_POST['tempParam']->Raw()).stripslashes($_POST['temp']->Raw());
      if ($_POST['graphic']=='0') {
         eval($table);
      } else {
         $url = Dispatcher::Instance()->GetUrl('template', 'reportGraphic', 'view', 'img').'&tab_id='.$_GET['tab_id'];
         $data['tabel'] = '<img src="'.$url.'" />';
    	}
      $this->mrTemplate->addVar('content', 'HASIL', $data['filter'].$data['tabel']);
   }
}
?>
