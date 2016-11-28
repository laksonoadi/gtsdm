<?php

class DataKontrakPegawai extends Database {

   protected $mSqlFile= 'module/data_pegawai/business/data_kontrak_pegawai.sql.php';
   
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
   
   function GetListHistoryKontrakPegawai($id) {
   $result = $this->Open($this->mSqlQueries['get_list_history_kontrak_pegawai'], array($id));
   return $result;
      
   }
   
   function GetDataKontrakPegawaiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_kontrak_pegawai_by_id'], array($id,$dataId));
   return $result;
      
   }
      
   function GetCount($pNip='', $pNama='') {
     if(($pNip!='') and ($pNama!=''))                      
         $str = ' OR ';
      else
         $str = ' AND ';      
     $sql = sprintf($this->mSqlQueries['get_count_pegawai'], '%s',$str,'%s','%d','%d');
     $result = $this->Open($sql, array('%'.$pNip.'%', '%'.$pNama.'%')); 
     #$result = $this->Open($this->mSqlQueries['get_count_pegawai'], array());
     return $result[0]['total'];     
   }
   
   function GetCountHistoryKontrakPegawai($id) {
     $result = $this->Open($this->mSqlQueries['get_count_history_kontrak_pegawai'], array($id));
     return $result[0]['total'];     
   }
   
   function GetTanggalAwalKontrakPegawaiById($id){
     $result = $this->Open($this->mSqlQueries['get_tanggal_awal_kontrak_pegawai_by_id'], array($id));
     return $result[0]['tgl_awal'];
   }
   
   function GetTanggalAkhirKontrakPegawaiById($id){
     $result = $this->Open($this->mSqlQueries['get_tanggal_akhir_kontrak_pegawai_by_id'], array($id));
     return $result[0]['tgl_akhir'];
   }
//===============do======================//   
   function Add($data) {	   
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);	  
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], $data);
     /*$x = sprintf($this->mSqlQueries['do_update'], $data['pegKode'], $data['jabs_ref'], $data['eselon'], $data['golongan_ref'], $data['mulai'], $data['selesai'],
          $data['pejabat'], $data['nosk'], $data['tgl_sk'], $data['status'], $data['upload'], $data['id']);
     print_r($x);exit;*/         		  
		//$this->mdebug();  
      return $return;
   }   
	
	function Delete($id) {
      //$id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
      //exit; 
       return $ret;
	}
	
	function UpdateStatus($status,$id,$pegId){
	  $return = $this->Execute($this->mSqlQueries['update_status'], array($status,$id,$pegId));         		  
    return $return;
	}
	
	function UpdateTglKeluarInstitusi($tglkeluar,$id) {
    $return = $this->Execute($this->mSqlQueries['do_update_tgl_keluar_institusi'], array($tglkeluar, $id));  
    return $return;
  }
  
  function UpdateTglMasukInstitusi($tglmasuk,$id) {
     $return = $this->Execute($this->mSqlQueries['do_update_tgl_masuk_institusi'], array($tglmasuk, $id));  
      return $return;
  }
	
	function GetMaxStatus(){
	   $result = $this->Open($this->mSqlQueries['get_max_status'],array());
      return $result;
	}
	
	function GetMaxId(){
	   $result = $this->Open($this->mSqlQueries['get_max_id'],array());
      return $result;
	}
}
?>
