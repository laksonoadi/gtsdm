<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_bkd/business/mutasi_bkd.class.php';

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
   var $pageBatal;
   
   function __construct($ret) {
      $this->Obj = new MutasiBkd();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataId']->Integer()->Raw();
      $this->profilId = $_POST['pegId']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();

      $this->idpegbkd	= $_GET['idpegbkd']->Integer()->Raw();
      $this->idbkd		= $_GET['idbkd']->Integer()->Raw();
	  $this->paramBkd 	= $_GET['paramDelete'];

      $this->idpegbkd = $_POST['idpegbkd'];
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

      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->delId;
         $this->pageBatal = Dispatcher::Instance()->GetUrl('mutasi_bkd','pegawai','view','html');
         $this->pageEndDelete = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->idpegbkd.'&dataId='.$this->idbkd.'&tabActive='.$this->paramBkd;		 
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html',true).'&id='.$this->profilId;
         $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html',true).'&id='.$this->profilId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->delId;
         $this->pageBatal = Dispatcher::Instance()->GetUrl('mutasi_bkd','pegawai','view','html');
         $this->pageEndDelete = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$this->idpegbkd.'&dataId='.$this->idbkd.'&tabActive='.$this->paramBkd;		 
        }
      // echo $this->pageBack;exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbalik'])) return $this->pageBatal;
	// echo $this->POST['pegId']; exit;
	
    if (trim($this->POST['pegId']) == ''){
		$error = $this->msgReqDataEmpty;
    }
    
	if (isset($error))
	{
		$msg = array($this->_POST, $error, $this->cssAlert);
		Messenger::Instance()->Send('rekomendasi', 'rekomendasi', 'view', 'html', $msg, Messenger::NextRequest);

		$return = $this->pageInput;
		if (isset($_GET['id'])){
		$return .= "&id=".$this->delId;
	}
		return $return;
	}
    return true;
  }
  
	function AddData($nama_file){

	}
	
	function UpdateData(){
		$check = $this->Check();
		if ($check !== true) return $check;

		if($this->POST['btnsimpan']){
			// echo "TEST UPDATE DATA";
			// echo sizeof($this->POST['data']['pddk']['namakegPddk']);
			// echo $this->profilId.' - '.$this->decId;
			// exit;
			
			
		// FORM DOSEN START ---------------------------------------------------------------------------------------------------------------------------
			$data=array(
					'nmPt'		=> $this->POST['nmPt'],
					'almtpt'	=> $this->POST['almtpt'],
					'nohp'		=> $this->POST['nohp'],
					'jenis'		=> $this->POST['jenis'],
					'thnakd'	=> $this->POST['thnAkd'],
					'semester'	=> $this->POST['semester'],
					'asesor1'	=> $this->POST['asesor1'],
					'asesor2'	=> $this->POST['asesor2'],
					'bkdid'		=> $this->decId,
					'id'		=> $this->POST['pegId']
					);
			// print_r($data);
			// exit;

			$result = $this->Obj->UpdateDosen($data);
		// FORM DOSEN END ----------------------------------------------------------------------------------------------------------------------------
			
		// FORM PENDIDIKAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['pddk']['namakegPddk']); $i++)
			{
				if (!empty($_FILES['filePddk']['tmp_name'][$i])){
						$nama_filePddk = rand(0,10000).trim($_FILES['filePddk']['name'][$i]);
				}else{
						$nama_filePddk = "-";
				}

				$namakegPddk	= $this->POST['data']['pddk']['namakegPddk'][$i];
				$bpenugasanPddk	= $this->POST['data']['pddk']['bpenugasanPddk'][$i];
				$sks1Pddk		= $this->POST['data']['pddk']['sks1Pddk'][$i];
				$mpPddk			= $this->POST['data']['pddk']['mpPddk'][$i];
				$bdokPddk		= $this->POST['data']['pddk']['bdokPddk'][$i];
				$sks2Pddk		= $this->POST['data']['pddk']['sks2Pddk'][$i];

				
				$array1=array(
					'idBkdPddk'			=> $this->decId,
					'namakegPddk'		=> $namakegPddk,
					'bpenugasanPddk'	=> $bpenugasanPddk,
					'sks1Pddk'			=> $sks1Pddk,
					'mpPddk'			=> $mpPddk,
					'bdokPddk'			=> $bdokPddk,
					'sks2Pddk'			=> $sks2Pddk,
					'rekomen'			=> '-1',
					'filePddk'			=> $nama_filePddk
				);

				// print_r($array1);
				$result = $this->Obj->AddPendidikan($array1);
				if($result == true){
					if (!empty($_FILES['filePddk']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePddk']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePddk);
					}
				}
			}
		// FORM PENDIDIKAN END ---------------------------------------------------------------------------------------------------------------------

		// FORM PENELITIAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['penlt']['namakegPenlt']); $i++)
			{
				if (!empty($_FILES['filePenlt']['tmp_name'][$i])){
						$nama_filePenlt = rand(0,10000).trim($_FILES['filePenlt']['name'][$i]);
				}else{
						$nama_filePenlt = "-";
				}

				$namakegPenlt	= $this->POST['data']['penlt']['namakegPenlt'][$i];
				$bpenugasanPenlt= $this->POST['data']['penlt']['bpenugasanPenlt'][$i];
				$sks1Penlt		= $this->POST['data']['penlt']['sks1Penlt'][$i];
				$mpPenlt		= $this->POST['data']['penlt']['mpPenlt'][$i];
				$bdokPenlt		= $this->POST['data']['penlt']['bdokPenlt'][$i];
				$sks2Penlt		= $this->POST['data']['penlt']['sks2Penlt'][$i];

				
				$array2=array(
					'idBkdPenlt'		=> $this->decId,
					'namakegPenlt'		=> $namakegPenlt,
					'bpenugasanPenlt'	=> $bpenugasanPenlt,
					'sks1Penlt'			=> $sks1Penlt,
					'mpPenlt'			=> $mpPenlt,
					'bdokPenlt'			=> $bdokPenlt,
					'sks2Penlt'			=> $sks2Penlt,
					'rekomen'			=> '-1',
					'filePenlt'			=> $nama_filePenlt
				);

				// print_r($array2);
				$result = $this->Obj->AddPenelitian($array2);
				if($result == true){
					if (!empty($_FILES['filePenlt']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePenlt']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenlt);
					}
				}
			}
		// FORM PENELITIAN END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PENGABDIAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['pengbd']['namakegPengbd']); $i++)
			{
				if (!empty($_FILES['filePengbd']['tmp_name'][$i])){
						$nama_filePengbd = rand(0,10000).trim($_FILES['filePengbd']['name'][$i]);
				}else{
						$nama_filePengbd = "-";
				}

				$namakegPengbd		= $this->POST['data']['pengbd']['namakegPengbd'][$i];
				$bpenugasanPengbd	= $this->POST['data']['pengbd']['bpenugasanPengbd'][$i];
				$sks1Pengbd			= $this->POST['data']['pengbd']['sks1Pengbd'][$i];
				$mpPengbd			= $this->POST['data']['pengbd']['mpPengbd'][$i];
				$bdokPengbd			= $this->POST['data']['pengbd']['bdokPengbd'][$i];
				$sks2Pengbd			= $this->POST['data']['pengbd']['sks2Pengbd'][$i];

				
				$array3=array(
					'idBkdPengbd'		=> $this->decId,
					'namakegPengbd'		=> $namakegPengbd,
					'bpenugasanPengbd'	=> $bpenugasanPengbd,
					'sks1Pengbd'		=> $sks1Pengbd,
					'mpPengbd'			=> $mpPengbd,
					'bdokPengbd'		=> $bdokPengbd,
					'sks2Pengbd'		=> $sks2Pengbd,
					'rekomen'			=> '-1',
					'filePengbd'		=> $nama_filePengbd
				);

				// print_r($array3);
				$result = $this->Obj->AddPengabdian($array3);
				if($result == true){
					if (!empty($_FILES['filePengbd']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePengbd']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePengbd);
					}
				}
			}
		// FORM PENGABDIAN END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PENUNJANG START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['penunj']['namakegPenunj']); $i++)
			{
				if (!empty($_FILES['filePenunj']['tmp_name'][$i])){
						$nama_filePenunj = rand(0,10000).trim($_FILES['filePenunj']['name'][$i]);
				}else{
						$nama_filePenunj = "-";
				}

				$namakegPenunj		= $this->POST['data']['penunj']['namakegPenunj'][$i];
				$bpenugasanPenunj	= $this->POST['data']['penunj']['bpenugasanPenunj'][$i];
				$sks1Penunj			= $this->POST['data']['penunj']['sks1Penunj'][$i];
				$mpPenunj			= $this->POST['data']['penunj']['mpPenunj'][$i];
				$bdokPenunj			= $this->POST['data']['penunj']['bdokPenunj'][$i];
				$sks2Penunj			= $this->POST['data']['penunj']['sks2Penunj'][$i];

				
				$array4=array(
					'idBkdPenunj'		=> $this->decId,
					'namakegPenunj'		=> $namakegPenunj,
					'bpenugasanPenunj'	=> $bpenugasanPenunj,
					'sks1Penunj'		=> $sks1Penunj,
					'mpPenunj'			=> $mpPenunj,
					'bdokPenunj'		=> $bdokPenunj,
					'sks2Penunj'		=> $sks2Penunj,
					'rekomen'			=> '-1',
					'filePenunj'		=> $nama_filePenunj
				);

				// print_r($array4);
				$result = $this->Obj->AddPenunjang($array4);
				if($result == true){
					if (!empty($_FILES['filePenunj']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePenunj']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenunj);
					}
				}
			}
		// FORM PENUNJANG END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PROFESOR START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['prof']['namakegProf']); $i++)
			{
				if (!empty($_FILES['fileProf']['tmp_name'][$i])){
						$nama_fileProf = rand(0,10000).trim($_FILES['fileProf']['name'][$i]);
				}else{
						$nama_fileProf = "-";
				}

				$namakegProf	= $this->POST['data']['prof']['namakegProf'][$i];
				$bpenugasanProf	= $this->POST['data']['prof']['bpenugasanProf'][$i];
				$sks1Prof		= $this->POST['data']['prof']['sks1Prof'][$i];
				$mpProf			= $this->POST['data']['prof']['mpProf'][$i];
				$bdokProf		= $this->POST['data']['prof']['bdokProf'][$i];
				$sks2Prof		= $this->POST['data']['prof']['sks2Prof'][$i];

				
				$array5=array(
					'idBkdProf'		=> $this->decId,
					'namakegProf'	=> $namakegProf,
					'bpenugasanProf'=> $bpenugasanProf,
					'sks1Prof'		=> $sks1Prof,
					'mpProf'		=> $mpProf,
					'bdokProf'		=> $bdokProf,
					'sks2Prof'		=> $sks2Prof,
					'rekomen'		=> '-1',
					'fileProf'		=> $nama_fileProf
				);

				// print_r($array5);
				$result = $this->Obj->AddProfesor($array5);
				if($result == true){
					if (!empty($_FILES['fileProf']['tmp_name'][$i])){
						move_uploaded_file($_FILES['fileProf']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_fileProf);
					}
				}
			}
		// FORM PROFESOR END ---------------------------------------------------------------------------------------------------------------------

		
			if ($result){
				$msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
			}else{
				$msg = array(1=>$this->msgUpdateFail, $this->cssFail);
			}
			Messenger::Instance()->Send('mutasi_bkd', 'MutasiBkd', 'view', 'html', $msg, Messenger::NextRequest);
			return $this->pageView;
		}

	}
	
	
	
	function InputData(){
		$check = $this->Check();
		if ($check !== true) return $check;

		if($this->POST['btnsimpan']){
		// FORM DOSEN START ---------------------------------------------------------------------------------------------------------------------------
		$data=array(
					'id'		=> $this->POST['pegId'],
					'nosertf'	=> $this->POST['nosertf'],
					'nama'		=> $this->POST['nama'],
					'nip'		=> $this->POST['nip'],
					'nidn'		=> $this->POST['nidn'],
					'nmPt'		=> $this->POST['nmPt'],
					'almtpt'	=> $this->POST['almtpt'],
					'fakultas'	=> $this->POST['fakultas'],
					'prodiid'	=> $this->POST['prodiid'],
					'bidang'	=> $this->POST['bidang'],
					'nohp'		=> $this->POST['nohp'],
					'jabfungid'	=> $this->POST['jabfungid'],
					'pktgolid'	=> $this->POST['pktgolid'],
					's1'		=> $this->POST['s1'],
					's2'		=> $this->POST['s2'],
					's3'		=> $this->POST['s3'],
					'jenis'		=> $this->POST['jenis'],
					'thnakd'	=> $this->POST['thnAkd'],
					'semester'	=> $this->POST['semester'],
					'asesor1'	=> $this->POST['asesor1'],
					'asesor2'	=> $this->POST['asesor2']
					);
			// print_r($data);

			$result = $this->Obj->AddDosen($data);
			$getId	= $this->Obj->GetMaxId();
		// FORM DOSEN END ----------------------------------------------------------------------------------------------------------------------------

		// FORM PENDIDIKAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['pddk']['namakegPddk']); $i++)
			{
				// echo $_FILES['filePddk']['tmp_name'][$i]."<br/>";

				if (!empty($_FILES['filePddk']['tmp_name'][$i])){
						$nama_filePddk = rand(0,10000).trim($_FILES['filePddk']['name'][$i]);
				}else{
						$nama_filePddk = "-";
				}
				// echo $nama_filePddk."<br />";

				$namakegPddk	= $this->POST['data']['pddk']['namakegPddk'][$i];
				$bpenugasanPddk	= $this->POST['data']['pddk']['bpenugasanPddk'][$i];
				$sks1Pddk		= $this->POST['data']['pddk']['sks1Pddk'][$i];
				$mpPddk			= $this->POST['data']['pddk']['mpPddk'][$i];
				$bdokPddk		= $this->POST['data']['pddk']['bdokPddk'][$i];
				$sks2Pddk		= $this->POST['data']['pddk']['sks2Pddk'][$i];
				
				$array1=array(
					'idBkdPddk'			=> $getId[0]['MAXID'],
					'namakegPddk'		=> $namakegPddk,
					'bpenugasanPddk'	=> $bpenugasanPddk,
					'sks1Pddk'			=> $sks1Pddk,
					'mpPddk'			=> $mpPddk,
					'bdokPddk'			=> $bdokPddk,
					'sks2Pddk'			=> $sks2Pddk,
					'rekomen'			=> '-1',
					'filePddk'			=> $nama_filePddk
				);

				// print_r($array1);
				$result = $this->Obj->AddPendidikan($array1);
				if($result == true){
					if (!empty($_FILES['filePddk']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePddk']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePddk);
					}
				}
				
			}				
				// exit;

		// FORM PENDIDIKAN END ---------------------------------------------------------------------------------------------------------------------


		// FORM PENELITIAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['penlt']['namakegPenlt']); $i++)
			{
				if (!empty($_FILES['filePenlt']['tmp_name'][$i])){
						$nama_filePenlt = rand(0,10000).trim($_FILES['filePenlt']['name'][$i]);
				}else{
						$nama_filePenlt = "-";
				}

				$namakegPenlt	= $this->POST['data']['penlt']['namakegPenlt'][$i];
				$bpenugasanPenlt= $this->POST['data']['penlt']['bpenugasanPenlt'][$i];
				$sks1Penlt		= $this->POST['data']['penlt']['sks1Penlt'][$i];
				$mpPenlt		= $this->POST['data']['penlt']['mpPenlt'][$i];
				$bdokPenlt		= $this->POST['data']['penlt']['bdokPenlt'][$i];
				$sks2Penlt		= $this->POST['data']['penlt']['sks2Penlt'][$i];

				
				$array2=array(
					'idBkdPenlt'		=> $getId[0]['MAXID'],
					'namakegPenlt'		=> $namakegPenlt,
					'bpenugasanPenlt'	=> $bpenugasanPenlt,
					'sks1Penlt'			=> $sks1Penlt,
					'mpPenlt'			=> $mpPenlt,
					'bdokPenlt'			=> $bdokPenlt,
					'sks2Penlt'			=> $sks2Penlt,
					'rekomen'			=> '-1',
					'filePenlt'			=> $nama_filePenlt
				);

				// print_r($array2);
				$result = $this->Obj->AddPenelitian($array2);
				if($result == true){
					if (!empty($_FILES['filePenlt']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePenlt']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenlt);
					}
				}
			}
		// FORM PENELITIAN END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PENGABDIAN START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['pengbd']['namakegPengbd']); $i++)
			{
				if (!empty($_FILES['filePengbd']['tmp_name'][$i])){
						$nama_filePengbd = rand(0,10000).trim($_FILES['filePengbd']['name'][$i]);
				}else{
						$nama_filePengbd = "-";
				}

				$namakegPengbd		= $this->POST['data']['pengbd']['namakegPengbd'][$i];
				$bpenugasanPengbd	= $this->POST['data']['pengbd']['bpenugasanPengbd'][$i];
				$sks1Pengbd			= $this->POST['data']['pengbd']['sks1Pengbd'][$i];
				$mpPengbd			= $this->POST['data']['pengbd']['mpPengbd'][$i];
				$bdokPengbd			= $this->POST['data']['pengbd']['bdokPengbd'][$i];
				$sks2Pengbd			= $this->POST['data']['pengbd']['sks2Pengbd'][$i];

				
				$array3=array(
					'idBkdPengbd'		=> $getId[0]['MAXID'],
					'namakegPengbd'		=> $namakegPengbd,
					'bpenugasanPengbd'	=> $bpenugasanPengbd,
					'sks1Pengbd'		=> $sks1Pengbd,
					'mpPengbd'			=> $mpPengbd,
					'bdokPengbd'		=> $bdokPengbd,
					'sks2Pengbd'		=> $sks2Pengbd,
					'rekomen'			=> '-1',
					'filePengbd'		=> $nama_filePengbd
				);

				// print_r($array3);
				$result = $this->Obj->AddPengabdian($array3);
				if($result == true){
					if (!empty($_FILES['filePengbd']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePengbd']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePengbd);
					}
				}
			}
		// FORM PENGABDIAN END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PENUNJANG START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['penunj']['namakegPenunj']); $i++)
			{
				if (!empty($_FILES['filePenunj']['tmp_name'][$i])){
						$nama_filePenunj = rand(0,10000).trim($_FILES['filePenunj']['name'][$i]);
				}else{
						$nama_filePenunj = "-";
				}

				$namakegPenunj		= $this->POST['data']['penunj']['namakegPenunj'][$i];
				$bpenugasanPenunj	= $this->POST['data']['penunj']['bpenugasanPenunj'][$i];
				$sks1Penunj			= $this->POST['data']['penunj']['sks1Penunj'][$i];
				$mpPenunj			= $this->POST['data']['penunj']['mpPenunj'][$i];
				$bdokPenunj			= $this->POST['data']['penunj']['bdokPenunj'][$i];
				$sks2Penunj			= $this->POST['data']['penunj']['sks2Penunj'][$i];

				
				$array4=array(
					'idBkdPenunj'		=> $getId[0]['MAXID'],
					'namakegPenunj'		=> $namakegPenunj,
					'bpenugasanPenunj'	=> $bpenugasanPenunj,
					'sks1Penunj'		=> $sks1Penunj,
					'mpPenunj'			=> $mpPenunj,
					'bdokPenunj'		=> $bdokPenunj,
					'sks2Penunj'		=> $sks2Penunj,
					'rekomen'			=> '-1',
					'filePenunj'		=> $nama_filePenunj
				);

				// print_r($array4);
				$result = $this->Obj->AddPenunjang($array4);
				if($result == true){
					if (!empty($_FILES['filePenunj']['tmp_name'][$i])){
						move_uploaded_file($_FILES['filePenunj']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenunj);
					}
				}
			}
		// FORM PENUNJANG END ---------------------------------------------------------------------------------------------------------------------
			
			
		// FORM PROFESOR START ---------------------------------------------------------------------------------------------------------------------
			for ($i=0; $i<sizeof($this->POST['data']['prof']['namakegProf']); $i++)
			{
				if (!empty($_FILES['fileProf']['tmp_name'][$i])){
						$nama_fileProf = rand(0,10000).trim($_FILES['fileProf']['name'][$i]);
				}else{
						$nama_fileProf = "-";
				}

				$namakegProf	= $this->POST['data']['prof']['namakegProf'][$i];
				$bpenugasanProf	= $this->POST['data']['prof']['bpenugasanProf'][$i];
				$sks1Prof		= $this->POST['data']['prof']['sks1Prof'][$i];
				$mpProf			= $this->POST['data']['prof']['mpProf'][$i];
				$bdokProf		= $this->POST['data']['prof']['bdokProf'][$i];
				$sks2Prof		= $this->POST['data']['prof']['sks2Prof'][$i];

				
				$array5=array(
					'idBkdProf'		=> $getId[0]['MAXID'],
					'namakegProf'	=> $namakegProf,
					'bpenugasanProf'=> $bpenugasanProf,
					'sks1Prof'		=> $sks1Prof,
					'mpProf'		=> $mpProf,
					'bdokProf'		=> $bdokProf,
					'sks2Prof'		=> $sks2Prof,
					'rekomen'		=> '-1',
					'fileProf'		=> $nama_fileProf
				);

				// print_r($array5);
				$result = $this->Obj->AddProfesor($array5);
				if($result == true){
					if (!empty($_FILES['fileProf']['tmp_name'][$i])){
						move_uploaded_file($_FILES['fileProf']['tmp_name'][$i], GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_fileProf);
					}
				}
			}
		// FORM PROFESOR END ---------------------------------------------------------------------------------------------------------------------
			
			if ($result){
				$msg = array(1=>$this->msgAddSuccess, $this->cssDone);
			}else{
				$msg = array(1=>$this->msgAddFail, $this->cssFail);
			}
			Messenger::Instance()->Send('mutasi_bkd', 'MutasiBkd', 'view', 'html', $msg, Messenger::NextRequest);
			return $this->pageView;
		}

		
   }
   
   function Delete(){
		$paramBkd	= $_GET['paramDelete'];
		$idDelete	= $_GET['idDelete'];
		// print_r($this->POST);
		// echo $_GET['idDelete'];
		// echo $paramBkd;
		// echo $this->pageEndDelete;
		// exit();
		if($paramBkd == "pendidikan"){
			$deleteData = $this->Obj->DeletePendidikan($idDelete);
			if($deleteData == true){
				$cekPdddk		= $this->Obj->GetNmFilePendidikan($idDelete);
				$nama_filePddk	= $cekPdddk[0]['nmfile'];
				@unlink(GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePddk);
			}
			$headerLocate		= $this->pageEndDelete;

		}else if($paramBkd == "penelitian"){
			$deleteData 	= $this->Obj->DeletePenelitian($idDelete);
			if($deleteData == true){
				$cekPenelt		= $this->Obj->GetNmFilePenelitian($idDelete);
				$nama_filePenelt= $cekPenelt[0]['nmfile'];
				@unlink(GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenelt);
			}
			$headerLocate	= $this->pageEndDelete;

		}else if($paramBkd == "pengabdian"){
			$deleteData 	= $this->Obj->DeletePengabdian($idDelete); 
			if($deleteData == true){
				$cekPengbd		= $this->Obj->GetNmFilePengabdian($idDelete);
				$nama_filePengbd= $cekPengbd[0]['nmfile'];
				@unlink(GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePengbd);
			}
			$headerLocate	= $this->pageEndDelete;

		}else if($paramBkd == "penunjang"){
			$deleteData 	= $this->Obj->DeletePenunjang($idDelete); 
			if($deleteData == true){
				$cekPenunj		= $this->Obj->GetNmFilePenunjang($idDelete);
				$nama_filePenunj= $cekPenunj[0]['nmfile'];
				@unlink(GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_filePenunj);
			}
			$headerLocate	= $this->pageEndDelete;

		}else if($paramBkd == "profesor"){
			$deleteData 	= $this->Obj->DeleteProfesor($idDelete); 
			if($deleteData == true){
				$cekProf		= $this->Obj->GetNmFileProfesor($idDelete);
				$nama_fileProf	= $cekProf[0]['nmfile'];
				@unlink(GTFWConfiguration::GetValue( 'application', 'bo_save_path').$nama_fileProf);
			}
			$headerLocate	= $this->pageEndDelete;

		}else{
			$deleteData 	= $this->Obj->Delete($this->POST['idDelete']);
			$headerLocate	= $this->pageBack;
		}

		if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_bkd', 'MutasiBkd', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_bkd', 'MutasiBkd', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}

		$return = $headerLocate;
		return $return;
   }   

   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>
