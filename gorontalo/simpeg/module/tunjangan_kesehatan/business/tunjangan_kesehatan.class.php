<?php

class TunjanganKesehatan extends Database {

   protected $mSqlFile= 'module/tunjangan_kesehatan/business/tunjangan_kesehatan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //     
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
  
//==GET== 
   function GetCountData($jenis_tunj='') {
      if($jenis_tunj != ""){
  		  $str = " WHERE (b.jtkNama LIKE '%".$jenis_tunj."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   } 
   
   function GetData ($offset, $limit, $jenis_tunj='') { 
      if($jenis_tunj != ""){
  		  $str = "WHERE (b.jtkNama LIKE '%".$jenis_tunj."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $offset, $limit));
  		return $this->Open(stripslashes($result), array());    
   } 
   
   function GetDatTunDetail($id){
      $result = $this->Open($this->mSqlQueries['get_dattun_detail'], array($id)); 
      //print_r($this->getLastError());
  	  if($result)
  	     return $result[0];
  	  else
  	     return $result;
   }
   
   function GetJenisTun(){
      $result = $this->Open($this->mSqlQueries['get_jenis_tun'], array());
	  return $result;
   }
   
   function GetStatNikah(){
      $result = $this->Open($this->mSqlQueries['get_stat_nikah'], array());
	  return $result;
   }
  
////DO
   function Add($data) {	 
      $return = $this->Execute($this->mSqlQueries['do_add'],$data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function Update($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_update'],$data);	  
      return $return;
   }  
   
    function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));	
      return $result;
    }
}
?>
