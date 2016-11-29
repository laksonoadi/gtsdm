<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_pph_pegawai_komponen/business/PphPegawaiKomp.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot').'main/function/terbilang.php';

class PrintSptMassal extends HtmlResponse {	
	function __construct(){
      $this->arrBulan = array("Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni", 
                        "Juli",
                        "Agustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember");
	}
   
	function toIndonesianFormatDate($tanggal){   
      $arr  =  explode("-", $tanggal);      
      if(is_array($arr)):         
         $bulan   = $this->arrBulan[(int)$arr[1]-1];
         $tanggal = $arr[2]." ".$bulan." ".$arr[0];       
      endif;
      return $tanggal;
	}
   
	function convertBulan($bulan){
      return $bulan  = $this->arrBulan[(int)$bulan-1];
	}

	function FormatArray($arr){
		$j = 0;
		$k = 0;
		for($i=0;$i<count($arr);$i++){
			if($i > 0){
				if($arr[$i]['KATEGORI_ID'] == $arr[$i-1]['KATEGORI_ID']){
					$k++;
				}else{
					$k = 0;
					$j++;
				}
			}
			$arr_rumus[$j][$k] = $arr[$i];
		}
		return $arr_rumus;
	}
	
	function ProcessRequest() {		
		$pphObj = new PphPegawaiKomp();
		//$idJob = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		
		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/templete_SPT_masal.rtf");
		$contents = str_replace('[NPWP]', $dataPph[0]['NPWP'], $contents);
		$contents = str_replace('[NAMA PEMOTONG PAJAK]', $dataPph[0]['NAMA_PEMOTONG_PAJAK'], $contents);
		$contents = str_replace('[ALAMAT PEMOTONG PAJAK]', $dataPph[0]['CUS_NPWP'], $contents);
		$contents = str_replace('[BULAN]', $this->arrBulan[(int)(date('m')-1)], $contents);
		$contents = str_replace('[TAHUN]', (date('Y')), $contents);
		$contents = str_replace('[TANGGAL SETOR]', $this->toIndonesianFormatDate(date('Y-m-d')), $contents);
		
		for($i=1;$i<15;$i++){
		$contents = str_replace('[MAP/KJS'.$i.']', $data['port'][0]['PORT_NAME'], $contents);
		$contents = str_replace('[JPP'.$i.']', $this->toIndonesianFormatDate($data['port'][0]['JOB_PORT_DATE_ARRIVED']), $contents);
		$contents = str_replace('[JPB'.$i.']', $this->toIndonesianFormatDate($data['port'][0]['JOB_PORT_DATE_SAILED']), $contents);
		$contents = str_replace('[PPH POTONG'.$i.']', $nextPort, $contents);
		}
		
		$contents = str_replace('[JUMLAH BRUTO] ', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[JUMLAH PPH] ', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[KELEBIHAN SETOR]', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[PPH BLM DIHITUNG]', $dataPph[0]['VES_NAME'], $contents);
		
		for($i=1;$i<3;$i++){
		$contents = str_replace('[MAP/KJS FINAL1'.$i.']', $data['port'][0]['PORT_NAME'], $contents);
		$contents = str_replace('[JPP FINAL'.$i.']', $this->toIndonesianFormatDate($data['port'][0]['JOB_PORT_DATE_ARRIVED']), $contents);
		$contents = str_replace('[JPB FINAL'.$i.']', $this->toIndonesianFormatDate($data['port'][0]['JOB_PORT_DATE_ARRIVED']), $contents);
		$contents = str_replace('[PPH FINAL'.$i.']', $this->toIndonesianFormatDate($data['port'][0]['JOB_PORT_DATE_ARRIVED']), $contents);
		}
		
		$contents = str_replace('[JML BRUTO]', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[JML PPH]', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[TERBILANG] ', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[CEK]', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[BANYAK BUKTI PEMOTONGAN]', $dataPph[0]['VES_NAME'], $contents);
		$contents = str_replace('[TGL PEMOTONGAN PAJAK]', $dataPph[0]['VES_NAME'], $contents);
		
		header("Content-type: application/msword");
		header("Content-disposition: inline; filename=spt_massal".$dataPph[0]['NO_SURAT'].'_'.(date('d-m-Y')).".rtf");
		header("Content-length: " . strlen($contents));
		print $contents;
      
	}
   
}
?>
