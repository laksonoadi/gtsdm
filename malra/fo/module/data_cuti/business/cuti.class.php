<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class Cuti extends Database {

	protected $mSqlFile= 'module/data_cuti/business/cuti.sql.php';
   
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
  			//return 0;
  		} else {
  			//return $res3;
  		}  
		
		$Obj = new DataPegawai;	 
		$totalData = $Obj->GetCountPegawaiByUserId($nip_nama, 'all');
		return $totalData;
	}
   
	function GetData ($offset, $limit, $nip_nama='') { 
		if($nip_nama != ""){
			$str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
		}else{
			$str = "";
		}
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data1'], array($str, $offset, $limit));
		//return $this->Open(stripslashes($result), array());    
	  
		$Obj = new DataPegawai;
		$result = $Obj->GetDataPegawaiByUserId($offset, $limit, $nip_nama, 'all');
		return $result;
	}
   
	function GetCountCuti($idPeg, $tampil,$year,$nomor) {
		if($tampil != "all"){
			$str = " AND cutiStatus = '".$tampil."'";
		}else{
			$str = "";
		}
		
		$str .= " AND cutiNo LIKE '%".$nomor."%'";
		$str .= " AND YEAR(cutiMulai)=".$year;
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_count2'], array($idPeg, $str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
		if (!$res3) {
			return 0;
  		} else {
  			return $res3;
  		}  
	}
   
	function GetDataCuti($offset, $limit, $idPeg, $tampil,$year,$nomor) { 
		if($tampil != "all"){
			$str = " AND cutiStatus = '".$tampil."'";
		}else{
			$str = "";
		}
		
		$str .= " AND cutiNo LIKE '%".$nomor."%'";
		$str .= " AND YEAR(cutiMulai)=".$year;
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data2'], array($idPeg, $str, $offset, $limit));
  		//print_r(stripslashes($result));
		$result=$this->Open(stripslashes($result), array());
		for ($i=0; $i<sizeof($result); $i++){
			$result[$i]['durasi']=$this->GetDurasi($result[$i]['mulai'],$result[$i]['selesai']);
		}
		return   $result;  
	}
   
	function GetDataById($id) {      
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
		if($result)
			return $result[0];
		else
			return $result;	  
	}
   
	function GetDataCutiDet($id) {      
		$result = $this->Open($this->mSqlQueries['get_data_cuti_det'], array("%Y-%m-%d",$id)); 
		$result[0]['durasi']=$this->GetDurasi($result[0]['tglmul'],$result[0]['tglsel']);
	    return $result;	  
	}
   
	function GetComboTipe(){
		$result = $this->Open($this->mSqlQueries['get_combo_tipe'], array());
		return $result;
	}
   
	function CekNmrCuti($nmr){
		$result = $this->Open($this->mSqlQueries['cek_nmr_cuti'], array($nmr));
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
   
	function GetPeriodeCutiByPegId($pegId){
		$result = $this->Open($this->mSqlQueries['get_periode_cuti_by_peg_id'], array($pegId));
		return $result;
	}
   
	function UpdatePeriodeCutiDiambil($durasi,$durasi,$pegId,$perId) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil'], array($durasi,$durasi,$pegId,$perId));
		return $return;
	}
   
	function UpdatePeriodeCutiDiambilTambah($durasi,$durasi,$pegId,$perId) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil_tambah_by_id'], array($durasi,$durasi,$pegId,$perId));
		//echo vsprintf($this->mSqlQueries['do_update_periode_cuti_diambil_tambah_by_id'], array($durasi,$durasi,$pegId,$perId)).'<br/><br/>';
		return $return;
	}
   
	function UpdatePeriodeCutiDiambilKurang($durasi,$durasi,$pegId,$perId) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_periode_cuti_diambil_kurang_by_id'], array($durasi,$durasi,$pegId,$perId));
		//echo vsprintf($this->mSqlQueries['do_update_periode_cuti_diambil_kurang_by_id'], array($durasi,$durasi,$pegId,$perId)).'<br/><br/>';
		return $return;
	}
	
	function GetComboTahunCuti($id){
		$result = $this->Open($this->mSqlQueries['get_combo_tahun_cuti'], array($id));
		return $result;
	}
	
	function GetHariLibur() {
		$result = $this->Open($this->mSqlQueries['get_hari_libur'], array());
		$hari_libur="";
		for ($i=0; $i<sizeof($result); $i++){
			$hari_libur .=";".$result[$i]['tanggal'];
		}
		return $hari_libur;
    }
	
	function GetDurasi($awal,$akhir) {
		$result = $this->Open($this->mSqlQueries['get_hari_libur'], array());
		$durasi=0;
		
		$query_banding="SELECT IF(DATE('".$awal."')<=DATE('".$akhir."'),1,0) AS hari";
		$banding = $this->Open($query_banding, array()); $banding=$banding[0]['hari'];
		
		while ($banding==1){
			$query_cek_nama_hari="SELECT UPPER(DAYNAME(DATE('".$awal."'))) as hari";
			$hari = $this->Open($query_cek_nama_hari, array()); $hari=$hari[0]['hari'];
			
			$libur=false;
			for ($i=0; $i<sizeof($result); $i++){
				if (($result[$i]['tanggal']==$awal)) $libur=true;
			}
			
			if (($libur==false)&&($hari!='SUNDAY')&&($hari!='SATURDAY')) $durasi++;
			
			$i++;
			
			$query_next="SELECT ADDDATE(DATE('".$awal."'),1) AS hari";
			$next = $this->Open($query_next, array()); $awal=$next[0]['hari'];
			
			$query_banding="SELECT IF(DATE('".$awal."')<=DATE('".$akhir."'),1,0) AS hari";
			$banding = $this->Open($query_banding, array()); $banding=$banding[0]['hari'];
			
			if ($i>10) break;
		}
		return $durasi;
    }
}
?>
