<?php
class PangkatGolongan extends Database {
   protected $mSqlFile='module/pangkat_golongan/business/mysqlt/pangkat_golongan.sql.php';
   
   function __construct($connectionNumber=0){
      parent::__construct($connectionNumber);
      //
   }
   
   function GetListPangkatGolongan($pPangkatGolongan, $start, $limit) {
   //echo $pMatakuliah;
   $result = $this->Open($this->mSqlQueries['get_list_pangkat_golongan'], array('%'.$pPangkatGolongan.'%', $start, $limit));
   return $result;
      
   }
   
   function GetPangkatGolonganById($id) {
   $result = $this->Open($this->mSqlQueries['get_pangkat_golongan_by_id'], array($id));
   return $result;
      
   }
   
   function GetCountPangkatGolongan() {
      $result = $this->Open($this->mSqlQueries['get_count_pangkat_golongan'], array());
      return $result[0]['total']; 
   }
   
   function DoAddPangkatGolongan($pangkat,$nama,$tingkat,$masa,$urut){
      $result = $this->Execute($this->mSqlQueries['do_add_pangkat_golongan'], array($pangkat,$nama,$tingkat,$masa,$urut));
      //exit;
      return $result;
   }
   
   function DoUpdatePangkatGolongan($pangkat,$nama,$tingkat,$masa,$urut,$id) {
      $result = $this->Execute($this->mSqlQueries['do_update_pangkat_golongan'], array($pangkat,$nama,$tingkat,$masa,$urut,$id));
      //exit;
      return $result;
   }
   
   function DoDeletePangkatGolonganByArrayId($id){
      $id = implode(",", $id);
		$result=$this->Execute($this->mSqlQueries['do_delete_pangkat_golongan_by_array_id'], array($id));		
		return $result;
   }
   
   function DoDeletePangkatGolongan($id){
		$result=$this->Execute($this->mSqlQueries['do_delete_pangkat_golongan'], array($id));		
		return $result;
   }
   
}
?>
