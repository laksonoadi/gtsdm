<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';

class ViewComboGroup extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/user/template');
      $this->SetTemplateFile('view_combo_group.html');
   }

   function ProcessRequest() {
      $idUnit = $_REQUEST['unit'];
      $applicationId = GTFWConfiguration::GetValue('application', 'application_id');
      $userObj = new AppUser();
      $dataGroup = $userObj->GetDataGroupByUnitId("", $idUnit, $applicationId);
      $return['dataGroup'] = $dataGroup;
      return $return;
   }

   function ParseTemplate($data = NULL) {

      if(!empty($data)) {
         $dataGroup = $data["dataGroup"];
         $all = "false";
         $mTemplate = "combolist";
         $mTemplateID = "COMBO";
         
         $this->mrTemplate->addVar("combobox", "COMBO_NAME", 'group');

         if ($all == "true") {
               $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- SEMUA --");
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", "all");
               $this->mrTemplate->parseTemplate("$mTemplate","a");
         } else if ($all == "false") {
                  $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- PILIH --");
                  $this->mrTemplate->parseTemplate("$mTemplate","a");
         }

         for ($i=0;$i<sizeof($dataGroup);$i++) {
            if (($dataGroup[$i]['id'] == trim($mId)) && ($mId != "")) {
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "SELECTED");
            }
            else {
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "");
            }
   
            $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", $dataGroup[$i]['id']);
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", $dataGroup[$i]['name']);
   
            $this->mrTemplate->parseTemplate("$mTemplate","a");
         }
      }
   }
}
?>
