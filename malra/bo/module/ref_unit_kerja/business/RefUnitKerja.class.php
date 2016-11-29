<?php

class RefUnitKerja extends Database {

	protected $mSqlFile= 'module/ref_unit_kerja/business/refunitkerja.sql.php';

	//==GET==
	public function get($offset, $limit, $param){
		$result = $this->Open($this->mSqlQueries['get'], array('%' . $param['nama'] . '%',$offset,$limit));
		return $result;
	}

	public function count($param){
		$result = $this->Open($this->mSqlQueries['count'], array('%' . $param['nama'] . '%'));
		if(!$result) return $result;
		else return $result[0]['total'];
	}

	public function getById($id){
		$result = $this->Open($this->mSqlQueries['getById'], array($id));
		if($result)return $result[0];
		else return false;
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