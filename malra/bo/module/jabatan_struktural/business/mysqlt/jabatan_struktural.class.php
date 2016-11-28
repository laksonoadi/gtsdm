<?php
class JabatanStruktural extends Database {
   protected $mSqlFile='module/jabatan_struktural/business/mysqlt/jabatan_struktural.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
      //
   }
   
   function GetListJabstruk($pJabstruk, $start, $limit) {
   //echo $pJabstruk;
   $result = $this->Open($this->mSqlQueries['get_list_jabstruk'], array('%'.$pJabstruk.'%', $start, $limit));
   //exit;
   return $result;
      
   }
   
   function GetJabstrukById($id) {
   $result = $this->Open($this->mSqlQueries['get_jabstruk_by_id'], array($id));
   return $result;   
   }
   
   function GetCountJabstruk() {
      $result = $this->Open($this->mSqlQueries['get_count_jabstruk'], array());
      return $result[0]['total']; 
   }
   
   function GetComboTpstrId() {
		$result = $this->Open($this->mSqlQueries['get_combo_tpstrid'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboKomponenKeuangan() {
		$result = $this->Open($this->mSqlQueries['get_combo_komponen_keuangan'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function GetComboKomponenGaji() {
		$result = $this->Open($this->mSqlQueries['get_combo_komponen_gaji'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function DoAddJabstruk($nama,$tingkat,$batas,$tpstr,$komp,$unit,$keterangan){
      $result = $this->Execute($this->mSqlQueries['do_add_jabstruk'], array($nama,$tingkat,$batas,$tpstr,$komp,$unit,$keterangan));
      //exit;
      return $result;
   }
   
   function DoUpdateJabstruk($nama,$tingkat,$batas,$tpstr,$komp,$unit,$keterangan,$id) {
      
      $result = $this->Execute($this->mSqlQueries['do_update_jabstruk'], array($nama,$tingkat,$batas,$tpstr,$komp,$unit,$keterangan,$id));
      //exit;
      return $result;
   }
   
   function DoDeleteJabstrukByArrayId($id){
      $id = implode(",", $id);
		$result=$this->Execute($this->mSqlQueries['do_delete_jabstruk_by_array_id'], array($id));		
		return $result;
   }
   
   function DoDeleteJabstruk($id){
		$result=$this->Execute($this->mSqlQueries['do_delete_jabstruk'], array($id));		
		return $result;
   }

   function GetUnitById($id) {
   $result = $this->Open($this->mSqlQueries['get_unit_name'], array($id));
   return $result;   
   }

   function GetJenisJabatanById($id) {
   $result = $this->Open($this->mSqlQueries['get_tipe_jabatan'], array($id));
   return $result;   
   }
   
}
?>
