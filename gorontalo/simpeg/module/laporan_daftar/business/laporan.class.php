<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Laporan extends Database {

	protected $mSqlFile= 'module/laporan_daftar/business/laporan.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);   
		//  
	}
   
	function getVariabelGlobal(){	
		$this->varFilter=array('jabatan_fungsional','jenisfungsional','unit','status','jenis','golongan','fungsional','pendidikan','agama','eselon','nip_nama','jenis_kepegawaian');
		
		$this->field=array(
						'fnama'=>array('pegNama'),
						'fnip'=>array('pegKodeResmi'),
						'fttl'=>array('pegTmpLahir','pegTglLahir'),
						'falamat'=>array('pegAlamat'),
						'fkarpeg'=>array('pegKodeGateAccess'),
						'fkarsu'=>array('sutriNoKartu'),
						'fnpwp'=>array('pegNoNPWP'),
						'fpnssk'=>array('pegPnsSK'),
						'fkenpask'=>array('pktgolNoSk'),
						'fstatuspeg'=>array('statrPegawai'),
						'fjenispeg'=>array('jnspegrNama'),
						'funit'=>array('satkerNama'),
						'fjk'=>array('pegKelamin'),
						'fgolongan'=>array('pktgolrNama','pktgolrId','pktgolTmt'),
						// 'ffungsional'=>array('jabfungrNama','tmt_fungsional','sk_fungsional'),
						'fstruktural'=>array('jabstrukrNama','tmt_struktural','sk_struktural'),
						'fpendidikan'=>array('pendNama','pddkInstitusi','pddkJurusan','pddkThnLulus'),
						'fpelatihan'=>array('jnspelrTahun','jnspelrNama','pelJmlJam'),
						'fmks'=>array('mks'),
						'fusia'=>array('usia'),
						'feselon'=>array('jbtnEselon'),
						'fagama'=>array('agmNama'),
						''=>''
					);
					
		$this->order=array(
						'fnama'=>array('pegNama'),
						'fnip'=>array('pegKodeResmi'),
						'fttl'=>array('pegTglLahir'),
						'fkarpeg'=>array('pegKodeGateAccess'),
						'fjenispeg'=>array('jnspegrNama'),
						'funit'=>array('satkerId'),
						'fjk'=>array('pegKelamin'),
						'fgolongan'=>array('pktgolrId'),
						//'ffungsional'=>array('jabfungrId'),
						'fstruktural'=>array('jabstrukrId'),
						'fpendidikan'=>array('pendId'),
						'fmasakerja'=>array('mks'),
						'fusia'=>array('usia'),
						'feselon'=>array('jbtnEselon'),
						'fagama'=>array('agmNama'),
						''=>''
					);
					
		$this->caption=array(
						'fnama'=>array('Nama Pegawai'),
						'fnip'=>array('NIP Pegawai'),
						'fttl'=>array('TTL','Tempat Lahir','Tanggal Lahir'),
						'falamat'=>array('Alamat'),
						'fkarpeg'=>array('Karpeg'),
						'fkarsu'=>array('Karsu'),
						'fnpwp'=>array('NPWP'),
						'fpnssk'=>array('SK PNS'),
						'fkenpask'=>array('SK Kenpa'),
						'fstatuspeg'=>array('Status Pegawai'),
						'fjenispeg'=>array('Jenis Pegawai'),
						'funit'=>array('Unit Kerja'),
						'fjk'=>array('Jenis Kelamin'),
						'fgolongan'=>array('Pangkat Golongan','Pangkat','Golongan','TMT'),
						//'ffungsional'=>array('Jabatan Fungsional','Jabatan','TMT','Nomor SK'),
						'fstruktural'=>array('Jabatan Struktural','Jabatan','TMT','Nomor SK'),
						'fpendidikan'=>array('Pendidikan Terakhir','Jenjang','Institusi','Jurusan','Tahun Lulus'),
						'fpelatihan'=>array('Pelatihan','Tahun','Nama Pelatihan','Jumlah Jam'),
						'fmks'=>array('Masa Kerja Seluruh'),
						'fusia'=>array('Usia'),
						'feselon'=>array('Eselon'),
						'fagama'=>array('Agama'),
						''=>''
					);

					if(!empty($this->filter['eselon'])){
						$eselon_query = 'AND jbtnEselon= "'.$this->filter['eselon'].'"';
					}else{
						$eselon_query = '';
					}
					

		$this->query_filter=array(
						'unit'=>' AND (satkerId='.$this->filter['unit'].' OR satkerLevel LIKE CONCAT((SELECT satkerLevel FROM pub_satuan_kerja WHERE satkerId='.$this->filter['unit'].'),".%%"))',
						'status'=>' AND k.statpegStatrId='.$this->filter['status'],
						'golongan'=>' AND pktgolrId= "'.$this->filter['golongan'].'"',
						'fungsional'=>' AND jfr.jabfungrId= '.$this->filter['fungsional'],
						'jabatan_fungsional'=>' AND jfr.jabfungrJenisrId= '.$this->filter['jabatan_fungsional'],
						'jenis_kepegawaian'=>' AND sk.satkerpegJenPegId= '.$this->filter['jenis_kepegawaian'],
						'pendidikan'=>' AND pendId= '.$this->filter['pendidikan'],
						'jenis'=>' AND jnspegrId= '.$this->filter['jenis'],
						'jenisfungsional'=>' AND jfr.jabfungrJenisrId= '.$this->filter['jenisfungsional'],
						'agama'=>' AND agmId= '.$this->filter['agama'],
						'eselon'=>$eselon_query,
						'nip_nama'=>'AND ( pegKodeResmi LIKE "%%'.$this->filter['nip_nama'].'%%" OR pegNama LIKE "%%'.$this->filter['nip_nama'].'%%"  )',
						''=>''
					);
		// print_r($this->query_filter);
	}
  
//==GET==    
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
   
	function GetDaftarPegawai($startRec="", $itemViewed="") {
		$sql=$this->mSqlQueries['get_daftar_pegawai']; 
		
		if ($this->berdasarkan=='eselon'){
			if ($this->urutan=='ASC') {
				$this->urutan='DESC';
			}else{
				$this->urutan='ASC';
			}
		}
        
        $Satker = new SatuanKerja();
        
        $result = $Satker->GetSatkerAndLevel();
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
	  
		$filter='';
		$var=array_keys($this->query_filter);
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->filter[$var[$i]] != "all")&&($this->filter[$var[$i]]!='')) $filter .= $this->query_filter[$var[$i]];
		}

		// print_r($this->query_filter);exit();
		
		$order=' '.$this->order['f'.$this->berdasarkan][0].' '.$this->urutan;
		if (is_integer($startRec)){
			$limit='LIMIT '.$startRec.','.$itemViewed;
		}else{
			$limit='';
		}
		
		$sql=str_replace('%filter%',$filter,$sql);
		$sql=str_replace('%LIMIT%',$limit,$sql);
		$sql=str_replace('%order%',$order,$sql);
		$sql=str_replace('%notnull%',$this->order['f'.$this->berdasarkan][0],$sql);
		
		$result=$this->Open($sql, array($satkerlevel, $satker));
		
		for ($i=0; $i<sizeof($result); $i++){
			$result[$i]['mks'] = $this->GetMksByPegId($result[$i]['pegId']);
		}
		return $result;
	}
	
	function GetCountDaftarPegawai() {
		$sql=$this->mSqlQueries['get_count_daftar_pegawai']; 
		
		if ($this->berdasarkan=='eselon'){
			if ($this->urutan=='ASC') {
				$this->urutan='DESC';
			}else{
				$this->urutan='ASC';
			}
		}
        
        $Satker = new SatuanKerja();
        
        $result = $Satker->GetSatkerAndLevel();
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
		
		$sql=str_replace('%filter%',$filter,$sql);
		$sql=str_replace('%LIMIT%',$limit,$sql);
		$sql=str_replace('%order%',$order,$sql);
		// print_r($sql);exit();
		// echo '<pre>'.$sql.'</pre>';
		$result=$this->Open($sql, array($satkerlevel, $satker));
		
		return $result[0]['TOTAL'];
	}
   
	function IndonesianDate($StrDate, $StrFormat){
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat){
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
	
	function GetMksByPegId($pegId) { 
		$masa_kerja = $this->Open($this->mSqlQueries['get_masa_kerja'],array($pegId));
		if ($masa_kerja[0]['MKS_TAHUN']!=''){
			return $masa_kerja[0]['MKS_TAHUN'].' Tahun '.$masa_kerja[0]['MKS_BULAN'].' Bulan ';
		}else{
			return '0 Tahun 0 Bulan ';
		}
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
