<?php

class MutasiJabatanStruktural extends Database {

   protected $mSqlFile= 'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.sql.php';
   
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


   function GetUnitKerjaPegawaiAktif($id) { 
   $result = $this->Open($this->mSqlQueries['get_unit_kerja_pegawai_aktif'], array($id));
   return $result;
   }
   
   
   function GetListMutasiJabatanStruktural($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_jabatan_struktural'], array($id));
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_jabatan_struktural_by_id'], array($id,$dataId));
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
   
   function GetComboJabatanStruktural($id) {
		$result = $this->Open($this->mSqlQueries['get_combo_jabstruk'], array($id));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }

   function GetComboJabatanStrukturalByUnit($id) {
    $result = $this->Open($this->mSqlQueries['get_combo_jabstruk_by_unit'], array($id));
    //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
    return $result;
   }
   
   
   function GetComboPangkatGolongan($id) {
		$result = $this->Open($this->mSqlQueries['get_combo_golongan'], array($id));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboPangkatGolonganAll() {
		$result = $this->Open($this->mSqlQueries['get_combo_golongan_all'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
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
	
	function GetMaxStatus(){
	   $result = $this->Open($this->mSqlQueries['get_max_status'],array());
      return $result;
	}
	
	function GetMaxId(){
	   $result = $this->Open($this->mSqlQueries['get_max_id'],array());
      return $result;
	}
	
	function GetIdLain($id1,$id2){
	   $result = $this->Open($this->mSqlQueries['get_id_lain'],array($id1,$id2));
      return $result;
	}
	
	function AddDataMutasi($id,$idStruk,$dateNow,$idLain){	
      $this->StartTrans();
        if(!empty($idLain)){
      		for ($i=0; $i<sizeof($idLain); $i++){
      		  $this->Execute($this->mSqlQueries['do_delete_komp_mutasi'], array($id,$idLain[$i]['komp']));
      	  }
    	  }
    	$this->Execute($this->mSqlQueries['do_add_komp_mutasi'], array($id,$idStruk,$dateNow));	  
      $result = $this->EndTrans(true);
      return $result;
  }
  
  function UpdateDataMutasi($idStruk,$dateNow,$id,$struk){
    	$result = $this->Execute($this->mSqlQueries['do_update_komp_mutasi'], array($idStruk,$dateNow,$id,$struk));	  
      return $result;
      //print_r($this->getLastError());exit;
  }

  function GetCheckPegawaiNonJob($id){
      $result = $this->Open($this->mSqlQueries['pegawai_is_non_job'], array($id)); 
      $result = $result[0]['found'];
      return $result;
      
  }


}
?>
