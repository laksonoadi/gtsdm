<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';

class Process
{
	var $POST;
	//var $FILES;
	var $user;
	var $Obj;
	var $cssAlert = "notebox-alert";
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";
	var $pageInput;
	var $decId;
	var $pageView;
	var $pageBack;
   
	function __construct($ret) {
		$this->Obj = new MutasiPak();
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->decId = $_POST['dataId']->Integer()->Raw();
		$this->profilId = $_POST['pegId']->Integer()->Raw();
		$this->delId = $_GET['id']->Integer()->Raw();
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
			$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
			$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
			$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
			$this->msgReqDataEmpty='All field marked with * and date field must be filled';
    		$this->error='Data incomplete!';
    		$this->activeStatus='Active';$this->inactiveStatus='Inactive';
		}else{
			$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
			$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
			$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
			$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
    		$this->error='Data tidak lengkap!';
    		$this->activeStatus='Aktif';$this->inactiveStatus='Tidak Aktif';
		}
      
		if($ret == "html"){
			$this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html').'&id='.$this->profilId;
			$this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html').'&id='.$this->profilId;
			$this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html').'&id='.$this->delId;
        }else{
			$this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html',true).'&id='.$this->profilId;
			$this->pageView = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html',true).'&id='.$this->profilId;
			$this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak','view','html').'&id='.$this->delId;
        }
	}
	
	function SetPost($param){
		$this->POST = $param->AsArray(); 
		//echo '<pre>'; print_r($this->POST); echo '</pre>';exit();
	}
  
	function Check (){
	    if (isset($this->POST['btnbatal'])) {
			return $this->pageView;
	    }
	    
	    if (isset($this->check_error)){
			$msg = array($this->POST, $this->error, $this->cssAlert);
			Messenger::Instance()->Send('mutasi_pak_kumulatif','MutasiPak','view','html', $msg, Messenger::NextRequest);
	      
			$return = $this->pageInput;
			if (isset($_GET['dataId'])){ 
				$return .= "&dataId=".$this->decId."&id=".$this->profilId;
			}
			return $return;
	    }
		return true;
	}
  
	function AddData(){
	    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
	    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
	    $tgl_penetapan=$this->POST['tgl_penetapan_year'].'-'.$this->POST['tgl_penetapan_mon'].'-'.$this->POST['tgl_penetapan_day'];
	    
	    $array=array(
					'pegId'=>$this->POST['pegId'],
					'no_pak'=>$this->POST['no_pak'],
					'tgl_penetapan'=>$tgl_penetapan,
					'pejabat'=>$this->POST['pejabat'],
					'mulai'=>$mulai,
					'selesai'=>$selesai,
					'diangkat'=>$this->POST['jabatan'],
					$this->user
					);
	    
	    $this->Obj->StartTrans();
	    $result = $this->Obj->Add($array);
	    if ($result===true){
	    	$getId=$this->Obj->GetMaxId();
	    	$data_kegiatan=$this->POST['data']['kegiatan'];
			for ($i=0; $i<sizeof($data_kegiatan['kegiatan']); $i++){
				$kegiatan=$data_kegiatan['kegiatan'][$i];
				$angka_kredit=$data_kegiatan['angka_kredit'][$i];
				$deskripsi=$data_kegiatan['deskripsi'][$i];
				$peran=$data_kegiatan['peran'][$i];
				$lokasi=$data_kegiatan['lokasi'][$i];
				$waktu=$data_kegiatan['waktu'][$i];
				$bukti=$data_kegiatan['bukti'][$i];
				$lampiran=$data_kegiatan['lampiran'][$i];
				$referensi=$data_kegiatan['referensi'][$i];
				$array_detail=array(
									$getId,
									$kegiatan,
									$angka_kredit,
									$deskripsi,
									$peran,
									$lokasi,
									$waktu,
									$bukti,
									$lampiran
									);
				$result = $this->Obj->AddUnsur($array_detail);
				if ($result) { $result = $this->Obj->AddUnsurRelasi(array($getId,$referensi)); }
				if (!$result) break;
			}
	    	  
	    }
	    $this->Obj->EndTrans($result);
	    
		if ($result){
			return $result;
	    }else{
			return false;
	    }
	}
	
	function UpdateData(){ 
		$mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
		$selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
		$tgl_penetapan=$this->POST['tgl_penetapan_year'].'-'.$this->POST['tgl_penetapan_mon'].'-'.$this->POST['tgl_penetapan_day'];
    
		$array=array(
				'pegId'=>$this->POST['pegId'],
				'no_pak'=>$this->POST['no_pak'],
				'tgl_penetapan'=>$tgl_penetapan,
				'pejabat'=>$this->POST['pejabat'],
				'mulai'=>$mulai,
				'selesai'=>$selesai,
				'diangkat'=>$this->POST['jabatan'],
				$this->user,
				'id'=>$this->POST['dataId']
				);
		
		$this->Obj->StartTrans();
		$result = $this->Obj->Update($array);
		if ($result===true){
			$getId=$this->POST['dataId'];
			$data_kegiatan=$this->POST['data']['kegiatan'];
			$result = $this->Obj->DeleteUnsur($getId);
			if ($result===true){
				for ($i=0; $i<sizeof($data_kegiatan['kegiatan']); $i++){
					$kegiatan=$data_kegiatan['kegiatan'][$i];
					$angka_kredit=$data_kegiatan['angka_kredit'][$i];
					$deskripsi=$data_kegiatan['deskripsi'][$i];
					$peran=$data_kegiatan['peran'][$i];
					$lokasi=$data_kegiatan['lokasi'][$i];
					$waktu=$data_kegiatan['waktu'][$i];
					$bukti=$data_kegiatan['bukti'][$i];
					$lampiran=$data_kegiatan['lampiran'][$i];
					$referensi=$data_kegiatan['referensi'][$i];
					$array_detail=array(
										$getId,
										$kegiatan,
										$angka_kredit,
										$deskripsi,
										$peran,
										$lokasi,
										$waktu,
										$bukti,
										$lampiran
										);
					$result = $this->Obj->AddUnsur($array_detail);
					if ($result) { $result = $this->Obj->AddUnsurRelasi(array($getId,$referensi)); }
					if (!$result) break;
				}
			}
		}    
		$this->Obj->EndTrans($result);
    
		if ($result){
			return $result;
		}else{
			return false;
		}
	}
	
	function InputData(){
		$check = $this->Check();
		if ($check !== true) return $check;
		if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
			$rs_add = $this->AddData();
			if($rs_add== true){
				Messenger::Instance()->Send('mutasi_pak_kumulatif','MutasiPak','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
				return $this->pageView;
			}else{
				Messenger::Instance()->Send('mutasi_pak_kumulatif','MutasiPak','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
				return $this->pageView;
			}
		}else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
			$rs_update = $this->UpdateData();
			if($rs_update== true){
				Messenger::Instance()->Send('mutasi_pak_kumulatif','MutasiPak','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
				return $this->pageView;
			}else{
				Messenger::Instance()->Send('mutasi_pak_kumulatif','MutasiPak','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
				return $this->pageView;
			}
		}
		return $urlRedirect;
	}
   
	function Delete(){
		//print_r($this->POST);exit();
		$this->Obj->StartTrans();
		$result = $this->Obj->DeleteUnsur($this->POST['idDelete']);
		if ($result) $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
		$this->Obj->EndTrans($deleteData);
		if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
		$return = $this->pageBack;
		//$return .= "&id=".$this->delId;
		return $return;
	}
   
	function dumper($print){
		echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>
