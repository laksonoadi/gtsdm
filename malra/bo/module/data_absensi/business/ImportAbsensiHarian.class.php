<?php

class ImportAbsensiHarian extends Database {

	protected $mSqlFile= 'module/data_absensi/business/importabsensiharian.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
	}
   
	function CheckAbsensiPegawai($kode,$tanggal) {
		$result = $this->Open($this->mSqlQueries['cek_absensi_pegawai'], array($kode, $tanggal));
		if(!empty($result))
			return true;
		else
			return false;
	}

	function AddAbsensiHarian($arrData){
		$result = $this->Execute($this->mSqlQueries['add_absensi_harian'], $arrData);
		return $result;
	}
	
	function UpdateAbsensiHarian($arrData){
		$result = $this->Execute($this->mSqlQueries['update_absensi_harian'], $arrData);
		return $result;
	}
	
	function UpdateKodeAbsensiHarian($arrData){
		$result = $this->Execute($this->mSqlQueries['update_kode_absensi_harian'], $arrData);
		return $result;
	}
	
	function AnalisisAbsensiHarian($kode,$tanggal){
		$result = $this->Execute($this->mSqlQueries['analisis_absensi_harian'], array($kode,$tanggal));
		return $result;
	}
	
	function AnalisisAbsensiHarianAll(){
		$result = $this->Execute($this->mSqlQueries['analisis_absensi_harian_all'], array());
		return $result;
	}
  
  
	function ConvertDate($StrDate, $StrFormat, $ResultFormat){
		/*
		*	Fungsi untuk menconvert format Tanggal
		*/
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
		}//End switch
		$ResultFormat = strtoupper($ResultFormat);
		switch ($ResultFormat)
		{
			case "MM-DD-YYYY" :	$StrResult = $Month."-".$Day."-".$Year;
								break;
			case "DD-MM-YYYY" :	$StrResult = $Day."-".$Month."-".$Year;
								break;
			case "YYYY-MM-DD" :	$StrResult = $Year."-".$Month."-".$Day;
								break;
			case "MM/DD/YYYY" :	$StrResult = $Month."/".$Day."/".$Year;
								break;
			case "DD/MM/YYYY" :	$StrResult = $Day."/".$Month."/".$Year;
								break;
			case "YYYY/MM/DD" :	$StrResult = $Year."/".$Month."/".$Day;
								break;
		}//End switch
		return $StrResult;
} //End function


}
?>
