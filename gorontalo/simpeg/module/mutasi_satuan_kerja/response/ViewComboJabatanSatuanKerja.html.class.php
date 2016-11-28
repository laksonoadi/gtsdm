<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_satuan_kerja/business/mutasi_satuan_kerja.class.php';

class ViewComboJabatanSatuanKerja extends HtmlResponse
{
   	function TemplateModule() {
      	
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_satuan_kerja/'.GTFWConfiguration::GetValue('application', 'template_address').'');
        $this->SetTemplateFile('view_combo_jabatan.html');
   	}
    
    function ProcessRequest() {
        
        
        $get = $_GET;
        // print_r($unit_id);
        
        $jabatan = new MutasiSatuanKerja;
          $jabatanlist  = $jabatan->GetJabBySatker($get['parent']);
        
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