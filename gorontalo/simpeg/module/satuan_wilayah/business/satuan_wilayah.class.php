<?php

class SatuanWilayah extends Database {

   protected $mSqlFile= 'module/satuan_wilayah/business/satuan_wilayah.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);       
   }
   
   function GetDataSearch($offset, $limit, $data){
      $input=$data['input'];
      $arg='';
	  $params = array("%$input%");
	  if($limit !== NULL AND $offset !== NULL){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
	  $result = $this->Open($this->mSqlQueries['get_list_satwil_nama'].$arg,$params);
      return $result;
   }
   
   function GetCount ($data) {
     //echo($this->mSqlQueries['count_list_satwil_nama']);print_r(array("%$data%"));
	 $input=$data['input'];
     $result = $this->Open($this->mSqlQueries['count_list_satwil_nama'], array("%$input%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['TOTAL'];    
   }
   
   function GetListSatWil(){
     
      $result = $this->Open($this->mSqlQueries['get_list_satwil'], array());
	  return $result;
   }
   
   function GetSatWilLevel($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level'], array($level));
	  return $result[0];
   }
   
   function GetSatWilByLevel($level){
      $result = $this->Open($this->mSqlQueries['get_satwil_by_level'], array("$level%"));
	  return $result;
   }
   
   function GetSatWilDetail($id){
      $result = $this->Open($this->mSqlQueries['get_satwil_detail'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;
   }
   
   function GetComboSatWil(){
      $result = $this->Open($this->mSqlQueries['get_combo_satwil'], array());
	  return $result;
   }
   
   /*function GetComboUnit(){
      $result = $this->Open($this->mSqlQueries['get_combo_unit'], array());
	  return $result;
   }*/
   
   function Add($data) {
       $params=array($data['level'],$data['kode'],$data['nama'],$data['user']);
      $return = $this->Execute($this->mSqlQueries['insert_satwil'],$params); 
      return $return;
   }

    function Update($data) {
       $params=array($data['level'],$data['kode'],$data['nama'],$data['user'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['update_satwil'],$params); 
      return $return;
   }

    function Delete($id) {
      $id = $id['idDelete'];
	  $detail=$this->GetSatWilDetail($id);
	  $level = $detail['satwilLevel'];
	  //print_r($level);exit();
	   $ret = $this->Execute($this->mSqlQueries['delete_satwil'], array("$level%"));
       return $ret;
	}   

   
 }
?>
