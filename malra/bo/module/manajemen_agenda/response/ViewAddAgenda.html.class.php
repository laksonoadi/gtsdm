<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_agenda/business/ManajemenAgenda.class.php';


class ViewAddAgenda extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_agenda.html');
   }
   
   function ProcessRequest() {
      $this->mAgenda = new ManajemenAgenda();
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
      
      if($_POST['action'] == 'Simpan'){
         $this->CheckRequiredFields();
         if(!empty($_FILES['foto']['name'])){
            $this->CheckFileRequirements();
         }
         $data['action'] = 'Simpan';
         return $data;
      }
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($this->mMessage)){
			$message = implode($this->mMessage, '<br/>');
			$this->mrTemplate->addVar('message', 'MESSAGE', $message);
      }elseif($data['action'] == 'Simpan'){
         if($this->DoUploadFile() != false){
            $this->DoAddAgenda($this->mFileName);
			//print_r($this->DoAddAgenda($this->mFileName));die;
            $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_agenda','AdminListAgenda','View','html').'&idSukses=1');
         }
      }
      
      $this->mrTemplate->addVar('content','SUB','AddAgenda');
      $this->mrTemplate->addVar('content','START_DATE',date('d/m/Y'));
      $this->mrTemplate->addVar('content','END_DATE',date('d/m/Y'));
      $this->mrTemplate->addVar('content','CHECKED','');
      
      $this->mrTemplate->addVar('content','MAX_WIDTH',GTFWConfiguration::GetValue('application', 'max_image_width'));
      $this->mrTemplate->addVar('content','MAX_HEIGHT',GTFWConfiguration::GetValue('application', 'max_image_height'));
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      //$this->CheckRequired($_POST['title'], 'Nama Agenda harus diisi');
      //$this->CheckRequired($_POST['tempat'], 'Tempat harus diisi');
      //$this->CheckRequired($_POST['tanggal_mulai'], 'Tanggal Mulai harus diisi');
      //$this->CheckRequired($_POST['tanggal_selesai'], 'Tanggal Selesai harus diisi');
		
		$this->validation = TRUE;
		
		// Check title
		if(trim($_POST['title']->Raw()) === '') {
			$this->validation = FALSE;
			$this->mMessage[] = "Nama Agenda tidak boleh kosong";
		}
		
		// Check content
		/* if(trim($_POST['article']->Raw()) === '') {
			$this->validation = FALSE;
			$this->mMessage[] = "Keterangan tidak boleh kosong";
		} */
   }
   
   function CheckFileRequirements(){
      $fileSend = $_FILES['foto'];
      
      #get image MIME, should be JPG of PNG
      $fileMIME = $fileSend['type'];
      switch($fileMIME){
         case 'image/jpeg':
         case 'image/png' :
            // nothing to do
         break;
         default:
            $this->mMessage[] = 'Foto harus berupa file JPG atau PNG';
         break;
      }
      
      #get image size, should be less than needed (see the configuration files for the setting)
      $fileSize = getimagesize($fileSend['tmp_name']);
      if(($fileSize[0] > GTFWConfiguration::GetValue('application', 'max_image_width')) ||
         ($fileSize[1] > GTFWConfiguration::GetValue('application', 'max_image_height'))){
            $this->mMessage[] = 'Ukuran foto melebih maksimum '.GTFWConfiguration::GetValue('application', 'max_image_width'). 'x' .
                                 GTFWConfiguration::GetValue('application', 'max_image_height') .' pixel';
      }
   }
   
   function DoUploadFile(){
      $fileSend = $_FILES['foto'];
      
      if(empty($fileSend['name'])){
         return true;
      }
      
      $buffExplodedName = explode('.',$fileSend['name']);
      $extensions = array_pop($buffExplodedName);
      
      $fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
      $fileDir = GTFWConfiguration::GetValue('application', 'file_agenda');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function DoAddAgenda($fileName){
      #parameter preparation
      $sender = $_SESSION['username']; //Security::Instance()->mrUser->mUserName;
      $tanggal_mulai = $_POST['mulai_year'].'-'.$_POST['mulai_mon'].'-'.$_POST['mulai_day'];
      $tanggal_selesai = $_POST['selesai_year'].'-'.$_POST['selesai_mon'].'-'.$_POST['selesai_day'];
      $status = $_POST['status'] == '1' ? '1' : '0';
      
      // return $this->mAgenda->AddAgenda($_POST['title'],$_POST['article'],$tanggal_mulai,$tanggal_selesai,$_POST['tempat'],$_POST['url'],$fileName,$_POST['caption'],$status,$sender);
      return $this->mAgenda->AddAgenda($_POST['title'],$_POST['article'],$tanggal_mulai,$tanggal_selesai,$_POST['tempat'],$fileName,$_POST['caption'],$status,$sender);
   }
}
?>