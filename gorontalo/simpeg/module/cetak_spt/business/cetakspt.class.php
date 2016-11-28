<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class CetakSpt extends Database {

   protected $mSqlFile= 'module/cetak_spt/business/cetakspt.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
   }
     
   function GetListPegawai($tampilkan, $start, $limit) {   
      $result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
      return $result;
   }
   
   function GetDataDetail($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
      return $result;
   }
   
   function GetListMutasiSatuanKerja($id) {
      $result = $this->Open($this->mSqlQueries['get_list_mutasi_satuan_kerja_pegawai'], array($id));
      return $result;
   }
   
   function GetDataMutasiById($id,$dataId) {
      $result = $this->Open($this->mSqlQueries['get_data_mutasi_satuan_kerja_pegawai_by_id'], array($id,$dataId));
      return $result;
   }

   function GetDataSptById($id) {
      $result = $this->Open($this->mSqlQueries['get_detail_spt'], array($id));
      return $result;
   }

   function GetEselonById($id) {
      $result = $this->Open($this->mSqlQueries['get_eselon'], array($id));
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
   
   function GetComboSatuanKerja() {
		//$result = $this->Open($this->mSqlQueries['get_combo_satuan_kerja'], array());
		$this->Obj = new SatuanKerja();
	    $result = $this->Obj->GetSatuanKerjaByUserId();
		return $result;
   }
   
   function GetComboTreeSatuanKerja($unit_id = NULL, $parent = 0, $level = 0) {
      // Recursive function
      // Not really recommended because this makes multiple function call (overhead)
      //  and a SQL query execution each time
      // But this is done to ensure correct ordering
      $where = '';
      if(!empty($unit_id)) {
         $where .= sprintf(' AND satkerUnitId = %d ', $unit_id);
      }
      $query = $this->mSqlQueries['get_combo_tree_satuan_kerja'];
      $query = str_replace('--where--', $where, $query);
      $list = $this->Open(stripslashes($query), array($parent));
      
      $tree = array();
      $tmpTree = array();
      
      foreach($list as $item) {
         $children = $this->GetComboTreeSatuanKerja($unit_id, $item['id'], $level+1);
         $item['name'] = str_repeat('&nbsp;&nbsp;&nbsp;', $level) . $item['nama'];
         $tmpTree[] = array_merge(array($item), $children);
      }
      
      foreach($tmpTree as $item) {
         $tree = array_merge($tree, $item);
      }
      
      return $tree;
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
		 ////exit;
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
      //exit;
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

   function GetMaxSptId(){
      $result = $this->Open($this->mSqlQueries['get_max_spt_id'],array());
      return $result;
   }

   

   function GetComboJabatanStruktural() {
      $result = $this->Open($this->mSqlQueries['get_combo_jabstruk'], array());
      //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
      return $result;
   }

   function GetJabatanStrukturalDefault() {
      $result = $this->Open($this->mSqlQueries['get_combo_jabstruk_default'], array());
      //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
      return $result;
   }

   function GetDataLastJabatan($id,$dataId) {
      $result = $this->Open($this->mSqlQueries['get_data_mutasi_satuan_kerja_pegawai_by_id'], array($id,$dataId));
      return $result;
   }

   function GetDataDetailPegawai($id) { 
    $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
    return $result[0];
   }

     function GetListJenisKepegawaian(){
     
      $result = $this->Open($this->mSqlQueries['get_combo_jenis_kepegawaian'], array());
    return $result;
   }

    function GetDataListJabatan($id) { 
    $result = $this->Open($this->mSqlQueries['get_list_jabatan'], array($id));
    return $result;
   }

   function GetUnitSpt($id) { 
    $result = $this->Open($this->mSqlQueries['get_unit_spt'], array($id));
    return $result;
   }

   function GetUnitSptKetua($id) { 
    $result = $this->Open($this->mSqlQueries['get_unit_spt_ketua'], array($id));
    return $result;
   }
   

   function AddKetua($data) {     
      $return = $this->Execute($this->mSqlQueries['do_add_ketua'], $data);
      //exit;   
      return $return;
   }  
  
   function UpdateKetua($data) {
     $return = $this->Execute($this->mSqlQueries['do_update_ketua'], $data);
    //$this->mdebug();  
     ////exit;
      return $return;
   }   
   
   function GetDataPegawaiDetailSPTKetua($id) { 
    $result = $this->Open($this->mSqlQueries['get_data_spt_ketua'], array($id));
    return $result[0];
   }

}
?>
