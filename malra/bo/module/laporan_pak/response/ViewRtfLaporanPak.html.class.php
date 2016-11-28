<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pak/business/laporan.class.php';
   
class ViewRtfLaporanPak extends HtmlResponse
{
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      $this->Obj=new Laporan;
      $this->tahun_awal=date('Y')-10;
      $this->tahun_akhir=date('Y')+10;
      
      if(isset($_POST['awal_year'])) {
  				$this->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
  		} elseif(isset($_GET['awal'])) {
  				$this->awal = Dispatcher::Instance()->Decrypt($_GET['awal']);
  		} else {
  				$this->awal = date('Y-m').'-01';
  		}
  		$this->label_awal=$this->Obj->IndonesianDate($this->awal,'YYYY-MM-DD');
  		
  		if(isset($_POST['akhir_year'])) {
  				$this->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];;
  		} elseif(isset($_GET['akhir'])) {
  				$this->akhir = Dispatcher::Instance()->Decrypt($_GET['akhir']);
  		} else {
  				$this->akhir = date('Y-m').'-'.$this->Obj->getLastDate(date('Y'),date('m'));
  		}
  		$this->label_akhir=$this->Obj->IndonesianDate($this->akhir,'YYYY-MM-DD');
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = 'all';
  		}
			
  		
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		  				  
		  $totalData = $this->Obj->GetCountDataPak($this->awal,$this->akhir,$this->unit_kerja);
		  $dataPegawai = $this->Obj->GetDataPak(0, $totalData, $this->awal,$this->akhir, $this->unit_kerja);
		  
		  $no='';
		  $nip='';
		  $nama='';
		  $jk='';
		  $pangkat='';
		  $gol='';
		  $unit='';
		  $lama='';
		  $baru='';
		  $digunakan='';
		  $lebihan='';
		  $diangkat='';
		  $tanggal='';
		  for ($i=0; $i<sizeof($dataPegawai); $i++) {
		      $no.=($i+1)."\par";
    		  $nip.=$dataPegawai[$i]['nip']."\par";
    		  $nama.=$dataPegawai[$i]['nama'].'\par';
    		  $jk.=$dataPegawai[$i]['jenis_kelamin'].'\par';
    		  $pangkat.=$dataPegawai[$i]['jabatan'].'\par';
    		  $gol.=$dataPegawai[$i]['golongan'].'\par';
    		  $unit.=$dataPegawai[$i]['unit_kerja'].'\par';
    		  $lama.=$dataPegawai[$i]['pak_jumlah_lama'].'\par';
    		  $baru.=$dataPegawai[$i]['pak_jumlah_baru'].'\par';
    		  $digunakan.=$dataPegawai[$i]['pak_jumlah_digunakan'].'\par';
    		  $lebih.=$dataPegawai[$i]['pak_jumlah_lebihan'].'\par';
    		  $diangkat.=$dataPegawai[$i]['dapat_diangkat'].'\par';
    		  $tanggal.=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal_pak'],'YYYY-MM-DD').'\par';
		  }
  		
  		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_Laporan_pak.rtf");
  		$contents = str_replace("[PERIODE_AWAL]",$this->label_awal, $contents);
  		$contents = str_replace("[PERIODE_AKHIR]",$this->label_akhir, $contents);
  		$contents = str_replace("[UNIT_KERJA]",$this->label_unit_kerja, $contents);
  		$contents = str_replace("[TANGGAL_EXPORT]",date('Y-m-d'), $contents);
  		
  		$contents = str_replace("[NO]",$no, $contents);
  		$contents = str_replace("[NIP]",$no, $contents);
  		$contents = str_replace("[NAMA]",$nama, $contents);
  		$contents = str_replace("[JK]",$jk, $contents);
  		$contents = str_replace("[PANGKAT]",$pangkat, $contents);
  		$contents = str_replace("[GOL]",$gol, $contents);
  		$contents = str_replace("[UNIT]",$unit, $contents);
  		$contents = str_replace("[LAMA]",$lama, $contents);
  		$contents = str_replace("[BARU]",$baru, $contents);
  		$contents = str_replace("[DIGUNAKAN]",$digunakan, $contents);
  		$contents = str_replace("[LEBIH]",$lebih, $contents);
  		$contents = str_replace("[DIANGKAT]",$diangkat, $contents);
  		$contents = str_replace("[TANGGAL]",$tanggal, $contents);
      //print_r($no); exit();
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=laporan_pak_".date('dmY').".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
   }
}
   

?>