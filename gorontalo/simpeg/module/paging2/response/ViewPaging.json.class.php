<?php

class ViewPaging extends JsonResponse {

   var $mComponentParameters;

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/paging2/template');
      $this->SetTemplateFile('view_paging.html');
   }   

   function ProcessRequest() {
      $page = false;
      $itemsViewed = 10;
      $totItems = 0;
      $url = '';
	  $dest = "sub-sub-content";

      // By default fetch param from gtfw-render module

      $itemsViewed = $this->mComponentParameters['itemviewed'];
      $totItems = $this->mComponentParameters['totitems'];
      $url = $this->mComponentParameters['pagingurl'];

      if(isset($this->mComponentParameters['page'])) {
         $page = $this->mComponentParameters['page'];
      } else
         $page = false;

      if(isset($this->mComponentParameters['nav_class'])) {
         $nav_class = $this->mComponentParameters['nav_class'];
      }  else
         $nav_class = '';

      // support component messenger
      $msg = Messenger::Instance()->Receive(__FILE__, $this->mComponentName);
      if(!empty($msg)) {
         //SysLog::Log(print_r($msg, true), 'paging');
         $itemsViewed = $msg[0][0];
         $totItems = $msg[0][1];
         $url = $msg[0][2];         
         
         if(isset($msg[0][3])){ 
            $page = $msg[0][3][0];
            $keyword = $msg[0][3][1];
            $combo = $msg[0][3][2];            
         }
         else{
            $page = false;     
            $keyword = false;
            $combo = false;
         }
		 
		 if(isset($msg[0][4])){
			$dest = $msg[0][4];
		 }
      }

      //SysLog::Log('nav_class: '.$nav_class, 'paging');
      $arrayMsg =  array("totItems"    => $totItems,
                         "itemsViewed" => $itemsViewed,
                         "url"         => $url,
                         "page"        => $page, 
                         "nav_class"   => $nav_class, 
                         "keyword"     => $keyword, 
                         "combo"       => $combo,
						 "dest"		   => $dest
                        );
      
      Messenger::Instance()->Send('paging2', 'Paging', 'view', 'html', $arrayMsg, Messenger::NextRequest);				       
      return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("'.$dest.'"," '. Dispatcher::Instance()->GetUrl('paging2', 'Paging','view','html').'&ascomponent=1")' );      
    
   }

   function ParseTemplate($data = NULL) {   
   }
}
?>
