<?php

class JenisKarya extends Database {

   protected $mSqlFile= 'module/jenis_karya/business/jenis_karya.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);     
   }
//==GET==      
   function GetData ($offset, $limit, $data) { 
     
      $arg='';
	  $params = array("%$data%");
	  if($limit !== NULL AND $offset !== NULL){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
      $result = $this->Open($this->mSqlQueries['get_data'].$arg,$params);
      return $result;
   }

   function GetCount ($data) {
     $result = $this->Open($this->mSqlQueries['get_count'], array("%$data%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['total'];    
   }
   
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }  
   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], array($data['nama']));	  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], array($data['nama'],$data['id']));         		  
		//$this->mdebug();  
      return $return;
   }   
	
	function Delete($id) {
      $id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
       return $ret;
	}
}
?>
