<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppHistoryGajiPegawai.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'main/function/date.php';

class ViewCetakSlipGajiPegawai extends HtmlResponse {
   #var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gaji_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_cetak_slip_gaji_pegawai.html');
	}
   
    function TemplateBase() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
		$this->SetTemplateFile('document-blank.html');
		$this->SetTemplateFile('layout-common-blank.html');
	}
   
	function ProcessRequest() {
		$Obj = new AppHistoryGajiPegawai();
		$_GET = $_GET->AsArray();
		$idDec = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$periode = Dispatcher::Instance()->Decrypt($_GET['periode']);
      
		$data_cetak = $Obj->GetDataCetak($idDec,$periode);

		#print_r($data_cetak); exit;
		$return = $data_cetak;
		$return['periode'] = $periode;
		return $return;
	}
   
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVars('content', $data['header'][0], '');
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($lang=='eng'){
			$period=$this->periode2stringEng($data['periode'],1);
		}else{
			$period=$this->periode2string($data['periode'],1); 
		}
         
		$this->mrTemplate->AddVar('content', 'PERIODE', $period);
		$tmpt=GTFWConfiguration::GetValue('application', 'company_address');
		$this->mrTemplate->AddVar('content', 'TMPT', $tmpt);
         
		if ($lang=='eng'){
			$tglcetak=$this->periode2stringEng(date('Y-m-d'),2);
		}else{
			$tglcetak=$this->periode2string(date('Y-m-d'),2); 
		}
		$this->mrTemplate->AddVar('content', 'TGL', $tglcetak);

		$total_gaji=0;
		if (empty($data['tunjangan'])) {
			$this->mrTemplate->AddVar('data_tunjangan', 'TUNJANGAN_EMPTY', 'YES');
		} else {
			$this->mrTemplate->AddVar('data_tunjangan', 'TUNJANGAN_EMPTY', 'NO');
			$no = 1; $jml = 0;
			$data['slip']=$data['tunjangan'];
			for ($i=0; $i<count($data['slip']); $i++) {
				$data['slip'][$i]['nomer'] = $no++;
				$data['slip'][$i]['nominal_rp'] = number_format($data['slip'][$i]['nominal'], 2, ',', '.');
				$data['slip'][$i]['indodate'] = IndonesianDate($data['slip'][$i]['bb_tanggal'], 'yyyy-mm-dd');
				$jml += $data['slip'][$i]['nominal'];
				if($data['slip'][$i]['kolom']==NULL){
					if ($lang=='eng'){
						$data['slip'][$i]['kolom']="Basic Salary";
						$pendlain="Other Income";
					}else{
						$data['slip'][$i]['kolom']="Gaji Pokok";
						$pendlain="Pendapatan Lain"; 
					}
				}
				$this->mrTemplate->AddVars('data_tunjangan_item', $data['slip'][$i], '');
				$this->mrTemplate->parseTemplate('data_tunjangan_item', 'a');    
			}
			$total_gaji +=$jml;
			$this->mrTemplate->AddVar('content', 'TOTAL_GDP_HONOR_TUNJANGAN', number_format($jml,2,',','.'));
		}
		
		if (empty($data['lain'])) {
			$this->mrTemplate->AddVar('data_lain', 'LAIN_EMPTY', 'YES');
		} else {
			$this->mrTemplate->AddVar('data_lain', 'LAIN_EMPTY', 'NO');
			$no = 1; $jml = 0;
			$data['slip']=$data['lain'];
			for ($i=0; $i<count($data['slip']); $i++) {
				$data['slip'][$i]['nomer'] = $no++;
				$data['slip'][$i]['nominal_rp'] = number_format($data['slip'][$i]['nominal'], 2, ',', '.');
				$data['slip'][$i]['indodate'] = IndonesianDate($data['slip'][$i]['bb_tanggal'], 'yyyy-mm-dd');
				$jml += $data['slip'][$i]['nominal'];
				$this->mrTemplate->AddVars('data_lain_item', $data['slip'][$i], '');
				$this->mrTemplate->parseTemplate('data_lain_item', 'a');    
			}
			
			$this->mrTemplate->AddVar('content', 'TOTAL_TUNJANGAN_LAIN', number_format($jml,2,',','.'));
			$this->mrTemplate->AddVar('content', 'TOTAL_GAJI_KOTOR', number_format($total_gaji+$jml,2,',','.'));
		}
		
		if (empty($data['potongan'])) {
			$this->mrTemplate->AddVar('data_potongan', 'POTONGAN_EMPTY', 'YES');
		} else {
			$this->mrTemplate->AddVar('data_potongan', 'POTONGAN_EMPTY', 'NO');
			
			#print_r($data['saldo']); exit;
			$no = 1; $jml = 0;
			$data['slip']=$data['potongan'];
			for ($i=0; $i<count($data['slip']); $i++) {
				$data['slip'][$i]['nomer'] = $no++;
				$data['slip'][$i]['nominal_rp'] = number_format($data['slip'][$i]['nominal'], 2, ',', '.');
				$data['slip'][$i]['indodate'] = IndonesianDate($data['slip'][$i]['bb_tanggal'], 'yyyy-mm-dd');
				$jml += $data['slip'][$i]['nominal'];
				$this->mrTemplate->AddVars('data_potongan_item', $data['slip'][$i], '');
				$this->mrTemplate->parseTemplate('data_potongan_item', 'a');    
			}
			$total_gaji -=$jml;
			$this->mrTemplate->AddVar('content', 'TOTAL_POTONGAN', number_format($jml,2,',','.'));
		}
		
		$this->mrTemplate->AddVar('content', 'TOTAL_GAJI_BERSIH', number_format($total_gaji,2,',','.'));
	}
   
	function periode2string($date,$a) {
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
	   if($a==1){
       $bulan = substr($date,-2);
  	   $tahun = substr($date,0,4);
  	   return $bln[(int) $bulan].' '.$tahun;
     }else{
       $bulan = substr($date,5,2);
  	   $tahun = substr($date,0,4);
  	   $tanggal = substr($date,8,2);
  	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun;
     }            
	   
	}
	
	function periode2stringEng($date,$a) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   if($a==1){
       $bulan = substr($date,-2);
  	   $tahun = substr($date,0,4);
  	   return $bln[(int) $bulan].' '.$tahun;
     }else{
       $bulan = substr($date,5,2);
  	   $tahun = substr($date,0,4);
  	   $tanggal = substr($date,8,2);
  	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun;
     }  
	}
   
}
?>