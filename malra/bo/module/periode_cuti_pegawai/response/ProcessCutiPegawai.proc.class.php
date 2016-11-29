<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/periode_cuti_pegawai/business/periode_cuti.class.php';

class Process{

  var $_POST;
  var $Obj;
  var $user;
  var $pageView;
  var $pageInput;
  
  var $cssDone = "notebox-done";
  var $cssAlert = "notebox-alert";
  var $cssFail = "notebox-warning";
  
  var $return;
  var $decId;
  var $encId;
  
  function __construct() {
    $this->Obj = new PeriodeCuti;
    $this->_POST = $_REQUEST->AsArray();
    $this->decId = $_GET['id']->Integer()->Raw();
    $this->pegId = $_POST['pegId'];
    $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
    $this->encId = $this->decId;
    $this->awal = $this->POST['cutiperAwal_year'].'-'.$this->POST['cutiperAwal_mon'].'-'.$this->POST['cutiperAwal_day'];
    $this->akhir = $this->POST['cutiperAkhir_year'].'-'.$this->POST['cutiperAkhir_mon'].'-'.$this->POST['cutiperAkhir_day'];
    $this->pageView = Dispatcher::Instance()->GetUrl('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html') . '&pegId=' . $this->pegId;
    $this->pageBack = Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html') . '&dataId=' . $this->pegId;
    $this->pageInput = Dispatcher::Instance()->GetUrl('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html');
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
  
  function Check (){
    if (isset($this->_POST['btnbalik'])) return $this->pageView;
    if (trim($this->_POST['total']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->_POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html', $msg, Messenger::NextRequest);
    
      $return = $this->pageInput;
      if (isset($_GET['id'])){ 
         $return .= "&id=".$this->decId;
      }
      return $return;
    }
    return true;
  }
  
  function Update(){
    $check = $this->Check();
    if ($check !== true){ 
      return $check;
    }
  
    #$awal=$this->_POST['cutiperAwal_year'].'-'.$this->_POST['cutiperAwal_mon'].'-'.$this->_POST['cutiperAwal_day'];
    #$akhir=$this->_POST['cutiperAkhir_year'].'-'.$this->_POST['cutiperAkhir_mon'].'-'.$this->_POST['cutiperAkhir_day'];
    
    #$status = (isset($this->_POST['status'])) ? $this->_POST['status'] : 'Expired';
    #if(trim($status)=='')	  
	     #$is_aktif='Expired';
	$periodeCutiId = $this->_POST['periodeCutiId'];
	$totalcuti = $this->Obj->GetDataPeriodeCutiById($periodeCutiId);
	$totalsisa = $totalcuti[0]['totalall'] - $this->_POST['total'];		
    $data=array('pegId'=>$this->_POST['pegId'],'periodeCutiId'=>$periodeCutiId, 'totalcuti'=>$totalcuti[0]['totalall'],'total'=>$this->_POST['total'],'totalsisa'=>$totalsisa,'id'=>$this->encId);

    $result = $this->Obj->Update($data);
    if($result){
      $msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
    }else{
      $msg = array(1=>$this->msgUpdateFail, $this->cssFail);
    }
    Messenger::Instance()->Send('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html', $msg, Messenger::NextRequest);
    return $this->pageView;
  }
  
	function Add(){
		$check = $this->Check();
		#$awal=$this->_POST['cutiperAwal_year'].'-'.$this->_POST['cutiperAwal_mon'].'-'.$this->_POST['cutiperAwal_day'];
		#$akhir=$this->_POST['cutiperAkhir_year'].'-'.$this->_POST['cutiperAkhir_mon'].'-'.$this->_POST['cutiperAkhir_day'];
		
		if (!isset($this->_POST['status'])) {
			$this->_POST['status'] = 'Expired';
		}
		else {
			$this->_POST['status'] = 'Active';
		}
		
		if(trim($this->_POST['status']) == ''){  
			 $this->_POST['status'] = 'Expired';
		}
				
		if ($check !== true) return $check;
		
		$totalcuti = $this->Obj->GetDataPeriodeCutiById($this->_POST['periodeCutiId']);
		
		// Jika periode sudah pernah diambil, cari sisanya. Jika belum pernah, berarti sisanya masih penuh (total).
		$sisaCutiPeriode = $this->Obj->GetSisaCutiPeriode($this->_POST['pegId'], $this->_POST['periodeCutiId']);
		$sisaCutiPeriode = ($sisaCutiPeriode)? $sisaCutiPeriode['sisa'] : $totalcuti[0]['totalall'];

		// Cari sisa cuti sebelumnya (selain periode ini).
		$sisaCutiSebelumnya = $this->Obj->GetSisaCutiSebelumnya($this->_POST['pegId'], $this->_POST['periodeCutiId']);
		$sisaCutiSebelumnya = ($sisaCutiSebelumnya)? $sisaCutiSebelumnya['sisa'] : 0;
		
		// Jika total hari cuti yang ingin diambil melewati jatah cuti, add gagal.
		if($this->_POST['total'] > $sisaCutiPeriode + $sisaCutiSebelumnya){
		  	$msg = array(1 => $this->msgAddFail, $this->cssFail);
		}
		else{
			$totalsisa = $sisaCutiPeriode - $this->_POST['total'] + $sisaCutiSebelumnya;
			//print_r($totalcuti[0]['totalall']);die;
			$data = array(
				'pegId' 		=> $this->_POST['pegId'],
				'periodeCutiId' => $this->_POST['periodeCutiId'],
				'totalcuti' 	=> $totalcuti[0]['totalall'],
				'total' 		=> $this->_POST['total'],
				'totalsisa' 	=> $totalsisa
			);
			//print_r($data);exit();
			$result = $this->Obj->Add($data);
		
			if($result){
			  	$msg = array(1 => $this->msgAddSuccess, $this->cssDone);
			}
			else{
			  	$msg = array(1 => $this->msgAddFail, $this->cssFail);
			}
		}
		Messenger::Instance()->Send('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
  	}
  
  	function Delete(){
    	$result = $this->Obj->Delete($this->_POST);
    	if ($result){
      		$msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
	    }
    	else{
	      	$msg = array(1=>$this->msgDeleteFail, $this->cssFail);
    	}
    	Messenger::Instance()->Send('periode_cuti_pegawai', 'cutiPegawai', 'view', 'html', $msg, Messenger::NextRequest);
    	return $this->pageView;
  	}
}

?>
