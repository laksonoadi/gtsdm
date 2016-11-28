<?php

class AppKomponen extends Database {

	protected $mSqlFile= 'module/komponen_pph_21/business/appkomponenpph.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
	}
   
	function GetKomponenPph(){
      return $this->Open($this->mSqlQueries['get_komponen_pph'],array());
	}
   
	function GetKomponenGaji(){
      return $this->Open($this->mSqlQueries['get_komponen_gaji'],array());
	}
		
	function GetDataKomponen($offset, $limit, $id='') {
      #printf($this->mSqlQueries['get_data_komponen'], '%'.$id.'%', $offset, $limit);
		$result = $this->Open($this->mSqlQueries['get_data_komponen'], array('%'.$id.'%', $offset, $limit));
		return $result;
	}

	function GetCountDataKomponen($id) {
		$result = $this->Open($this->mSqlQueries['get_count_data_komponen'], array('%'.$id.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}

	function GetDataKomponenById($komponenId) {
		$result = $this->Open($this->mSqlQueries['get_data_komponen_by_id'], array($komponenId));
		return $result;
	}

	function GetDataKomponenByArrayId($arrKomponenId) {
		$komponenId = implode("', '", $arrKomponenId);
		$result = $this->Open($this->mSqlQueries['get_data_komponen_by_array_id'], array($komponenId));
		return $result;
	}

//===DO==
	
	function DoAddKomponen($komponenNama,$komponenFormula, $maxValue, $jenis) {
		$result = $this->Execute($this->mSqlQueries['do_add_komponen'], array($komponenNama,$komponenFormula,$_SESSION['username'], $maxValue, $jenis));
		return $result;
	}
	
	function DoUpdateKomponen($komponenNama, $komponenFormula, $maxValue, $jenis, $komponenId) {
      $result = $this->Execute($this->mSqlQueries['do_update_komponen'], array($komponenNama,$komponenFormula,$_SESSION['username'], $maxValue, $jenis, $komponenId));
// 	  printf($this->mSqlQueries['do_update_komponen'], $komponenKode, $komponenNama, $tipeunit, $satker, $komponenId);
	  
		return $result;
	}
	
	function DoDeleteKomponenById($komponenId) {
		$result=$this->Execute($this->mSqlQueries['do_delete_komponen_by_id'], array($komponenId));
		return $result;
	}

	function DoDeleteKomponenByArrayId($arrKomponenId) {
		$komponenId = implode("', '", $arrKomponenId);
		$result=$this->Execute($this->mSqlQueries['do_delete_komponen_by_array_id'], array($komponenId));
		return $result;
	}
}
?>
