<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_pph_pegawai_komponen/business/PphPegawaiKomp.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot').'main/function/terbilang.php';

class PrintSpt extends HtmlResponse {	
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
	
	function ProcessRequest() {	
		$pphObj = new PphPegawaiKomp();
		$idPegawai = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
		$nipPegawai = Dispatcher::Instance()->Decrypt($_GET['nip']->Raw());
		
		$dataPph = $pphObj->GetDataKompPegById($idPegawai);	
	
		$dataTunjanganPph = $pphObj->GetJumlahNominal($idPegawai);
		
		$cetak[0]['terbilang'] = terbilang($cetak[0]['jumlah_anggaran'],2);
		
		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/templete_SPT.rtf");
		$contents = str_replace('[NOMOR URUT]', $dataPph[0]['nomor_urut'], $contents);
		$contents = str_replace('[NPWP PEMOTONG PAJAK]', $dataPph[0]['npwp_pemotong_pajak'], $contents);
		$contents = str_replace('[NAMA PEMOTONG PAJAK]', $dataPph[0]['nama_pemotong_pajak'], $contents);
		$contents = str_replace('[ALAMAT PEMOTONG PAJAK]', $dataPph[0]['alamat_pemotong_pajak'], $contents);
		$contents = str_replace('[NAMA PEGAWAI ATAU PENERIMA PENSIUN/THT/JHT]', $dataPph[0]['nama_pegawai'], $contents);
		$contents = str_replace('[NPWP PEGAWAI ATAU PENERIMA PENSIUN/THT/JHT]', $dataPph[0]['npwp_pegawai'], $contents);
		$contents = str_replace('[ALAMAT PEGAWAI ATAU PENERIMA PENSIUN/THT/JHT]', $dataPph[0]['alamat_pegawai'], $contents);
		$contents = str_replace('[JABATAN]', $dataPph[0]['jabatan'], $contents);
		
		$contents = str_replace('[GAJI/PENSIUN ATAU THT/JHT]', number_format($dataPph[0]['nominal_komponen'], 2, ',', '.'), $contents);
		$contents = str_replace('[TUNJANGAN PPH]', number_format($dataTunjanganPph[0]['jumlah'], 2, ',', '.'), $contents);
		$contents = str_replace('[TUNJANGAN LAINNYA, UANG LEMBUR, DAN SEBAGAINYA]', $dataPph[0]['tunjangan'], $contents);
		$contents = str_replace('[HONORARIUM DAN IMBALAN LAIN SEJENISNYA]', $dataPph[0]['honorarium'], $contents);
		$contents = str_replace('[PREMI ASURANSI YANG DIBAYAR PEMBERI KERJA]', $dataPph[0]['premi_asuransi'], $contents);
		$contents = str_replace('[PENERIMAAN DALAM BENTUK LAIN DAN KENIKMATAN LAIN]', $data['port'][0]['kenikmatan_lain'], $contents);
		
		$jumlah=$dataPph[0]['nominal_komponen']+$dataTunjanganPph[0]['jumlah']+$dataPph[0]['tunjangan']+$dataPph[0]['honorarium']+
				$dataPph[0]['premi_asuransi']+$data['port'][0]['kenikmatan_lain'];
		
		$contents = str_replace('[JUMLAH]', number_format($jumlah, 2, ',', '.'), $contents);
		$contents = str_replace('[TANTIEM, BONUS, GRATIFIKASI, JASA PRODUKSI DAN THR]', number_format($data['port'][0]['tantiem'], 2, ',', '.'), $contents);
		$jumlahPenghasilanBruto=$jumlah+$data['port'][0]['tantiem'];
		$contents = str_replace('[JUMLAH PENGHASILAN BRUTO]', number_format($jumlahPenghasilanBruto, 2, ',', '.'), $contents);
		$contents = str_replace('[BIAYA JABATAN/BIAYA PENSIUN PENGHASILAN ANGKA 7]', number_format($jumlah, 2, ',', '.'), $contents);
		$contents = str_replace('[BIAYA JABATAN/BIAYA PENSIUN PENGHASILAN ANGKA 8]', number_format($data['port'][0]['tantiem'], 2, ',', '.'), $contents);
		$contents = str_replace('[IURAN PENSIUN ATAU IURAN THT/JHT]', $dataPph[0]['iuran_pensiun'], $contents);
		
		$jumlahPengurangan=$jumlah+$data['port'][0]['tantiem']+$dataPph[0]['iuran_pensiun'];
		
		$contents = str_replace('[JUMLAH PENGURANGAN]', number_format($jumlahPengurangan, 2, ',', '.'), $contents);
		
		$jumlahPenghasilanNeto=$jumlahPenghasilanBruto-$jumlahPengurangan;
		
		$contents = str_replace('[JUMLAH PENGHASILAN NETO]', number_format($jumlahPenghasilanNeto, 2, ',', '.'), $contents);
		$contents = str_replace('[PENGHASILAN NETO MASA SEBELUMNYA]', number_format($dataPph[0]['neto_masa_sebelumnya'], 2, ',', '.'), $contents);
		$contents = str_replace('[JUMLAH PENGHASILAN NETO PENGHITUNGAN SETAHUN]', number_format($dataPph[0]['neto_penghitungan_setahun'], 2, ',', '.'), $contents);
		$contents = str_replace('[PENGHASILAN TIDAK KENA PAJAK]', number_format($dataPph[0]['ptkp'], 2, ',', '.'), $contents);
		
		$kenaPajakSetahun=$dataPph[0]['neto_penghitungan_setahun']-$dataPph[0]['ptkp'];
		
		$contents = str_replace('[PENGHASILAN KENA PAJAK SETAHUN/DISETAHUNKAN]',  number_format($kenaPajakSetahun, 2, ',', '.'), $contents);
		$contents = str_replace('[PPH PASAL 21 PENGHASILAN KENA PAJAK SETAHUN]', number_format($dataPph[0]['penghasilan_kena_pajak_setahun'], 2, ',', '.'), $contents);
		$contents = str_replace('[PPH PASAL 21 TELAH DIPOTONG MASA SEBELUMNYA]', number_format($dataPph[0]['dipotong_masa_sebelumnya'], 2, ',', '.'), $contents);
		$contents = str_replace('[PPH PASAL 21 TERUTANG]', number_format($dataPph[0]['terutang'], 2, ',', '.'), $contents);
		$contents = str_replace('[PPH PASAL 21 DITANGGUNG PEMERINTAH]', number_format($dataPph[0]['ditanggung_pemerintah'], 2, ',', '.'), $contents);
		
		$harusDipotong=$dataPph[0]['terutang']-$dataPph[0]['ditanggung_pemerintah'];
		
		$contents = str_replace('[PPH PASAL 21 YANG HARUS DIPOTONG]', number_format($harusDipotong, 2, ',', '.'), $contents);
		$contents = str_replace('[PPH PASAL 21 DAN PASAL 26 TELAH DIPOTONG DILUNASI]', $dataPph[0]['dipotong_dilunasi'], $contents);
		
		$jumlahPph=$harusDipotong-$dataPph[0]['dipotong_dilunasi'];
		
		$contents = str_replace('[JUMLAH PPH PASAL 21]', number_format($jumlahPph, 2, ',', '.'), $contents);
		$contents = str_replace('[BULAN]', $dataPph[0]['PPH_NAME'], $contents);
		$contents = str_replace('[TAHUN]', $dataPph[0]['PPH_NAME'], $contents);
		
		$contents = str_replace('[JUMLAH TERSEBUT PADA ANGKA 25]', number_format($jumlahPph, 2, ',', '.'), $contents);
		$contents = str_replace('[TGL]', (date('d')), $contents);
		$contents = str_replace('[BLN]', $this->arrBulan[(int)(date('m')-1)], $contents);
		$contents = str_replace('[THN]', (date('Y')), $contents);
		$contents = str_replace('[NAMA LENGKAP]', $dataPph[0]['PPH_NAME'], $contents);
		$contents = str_replace('[NPWP]', $dataPph[0]['PPH_NAME'], $contents);
		
		header("Content-type: application/msword");
		header("Content-disposition: inline; filename=spt_nip".$nipPegawai.'_'.(date('d-m-Y')).".rtf");
		header("Content-length: " . strlen($contents));
		print $contents;
      
	}
   
}
?>
