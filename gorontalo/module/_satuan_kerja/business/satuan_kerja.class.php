<?php

class SatuanKerja extends Database {

   protected $mSqlFile= 'module/satuan_kerja/business/satuan_kerja.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);       
   }
   
   function GetDataSearch($offset, $limit, $data){
      $input=$data['input'];
      $arg='';
	  $params = array("%$input%");
	  if($limit!=NULL AND $data != NULL){
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
   
   function GetSatKerLevel($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level'], array($level));
	  return $result[0];
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
       $params=array($data['level'],$data['unit'],$data['nama'],$data['user']);
      $return = $this->Execute($this->mSqlQueries['insert_satker'],$params); 
      return $return;
   }

    function Update($data) {
       $params=array($data['level'],$data['unit'],$data['nama'],$data['user'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['update_satker'],$params); 
      return $return;
   }

    function Delete($id) {
      $id = $id['idDelete'];
	  $detail=$this->GetSatKerDetail($id);
	  $level = $detail['satkerLevel'];
	  //print_r($level);exit();
	   $ret = $this->Execute($this->mSqlQueries['delete_satker'], array("$level%"));
       return $ret;
	}   

   
 }
?>
