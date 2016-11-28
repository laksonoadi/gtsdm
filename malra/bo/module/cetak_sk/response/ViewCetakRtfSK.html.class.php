<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/cetak_sk/business/CetakSK.class.php';

class ViewCetakRtfSK extends HtmlResponse{
   
	function ProcessRequest(){
		$Obj = new CetakSK();
		$jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
		$data = $_POST->AsArray();
		$data['gaji_pokok_terbilang']=$Obj->terbilang($data['gaji_pokok']);
		$data['tunjangan_jabatan_terbilang']=$Obj->terbilang($data['tunjangan_jabatan']);
		
		$data['gaji_pokok']=number_format($data['gaji_pokok'],0,',','.');
		$data['gaji_pokok_l']=number_format($data['gaji_pokok_l'],0,',','.');
		$data['tunjangan_jabatan']=number_format($data['tunjangan_jabatan'],0,',','.');
		
		$data['tanggal_penetapan']=$Obj->date2string($data['tanggal_penetapan_year'].'-'.$data['tanggal_penetapan_mon'].'-'.$data['tanggal_penetapan_day']);
  		
		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_sk_".$jenis.".rtf");
  		
  		//print_r($contents);  
  		$keys=array_keys($data);
		for ($i=0; $i<sizeof($keys);$i++){
			$contents = str_replace("[".strtoupper($keys[$i])."]",$data[$keys[$i]], $contents);  
		}
      
  		$nama=str_replace(" ","_",$data['nama_pegawai']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=SK_".strtoupper($jenis)."_".strtoupper($nama).".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
	}
	
	function sebutan($nilai){
		if ($nilai>=91) return 'Amat Baik';
		if ($nilai>=76) return 'Baik';
		if ($nilai>=61) return 'Cukup';
		if ($nilai>=51) return 'Sedang';
		
		return 'Kurang';
	}
}
   

?>