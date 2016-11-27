<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class PersetujuanBkd extends Database {

   protected $mSqlFile= 'module/persetujuan_bkd/business/persetujuan_bkd.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
   function GetListPegawai($tampilkan, $start, $limit) {   
     $result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
     return $result;
   }
   
   function GetPegawaiFull($id) {   
     $result = $this->Open($this->mSqlQueries['get_pegawai_full'], array($id));       
     return $result;
   }
   
   function GetPegInBkd($id) {   
     $result = $this->Open($this->mSqlQueries['get_pegawai_bkd'], array($id));       
     return $result;
   }
   
   function GetListBkd($id) {   
     $result = $this->Open($this->mSqlQueries['get_list_bkd'], array($id));       
     return $result;
   }
   
   function GetDetailBkd($id) {   
     $result = $this->Open($this->mSqlQueries['get_detail_bkd'], array($id));       
     return $result;
   }
   
   function GetDataDetailBkdDosen($id,$dataId) {   
     $result = $this->Open($this->mSqlQueries['get_data_detail_bkd_dosen'], array($id,$dataId));       
     return $result;
   }

   function GetDataDetail($id) { 
   $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
   return $result;
   }
   
   function GetListMutasiSatuanKerja($id) {
   $result = $this->Open($this->mSqlQueries['get_list_mutasi_satuan_kerja_pegawai'], array($id));
   
   return $result;
      
   }
   
   function GetDataMutasiById($id,$dataId) {
   $result = $this->Open($this->mSqlQueries['get_data_mutasi_satuan_kerja_pegawai_by_id'], array($id,$dataId));
   return $result;
      
   }

   function GetDataMutasiBkdById($id,$dataId) {
     $result = $this->Open($this->mSqlQueries['get_data_mutasi_bkd_by_id'], array($id,$dataId));
     //
     return $result;
   }
         
   function GetCount($tampilkan) {
     $result = $this->Open($this->mSqlQueries['get_count_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%'));
     return $result[0]['total'];     
   }
   
   function GetCountMutasi($id) {
     $result = $this->Open($this->mSqlQueries['get_count_mutasi'], array($id));
     return $result[0]['total'];     
   }
   
   function CountPegInBkd($id) {
     $result = $this->Open($this->mSqlQueries['count_peg_bkd'], array($id));
     return $result[0]['cekCountPeg'];     
   }
   
   function GetComboSatuanKerja() {
		$result = $this->Open($this->mSqlQueries['get_combo_satuan_kerja'], array());
		// $this->Obj = new SatuanKerja();
	    // $result = $this->Obj->GetSatuanKerjaByUserId();
		return $result;
   }
   
   function GetComboRekomendasi() {
		$result = $this->Open($this->mSqlQueries['get_combo_rekomendasi'], array());
		// $this->Obj = new SatuanKerja();
	    // $result = $this->Obj->GetSatuanKerjaByUserId();
		return $result;
   }

	function GetTahun(){
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


// GET DATA PENDIDIKAN START ------------------------------------------------------------------------------------------------------------------------------
   function GetDataPendidikan($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_pendidikan'], array($id));
      return $result;
   }
// GET DATA PENDIDIKAN END --------------------------------------------------------------------------------------------------------------------------------
	
// GET DATA PENELITIAN START ------------------------------------------------------------------------------------------------------------------------------
   function GetDataPenelitian($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_penelitian'], array($id));
      return $result;
   }
// GET DATA PENELITIAN END --------------------------------------------------------------------------------------------------------------------------------
	
// GET DATA PENGABDIAN START ------------------------------------------------------------------------------------------------------------------------------
   function GetDataPengabdian($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_pengabdian'], array($id));
      return $result;
   }
// GET DATA PENGABDIAN END --------------------------------------------------------------------------------------------------------------------------------
	
// GET DATA PENUNJANG START ------------------------------------------------------------------------------------------------------------------------------
   function GetDataPenunjang($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_penunjang'], array($id));
      return $result;
   }
// GET DATA PENUNJANG END --------------------------------------------------------------------------------------------------------------------------------
	
// GET DATA PROFESOR START ------------------------------------------------------------------------------------------------------------------------------
   function GetDataProfesor($id) { 
      $result = $this->Open($this->mSqlQueries['get_data_profesor'], array($id));
      return $result;
   }
// GET DATA PROFESOR END --------------------------------------------------------------------------------------------------------------------------------
	



//==================================================================== INSERT ======================================================================//   
   function Add($data) {
      $return = $this->Execute($this->mSqlQueries['do_add'], 
			array(
				$data['id'],
				$data['nosertf'],
				$data['nama'],
				$data['nip'],
				$data['nidn'],
				$data['nmPt'],
				$data['almtpt'],
				$data['fakultas'],
				$data['prodiid'],
				$data['bidang'],
				$data['nohp'],
				$data['jabfungid'],
				$data['pktgolid'],
				$data['s1'],
				$data['s2'],
				$data['s3'],
				$data['jenis'],
				$data['thnakd'],
				$data['semester'],
				$data['asesor1'],
				$data['asesor2']
			));
      // exit;	  
      return $return;
   }  
	
// ADD DOSEN -----------------------------------------------------------------------------------------------------------------------------------
   function AddDosen($data) {
      $return = $this->Execute($this->mSqlQueries['do_add_dosen'], 
			array(
				$data['kesimpulan'],
				$data['tglNilai'],
				$data['bkdid'],
				$data['id']
			));
      // exit;	  
      return $return;
   }  
// ADD DOSEN -----------------------------------------------------------------------------------------------------------------------------------
	
// ADD PENDIDIKAN -----------------------------------------------------------------------------------------------------------------------------------
   function AddPendidikan($array1) {
    $return = $this->Execute($this->mSqlQueries['do_add_pendidikan'],
			array(
				$array1['rekomenPddk'],
				$array1['idPddk']
			));
    return $return;
   }
// ADD PENDIDIKAN -----------------------------------------------------------------------------------------------------------------------------------

// ADD PENELITIAN -----------------------------------------------------------------------------------------------------------------------------------
   function AddPenelitian($array2) {
    $return = $this->Execute($this->mSqlQueries['do_add_penelitian'],
			array(
				$array2['rekomenPenlt'],
				$array2['idPenlt']
			));
      // exit;	  
    return $return;
   }
// ADD PENELITIAN -----------------------------------------------------------------------------------------------------------------------------------
	
// ADD PENGABDIAN -----------------------------------------------------------------------------------------------------------------------------------
   function AddPengabdian($array3) {
    $return = $this->Execute($this->mSqlQueries['do_add_pengabdian'],
			array(
				$array3['rekomenPengb'],
				$array3['idPengb']
			));
    return $return;
   }  
// ADD PENGABDIAN -----------------------------------------------------------------------------------------------------------------------------------
	
// ADD PENUNJANG -----------------------------------------------------------------------------------------------------------------------------------
   function AddPenunjang($array4) {
    $return = $this->Execute($this->mSqlQueries['do_add_penunjang'],
			array(
				$array4['rekomenPenunj'],
				$array4['idPenunj']
			));
    return $return;
   }  
// ADD PENUNJANG -----------------------------------------------------------------------------------------------------------------------------------
	
// ADD PROFESOR -----------------------------------------------------------------------------------------------------------------------------------
   function AddProfesor($array5) {
    $return = $this->Execute($this->mSqlQueries['do_add_profesor'],
			array(
				$array5['rekomenProf'],
				$array5['idProf']
			));
    return $return;
   }  
// ADD PROFESOR -----------------------------------------------------------------------------------------------------------------------------------
	


//==================================================================== UPDATE ======================================================================//   
// UPDATE DOSEN -----------------------------------------------------------------------------------------------------------------------------------
   function UpdateDosen($data) {
      $return = $this->Execute($this->mSqlQueries['do_update_dosen'], 
			array(
				$data['nmPt'],
				$data['almtpt'],
				$data['nohp'],
				$data['jenis'],
				$data['thnakd'],
				$data['semester'],
				$data['asesor1'],
				$data['asesor2'],
				$data['bkdid'],
				$data['id']
			));
      // exit;
      return $return;
   }  
// UPDATE DOSEN -----------------------------------------------------------------------------------------------------------------------------------



	function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		//$this->mdebug();  
		 ////exit;
      return $return;
   }   
	


//==================================================================== DELETE ======================================================================//   
	function Delete($id) {
      // $id = $id['idDelete'];exit; 
	   $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
      // exit;
       return $ret;
	}

	function DeletePendidikan($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete_pendidikan'], array($id));		
       return $ret;
	}

	function DeletePenelitian($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete_penelitian'], array($id));		
       return $ret;
	}

	function DeletePengabdian($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete_pengabdian'], array($id));		
       return $ret;
	}

	function DeletePenunjang($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete_penunjang'], array($id));		
       return $ret;
	}

	function DeleteProfesor($id) {
	   $ret = $this->Execute($this->mSqlQueries['do_delete_profesor'], array($id));		
       return $ret;
	}



// GET NAMA FILE START ================================================================================================================
	function GetNmFilePendidikan($id){
	   $result = $this->Open($this->mSqlQueries['get_nmfile_pendidikan'],array($id));
      return $result;
	}

	function GetNmFilePenelitian($id){
	   $result = $this->Open($this->mSqlQueries['get_nmfile_penelitian'],array($id));
      return $result;
	}
	
	function GetNmFilePengabdian($id){
	   $result = $this->Open($this->mSqlQueries['get_nmfile_pengabdian'],array($id));
      return $result;
	}
	
	function GetNmFilePenunjang($id){
	   $result = $this->Open($this->mSqlQueries['get_nmfile_penunjang'],array($id));
      return $result;
	}
	
	function GetNmFileProfesor($id){
	   $result = $this->Open($this->mSqlQueries['get_nmfile_profesor'],array($id));
      return $result;
	}
// GET NAMA FILE END ================================================================================================================
	


// GET COUNT RECORD START ================================================================================================================
	function GetCountRecPenddk($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rec_penddk'],array($id));
      return $result;
	}
	function GetCountRecPenlt($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rec_penlt'],array($id));
      return $result;
	}
	function GetCountRecPengbd($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rec_pengbd'],array($id));
      return $result;
	}
	function GetCountRecPenunj($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rec_penunj'],array($id));
      return $result;
	}
	function GetCountRecProf($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rec_prof'],array($id));
      return $result;
	}
// GET COUNT RECORD END ================================================================================================================	
	

// GET COUNT REKOMENDASI START ================================================================================================================
	function GetCountRekPenddk($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rek_penddk'],array($id));
      return $result;
	}
	function GetCountRekPenlt($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rek_penlt'],array($id));
      return $result;
	}
	function GetCountRekPengbd($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rek_pengbd'],array($id));
      return $result;
	}
	function GetCountRekPenunj($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rek_penunj'],array($id));
      return $result;
	}
	function GetCountRekProf($id){
	   $result = $this->Open($this->mSqlQueries['get_count_rek_prof'],array($id));
      return $result;
	}
// GET COUNT REKOMENDASI END ================================================================================================================



	
	function UpdateStatus($status,$id,$pegId){
	   $return = $this->Execute($this->mSqlQueries['update_status'], array($status,$id,$pegId));
      //exit;          		  
      return $return;
	}
	
	function GetMaxStatus(){
	   $result = $this->Open($this->mSqlQueries['get_max_status'],array());
      return $result;
	}
	
	function GetMaxId(){
	   $result = $this->Open($this->mSqlQueries['get_max_id'],array());
      return $result;
	}
}
?>
