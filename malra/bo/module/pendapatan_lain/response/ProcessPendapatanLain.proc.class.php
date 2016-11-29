<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/business/pendapatan_lain.class.php';
class ProcessPendapatanLain{

	var $_POST;
	var $Obj;
	var $user;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;

	function __construct() {
		$this->Obj = new PendapatanLain();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_POST['dataId']);
		$this->decId2 = Dispatcher::Instance()->Decrypt($_POST['tglId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->encId2 = Dispatcher::Instance()->Encrypt($this->decId2);
		$this->pageView = Dispatcher::Instance()->GetUrl('pendapatan_lain', 'pendapatanLain', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('pendapatan_lain', 'inputPendapatanLain', 'view', 'html');
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
     }
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
		  if($this->_POST['op'] == "add"){
        if(trim($this->_POST['jenis']) == ""){
          return "empty";
        } elseif($this->_POST['tanggal_day'] == "0000" or $this->_POST['tanggal_mon'] == "00" or $this->_POST['tanggal_year'] == "00"){
          return "empty";
        } elseif(trim($this->_POST['deskripsi']) == ""){
        return "empty";
        }
      }
      
			return true;
			
		}
		return false;
	}

	function AddData() {
		$cek = $this->Check();
		if($cek === true) {
		  $arrPegawai = $this->getPOST();
			$arrPegawai = $arrPegawai['tambah'];
			$tgl = $this->_POST['tanggal_year'].'-'.$this->_POST['tanggal_mon'].'-'.$this->_POST['tanggal_day'];
			if($this->_POST['op']=="add"){
        $addPendapatanLain = $this->Obj->DoAddData($arrPegawai,$this->_POST['jenis'], $this->_POST['nominal'], $this->_POST['deskripsi'], $tgl, $this->decId, $this->decId2);
			}else{
        $addPendapatanLain = $this->Obj->DoUpdateData($arrPegawai, $this->_POST['jenis2'], $this->_POST['nominal'], $this->decId, $this->decId2);
			}
      if ($addPendapatanLain === true) {
        if($this->_POST['op']=="add"){
			    Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
        }else{
          Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
        }
      } else {
        if($this->_POST['op']=="add"){
				  Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			  }else{
          Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgUpdateFail.$this->decId.'-'.$this->decId2, $this->cssFail),Messenger::NextRequest);
			  }
      }
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('pendapatan_lain', 'inputPendapatanLain', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId. "&tglId=" . $this->encId2;
		}
		return $this->pageView;
	}

	function Delete() {
		$Id = $this->_POST['idDelete'];
		$Tgl = $this->_POST['tglDelete'];
		$delete = $this->Obj->Delete($Id,$Tgl);
		if($delete === true) {
			Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			Messenger::Instance()->Send('pendapatan_lain', 'pendapatanLain', 'view', 'html', array($this->_POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageView;
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
               $data['tambah'][$i]['nominal']=$data['tambah']['nominal'][$key];
               $i++;
            }
            unset($data['tambah']['id']);			
            unset($data['tambah']['kode']);			
            unset($data['tambah']['nama']);
            unset($data['tambah']['nominal']);
         }//end ifisset tambah
      }//end if isset post
	   
      return $data;
   }
}
?>
