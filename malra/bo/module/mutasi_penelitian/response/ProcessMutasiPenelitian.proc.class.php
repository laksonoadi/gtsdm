<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_penelitian/business/mutasi_penelitian.class.php';

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
      $this->Obj = new MutasiPenelitian();
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
      if($ret == "html"){
      $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$this->profilId;
      $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$this->profilId;
      $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$this->delId;
      $this->pageReload = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$_GET['id']->Integer()->Raw();
      }else{
	  $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html',true).'&id='.$this->profilId;
      $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html',true).'&id='.$this->profilId;
      $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$this->delId;
      $this->pageReload = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$_GET['id']->Integer()->Raw();
      }
	  //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray(); //print_r($this->POST);
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) return $this->pageView;
    if (($tipe==1)&&(trim($this->POST['pnltnJudulBuku']) == '')){
       $error = $this->msgReqDataEmpty;   
    } else
    if (($tipe==2)&&(trim($this->POST['pnltnJudulArtikel']) == '')){
       $error = $this->msgReqDataEmpty;     
    } else
    if (($tipe==3)&&(trim($this->POST['pnltnJudulKaryaIlmiah']) == '')){
       $error = $this->msgReqDataEmpty;     
    } else
    if (($tipe==4)&&(trim($this->POST['pnltnJudulPublikasi']) == '')){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput."&tipe=".$this->POST['pnltnTipePenelitianId'];
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }

  function AddData($nama_file){
  //print_r($nama_file);//exit;
      $tipe=$this->POST['pnltnTipePenelitianId'];
      //$check = $this->Check($tipe);
      //if ($check !== true) return $check;
      if ($tipe==1){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['buku_jenis_buku_ref'],
                  $this->POST['pnltnJudulBuku'],
                  $this->POST['buku_jenis_kegiatan_ref'],
                  $this->POST['buku_peranan_ref'],
                  $this->POST['pnltnTahunBuku'],
                  $this->POST['pnltnPenerbitBuku'],
                  $this->POST['pnltnKeteranganBuku'],
				  $nama_file
          );
		  //$this->dumper($array);exit();
		  //print_r($array);
      } else
      if ($tipe==2){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['artikel_jenis_buku_ref'],
                  $this->POST['pnltnJudulArtikel'],
                  $this->POST['artikel_jenis_kegiatan_ref'],
                  $this->POST['artikel_peranan_ref'],
                  $this->POST['pnltnTahunArtikel'],
                  $this->POST['pnltnKeteranganArtikel'],
				  $nama_file
          );  
      } else
      if ($tipe==3){
          //$check = $this->CheckPenelitian();
          //if ($check !== true) return $check;
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['karya_ilmiah_jenis_karya_ref'],
                  $this->POST['karya_ilmiah_jenis_penelitian_ref'],
                  $this->POST['pnltnJudulKaryaIlmiah'],
                  $this->POST['karya_ilmiah_peranan_ref'],
                  $this->POST['pnltnTahunKaryaIlmiah'],
                  $this->POST['karya_ilmiah_asal_dana_ref'],
                  $this->POST['pnltnKeteranganKaryaIlmiah'],
				  $nama_file
          );  
      } else
      if ($tipe==4){
          //$check = $this->CheckPublikasi();
          //if ($check !== true) return $check;
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['publikasi_jenis_publikasi_ref'],
                  $this->POST['pnltnJudulPublikasi'],
                  $this->POST['publikasi_peranan_ref'],
                  $this->POST['pnltnTahunPublikasi'],
                  $this->POST['pnltnKeteranganPublikasi'],
				  $nama_file
          );    
      }
      #$this->dumper($array);exit();
      $result = $this->Obj->Add($array,$tipe);
         if ($result){
			return $result;
            //Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
         }else{
			return false;
            //Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
         }
         return $this->pageView."&tipe=".$tipe;
  }
	
	function UpdateData($nama_file){
		    $tipe=$this->POST['pnltnTipePenelitianId'];
	    #$check = $this->Check($tipe);
      #if ($check !== true) return $check;
      if ($tipe==1){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['buku_jenis_buku_ref'],
                  $this->POST['pnltnJudulBuku'],
                  $this->POST['buku_jenis_kegiatan_ref'],
                  $this->POST['buku_peranan_ref'],
                  $this->POST['pnltnTahunBuku'],
                  $this->POST['pnltnPenerbitBuku'],
                  $this->POST['pnltnKeteranganBuku'],
				  $nama_file,
                  $this->POST['dataKode']
          );
      } else
      if ($tipe==2){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['artikel_jenis_buku_ref'],
                  $this->POST['pnltnJudulArtikel'],
                  $this->POST['artikel_jenis_kegiatan_ref'],
                  $this->POST['artikel_peranan_ref'],
                  $this->POST['pnltnTahunArtikel'],
                  $this->POST['pnltnKeteranganArtikel'],
				  $nama_file,
                  $this->POST['dataKode']
          );  
      } else
      if ($tipe==3){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['karya_ilmiah_jenis_karya_ref'],
                  $this->POST['karya_ilmiah_jenis_penelitian_ref'],
                  $this->POST['pnltnJudulKaryaIlmiah'],
                  $this->POST['karya_ilmiah_peranan_ref'],
                  $this->POST['pnltnTahunKaryaIlmiah'],
                  $this->POST['karya_ilmiah_asal_dana_ref'],
                  $this->POST['pnltnKeteranganKaryaIlmiah'] ,
				  $nama_file,
                  $this->POST['dataKode']
          );  
      } else
      if ($tipe==4){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['publikasi_jenis_publikasi_ref'],
                  $this->POST['pnltnJudulPublikasi'],
                  $this->POST['publikasi_peranan_ref'],
                  $this->POST['pnltnTahunPublikasi'],
                  $this->POST['pnltnKeteranganPublikasi'] ,
				  $nama_file,
                  $this->POST['dataKode']
          );    
      }
      //$this->dumper($array);exit();
      $result = $this->Obj->Update($array,$tipe);
         if ($result){
			return $result;
            #Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
         }else{
			return false;
            #Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
         }
      return $this->pageView."&tipe=".$tipe;
  }
	
    function InputData(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
	  //print_r($_FILES['file']['tmp_name']);exit;
	  //$tipe=$this->POST['pnltnTipePenelitianId'];
	  //echo ($this->POST['pnltnTipePenelitianId']);exit;
	  //if ($tipe==1){
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else {
				$nama_file = $this->POST['upload'];
			}
		//print_r($_FILES['file']['tmp_name']);exit;
		$rs_add = $this->AddData($nama_file);    
        if($rs_add == true){
		   
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload_buku']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
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
           Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $this->pageView;
        }else{
           Messenger::Instance()->Send('mutasi_penelitian','MutasiPenelitian','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
   //$return .= "&id=".$this->delId;
	return $return;
   }
   
   function ReloadData(){
      $tipe=$this->POST['pnltnTipePenelitianId'];
      $check = $this->Check($tipe);
      if ($check !== true) return $check;
      if ($tipe==1){
        $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED1", "selected");
         $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'buku');
         if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_buku', $data['input'], '');
	       }
      } else
      if ($tipe==2){
         $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED2", "selected");
         $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'artikel');
         if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_artikel', $data['input'], '');
	       }
      } else
      if ($tipe==3){
         $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED3", "selected");
        $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'penelitian');
        if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_penelitian', $data['input'], '');
	       }
      } else
      if ($tipe==4){
        $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED4", "selected");
        $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'publikasi');
        if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_publikasi', $data['input'], '');
	       }
      }
	  $this->mrTemplate->AddVars('content', $data['input'], '');
      return $this->pageReload."&tipe=".$tipe;
  }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>