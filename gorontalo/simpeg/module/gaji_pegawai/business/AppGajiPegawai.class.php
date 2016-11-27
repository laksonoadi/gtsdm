<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class AppGajiPegawai extends Database {

	protected $mSqlFile= 'module/gaji_pegawai/business/appgajipegawai.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
		
		$this->listIdPeg=$this->GetListIdPegByUserId();
		//
	}
	
	function GetListIdPegByUserId(){
		$Obj = new DataPegawai;
		$this->totalData = $Obj->GetCountPegawaiByUserId('', 'all');
	    $result = $Obj->GetDataPegawaiByUserId(0, $this->totalData, '', 'all');
		
		$list='0';
		for ($i=0; $i<sizeof($result); $i++){
			$list .=','.$result[$i]['id'];
		}
		return $list;
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
		  
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama != "") and ($satkerja == "")){
		  $str = " WHERE (a.pegKodeResmi LIKE '%".$nip_nama."%' OR a.pegNama LIKE '%".$nip_nama."%')";
		  //AND d.satkerpegSatkerId IS NULL";
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama == "") and ($satkerja != "")){
		  $str = " WHERE d.satkerpegSatkerId = '".$satkerja."'";
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
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
		  
		  $str2 .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		  
	    }elseif(($str != "") and ($jenis != "") and ($jenis != "all")){
	      if($jenis != "ns"){
	        $str2 = " AND e.gajipegStatus = '".$s."' AND EXTRACT(YEAR_MONTH FROM e.gajipegPeriode)='".$awal."'";
	      }else{
	        $str2 = " AND e.gajipegStatus is NULL";
	      }
		  
		  $str2 .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		  
	    }else{
	      $str2 = "";
		  if ($str=='') $str2 .= ' WHERE a.pegId IN ('.$this->listIdPeg.') ';
	    }
		
    
		//$result = $this->Open($this->mSqlQueries['get_data'], array($awal, $akhir,'%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%', $offset, $limit));
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($awal, $awal, $awal, $str, $str2, $offset, $limit));
		//return $result;
		$res = $this->Open(stripslashes($result), array());
		//print_r(stripslashes($result));
		return $res;
	}

	function GetDataCetak($nip_nama='', $satkerja='', $jenis='',$idBulan, $idTahun) {
		$awal = $idTahun.$idBulan;
		if(($nip_nama != "") and ($satkerja != "")){
		  $str = " WHERE (a.pegKodeResmi LIKE '%".$nip_nama."%' OR a.pegNama LIKE '%".$nip_nama."%') AND
		  d.satkerpegSatkerId = '".$satkerja."'";
		  
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama != "") and ($satkerja == "")){
		  $str = " WHERE (a.pegKodeResmi LIKE '%".$nip_nama."%' OR a.pegNama LIKE '%".$nip_nama."%')";
		  //AND d.satkerpegSatkerId IS NULL";
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama == "") and ($satkerja != "")){
		  $str = " WHERE d.satkerpegSatkerId = '".$satkerja."'";
		  $str .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
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
		  
		  $str2 .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		  
	    }elseif(($str != "") and ($jenis != "") and ($jenis != "all")){
	      if($jenis != "ns"){
	        $str2 = " AND e.gajipegStatus = '".$s."' AND EXTRACT(YEAR_MONTH FROM e.gajipegPeriode)='".$awal."'";
	      }else{
	        $str2 = " AND e.gajipegStatus is NULL";
	      }
		  
		  $str2 .= ' AND a.pegId IN ('.$this->listIdPeg.') ';
		  
	    }else{
	      $str2 = "";
		  if ($str=='') $str2 .= ' WHERE a.pegId IN ('.$this->listIdPeg.') ';
	    }
		
    
		//$result = $this->Open($this->mSqlQueries['get_data'], array($awal, $akhir,'%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%', $offset, $limit));
		$result = $this->GetQueryKeren($this->mSqlQueries['get_data_cetak'], array($awal, $awal, $awal, $str, $str2,$awal));
		//return $result;
		$res = $this->Open(stripslashes($result), array());
		//print_r(stripslashes($result));
		//print_r($this->getLastError());
		return $res;
		
	}
	
	function GetCountData($nip_nama='', $satkerja='', $jenis='') {
		//$result = $this->Open($this->mSqlQueries['get_count_data'], array('%'.$nip_nama.'%', '%'.$nip_nama.'%', '%'.$unitkerja.'%'));
      //$result = sizeof($result);   
		if(($nip_nama != "") and ($satkerja != "")){
		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND satkerpegSatkerId = '".$satkerja."'";
		  $str .= ' AND pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama != "") and ($satkerja == "")){
		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND satkerpegSatkerId IS NULL";
		  $str .= ' AND pegId IN ('.$this->listIdPeg.') ';
		}elseif(($nip_nama == "") and ($satkerja != "")){
		  $str = " WHERE satkerpegSatkerId = '".$satkerja."'";
		  $str .= ' AND pegId IN ('.$this->listIdPeg.') ';
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
	  $str2 .= ' AND pegId IN ('.$this->listIdPeg.') ';
    }elseif(($str != "") and ($jenis != "") and ($jenis != "all")){
      if($jenis != "ns"){
        $str2 = " AND gajipegStatus = '".$s."'";
      }else{
        $str2 = " AND gajipegStatus is NULL";
      }
	  $str2 .= ' AND pegId IN ('.$this->listIdPeg.') ';
    }else{
      $str2 = "";
	  if ($str=='') $str2 .= ' WHERE pegId IN ('.$this->listIdPeg.') ';
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

	function GetDataById($id) {
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));
		return $result[0];
	}

	function GetTotalPegawaiAktif() {
		$result = $this->Open($this->mSqlQueries['get_total_pegawai_aktif'], array());
		//return $result[0]['total'];
		return $this->totalData;
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
		return $bulan;
	}
	
	function GetKomponenById($id) {
		$result = $this->Open($this->mSqlQueries['get_komponen_by_id'], array($id));
		return $result;
	}
	
	function getDataKomponen() {
		$result = $this->Open($this->mSqlQueries['get_data_komponen'], array());
		return $result;
	}
	
	function getDataKomponenIsi($jenis='', $nip_nama, $periode) {
		//satu periode
              if($jenis == "sudah"){
		  $s = 1;
		}elseif($jenis == "belum"){
		  $s = 0;
		}elseif($jenis == "ns"){
		  $s = "";
		}
    
	      if($jenis != "ns"){
	        $str2 = " AND a.gajipegStatus = '".$s."' ";
	      }else{
	        $str2 = " ";
	      }

              $str_tampilkan = $str2." AND EXTRACT(YEAR_MONTH FROM a.gajipegPeriode) = '".$periode."' ";
		$sql = $this->GetQueryKeren($this->mSqlQueries['get_data_komponen_isi'], array($nip_nama, $str_tampilkan));
		$sql = stripslashes($sql);
		$result = $this->Open($sql, array());
		//echo $sql;
		return $result;
	}
	
	function getDataNominalPendapatanlain($idpeg,$periode) {
		$result = $this->Open($this->mSqlQueries['get_data_nominal_pendapatan_lain'], array($idpeg,$periode));
		//print_r($this->getLastError());
		return $result;
	}
	
	function getId($id){
		$result = $this->Open($this->mSqlQueries['cek_data'], array($id)); 
	    return $result;
	}
   
	function GetGajiPegawaiDetailById($id){
		$result = $this->Open($this->mSqlQueries['get_gaji_pegawai_det_by_id'], array($id)); 
	    return $result[0];
	}

//===DO==
	
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
		//$this->StartTrans();
		$deleteKomponen = $this->Execute($this->mSqlQueries['do_delete_komponen'], array($id));
		$dateNow=date('Y').date('m').date('d');
		for ($i=0; $i<sizeof($komponen); $i++){
			$this->Execute($this->mSqlQueries['do_add_komponen'], array($id,$komponen[$i]['id'],$dateNow));
		}
		$result=$this->Execute($this->mSqlQueries['do_add_data'], array($cash, $tgl, $aktif, $id));
		if (!$result){
			$result=$this->Execute($this->mSqlQueries['do_update_data'], array($cash, $tgl, $aktif, $id));
		}
		//$this->EndTrans(true);
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
}
?>
