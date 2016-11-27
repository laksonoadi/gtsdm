<?php

class PphPegawaiKomp extends Database {

	protected $mSqlFile= 'module/ref_pph_pegawai_komponen/business/pphpegawaikomp.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		#
		
	}
	
	function GetDataDetailPegawai($id) { 
    $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
    return $result;
  }
		
	function getDataKompPeg($offset, $limit, $nip='',$nama='', $idTahun) {
    $jan = $idTahun.'01';
    $feb = $idTahun.'02';
    $mar = $idTahun.'03';
    $apr = $idTahun.'04';
    $mei = $idTahun.'05';
    $jun = $idTahun.'06';
    $jul = $idTahun.'07';
    $agu = $idTahun.'08';
    $sep = $idTahun.'09';
    $okt = $idTahun.'10';
    $nov = $idTahun.'11';
    $des = $idTahun.'12';
    $result = $this->Open($this->mSqlQueries['get_data_komp_peg'], array($jan,$feb,$mar,$apr,$mei,$jun,$jul,$agu,$sep,$okt,$nov,$des,'%'.$nip.'%', '%'.$nama.'%', $offset, $limit));
    return $result;
	}
	
	function GetDataKompPtkpByPegId($pegId,$pegJnskel) {
		$result = $this->Open($this->mSqlQueries['get_data_komp_ptkp_by_peg_id'], array($pegId, $pegJnskel));
    return $result;
	}

	function getCountKompPeg($nip, $nama) {
		$result = $this->Open($this->mSqlQueries['get_count_data_pphrp'], array('%'.$nip.'%', '%'.$nama.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
		
	}

	function GetDataKompPegById($idPeg,$month,$year) {
		//$result = $this->Open($this->mSqlQueries['get_data_komp_peg_by_id'], array($idPeg, $idPeg));
		$awal = $year.$month;
    $result = $this->Open($this->mSqlQueries['get_data_komp_peg_by_id'], array($idPeg,$awal));
    return $result;
	}

	function GetDataRincian($idPeg,$month,$year) {
	  $awal = $year.$month;
		$result = $this->Open($this->mSqlQueries['get_data_pph_pegawai_komponen'], array($idPeg,$awal));
		return $result;
	}
	
	function GetComboKomponen() {
		$result=$this->Open($this->mSqlQueries['get_combo_komponen'], array());
		return $result;
	}
	
	function GetMaxValue($idFormula) {
		$result=$this->Open($this->mSqlQueries['get_max_value'], array($idFormula));
		return $result;
	}
	
	function GetJumlahNominal($idPegawai,$year,$month) {
		$awal = $year.$month;
    $result=$this->Open($this->mSqlQueries['get_jumlah_nominal'], array($idPegawai,$awal));
		return $result;
	}
	
	function GetDataPotongan(){
	$result=$this->Open($this->mSqlQueries['get_data_potongan'], array());
		return $result;
	}
	
	function GetDataPegPot($idPeg,$month,$year){
	$awal = $year.$month;
	$result=$this->Open($this->mSqlQueries['get_data_peg_pot'], array($idPeg,$awal));
		return $result;
	}
	
	function GetTotalGaji($idPeg){
	$result=$this->Open($this->mSqlQueries['get_total_gaji'], array($idPeg,$idPeg));
		return $result;
	}
	
	function GetGajiPokok($idPeg){
	  $result=$this->Open($this->mSqlQueries['get_gaji_pokok'], array($idPeg));
		return $result;
	}
	
	//untuk revisi---
	function GetKomponenGajiPegawai($id){
      return $this->Open($this->mSqlQueries['get_komponen_gaji_peg'],array($id));
   }
   
   function GetFormulaPph($id){
      $formula = $this->Open($this->mSqlQueries['get_formula_pph'],array($id));
      return $formula[0]['formula'];
   }
   
   function GetBulanMasaKerjaPerTahun($id){
      $return = $this->Open($this->mSqlQueries['get_bulan_masa_kerja_per_tahun'],array($id));
      return $return[0]['masa_kerja'];
   }
   
   function GetTahun(){
    $year = date('Y')+4;
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
   
	//---------
//===DO==
	
	function DoAddPphPegawaiKomp($idpegawai, $idkomp, $nominal, $periode, $userId ) {
	//
		$result = $this->Execute($this->mSqlQueries['do_add_pphkomp'], array($idpegawai, $idkomp, $nominal, $periode, $userId));
	// 
		return $result;
	}
	
	function DoAddPegawaiPotongan($idpegawai, $nilai, $nilai_no_npwp, $periode) { 
		$result = $this->Execute($this->mSqlQueries['do_add_pegawai_potongan'], array($idpegawai, $nilai, $nilai_no_npwp, $periode));
		return $result;
	}
	
	function DoUpdatePegawaiPotongan($idpegawai, $nilai, $nilai_no_npwp, $periode, $idPot) {
	//
		$result = $this->Execute($this->mSqlQueries['do_update_pegawai_potongan'], array($idpegawai, $nilai, $nilai_no_npwp, $periode, $idPot));
		return $result;
	// 
	}
	
	function DoDeletePphPegawaiKompById($pphKompId) {
		$result=$this->Execute($this->mSqlQueries['do_delete_pphkomp_by_id'], array($pphKompId));
		return $result;
	}
}
?>
