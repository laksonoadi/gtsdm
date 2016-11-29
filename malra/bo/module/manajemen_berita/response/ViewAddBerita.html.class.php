<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_berita/business/ManejemenBerita.class.php';


class ViewAddBerita extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_berita.html');
   }
   
   function ProcessRequest() {
      $this->mBerita = new ManejemenBerita();
		$status = $_GET['statusAdd'];
      if ($status == '2'){
			$this->Pesan = 'Penambahan data gagal dilakukan';
			$this->css = 'notebox-warning';
		}
		
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_berita', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'tanggal_berita'), Messenger::CurrentRequest);
      
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
		if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if (!empty($this->mMessage) && !$this->Pesan){
			$this->mrTemplate->setAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->addVar('warning_box', 'CLASS_PESAN', 'notebox-warning');
			$message = implode($this->mMessage, '<br/>');
			$this->mrTemplate->addVar('warning_box', 'ISI_PESAN', $message);
      }elseif($data['action'] == 'Simpan'){
         if($this->DoUploadFile() != false){
            $result = $this->DoAddBerita($this->mFileName);
			if ($result == true){
            $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','AdminListBerita','View','html').'&add=1'.'&statusAdd=1');
			} else {
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','AddBerita','View','html').'&add=1'.'&statusAdd=2');
			}
         }
      }
      
      $this->mrTemplate->addVar('content','SUB','AddBerita');
      $this->mrTemplate->addVar('content','DATE',date('d/m/Y'));
      $this->mrTemplate->addVar('content','CHECKED','checked');
      
      $this->mrTemplate->addVar('content','MAX_WIDTH',GTFWConfiguration::GetValue('application', 'max_image_width'));
      $this->mrTemplate->addVar('content','MAX_HEIGHT',GTFWConfiguration::GetValue('application', 'max_image_height'));
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      //$this->CheckRequired($_POST['title'], 'Judul harus diisi');
      //$this->CheckRequired($_POST['article'], 'Artikel harus diisi');
      //$this->CheckRequired($_POST['tanggal_berita'], 'Tanggal harus diisi');
		
		$this->validation = TRUE;
		
		// Check title
		if(trim($_POST['title']->Raw()) === '') {
			$this->validation = FALSE;
			$this->mMessage[] = "Nama Agenda tidak boleh kosong";
		}
		
		// Check content
		if(trim($_POST['article']->Raw()) === '') {
			$this->validation = FALSE;
			$this->mMessage[] = "Keterangan tidak boleh kosong";
		}
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
      $fileDir = GTFWConfiguration::GetValue('application', 'file_berita');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function DoAddBerita($fileName){
      #parameter preparation
      $sender = $_SESSION['username'];
      $date = $_POST['tanggal_berita_year'].'-'.$_POST['tanggal_berita_mon'].'-'.$_POST['tanggal_berita_day'];
      $status = $_POST['status'] == '1' ? '1' : '0';
      
      return $this->mBerita->AddBerita($_POST['title'],$_POST['article'],$fileName,$_POST['caption'],$status,$sender,$date);
   }
}
?>