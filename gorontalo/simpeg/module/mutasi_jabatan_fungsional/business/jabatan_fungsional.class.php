<?php

class JabatanFungsional extends Database {

   protected $mSqlFile= 'module/mutasi_jabatan_fungsional/business/jabatan_fungsional.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //     
   }
   
   function GetQueryKeren($sql,$params) {
		foreach ($params as $k => $v) {
			if (is_array($v)) {
				$params[$k] = '~~' . join("~~,~~", $v) . '~~';
				$params[$k] = str_replace('~~', '\'', addslashes($params[$k]));
			} else {
				$params[$k] = addslashes($params[$k]);
			}
		}
		$param_serialized = '~~' . join("~~,~~", $params) . '~~';
		$param_serialized = str_replace('~~', '\'', addslashes($param_serialized));
		eval('$sql_parsed = sprintf("' . $sql . '", ' . $param_serialized . ');');
		//echo $sql_parsed;
		return $sql_parsed;
	}
//get data profil   
   function GetListPegawai($tampilkan, $start, $limit) {   
     $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
     return $result;
   }
   
   function GetDataDetail ($id) { 
     
      $arg='';
	  $params = array();
	  if($id!=NULL){
	     $arg.='AND pegId=\'%s\' ';
		 $params[]=$id;
	  }
	  
      $result = $this->Open($this->mSqlQueries['get_data_pegawai_detail'].$arg,$params);
      return $result;
   }

   function GetCount($tampilkan) {
     $result = $this->Open($this->mSqlQueries['get_count_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%'));
     return $result[0]['total'];     
   }
   //get data jabatan
   function GetJabatan ($offset, $limit, $pegId) { 
     $arg='AND a.jbtnPegKode= \'%s\' ORDER BY a.jbtnTglMulai DESC ';
	 $params=array($pegId);
	 
	  if($limit!=NULL ){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
	  //echo(vsprintf($this->mSqlQueries['get_jabatan_detail'].$arg,$params));
      $result = $this->Open($this->mSqlQueries['get_jabatan_detail'].$arg,$params);
      return $result;
   }
   
   function GetJabatanDetail ($pegId,$id=NULL) { 
     
     $arg='AND a.jbtnPegKode= \'%s\' ';
	 $params=array($pegId);
	  if($id!=NULL){
	     $arg.='AND a.jbtnId=\'%s\' ';
		 $params[]=$id;
	  }
	  
      $result = $this->Open($this->mSqlQueries['get_jabatan_detail'].$arg,$params);
      return $result;
   }

   function GetCountJabatan ($pegId) {
     $arg='AND a.jbtnPegKode= \'%s\' ';
	 $params=array($pegId);
	 
     $result = $this->Open($this->mSqlQueries['get_count_jabatan'].$arg, $params);  
     if (!$result)
       return 0;
     else
       return $result[0]['total'];    
   }
   function GetRefJabatan($id=NULL){
      $arg='';
	  $params=array();
	  if($id !=NULL){
		$arg='WHERE jabfungrId = \'%s\' ';
		$params[] = $id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_ref_jabatan'].$arg,$params);
      return $result;
   }
   
   function GetRefGolongan($id=NULL){
      $arg='';
	  $params=array();
	  if($id !=NULL){
		$arg='WHERE pktgolrId = \'%s\' ';
		$params[] = $id;
	  }
	  $result = $this->Open($this->mSqlQueries['get_ref_golongan'].$arg,$params);
      return $result;
   }
   
   function GetComboPangkatGolongan($id) {
		$result = $this->Open($this->mSqlQueries['get_combo_golongan'], array($id));
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetIdFung($id) {      
      $result = $this->Open($this->mSqlQueries['get_id_fung'], array($id)); 
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
      $id = $id['idDelete'];
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
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
	
	function AddDataMutasi($id,$idFung1,$idFung2,$dateNow,$idLain){	
      $this->StartTrans();
      if(!empty($idLain)){
    		for ($i=0; $i<sizeof($idLain); $i++){
    		  $this->Execute($this->mSqlQueries['do_delete_komp_mutasi'], array($id,$idLain[$i]['komp1']));
    		  $this->Execute($this->mSqlQueries['do_delete_komp_mutasi'], array($id,$idLain[$i]['komp2']));
    	  }
  	  }
    	$this->Execute($this->mSqlQueries['do_add_komp_mutasi'], array($id,$idFung1,$dateNow));	  
      $this->Execute($this->mSqlQueries['do_add_komp_mutasi'], array($id,$idFung2,$dateNow));	  
      $result = $this->EndTrans(true);
      //print_r($this->getLastError());exit;
      return $result;
  }
  
  function UpdateDataMutasi($idFung1,$idFung2,$dateNow,$id,$fung1,$fung2){
    	$this->StartTrans();
      $this->Execute($this->mSqlQueries['do_update_komp_mutasi'], array($idFung1,$dateNow,$id,$fung1));
      $this->Execute($this->mSqlQueries['do_update_komp_mutasi'], array($idFung2,$dateNow,$id,$fung2));	  
      $result = $this->EndTrans(true);
      return $result;
      //print_r($this->getLastError());exit;
  }

  function GetJenStrukById($id1){
     $result = $this->Open($this->mSqlQueries['get_list_fungsional_by_id'],array($id1));
      return $result;
  }
}
?>
