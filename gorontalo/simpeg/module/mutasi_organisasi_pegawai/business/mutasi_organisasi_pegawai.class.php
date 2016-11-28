<?php

class MutasiOrganisasiPegawai extends Database {

   protected $mSqlFile= 'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
   
   function GetComboJenisOrganisasi() {   
     $result = $this->Open($this->mSqlQueries['get_combo_jenis_organisasi'], array());      
     return $result;
   }
   
   function GetListPegawai($tampilkan, $start, $limit) {   
     $result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
     return $result;
   }
   
   function GetDataDetail($id) { 
   $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
   return $result;
   }
   
   function GetListMutasiOrganisasiPegawai($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_organisasi_pegawai'], array($id));
   return $result;
      
   }

    function GetListMutasiOrganisasiPegawaiVerifikasi($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_organisasi_pegawai_verifikasi'], array($id));
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_organisasi_pegawai_by_id'], array($id,$dataId));
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
      //exit;	  
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
