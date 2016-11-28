<?php

class SaudaraKandung extends Database {

   protected $mSqlFile= 'module/data_saudara_kandung/business/data_saudara_kandung.sql.php';
   
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
   function GetCount($nip_nama='') {
      if($nip_nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
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
   
   function GetData ($offset, $limit, $nip_nama='') { 
      if($nip_nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $offset, $limit));
  		return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetDataSdr($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_saudara'], array($id)); 
	     return $result;	  
   }

   function GetDataSdrVerifikasi($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_saudara_verifikasi'], array($id)); 
       return $result;    
   }
   
   function GetDataSdrDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_saudara_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetComboAgama(){
      $result = $this->Open($this->mSqlQueries['get_combo_agama'], array());
    return $result;
   }
   
//==DO==
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);	  
      return $return;
   }
   
   function Update($data) {
      $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
}
?>
