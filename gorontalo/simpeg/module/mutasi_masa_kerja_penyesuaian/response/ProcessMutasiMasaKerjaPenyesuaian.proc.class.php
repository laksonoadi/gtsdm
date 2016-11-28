<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_masa_kerja_penyesuaian/business/mutasi_masa_kerja_penyesuaian.class.php';

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
      $this->Obj = new MutasiMasaKerjaPenyesuaian();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataKode']->Integer()->Raw();
      $this->profilId = $_POST['pegKode']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
      
      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html').'&id='.$this->delId;
        }
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) {
      return $this->pageView;
      }
      
    if ((trim($this->POST['tahun']) == '')){
      $error = 'Data incomplete!';
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html', $msg, Messenger::NextRequest);
      
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
    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'tahun'=>$this->POST['tahun'],
				'bulan'=>$this->POST['bulan'],
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'upload'=>$nama_file
			);
  //$this->dumper($array);exit();
    $result = $this->Obj->Add($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData($nama_file){ 

    $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pegKode'=>$this->POST['pegKode'],
				'tahun'=>$this->POST['tahun'],
				'bulan'=>$this->POST['bulan'],
				'pejabat'=>$this->POST['pejabat'],
				'nosk'=>$this->POST['sk_no'],
				'tgl_sk'=>$tgl_sk,
				'upload'=>$nama_file,
				'id'=>$this->decId
			);
	 //$this->dumper($array);exit();
    $result = $this->Obj->Update($array);
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
		    
        if($rs_add == true){
		   
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html', array($this->POST,'Penambahan data berhasil dilakukan.',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html', array($this->POST,'Penambahan data gagal dilakukan.',$this->cssFail),Messenger::NextRequest);
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
           Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html', array($this->POST,'Perubahan data berhasil dilakukan.',$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian','view','html', array($this->POST,'Perubahan data gagal dilakukan.',$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian', 'MutasiMasaKerjaPenyesuaian', 'view', 'html', array($this->POST,'Penghapusan data berhasil dilakukan.', $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_masa_kerja_penyesuaian', 'MutasiMasaKerjaPenyesuaian', 'view', 'html', array($this->POST,'Penghapusan data gagal dilakukan.', $this->cssFail),Messenger::NextRequest);   
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