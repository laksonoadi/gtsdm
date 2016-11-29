<?php

class MutasiBintangTandaJasa extends Database {

   protected $mSqlFile= 'module/mutasi_bintang_tanda_jasa/business/mutasibintangtandajasa.sql.php';
   
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
   
   function GetListMutasi($id) {
   $result = $this->Open($this->mSqlQueries['GetListMutasi'], array($id));
   return $result;
      
   }

   function GetListMutasiVerifikasi($id) {
   $result = $this->Open($this->mSqlQueries['GetListMutasi_verifikasi'], array($id));
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['GetDataMutasiById'], array($id,$dataId));
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
   
    function GetComboTandaJasa() {
		$result = $this->Open($this->mSqlQueries['GetComboTandaJasa'], array());
		return $result;
   }
      
//===============do======================//   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);
      //exit;	  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], $data);   
		//exit;	 
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
