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
   
   function GetComboTreeSatuanKerja($unit_id = NULL, $id = 0) {
      $query = $this->mSqlQueries['get_combo_satker_order_level'];
      $where = '';
      if($id != 0) {
          $where .= ' AND (a.satkerId = b.satkerId OR a.satkerLevel LIKE CONCAT(b.satkerLevel, ".%%")) ';
      }
      if(!empty($unit_id)) {
         $where .= sprintf(' AND a.satkerUnitId = "%s" ', $unit_id);
      }
      $query = str_replace('--where--', $where, $query);
      $data = $this->Open($query, array($id));
      if(empty($data))
          return $data;
      
      // Start sorting out combo
      $str_lvl_offset = $data[0]['lv']; // Find the fewest level
      $combo = array();
      $pos = array();
      foreach($data as $k => $v) {
          if($v['lv'] == 0)
              $parent_level = $v['parentId'];
          else
              $parent_level = substr($v['level'], 0, strrpos($v['level'], '.'));
          
          if(!isset($pos[$parent_level])) {
              $pos[$parent_level] = $k;
              $position = $k;
          } else {
              $position = ++$pos[$parent_level];
          }
          foreach($pos as $pk => $pv) {
              if($pos[$pk] >= $position && $pk != $parent_level)
                  $pos[$pk]++;
          }
          $pos[$v['level']] = $position;
          $v['name'] = str_repeat('&nbsp;&nbsp;&nbsp;', max($v['lv'] - $str_lvl_offset, 0)) . $v['nama'];
          array_splice($combo, $position, 0, array($v));
      }
      return $combo;
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

   function GetComboPangkatGolonganAll() {
    $result = $this->Open($this->mSqlQueries['get_combo_golongan_all'], array());
    //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
    return $result;
   }

   function GetPangkatGolonganName($id) {
    $result = $this->Open($this->mSqlQueries['get_pangkat_gol_name'], array($id));
    //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
    return $result;
   }

    function GetLastMutasiUnitKerja($id) {
        $result = $this->Open($this->mSqlQueries['get_last_mutasi_satker_pegawai'], array($id));
        if(is_array($result)) {
            if(count($result) > 0) {
                // print_r($result);
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
