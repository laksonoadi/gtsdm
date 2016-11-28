<?php

class AppDetilGajiPegawai extends Database {

	protected $mSqlFile= 'module/gaji_pegawai/business/appdetilgajipegawai.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
	}

	function GetInfo($id) {
		$result = $this->Open($this->mSqlQueries['get_info'], array($id));
		return $result[0];
	}

	function GetData($id) {
		$result = $this->Open($this->mSqlQueries['get_data'], array($id));
		return $result;
	}

}
?>
