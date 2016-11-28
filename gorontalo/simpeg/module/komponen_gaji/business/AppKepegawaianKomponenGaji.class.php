<?php

class AppKepegawaianKomponenGaji extends Database
{
	protected $mSqlFile;
	
	function __construct($connectionNumber=0)
   {
      $this->mSqlFile = 'module/'.Dispatcher::Instance()->mModule.'/business/AppKepegawaianKomponenGaji.sql.php';
    parent::__construct($connectionNumber);
	}
   
   function AddData ($data)
   {
      extract($data);
      return $this->Execute($this->mSqlQueries['do_add_data'], array($id, $kode, $nama));
   }
   
   function UpdateData ($data)
   {
      extract($data);
      $this->Execute($this->mSqlQueries['do_update_data'], array($kode, $nama, $id));
      if ($this->Affected_Rows() < 1) return false;
      else return true;
   }
   
   function DeleteData ($id)
   {
      $sql = str_replace('%s', implode(', ', array_fill(0,count($id),'%s')), $this->mSqlQueries['do_delete_data']);
      $this->Execute($sql, $id);
      if ($this->Affected_Rows() < 1) return false;
      else return true;
   }
}
?>
