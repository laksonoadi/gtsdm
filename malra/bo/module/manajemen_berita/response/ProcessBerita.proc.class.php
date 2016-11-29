<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_berita/business/berita.class.php';

class Process
{
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
		$this->Obj			= new Berita;
		$this->_POST		= $_REQUEST->AsArray();
		$this->decId		= Dispatcher::Instance()->Decrypt($_GET['id']);
		$this->user			= Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId		= $this->decId;
		$this->pageView		= Dispatcher::Instance()->GetUrl('manajemen_berita', 'berita', 'view', 'html');
		$this->pageInput 	= Dispatcher::Instance()->GetUrl('manajemen_berita', 'berita', 'view', 'html').'&aksi=ya';
		$this->lang			= GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang		=='eng'){
			$this->msgAddSuccess	= 'Data added successfully';$this->msgAddFail='Data addition failed';
			$this->msgUpdateSuccess	= 'Data updated successfully';$this->msgUpdateFail='Data update failed';
			$this->msgDeleteSuccess	= 'Data deleted successfully';$this->msgDeleteFail='Data delete failed';
			$this->msgReqDataEmpty	= 'All field marked with * must be filled';
			$this->msgReqReduncData	= 'Nama Berita Yang Anda Inputkan Sudah Terdaftar';
		}else{
			$this->msgAddSuccess	= 'Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
			$this->msgUpdateSuccess	= 'Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
			$this->msgDeleteSuccess	= 'Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
			$this->msgReqDataEmpty	= 'Semua data bertanda * harus diisi';
			$this->msgReqReduncData	= 'Nama Berita Yang Anda Inputkan Sudah Terdaftar';
		}
	}


//	VALIDATION //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function Check ()
	{
		$publik = new Berita;

		if (isset($this->_POST['btnbalik'])) return $this->pageView;
		if (trim($this->_POST['nama']) == ''){
			$error = $this->msgReqDataEmpty;
		}

		$postKode	= $this->_POST['nama'];

		if (!isset($_GET['id'])){
			$result 	= $publik->GetKode($postKode);
			if($result['cekCode'] > 0){
				$error = $this->msgReqReduncData;
			}
		}

		// echo $error;
		// exit;
		if (isset($error))
		{
			$msg = array($this->_POST, $error, $this->cssAlert);
			Messenger::Instance()->Send('manajemen_berita', 'berita', 'view', 'html', $msg, Messenger::NextRequest);

			$return = $this->pageInput;
			if (isset($_GET['id'])){
				$return .= "&id=".$this->decId;
			}
			return $return;
		}
		return true;
	}

	
//	DO UPDATE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function Update()
	{
		$check 		= $this->Check();
		if ($check !== true) return $check;
		
		$sender = $_SESSION['username'];
		$date = $_POST['tanggal_berita_year'].'-'.$_POST['tanggal_berita_mon'].'-'.$_POST['tanggal_berita_day'];
		$status = $_POST['status'] == '1' ? '1' : '0';

		$fileSend = $_FILES['foto'];
		if(!empty($fileSend['name'])){
			$buffExplodedName = explode('.',$fileSend['name']);
			$extensions = array_pop($buffExplodedName);
			$fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
		}else{
			$fileName=$this->_POST['foto_fisik'];
		}
	  
		$data	= array(
					'title'		=> $this->_POST['nama'],
					'article'	=> $this->_POST['article'],
					'url'		=> $this->_POST['url'],
					'filename'	=> $fileName,
					'caption'	=> $this->_POST['caption'],
					'status'	=> $status,
					'sender'	=> $sender,
					'date'		=> $date,
					'id'		=> $this->_POST['idBerita']
				);
		
		$result = $this->Obj->Update($data);
		if ($result){
			$fileDir = GTFWConfiguration::GetValue('application', 'file_berita');
			if (!empty($fileSend['tmp_name'])){
				move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName);
			}
			$msg = array(1=>$this->msgUpdateSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgUpdateFail, $this->cssFail);
		}
		Messenger::Instance()->Send('manajemen_berita', 'berita', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}

	
//	DO ADD ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function Add()
	{
		$check		= $this->Check();
		if ($check !== true) return $check;
		
		$sender = $_SESSION['username'];
		$date = $_POST['tanggal_berita_year'].'-'.$_POST['tanggal_berita_mon'].'-'.$_POST['tanggal_berita_day'];
		$status = $_POST['status'] == '1' ? '1' : '0';

		$fileSend = $_FILES['foto'];
		if(!empty($fileSend['name'])){
			$buffExplodedName = explode('.',$fileSend['name']);
			$extensions = array_pop($buffExplodedName);
			$fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
		}else{
			$fileName="";
		}
		
		$data	= array(
					'title'		=> $this->_POST['nama'],
					'article'	=> $this->_POST['article'],
					'url'		=> $this->_POST['url'],
					'filename'	=> $fileName,
					'caption'	=> $this->_POST['caption'],
					'status'	=> $status,
					'sender'	=> $sender,
					'date'		=> $date
				);

		$result		= $this->Obj->Add($data);
		if ($result){
			$fileDir = GTFWConfiguration::GetValue('application', 'file_berita');
			if (!empty($fileSend['tmp_name'])){
				move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName);
			}
			$msg = array(1=>$this->msgAddSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgAddFail, $this->cssFail);
		}
		Messenger::Instance()->Send('manajemen_berita', 'berita', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}

	
//	DO DELETE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	function Delete()
	{
		// print_r($this->_POST);exit();
		$result = $this->Obj->Delete($this->_POST);
		if ($result){
			$msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
		}else{
			$msg = array(1=>$this->msgDeleteFail, $this->cssFail);
		}
		Messenger::Instance()->Send('manajemen_berita', 'berita', 'view', 'html', $msg, Messenger::NextRequest);
		return $this->pageView;
	}
	

//	>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function gSamViewArr($param) {
		echo"<pre>";print_r($param);echo"</pre>";exit;
	}
	  
	function gSamViewEcho($param) {
		echo"<pre>";echo$param.'<br/>';exit;
	}
	
}

?>