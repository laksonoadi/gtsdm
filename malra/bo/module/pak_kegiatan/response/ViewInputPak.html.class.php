<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_kegiatan/business/pak.class.php';

class ViewInputPak extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/pak_kegiatan/template/');
      $this->SetTemplateFile('input_pak.html');    
   } 
   
   function ProcessRequest() {
      $pak_obj = new Pak();
      $arrUnsur = $pak_obj->GetComboUnsur();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      
      if (!empty($id))
         $data['pak'] = $pak_obj->GetPakById($id);
         
         //print_r($data['pg']);
         $msg = Messenger::Instance()->Receive(__FILE__);
         $data['pesan'] = $msg[0][1];
         $data['css'] =$msg[0][2];
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unsur', array('unsur', $arrUnsur, $data['pak'][0]['unsurId'], '', ''), Messenger::CurrentRequest);
         return $data;   
   }  
   
   function ParseTemplate ($data = NULL){
       if ($data['pesan']){
         $this->mrTemplate->SetAttribute('warning_box','visibility','visible');
         $this->mrTemplate->AddVar('warning_box','ISI_PESAN',$data['pesan']);
         $this->mrTemplate->AddVar('warning_box','CLASS_PESAN',$data['css']);
       }
       
       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('pak_kegiatan', 'Pak', 'view', 'html') );
       
       
       if(empty($data['pak'])){
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pak_kegiatan', 'addPak', 'do', 'html') );
            $this->mrTemplate->AddVar('content', 'JUDUL', 'Tambah' );
       }else{
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pak_kegiatan', 'updatePak', 'do', 'html') );
            $this->mrTemplate->AddVar('content', 'JUDUL', 'Ubah' );
            //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pak','UpdatePangkatGolongan','do','html');
            //print_r($data['pak']);
		        
		    $this->mrTemplate->AddVar("content", "ID", $data['pak'][0]['id']);
            $this->mrTemplate->AddVar("content", "NAMA", $data['pak'][0]['nama']);
            $this->mrTemplate->AddVar("content", "ANGKA_KREDIT", $data['pak'][0]['angka_kredit']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>
