<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';


class ViewDetailPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_policy.html');
   }
   
   function DoUploadFile(){
      $fileSend = $_FILES['file'];
      
      if(empty($fileSend['name'])){
         return true;
      }
      
      $buffExplodedName = explode('.',$fileSend['name']);
      $extensions = array_pop($buffExplodedName);
      
      $fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
      $fileDir = GTFWConfiguration::GetValue('application', 'policy_save_path');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      
      if ($_POST['upload']=='Upload'){
          $this->DoUploadFile();
          if ($this->DoAddFile()==true){
              $data['message']='Upload File Berhasil Dilakukan';
              $data['css']='notebox-done';
          }else{
              $data['message']='Upload File Gagal Dilakukan';
              $data['css']='notebox-warning';
          }
      }else if ($_GET['op']=='aktif'){
          if ($this->DoUpdateStatus('Aktif',$_GET['fileid'])==true){
            $data['message']='Pengaturan Status File Aktif Berhasil';
            $data['css']='notebox-done';
          }else{
            $data['message']='Pengaturan Status File Aktif Gagal';
            $data['css']='notebox-warning';
          }
      }else if ($_GET['op']=='nonaktif'){
          if ($this->DoUpdateStatus('Tidak Aktif',$_GET['fileid'])==true){
            $data['message']='Pengaturan Status File Tidak Aktif Berhasil';
            $data['css']='notebox-done';
          }else{
            $data['message']='Pengaturan Status File Tidak Aktif Gagal';
            $data['css']='notebox-warning';
          }
      }else if ($_GET['op']=='delete'){
          if ($this->DoDelete($_GET['fileid'])==true){
            $data['message']='Penghapusan File Berhasil Dilakukan';
            $data['css']='notebox-done';
          }else{
            $data['message']='Penghapusan File Gagal Dilakukan';
            $data['css']='notebox-warning';
          }
      }else if ($_GET['op_down']=='0'){
          if ($this->DoUpdateIsDownload('0',$_GET['fileid'])==true){
            $data['message']='Regulation File View Only Successfully';
            $data['css']='notebox-done';
          }else{
            $data['message']='Regulation File View Only Failure';
            $data['css']='notebox-warning';
          }
      }else if ($_GET['op_down']=='1'){
          if ($this->DoUpdateIsDownload('1',$_GET['fileid'])==true){
            $data['message']='Regulation File Is Download Successfully';
            $data['css']='notebox-done';
          }else{
            $data['message']='Regulation File Is Download Failure';
            $data['css']='notebox-warning';
          }
      }

      $data['PegawaiPolicy']	= $this->mPolicy->GetPegawaiPolicyById($_REQUEST['id']);
	  $this->idPolicy			= $_REQUEST['id'];
	  
	  // echo "<pre>";print_r($data['PegawaiPolicy']);exit;
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'adminListPolicy', 'view', 'html');
      $nav[0]['menu']="Policy & Regulation History";
      $title = "Policy & Regulation Details";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      $data['policy'] = $this->mPolicy->GetPolicyById($_REQUEST['id']);
      $data['list_file_policy'] = $this->mPolicy->GetFilePolicyByPolicyId($data['policy'][0]['policyId']);
	  return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($data['message'])){
         $this->mrTemplate->setAttribute('message','visibility','visible');
         $this->mrTemplate->addVar('message','MESSAGE',$data['message']);
         $this->mrTemplate->addVar('message','CSS',$data['css']);
      }
	  
	  
	 $PegawaiPolicy	= $data['PegawaiPolicy'];
	 
	 if(!empty($PegawaiPolicy)){
		$recPeg	= sizeof($PegawaiPolicy);
		$this->mrTemplate->addVar('content','DISPLAY_SELECT','');
		$this->mrTemplate->addVar('content','DISPLAY_ALL','none');
	 }else{
		$this->mrTemplate->addVar('content','DISPLAY_SELECT','none');
		$this->mrTemplate->addVar('content','DISPLAY_ALL','');
	 }
	 
      $this->mrTemplate->addVar('content','URL_SELEKSI',Dispatcher::Instance()->GetUrl('manajemen_policy','popupPegawai','View','html').'&id='.$this->idPolicy);
      
      $urlAction = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'];
      $this->mrTemplate->addVar('content','URL_ACTION',$urlAction);
   
      if(!empty($data['policy'])){
         $policy = $data['policy'][0];
         $policy['policyIsAktif']=$policy['policyIsAktif'] == '1' ? 'Active' : 'Not Active';
         $policy['policyTanggalPolicy']=$this->mPolicy->IndonesianDate($policy['policyTanggalPolicy'],"yyyy-mm-dd");
         $this->mrTemplate->addVars('content',$policy,'');
         
         $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_policy','UpdatePolicy','View','html').'&id='.$policy['policyId'];
         $this->mrTemplate->addVar('content','URL_EDIT',$urlEdit);
         $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_policy','DeletePolicy','View','html').'&id='.$policy['policyId'];
         $this->mrTemplate->addVar('content','URL_DELETE',$urlDelete);
         $urlBack = Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html');
         $this->mrTemplate->addVar('content','URL_BACK',$urlBack);
         $urlFileManager = "admin/ext/gtxplorer/";
         $this->mrTemplate->addVar('content','URL_FILE_MANAGER',$urlFileManager);           
         
      }


		// if(empty($data['PegawaiPolicy'])){
			// $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
				// return NULL;
		// }else{
			// $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
		// }		
		// foreach ($data['PegawaiPolicy'] as $value)
		// {
			// $data 				= $value;//print_r($data);
			// $link				= $data['link'];
			// $data['number'] 	= $i;

			// $data['url_delete'] = $this->url_delete.
				// "&id=".Dispatcher::Instance()->Encrypt($this->id).
				// "&idPeg=".Dispatcher::Instance()->Encrypt($data['id']).
				// "&dataName=".Dispatcher::Instance()->Encrypt($data['namaPeg']);
			// $this->mrTemplate->AddVars('data_item', $data, '');
			// $this->mrTemplate->parseTemplate('data_item', 'a');
			// $i++;
		// }
		
		// echo "<pre>";print_r($data['PegawaiPolicy']);exit;
		// echo "<pre>";print_r($data['list_file_policy']);exit;
		
      
	  if(!empty($data['list_file_policy'][0]['filepolicyId'])){
         $this->mrTemplate->setAttribute('list_file_policy','visibility','visible');
         $no = 1;
         foreach($data['list_file_policy'] as $filepolicy){
            $filepolicy['no']=$no;
            if ($filepolicy['filepolicyStatus']=='Aktif'){
//                $filepolicy['filepolicyStatus']='Active';
                $filepolicy['url_aktif'] = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'].'&op=nonaktif&fileid='.$filepolicy['filepolicyId'];
                $filepolicy['button']='button-clipboard';
                $filepolicy['button_label']='Deactivate';
            } else{
//                $filepolicy['filepolicyStatus']='Not Active';
                $filepolicy['url_aktif'] = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'].'&op=aktif&fileid='.$filepolicy['filepolicyId'];
                $filepolicy['button']='button-check';
                $filepolicy['button_label']='Activate';
            }
            
            if($filepolicy['filepolicyIsDownload']=='1'){ 
              $filepolicy['filepolicyIsDownload']='Is Download'; 
              $filepolicy['url_download'] = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'].'&op_down=0&fileid='.$filepolicy['filepolicyId'];
              $filepolicy['button_download']='button-clipboard';
              $filepolicy['button_label_download']='View Only';
            } else {
              $filepolicy['filepolicyIsDownload']='View Only';
              $filepolicy['url_download'] = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'].'&op_down=1&fileid='.$filepolicy['filepolicyId'];
              $filepolicy['button_download']='button-check';
              $filepolicy['button_label_download']='Is Download'; 
            }
                
            $filepolicy['url_delete'] = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$_REQUEST['id'].'&op=delete&fileid='.$filepolicy['filepolicyId'];
            
            $tanggal=explode(' ',$filepolicy['filepolicyTgl']);
            $filepolicy['filepolicyTgl'] = $tanggal[1].'<br/>'.$this->mPolicy->IndonesianDate($tanggal[0],'YYYY-MM-DD');
            $this->mrTemplate->addVars('list_file_policy',$filepolicy,'');
            $this->mrTemplate->parseTemplate('list_file_policy','a');
            $no++;
         }
      }else{
         $this->mrTemplate->setAttribute('empty_file_policy','visibility','visible');
      }
   }
   
   function DoAddFile(){
      #parameter preparation
      $status = $_POST['status'] == '1' ? 'Aktif' : 'Tidak Aktif';
      $is_download = $_POST['is_download'] == '1' ? '1' : '0';
      $array=array( 'policyId'=>$_POST['id'],
                    'file'=>$this->mFileName,
                    'status'=>$status,
                    'is_download'=>$is_download
                  );

      return $this->mPolicy->AddFilePolicy($array);
   }
   
   function DoUpdateStatus($status,$id){
      return $this->mPolicy->UpdateStatusFilePolicy($status,$id);
   }
   
   function DoUpdateIsDownload($is_download,$id){
      return $this->mPolicy->UpdateIsDownloadFilePolicy($is_download,$id);
   }
   
    function DoDelete($id){
      return $this->mPolicy->DeleteFilePolicy($id);
   }
}
?>