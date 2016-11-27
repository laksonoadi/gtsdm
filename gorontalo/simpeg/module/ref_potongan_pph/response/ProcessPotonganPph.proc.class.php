<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_potongan_pph/business/PotonganPph.class.php';
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
		$this->Obj = new PotonganPph();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'potonganPph', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('ref_potongan_pph', 'inputPotonganPph', 'view', 'html');
		$this->idUser = Security::Instance()->mAuthentication->getcurrentuser()->GetUserId();
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
       			$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       			$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       			$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
			$this->msgOrderSuccess='Order setted successfully';$this->msgOrderFail='Set order failed';
       			$this->msgReqDataEmpty='All field marked with * and date field must be filled';
     		}else{
       			$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       			$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       			$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
			$this->msgOrderSuccess='Set urutan berhasil dilakukan';$this->msgOrderFail='Set urutan gagal dilakukan.';       			
			$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
     		}
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['pphrpNama']) == "" or trim($this->_POST['pphrpNominalMax'])== "") {
				return "empty";
			} else {
				return true;
			}
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();
		if($cek === true) {
			$addPotonganPph = $this->Obj->DoAddPotonganPph($this->_POST['pphrpNama'], $this->_POST['pphrpNominalMax'], $this->_POST['pphrpOrder'], $this->idUser);
			if ($addPotonganPph === true) {
				Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('ref_potongan_pph', 'inputPotonganPph', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput;
		}
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		if($cek === true) {
			$updatePotonganPph = $this->Obj->DoUpdatePotonganPph($this->_POST['pphrpNama'], $this->_POST['pphrpNominalMax'], $this->_POST['pphrpOrder'], $this->idUser, $this->decId);
			if ($updatePotonganPph === true) {
				Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('ref_potongan_pph', 'inputPotonganPph', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		}
		return $this->pageView;
	}

	function Delete() {
		$Id = $this->_POST['idDelete'];
		$delete = $this->Obj->DoDeletePotonganPphById($Id);
		if($delete === true) {
			Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST, $gagal . $this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
	
	function Order() {
	
			$IdPot = $_POST['idPotongan'];
			$order = $_POST['order'];
			for($i=0;$i<sizeof($IdPot);$i++) {
					$setOrder = $this->Obj->DoSetOrder($order[$i], $IdPot[$i]);
					}
					
		if($setOrder === true) {
			Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST,'Set Urutan Berhasil Dilakukan', $this->cssDone),Messenger::NextRequest);
		} else {
			Messenger::Instance()->Send('ref_potongan_pph', 'potonganPph', 'view', 'html', array($this->_POST, $gagal . ' Set Order Gagal.', $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
	}
}
?>
