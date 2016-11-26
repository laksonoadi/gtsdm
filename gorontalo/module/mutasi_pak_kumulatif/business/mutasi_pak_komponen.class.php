<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';
class MutasiPakKomponen extends Database {

	protected $mSqlFile= 'module/mutasi_pak_kumulatif/business/mutasi_pak_komponen.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);  
		//   
	}
     
	function GetKegiatanOtomatis($pegId) { 
		$js = new MutasiPak(); ///*$js->SinkronisasiKegiatan();*/
		
		$this->DoUpdateStatusPenggunaan($pegId);
		$result = $this->Open($this->mSqlQueries['get_komponen_pak'], array());      
		
		$kegiatan=array();
		for ($i=0; $i<sizeof($result); $i++){
			$komponenid=$result[$i]['komponenid'];
			$table=$result[$i]['tabel'];
			$field="concat(".implode(",';',",explode(';',$result[$i]['field'])).")";
			$kode=$result[$i]['kode'];
			
			$query=$this->mSqlQueries['get_kegiatan_pak_otomatis'];
			$query=str_replace("%komponenid%",$komponenid,$query);
			$query=str_replace("%table%",$table,$query);
			$query=str_replace("%field%",$field,$query);
			$query=str_replace("%kode%",$kode,$query);
			$query=str_replace("%pegid%",$pegId,$query);
			$arrkegiatan = $this->Open($query, array());
			for ($j=0; $j<sizeof($arrkegiatan); $j++){
				$kegiatan[]=$arrkegiatan[$j];
			}
			
		}
		return $kegiatan;
	}
	
	function DoUpdateStatusPenggunaan($pegId) {   
		$this->StartTrans();
		$kegiatan = $this->Open($this->mSqlQueries['get_all_kegiatan_pak'], array($pegId));
		for ($i=0; $i<sizeof($kegiatan); $i++){
			$temp=explode(':',$kegiatan[$i]['referensi']);
			$referensi[$temp[0].':'.$temp[1]][]=$temp[2];
		}
		if (is_array($referensi)){
			$tablekode=array_keys($referensi);
			for ($i=0; $i<sizeof($tablekode); $i++){
				$referensi[$tablekode[$i]][]=0;
				$arrId=implode(",",$referensi[$tablekode[$i]]);
				$temp=explode(':',$tablekode[$i]);
				$table=$temp[0];
				$kode=$temp[1];
				
				$query_use="UPDATE ".$table." SET ".$kode."Digunakan=1 WHERE ".$kode."Id IN (".$arrId.") AND ".$kode."PegId=".$pegId;
				$query_notuse="UPDATE ".$table." SET ".$kode."Digunakan=0 WHERE NOT(".$kode."Id IN (".$arrId.")) AND ".$kode."PegId=".$pegId;
				$result = $this->Execute($query_use, array());
				if ($result) $result = $this->Execute($query_notuse, array());
				if (!$result) break;
			}
		}
		$this->EndTrans($result);
	}
   
	
}
?>
