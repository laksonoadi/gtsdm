<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';

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
      $this->Obj = new MutasiPendidikan();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
	  
	  if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html').'&id='.$this->delId;
      }else{
		 $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html').'&id='.$this->delId;
      }
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    if (trim($this->POST['institusi']) == ''){
		$error = 'Nama Institusi Belum Dimasukan!';
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_pendidikan','MutasiPendidikan','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }


  function AddData($nama_file){
      $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
      $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
      $array=array(
                  'pegKode'=>$this->POST['pegKode'],
                  'jenispend'=>$this->POST['jenispend'],
                  'institusi'=>$this->POST['institusi'],
                  'jurusan'=>$this->POST['jurusan'],
                  'lulus'=>$this->POST['lulus'],
                  'tempat'=>$this->POST['tempat'],
                  'kepsek'=>$this->POST['kepsek'],
                  'negara'=>$this->POST['negara'],
                  'asdan'=>$this->POST['asdan'],
                  'lama'=>$this->POST['lama'],
                  'mulai'=>$mulai,
                  'selesai'=>$selesai,
                  'ket'=>$this->POST['ket'],
                  'pktgol'=>$this->POST['pktgol'],
                  'istamat'=>$this->POST['istamat'],
				  'upload'=>$nama_file
                  );
      
      $result = $this->Obj->Add($array);
         if ($result){
			return $result;
         }else{
			return false;
         }
         return $this->pageView;
  }
	
	function UpdateData($nama_file){
      $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
      $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day']; 
      $array=array(
                  'pegKode'=>$this->POST['pegKode'],
                  'jenispend'=>$this->POST['jenispend'],
                  'institusi'=>$this->POST['institusi'],
                  'jurusan'=>$this->POST['jurusan'],
                  'lulus'=>$this->POST['lulus'],
                  'tempat'=>$this->POST['tempat'],
                  'kepsek'=>$this->POST['kepsek'],
                  'negara'=>$this->POST['negara'],
                  'asdan'=>$this->POST['asdan'],
                  'lama'=>$this->POST['lama'],
                  'mulai'=>$mulai,
                  'selesai'=>$selesai,
                  'ket'=>$this->POST['ket'],
                  'pktgol'=>$this->POST['pktgol'],
                  'istamat'=>$this->POST['istamat'],
				  'upload'=>$nama_file,
                  'id'=>$this->decId
      );
      $result = $this->Obj->Update($array);
         if ($result){
			return $result;
         }else{
			return false;
         }
      return $this->pageView;
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
        if($rs_add == true){
		   
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_pendidikan','MutasiPendidikan','view','html', array($this->POST,'Penambahan Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_pendidikan','MutasiPendidikan','view','html', array($this->POST,'Penambahan Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
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
           Messenger::Instance()->Send('mutasi_pendidikan','MutasiPendidikan','view','html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_pendidikan','MutasiPendidikan','view','html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   
   function Delete(){
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_pendidikan', 'MutasiPendidikan', 'view', 'html', array($this->POST,'Penghapusan Data Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_pendidikan', 'MutasiPendidikan', 'view', 'html', array($this->POST,'Penghapusan Data Gagal Dilakukan', $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
	return $return;
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>