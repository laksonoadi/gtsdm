<?php

class TombolAksi extends Database {

	protected $mSqlFile= 'module/tombol_aksi/business/tombol_aksi.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//     
	}
   
	function GetReferensiTombolAksi($jenis='',$param=array()) {
		if (($jenis==1)||($jenis=='1')) {
			//Dari data verifikasi
			$result = $this->Open($this->mSqlQueries['get_referensi_tombol_aksi_1'], array($param[0],$param[1]));
			$IsShow = $result[0]['total']>0?'FALSE':'TRUE';
		}else if ($jenis==2) {
			//Dari Group
		}
		
		return $IsShow;
	}
   
}
?>
