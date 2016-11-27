<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppGajiPegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/Integrasi.class.php';
class ProcessGajiPegawai {

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
		$this->Obj = new AppGajiPegawai();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'inputGajiPegawai', 'view', 'html');
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='data can not be deleted';
       $this->msgReqDataEmpty='All field marked with * must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='data tidak dapat dihapus';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
     }
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['cash']) == "") $this->_POST['cash'] = "Tidak";
			if(trim($this->_POST['aktif']) == "") $this->_POST['aktif'] = "Tidak";
      if($this->_POST['tgl_gajian'] == "") {
				return "empty";
			} else {
				return true;
			}
		}
		return false;
	}

	function UpStatus() {
		$arrId = $_POST['id'];
		$arrPer = $_POST['periode'];
		$arrGaji = $_POST['idGaji'];
		
		$total_pegawai=0;
		$total_nominal=0;
		
		for($i=0;$i<sizeof($arrId);$i++) {
			$UpStatus = false;
			$UpStatus = $this->Obj->DoUpStatusData($arrId[$i],$arrPer[$i],$arrGaji[$i]);
			if($UpStatus === true) $sukses += 1;
			else $gagal += 1;
			
			$DetailGaji[$i]=$this->Obj->GetGajiPegawaiDetailById($arrGaji[$i]);
			$total_pegawai++;
			$total_nominal+=$DetailGaji[$i]['nominal'];
		}
		
		if ($UpStatus === true){
			//Ini Yang Integrasi Dengan gtfinansi terkait pembayaran
			$this->StatusIntegrasi=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtfinansi');
			if ($this->StatusIntegrasi){
				$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtfinansi');
				$Integrasi = new Integrasi($nomorkoneksi);
				$Integrasi->connect();
				$params=array(
							'catatan'=>'Pembayaran Gaji untuk '.$total_pegawai.' pegawai yang ditransaksikan pada '.date('Y-m-d'),
							'nilai'=>$total_nominal
						);
				$resultGaji=$Integrasi->InsertTransaksiGajiToFinansi($params);
				if ($resultGaji===true){
					for($i=0;$i<sizeof($DetailGaji);$i++) {
						$resultGajiDetail=$Integrasi->InsertTransaksiGajiDetailToFinansi($DetailGaji[$i]);
					}
				}
				$this->Obj->connect();
			}
			//==End Integrasi dengan gtfinansi
		}
		if ($UpStatus === true) {
			Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST, $gagal . ' '.$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
		}
    
		return $this->pageView;
	}

	function Update() {
		$cek = $this->Check();
		if($cek === true) {
		  $arrKomponen = $this->getPOST();
		  $arrKomponen = $arrKomponen['tambah'];
		  $x = $this->_POST;
		  //print_r($x);exit;
			$updateGajiPegawai = $this->Obj->DoUpdateData($this->_POST['cash'], $this->_POST['tgl_gajian'], $this->_POST['aktif'], $this->decId, $arrKomponen);
			if ($updateGajiPegawai === true) {
			  $id_cek = $this->Obj->getId($this->decId);
			  if($this->_POST['bank']==""){
          $this->_POST['bank']=NULL;
        }
			  if(empty($id_cek)){
          $updateGajiPegawai2 = $this->Obj->DoUpdateData2($this->_POST['rekening'], $this->_POST['bank'], $this->decId, $this->user);
        }else{
          $updateGajiPegawai2 = $this->Obj->DoUpdateData3($this->_POST['rekening'], $this->_POST['bank'], $this->decId, $this->user);
        }
				if ($updateGajiPegawai2 === true) {
          Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssDone),Messenger::NextRequest);
			  }else{
          Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgUpdateSuccess, $this->cssFail),Messenger::NextRequest);
        }
      } else {
				Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgUpdateFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('gaji_pegawai', 'inputGajiPegawai', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty),Messenger::NextRequest);
			return $this->pageInput . "&dataId=" . $this->encId;
		}
		return $this->pageView;
	}

	function Delete() {
		$arrId = $this->_POST['idDelete'];
		$arrPer = $this->_POST['perDelete'];
		$deleteArrData = $this->Obj->DoDeleteDataByArrayId($arrId,$arrPer);
		if($deleteArrData === true) {
			Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			//jika masuk disini, berarti PASTI ada salah satu atau lebih data yang gagal dihapus
			for($i=0;$i<sizeof($arrId);$i++) {
				$deleteData = false;
				$deleteData = $this->Obj->DoDeleteData($arrId[$i],$arrPer[$i]);
				if($deleteData === true) $sukses += 1;
				else $gagal += 1;
			}
			Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST, $gagal .' '.$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
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
               $i++;
            }
            unset($data['tambah']['id']);			
            unset($data['tambah']['kode']);			
            unset($data['tambah']['nama']);
         }//end ifisset tambah
      }//end if isset post
	   
      return $data;
   }
}
?>
