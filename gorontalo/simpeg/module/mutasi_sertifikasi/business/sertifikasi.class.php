<?php

class Sertifikasi extends Database {

	protected $mSqlFile= 'module/mutasi_sertifikasi/business/sertifikasi.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);   
		  
	}
   
  
	//==GET== 
	
	function GetDataDetail($id) { 
		$result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
		return $result;
	}
   
	function GetLastUsulanSertifikasiId() { 
		$result = $this->Open($this->mSqlQueries['get_last_usulan_sertifikasi_id'], array());
		return $result[0]['id'];    
	}
	
	function GetCountUsulanSertifikasi() {
  		$return = $this->Open($this->mSqlQueries['get_count_usulan_sertifikasi'], array());
		return $return[0]['TOTAL'];
	}

       function GetComboTahunUsulan() {
  		$return = $this->Open($this->mSqlQueries['get_combo_tahun_usulan'], array());
		return $return;
	}
   
	function GetUsulanSertifikasi($offset, $limit) { 
		$result = $this->Open($this->mSqlQueries['get_usulan_sertifikasi'], array($offset, $limit));
		return $result;    
	}
	
	function GetUsulanSertifikasiById($srtfkId) { 
		$result = $this->Open($this->mSqlQueries['get_usulan_sertifikasi_by_id'], array($srtfkId));
		return $result;    
	}
	
	function GetListPesertaSertifikasiById($srtfkId,$keyword="") { 
		$result = $this->Open($this->mSqlQueries['get_list_peserta_sertifikasi_by_id'], array($srtfkId,'%'.$keyword.'%','%'.$keyword.'%','%'.$keyword.'%'));
		return $result;    
	}
	
	function GetListPesertaSertifikasiByIdVerify($srtfkId,$keyword="") { 
		$result = $this->Open($this->mSqlQueries['get_list_peserta_sertifikasi_by_id_verify'], array($srtfkId,'%'.$keyword.'%','%'.$keyword.'%','%'.$keyword.'%'));
		//echo '<pre>'.vsprintf($this->mSqlQueries['get_list_peserta_sertifikasi_by_id_verify'], array($srtfkId,'%'.$keyword.'%','%'.$keyword.'%','%'.$keyword.'%')).'</pre>';
		return $result;    
	}
	
	function GetDetailPesertaSertifikasiById($srtfkPegId,$srtfkdetTahun) { 
		$result = $this->Open($this->mSqlQueries['get_detail_peserta_sertifikasi_by_id'], array($srtfkPegId,$srtfkdetTahun));
		return $result;    
	}
	
	function GetListPesertaSertifikasiByIdDetail($srtfkId,$srtfkdetHasilAkhir) { 
		$sql=$this->mSqlQueries['get_list_peserta_sertifikasi_by_id_detail'];
		if (($srtfkdetHasilAkhir=='LULUS')||($srtfkdetHasilAkhir=='BELUM LULUS')){
			$hasilakhir=" AND srtfkdetHasilAkhir='".$srtfkdetHasilAkhir."'";
		} else if (($srtfkdetHasilAkhir=='NULL')){
			$hasilakhir=" AND srtfkdetHasilAkhir IS NULL";
		}
		
		if (($srtfkId!='ALL')){
			$id=" AND srtfkdetSrtfkId=".$srtfkId;
		}
		
		$sql = str_replace('%hasilakhir%',$hasilakhir,$sql);
		$sql = str_replace('%srtfkid%',$id,$sql);
		$result = $this->Open($sql, array());
		return $result;    
	}
	
	function GetNomorPeserta($arrData) {
		$return = $this->Open($this->mSqlQueries['get_nomor_peserta'], $arrData);
		return $return[0]['number'];
	}
   
//==DO==
	function DoAddUsulanSertifikasi($arrData) {	   
		$return = $this->Execute($this->mSqlQueries['do_add_usulan_sertifikasi'], $arrData);	
		return $return;
	}
	
	function DoUpdateUsulanSertifikasi($arrData) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_usulan_sertifikasi'], $arrData);	
		return $return;
	}
	
	function DoDeletePesertaSertifikasi($arrData) {	   
	    $sql=str_replace("%filter%",$arrData['srtfkArrPegId'],$this->mSqlQueries['do_delete_peserta_sertifikasi']);
		$return = $this->Execute($sql,array($arrData['srtfkId']));	
		return $return;
	}
	
	function DoAddPesertaSertifikasi($arrData) {	   
	    $return = $this->Open($this->mSqlQueries['cek_peserta_sertifikasi'], $arrData);
		if ($return[0]['total']==0){
			$return = $this->Execute($this->mSqlQueries['do_add_peserta_sertifikasi'], $arrData);	
		}else{
			$return=true;
		}
		return $return;
	}
	
	function DoUpdateNoPesertaSertifikasi($arrData) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_no_peserta_sertifikasi_manual'], $arrData);	
		return $return;
	}
	
	function DoUpdatePenilaian($arrData) {	   
		$return = $this->Execute($this->mSqlQueries['do_update_penilaian'], $arrData);	
		return $return;
	}
	
	function DoModifiedSertifikasi($arrData) {	   
		$return = $this->Execute($this->mSqlQueries['do_modified_sertifikasi'], $arrData);	
		return $return;
	}
	
	function IndonesianDate($StrDate, $StrFormat)
	 {
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day." Januari ".$Year;
						break;
			case "02" :	$StrResult = $Day." Febuari ".$Year;
						break;
			case "03" :	$StrResult = $Day." Maret ".$Year;
						break;
			case "04" :	$StrResult = $Day." April ".$Year;
						break;
			case "05" :	$StrResult = $Day." Mei ".$Year;
						break;
			case "06" :	$StrResult = $Day." Juni ".$Year;
						break;
			case "07" :	$StrResult = $Day." Juli ".$Year;
						break;
			case "08" :	$StrResult = $Day." Agustus ".$Year;
						break;
			case "09" :	$StrResult = $Day." September ".$Year;
						break;
			case "10" :	$StrResult = $Day." Oktober ".$Year;
						break;
			case "11" :	$StrResult = $Day." November ".$Year;
						break;
			case "12" :	$StrResult = $Day." Desember ".$Year;
						break;
		} //end switch
		return $StrResult;
	}
	
}
?>
