<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppDetilGaji.class.php';
class ProcessDetilGaji {

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
		$this->Obj = new AppDetilGaji();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->decDetilId = Dispatcher::Instance()->Decrypt($_GET['id_detil']);
		$this->encDetilId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html') . '&dataId=' . $this->encId;
		$this->pageInput = $this->pageView;
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='data can not be deleted';$this->msgDeleteSuccess2='data deleted';
       $this->msgReqDataEmpty='All field marked with * must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='data tidak dapat dihapus';$this->msgDeleteSuccess2='data dihapus';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
     }
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['kode_detil']) == "") {
				return "empty";
			} elseif(trim($this->_POST['nama_detil']) == "") {
				return "empty";
			} elseif(trim($this->_POST['setting_detil']) == "") {
				return "empty";
         } elseif(!checkdate($this->_POST['tanggal_berlaku_mon'], $this->_POST['tanggal_berlaku_day'], $this->_POST['tanggal_berlaku_year'])) {
				return "empty";
			} elseif(trim($this->_POST['nominal_detil']) == "" && trim($this->_POST['persen_detil']) == "") {
				return "empty";			
			} else {
            if($this->_POST['setting_detil'] == "persen") {
               //persen
               $this->data['persen'] = $this->_POST['persen_detil'];
               $this->data['nominal'] = "0.00";
            } else {
               //nominal
               $this->data['nominal'] = $this->_POST['nominal_detil'];
               $this->data['persen'] = NULL;
            }
            $this->data['tanggal_berlaku'] = $this->_POST['tanggal_berlaku_year'] . "-" . $this->_POST['tanggal_berlaku_mon'] . "-" . $this->_POST['tanggal_berlaku_day'];
				return true;
			}
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();
		if($cek === true) {
			$addGaji = $this->Obj->DoAddData(array($this->decId, $this->_POST['kode_detil'], $this->_POST['nama_detil'], $this->_POST['setting_detil'], $this->data['nominal'], $this->data['persen'], $this->data['tanggal_berlaku']));
			if ($addGaji === true) {
				Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			   return $this->pageView;
			} else {
				Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			   return $this->pageView . '&input_error=' . Dispatcher::Instance()->Encrypt(1);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty, $this->cssFail),Messenger::NextRequest);
			return $this->pageView . '&input_error=' . Dispatcher::Instance()->Encrypt(1);
		}
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		if($cek === true) {
			$updateGaji = $this->Obj->DoUpdateData(array($this->_POST['kode_detil'], $this->_POST['nama_detil'], $this->_POST['setting_detil'], $this->data['nominal'], $this->data['persen'], $this->data['tanggal_berlaku'], $this->decDetilId));
			if ($updateGaji === true) {
				Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
		      return $this->pageView;
			} else {
				Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
		      return $this->pageView . '&id_detil=' . $this->encDetilId;
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty, $this->cssFail),Messenger::NextRequest);
			return $this->pageView . '&input_error=' . Dispatcher::Instance()->Encrypt(1);
		}
		return $this->pageView;
	}

	function Delete() {
		$arrId = $this->_POST['idDelete'];
		//print_r($this->_POST);
		$deleteArrData = $this->Obj->DoDeleteDataByArrayId($arrId);
		if($deleteArrData === true) {
			Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arrId);$i++) {
				$deleteData = false;
				$deleteData = $this->Obj->DoDeleteDataById($arrId[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
         if($gagal >0)
			   Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST, $gagal . ' '.$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
         else
			   Messenger::Instance()->Send('komponen_gaji', 'detilGaji', 'view', 'html', array($this->_POST, $sukses . ' '.$this->msgDeleteSuccess2, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
}
?>
