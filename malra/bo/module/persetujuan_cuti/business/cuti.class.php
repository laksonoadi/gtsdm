<?php

class Cuti extends Database {

   protected $mSqlFile= 'module/persetujuan_cuti/business/cuti.sql.php';
   
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
   function GetCountCuti($tampil, $datenow) {
      if($tampil != "all"){
  		  $str = " AND cutiTipecutiId = '".$tampil."'";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count2'], array($datenow, $str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataCuti($offset, $limit, $tampil, $datenow) { 
      if($tampil != "all"){
  		  $str = " AND cutiTipecutiId = '".$tampil."'";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data2'], array($datenow, $str, $offset, $limit));
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
   function Add($data,$tes) {
      if($tes=="yes"){
        $this->StartTrans();
        $this->Execute($this->mSqlQueries['do_add'], array($data['id'],$data['idsatker1'],$data['pegId1'],$data['status1']));	
        $this->Execute($this->mSqlQueries['do_add'], array($data['id'],$data['idsatker2'],$data['pegId2'],$data['status2']));
        $this->Execute($this->mSqlQueries['do_update_cuti'], array($data['status1'],$data['id']));	
        $result = $this->EndTrans(true);
      }else{
        $this->StartTrans();
        $this->Execute($this->mSqlQueries['do_add'], array($data['id'],$data['idsatker1'],$data['pegId1'],$data['status1']));	
        $this->Execute($this->mSqlQueries['do_add'], array($data['id'],$data['idsatker2'],$data['pegId2'],$data['status2']));
        $result = $this->EndTrans(true);
      }	   
      //print_r($this->getLastError());exit;  
      return $result;
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
