<?php

class Rekomendasi extends Database {

   protected $mSqlFile= 'module/rekomendasi/business/rekomendasi.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);   
      //  
   }
//==GET==      
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetKode($postKode) {      
      $result = $this->Open($this->mSqlQueries['get_kode'], array($postKode)); 
	  if (!$result)
		return 0;
	  else
		return $result[0]['cekCode'];    
   }
   
   function GetCount ($data) {
     $result = $this->Open($this->mSqlQueries['get_count'], array("%$data%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['total'];    
   }
   
   function GetData ($offset, $limit, $data) { 
      $result = $this->Open($this->mSqlQueries['get_data'], array("%$data%",$offset,$limit));
      return $result;
   }
   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], array($data['kode'], $data['nama']));	  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], array($data['kode'], $data['nama'], $data['id']));         		  
		// print_r($this->getLastError());
     return $return;
   }   
	
	function Delete($id) {
      $id = $id['idDelete'];
	    $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
      return $ret;
	}
}
?>
