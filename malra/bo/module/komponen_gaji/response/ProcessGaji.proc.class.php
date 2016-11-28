<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppGaji.class.php';
class ProcessGaji {

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
		$this->Obj = new AppGaji();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('komponen_gaji', 'gaji', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('komponen_gaji', 'inputGaji', 'view', 'html');
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='data can not be deleted';
       $this->msgReqDataEmpty='All field marked with * must be filled';
       $this->msgReqDataNum='The code field should not contain with numbers';
       $this->msgReqDataPnj='Code length must be 3 characters';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='data tidak dapat dihapus';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
       $this->msgReqDataNum='Kode tidak boleh mengandung angka';
       $this->msgReqDataPnj='Panjang kode harus 3 karakter';
     }
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['kode']) == "") {
				return "empty";
			} elseif(trim($this->_POST['nama']) == "") {
				return "empty";
			} elseif(trim($this->_POST['keterangan']) == "") {
				return "empty";
			} elseif(trim($this->_POST['jenis']) == "") {
				return "empty";
			} else {
            if(ereg("[0-9]", $this->_POST['kode'])) {
               return "hurufonly";
            } elseif(strlen(trim($this->_POST['kode'])) != 3) {
               return "panjangkurang";
            }
				return true;
			}
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();
		if($cek === true) {
			$addGaji = $this->Obj->DoAddData(strtoupper($this->_POST['kode']), $this->_POST['nama'], $this->_POST['keterangan'], $this->_POST['jenis']);
			if ($addGaji === true) {
				Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput;
		} elseif($cek == "hurufonly") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataNum),Messenger::NextRequest);
			return $this->pageInput;
		} elseif($cek == "panjangkurang") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataPnj),Messenger::NextRequest);
			return $this->pageInput;
		}
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		if($cek === true) {
			$updateGaji = $this->Obj->DoUpdateData(strtoupper($this->_POST['kode']), $this->_POST['nama'], $this->_POST['keterangan'], $this->_POST['jenis'], $this->decId);
			if ($updateGaji === true) {
				Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		} elseif($cek == "hurufonly") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataNum),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		} elseif($cek == "panjangkurang") {
			Messenger::Instance()->Send('komponen_gaji', 'inputGaji', 'view', 'html', array($this->_POST,$this->msgReqDataPnj),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		}
		return $this->pageView;
	}

	function Delete() {
		$arrId = $this->_POST['idDelete'];
		//print_r($this->_POST);
		$deleteArrData = $this->Obj->DoDeleteDataByArrayId($arrId);
		if($deleteArrData === true) {
			Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arrId);$i++) {
				$deleteData = false;
				$deleteData = $this->Obj->DoDeleteDataById($arrId[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
			Messenger::Instance()->Send('komponen_gaji', 'gaji', 'view', 'html', array($this->_POST, $gagal . ' '.$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
}
?>
