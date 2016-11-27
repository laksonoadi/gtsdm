<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_cuti/business/cuti.class.php';

class Process{
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
	var $encId;
   
	function __construct() {
		$this->Obj = new Cuti();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->pageView = Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html');
		$this->pageHistory = Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html');
		$this->decId = $_GET['dataId2']->Integer()->Raw();
		$this->decId2 = $_GET['dataId']->Integer()->Raw();
		$this->encId =$this->decId;
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
				$return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->POST['id'];
				//print_r($this->POST['id']);exit;
			} elseif($_GET['op'] == 'edit') {
				$return = $this->pageHistory;
				$return .= "&dataId=".$this->POST['idPeg'];
			}
			return $return;
		} 
		if($this->POST['tgl_mulai_day'] == "0000" or $this->POST['tgl_mulai_mon'] == "00" or $this->POST['tgl_mulai_year'] == "00"){
			$error = $this->msgReqDataEmpty;
		}elseif($this->POST['tgl_selesai_day'] == "0000" or $this->POST['tgl_selesai_mon'] == "00" or $this->POST['tgl_selesai_year'] == "00"){
			$error = $this->msgReqDataEmpty;
		}elseif(trim($this->POST['tipe']) == ''){
			$error = $this->msgReqDataEmpty;
		}elseif(trim($this->POST['alasan']) == ''){
			$error = $this->msgReqDataEmpty;
		}
    
		if (isset($error)){
			$msg = array($this->POST, $error, $this->cssAlert);
			Messenger::Instance()->Send('data_cuti', 'dataCuti', 'view', 'html', $msg, Messenger::NextRequest);
      
			$return = $this->pageView;
			if ($this->POST['op']=="edit"){ 
				$return = $this->pageHistory;
				$return .= "&dataId2=".$_GET['dataId2'];
			}
			if (isset($this->POST['idPeg'])){ 
				$return .= "&dataId=".$this->POST['idPeg'];
			}
			return $return;
		}
		return true;
	}

	function dateDiff($dformat, $endDate, $beginDate)	{
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
		$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
		return $end_date - $start_date + 1;
	}
	
	function AddDataCuti(){
		$a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
		$b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
		$Durasi = $this->POST['durasi'];
	
		$periodeCuti = $this->Obj->GetPeriodeCutiByPegId($this->POST['idPeg']);
    
		$array=array(
				'peg_id'=>$this->POST['idPeg'],
				//'no_cuti'=>$this->Obj->GetNumber('leave'),
				'no_cuti'=>$this->POST['idPeg'].$periodeCuti[0]['per_id'],
				'tgl_mulai'=>$a,
				'tgl_selesai'=>$b,
				'tipe'=>$this->POST['tipe'],
				'reduced'=>$this->POST['reduced'],
				'alasan'=>$this->POST['alasan'],
				'status'=>$this->POST['status'],
				'tggjwbker'=>$this->POST['tggjwbker'],
				'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
				'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],
				'per_id'=>$periodeCuti[0]['per_id']
				);
    
		
		$result = $this->Obj->Add($array);
		$lastId = $this->Obj->GetLastId();
		$dataCutiAdded = $this->Obj->GetDataCutiDet($lastId[0]['last_id']);
		if (($array['status']=='approved')&&($array['reduced'] == 'Yes')){
			$rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambil($dataCutiAdded[0]['durasi'],$dataCutiAdded[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
		}
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function UpdateDataCuti(){ 
		$a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
		$b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
		$c=$this->POST['tgl_stat_year'].'-'.$this->POST['tgl_stat_mon'].'-'.$this->POST['tgl_stat_day'];
		
		$Durasi = $this->POST['durasi']; //$this->dateDiff("-",$b,$a);
	
		$dataCuti = $this->Obj->GetDataCutiDet($this->POST['cuti_id']);
		$periodeCuti = $this->Obj->GetPeriodeCutiByPegId($this->POST['idPeg']);
	
		$array=array(
					'peg_id'=>$this->POST['idPeg'],
					'no_cuti'=>$this->POST['no_cuti'],
					'tgl_mulai'=>$a,
					'tgl_selesai'=>$b,
					'tipe'=>$this->POST['tipe'],
					'reduced'=>$this->POST['reduced'],
					'alasan'=>$this->POST['alasan'],
					'status'=>$this->POST['status'],
					'tglstat'=>$c, 
					'tggjwbker'=>$this->POST['tggjwbker'],
					'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
					'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],
					'per_id'=>$periodeCuti[0]['per_id'],
					'id'=>$this->POST['cuti_id']
					);
		
		$result = $this->Obj->Update($array);
		$dataCutiUpdated = $this->Obj->GetDataCutiDet($this->POST['cuti_id']);
									
		if (($dataCuti[0]['status']!='approved')&&($dataCutiUpdated[0]['status']=='approved')){
			if (($dataCutiUpdated[0]['reduced']=='Yes')){
				//Karena Sebelumnya tidak ada pengurangan jatah, maka jatah hanya dikurangi untuk update yang sekarang
				$rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
			}
		}elseif (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']!='approved')){
			if (($dataCuti[0]['reduced']=='Yes')){
				//Karena Sebelumnya ada pengurangan jatah, maka jatah dikembalikan lagi ke periode untuk update yang sekarang apapun statusnya
				$rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
			}
		}elseif (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']=='approved')){
			if (($dataCuti[0]['reduced']=='Yes')&&($dataCutiUpdated[0]['reduced']=='Yes')){
				//Mengembalikan yang terdahulu dulu, baru kemudian dikurangi lagi dengan update yang sekarang
				$rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
				$rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
			}elseif (($dataCuti[0]['reduced']=='No')&&($dataCutiUpdated[0]['reduced']=='Yes')){
				//mengurangi jatah untuk update yang sekarang
				$rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
			}elseif (($dataCuti[0]['reduced']=='Yes')&&($dataCutiUpdated[0]['reduced']=='No')){
				//mengembalikan jatah yang dikurangi terdahulu
				$rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
			}elseif (($dataCuti[0]['reduced']=='No')&&($dataCutiUpdated[0]['reduced']=='No')){
				//Nothing To Do
			}
		}
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function InputDataCuti(){
		$check = $this->Check();
		if ($check !== true) return $check;
		if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
			$rs_add = $this->AddDataCuti();
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg'];
			if($rs_add == true){
				Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
        
		}else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
			$rs_update = $this->UpdateDataCuti();
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->decId;
			//print_r($this->decId);exit;
			if($rs_update == true){
				Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
				return $return;
			}
		}
	}
   
	function Delete(){
		$deleteData = $this->Obj->Delete($this->POST['idDelete']); 
		if($deleteData == true) {
			Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
		$return = $this->pageView;
		$return .= "&dataId=".$this->decId2;
		return $return;
	}
}

?>
