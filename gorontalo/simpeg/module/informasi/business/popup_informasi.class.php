<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/verifikasi_data/business/verifikasi_data.class.php';

class PopupInformasi extends Database {

	protected $mSqlFile= 'module/informasi/business/popup_informasi.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);       
	}
    
	function GetUserLengkap() {      
	    $userId=$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$result = $this->Open($this->mSqlQueries['get_user_lengkap'], array($userId)); 
		return $result[0];	  
	}
	
	function GetListPegawaiPensiun() {
		if (!$this->HaveAccess('laporan_pensiun')) return array();
        
        $Verifikasi = new VerifikasiData();
        
        $result = $Verifikasi->GetSatkerAndLevel();
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_pensiun'], array($satkerlevel, $satker)); 
		return $result;	  
	}
	
	function GetListPegawaiNaikPangkat() {  
		if (!$this->HaveAccess('laporan_kenaikanpangkat')) return array();
        
        $Verifikasi = new VerifikasiData();
        
        $result = $Verifikasi->GetSatkerAndLevel();
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_naik_pangkat'], array($satkerlevel, $satker)); 
		return $result;	  
	}
	
	function GetListPegawaiNaikGaji() { 
		if (!$this->HaveAccess('laporan_kenaikangaji')) return array();
        
        $Verifikasi = new VerifikasiData();
        
        $result = $Verifikasi->GetSatkerAndLevel();
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_naik_gaji'], array($satkerlevel, $satker)); 
		return $result;	  
	}
	
	function GetListPegawaiCuti() {   
		if (!$this->HaveAccess('data_cuti')) return array();
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_cuti'], array()); 
		return $result;	  
	}
	
	function GetListPegawaiLembur() { 
		if (!$this->HaveAccess('data_lembur')) return array();
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_lembur'], array('%H:%i')); 
		return $result;	  
	}
	
	function GetListPegawaiVerifikasi() { 
		if (!$this->HaveAccess('verifikasi_data')) return array();
		$Verifikasi = new VerifikasiData();
		
		$data = $Verifikasi->GetComboJenisData();
		for ($i=0; $i<sizeof($data); $i++){
			$result[$i]['jenisdata'] = $data[$i]['id'];
			$result[$i]['judul'] = $data[$i]['name'];
			$result[$i]['jumlah'] = $Verifikasi->GetCountDataNotifikasi('',1,$data[$i]['id']);
			if ($result[$i]['jumlah']>0) {
				$temp[]=$result[$i];
			}
		}
		$result=$temp;
		return $result;	  
	}
	
	function GetListPegawaiPAK() { 
		if (!$this->HaveAccess('mutasi_pak_kumulatif')) return array();
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_pak'], array()); 
		return $result;	  
	}
	
	function GetListPegawaiBKD() { 
		if (!$this->HaveAccess('mutasi_bkd')) return array();
		$result = $this->Open($this->mSqlQueries['get_list_pegawai_bkd'], array()); 
		return $result;	  
	}
	
	function HaveAccess($module){
		$userid=Security::Authentication()->GetCurrentUser()->GetUserId();
		$result = $this->Open($this->mSqlQueries['cek_akses_by_userid'], array($userid,$module));
		if ($result[0]['total']>0) return true;
		return false;
	}

	function GetCountDaftarPegawai() {
        $Verifikasi = new VerifikasiData();
        
        $result = $Verifikasi->GetSatkerAndLevel();
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
		$result = $this->Open($this->mSqlQueries['get_count_daftar_pegawai'], array($satkerlevel, $satker));
		return $result;
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

	function GetCountDaftarPegawaiSatya() {
        $Verifikasi = new VerifikasiData();
        
        $result = $Verifikasi->GetSatkerAndLevel();
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
		$sql=$this->mSqlQueries['get_count_daftar_pegawai_satya']; 
		
		if ($this->berdasarkan=='eselon'){
			if ($this->urutan=='ASC') {
				$this->urutan='DESC';
			}else{
				$this->urutan='ASC';
			}
		}
	  
		$filter='';
		$var=array_keys($this->query_filter);
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->filter[$var[$i]] != "all")&&($this->filter[$var[$i]]!='')) $filter .= $this->query_filter[$var[$i]];
		}
		
		$order=' '.$this->order['f'.$this->berdasarkan][0].' '.$this->urutan;
		if ($startRec!=''){
			$limit='LIMIT '.$startRec.','.$itemViewed;
		}else{
			$limit='';
		}

		// Filter 10, 20, and 30 years, also considering leap years (tahun kabisat)
		$search = 'AND ((DATEDIFF(NOW(),pegTglMasukInstitusi) BETWEEN 10928 AND 10958) OR (DATEDIFF(NOW(),pegTglMasukInstitusi) BETWEEN 7275 AND 7305) OR (DATEDIFF(NOW(),pegTglMasukInstitusi) BETWEEN 3623 AND 3653))';
		

		$sql=str_replace('%filter%',$filter,$sql);
		$sql=str_replace('%LIMIT%',$limit,$sql);
		$sql=str_replace('%order%',$order,$sql);
		$sql=str_replace('%search%',$search,$sql);
		// echo '<pre>'.$sql.'</pre>';
		$result=$this->Open($sql, array($satkerlevel, $satker));
		
		return $result;
	}
  
}
?>
