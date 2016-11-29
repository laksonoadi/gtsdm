<?php

class Benefit extends Database {

   protected $mSqlFile= 'module/data_benefit/business/benefit.sql.php';
   
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
   
   function GetCountBenefit($idPeg, $tampil) {
      if($tampil != "all"){
  		  $str = " AND benefitStatus = '".$tampil."'";
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
   
   function GetDataBenefit($offset, $limit, $idPeg, $tampil) { 
      if($tampil != "all"){
  		  $str = " AND benefitStatus = '".$tampil."'";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data2'], array($idPeg, $str, $offset, $limit));
  		//print_r(stripslashes($result));
      return $this->Open(stripslashes($result), array());    
   }
   
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetDetailPegawaiById($id) {      
      $result = $this->Open($this->mSqlQueries['get_detail_pegawai_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   function GetDataBenefitDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_benefit_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetDataKlaimFromBenefitId($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_klaim_from_benefit_id'], array($id)); 
      //print_r($this->getLastError());exit;
	    return $result; 	  
   }
   
   function GetBalanceBenefitLeft($pegId){
      $result = $this->Open($this->mSqlQueries['get_balance_benefit_left'], array($pegId)); 
      //print_r($this->getLastError());exit;
	    if($result)
	     return $result[0];
	    else
	     return $result;;                                  
   }
   
   function GetComboJenisBenefit(){
     $result = $this->Open($this->mSqlQueries['get_combo_jenis_benefit'], array());
	   return $result;
   }
   
   function GetJenisBenefitById($id){
     $result = $this->Open($this->mSqlQueries['get_jenis_benefit_by_id'], array($id));
	   return $result[0]['name'];
   }
   
   function GetComboJenisKlaim(){
     $result = $this->Open($this->mSqlQueries['get_combo_jenis_klaim'], array());
	   return $result;
   }
   
   function CekNmrBenefit($nmr){
     $result = $this->Open($this->mSqlQueries['cek_nmr_benefit'], array($nmr));
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
   
   function GetLastId(){
     $result = $this->Open($this->mSqlQueries['get_last_id'], array());
	   return $result;
   }
   
//==DO==
   function Add($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddKlaim($benefitId,$tipe,$nilai,$file) {	  
      //echo sprintf($this->mSqlQueries['do_add_klaim'], $benefitId,$tipe,$nilai,$file); 
      $return = $this->Execute($this->mSqlQueries['do_add_klaim'], array($benefitId,$tipe,$nilai,$file));
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function Update($data) {
      //echo vsprintf($this->mSqlQueries['do_update'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateApproval($data) {
      //echo vsprintf($this->mSqlQueries['do_update_approval'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_approval'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
      $this->DeleteKlaim($id);
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteKlaim($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_klaim'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function UpdateNilaiKlaim($nilai,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_nilai_klaim'], array($nilai,$id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function GetBalanceBenefitByPegId($pegId){
     $result = $this->Open($this->mSqlQueries['get_balance_benefit_by_peg_id'], array($pegId));
	   return $result;
   }
   
   function UpdateBalanceBenefitDiambil($durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_benefit_diambil'], array($durasi,$pegId,$perId));
      return $return;
   }
   
   function UpdateBalanceBenefitDiambilTambah($durasi,$durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_benefit_diambil_tambah_by_id'], array($durasi,$pegId,$perId));
      return $return;
   }
   
   function UpdateBalanceBenefitDiambilKurang($durasi,$durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_benefit_diambil_kurang_by_id'], array($durasi,$pegId,$perId));
      return $return;
   }
   
   function num_toprocess($num) {
      // ex : 2.980,87 -> 2980.87
      $num = str_replace(array(".", " "), "", $num);
      $num = str_replace(",", ".", $num);
      if (is_numeric($num)) {
         return $num;
      }
      return 0;
   }
   
   
   function num_todisplay($num, $dfixed=true, $ddec=2) {
      // ex :  2980.87 -> 2.980,87
      if (is_numeric($num)) {
         $check = explode(".", $num);
         $dec = (isset($check[1])) ? strlen($check[1]) : 0;
         if ($dfixed == true) $dec = $ddec;
         $num = number_format($num, $dec, ',', '.');
      }
      return $num;
   }
}
?>
