<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';

class SatuanKerja extends Database {

   protected $mSqlFile= 'module/satuan_kerja/business/satuan_kerja.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber); 
	  $this->Obj = new DataPegawai();      
   }
   
   function GetListIdAnakUnitByUnitId($unitId,$nama=''){
	  $list='0';
	  $data=$this->Open($this->mSqlQueries['get_list_id_anak_unit_by_unit_id'], array($unitId,$unitId,'%'.$nama.'%'));
	  for ($i=0; $i<sizeof($data); $i++){
		$list .=','.$data[$i]['id'];
	  }
	  return $list;
   }
   
   function GetSatuanKerjaByUserId($nama=''){
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->Obj->GetUserIdByUserName();
      
      // echo $nama;
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
      
      }

		$unitgrup = array();
      if($result){
			$satker = $result[0]['satkerId'];
			$satlev = $result[0]['satkerLevel'];
			foreach ($result as $key => $value) {
			  $unitgrup[] = $value['satkerId'];
			}
      }
		$resultunitgroup = implode(',', $unitgrup);

		// var_dump(count($result), $resultunitgroup);
		// $result = $this->GetComboSatuanKerja(NULL, 0, $resultunitgroup);
		$result = $this->GetComboTreeSatuanKerja(NULL, $satker);
      
  	  return $result; 
   }
   
   function GetDataSearch($offset, $limit, $data){
      $input=$data['input'];
      $arg='';
	  $params = array("%$input%");
	  if($limit !== NULL AND $offset !== NULL){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
	  $result = $this->Open($this->mSqlQueries['get_list_satker_nama'].$arg,$params);
      return $result;
   }
   
   function GetCount ($data) {
     //echo($this->mSqlQueries['count_list_satker_nama']);print_r(array("%$data%"));
	 $input=$data['input'];
     $result = $this->Open($this->mSqlQueries['count_list_satker_nama'], array("%$input%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['TOTAL'];    
   }
   
   function GetListSatKer(){
     
      $result = $this->Open($this->mSqlQueries['get_list_satker'], array());
	  return $result;
   }

    function GetListJenisKepegawaian(){
     
      $result = $this->Open($this->mSqlQueries['get_combo_jenis_kepegawaian'], array());
    return $result;
   }
   
   function GetSatKerLevel($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level'], array($level));
	  return $result[0];
   }

      function GetSatKerLevelNew($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level_new'], array($level));
    return $result[0];
   }

   
    function GetSatkerAndLevel() {
        $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
        return $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
    }
   
   function GetSatKerByLevel($level){
      $result = $this->Open($this->mSqlQueries['get_satker_by_level'], array("$level%"));
	  return $result;
   }
   
   function GetSatkerByParentId($parentId){
      $result = $this->Open($this->mSqlQueries['get_satker_by_parent_id'], array($parentId));
	  return $result;
   }
   
   function GetSatKerDetail($id){
      $result = $this->Open($this->mSqlQueries['get_satker_detail'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;
   }
   
   function GetComboSatKer(){
      $result = $this->Open($this->mSqlQueries['get_combo_satker'], array());
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
      
      // For styling only; could be omitted
      $tmp = $this->Open($this->mSqlQueries['get_satker_per_unit'], array());
      // Make 1-dimensional array containing only the ids
      $unit_satker = array();
      foreach($tmp as $k => $row) {
          $unit_satker[$k] = $row['satkerId'];
      }
      
      // Start sorting out combo
      $str_lvl_offset = $data[0]['lv']; // Find the fewest level
      $combo = array();
      $pos_all = array();
      $pos_child = array();
      foreach($data as $k => $v) {
          if($v['lv'] == 0)
              $parent_level = $v['parentId'];
          else
              $parent_level = substr($v['level'], 0, strrpos($v['level'], '.'));
          
          if(!isset($pos_all[$parent_level])) {
              $pos_all[$parent_level] = $k;
              $pos_child[$parent_level] = $k;
              $position = $k;
          } else {
              $position = ++$pos_child[$parent_level];
          }
          foreach($pos_all as $pk => $pv) {
              if($pos_all[$pk] >= $position && $pk != $parent_level) {
                  $pos_all[$pk]++;
                  $pos_child[$pk]++;
              }
          }
          $pos_all[$v['level']] = $position;
          $pos_child[$v['level']] = $position;
          $v['name'] = str_repeat('&nbsp;', max($v['lv'] - $str_lvl_offset, 0) * 3) . $v['nama'];
          // Style
          if(in_array($v['id'], $unit_satker)) {
              $v['style'] = 'font-size: 1.1em; font-weight: bold; background-color: #ddd;';
          }
          array_splice($combo, $position, 0, array($v));
      }
      return $combo;
   }
   
   function GetComboSatuanKerja($unit_id = NULL, $id = 0) {
      $query = $this->mSqlQueries['get_combo_satker_order_level'];
      $where = '';
      if($id != 0) {
          $where .= ' AND (a.satkerLevel LIKE CONCAT(b.satkerLevel, ".%%")) ';
      }
      if(!empty($unit_id)) {
         $where .= sprintf(' AND a.satkerUnitId = "%s" ', $unit_id);
      }
      $query = str_replace('--where--', $where, $query);
      $data = $this->Open($query, array($id));
      if(empty($data))
          return $data;
      
      // For styling only; could be omitted
      $tmp = $this->Open($this->mSqlQueries['get_satker_per_unit'], array());
      // Make 1-dimensional array containing only the ids
      $unit_satker = array();
      foreach($tmp as $k => $row) {
          $unit_satker[$k] = $row['satkerId'];
      }
      
      // Start sorting out combo
      $str_lvl_offset = $data[0]['lv']; // Find the fewest level
      $combo = array();
      $pos_all = array();
      $pos_child = array();
      foreach($data as $k => $v) {
          if($v['lv'] == 0)
              $parent_level = $v['parentId'];
          else
              $parent_level = substr($v['level'], 0, strrpos($v['level'], '.'));
          
          if(!isset($pos_all[$parent_level])) {
              $pos_all[$parent_level] = $k;
              $pos_child[$parent_level] = $k;
              $position = $k;
          } else {
              $position = ++$pos_child[$parent_level];
          }
          foreach($pos_all as $pk => $pv) {
              if($pos_all[$pk] >= $position && $pk != $parent_level) {
                  $pos_all[$pk]++;
                  $pos_child[$pk]++;
              }
          }
          $pos_all[$v['level']] = $position;
          $pos_child[$v['level']] = $position;
          $v['name'] = str_repeat('&nbsp;', max($v['lv'] - $str_lvl_offset, 0) * 3) . $v['nama'];
          // Style
          if(in_array($v['id'], $unit_satker)) {
              $v['style'] = 'font-size: 1.1em; font-weight: bold; background-color: #ddd;';
          }
          array_splice($combo, $position, 0, array($v));
      }
      return $combo;
   }
   
   function GetSatuanKerjaStructure($unit_id = NULL, $id = 0) {
      $query = $this->mSqlQueries['get_combo_satker_order_level'];
      $where = '';
      if($id != 0) {
          $where .= ' AND (a.satkerLevel LIKE CONCAT(b.satkerLevel, ".%%")) ';
      }
      if(!empty($unit_id)) {
         $where .= sprintf(' AND a.satkerUnitId = "%s" ', $unit_id);
      }
      $query = str_replace('--where--', $where, $query);
      $data = $this->Open($query, array($id));
      if(empty($data))
          return $data;
      
      // Start forming the structure
      // The following will produce a multilevel/multidimensional array
      $tree = array();
      $parent_path = array();
      foreach($data as $k => $v) {
          $v['children'] = array();
          
          if($v['lv'] == 0) {
              $index = (int) $v['level'];
              if(isset($tree[$index])) {
                  // Same index; put this one after the item with the same index,
                  // and adjust all following entries
                  $temp = $tree;
                  foreach($temp as $tkey => $tval) {
                      if($tkey > $index) {
                          $tree[$tkey + 1] = $tval;
                      }
                  }
                  $index++;
              }
              $tree[$index] = $v;
              $inserted = array_search($v, $tree, true);
              $parent_path[$v['level']] = array($inserted);
          } else {
              $index = (int) $v['child_index'];
              $ref = &$tree;
              // Get to the specified parent path
              foreach($parent_path[$v['parent_level']] as $arrkey) {
                  $ref = &$ref[$arrkey]['children'];
              }
              if(isset($ref[$index])) {
                  // Same index; put this one after the item with the same index,
                  // and adjust all following entries
                  $temp = $ref;
                  foreach($temp as $tkey => $tval) {
                      if($tkey > $index) {
                          $ref[$tkey + 1] = $tval;
                      }
                  }
                  $index++;
              }
              $ref[$index] = $v;
              $inserted = array_search($v, $ref, true);
              $parent_path[$v['level']] = array_merge($parent_path[$v['parent_level']], array($inserted));
          }
      }
      
      // The following reindexes the array to 0-based array
      $reindex = true;
      if($reindex) {
          // Reindex from the deepest level first to avoid missing array address
          foreach(array_reverse($parent_path, true) as $level => $location) {
              $ref = &$tree;
              foreach($location as $arrkey) {
                  $ref = &$ref[$arrkey]['children'];
              }
              if(!empty($ref)) {
                  $ref = array_values($ref);
              }
          }
          // Reindex top level
          $tree = array_values($tree);
      }
      
      return $tree;
   }
   
   function GetComboUnitSatker(){
      $result = $this->Open($this->mSqlQueries['get_combo_unit_satker'], array());
	  return $result;
   }
   
   function GetComboUnit(){
      $result = $this->Open($this->mSqlQueries['get_combo_unit'], array());
	  return $result;
   }
    
	function GetComboUserUnitKerja(){
        $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
        $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
        $satker = $result[0];
        
		$sql = $this->mSqlQueries['get_combo_user_unit_kerja'];
		$result = $this->Open($sql, array($satker['satkerId']));
		return $result;
	}
   
   function GetComboTipeStruktural(){
      $result = $this->Open($this->mSqlQueries['get_combo_tipe_struktural'], array());
	  return $result;
   }
   
   function GetListSkts(){
      $result = $this->Open($this->mSqlQueries['get_list_skts'], array());
	  return $result;
   }
   
   function Add($data) {
       $params=array($data['level'],$data['parent'],$data['unit'],$data['nama'],$data['struktural'],$data['user']);
      $return = $this->Execute($this->mSqlQueries['insert_satker'],$params); 
      return $return;
   }

    function Update($data) {
       $params=array($data['level'],$data['parent'],$data['unit'],$data['nama'],$data['struktural'],$data['user'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['update_satker'],$params); 
      return $return;
   }

   function UpdateWOLevel($data) {
       $params=array($data['parent'],$data['unit'],$data['nama'],$data['struktural'],$data['user'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['update_satker_wo_level'],$params); 
      return $return;
   }

    function UpdateDescendants($old_level, $new_level, $user = '') {
      if($user == '')
          $user = Security::Authentication()->GetCurrentUser()->GetUserId();
      
      $query = $this->mSqlQueries['update_satker_descendants'];
      $query = str_replace('--old_level--', $old_level, $query);
      $query = str_replace('--new_level--', $new_level, $query);
      
      $params=array($user);
      $return = $this->Execute($query, $params);
      return $return;
   }
   
    function CanDelete($id) {
        $id = $id['idDelete'];
        // Check pegawai
        $result = $this->Open($this->mSqlQueries['count_satker_pegawai'], array($id));
        if(!$result) {
            return FALSE;
        }
        
        $total = (int) $result[0]['total'];
        if($total > 0) {
            return 'Masih ada '.$total.' Pegawai yang memakai Satuan Kerja tersebut atau turunan dari Satuan Kerja tersebut.';
        }
        
        // Check jabstrukr
        $result = $this->Open($this->mSqlQueries['count_satker_jabstrukr'], array($id));
        if(!$result) {
            return FALSE;
        }
        
        $total = (int) $result[0]['total'];
        if($total > 0) {
            return 'Masih ada '.$total.' Jabatan Struktural yang memakai Satuan Kerja tersebut atau turunan dari Satuan Kerja tersebut.';
        }
        
        return TRUE;
    }
   
    function Delete($id) {
      $id = $id['idDelete'];
	  $detail=$this->GetSatKerDetail($id);
	  $level = $detail['satkerLevel'];
	 // print_r($level);
	   $ret = $this->Execute($this->mSqlQueries['delete_satker'], array($detail['satkerId'], $level));
     //         echo "<pre>";
     //         echo $this->getLastError();
     //         print_r($ret);
     //         echo "</pre>";
     // exit();
       return $ret;
	}   

     function GetListSatKerRefrensi(){
     
      $result = $this->Open($this->mSqlQueries['get_list_pub_satuan_kerja'], array());
    return $result;
   }

   function GetSatKerLevelRefrensi($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level_refrensi'], array($level));
    return $result[0];
   }

   function GetDataSatuanKerja($level){
      $result = $this->Open($this->mSqlQueries['get_data_satuan_kerja'], array($level));
    return $result[0];
   }

   function GetCekLevelParent($id,$level){
      $result = $this->Open($this->mSqlQueries['get_cek_level_satuan_kerja'], array($id,$level));
    return $result[0];
   }
  function GetListSatuanKerja($level){
    $result = $this->Open($this->mSqlQueries['get_list_satuan_kerja'], array($id,$level));
      return $result[0]; 
   }

    function UpdateNode($data) {
       $params=array($data['level'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['update_satker_node'],$params); 
      return $return;
   }

 }
?>
