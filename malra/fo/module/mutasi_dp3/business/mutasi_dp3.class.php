<?php

class MutasiDp3 extends Database {

   protected $mSqlFile= 'module/mutasi_dp3/business/mutasi_dp3.sql.php';
   
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
     //
     return $result;
   }
   
   function GetListMutasiDp3($id) {
     $result = $this->Open($this->mSqlQueries['get_list_mutasi_dp3'], array($id));
     return $result; 
   }
   
   function GetDataMutasiById($id,$dataId) {
     $result = $this->Open($this->mSqlQueries['get_data_mutasi_dp3_by_id'], array($id,$dataId));
     //
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
   
   function GetComboUnitKerja() {
		$result = $this->Open($this->mSqlQueries['get_combo_unit_kerja'], array());
		return $result;
   }
   
   function GetComboJabatan($pegId) {
		$result = $this->Open($this->mSqlQueries['get_combo_jabatan'], array($pegId));
		return $result;
   }
   
   function GetIdStruk($id) {      
      $result = $this->Open($this->mSqlQueries['get_id_struk'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
//===============do======================//   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);
      // exit();	  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		 // exit(); 
     return $return;
   }   
	
	function Delete($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
      //exit; 
     return $ret;
	}
	
	function GetMaxId(){
	   $result = $this->Open($this->mSqlQueries['get_max_id'],array());
      return $result[0]['MAXID'];
	}
	
	function GetIdLain($id1,$id2){
	   $result = $this->Open($this->mSqlQueries['get_id_lain'],array($id1,$id2));
      return $result;
	}
}
?>
