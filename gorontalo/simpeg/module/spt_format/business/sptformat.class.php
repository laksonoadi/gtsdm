<?php

class SptFormat extends Database {

	protected $mSqlFile= 'module/spt_format/business/sptformat.sql.php';

	//==ADDITIONAL==
	public function comboKategori(){ 
		$result = $this->Open($this->mSqlQueries['comboKategori'], array());
		return $result;
	}

	//==GET==
	public function get($offset, $limit, $param){
		if(!isset($param['kategori']))$param['kategori'] = '%';
		if($param['kategori'] == 'all')$param['kategori'] = '%';
		$result = $this->Open($this->mSqlQueries['get'], array($param['kategori'],$offset,$limit));
		return $result;
	}

	public function count($param){
		if(!isset($param['kategori']))$param['kategori'] = '%';
		if($param['kategori'] == 'all')$param['kategori'] = '%';
		$result = $this->Open($this->mSqlQueries['count'], array($param['kategori']));  
		if(!$result)return 0;
		else return $result[0]['total'];    
	}

	public function getById($id){      
		$result = $this->Open($this->mSqlQueries['getById'], array($id)); 
		if($result)return $result[0];
		else return false;	  
	}  

	public function getKategoriIdAktif($id){      
		$result = $this->Open($this->mSqlQueries['getKategoriIdAktif'], array($id)); 
		if($result)return $result[0]['id'];
		else return null;	  
	}  

	//==SET==
	public function add($param){	   
		$return = $this->Execute($this->mSqlQueries['add'], $param);	  
		return $return;
	}  

	public function fullUpdate($param){
		$return = $this->Execute($this->mSqlQueries['fullUpdate'], $param);
		// echo 'console.log("' . vsprintf($this->mSqlQueries['fullUpdate'], $param) . '");';
		return $return;
	}   

	public function update($param){
		$return = $this->Execute($this->mSqlQueries['update'], $param);
		return $return;
	}   

	public function nonaktif($param){
		$return = $this->Execute($this->mSqlQueries['nonaktif'], $param);
		return $return;
	}   

	public function aktivasi($param){
		$return = $this->Execute($this->mSqlQueries['aktivasi'], $param);
		return $return;
	}   

	public function delete($param){
		$ret = $this->Execute($this->mSqlQueries['delete'], $param);
		return $ret;
	}
}
?>