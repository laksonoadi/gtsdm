<?php

class Laporan extends Database {

	protected $mSqlFile= 'module/laporan_rekapitulasi/business/laporan.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);   
		  
	}
	
	function getVariabelGlobal(){		
		$this->judul=array(
						'unit'=>'Unit Kerja',
						'status'=>'Status Pegawai',
						'golongan'=>'Pangkat/Golongan',
						'fungsional'=>'Jabatan Fungsional',
						'jenisfungsional'=>'Jenis Fungsional',
						'struktural'=>'Jabatan Struktural',
						'eselon'=>'Tingkatan Eselon',
						'pendidikan'=>'Tingkat Pendidikan',
						'spendidikan'=>'Status Pendidikan',
						'jenis'=>'Jenis Pegawai',
						'agama'=>'Agama',
						'statnikah'=>'Status Pernikahan',
						'usia'=>'Usia Pegawai',
						'masakerja'=>'Lama Bekerja',
						'sertifikasi'=>'Tahun Sertifikasi Dosen',
						''=>''
					);
					
		$this->query_filter=array(
						'unit'=>' AND (satkerId='.$this->filter['unit'].' OR satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja WHERE satkerId='.$this->filter['unit'].'),".%"))',
						'status'=>' AND statrId='.$this->filter['status'],
						'golongan'=>' AND pktgolrId= "'.$this->filter['golongan'].'"',
						'fungsional'=>' AND jfr.jabfungrId= '.$this->filter['fungsional'],
						'pendidikan'=>' AND pendId= '.$this->filter['pendidikan'],
						'jenis'=>' AND jnspegrId= '.$this->filter['jenis'],
						'jenisfungsional'=>' AND jfrs2.jabfungjenisrId= '.$this->filter['jenisfungsional'],
						''=>''
					);
		
	}
  
//==GET== 
	function GetComboUnitKerja($combo=false){
		$sql=$this->mSqlQueries['get_combo_unit_kerja'];
		
		$filter='';
		if(($this->filter['unit'] != "all")&&($this->filter['unit']!='')&&($combo==false)){
			$filter .= $this->query_filter['unit'];
		}
		$sql=str_replace('%filter%',$filter,$sql);
		
		$result=$this->Open($sql,array());
		
		if(($this->filter['unit'] == "all")&&($combo==false)){
			$i=sizeof($result);
			$result[$i]['id']=99999;
			$result[$i]['name']='Belum Diset';
		}
		
		return $result;
	}
   
	function GetComboVariabel($variabel='unit',$param1='all'){
		$sql=$this->mSqlQueries['get_combo_'.$variabel];
		
		$filter='';
		if(($this->filter[$variabel] != "all")&&($this->filter[$variabel]!='')){
			$filter .= $this->query_filter[$variabel];
		}
		
		if (($param1 != "all")&&($variabel=='fungsional')){
			$filter .=' AND jabfungrJenisrId='.$param1;
		}
		
		$sql=str_replace('%filter%',$filter,$sql);
		//echo $sql; exit;
		$result=$this->Open($sql,array());
		
		$i=sizeof($result);
		if (($variabel!='unit')&&(($this->filter[$variabel] == "all")||($this->filter[$variabel] == ""))){
			$result[$i]['id']=99999;
			$result[$i]['name']='Belum Diset';
		}
		return $result;
	}
	
	function GetComboVariabel2($variabel='unit',$param1='all'){
		$sql=$this->mSqlQueries['get_combo_'.$variabel];
		
		$filter='';
		if($param1 != "all"){
			$filter=' AND jabfungjenisrId='.$param1;
		}
		
		$sql=str_replace('%filter%',$filter,$sql);
		$result=$this->Open($sql,array());
		return $result;
	}
   
	function GetComboJabatanFungsional(){
		return $this->Open($this->mSqlQueries['get_combo_jabatan_fungsional'],array());
	}
   
	function GetDataPegawaiBackUp($offset, $limit, $jabatan_fungsional,$variabel='unit') {
		$sql=$this->mSqlQueries['get_data_pegawai_'.$variabel]; 
		
		$filter='';
		$var=array_keys($this->query_filter);
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->filter[$var[$i]] != "all")&&($this->filter[$var[$i]]!='')) $filter .= $this->query_filter[$var[$i]];
		}
      
		$sql=str_replace('%filter%',$filter,$sql);
		$sql=str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      
		$result=$this->Open($sql, array()); 
  		return $result;     
	}
	
	function GetDataPegawai($offset, $limit, $jabatan_fungsional,$variabel='unit') {
		$sql=$this->mSqlQueries['get_statistik_pegawai']; 
		
		$idvar=$this->mSqlQueries['id_'.$variabel]; 
		$idvargroup='';
		if (($variabel!='usia')&&($variabel!='masakerja')){
			$idvargroup=$this->mSqlQueries['id_'.$variabel].","; 
		}else{
			$idvargroup='';
		}
		$variabel_tampil=$this->mSqlQueries['tampil_'.$variabel]; 
		
		$filter='';
		$var=array_keys($this->query_filter);
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->filter[$var[$i]] != "all")&&($this->filter[$var[$i]]!='')) {
				$filter .= $this->query_filter[$var[$i]];
			}
		}
		
		$join = $this->mSqlQueries['join_unit'];
		if ($variabel!='unit'){
			$join .=$this->mSqlQueries['join_'.$variabel];
		}
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->filter[$var[$i]] != "all")&&($this->filter[$var[$i]]!='')&&($var[$i]!='unit')&&($var[$i]!=$variabel)) {
				$join .= $this->mSqlQueries['join_'.$var[$i]];
			}
		}
		
		if ($variabel_tampil=='') $variabel_tampil='nama';
		
		$sql=str_replace('%variabel_tampil%',$variabel_tampil,$sql);
		$sql=str_replace('%variabel%',$idvar,$sql);
		$sql=str_replace('%variabel_group%',$idvargroup,$sql);
		$sql=str_replace('%join%',$join,$sql);
		$sql=str_replace('%filter%',$filter,$sql);
		
		
		$result=$this->Open($sql, array()); 
		//echo '<pre>'.print_r($sql).'</pre>'; //exit;
  		return $result;     
	}
   
   
   
	function IndonesianDate($StrDate, $StrFormat){
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
	
	function IndonesianDate2($StrDate, $StrFormat)
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
			case "01" :	$StrResult = $Day."-Jan-".$Year;
						break;
			case "02" :	$StrResult = $Day."-Feb-".$Year;
						break;
			case "03" :	$StrResult = $Day."-Mar-".$Year;
						break;
			case "04" :	$StrResult = $Day."-Apr-".$Year;
						break;
			case "05" :	$StrResult = $Day."-May-".$Year;
						break;
			case "06" :	$StrResult = $Day."-Jun-".$Year;
						break;
			case "07" :	$StrResult = $Day."-Jul-".$Year;
						break;
			case "08" :	$StrResult = $Day."-Aug-".$Year;
						break;
			case "09" :	$StrResult = $Day."-Sep-".$Year;
						break;
			case "10" :	$StrResult = $Day."-Okt-".$Year;
						break;
			case "11" :	$StrResult = $Day."-Nov-".$Year;
						break;
			case "12" :	$StrResult = $Day."-Des-".$Year;
						break;
		} //end switch
		return $StrResult;
	}
	
	function GetBulan($Month)
	 {  
	    
			if (($Month=='1')) return "Januari ";
			if (($Month=='2')) return "Febuari ";
			if (($Month=='3')) return "Maret ";
			if (($Month=='4')) return "April ";
		  if (($Month=='5')) return "Mei ";
			if (($Month=='6')) return "Juni ";
			if (($Month=='7')) return "Juli ";
		  if (($Month=='8')) return "Agustus ";
			if (($Month=='9')) return "September ";
			if (($Month=='10')) return "Oktober ";
			if (($Month=='11')) return "November ";
			if (($Month=='12')) return "Desember ";
			
			return "";
	}
	
	function isKabisat($thn) {
			// jika tahun habis dibagi 4, maka tahun kabisat
			if (($thn % 4) != 0) {
				return false;
			} // jika tidak habis dibagi 4, maka jika habis dibagi 100 dan 400 maka tahun kabisat
			else if ((($thn % 100) == 0) && (($thn % 400) != 0)) {
				return false;
			}
			else {
				return true;
			}
		}

   // mendapatkan tanggal terakhir dari sutau bulan
	function getLastDate($tahun,$bulan){
      $kabisat = $this->isKabisat($tahun);
      if ($kabisat == true)
         $febLastDate = 29;
      else
         $febLastDate = 28;
      
      if (($bulan=='1')) $bln=0;
			if (($bulan=='2')) $bln=1;
			if (($bulan=='3')) $bln=2;
			if (($bulan=='4')) $bln=3;
			if (($bulan=='5')) $bln=4;
			if (($bulan=='6')) $bln=5;
			if (($bulan=='7')) $bln=6;
			if (($bulan=='8')) $bln=7;
			if (($bulan=='9')) $bln=8;
			if (($bulan=='10')) $bln=9;
			if (($bulan=='11')) $bln=10;
			if (($bulan=='12')) $bln=11;
			
      $arrLastDate = array(31,$febLastDate,31,30,31,30,31,31,30,31,30,31);
      for ($i=0;$i<12;$i++){
         if ($i == $bln)  
            //$lastDate =  $tahun.'-'.$bulan.'-'.$arrLastDate[$i];
            $lastDate =  $arrLastDate[$i];
      }
      return $lastDate;
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
