<?php
//require_once 'module/komponen_gaji/business/AppKepegawaianKomponenGaji.class.php';

class AppDetilGaji extends Database {

	protected $mSqlFile= 'module/komponen_gaji/business/appdetilgaji.sql.php';
   // integrasi dengan gtsdm
   private $Obj;
	
	function __construct($connectionNumber=0) {
      //$this->Obj = new AppKepegawaianKomponenGaji;
		parent::__construct($connectionNumber);
		//
		//$this->mrDbEngine->debug = 1;
	}

	function GetData($offset, $limit, $kid, $kode='0', $nama='') {
		#printf($this->mSqlQueries['get_data'],$kid, $kode, $nama, $offset, $limit);
		return $this->Open($this->mSqlQueries['get_data'], array($kid, '%'.$kode.'%', '%'.$nama.'%', $offset, $limit));
	}

	function GetCountData($kid, $kode='', $nama='') {
		$result = $this->Open($this->mSqlQueries['get_count_data'], array($kid, '%'.$kode.'%', '%'.$nama.'%'));
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}

	function GetDataById($id) {
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id));
		return $result[0];
	}

	function GetInfo($id) {
		$result = $this->Open($this->mSqlQueries['get_info'], array($id));
		if ($result[0]['otomatis']==1){
			$this->InputOtomatisDetailKomponenFromReferensi($id,$result[0]['kode'],$result[0]['nama'],$result[0]['arr_table']);
		}
		return $result[0];
	}
	
	function InputOtomatisDetailKomponenFromReferensi($id,$kode,$nama,$tipe_referensi){
		if ($tipe_referensi=='pangkat_golongan'){
			$table_name='sdm_ref_pangkat_golongan';
			$field_id='pktgolrId';
			$field_name='pktgolrId';
		}else if ($tipe_referensi=='jabatan_fungsional'){
			$table_name='pub_ref_jabatan_fungsional';
			$field_id='jabfungrId';
			$field_name='jabfungrNama';
		}else if ($tipe_referensi=='jabatan_struktural'){
			$table_name='sdm_ref_jabatan_struktural';
			$field_id='jabstrukrId';
			$field_name='jabstrukrNama';
		}else if ($tipe_referensi=='pendidikan'){
			$table_name='pub_ref_pendidikan';
			$field_id='pendId';
			$field_name='pendNama';
		}
		
		$query="SELECT ".$field_id." as id, ".$field_name." as name FROM ".$table_name." ORDER BY ".$field_id;
		$arrDetil=$this->Open($query, array());
		
		for ($i=0; $i<sizeof($arrDetil); $i++){
			$kodeDetail=$kode.'-'.$arrDetil[$i]['id'];
			$query_cek="SELECT COUNT(*) as total FROM sdm_ref_komponen_gaji_detail WHERE kompgajidtKode='".$kodeDetail."'";
			$cek=$this->Open($query_cek, array()); $total=$cek[0]['total'];
			if ($total==0){
				$query_add="INSERT INTO sdm_ref_komponen_gaji_detail(kompgajidtKompgajiId,kompgajidtKode,kompgajidtNama,kompgajidtStatusSeting)";
				$query_add .= " VALUES (".$id.",'".$kodeDetail."','".$nama." ".$arrDetil[$i]['name']."','nominal')";
				$insert=$this->Execute($query_add, array());
			}else{
				$query_update="UPDATE sdm_ref_komponen_gaji_detail SET kompgajidtNama='".$nama." ".$arrDetil[$i]['name']."' WHERE kompgajidtKode='".$kodeDetail."'";
				$update=$this->Execute($query_update, array());
			}
		}
		
	}
	
	function GetKomponenGajiOtomatis($pegId,$periode=""){
	    if ($periode=="") $periode=date('Y-m');
		$query_komp=$this->mSqlQueries['query_komp'];
		$query_lama=$this->mSqlQueries['query_lama'];
		$query_delete=$this->mSqlQueries['query_delete'];
		$query_insert=$this->mSqlQueries['query_insert'];
		
		$komponen_otomatis=$this->Open($this->mSqlQueries['get_komponen_pegawai_detail'],array($periode,$periode,$periode,$periode,$periode,$pegId));
		$tunjangan_studi=$this->Open($this->mSqlQueries['get_tunjangan_studi'],array($pegId));
		$komponen_otomatis[0]['bea']=$tunjangan_studi[0]['bea'];
		
		$var=array('pktgol','jabfung','jenisjbtn','jabstruk','pend','jabfung','idg','thp','tbk','jabstruk','jabstruk2','bea','pktgol','pktgol');
		$kode=array('GOL','JFU','JJB','JST','PEND','HON','IDG','THP','TBK','TTJ','JSTR','BEA','TTKY','TVKY');
		
		for ($i=0; $i<sizeof($var); $i++){
			$id[$var[$i]]=$komponen_otomatis[0][$var[$i]];
			#Get apakah sebelumnya sudah ada komponen gaji terkait
			$lama=$this->Open($query_lama,array($kode[$i],$pegId));
			#Get komponen untuk yang baru
			$baru=$this->Open($query_komp,array($kode[$i].'-'.$id[$var[$i]]));
			#cek apakah komponen baru itu sama dengan komponen lama, jika tidak sama maka yang lama dihapus dan tambahkan yang baru
			if (($lama[0]['id_komponen']=='')&&($baru[0]['id_komponen']!='')){
				$insert=$this->Execute($query_insert,array($pegId,$baru[0]['id_komponen']));
			}elseif (($lama[0]['id_komponen']!=$baru[0]['id_komponen'])&&($baru[0]['id_komponen']!='')){
				$delete=$this->Execute($query_delete,array($lama[0]['id']));
				$insert=$this->Execute($query_insert,array($pegId,$baru[0]['id_komponen']));
			}elseif (($lama[0]['id_komponen']!=$baru[0]['id_komponen'])&&($baru[0]['id_komponen']=='')){
				$delete=$this->Execute($query_delete,array($lama[0]['id']));
			}
			
		}
	}
   
//===DO==
	
	function DoAddData($arr) {
		$result = $this->Execute($this->mSqlQueries['do_add_data'], $arr);
		return $result;
	}
	
	function DoUpdateData($arr) {
		$result = $this->Execute($this->mSqlQueries['do_update_data'], $arr);
		return $result;
	}
	
	function DoDeleteData($id) {
		$result=$this->Execute($this->mSqlQueries['do_delete_data'], array($id));
		return $result;
	}

	function DoDeleteDataByArrayId($arrId) {
		$id = implode("', '", $arrId);
		$result=$this->Execute($this->mSqlQueries['do_delete_data_by_array_id'], array($id));
		return $result;
	}
}
?>
