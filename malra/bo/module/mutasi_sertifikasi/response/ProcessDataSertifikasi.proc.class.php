<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_sertifikasi/business/sertifikasi.class.php';

class Process{
	var $POST;
	var $user;
	var $Obj;
	var $cssAlert = "notebox-alert";
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";
	var $pageInput;
	var $srtfkId;
	var $pageView;
   
	function __construct() {
		$this->Obj = new Sertifikasi();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->pageView = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html');
		$this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html');
		$this->srtfkId = $_GET['srtfkId']->Integer()->Raw();
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
			$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
			$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
			$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
			$this->msgReqDataEmpty='All field marked with * and date field must be filled';
		}else{
			$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
			$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
			$this->msgVerifySuccess='Verifikasi data berhasil dilakukan';$this->msgVerifyFail='Verifikasi data gagal dilakukan';
			$this->msgNilaiSuccess='Penilaian berhasil dilakukan';$this->msgNilaiFail='Penilaian gagal dilakukan';
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
	
		if($_GET['op'] == 'add'){
			if($this->POST['srtfkPeriodeAwal_day'] == "0000" or $this->POST['srtfkPeriodeAwal_mon'] == "00" or $this->POST['srtfkPeriodeAwal_year'] == "00"){
				$error = $this->msgReqDataEmpty;
			}elseif($this->POST['srtfkPeriodeAkhir_day'] == "0000" or $this->POST['srtfkPeriodeAkhir_mon'] == "00" or $this->POST['srtfkPeriodeAkhir_year'] == "00"){
				$error = $this->msgReqDataEmpty;
			}elseif(trim($this->POST['srtfkTahun']) == ''){
				$error = $this->msgReqDataEmpty;
			}
		}else{
			if(trim($this->POST['srtfkTahun']) == ''){
				$error = $this->msgReqDataEmpty;
			}
		}
		
		if (isset($error)){
			$msg = array($this->POST, $error, $this->cssAlert);
			Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', $msg, Messenger::NextRequest);
      
			$return = $this->pageView;
			
			return $return;
		}
		return true;
	}
  
	function AddDataSertifikasi(){
		$srtfkPeriodeAwal=$this->POST['srtfkPeriodeAwal_year'].'-'.$this->POST['srtfkPeriodeAwal_mon'].'-'.$this->POST['srtfkPeriodeAwal_day'];
		$srtfkPeriodeAkhir=$this->POST['srtfkPeriodeAkhir_year'].'-'.$this->POST['srtfkPeriodeAkhir_mon'].'-'.$this->POST['srtfkPeriodeAkhir_day'];
		
		$this->Obj->StartTrans();
		$array=array(
			'srtfkTahun'=>$this->POST['srtfkTahun'],
            'srtfkPeriodeAwal'=>$srtfkPeriodeAwal,
            'srtfkPeriodeAkhir'=>$srtfkPeriodeAkhir,
            'srtfkCreatedUserId'=>$this->user);
		$result = $this->Obj->DoAddUsulanSertifikasi($array);
		
		$srtfkId = $this->Obj->GetLastUsulanSertifikasiId();
		
		$arrPegawai = $this->getPOST();
		$arrPegawai = $arrPegawai['tambah'];
		
		$arrPegId=implode(',',$arrPegawai['srtfkdetPegId']);
		
		if ($result){
			$array=array(
				'srtfkId'=>$srtfkId,
				'srtfkArrPegId'=>$arrPegId);
			$result = $this->Obj->DoDeletePesertaSertifikasi($array);
			
			
			for ($i=0; $i<sizeof($arrPegawai['srtfkdetPegId']); $i++){
				$array=array(
					'srtfkId'=>$srtfkId,
					'srtfkArrPegId'=>$arrPegawai['srtfkdetPegId'][$i]);
				if ($result) {
					$result = $this->Obj->DoAddPesertaSertifikasi($array);
				}else{ break; }
			}
		}
		
		$this->Obj->EndTrans($result);
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function UpdateDataSertifikasi(){ 
		$srtfkPeriodeAwal=$this->POST['srtfkPeriodeAwal_year'].'-'.$this->POST['srtfkPeriodeAwal_mon'].'-'.$this->POST['srtfkPeriodeAwal_day'];
		$srtfkPeriodeAkhir=$this->POST['srtfkPeriodeAkhir_year'].'-'.$this->POST['srtfkPeriodeAkhir_mon'].'-'.$this->POST['srtfkPeriodeAkhir_day'];
		
		$this->Obj->StartTrans();
		$array=array(
			'srtfkTahun'=>$this->POST['srtfkTahun'],
            'srtfkPeriodeAwal'=>$srtfkPeriodeAwal,
            'srtfkPeriodeAkhir'=>$srtfkPeriodeAkhir,
            'srtfkModifiedUserId'=>$this->user,
			'srtfkId'=>$this->srtfkId);
		$result = $this->Obj->DoUpdateUsulanSertifikasi($array);
		
		$arrPegawai = $this->getPOST();
		$arrPegawai = $arrPegawai['tambah'];
		
		
		$arrPegId='srtfkdetPegId=0 ';
		for ($i=0; $i<sizeof($arrPegawai['srtfkdetPegId']); $i++){
			$arrPegId .=' OR srtfkdetPegId='.$arrPegawai['srtfkdetPegId'][$i];
		}
		
		$srtfkId = $_POST['srtfkId'];
		if ($result){
			$array=array(
				'srtfkId'=>$srtfkId,
				'srtfkArrPegId'=>$arrPegId);
			$result = $this->Obj->DoDeletePesertaSertifikasi($array);
			
			
			for ($i=0; $i<sizeof($arrPegawai['srtfkdetPegId']); $i++){
				$array=array(
					'srtfkId'=>$srtfkId,
					'srtfkArrPegId'=>$arrPegawai['srtfkdetPegId'][$i]);
				if ($result) {
					$result = $this->Obj->DoAddPesertaSertifikasi($array);
				}else{ break; }
			}
			
		}
		
		if ($result) $result = $this->Obj->DoModifiedSertifikasi(array($this->POST['srtfkId']));
		$this->Obj->EndTrans($result);
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function VerifikasiDataSertifikasi(){
		
		$arrPegId=array();
		
		$data = $this->POST['data']['tambah'];
		for ($i=0; $i<sizeof($data['srtfkdetPegId']); $i++){
			$arrPegId[]=$data['srtfkdetPegId'][$i];
		}
		
		$result=true;
		$srtfkTahun=$this->POST['srtfkTahun'];
		$this->Obj->StartTrans(); 
		
		for ($i=0; $i<sizeof($arrPegId); $i++){
			if (($data['srtfkdetVerifikasi'][$arrPegId[$i]]=='on')||($data['srtfkdetIsVerifikasi'][$arrPegId[$i]]=='1')){
				if ($data['srtfkdetNoPeserta'][$arrPegId[$i]]==''){
					$data['srtfkdetNoPeserta'][$arrPegId[$i]]= $this->Obj->GetNomorPeserta(array($srtfkTahun,$arrPegId[$i],$srtfkTahun));
				}
				$array=array(
					'srtfkdetNoPeserta'=>$data['srtfkdetNoPeserta'][$arrPegId[$i]],
					'srtfkdetTahun'=>$srtfkTahun,
		            'srtfkdetPegId'=>$arrPegId[$i]);
				$result = $this->Obj->DoUpdateNoPesertaSertifikasi($array);
				if (!$result) break;
			}
		}
		
		if ($result) $result = $this->Obj->DoModifiedSertifikasi(array($this->POST['srtfkId']));
		$this->Obj->EndTrans($result);
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function PenilaianDataSertifikasi(){
		$arrPegId=array();
		
		$data = $this->POST['data']['tambah'];
		for ($i=0; $i<sizeof($data['srtfkdetPegId']); $i++){
			$arrPegId[]=$data['srtfkdetPegId'][$i];
		}
		
		$result=true;
		$srtfkTahun=$this->POST['srtfkTahun'];
		$this->Obj->StartTrans();
		
		for ($i=0; $i<sizeof($arrPegId); $i++){
			if ($data['srtfkdetIsVerifikasi'][$arrPegId[$i]]=='1'){
				$array=array(
					'srtfkdetNo'=>$data['srtfkdetNo'][$arrPegId[$i]],
					'srtfkdetPersep'=>$data['srtfkdetPersep'][$arrPegId[$i]],
					'srtfkdetPerson'=>$data['srtfkdetPerson'][$arrPegId[$i]],
					'srtfkdetGabPAK'=>$data['srtfkdetGabPAK'][$arrPegId[$i]],
					'srtfkdetKonst'=>$data['srtfkdetKonst'][$arrPegId[$i]],
					'srtfkdetHasilAkhir'=>$data['srtfkdetHasilAkhir'][$arrPegId[$i]],
					'srtfkdetATDL'=>$data['srtfkdetATDL'][$arrPegId[$i]],
					'srtfkdetAsesorI'=>$data['srtfkdetAsesorI'][$arrPegId[$i]],
					'srtfkdetAsesorII'=>$data['srtfkdetAsesorII'][$arrPegId[$i]],
					'srtfkdetIsLock'=>$data['srtfkdetIsLock'][$arrPegId[$i]]=='on'?'1':'0',
					'srtfkdetTahun'=>$srtfkTahun,
		            'srtfkdetPegId'=>$arrPegId[$i]);
				$result = $this->Obj->DoUpdatePenilaian($array);
				if (!$result) break;
			}
		}
		
		if ($result) $result = $this->Obj->DoModifiedSertifikasi(array($this->POST['srtfkId']));
		$this->Obj->EndTrans($result);
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function InputDataSertifikasi(){
		$check = $this->Check();
		if ($check !== true) return $check;
		if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
			$rs_add = $this->AddDataSertifikasi($nama_file);
		
			$return = $this->pageView;
			$return .= "&srtfkId=".$this->POST['srtfkId'];
			if($rs_add == true){
				Messenger::Instance()->Send('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
        
		}elseif ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
			$rs_update = $this->UpdateDataSertifikasi();
		
			$return = $this->pageView;
			$return .= "&srtfkId=".$this->POST['srtfkId'];
			if($rs_update == true){
				Messenger::Instance()->Send('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
        
		}elseif ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'verify')) {
			$rs_verify = $this->VerifikasiDataSertifikasi();
		
			$return = $this->pageView;
			$return .= "&srtfkId=".$this->POST['srtfkId'];
			if($rs_verify == true){
				Messenger::Instance()->Send('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html', array($this->POST,$this->msgVerifySuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgVerifyFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
		}elseif ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'nilai')) {
			$rs_verify = $this->PenilaianDataSertifikasi();
		
			$return = $this->pageView;
			$return .= "&srtfkId=".$this->POST['srtfkId'];
			if($rs_verify == true){
				Messenger::Instance()->Send('mutasi_sertifikasi', 'historyDataSertifikasi', 'view', 'html', array($this->POST,$this->msgNilaiSuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgNilaiFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
        
		}
	}
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_sertifikasi', 'dataSertifikasi', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
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
		}
			
		return $data;
	}
}

?>