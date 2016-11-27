<?php

class GajiPokok extends Database {

   protected $mSqlFile= 'module/gaji_pokok/business/gaji_pokok.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);   
      //  
   }
   
//==GET==    
   function GetData() { 
      $result = $this->Open($this->mSqlQueries['get_data'], array()); 
      //print_r($this->getLastError());
	     return $result;   
   }
   
   function GetGajiPokok($id) {      
      $result = $this->Open($this->mSqlQueries['get_gaji_pokok'], array($id)); 
	     return $result;	  
   }
   
   function GetGapokDet($idPangkat,$idKomp) {      
      $result = $this->Open($this->mSqlQueries['get_gapok_det'], array($idPangkat,$idKomp)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetNamaPang($id) {      
      $result = $this->Open($this->mSqlQueries['get_nama_pang'], array($id)); 
	     return $result;	  
   }
   
//==DO==
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);	  
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
}
?>
