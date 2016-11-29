<?php
class ManajemenAgenda extends Database {
   //protected $mSqlFile = 'module/agenda/business/agenda.sql.php';
   //protected $mDbConfig = array('db_namespace' => 'Agenda');
   
   protected $mSqlFile= 'module/manajemen_agenda/business/businessagenda.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }

   function ListAgenda($offset,$limit) {
      $dateFormat = '%d-%m-%Y';
      return $this->Open($this->mSqlQueries['list_agenda'], array($dateFormat,$_SESSION['username'],$offset,$limit));
   }
   
   #deprecated
   function ListAgendaAktif($offset,$limit){
      return $this->Open($this->mSqlQueries['list_agenda_aktif'], array($_SESSION['username'],$offset,$limit));
   }

   function CountAgenda(){
      $buff = $this->Open($this->mSqlQueries['count_agenda'], array($_SESSION['username']));
      return $buff[0]['NUMBER'];
   }

   function CountAgendaAktif(){
      $buff = $this->Open($this->mSqlQueries['count_agenda_aktif'], array($_SESSION['username'],));
      return $buff[0]['NUMBER'];
   }
   
   function GetAgendaById($id){
      return $this->Open($this->mSqlQueries['get_agenda_by_id'], array($id));
   }

   function AddAgenda($nama,$artikel,$tanggal_mulai,$tanggal_selesai,$tempat,$foto,$caption_foto,$status,$pengirim){
      return $this->Execute($this->mSqlQueries['add_agenda'],array($nama,$artikel,$tanggal_mulai,$tanggal_selesai,$tempat,$foto,$caption_foto,$status,$pengirim));
   }
   
   function UpdateAgenda($nama,$artikel,$tanggal_mulai,$tanggal_selesai,$tempat,$foto,$caption_foto,$status,$pengirim,$id){
      return $this->Execute($this->mSqlQueries['update_agenda'],array($nama,$artikel,$tanggal_mulai,$tanggal_selesai,$tempat,$foto,$caption_foto,$status,$pengirim,$id));
   }
   
   function DeleteAgenda($id){
      return $this->Execute($this->mSqlQueries['delete_agenda'],array($id));
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