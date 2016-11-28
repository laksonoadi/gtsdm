<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_fungsional/business/jabatan_fungsional.class.php';

class ViewComboJabatanFungsional extends HtmlResponse
{
   	function TemplateModule() {
      	
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_jabatan_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
        $this->SetTemplateFile('view_combo_jabatan.html');
   	}
    
    function ProcessRequest() {
        
        
        $get                = $_GET;
        // print_r($unit_id);
        
        $jabatan = new JabatanFungsional;
          $jabatanlist  = $jabatan->GetJenStrukById($get['parent']);
        
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