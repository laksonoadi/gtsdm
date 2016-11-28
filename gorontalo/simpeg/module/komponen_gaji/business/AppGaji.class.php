<?php

class AppGaji extends Database {

	protected $mSqlFile= 'module/komponen_gaji/business/appgaji.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
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

	function GetData($offset, $limit, $nama='', $jenis='') {
		if($jenis != "" && $jenis != "all") $str_jenis = " AND kompgajiJenis = '".$jenis."'";
		else $str_jenis = "";

		$sql = $this->GetQueryKeren($this->mSqlQueries['get_data'], array('%'.$nama.'%', '%'.$nama.'%', $str_jenis, $offset, $limit));
		//echo "<pre>" . stripslashes($sql) . "</pre>";
		return $this->Open(stripslashes($sql), array());
	}

	function GetCountData($nama, $jenis) {
		if($jenis != "" && $jenis != "all") $str_jenis = " AND kompgajiJenis = '".$jenis."'";
		else $str_jenis = "";
		$sql = $this->GetQueryKeren($this->mSqlQueries['get_count_data'], array('%'.$nama.'%', '%'.$nama.'%', $str_jenis));
      $result = $this->Open(stripslashes($sql), array());
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}

	function GetDataById($id) {
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));
		return $result;
	}

   //EXCEL
   function GetDataSheet1() {
		$result = $this->Open($this->mSqlQueries['get_data_sheet1'], array());
		return $result;
   }
   function GetDataSheet2() {
		$result = $this->Open($this->mSqlQueries['get_data_sheet2'], array());
		return $result;
   }
   function GetDataSheet3() {
		$result = $this->Open($this->mSqlQueries['get_data_sheet3'], array());
		return $result;
   }
   //EXCEL END
//===DO==
	
	function DoAddData($kode, $nama, $keterangan, $jenis) {
		$result = $this->Execute($this->mSqlQueries['do_add_data'], array($kode, $nama, $keterangan, $jenis));
		return $result;
	}
	
	function DoUpdateData($kode, $nama, $keterangan, $jenis, $id) {
		$result = $this->Execute($this->mSqlQueries['do_update_data'], array($kode, $nama, $keterangan, $jenis, $id));
	  //$debug = sprintf($this->mSqlQueries['do_update_gaji'], $gajiKode, $gajiNama, $tipeunit, $satker, $gajiId);
	  //echo $debug;
		return $result;
	}
	
	function DoDeleteData($id) {
		$result=$this->Execute($this->mSqlQueries['do_delete_data'], array($id));
		return $result;
	}

	function DoDeleteDataByArrayId($arrId) {
		$id = implode("', '", $arrId);
		$result=$this->Execute($this->mSqlQueries['do_delete_data_by_array_id'], array($id));
		return $result;
	}
/*
	function GetDataGajiByArrayId($arrGajiId) {
		$gajiId = implode("', '", $arrGajiId);
		$result = $this->Open($this->mSqlQueries['get_data_gaji_by_array_id'], array($gajiId));
		return $result;
	}

   */
}
?>
