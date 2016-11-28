<?php
class PopupPegawai extends Database {

	protected $mSqlFile= 'module/mutasi_sertifikasi/business/popup_pegawai.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);       
	}
      
	
	function GetCountData($nama='',$satker='') {
  		$res2 = $this->Open($this->mSqlQueries['get_count'], array('%'.$nama.'%','%'.$nama.'%'));
		return $res2[0]['TOTAL'];
	}
   
	function GetData ($offset, $limit, $nama='',$satker='') { 
		$result = $this->Open($this->mSqlQueries['get_data'], array('%'.$nama.'%','%'.$nama.'%', $offset, $limit));
		return $result;
	}
}
?>
