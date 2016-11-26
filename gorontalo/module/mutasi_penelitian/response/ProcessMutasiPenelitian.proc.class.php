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
   
   function __construct() {
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
	  }else{
	    $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html',true).'&id='.$this->profilId;
		$this->pageView = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html',true).'&id='.$this->profilId;
		$this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian','view','html').'&id='.$this->delId;
	  }
       
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
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
      $tipe=$this->POST['pnltnTipePenelitianId'];
      //$check = $this->Check($tipe);
      //if ($check !== true) return $check;
      if ($tipe==1){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_buku_ref'],
                  $this->POST['pnltnJudulBuku'],
                  $this->POST['jenis_kegiatan_ref'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnPenerbit'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file
          );
      } else
      if ($tipe==2){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_buku_ref'],
                  $this->POST['pnltnJudulArtikel'],
                  $this->POST['jenis_kegiatan_ref'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file
          );  
      } else
      if ($tipe==3){
          //$check = $this->CheckPenelitian();
          //if ($check !== true) return $check;
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_karya_ref'],
                  $this->POST['jenis_penelitian_ref'],
                  $this->POST['pnltnJudulKaryaIlmiah'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['asal_dana_ref'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file				
          );  
      } else
      if ($tipe==4){
          //$check = $this->CheckPublikasi();
          //if ($check !== true) return $check;
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_publikasi_ref'],
                  $this->POST['pnltnJudulPublikasi'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file
          );    
      }
      //$this->dumper($array);exit();
		$result = $this->Obj->Add($array,$tipe);
		
		//Ini Yang Integrasi Dengan gtAkademik
		$this->StatusIntegrasiAkademik=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtakademik');
		if (($this->StatusIntegrasiAkademik)&&($result)){
		    $id=$this->Obj->LastInsertId();
		    $dataLastInsertedId=$this->Obj->GetDataMutasiById($this->POST['pegKode'],$this->Obj->LastInsertId());
			$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtakademik');
			$mgjIntegrasi = new MutasiPenelitian($nomorkoneksi);
			$mgjIntegrasi->connect();
			$arrayIntegrasi = array(
									'pubdDsnPegNip'=>$dataLastInsertedId[0]['pegKodeResmi'],
									'pubdJudul'=>$dataLastInsertedId[0]['pnltnJudulPublikasi']
								);
			$dataMgj = $mgjIntegrasi->AddIntegrasi($arrayIntegrasi,$tipe);
			$this->Obj->connect();
		}
		//==End Integrasi dengan gtAkademik
		
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
	    $check = $this->Check($tipe);
      if ($check !== true) return $check;
      if ($tipe==1){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_buku_ref'],
                  $this->POST['pnltnJudulBuku'],
                  $this->POST['jenis_kegiatan_ref'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnPenerbit'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file,
                  $this->POST['dataKode']
          );
      } else
      if ($tipe==2){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_buku_ref'],
                  $this->POST['pnltnJudulArtikel'],
                  $this->POST['jenis_kegiatan_ref'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnKeterangan'],
				  $nama_file,
                  $this->POST['dataKode']
          );  
      } else
      if ($tipe==3){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_karya_ref'],
                  $this->POST['jenis_penelitian_ref'],
                  $this->POST['pnltnJudulKaryaIlmiah'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['asal_dana_ref'],
                  $this->POST['pnltnKeterangan'] ,
				  $nama_file,
                  $this->POST['dataKode']
          );  
      } else
      if ($tipe==4){
          $array=array(
                  $this->POST['pegKode'],
                  $this->POST['jenis_publikasi_ref'],
                  $this->POST['pnltnJudulPublikasi'],
                  $this->POST['peranan_ref'],
                  $this->POST['pnltnTahun'],
                  $this->POST['pnltnKeterangan'] ,
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
			       @unlink(GTFWConfiguration::GetValue( 'application', 'file_save_path').$this->POST['upload_buku']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'file_save_path').$nama_file);
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
			       @unlink(GTFWConfiguration::GetValue( 'application', 'file_save_path').$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'file_save_path').$nama_file);
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
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>