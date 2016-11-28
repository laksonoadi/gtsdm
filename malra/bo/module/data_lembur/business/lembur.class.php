<?php

class Lembur extends Database {

	protected $mSqlFile= 'module/data_lembur/business/lembur.sql.php';
   
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
  
//==GET== 
	function GetCount($nip_nama='') {
		if($nip_nama != ""){
			$str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
		}else{
			$str = "";
		}
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_count1'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
		if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
	}
   
	function GetData ($offset, $limit, $nip_nama='') { 
		if($nip_nama != ""){
			$str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
		}else{
			$str = "";
		}
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data1'], array($str, $offset, $limit));
  		return $this->Open(stripslashes($result), array());    
	}
   
	function GetCountLembur($idPeg, $tampil) {
		if($tampil != "all"){
			$str = " AND lemburStatus = '".$tampil."'";
		}else{
			$str = "";
		}
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_count2'], array($idPeg, $str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
		if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
	}
   
	function GetDataLembur($offset, $limit, $idPeg, $tampil) { 
		if($tampil != "all"){
			$str = " AND lemburStatus = '".$tampil."'";
		}else{
			$str = "";
		}
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data2'], array('%H:%i','%H:%i','%H:%i',$idPeg, $str, $offset, $limit));
  		//print_r(stripslashes($result));
		return $this->Open(stripslashes($result), array());    
	}
   
	function GetDataById($id) {      
		$result[0] = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));
		$result[1] = $this->Open($this->mSqlQueries['get_spv_by_spv_id'], array($result[0][0]['id_spv']));
		$result[2] = $this->Open($this->mSqlQueries['get_mor_by_mor_id'], array($result[0][0]['id_mor']));
		if($result)
			#return $result[0];
			return $result;
		else
			return $result;	  
	}
   
	function GetDataLemburDet($id) {      
		$result = $this->Open($this->mSqlQueries['get_data_lembur_det'], array('%H:%i','%H:%i','%H:%i',$id)); 
		//print_r($this->getLastError());exit;
	    return $result;	  
	}
   
	function CekNmrLembur($nmr){
		$result = $this->Open($this->mSqlQueries['cek_nmr_lembur'], array($nmr));
		return $result;
	}
   
	function GetTahunNo(){
		$result = $this->Open($this->mSqlQueries['get_tahun_no'], array());
		return $result;
	}
   
	function GetNoBaru($tahun){
		$result = $this->Open($this->mSqlQueries['get_no_baru'], array($tahun));
		return $result;
	}
   
	function GetNumber($tipe){
		$result = $this->Open($this->mSqlQueries['get_sql_generate_number'], array($tipe));
		$result =  $this->open($result['0']['formatNumberFormula'],array());
		return $result['0']['number'];
	}
   
//==DO==
	function Add($data) {	   
		$return = $this->Execute($this->mSqlQueries['do_add'], $data);	
		//print_r($this->getLastError());exit;  
		return $return;
	}
   
	function Update($data) {
		$return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		//print_r($this->getLastError());exit;  
		return $return;
	}
   
	function Delete($id) {
		$result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
		//print_r($this->getLastError());exit;	
		return $result;
	}
}
?>
