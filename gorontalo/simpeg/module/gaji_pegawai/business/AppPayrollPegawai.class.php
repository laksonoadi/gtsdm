<?php

class AppPayrollPegawai extends Database {

  protected $mSqlFile= 'module/gaji_pegawai/business/apppayrollpegawai.sql.php';
  
  function __construct($connectionNumber=0) {
    parent::__construct($connectionNumber);
    //$this->mrDbEngine->debug = 1;
    //
  }
  
  //===GET DATA===
  function GetBidataPegawaiById($id) {
    return $this->Open($this->mSqlQueries['get_biodata_pegawai_by_id'], array($id,$id));
  }
  
  function GetKomponenGajiPegawai($id){
    return $this->Open($this->mSqlQueries['get_komponen_gaji_peg'],array($id));
  }
  
  function GetFormulaGaji($id,$nip=""){
    $formula = $this->Open($this->mSqlQueries['get_formula_gaji'],array($id,$nip));
	if (sizeof($formula)<=0) return 0;
    return $formula[0]['kompformFormula'];
  }
  
  function CekFormulaGaji($id,$nip="",$periode=""){
    if ($periode=="") $periode=date('Y-m').'-01';
    $formula = $this->Open($this->mSqlQueries['cek_formula_gaji'],array($id,$nip,$periode));
    return $formula[0];
  }
  
  function CekGaji($nip="",$periode=""){
    if ($periode=="") $periode=date('Y-m').'-01';
    $formula = $this->Open($this->mSqlQueries['cek_gaji'],array($nip,$periode));
    return $formula[0]['sudah'];
  }
  
  function GetKomponenGaji(){
    return $this->Open($this->mSqlQueries['get_komponen_gaji'],array());
  }
  
  function GetDataPendapatan($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_pendapatan'],array($id,$month,$year));
    return $result;
  }
  
  function GetDataPendapatanTotal($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_pendapatan_total'],array($id,$month,$year));
    return $result;
  }
  
  function GetDataTunjanganThr($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_tunjangan_thr'],array($id,$month,$year));
    return $result;
  }
  
  function GetDataPotonganGaji($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_potongan_gaji'],array($id,$month,$year));
    return $result;
  }
  
  function GetDataPotonganGajiTotal($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_potongan_gaji_total'],array($id,$month,$year));
    return $result;
  }
  
  function GetDataTunjanganPph($id,$month,$year){
    $result = $this->Open($this->mSqlQueries['get_data_tunjangan_pph'],array($id,$month,$year));
    return $result;
  }
  
  function GetGapokSwasta($id){
    $result = $this->Open($this->mSqlQueries['get_gapok_swasta'],array($id));
    return $result;
  }
  
  function GetGapokNegeri($id){
    $result = $this->Open($this->mSqlQueries['get_gapok_negeri'],array($id));
    //print_r($this->getLastError());exit;
    return $result;
  }
  
  function GetVariabelPegawai($nip){
    $result = $this->Open($this->mSqlQueries['get_variabel_pegawai'],array($nip));
    return $result[0];
  }
  
  function GetJumlahHari($periode='',$pegId){
    if ($periode=='') $periode=date('Y-m');
	$from=$this->Open($this->mSqlQueries['get_tanggal_awal'],array($periode,$pegId)); $from=$from[0]['awal'];
	$end=$this->Open($this->mSqlQueries['get_tanggal_akhir'],array($periode,$pegId)); $end=$end[0]['akhir'];
	$tgl=$from;
	$count=0;
	$Hadir=0;
	$kategori0=0;
	$kategori1=0;
	$kategori2=0;
	$kategori3=0;
	while (true){
		$isLewat=$this->Open($this->mSqlQueries['is_lewat'],array($end,$tgl)); $isLewat=$isLewat[0]['isLewat'];
		if ($isLewat>=0){
			$isLibur=$this->Open($this->mSqlQueries['is_libur'],array($tgl,$tgl)); $isLibur=$isLibur[0]['isLibur'];
			if ($isLibur==0) {
				$count=$count+1;
				$IsMasuk=$this->Open($this->mSqlQueries['is_masuk'],array($pegId,$tgl)); 
				$IsKategori=$IsMasuk[0]['kategori'];
				$IsIzin=$IsMasuk[0]['IsIzin'];
				$IsMasuk=$IsMasuk[0]['IsMasuk'];
				
				if ($IsMasuk==1){
					if (in_array($IsIzin,array(0,3))) $Hadir += 1;
					
					if ($IsIzin==2) { $kategori2 +=1; }else //Jika Tidak Hadir Dengan keterangan atau Sakit Tanpa Surat Keterangan Dokter
					if ($IsIzin==3) { $Hadir -=1; } //Jika Hadir Tidak Mendapat Uang Makan
				
					if ($IsKategori==1) $kategori1 +=1;
					if ($IsKategori==2) $kategori2 +=1;
				}else{
					$kategori0 += 1;
				}
			}
		}else{
			break;
		}		
		$tgl=$this->Open($this->mSqlQueries['next_tanggal'],array($tgl)); $tgl=$tgl[0]['tgl'];
	}
	
    return array($count,$Hadir,$kategori1,$kategori2,$kategori0,$kategori3);
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
  
  function getIdPeg($idPegawai){
    $result = $this->Open($this->mSqlQueries['get_id_gaji_pegawai_by_periode'],array($idPegawai));
    return $result;
  }
  
  function GetIdDetilGajiPegPer($id, $idFormula){
    $result = $this->Open($this->mSqlQueries['get_id_detil_gaji_pegawai_by_periode'],array($idPegawai));
    return $result;
  }
  
  function GetMaxIdGajiPeg(){
    $res = $this->Open($this->mSqlQueries['get_max_id_gaji_pegwai_mst'],array());
    return $res[0]['id'];
  }
  
  //===DO===
  function AddDetailGajiPegwai($idGaji,$idFormula,$nominal,$isManual,$periode,$idPegawai,$aa){
    if($aa=="t"){
      $result=$this->Execute($this->mSqlQueries['add_detail_gaji_pegawai'],array($idGaji,$idFormula,$nominal,$isManual,$periode));
    }else{
      $result = $this->Execute($this->mSqlQueries['add_detail_gaji_pegawai'],array($idGaji,$idFormula,$nominal,$isManual,$periode));
    }
    //print_r($this->getLastError());exit;
    return $result;
  }
  
  function AddDetailGajiPegwaiGapok($idGaji,$periode,$gapok){
    $id1=NULL;
    $id2="Tidak";
    $result = $this->Execute($this->mSqlQueries['add_detail_gaji_pegawai'],array($idGaji,$id1,$gapok,$id2,$periode));
    return $result;
  }
  
  function AddGajiPegwaiMst($idPegawai,$total,$periode){        
    $result = $this->Execute($this->mSqlQueries['insert_gaji_pegwai_mst'],array($idPegawai, $total, $periode));
    return $result;
  }
  
  function UpdateDetailGajiPegwai($idPegawai,$idFormula,$nominal,$isManual,$periode,$id){
    $result = $this->Execute($this->mSqlQueries['update_gaji_pegawai_det'],array($nominal,$isManual,$periode,$idPegawai,$idFormula,$id));
    return $result;
  }
  
  function UpdateGajiPegwaiMst($id,$total){
    $result = $this->Execute($this->mSqlQueries['update_gaji_pegawai_mst'],array($total, $id));     
    return $result;
  }
  
  function DeleteDetailGajiPegawai($id){
    return $this->Execute($this->mSqlQueries['delete_detail_gaji_pegawai'],array($id));
  }
}
?>
