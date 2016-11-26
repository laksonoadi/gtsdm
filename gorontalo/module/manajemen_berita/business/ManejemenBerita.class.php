<?php
class ManejemenBerita extends Database {
   //protected $mSqlFile = 'module/berita/business/berita.sql.php';
   //protected $mDbConfig = array('db_namespace' => 'Berita');
   
   protected $mSqlFile= 'module/manajemen_berita/business/businessberita.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }

   function ListBerita($offset,$limit) {
      $dateFormat = '%d-%m-%Y';
      //echo sprintf($this->mSqlQueries['list_berita'],$dateFormat,$offset,$limit);
      return $this->Open($this->mSqlQueries['list_berita'], array($dateFormat,$_SESSION['username'],$offset,$limit));
   }

   function ListBeritaAktif($offset,$limit){
      $dateFormat = '%d-%m-%Y';
      return $this->Open($this->mSqlQueries['list_berita_aktif'], array($dateFormat,$_SESSION['username'],$offset,$limit));
   }

   function CountBerita(){
      $buff = $this->Open($this->mSqlQueries['count_berita'], array($_SESSION['username']));
      return $buff[0]['NUMBER'];
   }

   function CountBeritaAktif(){
      $buff = $this->Open($this->mSqlQueries['count_berita_aktif'], array($_SESSION['username']));
      return $buff[0]['NUMBER'];
   }
   
   function GetBeritaById($id){
      return $this->Open($this->mSqlQueries['get_berita_by_id'], array($id));
   }

   function AddBerita($nama,$artikel,$url,$foto,$caption_foto,$status,$pengirim,$tanggal_berita){
      return $this->Execute($this->mSqlQueries['add_berita'],array($nama,$artikel,$url,$foto,$caption_foto,$status,$pengirim,$tanggal_berita));
   }
   
   function UpdateBerita($nama,$artikel,$url,$foto,$caption_foto,$status,$pengirim,$tanggal_berita,$id){
      return $this->Execute($this->mSqlQueries['update_berita'],array($nama,$artikel,$url,$foto,$caption_foto,$status,$pengirim,$tanggal_berita,$id));
   }
   
   function DeleteBerita($id){
      return $this->Execute($this->mSqlQueries['delete_berita'],array($id));
   }
   
   function IndonesianDate($StrDate, $StrFormat)
	{
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day." Januari ".$Year;
						break;
			case "02" :	$StrResult = $Day." Pebuari ".$Year;
						break;
			case "03" :	$StrResult = $Day." Maret ".$Year;
						break;
			case "04" :	$StrResult = $Day." April ".$Year;
						break;
			case "05" :	$StrResult = $Day." Mei ".$Year;
						break;
			case "06" :	$StrResult = $Day." Juni ".$Year;
						break;
			case "07" :	$StrResult = $Day." Juli ".$Year;
						break;
			case "08" :	$StrResult = $Day." Agustus ".$Year;
						break;
			case "09" :	$StrResult = $Day." September ".$Year;
						break;
			case "10" :	$StrResult = $Day." Oktober ".$Year;
						break;
			case "11" :	$StrResult = $Day." Nopember ".$Year;
						break;
			case "12" :	$StrResult = $Day." Desember ".$Year;
						break;
		} //end switch
		return $StrResult;
	}
}
?>