<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewComboSatuanKerja extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
         'module/user/template');
      $this->SetTemplateFile('view_combo_satuan_kerja.html');
   }

   function ProcessRequest() {
      $idUnit = $_REQUEST['unit'];
      $applicationId = GTFWConfiguration::GetValue('application', 'application_id');
      $userObj = new AppUser();
      $satkerObj = new SatuanKerja;
      $dataSatuanKerja = $satkerObj->GetComboSatuanKerja($idUnit);
      $return['dataSatuanKerja'] = $dataSatuanKerja;
      return $return;
   }

   function ParseTemplate($data = NULL) {

      if(!empty($data)) {
         $dataSatuanKerja = $data["dataSatuanKerja"];
         $all = "false";
         $mTemplate = "combolist";
         $mTemplateID = "COMBO";
         
         $this->mrTemplate->addVar("combobox", "COMBO_NAME", 'satuan_kerja');

         if ($all == "true") {
               $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- SEMUA --");
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", "all");
               $this->mrTemplate->parseTemplate("$mTemplate","a");
         } else if ($all == "false") {
                  $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", "-- PILIH --");
                  $this->mrTemplate->parseTemplate("$mTemplate","a");
         }

         for ($i=0;$i<sizeof($dataSatuanKerja);$i++) {
            if (($dataSatuanKerja[$i]['id'] == trim($mId)) && ($mId != "")) {
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "SELECTED");
            }
            else {
               $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_SELECTED", "");
            }
   
            $this->mrTemplate->addVar("$mTemplate", $mTemplateID."_VALUE", $dataSatuanKerja[$i]['id']);
            $this->mrTemplate->addVar("$mTemplate", "$mTemplateID", $dataSatuanKerja[$i]['name']);
   
            $this->mrTemplate->parseTemplate("$mTemplate","a");
         }
      }
   }
}
?>
