<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';

class ViewComboPegawai extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/user_portal/template');
      $this->SetTemplateFile('view_combo_pegawai.html');
   }

   function ProcessRequest() {
      $idGroup = $_REQUEST['group'];
      $applicationId = GTFWConfiguration::GetValue('application', 'application_portal_id');
      $userObj = new AppUser();
      $dataPegawai = $userObj->GetDataPegawaiByGroup($idGroup);
      $return['dataPegawai'] = $dataPegawai;
      return $return;
   }

   function ParseTemplate($data = NULL) {

      if(!empty($data)) {
         $dataGroup = $data["dataPegawai"];
         $all = "false";
         $mTemplate = "combolist";
         $mTemplateID = "COMBO";
         
         $this->mrTemplate->addVar("combobox", "COMBO_NAME", 'pegawai');

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
