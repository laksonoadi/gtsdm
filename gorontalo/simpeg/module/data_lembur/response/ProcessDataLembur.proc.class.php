<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_lembur/business/lembur.class.php';

class Process {
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
		$this->Obj = new Lembur();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->pageInput = Dispatcher::Instance()->GetUrl('data_lembur', 'dataLembur', 'view', 'html');
		$this->pageView = Dispatcher::Instance()->GetUrl('data_lembur', 'historyDataLembur', 'view', 'html');
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
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg'];
			return $return;
		} 
		if($this->POST['tglaju_day'] == "0000" or $this->POST['tglaju_mon'] == "00" or $this->POST['tglaju_year'] == "00"){
			$error = $this->msgReqDataEmpty;
		}elseif($this->POST['tglstat_day'] == "0000" or $this->POST['tglstat_mon'] == "00" or $this->POST['tglstat_year'] == "00"){
			$error = $this->msgReqDataEmpty;
		}elseif(trim($this->POST['alasan']) == ''){
			$error = $this->msgReqDataEmpty;
		}
    
		if (isset($error)){
			$msg = array($this->POST, $error, $this->cssAlert);
			Messenger::Instance()->Send('data_lembur', 'dataLembur', 'view', 'html', $msg, Messenger::NextRequest);
      
			$return = $this->pageInput;
			/*if (($this->POST['op']=="edit") and (isset($this->POST['idIstri']))){ 
			$return .= "&dataId2=".$this->POST['idIstri'];
			}*/
			if (isset($this->POST['idPeg'])){ 
				$return .= "&dataId=".$this->POST['idPeg'];
			}
			return $return;
		}
		return true;
	}
  
	function AddDataLembur(){
		$a=$this->POST['tglaju_year'].'-'.$this->POST['tglaju_mon'].'-'.$this->POST['tglaju_day'];
		$b=$this->POST['tglstat_year'].'-'.$this->POST['tglstat_mon'].'-'.$this->POST['tglstat_day'];
    
		$c=$this->POST['start_jam'].':'.$this->POST['start_menit'].':00';
		$d=$this->POST['end_jam'].':'.$this->POST['end_menit'].':00';
    
		$array=array(
				'id'=>$this->POST['idPeg'],
				'no_lembur'=>$this->Obj->GetNumber('overtime'),
				'tglaju'=>$a,
				'mulai'=>$c,
				'selesai'=>$d,
				'alasan'=>$this->POST['alasan'],
				'status'=>$this->POST['status'],
				'tglstat'=>$b
			);
  
		$result = $this->Obj->Add($array);
    
		$rs_periode_lembur = $this->Obj->UpdatePeriodeLemburDiambil($this->POST['idPeg']);
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function UpdateDataLembur(){ 
		$a=$this->POST['tglaju_year'].'-'.$this->POST['tglaju_mon'].'-'.$this->POST['tglaju_day'];
		$b=$this->POST['tglstat_year'].'-'.$this->POST['tglstat_mon'].'-'.$this->POST['tglstat_day'];
		
		$c=$this->POST['start_jam'].':'.$this->POST['start_menit'].':00';
		$d=$this->POST['end_jam'].':'.$this->POST['end_menit'].':00';
    
		$array=array(
				'id'=>$this->POST['idPeg'],
				'no_lembur'=>$this->POST['no_lembur'],
				'tglaju'=>$a,
				'mulai'=>$c,
				'selesai'=>$d,
				'alasan'=>$this->POST['alasan'],
				'status'=>$this->POST['status'],
				'tglstat'=>$b,
				'dataId'=>$this->POST['dataId2']
			);
  
		$result = $this->Obj->Update($array);
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function InputDataLembur(){
		$check = $this->Check();
		if ($check !== true) return $check;
		if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
			$rs_add = $this->AddDataLembur();
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg'];
			if($rs_add == true){
				Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
				return $return;
			}
        
		} else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
			$rs_update = $this->UpdateDataLembur();
			$return = $this->pageView;
			$return .= "&dataId=".$this->POST['idPeg'];
			if($rs_update == true){
				Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
				return $return;
			}else{
				Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
				return $return;
			}
		}
	}
   
	function Delete(){
		$deleteData = $this->Obj->Delete($this->POST['idDelete']); 
		if($deleteData == true) {
			Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_lembur', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
		$return = $this->pageView;
		$return .= "&dataId=".$this->decId2;
		return $return;
	}
}

?>