<?php

class CetakSK extends Database {

	protected $mSqlFile= 'module/cetak_sk/business/cetak_sk.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
	}
   
	function GetDataDetail ($pegId,$dataId,$jenis) { 
	    $filter = array(
						'fungsional'=>" AND jf.jbtnId=".$dataId." AND pktgolStatus='Aktif' ",
						'pangkat_golongan'=>" AND pktgolId=".$dataId." AND jf.jbtnStatus='Aktif'",
						'gaji_berkala'=>" AND kgbId=".$dataId." AND jf.jbtnStatus='Aktif'  AND pktgolStatus='Aktif' "
					);
		$result = $this->Open($this->mSqlQueries['get_data_detail'].$filter[strval($jenis)],array($pegId));
		//echo vsprintf($this->mSqlQueries['get_data_detail'].$filter[strval($jenis)],array($pegId));
		
		$pangkat_lama = $this->Open($this->mSqlQueries['get_data_pangkat_sebelumnya'],array($result[0]['urutan_pangkat'],$pegId));
		$tunjangan_dosen = $this->Open($this->mSqlQueries['get_tunjangan_dosen'],array($result[0]['jabatan_fungsional']));
		$angka_kredit = $this->Open($this->mSqlQueries['get_angka_kredit'],array($pegId));
		$masa_kerja = $this->Open($this->mSqlQueries['get_masa_kerja'],array($pegId));
		
		if ($result[0]['id_mutasi_gaji_berkala']=='') $result[0]['id_mutasi_gaji_berkala']=0;
		$kgb_lama = $this->Open($this->mSqlQueries['get_data_kgb_sebelumnya'],array($pegId,$result[0]['id_mutasi_gaji_berkala']));
		
		$result[0]['pangkat_golongan_l']=$pangkat_lama[0]['pangkat_golongan_l'];
		$result[0]['tmt_pangkat_golongan_l']=$pangkat_lama[0]['tmt_pangkat_golongan_l'];
		$result[0]['gaji_pokok_l']=$kgb_lama[0]['gaji_pokok_l'];
		$result[0]['tmt_gaji_berkala_l']=$kgb_lama[0]['tmt_gaji_berkala_l'];
		$result[0]['tunjangan_jabatan']=$tunjangan_dosen[0]['tunjangan_dosen'];
		$result[0]['label_tunjangan_jabatan']=number_format($result[0]['tunjangan_jabatan'],0,',','.');
		$result[0]['angka_kredit']=$angka_kredit[0]['angka_kredit']==''?0:$angka_kredit[0]['angka_kredit'];
		$result[0]['masa_kerja_golongan']=$masa_kerja[0]['MKG_TAHUN'].' Tahun '.$masa_kerja[0]['MKG_BULAN'].' Bulan ';
		$result[0]['masa_kerja_seluruh']=$masa_kerja[0]['MKS_TAHUN'].' Tahun '.$masa_kerja[0]['MKS_BULAN'].' Bulan ';
		
		$result[0]['tmt_fungsional']=$this->date2string($result[0]['tmt_fungsional']);
		$result[0]['tmt_pangkat_golongan']=$this->date2string($result[0]['tmt_pangkat_golongan']);
		$result[0]['tmt_pangkat_golongan_l']=$this->date2string($result[0]['tmt_pangkat_golongan_l']);
		$result[0]['tmt_struktural']=$this->date2string($result[0]['tmt_struktural']);
		$result[0]['tmt_gaji_berkala']=$this->date2string($result[0]['tmt_gaji_berkala']);
		$result[0]['tanggal_lahir']=$this->date2string($result[0]['tanggal_lahir']);
		
		
		if ($result[0]['jabatan_fungsional']!=''){
			$result[0]['dosen']= ' Jabatan '.$result[0]['jabatan_fungsional'].' dengan angka kredit '.$result[0]['angka_kredit'];
		}else{
			$result[0]['jabatan_fungsional']=$result[0]['jabatan_struktural'];
			$result[0]['tmt_fungsional']=$result[0]['tmt_struktural'];
		}
		
		//print_r($result);
		
		return $result;
	}

	function kekata($x) {
		$x = abs($x);
		$angka = array("", "satu", "dua", "tiga", "empat", "lima",
		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($x <12) {
			$temp = " ". $angka[$x];
		} else if ($x <20) {
			$temp = $this->kekata($x - 10). " belas";
		} else if ($x <100) {
			$temp = $this->kekata($x/10)." puluh". $this->kekata($x % 10);
		} else if ($x <200) {
			$temp = " seratus" . $this->kekata($x - 100);
		} else if ($x <1000) {
			$temp = $this->kekata($x/100) . " ratus" . $this->kekata($x % 100);
		} else if ($x <2000) {
			$temp = " seribu" . $this->kekata($x - 1000);
		} else if ($x <1000000) {
			$temp = $this->kekata($x/1000) . " ribu" . $this->kekata($x % 1000);
		} else if ($x <1000000000) {
			$temp = $this->kekata($x/1000000) . " juta" . $this->kekata($x % 1000000);
		} else if ($x <1000000000000) {
			$temp = $this->kekata($x/1000000000) . " milyar" . $this->kekata(fmod($x,1000000000));
		} else if ($x <1000000000000000) {
			$temp = $this->kekata($x/1000000000000) . " trilyun" . $this->kekata(fmod($x,1000000000000));
		}
        return $temp;
	}
	
	function terbilang($x, $style=4) {
		if($x<0) {
			$hasil = "minus ". trim($this->kekata($x));
		} else {
			$hasil = trim($this->kekata($x));
		}
		switch ($style) {
			case 1:
				$hasil = strtoupper($hasil);
				break;
			case 2:
				$hasil = strtolower($hasil);
				break;
			case 3:
				$hasil = ucwords($hasil);
				break;
			default:
				$hasil = ucfirst($hasil);
				break;
		}
		return $hasil;
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
}
?>
