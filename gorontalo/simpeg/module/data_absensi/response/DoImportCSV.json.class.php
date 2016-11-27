<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/'.Dispatcher::Instance()->mModule.'/response/ProcessImport.proc.class.php';

class DoImportCSV extends JsonResponse
{
   function TemplateModule ()
   {
   }
   
   function ProcessRequest ()
   {
      $Obj = new ProcessImport;
      $url['canceled'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'search', 'view', 'html');
      $url['redo'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'search', 'view', 'html');
      $url['success'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'list', 'view', 'html')."&spModule=budgetDetail";
      $url['failed'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'search', 'view', 'html');
      
      $param = $Obj->ImportCSV();
      if (!isset($param['status'])) $param['status'] = 'canceled';
      
      if (isset($param['id']) && in_array($param['status'], array('canceled', 'failed')))
         $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'detail', 'view', 'html');
      else $url = $url[$param['status']];
      if (isset($param['id'])) $url .= "&id=".$param['id'];
      if (in_array($param['status'], array('success')))
         $targetDiv = 'subcontent-element';
      else $targetDiv = 'containerAddEditDetail';
      
      if (isset($param['message']))
      {
         if ($param['status'] == 'redo') Messenger::Instance()->Send(Dispatcher::Instance()->mModule, 'search', 'view', 'html', $param['message'], Messenger::NextRequest);
         elseif ($param['status'] == 'success') Messenger::Instance()->Send(Dispatcher::Instance()->mModule, 'list', 'view', 'html', $param['message'], Messenger::NextRequest);
         elseif ($param['status'] == 'failed' && isset($param['id'])) Messenger::Instance()->Send(Dispatcher::Instance()->mModule, 'detail', 'view', 'html', $param['message'], Messenger::NextRequest);
         elseif ($param['status'] == 'failed') Messenger::Instance()->Send(Dispatcher::Instance()->mModule, 'search', 'view', 'html', $param['message'], Messenger::NextRequest);
      }
      
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("'.$targetDiv.'","'.htmlentities($url).'&amp;ascomponent=1")');  
   }

   function ParseTemplate ($data = NULL)
   {
   }
}
?>