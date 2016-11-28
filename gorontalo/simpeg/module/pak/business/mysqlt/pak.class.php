<?php
class Pak extends Database {
   protected $mSqlFile='module/pak/business/mysqlt/pak.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
      //
   }
   
   function GetListPak($pPak, $start, $limit) {
   //echo $pPak;
   $result = $this->Open($this->mSqlQueries['get_list_pak'], array('%'.$pPak.'%', $start, $limit));
   //exit;
   return $result;
      
   }
   
   function GetPakById($id) {
   $result = $this->Open($this->mSqlQueries['get_pak_by_id'], array($id));
   return $result;
      
   }
   
   function GetCountPak() {
      $result = $this->Open($this->mSqlQueries['get_count_pak'], array());
      return $result[0]['total']; 
   }
   
   function GetComboJabFungJenisrId() {
		$result = $this->Open($this->mSqlQueries['get_combo_jabfungjenisrid'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function DoAddPak($pangkat,$nama,$tingkat,$jabfung){
      $result = $this->Execute($this->mSqlQueries['do_add_pak'], array($pangkat,$nama,$tingkat,$jabfung));
      //exit;
      return $result;
   }
   
   function DoUpdatePak($pangkat,$nama,$tingkat,$jabfung,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_pak'], array($pangkat,$nama,$tingkat,$jabfung,$id));
      //exit;
      return $result;
   }
   
   function DoDeletePakByArrayId($id){
      $id = implode(",", $id);
		$result=$this->Execute($this->mSqlQueries['do_delete_pak_by_array_id'], array($id));		
		return $result;
   }
   
   function DoDeletePak($id){
		$result=$this->Execute($this->mSqlQueries['do_delete_pak'], array($id));		
		return $result;
   }
   
}
?>
