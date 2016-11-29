<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/business/AppTemplateCetak.class.php';

class ProcessTemplateCetak{
	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;
   
   function __construct(){
      $this->templateObj = new AppTemplateCetak();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->status = Dispatcher::Instance()->Decrypt($_REQUEST['status']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');
   }
   
   
   function Check(){
   if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['nama']) == "")  {
				return "empty";
			} else{
				return true;
			}
		}
		return false;
   }
   function UpdateStatus(){
      $updateStatus=$this->templateObj->DoUpdateStatusTemplate($this->status,$this->decId);
      
      if($this->status=="Tidak Aktif"){
         $this->status="Aktif";
      }else{
         $this->status="Non Aktif";
      }
      
      if ($updateStatus===true){
         Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
            array($this->_POST,'Template Berhasil Di '.$this->status.'kan', $this->cssDone),Messenger::NextRequest);
      }else{
         Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
            array($this->_POST,'Template Gagal Di '.$this->status.'kan', $this->cssFail),Messenger::NextRequest);
      }
      return $this->pageView;
   }
   
   function UploadFile($update=''){
      if (isset($_POST['btnsimpan'])){
      
         $_FILES['file']['name'] = preg_replace(
            '/[^a-zA-Z0-9\.\$\%\'\`\-\@\{\}\~\!\#\(\)\&\_\^]/'
            ,'',str_replace(array(' ','%20'),array('_','_'),$_FILES['file']['name']));
            
            
         $nama='template_'.strtolower(preg_replace(
            '/[^a-zA-Z0-9\.\$\%\'\`\-\@\{\}\~\!\#\(\)\&\_\^]/'
            ,'',str_replace(array(' ','%20'),array('_','_'),$this->_POST['nama'])));
         

            $fileName=$nama.".rtf";
         
         $dir = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
         $file = $dir . "/doc/" ;
         $files = explode(".", $_FILES['file']['name']);
         
         if (strtolower($files[count($files) - 1]) != "rtf" ){
            $return['status']="file_not_valid";
         }else {
            if($update==true){
            rename($file.$this->_POST['template_path'],$file."temp_".$this->_POST['template_path']);
            }
            $upload=move_uploaded_file($_FILES['file']['tmp_name'], $file.$fileName);
            if($upload==true){
               $return['file_name']=$fileName;
               $return['status']=true;
            }else{
               $return['status']="upload_fail";
            }
         }
      }
      return $return;
   
   }
   
   function Add($response=''){
      $cek = $this->Check();
      if (isset($_POST['btnbalik'])){
         if ($response == 'json')
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
         else
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
      }
      
      
      $uploadFile=$this->UploadFile();
      
      if(($uploadFile['status']===true) and ($cek===true)){

         $addTemplate=$this->templateObj->DoAddTemplate($this->_POST['nama'],$uploadFile['file_name']);

         if($addTemplate===true){

            Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
               array($this->_POST,'Template Berhasil DiTambahkan', $this->cssDone),Messenger::NextRequest);
            
            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html'); 
         }else{
         
            Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
               array($this->_POST,'Template Berhasil DiGagalkan', $this->cssFail),Messenger::NextRequest);
         }
      }elseif($cek==="empty"){
         
         Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
           array($this->_POST,'Lengkapi Isian Data'),Messenger::NextRequest);
         
         if ($response == 'json')
            return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
         else
            return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');     
            
      }elseif($uploadFile['status']==="file_not_valid"){
      
         Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
           array($this->_POST,'File RTF Tidak Valid'),Messenger::NextRequest);
         
         if ($response == 'json')
            return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
         else
            return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');
            
      }elseif($uploadFile['status']==="upload_fail"){
      
         Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
            array($this->_POST,'Template Gagal Di Upload', $this->cssDone),Messenger::NextRequest);
         
         if ($response == 'json')
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
         else
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
               
      }

   }
  
   function Update($response=''){
   $cek = $this->Check();
   
      if (isset($_POST['btnbalik'])){
         if ($response == 'json')
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
         else
            return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
      }
      
      
      $dir = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
      $file = $dir . "/doc/" ;

      if(file_exists($file.$this->_POST['template_path'])){
         
         $uploadFile=$this->UploadFile(true);

         if(($uploadFile['status']===true) and ($cek===true)){
            // hapus template lama
           $is_writable=is_writable($file."temp_".$this->_POST['template_path']);
            
            if($is_writable==true){
               unlink($file."temp_".$this->_POST['template_path']);
            }
            else{
               unlink($file.$this->_POST['template_path']);
               
               $rename=rename($file."temp_".$this->_POST['template_path'],$file.$this->_POST['template_path']);
               
               Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
                  array($this->_POST,'Template Gagal Diganti', $this->cssFail),Messenger::NextRequest);

               if ($response == 'json')
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
               else
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
            }
            
            //simpan informasi template ke db
            $updateTemplate=$this->templateObj->DoUpdateTemplate($this->_POST['nama'],$this->_POST['template_path'],$this->decId);
            
            if($updateTemplate==true){

               Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
                  array($this->_POST,'Template Berhasil DiGanti', $this->cssDone),Messenger::NextRequest);
               
               if ($response == 'json')
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
               else
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
            }
            
         }elseif($cek==="empty"){
         
            Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
              array($this->_POST,'Lengkapi Isian Data'),Messenger::NextRequest);
         
            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');     
            
         }elseif($uploadFile['status']==="file_not_valid"){
      
            Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
              array($this->_POST,'File RTF Tidak Valid'),Messenger::NextRequest);
         
            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');
            
         }elseif($uploadFile['status']="upload_fail"){
            
            Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
               array($this->_POST,'Template Gagal Diganti', $this->cssFail),Messenger::NextRequest);

            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
         }
      }else{
         $uploadFile=$this->UploadFile();
         
         if(($uploadFile['status']===true) and ($cek===true)){
         
         $updateTemplate=$this->templateObj->DoUpdateTemplate($this->_POST['nama'],$this->_POST['template_path'],$this->decId);
            
            if($updateTemplate==true){

               Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
                  array($this->_POST,'Template Berhasil DiGanti', $this->cssDone),Messenger::NextRequest);
               
               if ($response == 'json')
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
               else
                  return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
            }
         }elseif($cek==="empty"){
         
            Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
              array($this->_POST,'Lengkapi Isian Data'),Messenger::NextRequest);
         
            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');     
            
         }elseif($uploadFile['status']==="file_not_valid"){
      
            Messenger::Instance()->Send('template_cetak', 'uploadTemplateCetak', 'view', 'html', 
              array($this->_POST,'File RTF Tidak Valid'),Messenger::NextRequest);
         
            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');
            
         }elseif($uploadFile['status']="upload_fail"){
            
            Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', 
               array($this->_POST,'Template Gagal Diganti', $this->cssFail),Messenger::NextRequest);

            if ($response == 'json')
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html', true);
            else
               return Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
         }
      }
   }
   
   function Delete() {
		$arrId = $this->_POST['idDelete'];
		
		$deleteDataById = $this->templateObj->DoDeleteTemplateById($arrId);
      
		if($deleteDataById==false) {
		      if(is_array($arrId)==false)
               $arr[0]=$arrId;
            else
               $arr=$arrId;
         $deleteDataByArrayId = $this->templateObj->DoDeleteTemplateByArrayId($arr);
		}
		if($deleteDataByArrayId === true) {
			Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', array($this->_POST,'Penghapusan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);
		} elseif($deleteDataById === true) {
			Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', array($this->_POST,'Penghapusan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arr);$i++) {
				$deleteData = false;
				$deleteData = $this->templateObj->DoDeleteTemplateById($arr[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
			Messenger::Instance()->Send('template_cetak', 'templateCetak', 'view', 'html', array($this->_POST, $gagal . ' Data Tidak Dapat Dihapus.', $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
   
}

?>