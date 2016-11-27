<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/sks_dosen/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/sks_dosen.class.php';

class ViewInputSksDosen extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/sks_dosen/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('input_sks_dosen.html');    
   } 
   
   function ProcessRequest() {
      $sks_obj = new SksDosen();
      $arrJabfung = $sks_obj->GetComboJabfung();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      
      if (!empty($id))
         $data['sks'] = $sks_obj->GetSksDosenById($id);
         
         //print_r($data['pg']);
         $msg = Messenger::Instance()->Receive(__FILE__);
         $data['pesan'] = $msg[0][1];
         $data['css'] =$msg[0][2];
         $data['lang'] =$lang;
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabfung', array('jabfung', $arrJabfung, $data['sks'][0]['jfid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         if ($lang=='eng'){
         $list_status=array(array('id'=>'Aktif','name'=>'Active'),array('id'=>'Tidak Aktif','name'=>'Inactive'));
         }else{
         $list_status=array(array('id'=>'Aktif','name'=>'Aktif'),array('id'=>'Tidak Aktif','name'=>'Tidak Aktif'));  
         }
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $data['sks'][0]['status'], '', 'id="status"'), Messenger::CurrentRequest);
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
            $this->mrTemplate->AddVar('content', 'TITLE', 'TEACHING CREDIT');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', !empty($data['sks']) ? 'Update' : 'Add');
         }else{
            $this->mrTemplate->AddVar('content', 'TITLE', 'SKS DOSEN');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', empty($data['sks']) ? 'Ubah' : 'Tambah');  
        }
       
       if(empty($data['sks'])){
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('sks_dosen', 'addSksDosen', 'do', 'html') );
            $this->mrTemplate->AddVar("content", "NOMINAL", "0");
       }else{
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('sks_dosen', 'updateSksDosen', 'do', 'html') );
            //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pak','UpdatePangkatGolongan','do','html');
            //print_r($data['sks']);		        
            $this->mrTemplate->AddVar("content", "ID", $data['sks'][0]['id']);
            $this->mrTemplate->AddVar("content", "TAHUN", $data['sks'][0]['tahun']);
            $this->mrTemplate->AddVar("content", "SEMESTER", $data['sks'][0]['semester']);
            $this->mrTemplate->AddVar("content", "NOMINAL", $data['sks'][0]['nominal']);
            //$this->mrTemplate->AddVar("content", "URUT", $data['pg'][0]['urut']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>