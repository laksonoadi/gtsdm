<?php

class ViewCombobox extends HtmlResponse {

   var $mComponentParameters;

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/combobox/template');
      $this->SetTemplateFile('view_combobox.html');
   }

   function ProcessRequest() {
		
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$return['idNama'] = $msg[0][0];
		$return['arrData'] = $msg[0][1];
		$return['id'] = $msg[0][2];
		$return['all'] = $msg[0][3];
		$return['action'] = $msg[0][4];
		$return['extended'] = isset($msg[0][5]) ? $msg[0][5] : 'auto';
      return $return;
   }

   function ParseTemplate($data = NULL) {
		
      if(!empty($data)) {
			$all = $data["all"];
			$mTemplate = "combolist";
			$mTemplateID = "COMBO";
			$mArray = $data["arrData"];
			$mId = $data["id"];
			
			
			$this->mrTemplate->addVar("combobox", "COMBO_NAME", $data["idNama"]);
			$this->mrTemplate->addVar("combobox", "ACTION", $data["action"]);
	    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
         if ($all == "true") {
    	     if ($lang=='eng'){
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- ALL --");
           }else{
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- SEMUA --"); 
           }
					$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", "all");
               $this->mrTemplate->parseTemplate("$mTemplate","a");
			} else if ($all == "false") {
  		     if ($lang=='eng'){
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- SELECT --");
           }else{
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- PILIH --"); 
           }
          $this->mrTemplate->parseTemplate("$mTemplate","a");
			}
            
            /**
             * Modified by Gagas AI
             * Added support for extended combobox using the jQuery Chosen plugin
             * Values can be :
             *  true    boolean     => use the plugin
             *  false   boolean     => don't use the plugin
             *  'auto'  string      => use the plugin when the data count is above a certain amount
             * Default value is 'auto'
             */
            $extended = $data['extended'];
            $extended_auto_threshold = 20;
            if($extended === true ||
                    ($extended === 'auto' && sizeof($mArray) > $extended_auto_threshold)) {
                $this->mrTemplate->setAttribute('combobox_extended_script', 'visibility', 'visible');
                // Clear the first option (specified by $all) and add an empty option
                //  for the placeholder
                $this->mrTemplate->clearTemplate("$mTemplate");
                $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", ""); 
                $this->mrTemplate->parseTemplate("$mTemplate", "a");
                
                // Determine the placeholder
                
                $placeholder = '';
                if ($all == "true") {
                    if ($lang=='eng') {
                        $placeholder = '-- ALL --';
                    } else {
                        $placeholder = '-- SEMUA --';
                    }
                } else if ($all == "false") {
                    if ($lang=='eng'){
                        $placeholder = '-- SELECT --';
                    }else{
                        $placeholder = '-- PILIH --';
                    }
                }
                $this->mrTemplate->addVar("combobox", "ACTION", $data["action"].' data-placeholder="'.$placeholder.'" ');
            }
            
				
				
				//print_r($mArray);exit;
		
				for ($i=0;$i<sizeof($mArray);$i++) {
					if (($mArray[$i]['id'] == trim($mId)) && ($mId != "")) {				
						$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "SELECTED");
					}
					else {
						$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "");
					}
					
					if(isset($mArray[$i]['style']) && trim($mArray[$i]['style']) != "") {
						$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_STYLE", trim($mArray[$i]['style']));
					}
					
					if(isset($mArray[$i]['combo_disabled']) && (string)$mArray[$i]['combo_disabled'] != 'false') {
						$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_IS_DISABLED", "YES");
					} else {
						$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_IS_DISABLED", "NO");
					}
		
					$this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", $mArray[$i]['id']);
					$this->mrTemplate->addVar("$mTemplate", "$mTemplateID", $mArray[$i]['name']);
		
					$this->mrTemplate->parseTemplate("$mTemplate","a");
				}
			}
      }
   }
?>
