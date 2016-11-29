<?php

class AppPopupBank extends Database {

   protected $mSqlFile= 'module/data_pegawai/business/apppopupbank.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);       
   }

	function GetData($offset, $limit, $nama='') {
		$result = $this->Open($this->mSqlQueries['get_data'], array('%'.$nama.'%', $offset, $limit));
      //$debug = sprintf($this->mSqlQueries['get_data'], '%'.$nama.'%', '%'.$alamat.'%', '%'.$alamat.'%', $offset, $limit);
      //echo $debug;
      return $result;
	}
	
	function GetComboBank() {
		$result = $this->Open($this->mSqlQueries['get_combo_bank'], array());
    return $result;
	}

	function GetCountData ($nama='') {
		$result = $this->Open($this->mSqlQueries['get_count_data'], array('%'.$nama.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}
}
?>
