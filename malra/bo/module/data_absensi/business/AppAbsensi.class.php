<?php

class AppAbsensi extends Database {

	protected $mSqlFile= 'module/data_absensi/business/appabsensi.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		$this->mrDbEngine->debug = 1;
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
		
	function GetData($offset, $limit, $nip_nama='', $satkerja='', $jenis='',$idBulan, $idTahun) {
		$awal = $idTahun.$idBulan;
		if(($nip_nama != "") and ($satkerja != "")){
		  $str = " WHERE (a.pegKodeResmi LIKE '%".$nip_nama."%' OR a.pegNama LIKE '%".$nip_nama."%') AND
      d.satkerpegSatkerId = '".$satkerja."'";
    }elseif(($nip_nama != "") and ($satkerja == "")){
		  $str = " WHERE (a.pegKodeResmi LIKE '%".$nip_nama."%' OR a.pegNama LIKE '%".$nip_nama."%')AND
      d.satkerpegSatkerId IS NULL";
    }elseif(($nip_nama == "") and ($satkerja != "")){
		  $str = " WHERE d.satkerpegSatkerId = '".$satkerja."'";
    }else{
      $str = "";
    }
    
    if($jenis == "sudah"){
      $s = 1;
    }elseif($jenis == "belum"){
      $s = 0;
    }elseif($jenis == "ns"){
      $s = "";
    }
    
    if(($str == "") and ($jenis != "") and ($jenis != "all")){
      if($jenis != "ns"){
        $str2 = " WHERE e.gajipegStatus = '".$s."' AND EXTRACT(YEAR_MONTH FROM e.gajipegPeriode)='".$awal."'";
      }else{
        $str2 = " WHERE e.gajipegStatus is NULL";
      }
    }elseif(($str != "") and ($jenis != "") and ($jenis != "all")){
      if($jenis != "ns"){
        $str2 = " AND e.gajipegStatus = '".$s."' AND EXTRACT(YEAR_MONTH FROM e.gajipegPeriode)='".$awal."'";
      }else{
        $str2 = " AND e.gajipegStatus is NULL";
      }
    }else{
      $str2 = "";
    }
    
		//$result = $this->Open($this->mSqlQueries['get_data'], array($awal, $akhir,'%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%', $offset, $limit));
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($awal, $awal, $str, $str2, $offset, $limit));
      //
      //$debug = sprintf($this->mSqlQueries['get_data'], '%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%', $offset, $limit);
      //echo $debug;
		//return $result;
		$res = $this->Open(stripslashes($result), array());
		//print_r(stripslashes($result));
		return $res;
	}

	function GetCountData($nip_nama='', $satkerja='', $jenis='') {
		//$result = $this->Open($this->mSqlQueries['get_count_data'], array('%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%'));
      //$result = sizeof($result);   
		if(($nip_nama != "") and ($satkerja != "")){
		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND
      satkerpegSatkerId = '".$satkerja."'";
    }elseif(($nip_nama != "") and ($satkerja == "")){
		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND
      satkerpegSatkerId IS NULL";
    }elseif(($nip_nama == "") and ($satkerja != "")){
		  $str = " WHERE satkerpegSatkerId = '".$satkerja."'";
    }else{
      $str = "";
    }
    
    if($jenis == "sudah"){
      $s = 1;
    }elseif($jenis == "belum"){
      $s = 0;
    }elseif($jenis == "ns"){
      $s = "";
    }
    
    if(($str == "") and ($jenis != "") and ($jenis != "all")){
      if($jenis != "ns"){
        $str2 = " WHERE gajipegStatus = '".$s."'";
      }else{
        $str2 = " WHERE gajipegStatus is NULL";
      }
    }elseif(($str != "") and ($jenis != "") and ($jenis != "all")){
      if($jenis != "ns"){
        $str2 = " AND gajipegStatus = '".$s."'";
      }else{
        $str2 = " AND gajipegStatus is NULL";
      }
    }else{
      $str2 = "";
    }
    
    $result = $this->GetQueryKeren($this->mSqlQueries['get_count_data'], array($str, $str2));
		$res2 = $this->Open(stripslashes($result), array());
		$res3 = sizeof($res2); 
		
    if (!$res3) {
			return 0;
		} else {
			return $res3;
		}
	}
	
	function GetComboPegawai() {
		$result = $this->Open($this->mSqlQueries['get_combo_pegawai'], array());
		return $result;
	}

	function GetDataById($id) {
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));
		return $result[0];
	}
	
	function GetKodeGateAccessByPegId($id) {
		$result = $this->Open($this->mSqlQueries['get_kode_gate_access_by_peg_id'], array($id));
		return $result[0];
	}

	function GetTotalPegawaiAktif() {
		$result = $this->Open($this->mSqlQueries['get_total_pegawai_aktif'], array());
		return $result[0]['total'];
	}

	function GetTotalSeluruh($idBulan,$idTahun) {
		$awal = $idTahun.'-'.$idBulan.'-01';
		$akhir = $idTahun.'-'.$idBulan.'-31';
		$result = $this->Open($this->mSqlQueries['get_total_seluruh'], array($awal,$akhir));
      return $result[0]['total'];
	}
	
	function GetTahun(){
		$year = date('Y')+3;
		$no=0;
		for($i=$year;$i>2001;$i--){
			$arrYear[$no]['id']=$i;
			$arrYear[$no]['name']=$i;
			$no++;
		}
		
		$arrYear[$no]['id']='all';
		$arrYear[$no]['name']='--SEMUA--';
		
		return $arrYear;
	}

	function GetBulan(){
		$bulan = array();
		$bulan[0]['id']='01';
		$bulan[0]['name']='Januari';
		$bulan[1]['id']='02';
		$bulan[1]['name']='Februari';
		$bulan[2]['id']='03';
		$bulan[2]['name']='Maret';
		$bulan[3]['id']='04';
		$bulan[3]['name']='April';
		$bulan[4]['id']='05';
		$bulan[4]['name']='Mei';
		$bulan[5]['id']='06';
		$bulan[5]['name']='Juni';
		$bulan[6]['id']='07';
		$bulan[6]['name']='Juli';
		$bulan[7]['id']='08';
		$bulan[7]['name']='Agustus';
		$bulan[8]['id']='09';
		$bulan[8]['name']='September';
		$bulan[9]['id']='10';
		$bulan[9]['name']='Oktober';
		$bulan[10]['id']='11';
		$bulan[10]['name']='Nopember';
		$bulan[11]['id']='12';
		$bulan[11]['name']='Desember';
		$bulan[12]['id']='all';
		$bulan[12]['name']='--SEMUA--';
		return $bulan;
	}
	
	function GetBulanEng(){
		$bulan = array();
		$bulan[0]['id']='01';
		$bulan[0]['name']='January';
		$bulan[1]['id']='02';
		$bulan[1]['name']='February';
		$bulan[2]['id']='03';
		$bulan[2]['name']='March';
		$bulan[3]['id']='04';
		$bulan[3]['name']='April';
		$bulan[4]['id']='05';
		$bulan[4]['name']='May';
		$bulan[5]['id']='06';
		$bulan[5]['name']='June';
		$bulan[6]['id']='07';
		$bulan[6]['name']='July';
		$bulan[7]['id']='08';
		$bulan[7]['name']='August';
		$bulan[8]['id']='09';
		$bulan[8]['name']='September';
		$bulan[9]['id']='10';
		$bulan[9]['name']='October';
		$bulan[10]['id']='11';
		$bulan[10]['name']='November';
		$bulan[11]['id']='12';
		$bulan[11]['name']='December';
		$bulan[12]['id']='all';
		$bulan[12]['name']='ALL';
		return $bulan;
	}
	
	function GetKomponenById($id) {
		$result = $this->Open($this->mSqlQueries['get_komponen_by_id'], array($id));
		return $result;
	}
	
	function getId($id){
      $result = $this->Open($this->mSqlQueries['cek_data'], array($id)); 
	    return $result;
   }

  function GetCountAbsensiHarianTemp($nama) {
      if($nama != ""){
  		  $str = " WHERE (absensitempPegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count_temp'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   }
   
   function GetDataAbsensiHarianTemp($offset, $limit, $nama) { 
      if($nama != ""){
  		  $str = " WHERE (absensitempPegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data_temp'], array('%H:%i',$str, $offset, $limit));
      $res = $this->Open(stripslashes($result), array());
      return $res;    
   }
   
  function GetCountAbsensiHarian($nama,$input_type='all',$periode_bulan='',$periode_tahun='') {
      if($nama != ""){
  		  $str = " AND (absensiPegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      
      if($input_type != "all"){
  		  $str .= " AND absensiIsManual=".$input_type."";
      }
	  
	  if (($periode_bulan != "all")&&($periode_bulan!="")&&($periode_tahun!="")){
  		  $str .= " AND absensiTanggal BETWEEN DATE_ADD('".$periode_tahun."-".$periode_bulan."-".$tglgaji."', INTERVAL -1 MONTH) AND DATE('".$periode_tahun."-".$periode_bulan."-".($tglgaji-1)."')";
      }
	  
	  /*if (($periode_bulan != "all")&&($periode_bulan!="")){
  		  $str .= " AND MONTH(absensiTanggal)=".$periode_bulan."";
      }
	  
	  if (($periode_tahun != "all")&&($periode_tahun!="")){
  		  $str .= " AND YEAR(absensiTanggal)=".$periode_tahun."";
      }*/
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str));
	    $res2 = $this->Open(stripslashes($result), array());
  	  #$res3 = sizeof($res2);
	     $res3 = $res2[0]['TOTAL']; 	
       if (!$res3) {
  		return 0;
  	} else {
  		return $res3;
  	}
  
   }
   
   function GetDataAbsenById($id) { 
      $res = $this->Open($this->mSqlQueries['get_data_absen_by_id'], array($id));
      return $res[0];    
   }
   
   function GetDataAbsensiHarian($offset, $limit, $nama,$input_type='all',$periode_bulan='',$periode_tahun='') { 
      $tglgaji = $this->Open("Select setValue From sdm_setting where setNama='tanggal_gajian' ", array()); $tglgaji=$tglgaji[0]['setValue'];
      if($nama != ""){
  		  $str = " AND (absensiPegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      
      if($input_type != "all"){
  		  $str .= " AND absensiIsManual=".$input_type."";
      }
	  
	  if (($periode_bulan != "all")&&($periode_bulan!="")&&($periode_tahun!="")){
  		  $str .= " AND absensiTanggal BETWEEN DATE_ADD('".$periode_tahun."-".$periode_bulan."-".$tglgaji."', INTERVAL -1 MONTH) AND DATE('".$periode_tahun."-".$periode_bulan."-".($tglgaji-1)."')";
      }
	  
	  /*if (($periode_bulan != "all")&&($periode_bulan!="")){
  		  $str .= " AND MONTH(absensiTanggal)=".$periode_bulan."";
      }
	  
	  if (($periode_tahun != "all")&&($periode_tahun!="")){
  		  $str .= " AND YEAR(absensiTanggal)=".$periode_tahun."";
      }*/
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array('%H:%i','%H:%i', $str, $offset, $limit));
      $res = $this->Open(stripslashes($result), array());
	  
      return $res;    
   }
   
   //EXCEL
   function GetDataSheet1($nama) {
    if($nama != ""){
  		  $str = " WHERE (absensiPegNama LIKE '%".$nama."%')";
      }else{
        $str = "";
      }
      
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data_sheet1'], array('%H:%i','%H:%i',$str));
		$res = $this->Open(stripslashes($result), array());
		return $res;
   }
   
//===DO==
	function DoAddAbsenManual($kode,$nama,$in,$out,$alasan) {
	  //echo sprintf($this->mSqlQueries['do_add_absen_manual'],$kode,$nama,$in,$out,$alasan);
		return $this->Execute($this->mSqlQueries['do_add_absen_manual'], array($kode,$nama,$in,$out,$alasan));
	}
	
	function DoUpdateAbsenManual($kode,$nama,$in,$out,$alasan,$id) {
	  //echo sprintf($this->mSqlQueries['do_add_absen_manual'],$kode,$nama,$in,$out,$alasan);
		return $this->Execute($this->mSqlQueries['do_update_absen_manual'], array($kode,$nama,$in,$out,$alasan,$id));
	}
	
	function DoDeleteAbsenManual($id) {
	  //echo sprintf($this->mSqlQueries['do_add_absen_manual'],$kode,$nama,$in,$out,$alasan);
		return $this->Execute($this->mSqlQueries['do_delete_absen_manual'], array($id));
	}
	
	function DoUpStatusDataByArray($arr,$per) {
		$result=$this->Execute($this->mSqlQueries['do_up_status_by_array_id'], array($arr,$per));
		return $result;
	}
	function DoUpStatusData($id,$per,$idGaji) {
		$this->StartTrans();
      $this->Execute($this->mSqlQueries['do_up_status'], array($id,$per));
      $this->Execute($this->mSqlQueries['update_pendapatan_pegawai'],array($idGaji,$id));
      $result = $this->EndTrans(true);
		//print_r($this->getLastError());
		return $result;                                                                                                                                                            
	}
	
	function DoUpdateData($cash, $tgl, $aktif, $id, $komponen) {
	  $this->StartTrans();
		$deleteKomponen = $this->Execute($this->mSqlQueries['do_delete_komponen'], array($id));
		$dateNow=date('Y').date('m').date('d');
	  for ($i=0; $i<sizeof($komponen); $i++){
		  $this->Execute($this->mSqlQueries['do_add_komponen'], array($id,$komponen[$i]['id'],$dateNow));
	  }
	  $this->Execute($this->mSqlQueries['do_update_data'], array($cash, $tgl, $aktif, $id));
		$result = $this->EndTrans(true);
	  //$debug = sprintf($this->mSqlQueries['do_update_gaji_pegawai'], $gaji_pegawaiKode, $gaji_pegawaiNama, $tipeunit, $satker, $gaji_pegawaiId);
	  //echo $debug;
		return $result;
	}
	
	function DoUpdateData2($rek, $bank, $id, $user) {
    $result = $this->Execute($this->mSqlQueries['do_add_data_2'], array($id, $bank, $rek, $user));
	  //$debug = sprintf($this->mSqlQueries['do_update_gaji_pegawai'], $gaji_pegawaiKode, $gaji_pegawaiNama, $tipeunit, $satker, $gaji_pegawaiId);
	  //echo $debug;
	  //print_r($this->getLastError());exit;
		return $result;
	}
	
	function DoUpdateData3($rek, $bank, $id, $user) {
    $result = $this->Execute($this->mSqlQueries['do_update_data_2'], array($rek, $bank, $user, $id));
	  //$debug = sprintf($this->mSqlQueries['do_update_gaji_pegawai'], $gaji_pegawaiKode, $gaji_pegawaiNama, $tipeunit, $satker, $gaji_pegawaiId);
	  //echo $debug;
		return $result;
	}
	
	function DoDeleteData($id,$per) {
		$result=$this->Execute($this->mSqlQueries['do_delete_data'], array($id,$per));
		return $result;
	}

	function DoDeleteDataByArrayId($arr,$per) {
		$id = implode("', '", $arr);
		$id2 = implode("', '", $per);
		$result=$this->Execute($this->mSqlQueries['do_delete_data_by_array_id'], array($id,$id2));
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
}
?>
