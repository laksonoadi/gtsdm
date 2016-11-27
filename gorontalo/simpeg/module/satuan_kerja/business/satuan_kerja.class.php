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
      // Get unit of the id, and all its descendants
      // Exclusive get of a combo branch
      $tree = array();
      if($id != 0) {
          $result = $this->Open($this->mSqlQueries['get_combo_satuan_kerja_by_id'], array($id));
          $tree[] = $result[0];
          $parent_id = $result[0]['id'];
      } else {
          $parent_id = 0;
      }
      $tree = array_merge($tree, $this->GetComboSatuanKerja($unit_id, $parent_id, '', 1));
      return $tree;
   }
   
   function GetComboSatuanKerja($unit_id = NULL, $parent = 0, $allowed_list = '', $_level = 0) {
      // Recursive function
      // Not really recommended because this makes multiple function call (overhead)
      //  and a SQL query execution each time
      // But this is done to ensure correct ordering
      $where = '';
      if(!empty($unit_id)) {
         $where .= sprintf(' AND satkerUnitId = %s ', $unit_id);
      }
		/* if($allowed_list !== '') {
			$where .= sprintf(' AND satkerParentId IN(%s) ', $allowed_list);
		} */
      $query = $this->mSqlQueries['get_combo_satuan_kerja'];
      $query = str_replace('--where--', $where, $query);
      $list = $this->Open(stripslashes($query), array($parent));
		// var_dump(vsprintf(stripslashes($query), array($parent)), $where);
		// exit;
      
      $tree = array();
      $tmpTree = array();
		$use_allowed = ($allowed_list !== '');
		$allowed = $use_allowed ? explode(',', $allowed_list) : array();
      
      foreach($list as $item) {
         $children = $this->GetComboSatuanKerja(NULL, $item['id'], $allowed_list, $_level+1);
         $item['name'] = str_repeat('&nbsp;&nbsp;&nbsp;', $_level) . $item['nama'];
			if(!$use_allowed) {
				$tmpTree[] = array_merge(array($item), $children);
			} else {
				switch(true) {
					case (count($children) > 0):
						// $item['combo_disabled'] = TRUE;
					case in_array($item['id'], $allowed):
						$tmpTree[] = array_merge(array($item), $children);
						break;
					default:
						$tmpTree[] = $children;
						break;
				}
			}
      }
      
      foreach($tmpTree as $item) {
         $tree = array_merge($tree, $item);
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

    function Delete($id) {
      $id = $id['idDelete'];
	  $detail=$this->GetSatKerDetail($id);
	  $level = $detail['satkerLevel'];
	 // print_r($level);
	   $ret = $this->Execute($this->mSqlQueries['delete_satker'], array("$level"));
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
