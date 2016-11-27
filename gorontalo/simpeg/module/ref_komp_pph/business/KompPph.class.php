<?php

class KompPph extends Database {

	protected $mSqlFile= 'module/ref_komp_pph/business/komppph.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		#
		
	}
		
	function GetDataPph($offset, $limit, $pph='') {
		$result = $this->Open($this->mSqlQueries['get_data_pph'], array('%'.$pph.'%','%'.$pph.'%', $offset, $limit));
		return $result;
	}

	function GetCountDataPph($pph) {
		
		$result = $this->Open($this->mSqlQueries['get_count_data_pph'], array('%'.$pph.'%', '%'.$pph.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
		
	}

	function GetDataPphById($pphId) {
		$result = $this->Open($this->mSqlQueries['get_data_pph_by_id'], array($pphId));
		return $result;
	}

	function GetDataPphByArrayId($arrPphId) {
		$pphId = implode("', '", $arrPphId);
		$result = $this->Open($this->mSqlQueries['get_data_pph_by_array_id'], array($pphId));
		return $result;
	}
	
	function GetDataExcelPph($cari='') {
		$result = $this->Open($this->mSqlQueries['get_data_excel'], array('%'.$cari.'%','%'.$cari.'%'));
		return $result;
	}

	//--untuk pengecekan----------
	function GetCodeGaji($code) {
		$result = $this->Open($this->mSqlQueries['get_code_gaji'], array($code));
		return $result;
	}
	function GetCodePph($code) {
		$result = $this->Open($this->mSqlQueries['get_code_pph'], array($code));
		return $result;
	}
	//--------------------------------
//===DO==
	
	function DoAddKompPph($pphKode,$pphNama, $pphKeterangan) {
	//
		$result = $this->Execute($this->mSqlQueries['do_add_pph'], array($pphKode,$pphNama, $pphKeterangan));
	// 
		return $result;
	}
	
	function DoUpdateKompPph($pphKode,$pphNama, $pphKeterangan, $pphId) {
		$result = $this->Execute($this->mSqlQueries['do_update_pph'], array($pphKode,$pphNama, $pphKeterangan, $pphId));
		return $result;
	}
	
	function DoDeleteKompPphById($pphId) {
		$result=$this->Execute($this->mSqlQueries['do_delete_pph_by_id'], array($pphId));
		return $result;
	}

	function DoDeleteKompPphByArrayId($arrPphId) {
		$pphId = implode("', '", $arrPphId);
		$result=$this->Execute($this->mSqlQueries['do_delete_pph_by_array_id'], array($pphId));
		return $result;
	}
}
?>
