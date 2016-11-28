<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/kode_nikah.class.php';

class ViewInputKodeNikah extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('input_kode_nikah.html');    
   } 
   
   function ProcessRequest() {
      $kn_obj = new KodeNikah();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      
      if (!empty($id))
         $data['kn'] = $kn_obj->GetKodeNikahById($id);
         
         //print_r($data['pg']);
         $msg = Messenger::Instance()->Receive(__FILE__);
         $data['pesan'] = $msg[0][1];
         $data['css'] =$msg[0][2];
         return $data;   
   }  
   
   function ParseTemplate ($data = NULL){
       if ($data['pesan']){
         $this->mrTemplate->SetAttribute('warning_box','visibility','visible');
         $this->mrTemplate->AddVar('warning_box','ISI_PESAN',$data['pesan']);
         $this->mrTemplate->AddVar('warning_box','CLASS_PESAN',$data['css']);
       }
       
       $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'MARRIAGE CODE REFERENCE');
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI KODE NIKAH');
       }
       
       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('kode_nikah', 'KodeNikah', 'view', 'html') );
       $this->mrTemplate->AddVar('content', 'URL_POPUP_GAJI', Dispatcher::Instance()->GetUrl('kode_nikah', 'popupGaji', 'view', 'html'));
       
       
       if(empty($data['kn'])){
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('kode_nikah', 'addKodeNikah', 'do', 'html') );
            if ($buttonlang=='eng'){
                 $this->mrTemplate->AddVar("content", "BUTTONLABEL", "Add");
                 $this->mrTemplate->AddVar("content", "JUDUL", "Add");
             }else{
                 $this->mrTemplate->AddVar("content", "BUTTONLABEL", "Tambah");
                 $this->mrTemplate->AddVar("content", "JUDUL", "Tambah");
            }
            
       }else{
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('kode_nikah', 'updateKodeNikah', 'do', 'html') );
            //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pak','UpdatePangkatGolongan','do','html');
            //print_r($data['kn']);		        
		        if ($buttonlang=='eng'){
                 $this->mrTemplate->AddVar("content", "BUTTONLABEL", "Update");
                 $this->mrTemplate->AddVar("content", "JUDUL", "Update");
             }else{
                 $this->mrTemplate->AddVar("content", "BUTTONLABEL", "Ubah");
                 $this->mrTemplate->AddVar("content", "JUDUL", "Ubah");
            }
            $this->mrTemplate->AddVar("content", "ID", $data['kn'][0]['id']);
            $this->mrTemplate->AddVar("content", "KODE", $data['kn'][0]['kode']);
            $this->mrTemplate->AddVar("content", "NAMA", $data['kn'][0]['nama']);
            $this->mrTemplate->AddVar("content", "KOMP", $data['kn'][0]['kompid']);
            $this->mrTemplate->AddVar("content", "KOMPLABEL", $data['kn'][0]['kompnama']);
            //$this->mrTemplate->AddVar("content", "URUT", $data['pg'][0]['urut']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>
