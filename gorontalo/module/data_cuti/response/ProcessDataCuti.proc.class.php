<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'bo_save_path') . 'module/email/business/Email.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

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
	var $startDate;
	var $endDate;
   
	function __construct() {
		$this->Obj = new Cuti();
		$this->ObjEmail = new Email();
		$this->pegawaiObj = new DataPegawai();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->pageView = Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html');
		$this->pageHistory = Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html');
		$this->decId = $_GET['dataId2']->Integer()->Raw();
		$this->decId2 = $_GET['dataId']->Integer()->Raw();
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
			$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
			$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
			$this->msgDeleteSuccess='Data Cancelled successfully';$this->msgDeleteFail='Data Cancelled failed';
			$this->msgReqDataEmpty='All field marked with * and date field must be filled';
		}else{
			$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
			$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
			$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
			$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
		}
     
		$this->startDate=$this->ObjEmail->IndonesianDate($_POST['tgl_mulai_year'].'-'.$_POST['tgl_mulai_mon'].'-'.$_POST['tgl_mulai_day'],'YYYY-MM-DD');
		$this->endDate=$this->ObjEmail->IndonesianDate($_POST['tgl_selesai_year'].'-'.$_POST['tgl_selesai_mon'].'-'.$_POST['tgl_selesai_day'],'YYYY-MM-DD');
	}
	
	function SetPost($param){
		$this->POST = $param->AsArray();
		$this->POST['reduced']='No';
	}
  
	function Check (){
		if (isset($this->POST['btnbatal'])){
			if($_GET['op'] == 'add'){
				$return = $this->pageView;
				$return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->POST['id'];
			} elseif($_GET['op'] == 'edit') {
				$return = $this->pageHistory;
				$return .= "&dataId=".$this->POST['idPeg'];
			}
      
			return $return;
		} 
		if(trim($this->POST['tipe']) == ''){
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
  
	function AddDataCuti(){
		$a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
		$b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
		$Durasi = $this->POST['durasi'];
	
		$periodeCuti = $this->Obj->GetPeriodeCutiByPegId($this->POST['idPeg']);
    
		$array=array(
				'peg_id'=>$this->POST['idPeg'],
				'no_cuti'=>$this->Obj->GetNumber('leave'),
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
		
		$Durasi = $this->POST['durasi'];
	
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
									
		
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function DoEmail($dataId,$file){
		//Block Untuk Kirim Email
        $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
        $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
        $hrd1=GTFWConfiguration::GetValue('application', 'email_hrd1');
		$hrd2=GTFWConfiguration::GetValue('application', 'email_hrd2');
        $to=$hrd1;
        $cc=$hrd2;
          
        $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
        $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
        $arrBody[2]['replace']='{CUTI_ALASAN}'; $arrBody[2]['with']=$_POST['alasan'];
        $arrBody[3]['replace']='{CUTI_NUMBER}'; $arrBody[3]['with']=$_POST['no_cuti'];
        $body=$this->ObjEmail->getBodyEmail($file,$arrBody);
        $subject=$this->ObjEmail->getSubjectEmail($file);
        //$x = array($to,$cc,$bcc,$from,$subject,$body);
        //print_r($x);exit;
        $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body); 
        //Akhir Kirim Email
		return $kirim;
    }
	
	function InputDataCuti(){
		$check = $this->Check();
		if ($check !== true) return $check;
		if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
			$rs_add = $this->AddDataCuti();
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg'];
			if($rs_add == true){
				$kirim=$this->DoEmail($this->POST['id'],'email_cuti_request');
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
				$kirim=$this->DoEmail($this->POST['id'],'email_cuti_request_edited');
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
			$kirim=$this->DoEmail($this->POST['idDelete'],'email_cuti_request_edited');
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