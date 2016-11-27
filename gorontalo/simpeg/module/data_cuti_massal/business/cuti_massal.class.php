<?php

class CutiMassal extends Database {

   protected $mSqlFile= 'module/data_cuti_massal/business/cuti_massal.sql.php';
   
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
   
   function GetCountCutiMassal() {
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array());
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataCutiMassal($offset, $limit) { 
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($offset, $limit));
  		//print_r(stripslashes($result));
      return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataCutiMassalDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_cuti_massal_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetMaxCutiMassalId() { 
      
      $result = $this->Open($this->mSqlQueries['get_max_cuti_massal_id'], array());
  		//print_r(stripslashes($result));
      return $result;    
   }
   
   function GetDataCutiMassalPegawai($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_cuti_massal_pegawai'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
//==DO==
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);	
      //$x = sprintf($this->mSqlQueries['do_add'], $data['nama'], $data['mulai'], $data['selesai'], $data['alasan'],$data['file']);
      //print_r($x);
     //print_r($this->getLastError());exit;
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
   
   function AddCutiMassalPegawai($arrPegawai,$getCutimassalId) {	   
      $return = $this->Execute($this->mSqlQueries['do_add_cuti_massal_pegawai'], array($arrPegawai,$getCutimassalId));	
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddMassalCuti($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add_massal_cuti'], $data);	
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function GetPeriodeCutiByPegId($pegId){
     $result = $this->Open($this->mSqlQueries['get_periode_cuti_by_peg_id'], array($pegId));
	   return $result;
   }
   
   function UpdatePeriodeCutiDiambil($pegId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil'], array($pegId));
      return $return;
   }
   
   function UpdatePeriodeCutiDiambilTambah($pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil_tambah'], array($pegId,$_perId));
      return $return;
   }
   
   function UpdatePeriodeCutiDiambilKurang($pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil_kurang'], array($pegId,$perId));
      return $return;
   }
}
?>
