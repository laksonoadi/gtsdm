<?php

class MutasiKepakaranDosen extends Database {

   protected $mSqlFile= 'module/mutasi_kepakaran_dosen/business/mutasi_kepakaran_dosen.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
   function GetListPegawai($tampilkan, $start, $limit) {   
     $result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
     return $result;
   }
   
   function GetDataDetail($id) { 
   $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
   return $result;
   }
   
   function GetListMutasiKepakaranDosen($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_kepakaran_dosen'], array($id));
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_kepakaran_dosen_by_id'], array($id,$dataId));
   return $result;
      
   }
      
   function GetCount($tampilkan) {
     $result = $this->Open($this->mSqlQueries['get_count_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%'));
     return $result[0]['total'];     
   }
   
   function GetCountMutasi($id) {
     $result = $this->Open($this->mSqlQueries['get_count_mutasi'], array($id));
     return $result[0]['total'];     
   }
   
//===============do======================//   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);
  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		//$this->mdebug();  
      return $return;
   }   
	
	function Delete($id) {
      //$id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
      //exit; 
       return $ret;
	}

}
?>