<?php

class AppKomponen extends Database {

	protected $mSqlFile= 'module/komponen_gaji_karyawan/business/appkomponengaji.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
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
			//return $result;
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
	
	function GetJenisPegawaiJoinKomponen($komponenId) {
		if ($komponenId!=""){
			$result = $this->Open($this->mSqlQueries['get_jenis_pegawai_join_komponen_edit'], array($komponenId));
		}else{
			$result = $this->Open($this->mSqlQueries['get_jenis_pegawai_join_komponen_tambah'], array());
		}
		return $result;
	}

//===DO==
	function InsertJenisPegawaiJoinKomponen($komponenId) {
		$data = $_POST['data']->AsArray();
		for ($i=0; $i<sizeof($data['jenis']); $i++){
			$insert = $this->InsertJenisPegawaiJoinKomponenDetail($komponenId,$data['jenis'][$i],$data['nilai'][$i]);
			if ($insert===false) break;
		}
		return $insert;
	}
	
	function InsertJenisPegawaiJoinKomponenDetail($komponenId,$jenisId,$nilai) {
		$result = $this->Execute($this->mSqlQueries['insert_jenis_pegawai_join_komponen'], array($komponenId,$jenisId,$nilai,$nilai));
		return $result;
	}
	
	function DoAddKomponen($komponenNama,$komponenFormula) {
		$this->StartTrans();
		$result = $this->Execute($this->mSqlQueries['do_add_komponen'], array($komponenNama,$komponenFormula,$_SESSION['username']));
		$komponenId=$this->GetLastInsertId();
		
		if ($result){
			$komponenId=$this->Open('SELECT max(kompformId) as id FROM sdm_komponen_formula', array());
			$result=$this->InsertJenisPegawaiJoinKomponen($komponenId[0]['id']);
		}
		
		$this->EndTrans($result);
		return $result;
	}
	
	function DoUpdateKomponen($komponenNama, $komponenFormula, $komponenId) {
		$this->StartTrans();
		$result = $this->Execute($this->mSqlQueries['do_update_komponen'], array($komponenNama,$komponenFormula,$_SESSION['username'], $komponenId));	  
		
		if ($result) {
			$result=$this->InsertJenisPegawaiJoinKomponen($komponenId);
		}
		
		$this->EndTrans($result);
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
