<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pelatihan/business/mutasi_pelatihan.class.php';

class ViewComboJenisPelatihan extends HtmlResponse
{
   	function TemplateModule() {
      	
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_pelatihan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
        $this->SetTemplateFile('view_combo_pelatihan.html');
   	}
    
    function ProcessRequest() {
        
        
        $get                = $_GET;
        // print_r($unit_id);
        
        $jabatan = new MutasiPelatihan;
          $jabatanlist  = $jabatan->GetJenStrukById($get['parent']);
        // print_r($jabatanlist);exit();
        // print_r($datakab);
        $return['listjabatan'] = $jabatanlist;
        return $return;
    }
    
    function ParseTemplate($data = NULL)
   {
      foreach ($data['listjabatan'] as $item_id => $item) {
                $this->mrTemplate->addVars('item',$item);
                $this->mrTemplate->ParseTemplate('item','a');
            }
    }   
    
}

?>