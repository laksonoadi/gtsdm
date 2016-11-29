<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_berita/business/ManejemenBerita.class.php';


class ViewUpdateBerita extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_berita.html');
   }
   
   function ProcessRequest() {
      $this->mBerita = new ManejemenBerita();
      $data['berita'] = $this->mBerita->GetBeritaById($_REQUEST['id']);
	  $status = $_GET['statusUpdate'];
      if ($status == '2'){
		$this->Pesan = 'Perubahan data gagal dilakukan';
		$this->css = 'notebox-warning';
	  }
      if($_POST['action'] == 'Simpan'){
         $this->CheckRequiredFields();
         if(!empty($_FILES['name'])){
            $this->CheckFileRequirements();
         }
         $data['action'] = 'Simpan';
         return $data;
      }
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_berita', array($data['berita'][0]['DATE'], $tahun['start'], $tahun['end'], '', '', 'tanggal_berita'), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
   if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if (!empty($this->mMessage)){
         $this->ShowMessage();
      }elseif($data['action'] == 'Simpan'){
         if($this->DoReUploadFile($data['berita'][0]['FOTO']) != false){
            $result = $this->DoUpdateBerita($this->mFileName);
            if ($result == true){
            $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','AdminListBerita','View','html').'&add=1'.'&statusUpdate=1');
			} else {
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','UpdateBerita','View','html').'&add=1'.'&statusUpdate=2');
			}
         }
      }
      
      if(!empty($data['berita'])){
         $berita = $data['berita'][0];
         $this->mrTemplate->addVar('content','ID',$berita['ID']);
         $this->mrTemplate->addVar('content','TITLE',$berita['TITLE']);
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($berita['ARTICLE']));
         $this->mrTemplate->addVar('content','URL',$berita['URL']);
         if(!empty($berita['FOTO'])){
            $this->mrTemplate->setAttribute('foto','visibility','visible');
            $this->mrTemplate->addVar('foto','FOTO',$berita['FOTO']);
         }
         $this->mrTemplate->addVar('content','CAPTION',$berita['CAPTION']);
         $this->mrTemplate->addVar('content','DATE',$this->mBerita->IndonesianDate($berita['DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $checked = $berita['STATUS'] == '1' ? 'checked' : '';
         $this->mrTemplate->addVar('content','CHECKED',$checked);
      }
      
      $this->mrTemplate->addVar('content','SUB','UpdateBerita');
      
      $this->mrTemplate->addVar('content','MAX_WIDTH',GTFWConfiguration::GetValue('application', 'max_image_width'));
      $this->mrTemplate->addVar('content','MAX_HEIGHT',GTFWConfiguration::GetValue('application', 'max_image_height'));
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      //$this->CheckRequired($_POST['title'], 'Judul harus diisi');
      //$this->CheckRequired($_POST['article'], 'Artikel harus diisi');
      //$this->CheckRequired($_POST['tanggal_berita'], 'Tanggal harus diisi');
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
         exit();
      }
      
      $buffExplodedName = explode('.',$fileSend['name']);
      $extensions = array_pop($buffExplodedName);
      
      $fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
      $fileDir = GTFWConfiguration::GetValue('application', 'file_berita');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         @unlink($fileDir.$oldFilename);
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function DoUpdateBerita($fileName){
      #parameter preparation
      $sender = $_SESSION['username'];
      $date = $_POST['tanggal_berita_year'].'-'.$_POST['tanggal_berita_mon'].'-'.$_POST['tanggal_berita_day'];
      $status = $_POST['status'] == '1' ? '1' : '0';
      
      return $this->mBerita->UpdateBerita($_POST['title'],$_POST['article'],$_POST['url'],$fileName,$_POST['caption'],$status,$sender,$date,$_POST['id']);
   }
}
?>