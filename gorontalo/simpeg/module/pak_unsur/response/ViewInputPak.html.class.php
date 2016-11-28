<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/business/pak.class.php';

class ViewInputPak extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/pak_unsur/template/');
      $this->SetTemplateFile('input_pak.html');    
   } 
   
   function ProcessRequest() {
      $pak_obj = new Pak();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      
      if (!empty($id))
         $data['pak'] = $pak_obj->GetPakById($id);
         
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
       
       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('pak_unsur', 'Pak', 'view', 'html') );
       
       
       if(empty($data['pak'])){
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pak_unsur', 'addPak', 'do', 'html') );
            $this->mrTemplate->AddVar('content', 'JUDUL', 'Tambah' );
       }else{
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pak_unsur', 'updatePak', 'do', 'html') );
            $this->mrTemplate->AddVar('content', 'JUDUL', 'Ubah' );
            //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pak','UpdatePangkatGolongan','do','html');
            //print_r($data['pak']);
            if ($data['pak'][0]['unsur']=="Utama") {
	            $this->mrTemplate->AddVar("input_unsur","SELECTED1", "SELECTED");
	            $this->mrTemplate->AddVar("input_unsur","SELECTED2", "");
			}else if ($data['pak'][0]['unsur']=="Penunjang"){
	            $this->mrTemplate->AddVar("input_unsur","SELECTED1", "");
	            $this->mrTemplate->AddVar("input_unsur","SELECTED2", "SELECTED");
		    }
		        
		       
		        
		    $this->mrTemplate->AddVar("content", "ID", $data['pak'][0]['id']);
            $this->mrTemplate->AddVar("content", "NAMA", $data['pak'][0]['nama']);
            $this->mrTemplate->AddVar("content", "UNSUR", $data['pak'][0]['unsur']);
            $this->mrTemplate->AddVar("content", "KETERANGAN", $data['pak'][0]['keterangan']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>
