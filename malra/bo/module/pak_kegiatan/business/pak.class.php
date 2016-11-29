<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_pegawai/business/data_pegawai.class.php';
class Pak extends Database {
   protected $mSqlFile='module/pak_kegiatan/business/pak.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
	  $this->Obj = new DataPegawai();
	  $this->userId = $this->Obj->GetUserIdByUserName();
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
   
   function GetComboUnsur() {
		$result = $this->Open($this->mSqlQueries['get_combo_unsur'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
   
   function DoAddPak($nama,$unsurId, $angkaKredit){
      $result = $this->Execute($this->mSqlQueries['do_add_pak'], array($nama,$unsurId,$angkaKredit,$this->userId));
      //exit;
      return $result;
   }
   
   function DoUpdatePak($nama,$unsurId,$angkaKredit,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_pak'], array($nama,$unsurId,$angkaKredit,$this->userId,$id));
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
