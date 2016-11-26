<?php

class MutasiKepakaranDosen extends Database {

   protected $mSqlFile= 'module/mutasi_kepakaran_dosen/business/mutasi_kepakaran_dosen.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
   function GetListPegawai($pNip='', $pNama='', $start, $limit) {
   //echo $pNip;
   if(($pNip!='') and ($pNama!=''))                      
         $str = ' OR ';
      else
         $str = ' AND ';      
   $sql = sprintf($this->mSqlQueries['get_list_pegawai'], '%s',$str,'%s','%d','%d');
   $result = $this->Open($sql, array('%'.$pNip.'%', '%'.$pNama.'%', $start, $limit));      
   //$result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$pNip.'%','%'.$pNama.'%', $start, $limit));
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
      
   function GetCount() {
     $result = $this->Open($this->mSqlQueries['get_count_pegawai'], array());
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
