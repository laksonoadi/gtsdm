<?php

class MutasiKgb extends Database {

   protected $mSqlFile= 'module/mutasi_kenaikan_gaji_berkala/business/mutasi_kgb.sql.php';
   
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
   
   function GetListMutasiKgb($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_kgb'], array($id));
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_kgb_by_id'], array($id,$dataId));
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
      
   function GetComboPangkatGolongan($id) {
		$result = $this->Open($this->mSqlQueries['get_combo_golongan'], array($id));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboPangkatGolonganAll($id) {
		$result = $this->Open($this->mSqlQueries['get_combo_golongan_all'], array($id));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetIdKomp($id1,$id2) {
    $result = $this->Open($this->mSqlQueries['get_id_komp'], array($id1,$id2));
    return $result;
   }
   
   function GetGajiByPangkatAndMk($pangkat, $masa_kerja) {
    $result = $this->Open($this->mSqlQueries['get_gaji_by_pkt_mk'], array($pangkat, $masa_kerja));
    return $result;
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

  function GetLastMutasiKgb($id) {
        $result = $this->Open($this->mSqlQueries['get_last_mutasi_kgb_pegawai'], array($id));
        if(is_array($result)) {
            if(count($result) > 0) {
                return $result[0]; // Newest item
            } else {
                return TRUE; // Not yet added
            }
        } else {
            return $result; // Fails
        }
  }
}
?>
