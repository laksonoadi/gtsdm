<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/import/business/ImportData.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/import/response/reader.php';

class ProcessImportData {
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
    var $ext;


	function __construct() {
		$this->Obj = new ImportData();
		$this->_POST = $_POST->AsArray();
		$this->file_allowed = array("xls");
		$this->file= $_FILES['file_excel'];
		$this->uploadDir = GTFWConfiguration::GetValue('application', 'file_save_path');
		$this->templateDir = str_replace('upload_file/file/','',GTFWConfiguration::GetValue('application', 'file_save_path')).'/doc/';
		$this->pageView = Dispatcher::Instance()->GetUrl('import', 'importData', 'view', 'html', true);
	}

	function Check(){
		if ($this->file['error'] == 0) return $this->Upload();
		Messenger::Instance()->Send('import', 'importData', 'view', 'html', array(1=>'Upload file gagal!', $this->cssFail),Messenger::NextRequest);
		return false;
	}

	function Upload() {
		$filename =  $this->file['name'];
		if(move_uploaded_file($this->file['tmp_name'], $this->uploadDir . $filename)) {
			return true;
		}
		return false;
	}

	function ReadFile($mFile) {
		$dataFile = $mFile;
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');
		if ($data->read($dataFile)) return $data;
		else return false;
	}

	function SaveData($data,$jenis){
		$arrData = $data->sheets['0']['cells'];
		
		$template = $this->ReadFile($this->templateDir . 'contoh_format_import_'.$jenis.'.xls');
		$arrTemplate = $template->sheets['0']['cells'];
		
		if (($arrData[1]==$arrTemplate[1])&&($arrData[2]==$arrTemplate[2])&&($arrData[3]==$arrTemplate[3])){
			$data=$arrData;
			$this->Obj->SetFileLog($jenis);
			if ($jenis=='duk'){
				$result=$this->Obj->ImportDUK($data);
			}elseif ($jenis=='unit_kerja'){
				$result=$this->Obj->ImportRiwayatUnitKerja($data);
			}elseif ($jenis=='golongan'){
				$result=$this->Obj->ImportRiwayatGolongan($data);
			}elseif ($jenis=='jabfung'){
				$result=$this->Obj->ImportRiwayatJabatanFungsional($data);
			}elseif ($jenis=='jabstruk'){
				$result=$this->Obj->ImportRiwayatJabatanStruktural($data);
			}elseif ($jenis=='pendidikan'){
				$result=$this->Obj->ImportRiwayatPendidikan($data);
			}elseif ($jenis=='gapok'){
				$result=$this->Obj->ImportRiwayatGajiPokok($data);
			}
			$this->Obj->CloseFileLog($jenis,$result);
			$pesan=$result[0].' data dari '.$result[1].' data berhasil diimport!'.$result[2].'.<br><a href="'.$this->Obj->pathFileLog.'" target="_Blank">Klik Disini</a> untuk mendowload log import.';
			if (empty($result[2])){
				Messenger::Instance()->Send('import', 'importData', 'view', 'html', array(1=>$pesan, $this->cssDone),Messenger::NextRequest);
			}else{
				Messenger::Instance()->Send('import', 'importData', 'view', 'html', array(1=>$pesan, $this->cssFail),Messenger::NextRequest);
			}
		}else{
			$pesan='Format Excel yang anda upload salah. Silahkan download format excel yang sesuai terlebih dahulu.';
			Messenger::Instance()->Send('import', 'importData', 'view', 'html', array(1=>$pesan, $this->cssFail),Messenger::NextRequest);
		}
		
		
		return $this->pageView;
   }

	function Import() {
		if(isset($this->_POST['cancel'])){
			return $this->pageView;
		}else{
			if($this->Check()){
				return $this->ImportFromExcel();
			} else return $this->pageView;
		}
	}
   
	function ImportFromExcel (){
		$data = $this->ReadFile($this->uploadDir . $this->file['name']);
		$jenis = $_POST['jenis_data'];
		
		if ($data) return $this->SaveData($data,$jenis);
			Messenger::Instance()->Send('import', 'importData', 'view', 'html', array(1=>'Format file Excel yang diupload tidak dikenal!', $this->cssFail),Messenger::NextRequest);
		return $this->pageView;
   }
}
?>
