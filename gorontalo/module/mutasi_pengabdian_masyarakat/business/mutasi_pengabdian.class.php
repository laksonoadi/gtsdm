<?php

class MutasiPengabdian extends Database {

   protected $mSqlFile= 'module/mutasi_pengabdian_masyarakat/business/mutasi_pengabdian.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
         
   }
   
   function GetComboJenisPengabdian() {   
     $result = $this->Open($this->mSqlQueries['get_combo_jenis_pengabdian'], array());      
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
   
   function GetListMutasiPengabdian($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_pengabdian'], array($id));
   //exit;	  
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_pelatihan_by_id'], array($id,$dataId));
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
   
    function GetComboTipePelatihan() {
		$result = $this->Open($this->mSqlQueries['get_combo_tipe_pelatihan'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboJenisPelatihan() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_pelatihan'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboAsalDana() {
		$result = $this->Open($this->mSqlQueries['get_combo_asal_dana'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
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
      return $return;
   }   
	
	function Delete($id) {
      //$id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id)); 
       return $ret;
	}

}
?>
