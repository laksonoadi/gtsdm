<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewRunQuery extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_run_query.html');
   }

   function ProcessRequest() {
      $rep = new Report();		
		$data['query'] = $rep->GetQueryById($_GET['que_id']);
      $query = $data['query']['query_sql'];
      $param = explode(',', $data['query']['query_param']);
      if ($query!='') {
   		$array = $rep->RunQuery($query, $param);
         $data['tabel'] = $rep->HeaderTableForRunQuery($array);
         $data['tabel'] .= $rep->DataTable($array);
         $data['tabel'] .= $rep->CloseTable($array);
      } else $data['tabel'] = 'Tidak terdapat keluaran hasil';
      
      // generate URL
      $data['url']['balik'] = Dispatcher::Instance()->GetUrl('report', 'query', 'view', 'html');
      // ---------
      
      return $data;
   }

   function ParseTemplate($data = NULL) {
      $this->mrTemplate->addVars('content', $data['url'], 'URL_');
		$this->mrTemplate->addVars('content', $data['query']);
      $this->mrTemplate->addVar('content', 'HASIL', $data['tabel']);
   }
}
?>
