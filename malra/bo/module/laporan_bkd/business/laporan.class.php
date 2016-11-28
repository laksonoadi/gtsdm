<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Laporan extends Database {

   protected $mSqlFile= 'module/laporan_bkd/business/laporan.sql.php';
   
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
  
// GET COMBO START /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   

   function GetComboUnitKerja(){
      $this->Obj = new SatuanKerja();
	  $result 	 = $this->Obj->GetSatuanKerjaByUserId();
	  return $result;
   }
   
   function GetComboPangkatGolongan(){
      return $this->Open($this->mSqlQueries['get_combo_pangkat_golongan'],array());
   }
   
   function GetComboFungsional(){
      return $this->Open($this->mSqlQueries['get_combo_fungsional'],array());
   }
   
   function GetComboPendidikan(){
      return $this->Open($this->mSqlQueries['get_combo_pendidikan'],array());
   }

	function GetComboTahun(){
		$year = date('Y')+3;
		$no	  = 0;
		for($i=$year;$i>2001;$i--){
			$x = $i + 1;
			$arrYear[$no]['id']		= $i;
			$arrYear[$no]['name']	= $i.' / '.$x;
			$no++;
		}
		return $arrYear;
	}

   function GetDataFakultas($unit_kerja){
	  $sql	= $this->mSqlQueries['get_fakultas'];
      if($unit_kerja != "all"){
  		$sql	= str_replace('%fakultas%'," WHERE satkerId='".$unit_kerja."'",$sql);
      }else{
        $sql	= str_replace('%fakultas%','',$sql);
      }
      $result=$this->Open($sql, array());
	  return $result;
   }

// GET COMBO END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
   



// GET DATA REKAPITULASI START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetDataRekapitulasiBkd($unit_kerja, $pangkat_golongan, $fungsional, $pendidikan, $fakultas) {
      $sql	= $this->mSqlQueries['get_data_rekapitulasi_bkd'];
      $list	= $this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      if($unit_kerja != "all"){
  		$sql	= str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$list.')' ,$sql);
      }else{
        $sql	= str_replace('%unit_kerja%','',$sql);
      }
      
      if($pangkat_golongan != "all"){
  		$sql	= str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql	= str_replace('%pangkat_golongan%','',$sql);
      }
      
      if($fungsional != "all"){
  		$sql	= str_replace('%fungsional%',' AND jabfungrId='.$fungsional ,$sql);
      }else{
        $sql	= str_replace('%fungsional%','',$sql);
      }
      
      if($pendidikan != "all"){
  		$sql	= str_replace('%pendidikan%'," AND pddkTkpddkrId='".$pendidikan."'" ,$sql);
      }else{
        $sql	= str_replace('%pendidikan%','',$sql);
      }

		$sql	= str_replace('%fakultas%'," AND sdm_bkd.bkdFakultas ='".$fakultas."'" ,$sql);
      
      $result	= $this->Open($sql, array());
	  return $result;
   }
// GET DATA REKAPITULASI END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




// GET DATA SKS GANJIL START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function getSksPendGanjil($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_pend_ganjil'], array($pegId));       
     return $result;
   }
   function getSksPenlGanjil($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_penl_ganjil'], array($pegId));       
     return $result;
   }
   function getSksPengGanjil($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_peng_ganjil'], array($pegId));       
     return $result;
   }
   function getSksPenuGanjil($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_penu_ganjil'], array($pegId));       
     return $result;
   }
// GET DATA SKS GANJIL END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GET DATA SKS GENAP START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function getSksPendGenap($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_pend_genap'], array($pegId));       
     return $result;
   }
   function getSksPenlGenap($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_penl_genap'], array($pegId));       
     return $result;
   }
   function getSksPengGenap($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_peng_genap'], array($pegId));       
     return $result;
   }
   function getSksPenuGenap($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_penu_genap'], array($pegId));       
     return $result;
   }
// GET DATA SKS GENAP END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   function getSksProf($pegId) {   
     $result = $this->Open($this->mSqlQueries['get_sks_prof'], array($pegId));       
     return $result;
   }


      
// GET DATA LAPORAN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkd($unit_kerja, $pangkat_golongan, $fungsional, $pendidikan) {
      $sql	= $this->mSqlQueries['get_data_bkd'];
      $list	= $this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      if($unit_kerja != "all"){
  		$sql	= str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$list.')' ,$sql);
      }else{
        $sql	= str_replace('%unit_kerja%','',$sql);
      }
      
      if($pangkat_golongan != "all"){
  		$sql	= str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql	= str_replace('%pangkat_golongan%','',$sql);
      }
      
      if($fungsional != "all"){
  		$sql	= str_replace('%fungsional%',' AND jabfungrId='.$fungsional ,$sql);
      }else{
        $sql	= str_replace('%fungsional%','',$sql);
      }
      
      if($pendidikan != "all"){
  		$sql	= str_replace('%pendidikan%'," AND pendId='".$pendidikan."'" ,$sql);
      }else{
        $sql	= str_replace('%pendidikan%','',$sql);
      }
      
      $sql=str_replace('%limit%','',$sql);
      $result=$this->Open($sql, array());
      
	  return sizeof($result);
   }
   
   function GetDataBkd($offset, $limit, $unit_kerja, $pangkat_golongan, $fungsional, $pendidikan) {
      $sql	= $this->mSqlQueries['get_data_bkd']; 
      $list	= $this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      if($unit_kerja != "all"){
  		$sql	= str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$list.')' ,$sql);
      }else{
        $sql	= str_replace('%unit_kerja%','',$sql);
      }
      
      if($pangkat_golongan != "all"){
  		$sql	= str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql	= str_replace('%pangkat_golongan%','',$sql);
      }
      
      if($fungsional != "all"){
  		$sql	= str_replace('%fungsional%',' AND jabfungrId='.$fungsional ,$sql);
      }else{
        $sql	= str_replace('%fungsional%','',$sql);
      }
      
      if($pendidikan != "all"){
  		$sql	= str_replace('%pendidikan%'," AND pddkTkpddkrId='".$pendidikan."'" ,$sql);
      }else{
        $sql	= str_replace('%pendidikan%','',$sql);
      }
      
      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
      
	  return $result;     
   }
// GET DATA LAPORAN END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   
// GET DATA DETAIL LAPORAN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdDetail($unit_kerja, $pangkat_golongan, $fungsional, $pendidikan, $jenis, $tahun, $semester, $pegId) {
      $sql	= $this->mSqlQueries['get_data_bkd_detail']; 
      $list	= $this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      if($unit_kerja != "all"){
  		$sql	= str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$list.')' ,$sql);
      }else{
        $sql	= str_replace('%unit_kerja%','',$sql);
      }
      
      if($pangkat_golongan != "all"){
  		$sql	= str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql	= str_replace('%pangkat_golongan%','',$sql);
      }
      
      if($fungsional != "all"){
  		$sql	= str_replace('%fungsional%',' AND jabfungrId='.$fungsional ,$sql);
      }else{
        $sql	= str_replace('%fungsional%','',$sql);
      }
      
      if($pendidikan != "all"){
  		$sql	= str_replace('%pendidikan%'," AND pendId='".$pendidikan."'" ,$sql);
      }else{
        $sql	= str_replace('%pendidikan%','',$sql);
      }
      
      if($jenis != "all"){
  		$sql	= str_replace('%jenis%'," AND bkdJenis='".$jenis."'" ,$sql);
      }else{
        $sql	= str_replace('%jenis%','',$sql);
      }
      
      if($tahun != "all"){
  		$sql	= str_replace('%tahun%'," AND sdm_bkd.bkdTahunAkademik='".$tahun."'" ,$sql);
      }else{
        $sql	= str_replace('%tahun%','',$sql);
      }
      
      if($semester  != "all"){
  		$sql	= str_replace('%semester%'," AND sdm_bkd.bkdSemester='".$semester."'" ,$sql);
      }else{
        $sql	= str_replace('%semester%','',$sql);
      }
      
      if($pegId != ""){
  		$sql	= str_replace('%idpegawai%'," AND pub_pegawai.pegId='".$pegId."'" ,$sql);
      }else{
        $sql	= str_replace('%idpegawai%','',$sql);
      }
      
      $sql=str_replace('%limit%','',$sql);
      $result=$this->Open($sql, array());
      
	  return sizeof($result);
   }
   
   function GetDataBkdDetail($offset, $limit, $unit_kerja, $pangkat_golongan, $fungsional, $pendidikan, $jenis, $tahun, $semester, $pegId) {
      $sql	= $this->mSqlQueries['get_data_bkd_detail']; 
      $list	= $this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      if($unit_kerja != "all"){
  		$sql	= str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$list.')' ,$sql);
      }else{
        $sql	= str_replace('%unit_kerja%','',$sql);
      }
      
      if($pangkat_golongan != "all"){
  		$sql	= str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql	= str_replace('%pangkat_golongan%','',$sql);
      }
      
      if($fungsional != "all"){
  		$sql	= str_replace('%fungsional%',' AND jabfungrId='.$fungsional ,$sql);
      }else{
        $sql	= str_replace('%fungsional%','',$sql);
      }
      
      if($pendidikan != "all"){
  		$sql	= str_replace('%pendidikan%'," AND pddkTkpddkrId='".$pendidikan."'" ,$sql);
      }else{
        $sql	= str_replace('%pendidikan%','',$sql);
      }
      
      if($jenis != "all"){
  		$sql	= str_replace('%jenis%'," AND bkdJenis='".$jenis."'" ,$sql);
      }else{
        $sql	= str_replace('%jenis%','',$sql);
      }
      
      if($tahun != "all"){
  		$sql	= str_replace('%tahun%'," AND sdm_bkd.bkdTahunAkademik='".$tahun."'" ,$sql);
      }else{
        $sql	= str_replace('%tahun%','',$sql);
      }
      
      if($semester  != "all"){
  		$sql	= str_replace('%semester%'," AND sdm_bkd.bkdSemester='".$semester."'" ,$sql);
      }else{
        $sql	= str_replace('%semester%','',$sql);
      }
      
      if($pegId != ""){
  		$sql	= str_replace('%idpegawai%'," AND pub_pegawai.pegId='".$pegId."'" ,$sql);
      }else{
        $sql	= str_replace('%idpegawai%','',$sql);
      }

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
      
	  return $result;     
   }
// GET DETAIL DATA LAPORAN END ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   
// GET DETAIL DATA INDIVIDU START /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdDetailIndividu($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_detail_individu']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND sdm_bkd.bkdId='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdDetailIndividu($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_detail_individu']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND sdm_bkd.bkdId='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// GET DETAIL DATA INDIVIDU END //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*   
   
															LIST ISI DATA LAPORAN
		
*/
// LIST DATA PENDIDIKAN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdPendidikan($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_pendidikan']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpendBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdPendidikan($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_pendidikan']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpendBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// LIST DATA PENDIDIKAN END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// LIST DATA PENELITIAN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdPenelitian($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_penelitian']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpenBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdPenelitian($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_penelitian']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpenBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// LIST DATA PENELITIAN END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
// LIST DATA PENGABDIAN START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdPengabdian($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_pengabdian']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpengBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdPengabdian($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_pengabdian'];

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpengBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// LIST DATA PENGABDIAN END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
// LIST DATA PENUNJANG START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdPenunjang($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_penunjang']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpenuBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdPenunjang($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_penunjang'];

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdpenuBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// LIST DATA PENUNJANG END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
// LIST DATA PROFESOR START ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   function GetCountDataBkdProfesor($id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_profesor']; 

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdprofBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','',$sql);
      $result	= $this->Open($sql, array());
	  return sizeof($result);
   }
   
   function GetDataBkdProfesor($offset, $limit, $id_bkd) {
      $sql	= $this->mSqlQueries['get_data_bkd_profesor'];

		if($id_bkd != ''){
			$sql	= str_replace('%idbkd%',' AND a.bkdprofBkdId ='.$id_bkd ,$sql);
		}else{
			$sql	= str_replace('%idbkd%','',$sql);
		}

      $sql		= str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result	= $this->Open($sql, array());
	  return $result;     
   }
// LIST DATA PROFESOR END /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   
   
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
