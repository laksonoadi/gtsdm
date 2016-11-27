<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class PopupPegawai extends Database {

   protected $mSqlFile= 'module/pendapatan_lain/business/popup_pegawai.sql.php';
   
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
	
	function GetCountData($nama='') {
      if($nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nama."%' OR pegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			//return 0;
  		} else {
  			//return $res3;
  		}  
	  $Obj = new DataPegawai;	 
	  $totalData = $Obj->GetCountPegawaiByUserId($nama, 'all');
	  return $totalData;
   }
   
   function GetData ($offset, $limit, $nama='') { 
      if($nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nama."%' OR pegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $offset, $limit));
  	  //return $this->Open(stripslashes($result), array());    
	  $Obj = new DataPegawai;
	  $result = $Obj->GetDataPegawaiByUserId($offset, $limit, $nama, 'all');
	  return $result;
   }
}
?>