<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';
  
class ViewDataPegawaiLogin extends HtmlResponse
{  
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_pegawai_login.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new DataPegawai();
         
      $id = $Obj->GetPegIdByUserName();
  		$dataPegawai = $Obj->GetDataById($id);
  		$return['dataPegawai'] = $dataPegawai;
  		
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {            
  		$dataPegawai = $data['dataPegawai'];
  		$ObjDatPeg = new DataPegawai();
        $pegId = $ObjDatPeg->GetPegIdByUserName();
        if (($pegId==-1)||($pegId=='-1')||empty($pegId)){
            $this->mrTemplate->AddVar('login', 'IS_LOGIN', 'NO');          
        }else {
            $this->mrTemplate->AddVar('login', 'IS_LOGIN', 'YES');
			
			
            if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['pegFoto']) | empty($dataPegawai['pegFoto'])) { 
    		 	$this->mrTemplate->AddVar('content2', 'FOTO2', GTFWConfiguration::GetValue( 'application', 'photo_download_path').'unknown.gif');
      	  	}else{
				$this->mrTemplate->AddVar('content2', 'FOTO2', GTFWConfiguration::GetValue( 'application', 'photo_download_path').$dataPegawai['pegFoto']);
            }
            $this->mrTemplate->AddVar('content2', 'ID', $dataPegawai['pegId']);
            $this->mrTemplate->AddVar('content2', 'NIP', $dataPegawai['pegKodeResmi']);
            $this->mrTemplate->AddVar('content2', 'NAMA', $dataPegawai['pegNama']);
            $this->mrTemplate->AddVar('content2', 'ALAMAT', $dataPegawai['pegAlamat']);
        }
   }
}
   

?>