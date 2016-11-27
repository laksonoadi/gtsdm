<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/business/ImportAbsensiHarian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/response/reader.php';

class ProcessImportAbsensiHarian {

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
		$this->Obj = new ImportAbsensiHarian();
		$this->_POST = $_POST->AsArray();
		
		$this->file_allowed = array("csv,xls");
		$this->file= $_FILES['file'];
		$this->uploadDir = GTFWConfiguration::GetValue('application', 'file_save_path');
		
		$this->formatDir = GTFWConfiguration::GetValue('application', 'bo_path').'/doc/contoh_format_import_absensi.xls';
      
		$this->pageView = Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html', true);
		$this->pageInput = Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html', true);
	}

	function Check(){
		if ($this->_POST['sumber'] == 'sdm') return true;
      
		if ($this->file['error'] == 0) return $this->Upload();
		Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array(1=>'Upload file gagal!', $this->cssFail),Messenger::NextRequest);
		return false;
	}

	function Upload() {
		$filename =  str_replace(" ","",$this->file['name']);
		$this->file['name']=$filename;
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

	function SaveData($data){
	    $format = $this->ReadFile($this->formatDir);
		$arrFormat = $format->sheets['0']['cells'];
		$arrData = $data->sheets['0']['cells'];
		
		if ($arrFormat[1]==$arrData[1]){
			for ($i=2; $i<sizeof($arrData); $i++){
				$data=$arrData[$i];
				$kode=$data[8];
				$array=array(
						'namaPegawai'=>$data[2],
						'jamMasuk'=>$data[4],
						'jamKeluar'=>$data[5],
						'absenMasuk'=>$data[6],
						'absenKeluar'=>$data[7],
						'kodeAbsen'=>$data[1],
						'tanggal'=>$this->Obj->ConvertDate($data[3],'DD/MM/YYYY','YYYY-MM-DD')
					);
				
				$cek=$this->Obj->CheckAbsensiPegawai($array['kodeAbsen'],$array['tanggal']);
				if ($cek==false){
					$result=$this->Obj->AddAbsensiHarian($array);
					$result=$this->Obj->AnalisisAbsensiHarian($array['kodeAbsen'],$array['tanggal']);
				}else{
					$result=$this->Obj->UpdateAbsensiHarian($array);
					$result=$this->Obj->AnalisisAbsensiHarian($array['kodeAbsen'],$array['tanggal']);
				}
				
				if ($kode!=''){
					$array=array(
						'absensiKode'=>$kode,
						'kodeAbsen'=>$data[1],
						'tanggal'=>$this->Obj->ConvertDate($data[3],'DD/MM/YYYY','YYYY-MM-DD')
					);
					$result=$this->Obj->UpdateKodeAbsensiHarian($array);
				}
			}
			Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array(1=>'Import Data Berhasil!', $this->cssDone),Messenger::NextRequest);
		}else{
			Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array(1=>'Format File yang diupload tidak sesuai!', $this->cssFail),Messenger::NextRequest);
		}
      
		return $this->pageView;
	}
   
	function ImportFromExcel (){
		$this->Upload();
		$data = $this->ReadFile($this->uploadDir . $this->file['name']);
		if ($data) return $this->SaveData($data);
		Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array(1=>'Format file yang diupload tidak dikenal!', $this->cssFail),Messenger::NextRequest);
		return $this->pageInput;
	}
}
?>
