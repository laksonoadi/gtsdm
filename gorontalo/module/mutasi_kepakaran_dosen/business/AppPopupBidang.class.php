<?php

class AppPopupBidang extends Database {

   protected $mSqlFile= 'module/mutasi_kepakaran_dosen/business/apppopupbidang.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //        
   }

	function GetData($offset, $limit, $nama) {
		$result = $this->Open($this->mSqlQueries['get_data'], array($nama, $offset, $limit));
      //$debug = sprintf($this->mSqlQueries['get_data'], '%'.$nama.'%', '%'.$alamat.'%', '%'.$alamat.'%', $offset, $limit);
      //echo $debug;
      return $result;
	}

	function GetCountData ($nama) {
		$result = $this->Open($this->mSqlQueries['get_count_data'], array($nama));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}
	
	function GetComboBidang() {
		$result = $this->Open($this->mSqlQueries['get_combo_bidang'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
  function GetComboKepakaran($bidang) {
		$result = $this->Open($this->mSqlQueries['get_combo_kepakaran'], array($bidang));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
}
?>
