<?php

class MutasiPak extends Database {

	protected $mSqlFile= 'module/mutasi_pak_kumulatif/business/mutasi_pak.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);  
		//   
	}
     
	function GetListPegawai($tampilkan, $start, $limit) {   
		$result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
		return $result;
	}
   
	function GetDataDetail($id) { 
		$result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
		return $result;
	}
   
	function getDataKegiatan($start, $limit,$nama) {   
		$result = $this->Open($this->mSqlQueries['get_list_kegiatan'], array('%'.$nama.'%', $start, $limit));      
		//echo vsprintf($this->mSqlQueries['get_list_kegiatan'], array('%'.$nama.'%', $start, $limit));
		return $result;
	}
	
	function GetListMutasiPak($id) { 
		$result = $this->Open($this->mSqlQueries['get_list_mutasi_pak'], array($id));
		return $result;
	}
   
	function GetDataMutasiById($id,$dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_mutasi_pak_by_id'], array($id,$dataId));
		return $result;
	}
      
	function GetCount($tampilkan) {
		$result = $this->Open($this->mSqlQueries['get_count_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%'));
		return $result[0]['total'];     
	}
   
	function GetCountDataKegiatan($nama) {
		$result = $this->Open($this->mSqlQueries['get_count_kegiatan'], array('%'.$nama.'%'));
		return $result[0]['total'];     
	}
   
	function GetCountMutasi($id) {
		$result = $this->Open($this->mSqlQueries['get_count_mutasi'], array($id));
		return $result[0]['total'];     
	}
   
	function GetComboUnitKerja() {
		$result = $this->Open($this->mSqlQueries['get_combo_unit_kerja'], array());
		return $result;
	}
   
	function GetComboKegiatan() {
		$result = $this->Open($this->mSqlQueries['get_combo_kegiatan'], array());
		return $result;
	}
   
	function GetComboJabatan($pegId) {
		$result = $this->Open($this->mSqlQueries['get_combo_jabatan'], array($pegId));
		return $result;
	}
   
	function GetDataUnsurPenilaian($dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_unsur_penilaian'], array($dataId));
		return $result;
	}
	
	function GetDataUnsurPenilaianGroup1($pegId,$dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_unsur_penilaian_group1'], array($dataId));
		return $result;
	}
	
	function GetDataUnsurPenilaianGroup2($pegId,$dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_unsur_penilaian_group2'], array($dataId));
		return $result;
	}
	
	function GetDataUnsurPenilaianLamaGroup2($pegId,$dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_unsur_penilaian_lama_group2'], array($pegId,$dataId));
		return $result;
	}
   
	function GetIdStruk($id) {      
		$result = $this->Open($this->mSqlQueries['get_id_struk'], array($id)); 
		if($result)
			return $result[0];
		else
			return $result;	  
	}
	
	function CekPAKSebelum($pegId) {
		$result = $this->Open($this->mSqlQueries['cek_pak_sebelum'], array($pegId));
		if (empty($result)) return false;
		return true;
	}
   
//===============do======================//   
	function Add($data) {	   
		$return = $this->Execute($this->mSqlQueries['do_add'], $data);
		//	  
		return $return;
	}  
	
	function Update($data) {
		$return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		// 
		return $return;
	}  

	function Approved($data) {
		$return = $this->Execute($this->mSqlQueries['do_approved'], $data);         		  
		// 
		return $return;
	}  
   
	function AddUnsur($data) {	   
		$return = $this->Execute($this->mSqlQueries['do_add_unsur'], $data);
		//	  
		return $return;
	}
	
	function AddUnsurRelasi($data) {	   
		$return = $this->Execute($this->mSqlQueries['do_add_unsur_relasi'], $data);
		//	  
		return $return;
	}
   
	function UpdateUnsur($data) {
		$return = $this->Execute($this->mSqlQueries['do_update_unsur'], $data);
		//         		  
		return $return;
	}  
	
	function Delete($id) {
		$ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
		//exit; 
		return $ret;
	}
	
	function DeleteUnsur($id) {
		$ret = $this->Execute($this->mSqlQueries['do_delete_unsur'], array($id));		
		//exit; 
		return $ret;
	}
	
	function GetMaxId(){
		$result = $this->Open($this->mSqlQueries['get_max_id'],array());
		return $result[0]['MAXID'];
	}
	
	function getMaxIdBypegId($id){
		$result = $this->Open($this->mSqlQueries['get_max_id_by_peg_id'],array($id));
		return $result[0]['MAXID'];
	}
	
	function GetIdLain($id1,$id2){
		$result = $this->Open($this->mSqlQueries['get_id_lain'],array($id1,$id2));
		return $result;
	}
	
	function SinkronisasiKegiatan($pegId="",$sincPengajaran=array(),$sincBimbingan=array()){
		$result=true;
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_pendidikan'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_organisasi'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_seminar'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_produk'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_pimpinan'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_penghargaan'],array($pegId));
		if ($result) $result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_pengabdian'],array($pegId));
		if ($result){
			for ($i=0; $i<sizeof($sincPengajaran); $i++){
				$result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_pengajaran'],$sincPengajaran[$i]);
			}
			
			for ($i=0; $i<sizeof($sincBimbingan); $i++){
				$result = $this->Execute($this->mSqlQueries['sinkronisasi_sdm_pak_bimbingan'],$sincBimbingan[$i]);
			}
		}
		return $result;
	}
	
	function SinkronisasiKegiatanIntegrasiAkademik($pegId,$jabatan,$nip){
		$result['pengajaran'] = $this->Open($this->mSqlQueries['get_sinkronisasi_sdm_pak_pengajaran_integrasi_akademik'],array($pegId,$pegId,$jabatan,$nip));
		$result['bimbingan'] = $this->Open($this->mSqlQueries['get_sinkronisasi_sdm_pak_bimbingan_integrasi_akademik'],array($pegId,$pegId,$nip,$pegId,$pegId,$nip));
		return $result;
	}
}
?>
