<?php
class Agenda extends Database {
   protected $mSqlFile = 'module/agenda/business/agenda.sql.php';

   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);
      //
   }

   function ListAgendaTerbaru() {
      $result = $this->Open($this->mSqlQueries['list_agenda_terbaru'], array());
	     return $result[0];
   }

   function ListBeberapaAgenda() {
      $result = $this->Open($this->mSqlQueries['list_beberapa_agenda'], array());
	     return $result;
   }

   function ListBeberapaAgenda2($num = 3) {
      $result = $this->Open($this->mSqlQueries['list_beberapa_agenda2'], array($num));
	     return $result;
   }

   function ListAgenda() {
      $result = $this->Open($this->mSqlQueries['list_agenda'], array());
	     return $result;
   }

   function GetAgendaById($id) {
      $result = $this->Open($this->mSqlQueries['get_agenda_by_id'], array($id));
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