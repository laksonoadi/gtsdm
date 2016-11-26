<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.class.php';

class Process
{
   var $POST;
   //var $FILES;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $pageView;
   var $pageBack;
   
   function __construct() {
      $this->Obj = new MutasiOrganisasiPegawai();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
      
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	    if ($this->lang=='eng'){
         $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
         $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
         $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
         $this->msgReqDataEmpty='All field marked with * must be filled';
      }else{
         $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
         $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
         $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
         $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
      }
      
      $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html').'&id='.$this->profilId;
      $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html').'&id='.$this->profilId;
      $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html').'&id='.$this->delId;
       
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    if (trim($this->POST['nama']) == '' or trim($this->POST['jabatan'])==''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }

  function AddData($nama_file){
      //$check = $this->Check();
         //if ($check !== true) return $check;
      if (!empty($this->POST['selesai']))
         {
         $selesai=$this->POST['selesai'];
         }
      else 
         {
         $selesai='0000';
         }
      $array=array(
                  'pegKode'=>$this->POST['pegKode'],
				  'jenis'=>$this->POST['jenis_organisasi'],
                  'nama'=>$this->POST['nama'],
                  'jabatan'=>$this->POST['jabatan'],
                  'mulai'=>$this->POST['mulai'],
                  'selesai'=>$selesai,
				  'upload'=>$nama_file
                  );
      //$this->dumper($array);exit();
      $result = $this->Obj->Add($array);
         //if ($result){
            //Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data added successfully',$this->cssDone),Messenger::NextRequest);
         //}else{
         //   Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data addition failed',$this->cssFail),Messenger::NextRequest);
         //}
         //return $this->pageView;
		 if ($result){
			return $result;
		 }else{
			return false;
		 }
  }
	
	function UpdateData($nama_file){
	   if (!empty($this->POST['selesai']))
         {
         $selesai=$this->POST['selesai'];
         }
      else 
         {
         $selesai='0000';
         }   
      $array=array(
                  'pegKode'=>$this->POST['pegKode'],
				  'jenis'=>$this->POST['jenis_organisasi'],
                  'nama'=>$this->POST['nama'],
                  'jabatan'=>$this->POST['jabatan'],
                  'mulai'=>$this->POST['mulai'],
                  'selesai'=>$selesai,
				  'upload'=>$nama_file,
                  'id'=>$this->decId
      );
      //$this->dumper($array);exit();
      $result = $this->Obj->Update($array);
         //if ($result){
            //Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data updated successfully',$this->cssDone),Messenger::NextRequest);
         //}else{
            //Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data update failed',$this->cssFail),Messenger::NextRequest);
         //}
      //return $this->pageView;
	  if ($result){
		return $result;
	  }else{
		return false;
	  }
  }
  
	function InputData(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }
        $rs_add = $this->AddData($nama_file);
		//print_r($rs_add), exit;    
        if($rs_add == true){
		   
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'file_save_path').$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'file_save_path').$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data added successfully',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data addition failed',$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }
        $rs_update = $this->UpdateData($nama_file);
        	
        if($rs_update == true){
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'file_save_path').$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'file_save_path').$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data updated successfully',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_organisasi_pegawai','MutasiOrganisasiPegawai','view','html', array($this->POST,'Data update failed',$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
      
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html', array($this->POST,'Data deleted successfully', $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_organisasi_pegawai', 'MutasiOrganisasiPegawai', 'view', 'html', array($this->POST,'Data delete failed', $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
   //$return .= "&id=".$this->delId;
	return $return;
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>