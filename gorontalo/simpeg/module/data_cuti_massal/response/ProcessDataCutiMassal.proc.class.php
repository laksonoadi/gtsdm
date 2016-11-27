<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_cuti_massal/business/cuti_massal.class.php';

class Process
{
   var $POST;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $decId2;
   var $pageView;
   
   function __construct() {
    $this->Obj = new CutiMassal();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_cuti_massal', 'dataCutiMassal', 'view', 'html');
	  $this->pageBack = Dispatcher::Instance()->GetUrl('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html');
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * and date field must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
     }
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      if($_GET['op'] == 'add'){
        $return = $this->pageView;
      }else{
        $return = $this->pageBack;
      }
      $return .= "&dataId=".$this->POST['id'];
      return $return;
    } 
    if($this->POST['mulai_day'] == "0000" or $this->POST['mulai_mon'] == "00" or $this->POST['mulai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif($this->POST['selesai_day'] == "0000" or $this->POST['selesai_mon'] == "00" or $this->POST['selesai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['nama']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['alasan']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_cuti_massal', 'dataCutiMassal', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;

      return $return;
    }
    return true;
  }
  
  function AddDataCutiMassal($nama_file){
    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    //$arrPegawai = $this->getPOST();
		
	//$pegcutiId = $arrPegawai[0]['id'];	
    $array=array(
            'nama'=>$this->POST['nama'],
            'mulai'=>$mulai,
            'selesai'=>$selesai,
            'alasan'=>$this->POST['alasan'],
            'file'=>$nama_file);
    $result = $this->Obj->Add($array);
	//print_r($result);die;
	$arrPegawai = $this->getPOST();
    $arrPegawai = $arrPegawai['tambah'];
	$getCutimassalId=$this->Obj->GetMaxCutiMassalId();
	
		
    
    for($i=0;$i<sizeof($arrPegawai);$i++) {
      $resultCutiMassalPeg = $this->Obj->AddCutiMassalPegawai($arrPegawai[$i],$getCutimassalId);
	  //print_r($this->Obj->getLastError()); exit;
      /*$arrayCuti=array($arrPegawai[$i],'no_cuti'=>$this->POST['no_cuti'],'mulai'=>$mulai,'selesai'=>$selesai,
        'tipe'=>"4",'reduced'=>"Yes",'alasan'=>$this->POST['alasan'],'tggjwbker'=>$this->POST['tggjwbker'],'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
        'pggjwbsmntk'=>$this->POST['pggjwbsmntk']);
	   //print_r($arrayCuti); echo '<hr>';
      $resultMassalCuti = $this->Obj->AddMassalCuti($arrayCuti);
	  //print_r($resultMassalCuti);die;
      if($array['reduced'] == 'Yes'){
        $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambil($this->POST['idPeg']);
      }elseif($array['reduced'] == 'No'){
        //no update periode cuti
      }*/
    }
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataCutiMassal($nama_file){ 
    $array=array('nama'=>$this->POST['nama'],'mulai'=>$mulai,'selesai'=>$selesai,'alasan'=>$this->POST['alasan'],'file'=>$nama_file);
    
    $arrPegawai = $this->getPOST();
		$arrPegawai = $arrPegawai['tambah'];
    
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    
    $array=array('no_kartu'=>$this->POST['no_kartu'],'hubungan'=>$this->POST['hubungan'],'nama'=>$this->POST['nama'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'tgl_selesai'=>$b,'id_lain'=>$this->POST['id_lain'],
      'kerja'=>$this->POST['kerja'],'ket'=>$this->POST['ket'],'tunjangan'=>$this->POST['tunjangan'],'mati'=>$this->POST['mati'],
      'id'=>$this->POST['idIstri']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataCutiMassal(){
      //print_r($this->POST);exit;
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }

        $rs_add = $this->AddDataCutiMassal($nama_file);
		//print_r($rs_add);exit;
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['id'];
        if($rs_add == true){
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
	         
           Messenger::Instance()->Send('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_cuti_massal', 'dataCutiMassal', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }/*else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        if (!empty($_FILES['file']['tmp_name'])){
		 	    $nama_file = rand(0,10000).trim($_FILES['file']['name']);
		    }else{
		 	    $nama_file = $this->POST['upload'];
		 	  }
		 	  
        $rs_update = $this->UpdateDataCutiMassal($nama_file);
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_update == true){
           if (!empty($_FILES['file']['tmp_name'])){
			       @unlink(GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$this->POST['upload']);
			       move_uploaded_file($_FILES['file']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot').'upload_file/file/'.$nama_file);
	         }
           Messenger::Instance()->Send('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_cuti_massal', 'dataCutiMassal', 'view', 'html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      } */
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('data_cuti_massal', 'dataCutiMassal', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_cuti_massal', 'dataCutiMassal', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
   
   function getPOST() {
      $data = false;
	
      if(isset($_POST['data'])) {
         if(is_object($_POST['data']))	  
            $data=$_POST['data']->AsArray();		 
         else
            $data=$_POST['data'];	 
		 
         if(isset($data['tambah'])) {		    
            $i=0;
            foreach($data['tambah']['id'] as $key => $val) {
               $data['tambah'][$i]['id']=$val;			   
               $data['tambah'][$i]['kode']=$data['tambah']['kode'][$key];
               $data['tambah'][$i]['nama']=$data['tambah']['nama'][$key];
               $i++;
            }
            unset($data['tambah']['id']);			
            unset($data['tambah']['kode']);			
            unset($data['tambah']['nama']);
         }//end ifisset tambah
      }//end if isset post
	   
      return $data;
   }
}

?>