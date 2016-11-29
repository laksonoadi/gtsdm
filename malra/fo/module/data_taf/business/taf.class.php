<?php

class Taf extends Database {

   protected $mSqlFile= 'module/data_taf/business/taf.sql.php';
   
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
   
   function GetCountTaf($idPeg, $tampil) {
      if($tampil != "all"){
  		  $str = " AND tafStatus = '".$tampil."'";
      }else{
        $str = "";
      }
      
      //$result = $this->GetQueryKeren($this->mSqlQueries['get_count2'], array($idPeg, $str));
  		$res2 = $this->Open($this->mSqlQueries['get_count2'], array($idPeg));
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataTaf($offset, $limit, $idPeg, $tampil) { 
      if($tampil != "all"){
  		  $str = " AND tafStatus = '".$tampil."'";
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
   
   function GetDetailKebijakan($grade) {      
      $jenisTaf=$this->Open($this->mSqlQueries['get_jenis_taf'], array());      
      
      for ($i=0; $i<sizeof($jenisTaf); $i++){
        $zona=$this->Open($this->mSqlQueries['get_zona_by_jenis_taf'], array($jenisTaf[$i]['id']));
        
        for ($ii=0; $ii<sizeof($zona); $ii++){
          $kebijakan['jenis_taf'][$i][$ii]=$jenisTaf[$i]['id'];
          $kebijakan['zona'][$i][$ii]=$zona[$ii]['id'];
          $kebijakan['kebijakan'][$i][$ii]=$this->Open($this->mSqlQueries['get_kebijakan'], array($grade,$zona[$ii]['id']));  
        }  
      }  
      return $kebijakan;
   }
   
   function GetDetailZona(){      
      $zona=$this->Open($this->mSqlQueries['get_detail_zona'], array());
      return $zona;
   }
   
   function GetDataTafDet($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_taf_det'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetTravelByTafId($id) {      
      $result = $this->Open($this->mSqlQueries['get_travel_by_taf_id'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetTransportByTafId($id) {      
      $result = $this->Open($this->mSqlQueries['get_transport_by_taf_id'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetAllowanceByTafId($id) {      
      $result = $this->Open($this->mSqlQueries['get_allowance_by_taf_id'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetBudgetByTafId($id) {      
      $result = $this->Open($this->mSqlQueries['get_budget_by_taf_id'], array($id)); 
      //print_r($this->getLastError());exit;
	     return $result;	  
   }
   
   function GetComboTipe(){
     $result = $this->Open($this->mSqlQueries['get_combo_tipe'], array());
	   return $result;
   }
   
   function GetComboTipeTransportasi(){
     $result = $this->Open($this->mSqlQueries['get_combo_tipe_transportasi'], array());
	   return $result;
   }
   
   function GetComboTujuan($tipe){
     $result = $this->Open($this->mSqlQueries['get_combo_tujuan'], array($tipe));
	   return $result;
   }
   
   function GetComboBudget(){
     $result = $this->Open($this->mSqlQueries['get_combo_budget'], array());
	   return $result;
   }
   
   function GetComboTipeAllowance(){
     $result = $this->Open($this->mSqlQueries['get_combo_tipe_allowance'], array());
	   return $result;
   }
   
   function GetJenisTafById($id){
     $result = $this->Open($this->mSqlQueries['get_jenis_taf_by_id'], array($id));
	   return $result[0]['name'];
   }
   
   function GetCurrByJenisTafId($id){
     $result = $this->Open($this->mSqlQueries['get_curr_by_jenis_taf_id'], array($id));
	   return $result[0]['name'];
   }
   
   function GetComboJenisKlaim(){
     $result = $this->Open($this->mSqlQueries['get_combo_jenis_klaim'], array());
	   return $result;
   }
   
   function CekNmrTaf($nmr){
     $result = $this->Open($this->mSqlQueries['cek_nmr_taf'], array($nmr));
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
   
   function GetLastIdTujuan(){
     $result = $this->Open($this->mSqlQueries['get_last_id_tujuan'], array());
	   return $result;
   }
   
//==DO==
   function Add($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddTujuan($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add_tujuan'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add_tujuan'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddAnggaran($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add_anggaran'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add_anggaran'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddTransportasi($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add_transportasi'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add_transportasi'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddBudget($data) {	  
      //echo vsprintf($this->mSqlQueries['do_add_budget'], $data); 
      $return = $this->Execute($this->mSqlQueries['do_add_budget'], $data);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   
   
   function Update($data) {
      //echo vsprintf($this->mSqlQueries['do_update'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateTujuan($data) {
      //echo vsprintf($this->mSqlQueries['do_update_tujuan'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_tujuan'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateTransportasi($data) {
      //echo vsprintf($this->mSqlQueries['do_update_transportasi'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_transportasi'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateAnggaran($data) {
      //echo vsprintf($this->mSqlQueries['do_update_anggaran'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_anggaran'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateAnggaranApproval($data) {
      //echo vsprintf($this->mSqlQueries['do_update_anggaran_approval'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_anggaran_approval'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateBudget($data) {
      //echo vsprintf($this->mSqlQueries['do_update_budget'], $data); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_budget'], $data);         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function UpdateApprovalSupervisor($tafId,$status,$tgl_status) {
      //echo vsprintf($this->mSqlQueries['do_update_approval'], $tafId,$status,$tgl_status); exit();
      $return = $this->Execute($this->mSqlQueries['do_update_approval_supervisor'], array($status,$tgl_status,$tafId));         		  
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function Delete($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete'], array($id));
      $this->DeleteKlaim($id);
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteAllowance($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_allowance'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteTujuan($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_tujuan'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteTransportasi($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_transportasi'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteBudget($id) {
      $result = $this->Execute($this->mSqlQueries['do_delete_budget'], array($id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteAllowanceMassal($tafId,$arrId) {
      //echo sprintf($this->mSqlQueries['do_delete_allowance_massal'],$tafId,$arrId); exit();
      $result = $this->Execute($this->mSqlQueries['do_delete_allowance_massal'], array($tafId,$arrId));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteTravelMassal($tafId,$arrId) {
      //echo sprintf($this->mSqlQueries['do_delete_travel_massal'],$tafId,$arrId);
      $result = $this->Execute($this->mSqlQueries['do_delete_travel_massal'], array($tafId,$arrId));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteTransportMassal($tafId,$arrId) {
      //echo sprintf($this->mSqlQueries['do_delete_transport_massal'],$tafId,$arrId);
      $result = $this->Execute($this->mSqlQueries['do_delete_transport_massal'], array($tafId,$arrId));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function DeleteBudgetMassal($tafId,$arrId) {
      //echo sprintf($this->mSqlQueries['do_delete_budget_massal'],$tafId,$arrId);
      $result = $this->Execute($this->mSqlQueries['do_delete_budget_massal'], array($tafId,$arrId));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function UpdateNilaiKlaim($nilai,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_nilai_klaim'], array($nilai,$id));
      //print_r($this->getLastError());exit;	
      return $result;
   }
   
   function GetBalanceTafByPegId($pegId){
     $result = $this->Open($this->mSqlQueries['get_balance_taf_by_peg_id'], array($pegId));
	   return $result;
   }
   
   function UpdateBalanceTafDiambil($durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_taf_diambil'], array($durasi,$pegId,$perId));
      return $return;
   }
   
   function UpdateBalanceTafDiambilTambah($durasi,$durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_taf_diambil_tambah_by_id'], array($durasi,$pegId,$perId));
      return $return;
   }
   
   function UpdateBalanceTafDiambilKurang($durasi,$durasi,$pegId,$perId) {	   
      $return = $this->Execute($this->mSqlQueries['do_update_balance_taf_diambil_kurang_by_id'], array($durasi,$pegId,$perId));
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
   
   function periode2string($date) {
	   $bln = array(
	        1  => 'Januari',
					2  => 'Februari',
					3  => 'Maret',
					4  => 'April',
					5  => 'Mei',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'Agustus',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Desember'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng2($date) {
	   $bln = array(
	        1  => 'Jan',
					2  => 'Feb',
					3  => 'Mar',
					4  => 'Apr',
					5  => 'May',
					6  => 'Jun',
					7  => 'Jul',
					8  => 'Aug',
					9  => 'Sep',
					10 => 'Oct',
					11 => 'Nov',
					12 => 'Dec'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEngWithDay($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   
	   $jd=cal_to_jd(CAL_GREGORIAN,$bulan,$tanggal,$tahun);
	   
	   return jddayofweek($jd,1).', '.(int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}
?>
