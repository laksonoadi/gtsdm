<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pangkat_golongan/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/pangkat_golongan.class.php';

class ViewInputPangkatGolongan extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/pangkat_golongan/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('input_pangkat_golongan.html');    
   } 
   
   function ProcessRequest() {
      $pg_obj = new PangkatGolongan();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      $data['lang'] =$lang;
      
      if (!empty($id))
         $data['pg'] = $pg_obj->GetPangkatGolonganById($id);
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
       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('sks_dosen', 'SksDosen', 'view', 'html') );
          if ($data['lang']=='eng'){
            $this->mrTemplate->AddVar('content', 'TITLE', 'GRADE');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', !empty($data['pg']) ? 'Update' : 'Add');
            $this->mrTemplate->AddVar('content', 'JUDUL', !empty($data['pg']) ? 'Update' : 'Add');
         }else{
            $this->mrTemplate->AddVar('content', 'TITLE', 'PANGKAT/GOLONGAN');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', !empty($data['pg']) ? 'Ubah' : 'Tambah');
            $this->mrTemplate->AddVar('content', 'JUDUL', !empty($data['pg']) ? 'Ubah' : 'Tambah');    
        }
       
       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('pangkat_golongan', 'PangkatGolongan', 'view', 'html') );
       
       if(empty($data['pg'])){
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pangkat_golongan', 'addPangkatGolongan', 'do', 'html') );
         $this->mrTemplate->AddVar("content", "TINGKAT", '0');
         $this->mrTemplate->AddVar("content", "MASA", '0');
       }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pangkat_golongan', 'updatePangkatGolongan', 'do', 'html') );
         //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pangkat_golongan','UpdatePangkatGolongan','do','html');
            //print_r($data['pg']);
            $this->mrTemplate->AddVar("content", "PANGKAT", $data['pg'][0]['pangkat']);
            $this->mrTemplate->AddVar("content", "NAMA", $data['pg'][0]['nama']);
            $this->mrTemplate->AddVar("content", "TINGKAT", $data['pg'][0]['tingkat']);
            $this->mrTemplate->AddVar("content", "MASA", $data['pg'][0]['masa']);
            $this->mrTemplate->AddVar("content", "URUT", $data['pg'][0]['urut']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>
