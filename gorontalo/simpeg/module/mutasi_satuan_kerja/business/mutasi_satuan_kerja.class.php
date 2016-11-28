<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class MutasiSatuanKerja extends Database {

   protected $mSqlFile= 'module/mutasi_satuan_kerja/business/mutasi_satuan_kerja.sql.php';
   
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

   function GetComboJabatanStruktural($id) {
      $result = $this->Open($this->mSqlQueries['get_combo_jabstruk'], array($id));
      //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
      return $result;
   }

   function GetJabBySatker($id) {
      $result = $this->Open($this->mSqlQueries['get_jab_by_satker'], array($id));
      //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
      return $result;
   }

   function GetKepalaSatker($id) {
      $result = $this->Open($this->mSqlQueries['get_kepala_satker'], array($id));
      //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
      return $result;
   }
   
}
?>
