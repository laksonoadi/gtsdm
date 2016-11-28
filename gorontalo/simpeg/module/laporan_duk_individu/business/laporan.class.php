<?php
//require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';
//require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Laporan extends Database {

   protected $mSqlFile= 'module/laporan_duk_individu/business/laporan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
     
  function GetCountDataDuk($tampilkan) {
      $sql=$this->mSqlQueries['get_data_duk_by_id'];
      if($tampilkan != "all"){
        $sql=str_replace('%tampilkan%'," AND pegNama LIKE '%".$tampilkan."%'" ,$sql);
      }else{
        $sql=str_replace('%tampilkan%','',$sql);
      }
      $sql=str_replace('%limit%','',$sql);
      $result=$this->Open($sql, array());
      
      return sizeof($result);
   }

  function GetDataDuk($tampilkan, $offset, $limit) {
      $sql=$this->mSqlQueries['get_data_duk_by_id']; 
      
      if($tampilkan != "all"){
        $sql=str_replace('%tampilkan%'," AND pegNama LIKE '%".$tampilkan."%'" ,$sql);
      }else{
        $sql=str_replace('%tampilkan%','',$sql);
      }

      $sql=str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result=$this->Open($sql, array());
      //print_r($sql);
      return $result;     
   }

    function GetDataDukById($pegId) {
      $sql=$this->mSqlQueries['get_data_duk_by_id']; 
      
      if($pegId != "all"){
        $sql=str_replace('%tampilkan%'," AND pegId = '".$pegId."'" ,$sql);
      }else{
        $sql=str_replace('%tampilkan%','',$sql);
      } 
      $sql=str_replace('%limit%','',$sql);
      $result=$this->Open($sql, array());
      //print_r($sql);
      return $result;     
   }

   function GetDataJabByDukById($pegId){
      $sql=$this->mSqlQueries['get_data_jab_by_duk_by_id'];       
      if($pegId != "all"){
        $sql=str_replace('%tampilkan%'," AND pegId = '".$pegId."'" ,$sql);
      }else{
        $sql=str_replace('%tampilkan%','',$sql);
      } 

      $result=$this->Open($sql, array());
      //print_r($sql);
      return $result;     
   }
     
  function GetCountDataAbsen() {
	  $result = $this->Open($this->mSqlQueries['get_count_data_absen'], array());
      return $result[0]['total'];
   }

  function GetDataAbsen($tampilkan, $offset, $limit) {
      $sql = $this->mSqlQueries['get_data_absen']; 
      
      if($tampilkan != "all"){
        $sql = str_replace('%tampilkan%'," AND pegNama LIKE '%".$tampilkan."%'" ,$sql);
      }
      else{
        $sql = str_replace('%tampilkan%','',$sql);
      }

      $sql = str_replace('%limit%','LIMIT '.$offset.','.$limit,$sql);
      $result = $this->Open($sql, array());

      return $result;     
   }


}
?>
