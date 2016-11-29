<?php
class Berita extends Database {
   protected $mSqlFile = 'module/berita/business/berita.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //
   }

   function ListBeritaTerbaru() {
      $result = $this->Open($this->mSqlQueries['list_berita_terbaru'], array());
	     return $result[0];
   }

   function ListBeberapaBerita() {
      $result = $this->Open($this->mSqlQueries['list_beberapa_berita'], array());
	     return $result;
   }

   function ListBeberapaBerita2($num = 5) {
      $result = $this->Open($this->mSqlQueries['list_beberapa_berita2'], array($num));
	     return $result;
   }

   function ListBerita() {
      $result = $this->Open($this->mSqlQueries['list_berita'], array());
	     return $result;
   }

   function GetBeritaById($id) {
      $result = $this->Open($this->mSqlQueries['get_berita_by_id'], array($id));
	     return $result[0];
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