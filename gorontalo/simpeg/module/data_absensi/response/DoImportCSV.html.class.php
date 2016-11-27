<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/'.Dispatcher::Instance()->mModule.'/response/ProcessImport.proc.class.php';

class DoImportCSV extends HtmlResponse
{
   function TemplateModule ()
   {
   }
   
   function ProcessRequest ()
   {
      $Obj = new ProcessImport;
      $url['canceled'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'list', 'view', 'html')."&spModule=budgetSearch";
      $url['redo'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'importCSV', 'view', 'html');
      $url['success'] = 
      $url['failed'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'list', 'view', 'html')."&spModule=budgetSearch";
      
      $param = $Obj->ImportCSV();
      
      $url = $url[$param['status']];
      if (isset($param['id'])) $url .= "&id=".$param['id'];
      
      $this->RedirectTo($url);
      return NULL;
   }

   function ParseTemplate ($data = NULL)
   {
   }
}
?>