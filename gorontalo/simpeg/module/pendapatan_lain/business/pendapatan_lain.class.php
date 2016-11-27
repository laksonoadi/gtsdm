<?php

class PendapatanLain extends Database {

   protected $mSqlFile= 'module/pendapatan_lain/business/pendapatan_lain.sql.php';
   
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
   function GetCountData($nip_nama='', $jenis='',$idBulan, $idTahun) {
		  $awal = $idTahun.$idBulan;
		  if($jenis == "all"){
        $jenis = "";
      }
      if(($nip_nama != "") and ($jenis != "")){
		    $str = " WHERE (c.pegKodeResmi LIKE '%".$nip_nama."%' OR c.pegNama LIKE '%".$nip_nama."%') AND 
        a.pndptnlainJnsId = '".$jenis."' AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }elseif(($nip_nama == "") and ($jenis != "")){
        $str = " WHERE a.pndptnlainJnsId = '".$jenis."' AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }elseif(($nip_nama != "") and ($jenis == "")){
        $str = " WHERE (c.pegKodeResmi LIKE '%".$nip_nama."%' OR c.pegNama LIKE '%".$nip_nama."%') AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }else{
        $str = "WHERE EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   } 
   
   function GetData ($offset, $limit, $nip_nama='', $jenis='',$idBulan, $idTahun) { 
      $awal = $idTahun.$idBulan;
		  if($jenis == "all"){
        $jenis = "";
      }
      if(($nip_nama != "") and ($jenis != "")){
		    $str = " WHERE (c.pegKodeResmi LIKE '%".$nip_nama."%' OR c.pegNama LIKE '%".$nip_nama."%') AND 
        a.pndptnlainJnsId = '".$jenis."' AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }elseif(($nip_nama == "") and ($jenis != "")){
        $str = " WHERE a.pndptnlainJnsId = '".$jenis."' AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }elseif(($nip_nama != "") and ($jenis == "")){
        $str = " WHERE (c.pegKodeResmi LIKE '%".$nip_nama."%' OR c.pegNama LIKE '%".$nip_nama."%') AND EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }else{
        $str = "WHERE EXTRACT(YEAR_MONTH FROM a.pndptnlainTanggal)='".$awal."'";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $offset, $limit));
      $res2 = $this->Open(stripslashes($result), array());
      //print_r($this->getLastError());
      //print_r(stripslashes($result));
  		return $res2;    
   } 
   
   function GetComboJenis(){
      $result = $this->Open($this->mSqlQueries['get_combo_jenis'], array());
	    return $result;
   }
   
   function GetDataById($id,$tgl) {
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id,$tgl));
		return $result[0];
	 }
	 
	 function GetPegawaiById($id,$tgl) {
		$result = $this->Open($this->mSqlQueries['get_pegawai_by_id'], array($id,$tgl));
		return $result;
	 }   
	 
	 function GetTahun(){
		$year = date('Y')+3;
		$year2 = date('Y')-5;
		$no=0;
		for($i=$year;$i>$year2;$i--){
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
  
////DO
   function DoAddData($pegawai, $jenis, $nominal, $des, $tanggal, $id, $tglId) {
  	  if(empty($pegawai)){
        $result = $this->Execute($this->mSqlQueries['do_add_pendapatan'], array($jenis, $des, $tanggal));
      }else{
        $this->StartTrans();
    		for ($i=0; $i<sizeof($pegawai); $i++){
    		  $this->Execute($this->mSqlQueries['do_add_pegawai'], array($pegawai[$i]['id'], $jenis, $pegawai[$i]['nominal'], $des, $tanggal));
    	  }
    		$result = $this->EndTrans(true);
      }
  		return $result;
	 }
   
   function DoUpdateData($pegawai, $jenis, $nominal, $id, $tglId) {
  	  $this->StartTrans();
  		$this->Execute($this->mSqlQueries['do_delete_pegawai'], array($id, $tglId));
  	  for ($i=0; $i<sizeof($pegawai); $i++){
  		  $this->Execute($this->mSqlQueries['do_add_pegawai'], array($pegawai[$i]['id'], $jenis, $pegawai[$i]['nominal'], $id, $tglId));
  	  }
  		$result = $this->EndTrans(true);
  	  //$debug = sprintf($this->mSqlQueries['do_update_gaji_pegawai'], $gaji_pegawaiKode, $gaji_pegawaiNama, $tipeunit, $satker, $gaji_pegawaiId);
  	  //echo $debug;
  	  //print_r($this->getLastError());
  		return $result;
	 }  
   
    function Delete($id,$tgl) {
      $result = $this->Execute($this->mSqlQueries['do_delete_pegawai'], array($id,$tgl));	
      //print_r($this->getLastError());
      return $result;
    }
}
?>
