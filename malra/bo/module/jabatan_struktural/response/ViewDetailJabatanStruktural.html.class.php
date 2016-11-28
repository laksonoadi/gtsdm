<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/jabatan_struktural.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
class ViewDetailJabatanStruktural extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('view_detail_jabatan_struktural.html');    
   } 
   
   function ProcessRequest() {
      $jabstruk_obj = new JabatanStruktural();
      $objSatker = new SatuanKerja();
      $arrTpstrid = $jabstruk_obj->GetComboTpstrId();
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');

      $data['lang'] =$lang;
      
      if (!empty($id))
         $data['jabstruk'] = $jabstruk_obj->GetJabstrukById($id);
         $data['jabstruk']['jenjabatan'] = $jabstruk_obj->GetJenisJabatanById($data['jabstruk'][0]['tsid']);
         $data['jabstruk']['unit'] = $jabstruk_obj->GetUnitById($data['jabstruk'][0]['unit']);
         
         //print_r($data['pg']);
         $msg = Messenger::Instance()->Receive(__FILE__);
         $data['pesan'] = $msg[0][1];
         $data['css'] =$msg[0][2];
         $arrsatker = $objSatker->GetComboSatuanKerja();
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tpstr', array('tpstr', $arrTpstrid, $data['jabstruk'][0]['tsid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', array('satker', $arrsatker, $data['jabstruk'][0]['unit'], 'false', ' style="width:200px;"  onchange="setDS()" '), Messenger::CurrentRequest);
         return $data;   
   }  
   
   function ParseTemplate ($data = NULL){
       if ($data['pesan']){
         $this->mrTemplate->SetAttribute('warning_box','visibility','visible');
         $this->mrTemplate->AddVar('warning_box','ISI_PESAN',$data['pesan']);
         $this->mrTemplate->AddVar('warning_box','CLASS_PESAN',$data['css']);
       }
       $this->mrTemplate->AddVar('content', 'URL_BACK',Dispatcher::Instance()->GetUrl('jabatan_struktural', 'JabatanStruktural', 'view', 'html') );

       $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'JabatanStruktural', 'view', 'html') );
         if ($data['lang']=='eng'){
            $this->mrTemplate->AddVar('content', 'TITLE', 'STRUTURAL POSITION');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', !empty($data['jabstruk']) ? 'Update' : 'Add');
            $this->mrTemplate->AddVar('content', 'JUDUL', !empty($data['jabstruk']) ? 'Update' : 'Add');
         }else{
            $this->mrTemplate->AddVar('content', 'TITLE', 'JABATAN STRUKTURAL');
            $this->mrTemplate->AddVar('content', 'BUTTONLABEL', !empty($data['jabstruk']) ? 'Ubah' : 'Tambah');
            $this->mrTemplate->AddVar('content', 'JUDUL', !empty($data['jabstruk']) ? 'Ubah' : 'Tambah');    
         }
       $this->mrTemplate->AddVar('content', 'URL_POPUP_GAJI', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'popupGaji', 'view', 'html'));
       
       
       if(empty($data['jabstruk'])){
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'addJabatanStruktural', 'do', 'html') );
            $this->mrTemplate->AddVar("content", "TINGKAT", "0");
            $this->mrTemplate->AddVar("content", "BATAS", "0");
       }else{
            $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'updateJabatanStruktural', 'do', 'html') );
            //$value['URL_ACTION'] = Dispatcher::Instance()->GetUrl('ref_pak','UpdatePangkatGolongan','do','html');
            //print_r($data['jabstruk']);		        
		        // print_r($data);            
            $this->mrTemplate->AddVar("content", "PATH_ANJAB", GTFWConfiguration::GetValue( 'application', 'template_anjab'));
            $this->mrTemplate->AddVar("content", "FILE_LAMA", $data['jabstruk'][0]['file_anjab']);
            $this->mrTemplate->AddVar("content", "ID", $data['jabstruk'][0]['id']);
            $this->mrTemplate->AddVar("content", "NAMA", $data['jabstruk'][0]['nama']);
            $this->mrTemplate->AddVar("content", "TINGKAT", $data['jabstruk'][0]['tingkat']);
            $this->mrTemplate->AddVar("content", "BATAS", $data['jabstruk'][0]['batas']);
            $this->mrTemplate->AddVar("content", "KOMPLABEL", $data['jabstruk'][0]['kompnama']);
            $this->mrTemplate->AddVar("content", "KOMP", $data['jabstruk'][0]['kompid']);

            $this->mrTemplate->AddVar("content", "KETERANGAN", $data['jabstruk'][0]['keterangan']);
            $this->mrTemplate->AddVar("content", "JENIS_STRUKTUR", $data['jabstruk']['jenjabatan']['0']['nama']);
            $this->mrTemplate->AddVar("content", "UNITKERJA", $data['jabstruk']['unit']['0']['nama']);
            $this->mrTemplate->ParseTemplate("content","a");
         
       } 
       
           
   }
   }
?>
