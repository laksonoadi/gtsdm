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

	function GetData($offset, $limit, $kid, $kode='0', $nama=' ') {
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
		return $result[0];
	}
   
//===DO==
	
	function DoAddData($arr) {
      //$this->StartTrans();
		$result = $this->Execute($this->mSqlQueries['do_add_data'], $arr);
      /*$data = $this->GetDataById($this->Insert_ID());
      $arg = array
      (
         'id' => $data['id_detil'],
         'kode' => $data['kode_detil'],
         'nama' => $data['kompgajiNama']
      );
      if ($result) $result = $this->Obj->AddData($arg);
      if (!$result) $this->EndTrans(false);
      else $this->EndTrans(true);*/
    //print_r($this->getLastError());exit; 
		return $result;
	}
	
	function DoUpdateData($arr) {
      /*$data = $this->GetDataById($arr[6]);
      $arg = array
      (
         'id' => $data['id_detil'],
         'kode' => $data['kode_detil'],
         'nama' => $data['kompgajiNama']
      );
      $this->Obj->StartTrans();
      $result = $this->Obj->UpdateData($arg);
		if ($result)*/ $result = $this->Execute($this->mSqlQueries['do_update_data'], $arr);
      /*if (!$result) $this->Obj->EndTrans(false);
      else $this->Obj->EndTrans(true);*/
	  //$debug = sprintf($this->mSqlQueries['do_update_gaji'], $gajiKode, $gajiNama, $tipeunit, $satker, $gajiId);
	  //echo $debug;
		return $result;
	}
	
	function DoDeleteData($id) {
      /*$this->Obj->StartTrans();
		$result = $this->Obj->DeleteData(array($id));
		if ($result)*/ $result=$this->Execute($this->mSqlQueries['do_delete_data'], array($id));
     /* if (!$result) $this->Obj->EndTrans(false);
      else $this->Obj->EndTrans(true);*/
		return $result;
	}

	function DoDeleteDataByArrayId($arrId) {
		$id = implode("', '", $arrId);
      /*$this->Obj->StartTrans();
      $result = $this->Obj->DeleteData($arrId);
		if ($result)*/ $result=$this->Execute($this->mSqlQueries['do_delete_data_by_array_id'], array($id));
     /* if (!$result) $this->Obj->EndTrans(false);
      else $this->Obj->EndTrans(true);*/
		return $result;
	}
}
?>
