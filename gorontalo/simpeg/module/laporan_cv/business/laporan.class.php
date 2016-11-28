<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class Laporan extends Database {

   protected $mSqlFile= 'module/laporan_cv/business/laporan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
   function GetListPegawai($tampilkan, $start, $limit) {   
     //$result = $this->Open($this->mSqlQueries['get_list_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%', $start, $limit));      
	 $Obj = new DataPegawai;
	 $result = $Obj->GetDataPegawaiByUserIdVerified($start, $limit, $tampilkan, 'all');
     return $result;
   }
   
   function GetDataDetail($id) { 
	 $result = $this->Open($this->mSqlQueries['get_data_pegawai'], array($id));
	 return $result[0];
   }
      
  function GetCount($tampilkan) {
     //$result = $this->Open($this->mSqlQueries['get_count_pegawai'], array('%'.$tampilkan.'%', '%'.$tampilkan.'%'));
	 //return $result[0]['total'];    
     $Obj = new DataPegawai;	 
	 $totalData = $Obj->GetCountPegawaiByUserIdVerified($tampilkan, 'all');
     return $totalData;     
   }

}
?>
