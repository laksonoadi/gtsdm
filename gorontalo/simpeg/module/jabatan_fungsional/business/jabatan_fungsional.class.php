<?php

class JabatanFungsional extends Database {

   protected $mSqlFile= 'module/jabatan_fungsional/business/jabatan_fungsional.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);     
   }
//==GET==      
   function GetData ($offset, $limit, $data) { 
     
      $arg='WHERE a.jabfungrNama LIKE %s ';
	  $params = array("%$data%");
	  
	  if($limit !== NULL AND $offset !== NULL){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
      $result = $this->Open($this->mSqlQueries['get_data'].$arg,$params);
      return $result;
   }
   
   function GetDataDetail ($id) { 
     
      $arg='';
	  $params = array();
	  if($id!=NULL){
	     $arg.='WHERE a.jabfungrId=\'%s\' ';
		 $params[]=$id;
	  }
	  
      $result = $this->Open($this->mSqlQueries['get_data'].$arg,$params);
      return $result;
   }

   function GetCount ($data) {
     $result = $this->Open($this->mSqlQueries['get_count'], array("%$data%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['total'];    
   }
   
  function GetKomponenGaji($id=NULL){
     $arg='';
	 $params = array();
	 if($id!=NULL){
	     $arg.='WHERE kompgajiId=\'%s\' ';
		 $params[]=$id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_komponen_gaji'].$arg,$params);
      return $result;
  }
  
  function GetJenisJabatan($id=NULL){
     $arg='';
	 $params = array();
	 if($id!=NULL){
	     $arg.='WHERE jabfungjenisrId=\'%s\' ';
		 $params[]=$id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_jenis_jabatan'].$arg,$params);
      return $result;
  }
   
   function GetGajiDetail($gaji,$id=NULL){
     $arg='';
	 $params = array();
	 //if($gaji!=NULL){
	     $arg.='AND kompgajidtKompgajiId=\'%s\' ';
		 $params[]=$gaji;
	  //}
	 if($id!=NULL){
	     $arg.='AND kompgajidtId=\'%s\' ';
		 $params[]=$id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_gaji_detail'].$arg,$params);
      return $result;
  }
  
  function GetGajiDetailAll($id=NULL){
     $arg='';
	 $params = array();
	 
	 if($id!=NULL){
	     $arg.='AND kompgajidtId=\'%s\' ';
		 $params[]=$id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_gaji_detail'].$arg,$params);
      return $result;
  }
  
  function GetGajiDetailRange($gaji,$start,$offset){
     
	 
	 	$arg='AND kompgajidtKompgajiId=\'%s\' ';
		$params = array($gaji);
		$arg.="LIMIT %s,%s ";
		array_push($params,$start,$offset);
	  $result = $this->Open($this->mSqlQueries['get_gaji_detail'].$arg,$params);
      return $result;
  }
   
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
      $id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
       return $ret;
	}
}
?>
