<?php
class SksDosen extends Database {
   protected $mSqlFile='module/sks_dosen/business/mysqlt/sks_dosen.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
      //
   }
   
   function GetListSksDosen($pSksDosen, $start, $limit) {
   //echo $pJabstruk;
   $result = $this->Open($this->mSqlQueries['get_list_sks_dosen'], array('%'.$pSksDosen.'%', $start, $limit));
   //exit;
   return $result;
      
   }
   
   function GetSksDosenById($id) {
   $result = $this->Open($this->mSqlQueries['get_sks_dosen_by_id'], array($id));
   return $result;
      
   }
   
   function GetCountSksDosen() {
      $result = $this->Open($this->mSqlQueries['get_count_sks_dosen'], array());
      return $result[0]['total']; 
   }
   
   function GetComboJabfung() {
		$result = $this->Open($this->mSqlQueries['get_combo_jabfung'], array());
		//echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
		return $result;
   }
      
   function DoAddSksDosen($tahun,$semester,$nominal,$status,$jfid){
      $result = $this->Execute($this->mSqlQueries['do_add_sks_dosen'], array($tahun,$semester,$nominal,$status,$jfid));
      //exit;
      return $result;
   }
   
   function DoUpdateSksDosen($tahun,$semester,$nominal,$status,$jfid,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_sks_dosen'], array($tahun,$semester,$nominal,$status,$jfid,$id));
      //exit;
      return $result;
   }
   
   function DoDeleteSksDosenByArrayId($id){
      $id = implode(",", $id);
		$result=$this->Execute($this->mSqlQueries['do_delete_sks_dosen_by_array_id'], array($id));		
		return $result;
   }
   
   function DoDeleteSksDosen($id){
		$result=$this->Execute($this->mSqlQueries['do_delete_sks_dosen'], array($id));		
		return $result;
   }
   
}
?>
