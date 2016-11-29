<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/business/pendapatan_lain.class.php';

class ViewDetailPendapatanLain extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/pendapatan_lain/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('detail_pendapatan_lain.html');
	}
	
	function ProcessRequest() {
		$idDec = Dispatcher::Instance()->Decrypt((string)$_GET['dataId']);
    $tglDec = Dispatcher::Instance()->Decrypt((string)$_GET['tglId']);
		$Obj = new PendapatanLain();

		$dataPendapatan = $Obj->GetDataById($idDec,$tglDec);
		$pegawai = $Obj->GetPegawaiById($idDec,$tglDec);
    
		$return['info'] = $dataPendapatan;
		$return['pegawai'] = $pegawai;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		$info = $data['info'];
		$this->mrTemplate->AddVar('content', 'JENIS', $info['nama']);
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if($lang=='eng'){
      $tgl=$this->periode2stringEng($info['tgl']);
    }else{
      $tgl=$this->periode2string($info['tgl']);
    }
		$this->mrTemplate->AddVar('content', 'TANGGAL', $tgl);
		
		$nom = number_format($info['nominal'], 2, ',', '.');
		$this->mrTemplate->AddVar('content', 'NOMINAL', $nom);
		
		$this->mrTemplate->AddVar('content', 'DESKRIPSI', $info['des']);

      if(empty($data['pegawai'])) {
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'YES');
      } else {
		   $pegawai = $data['pegawai'];
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'NO');
         for($i=0; $i<sizeof($pegawai); $i++) {
            /*if($pegawai[$i]['id'] == $pegawai[$i-1]['id']) {
               $pegawai[$i]['nama'] = "";
            }*/
          $pegawai[$i]['nominal'] = "Rp. ".number_format($pegawai[$i]['nominal'], 2, ',', '.');
				  $this->mrTemplate->AddVars('data_item', $pegawai[$i], 'DATA_');
				  $this->mrTemplate->parseTemplate('data_item', 'a');	 
         }
      }
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'pendapatanLain', 'view', 'html'));
	}
	
	function periode2string($date) {
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
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   $tanggal = substr($date,8,2);
	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
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
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   $tanggal = substr($date,8,2);
	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun;
	}
}
?>
