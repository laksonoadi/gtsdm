<?php

class ViewBreadcrump extends HtmlResponse {

   var $mComponentParameters;

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/policy/template');
      $this->SetTemplateFile('view_breadcrump.html');
   }

   function ProcessRequest() {
		
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$return['currentNama'] = $msg[0][0];
		$return['arrData'] = $msg[0][1];
		$return['id'] = $msg[0][2];
		$return['all'] = $msg[0][3];
		$return['action'] =$msg[0][4];
		return $return;
   }

   function ParseTemplate($data = NULL) {
		
      if(!empty($data)) {
			$mTemplate = "breadcrump_nav_item";
			$mTemplateID = "NAV";
			$mArray = $data["arrData"];
			$mAll = $data["all"];
			
			$this->mrTemplate->addVar("breadcrump_nav", "ACTION", $data["action"]);
			$this->mrTemplate->addVar("breadcrump_nav", "NAV_TITLE", $data["currentNama"]);
			
        for ($i=0;$i<sizeof($mArray);$i++) {
          if($mAll=="hidden"){
            $this->mrTemplate->SetAttribute("$mTemplate", 'visibility', 'hidden');
          }else{
            $this->mrTemplate->SetAttribute("$mTemplate", 'visibility', 'visible');          
          }
          
			    $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_MENU", $mArray[$i]['menu']);
				  $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_URL", $mArray[$i]['url']);
		      $this->mrTemplate->parseTemplate("$mTemplate","a");
			  }
			}
    }
  }
?>
