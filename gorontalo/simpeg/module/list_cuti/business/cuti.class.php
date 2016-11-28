<?php

class Cuti extends Database {

   protected $mSqlFile= 'module/list_cuti/business/cuti.sql.php';
   
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
   function GetCountCuti($tampil,$tipe) {
      if($tampil != "all"){
  		  $str = "WHERE cutiStatus = '".$tampil."'";
      }else{
        $str = "";
      }
      
      if(($str != "") and ($tipe != "all")){
  		  $str2 = " AND cutiTipecutiId = '".$tipe."'";
      }elseif(($str == "") and ($tipe != "all")){
  		  $str2 = "WHERE cutiTipecutiId = '".$tipe."'";
      }else{
        $str2 = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str,$str2));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataCuti($offset, $limit, $tampil, $tipe) { 
      if($tampil != "all"){
  		  $str = "WHERE cutiStatus = '".$tampil."'";
      }else{
        $str = "";
      }
      
      if(($str != "") and ($tipe != "all")){
  		  $str2 = " AND cutiTipecutiId = '".$tipe."'";
      }elseif(($str == "") and ($tipe != "all")){
  		  $str2 = "WHERE cutiTipecutiId = '".$tipe."'";
      }else{
        $str2 = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $str2, $offset, $limit));
  		//print_r(stripslashes($result));
      return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataCetak($tipe, $tampil) { 
      if($tipe != "all"){
  		  $str = "WHERE cutiTipecutiId = '".$tipe."'";
      }else{
        $str = "";
      }
      
      if(($str != "") and ($tampil != "all")){
  		  $str2 = " AND cutiStatus = '".$tampil."'";
      }elseif(($str == "") and ($tampil != "all")){
  		  $str2 = "WHERE cutiStatus = '".$tampil."'";
      }else{
        $str2 = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data_cetak'], array($str, $str2));
  		//print_r(stripslashes($result));
      return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetDataCutiDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_cuti_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetAppCutiDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_app_cuti_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetComboTipe(){
     $result = $this->Open($this->mSqlQueries['get_combo_tipe'], array());
	   return $result;
   }
   
//==DO==
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);	
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   /*function Update($data) {
      $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }*/
   
   function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
}
?>
