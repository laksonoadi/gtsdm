<?php

class MutasiPenelitian extends Database {

   protected $mSqlFile= 'module/mutasi_penelitian/business/mutasi_penelitian.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
   
   
   function GetListMutasiPenelitian($id) {
      $result = $this->Open($this->mSqlQueries['get_list_mutasi_penelitian'], array($id));
      return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
      $result = $this->Open($this->mSqlQueries['get_data_mutasi_penelitian_by_id'], array($id,$dataId));
      return $result;
      
   }
   
   function GetComboJenisBuku() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_buku'], array());
		return $result;
   }
   
   function GetComboJenisKarya() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_karya'], array());
		return $result;
   }
   
   function GetComboJenisPenelitian() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_penelitian'], array());
		return $result;
   }
   
   function GetComboJenisPublikasi() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_publikasi'], array());
		return $result;
   }
   
   function GetComboJenisKegiatan() {
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_kegiatan'], array());
		return $result;
   }
   
   function GetComboPeranan() {
		$result = $this->Open($this->mSqlQueries['get_combo_peranan'], array());
		return $result;
   }
   
   function GetComboAsalDana() {
		$result = $this->Open($this->mSqlQueries['get_combo_asal_dana'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
//===============do======================//   
   function Add($data,$tipe) {	
      if ($tipe==1){
          $return = $this->Execute($this->mSqlQueries['do_add_buku'], $data);            
      } else
      if ($tipe==2){
          $return = $this->Execute($this->mSqlQueries['do_add_artikel'], $data);  
      } else
      if ($tipe==3){
          $return = $this->Execute($this->mSqlQueries['do_add_penelitian'], $data);  
      } else
      if ($tipe==4){
          $return = $this->Execute($this->mSqlQueries['do_add_publikasi'], $data);  
      }   
      //exit;	  
      return $return;
   }  

   function AddIntegrasi($data,$tipe) {	
      if ($tipe==4){
          $return = $this->Execute($this->mSqlQueries['do_add_publikasi_integrasi'], $data);  
      } else {
		  $return= true;
	  }
      #exit;	  
      return $return;
   } 
	
   function Update($data,$tipe) {
     if ($tipe==1){
          $return = $this->Execute($this->mSqlQueries['do_update_buku'], $data);            
      } else
      if ($tipe==2){
          $return = $this->Execute($this->mSqlQueries['do_update_artikel'], $data);  
      } else
      if ($tipe==3){
          $return = $this->Execute($this->mSqlQueries['do_update_penelitian'], $data);  
      } else
      if ($tipe==4){
          $return = $this->Execute($this->mSqlQueries['do_update_publikasi'], $data);  
      }           		  
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
