<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji_karyawan/business/AppKomponenGaji.class.php';
class ProcessKomponen {

	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;

	function __construct() {
		$this->Obj = new AppKomponen();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('komponen_gaji_karyawan', 'inputKomponen', 'view', 'html');
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='data can not be deleted';
       $this->msgReqDataEmpty='All field marked with * must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='data gagal dihapus';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
     }
  }

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['komponen_nama']) == "") {
				return "emptyNama";
			} elseif(trim($this->_POST['formula']) == "") {
				return "emptyFormula";
			} else return true;
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();
		if($cek === true) {
			$addKomponen = $this->Obj->DoAddKomponen($this->_POST['komponen_nama'],$this->_POST['formula']);
			if ($addKomponen === true) {
				Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "emptyNama") {
			Messenger::Instance()->Send('komponen_gaji_karyawan', 'inputKomponen', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput;
		} elseif($cek == "emptyFormula") {
         Messenger::Instance()->Send('komponen_gaji_karyawan', 'inputKomponen', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
         return $this->pageInput;   
      }
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		if($cek === true) {
			$updateKomponen = $this->Obj->DoUpdateKomponen($this->_POST['komponen_nama'],$this->_POST['formula'], $this->decId);
			if ($updateKomponen === true) {
				Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "emptyNama") {
			Messenger::Instance()->Send('komponen_gaji_karyawan', 'inputKomponen', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		}  elseif($cek == "emptyFormula") {
         Messenger::Instance()->Send('komponen_gaji_karyawan', 'inputKomponen', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
         return $this->pageInput . "&dataId=" . $this->encId;
      }
		return $this->pageView;
	}

	function Delete() {
		$arrId = $this->_POST['idDelete'];
		//print_r($this->_POST);
		$deleteArrData = $this->Obj->DoDeleteKomponenByArrayId($arrId);
		if($deleteArrData === true) {
			Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arrId);$i++) {
				$deleteData = false;
				$deleteData = $this->Obj->DoDeleteKomponenById($arrId[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
			Messenger::Instance()->Send('komponen_gaji_karyawan', 'komponenGaji', 'view', 'html', array($this->_POST, $gagal . ' '.$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
}
?>
