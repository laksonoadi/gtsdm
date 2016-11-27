<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_komp_pph/business/KompPph.class.php';
class ProcessPph {

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
		$this->Obj = new KompPph();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('ref_komp_pph', 'kompPph', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('ref_komp_pph', 'inputKompPph', 'view', 'html');
	}

	function Check() {
	$codeGaji = $this->Obj->GetCodeGaji($this->_POST['pph_kode']);
	$codePph = $this->Obj->GetCodePph($this->_POST['pph_kode']);
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['pph_kode']) == "" or trim($this->_POST['pph_nama'])== "" or trim($this->_POST['pph_keterangan'])== "") {
				return "empty";
			} else if($codeGaji[0]['total']>0 and $codeGaji[0]['code_gaji_id']!=$this->decId) {
			    return "samaCodeGaji";
			} else if($codePph[0]['total']>0 and $codePph[0]['code_pph_id']!=$this->decId) {
				return "samaCodePph";
			} else return true;
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();
	  
		if($cek === true) {
			$addKompPph = $this->Obj->DoAddKompPph($this->_POST['pph_kode'], $this->_POST['pph_nama'], $this->_POST['pph_keterangan']);
			if ($addKompPph === true) {
				Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "samaCodeGaji") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,'Code sudah ada dalam code Gaji'),Messenger::NextRequest);
			return $this->pageInput;
		} elseif($cek == "samaCodePph") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,'Code sudah ada dalam code Pph'),Messenger::NextRequest);
			return $this->pageInput;
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput;
		}
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		
		if($cek === true) {
			$updateKompPph = $this->Obj->DoUpdateKompPph($this->_POST['pph_kode'], $this->_POST['pph_nama'], $this->_POST['pph_keterangan'], $this->decId);
			if ($updateKompPph === true) {
				Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "samaCodeGaji") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,'Code sudah ada dalam code Gaji'),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		} elseif($cekCodeGaji == "samaCodeGaji") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,'Code sudah ada dalam code Gaji'),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('ref_komp_pph', 'inputKompPph', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		}
		return $this->pageView;
	}

	function Delete() {
		$arrId = $this->_POST['idDelete'];
		//print_r($this->_POST);
		$deleteArrData = $this->Obj->DoDeleteKompPphByArrayId($arrId);
		if($deleteArrData === true) {
			Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arrId);$i++) {
				$deleteData = false;
				$deleteData = $this->Obj->DoDeleteKompPphById($arrId[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
			Messenger::Instance()->Send('ref_komp_pph', 'kompPph', 'view', 'html', array($this->_POST, $gagal . $this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
}
?>
