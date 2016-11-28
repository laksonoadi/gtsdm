<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_agenda/business/ManajemenAgenda.class.php';


class ViewUpdateAgenda extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_agenda.html');
   }
   
   function ProcessRequest() {
      $this->mAgenda = new ManajemenAgenda();
      $data['agenda'] = $this->mAgenda->GetAgendaById($_REQUEST['id']);
      
      if($_POST['action'] == 'Simpan'){
         $this->CheckRequiredFields();
         if(!empty($_FILES['foto']['name'])){
            $this->CheckFileRequirements();
         }
         $data['action'] = 'Simpan';
         // return $data;
      }
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($data['agenda'][0]['START_DATE'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($data['agenda'][0]['END_DATE'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($this->mMessage)){
			$message = implode($this->mMessage, '<br/>');
			$this->mrTemplate->addVar('message', 'MESSAGE', $message);
      }elseif($data['action'] == 'Simpan'){
         if($this->DoReUploadFile($data['agenda'][0]['FOTO']) != false){
            $this->DoUpdateAgenda($this->mFileName);
            $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_agenda','AdminListAgenda','View','html').'&idSukses=2');
         }
      }
		
      if(!empty($data['agenda'])){
         $agenda = $data['agenda'][0];
         $this->mrTemplate->addVar('content','ID',$agenda['ID']);
         $this->mrTemplate->addVar('content','TITLE',$agenda['TITLE']);
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($agenda['ARTICLE']));
         $this->mrTemplate->addVar('content','LOCATION',stripslashes($agenda['LOCATION']));
         $this->mrTemplate->addVar('content','URL',$agenda['URL']);
         if(!empty($agenda['FOTO'])){
            $this->mrTemplate->setAttribute('foto','visibility','visible');
            $this->mrTemplate->addVar('foto','FOTO_PATH',GTFWConfiguration::GetValue('application', 'file_agenda').$agenda['FOTO']);
         }
         $this->mrTemplate->addVar('content','CAPTION',$agenda['CAPTION']);
         $this->mrTemplate->addVar('content','START_DATE',$this->mAgenda->IndonesianDate($agenda['START_DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $this->mrTemplate->addVar('content','END_DATE',$this->mAgenda->IndonesianDate($agenda['END_DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $checked = $agenda['STATUS'] == '1' ? 'checked' : '';
         $this->mrTemplate->addVar('content','CHECKED',$checked);
      }
      
      $this->mrTemplate->addVar('content','SUB','UpdateAgenda');
      
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
   
   function DoReUploadFile($oldFilename){
      $fileSend = $_FILES['foto'];
      
      if(empty($fileSend['name'])){
         $this->mFileName = $oldFilename;
         return true;
      }
      
      $buffExplodedName = explode('.',$fileSend['name']);
      $extensions = array_pop($buffExplodedName);
      
      $fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
      $fileDir = GTFWConfiguration::GetValue('application', 'file_agenda');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         @unlink($fileDir.$oldFilename);
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function DoUpdateAgenda($fileName){
      #parameter preparation
      $sender = $_SESSION['username']; //Security::Instance()->mrUser->mUserName;
      $tanggal_mulai = $_POST['mulai_year'].'-'.$_POST['mulai_mon'].'-'.$_POST['mulai_day'];
      $tanggal_selesai = $_POST['selesai_year'].'-'.$_POST['selesai_mon'].'-'.$_POST['selesai_day'];
      $status = $_POST['status'] == '1' ? '1' : '0';
      //print_r(array($_POST['title'],$_POST['article'],$tanggal_mulai,$tanggal_selesai,$_POST['tempat'],$_POST['url'],$fileName,$_POST['caption'],$status,$sender,$_POST['id']));exit();
      return $this->mAgenda->UpdateAgenda($_POST['title'],$_POST['article'],$tanggal_mulai,$tanggal_selesai,$_POST['tempat'],$fileName,$_POST['caption'],$status,$sender,$_POST['id']);
   }
}
?>