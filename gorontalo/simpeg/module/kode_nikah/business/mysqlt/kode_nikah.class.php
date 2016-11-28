<?php
class KodeNikah extends Database {
   protected $mSqlFile='module/kode_nikah/business/mysqlt/kode_nikah.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
      //
   }
   
   function GetListKodeNikah($pKodeNikah, $start, $limit) {
   //echo $pKodeNikah;
   $result = $this->Open($this->mSqlQueries['get_list_kode_nikah'], array('%'.$pKodeNikah.'%', $start, $limit));
   //exit;
   return $result;
      
   }
   
   function GetKodeNikahById($id) {
   $result = $this->Open($this->mSqlQueries['get_kode_nikah_by_id'], array($id));
   return $result;
      
   }
   
   function GetCountKodeNikah() {
      $result = $this->Open($this->mSqlQueries['get_count_kode_nikah'], array());
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
   
   function DoAddKodeNikah($kode,$nama,$komp){
      $result = $this->Execute($this->mSqlQueries['do_add_kode_nikah'], array($kode,$nama,$komp));
      //exit;
      return $result;
   }
   
   function DoUpdateKodeNikah($kode,$nama,$komp,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_kode_nikah'], array($kode,$nama,$komp,$id));
      //exit;
      return $result;
   }
   
   function DoDeleteKodeNikahByArrayId($id){
      $id = implode(",", $id);
		$result=$this->Execute($this->mSqlQueries['do_delete_kode_nikah_by_array_id'], array($id));		
		return $result;
   }
   
   function DoDeleteKodeNikah($id){
		$result=$this->Execute($this->mSqlQueries['do_delete_kode_nikah'], array($id));		
		return $result;
   }
   
}
?>
