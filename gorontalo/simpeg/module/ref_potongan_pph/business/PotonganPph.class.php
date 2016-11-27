<?php

class PotonganPph extends Database {

	protected $mSqlFile= 'module/ref_potongan_pph/business/potonganpph.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		#
		
	}
		
	function GetDataPph($offset, $limit, $pph='') {
		$result = $this->Open($this->mSqlQueries['get_data_pphrp'], array('%'.$pph.'%', $offset, $limit));
		return $result;
	}

	function GetCountDataPph($pph) {
		
		$result = $this->Open($this->mSqlQueries['get_count_data_pphrp'], array('%'.$pph.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
		
	}

	function GetDataPphById($pphId) {
		$result = $this->Open($this->mSqlQueries['get_data_pphrp_by_id'], array($pphId));
		return $result;
	}

	function GetDataPphByArrayId($arrPphId) {
		$pphId = implode("', '", $arrPphId);
		$result = $this->Open($this->mSqlQueries['get_data_pphrp_by_array_id'], array($pphId));
		return $result;
	}
	
	function GetDataExcelPph($cari='') {
		$result = $this->Open($this->mSqlQueries['get_data_excel'], array('%'.$cari.'%'));
		return $result;
	}

//===DO==
	
	function DoAddPotonganPph($pphrpNama, $pphrpNominalMax, $pphrpOrder, $pphrpUserId) {
	//
		$result = $this->Execute($this->mSqlQueries['do_add_pphrp'], array($pphrpNama, $pphrpNominalMax, $pphrpOrder, $pphrpUserId));
	// 
		return $result;
	}
	
	function DoUpdatePotonganPph($pphrpNama, $pphrpNominalMax, $pphrpOrder,$pphrpUserId, $pphId) {
		$result = $this->Execute($this->mSqlQueries['do_update_pphrp'], array($pphrpNama, $pphrpNominalMax, $pphrpOrder,$pphrpUserId, $pphId));
		return $result;
	}
	
	function DoDeletePotonganPphById($pphId) {
		$result=$this->Execute($this->mSqlQueries['do_delete_pphrp_by_id'], array($pphId));
		return $result;
	}

	function DoDeletePotonganPphByArrayId($arrPphId) {
		$pphId = implode("', '", $arrPphId);
		$result=$this->Execute($this->mSqlQueries['do_delete_pphrp_by_array_id'], array($pphId));
		return $result;
	}
	
	function DoSetOrder($order, $pphId) {
		$result=$this->Execute($this->mSqlQueries['do_set_order'], array($order, $pphId));
		return $result;
	}
}
?>
