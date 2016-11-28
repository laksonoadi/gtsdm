<?php

class AppPopupMasa extends Database {

   protected $mSqlFile= 'module/mutasi_kenaikan_gaji_berkala/business/apppopupmasa.sql.php';
   
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
	
}
?>
