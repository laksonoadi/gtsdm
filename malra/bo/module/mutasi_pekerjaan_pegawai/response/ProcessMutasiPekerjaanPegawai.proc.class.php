<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pekerjaan_pegawai/business/mutasi_pekerjaan_pegawai.class.php';

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
   
   function __construct($ret) {
      $this->Obj = new MutasiPekerjaanPegawai();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
	  
	  if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html').'&id='.$this->delId;
		 }else{
		 $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html').'&id='.$this->delId;
		 }
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    if (trim($this->POST['nama']) == '' or trim($this->POST['jabatan'])==''){
      $error = 'Data Incomplete!';
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html', $msg, Messenger::NextRequest);
      
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
                  'nama'=>$this->POST['nama'],
                  'jabatan'=>$this->POST['jabatan'],
				  'tanggungjabatan'=>$this->POST['tanggung_jawab'],
                  'mulai'=>$this->POST['mulai'],
                  'selesai'=>$selesai,
				  'status'=>$this->POST['tipe'],
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
                  'nama'=>$this->POST['nama'],
                  'jabatan'=>$this->POST['jabatan'],
				  'tanggungjabatan'=>$this->POST['tanggung_jawab'],
                  'mulai'=>$this->POST['mulai'],
                  'selesai'=>$selesai,
				  'status'=>$this->POST['tipe'],
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
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html', array($this->POST,'Penambahan data berhasil dilakukan',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html', array($this->POST,'Penambahan data gagal dilakukan',$this->cssFail),Messenger::NextRequest);
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
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html', array($this->POST,'Perubahan data berhasil dilakukan',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_pekerjaan_pegawai','MutasiPekerjaanPegawai','view','html', array($this->POST,'Perubahan data gagal dilakukan',$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
      
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_pekerjaan_pegawai', 'MutasiPekerjaanPegawai', 'view', 'html', array($this->POST,'Penghapusan data berhasil dilakukan', $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_pekerjaan_pegawai', 'MutasiPekerjaanPegawai', 'view', 'html', array($this->POST,'Penghapusan data gagal dilakukan', $this->cssFail),Messenger::NextRequest);   
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