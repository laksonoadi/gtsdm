<?php

class AppBenefit extends Database {

   protected $mSqlFile= 'module/persetujuan_benefit/business/app_benefit.sql.php';
   
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
   function GetCountAppBenefit($nip_nama='') {
      if($nip_nama != ""){
  		  $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count2'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataAppBenefit($nip_nama='',$offset, $limit) { 
      if($nip_nama != ""){
  		  $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data2'], array($str,$offset, $limit));

  		//print_r(stripslashes($result));
      return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataById($id) {      
      $result[0] = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));     
      $result[1] = $this->Open($this->mSqlQueries['get_spv_by_spv_id'], array($result[0][0]['id_spv']));
      $result[2] = $this->Open($this->mSqlQueries['get_mor_by_mor_id'], array($result[0][0]['id_mor'])); 
	  if($result)
	     return $result;
	  else
	     return $result;	  
   }
   
   function GetDataAppBenefitDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_app_benefit_det'], array($id)); 
	     return $result;	  
   }
   
   function GetDetailPegawaiById($id) {      
      $result = $this->Open($this->mSqlQueries['get_detail_pegawai_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetDataKlaimFromBenefitId($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_klaim_from_benefit_id'], array($id)); 
      //print_r($this->getLastError());exit;
	    return $result; 	  
   }
   
   function GetBalanceBenefitLeft($pegId){
      $result = $this->Open($this->mSqlQueries['get_balance_benefit_left'], array($pegId)); 
      //print_r($this->getLastError());exit;
	    if($result)
	     return $result[0];
	    else
	     return $result;;                                  
   }
   
//==DO==
   function Update($data) {
      $result = $this->Execute($this->mSqlQueries['do_update_app_benefit'], array($data['status'],$data['tgl_status'],$data['id']));	
      //print_r($this->getLastError());exit;  
      return $result;
   }
   
   function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
}
?>
