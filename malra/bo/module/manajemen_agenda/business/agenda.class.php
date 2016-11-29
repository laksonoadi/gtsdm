<?php

class Agenda extends Database {

	protected $mSqlFile= 'module/manajemen_agenda/business/agenda.sql.php';

	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);   
		//  
	}

	//==GET==
	function GetDataById($id) {
		$dateFormat = '%d-%m-%Y';
		$result = $this->Open($this->mSqlQueries['get_data_by_id'], array($dateFormat,$id)); 
		if($result)
			return $result[0];
		else
			return $result;	  
	}

	function GetCount() {
		$result = $this->Open($this->mSqlQueries['get_count'], array($_SESSION['username']));
		if (!$result)
			return 0;
		else
			return $result[0]['total'];    
	}

	function getMaxId() { 
		$result = $this->Open($this->mSqlQueries['get_max_id'], array());
		return $result[0]['id'];
	}

	function GetData($offset, $limit) { 
		$dateFormat = '%d-%m-%Y';
		$result = $this->Open($this->mSqlQueries['get_data'], array($dateFormat,$_SESSION['username'],$offset,$limit));
		// $this->gSamViewArr($this->getLastError());
		return $result;
	}
	
	function GetKode($postKode) {      
		$result	= $this->Open($this->mSqlQueries['get_kode'], array($postKode)); 
		if (!$result)
			return 0;
		else
			return $result[0]['cekNama'];    
	}


	function Add($data) {
		$return = $this->Execute($this->mSqlQueries['do_add'],array(
					$data['nama'],
					$data['article'],
					$data['mulai'],
					$data['selesai'],
					$data['tempat'],
					$data['url'],
					$data['foto'],
					$data['caption'],
					$data['status'],
					$data['sender']
				));
		return $return;
	}  

	function Update($data) {
		$return = $this->Execute($this->mSqlQueries['do_update'],array(
					$data['nama'],
					$data['article'],
					$data['mulai'],
					$data['selesai'],
					$data['tempat'],
					$data['url'],
					$data['foto'],
					$data['caption'],
					$data['status'],
					$data['sender'],
					$data['id']
				));
		// $this->gSamViewArr($this->getLastError());
		return $return;
	}   

	function Delete($id) {
		$id		= $id['idDelete'];
		$ret	= $this->Execute($this->mSqlQueries['do_delete'], array($id));		
		return $ret;
	}
	
//	>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
	function gSamViewArr($param) {
		echo"<pre>";print_r($param);echo"</pre>";exit;
	}
	  
	function gSamViewEcho($param) {
		echo"<pre>";echo$param.'<br/>';exit;
	}

}
?>
