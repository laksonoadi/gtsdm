<?php
class MutasiStatus extends Database {

	protected $mSqlFile= 'module/mutasi_status/business/mutasi_status.sql.php';
   
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
   
	function GetListMutasiStatus($id) {
		$result = $this->Open($this->mSqlQueries['get_list_mutasi_status_pegawai'], array($id));
		return $result;
	}
    
    function GetLastMutasiStatus($id) {
        $result = $this->Open($this->mSqlQueries['get_last_mutasi_status_pegawai'], array($id));
        if(is_array($result)) {
            if(count($result) > 0) {
                return $result[0]; // Newest item
            } else {
                return TRUE; // Not yet added
            }
        } else {
            return $result; // Fails
        }
	}
   
	function GetDataMutasiById($id,$dataId) {
		$result = $this->Open($this->mSqlQueries['get_data_mutasi_status_pegawai_by_id'], array($id,$dataId));
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
   
	function GetComboStatus() {
		$result = $this->Open($this->mSqlQueries['get_combo_status'], array());
		return $result;
	}
   
//===============do======================//   
	function Add($data) {	   
		$return = $this->Execute($this->mSqlQueries['do_add'], $data);
		
		if ($data['status']=='Aktif'){
			$result = $this->UpdateStatusPegawai(array($data['statr'],$data['pegKode']));
		}
		
		return $return;
	}  
	
	function Update($data) {
		$return = $this->Execute($this->mSqlQueries['do_update'], $data);         		  
		
		if ($data['status']=='Aktif'){
			$result = $this->UpdateStatusPegawai(array($data['statr'],$data['pegKode']));
		}
		
		return $return;
	}   
	
	function UpdateStatusPegawai($data){
		$return = $this->Execute($this->mSqlQueries['do_update_status_pegawai'], $data);
		return $return;
	}
	
	function Delete($id) {
		//$id = $id['idDelete'];
		$ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
		//exit; 
		return $ret;
	}
	
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
