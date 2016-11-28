<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewRunTable extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_run_table.html');
   }

   function ProcessRequest() {
      $rep = new Report();
		
		$data['data'] = $rep->GetTableById($_GET['tab_id']);
		$data['tab_id'] = $_GET['tab_id'];
		
      return $data;
   }

   function ParseTemplate($data = NULL) {      
      $rep = new Report();

//
//========================= begin test ================================

   $unitId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUnitId();
   $groupId = Security::Instance()->mAuthentication->GetCurrentUser()->GetActiveUserGroupId();

   $query = $rep->GetQueryById($data['tab_id']);   
	
	$array = $rep->RunQuery($query['query_sql'], array($query['query_param']));
	
	print_r($data['data']);
   $data['list'] .= '<div class="popup-content-title">Run Query</div>';
   $url = Dispatcher::Instance()->GetUrl('license_own', 'licenseOwn', 'view', 'html');
   $urlhover = Dispatcher::Instance()->GetUrl('popups', 'Hover', 'popup', 'html');
   $data['list'] .= $rep->DataList($array['0'], $url, $urlhover);
	

//========================= end test ===================================
//comment this line below to try in mode file

      /*if ($data['data']['TABLE_IS_GRAPHIC']==0) eval($data['data']['TABLE_PHP_CODE']);
      else {
         $url = Dispatcher::Instance()->GetUrl('bo_template', 'reportGraphic', 'view', 'img').'&tab_id='.$_GET['tab_id'];
         $data['tabel'] = '<img src="'.$url.'" />';
    	}*/

//end comment
      $data['data']['balik'] = Dispatcher::Instance()->GetUrl('report', 'table', 'view', 'html');
      $this->mrTemplate->addVars('content', $data['data']);
      $this->mrTemplate->addVar('content', 'HASIL', $data['list']);
   }
}
?>
