<?php

class RefDaftarGaji extends Database {

	protected $mSqlFile= 'module/ref_daftar_gaji/business/refdaftargaji.sql.php';

	//==GET==
	public function get($offset = NULL, $limit = NULL, $param = NULL){
		$query = $this->mSqlQueries['get'];
		
		$where = '';
		if(isset($param['masa_kerja']) && $param['masa_kerja'] != 'all' && $param['masa_kerja'] !== '') {
			$where .= sprintf(" AND mkDafGaji = '%s' ", $param['masa_kerja']);
		}
		/* if(isset($param['gol_ruang']) && count($gol_ruang = explode('/', $param['gol_ruang'])) == 2) {
			$where .= vsprintf(" AND (golDafGaji = '%s' AND ruangDafGaji = '%s') ", $gol_ruang);
		} */
		if(isset($param['golongan']) && $param['golongan'] != 'all' && $param['golongan'] !== '') {
			$where .= sprintf(" AND SUBSTRING_INDEX(pktGolGaji, '/', 1) = '%s' ", $param['golongan']);
		}
		if(isset($param['ruang']) && $param['ruang'] != 'all' && $param['ruang'] !== '') {
			$where .= sprintf(" AND SUBSTRING_INDEX(pktGolGaji, '/', -1) = '%s' ", $param['ruang']);
		}
		
		$limiter = '';
		if(isset($offset) && isset($limit)) {
			$limiter = vsprintf('LIMIT %s, %s', array($offset, $limit));
		}
		
		$query = str_replace('--where--', $where, $query);
		$query = str_replace('--limit--', $limiter, $query);
		$result = $this->Open($query, array());
		return $result;
	}

	public function count($param){
		$query = $this->mSqlQueries['count'];
		
		$where = '';
		if(isset($param['masa_kerja']) && $param['masa_kerja'] != 'all' && $param['masa_kerja'] !== '') {
			$where .= sprintf(" AND mkDafGaji = '%s' ", $param['masa_kerja']);
		}
		/* if(isset($param['gol_ruang']) && count($gol_ruang = explode('/', $param['gol_ruang'])) == 2) {
			$where .= vsprintf(" AND (golDafGaji = '%s' AND ruangDafGaji = '%s') ", $gol_ruang);
		} */
		if(isset($param['golongan']) && $param['golongan'] != 'all' && $param['golongan'] !== '') {
			$where .= sprintf(" AND SUBSTRING_INDEX(pktGolGaji, '/', 1) = '%s' ", $param['golongan']);
		}
		if(isset($param['ruang']) && $param['ruang'] != 'all' && $param['ruang'] !== '') {
			$where .= sprintf(" AND SUBSTRING_INDEX(pktGolGaji, '/', -1) = '%s' ", $param['ruang']);
		}
		
		$query = str_replace('--where--', $where, $query);
		$result = $this->Open($query, array());
		if(!$result)return 0;
		else return $result[0]['total'];    
	}

	public function getById($id){      
		$result = $this->Open($this->mSqlQueries['getById'], array($id)); 
		if($result)return $result[0];
		else return false;	  
	}  
	
	public function getComboMasaKerja($max = 35) {
		$result = array();
		for($i = 0; $i <= $max; $i++) {
			$result[] = array('id' => (string)$i, 'name' => $i .' tahun');
		}
		return $result;
	}
	
	public function getComboGolonganRuang() {
		return $this->Open($this->mSqlQueries['get_combo_golongan_ruang'], array());
	}
	
	public function getComboGolongan() {
		return $this->Open($this->mSqlQueries['get_combo_golongan'], array());
	}
	
	public function getComboRuang() {
		return $this->Open($this->mSqlQueries['get_combo_ruang'], array());
	}
	
	public function checkExists($gol_ruang, $masa_kerja, $id = NULL) {
		if($id === NULL OR $id === '') $id = '-1';
		$result = $this->Open($this->mSqlQueries['check_exists'], array($gol_ruang, $masa_kerja, $id));
		if(!$result) return 0;
		else {
			return ($result[0]['total'] >= 1);
		}
	}

	//==SET==
	public function add($param){	   
		$return = $this->Execute($this->mSqlQueries['add'], $param);	  
		return $return;
	}  

	public function update($param){
		$return = $this->Execute($this->mSqlQueries['update'], $param);
		return $return;
	}   

	public function delete($param){
		$ret = $this->Execute($this->mSqlQueries['delete'], $param);
		return $ret;
	}
}
?>