<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/mutasi_dp3/business/mutasi_dp3.class.php';
   
class ViewRtfDP3 extends HtmlResponse{
   
	function ProcessRequest(){
		$js = new MutasiDp3();
		$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		$dataMutasi = $js->GetDataMutasiById($id,$dataId);
		$dataMutasi = $dataMutasi[0];
		
		$dataPegawai = $js->GetDataDetail($id);
		$dataMutasi['nama'] = $dataPegawai[0]['name'];
        $dataMutasi['nip'] = $dataPegawai[0]['kode'];
        $dataMutasi['no_seri'] = $dataPegawai[0]['no_seri'];
        $dataMutasi['tanggal_lahir'] = $this->date2string($dataPegawai[0]['tgl_lahir']);
        $dataMutasi['jenis_kelamin'] = $dataPegawai[0]['jenis_kelamin']=='L'?'Laki-laki':'Perempuan';
        $dataMutasi['pendidikan'] = $dataPegawai[0]['pendidikan_tertinggi'];
        $dataMutasi['pangkat_golongan'] = $dataPegawai[0]['pangkat_golongan'].' / '.$this->date2string($dataPegawai[0]['pangkat_golongan_tmt']);
        $dataMutasi['jabatan'] = $dataPegawai[0]['jabatan_fungsional'].' / '.$this->date2string($dataPegawai[0]['jabatan_fungsional_tmt']);
        $dataMutasi['unit_kerja'] = $dataPegawai[0]['unit_kerja_id'];
        $dataMutasi['unitkerja'] = $dataPegawai[0]['unit_kerja'];
		
		$dataMutasi['institusi']=GTFWConfiguration::GetValue( 'application', 'company_name');
		$dataMutasi['kabupaten']='Manokwari';
		
		$dataMutasi['mulai'] = $this->date2string($dataMutasi['mulai']);
		$dataMutasi['selesai'] = $this->date2string($dataMutasi['selesai']);
		$dataMutasi['tgl_buat'] = $this->date2string($dataMutasi['tgl_buat']);
		
		$dataMutasi['nilai_kes'] = $dataMutasi['kesetiaan'];
      	$dataMutasi['nilai_pre'] = $dataMutasi['prestasi_kerja'];
      	$dataMutasi['nilai_tan'] = $dataMutasi['tanggung_jawab'];
      	$dataMutasi['nilai_ket'] = $dataMutasi['ketaatan'];
      	$dataMutasi['nilai_kej'] = $dataMutasi['kejujuran'];
      	$dataMutasi['nilai_ker'] = $dataMutasi['kerjasama'];
      	$dataMutasi['nilai_pra'] = $dataMutasi['prakarsa'];
      	$dataMutasi['nilai_kep'] = $dataMutasi['kepemimpinan'];
		
		$dataMutasi['seb_kes'] = $this->sebutan($dataMutasi['kesetiaan']);
      	$dataMutasi['seb_pre'] = $this->sebutan($dataMutasi['prestasi_kerja']);
      	$dataMutasi['seb_tan'] = $this->sebutan($dataMutasi['tanggung_jawab']);
      	$dataMutasi['seb_ket'] = $this->sebutan($dataMutasi['ketaatan']);
      	$dataMutasi['seb_kej'] = $this->sebutan($dataMutasi['kejujuran']);
      	$dataMutasi['seb_ker'] = $this->sebutan($dataMutasi['kerjasama']);
      	$dataMutasi['seb_pra'] = $this->sebutan($dataMutasi['prakarsa']);
      	$dataMutasi['seb_kep'] = $this->sebutan($dataMutasi['kepemimpinan']);
		
		$dataMutasi['jumlah'] = $dataMutasi['kesetiaan']+$dataMutasi['prestasi_kerja']+$dataMutasi['tanggung_jawab']+$dataMutasi['ketaatan']+$dataMutasi['kejujuran']+$dataMutasi['kerjasama']+$dataMutasi['prakarsa']+$dataMutasi['kepemimpinan'];
		$dataMutasi['rata2'] = $dataMutasi['jumlah']/8;
  		
		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_laporan_dp3.rtf");
  		
  		//print_r($contents);  
  		$keys=array_keys($dataMutasi);
		for ($i=0; $i<sizeof($keys);$i++){
			$contents = str_replace("[".strtoupper($keys[$i])."]",$dataMutasi[$keys[$i]], $contents);  
		}
      
  		$nama=str_replace(" ","_",$dataMutasi['nama']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=dp3_".$nama.".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
	}
   
	function date2string($date) {
		$bln = array(
						1  => 'Januari',
      					2  => 'Februari',
      					3  => 'Maret',
      					4  => 'April',
      					5  => 'Mei',
      					6  => 'Juni',
      					7  => 'Juli',
      					8  => 'Agustus',
      					9  => 'September',
      					10 => 'Oktober',
      					11 => 'November',
      					12 => 'Desember'					
  	               );
		$arrtgl = explode('-',$date);
		if (sizeof($arrtgl)>2)
			return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
		else
			return $arrtgl[0];
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