<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class AppPopupUnitkerja extends Database {

   protected $mSqlFile= 'module/gaji_pegawai/business/apppopupunitkerja.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);       
   }
      
  function GetQueryKeren($sql,$params) {
		foreach ($params as $k => $v) {
			if (is_array($v)) {
				$params[$k] = '~~' . join("~~,~~", $v) . '~~';
				$params[$k] = str_replace('~~', '\'', addslashes($params[$k]));
			} else {
				$params[$k] = addslashes($params[$k]);
			}
		}
		$param_serialized = '~~' . join("~~,~~", $params) . '~~';
		$param_serialized = str_replace('~~', '\'', addslashes($param_serialized));
		eval('$sql_parsed = sprintf("' . $sql . '", ' . $param_serialized . ');');
		//echo $sql_parsed;
		return $sql_parsed;
	}
		
	function GetDataUnitkerja ($offset, $limit, $nama='') {
		/*if($tipeunit != "") 
			$str_tipeunit = " AND unitkerjaTipeUnitId = " . $tipeunit;
		else 
			$str_tipeunit = "";

		if($role['role_name'] == "OperatorUnit") 
			$str_unit = " AND (unitkerjaParentId=" . $unitkerjaUser['unit_kerja_id'] . " OR unitkerjaId=" . $unitkerjaUser['unit_kerja_id'] . ")";
		else 
			$str_unit="";*/
		if($nama != "") 
			$str_nama = " WHERE satkerNama LIKE '%".$nama."%'";
		else 
			$str_nama = "";
			
		$sql = $this->GetQueryKeren($this->mSqlQueries['get_data_unitkerja'], array($str_nama, $offset, $limit));
		//echo "<pre>" . $sql . "</pre>";
		//return $this->Open(stripslashes($sql), array());
		
		$this->Obj = new SatuanKerja();
	    $result = $this->Obj->GetSatuanKerjaByUserId($nama);
		return $result;
	}

	function GetCountDataUnitkerja ($nama='') {
		/*if($tipeunit != "") 
			$str_tipeunit = " AND unitkerjaTipeUnitId = " . $tipeunit;
		else 
			$str_tipeunit = "";

		if($role['role_name'] == "OperatorUnit") 
			$str_unit = " AND (unitkerjaParentId=" . $unitkerjaUser['unit_kerja_id'] . " OR unitkerjaId=" . $unitkerjaUser['unit_kerja_id'] . ")";
		else 
			$str_unit="";*/
		if($nama != "") 
			$str_nama = " WHERE satkerNama LIKE '%".$nama."%'";
		else 
			$str_nama = "";
		//$sql = $this->GetQueryKeren($this->mSqlQueries['get_count_data_unitkerja'], array('%'.$kode.'%', '%'.$kode.'%', '%'.$unitkerja.'%', '%'.$unitkerja.'%', $str_tipeunit, $str_unit));
		$sql = $this->GetQueryKeren($this->mSqlQueries['get_count_data_unitkerja'], array($str_nama));
		//echo $sql;
		$result = $this->Open(stripslashes($sql), array());

		if (!$result) {
			//return 0;
		} else {
			//return $result[0]['total'];
		}
		
		$this->Obj = new SatuanKerja();
	    $result = $this->Obj->GetSatuanKerjaByUserId($nama);
		return sizeof($result);
	}
	/*function GetDataTipeUnit($unitkerjaId = NULL) {
		$result = $this->Open($this->mSqlQueries['get_data_tipe_unit'], array());
		return $result;
	}*/
}
?>
