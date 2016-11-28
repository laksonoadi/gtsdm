<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/ImportGajiPegawai.class.php';

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/response/reader.php';

class ProcessImportGajiPegawai {

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
		$this->Obj = new ImportGajiPegawai();
		$this->_POST = $_POST->AsArray();
		
      $this->file_allowed = array("xls");
      $this->file= $_FILES['file_excel'];
      $this->uploadDir = GTFWConfiguration::GetValue('application', 'docroot') . "excel/";
      
      $this->pageView = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html', true);
	}

   function Check(){
      if ($this->_POST['sumber'] == 'sdm') return true;
      
      if ($this->file['error'] == 0) return $this->Upload();
      Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array(1=>'Upload file gagal!', $this->cssFail),Messenger::NextRequest);
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

   function SaveData($data){
      $arrData = $data->sheets['0']['cells'];
      $this->Obj->CreateKomponenGajiId($arrData[1]);
      
      if (!empty($this->Obj->KomponenGaji))
         for($i=2;$i<=count($arrData);$i++)
            $this->Obj->Import($arrData[$i]);
      else Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array(1=>'Susunan data baris dan kolom di dalam file Excel tidak benar!', $this->cssFail),Messenger::NextRequest);
      
      return $this->pageView;
   }

	function Import() {
      if(isset($this->_POST['cancel'])){
         return $this->pageView;
      }else{
         if($this->Check())
         {
            if ($this->_POST['sumber'] == 'sdm')
            {
               if (!$this->Obj->ImportFromGtSdm())
                  Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array(1=>'Tidak bisa berintegrasi dengan gtAkademik!', $this->cssFail),Messenger::NextRequest);
               return $this->pageView;
            }
            else return $this->ImportFromExcel();
         }
         else return $this->pageView;
      }
	}
   
   function ImportFromExcel ()
   {
      $data = $this->ReadFile($this->uploadDir . $this->file['name']);
      if ($data) return $this->SaveData($data);
      Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array(1=>'Format file Excel yang diupload tidak dikenal!', $this->cssFail),Messenger::NextRequest);
      return $this->pageView;
   }
}
?>
