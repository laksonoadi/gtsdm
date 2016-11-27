<?php
ini_set("max_execution_time",0);
class ImportData extends Database {
	protected $mSqlFile= 'module/import/business/import_data.sql.php';
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		$this->logPathSave = GTFWConfiguration::GetValue('application', 'file_save_path');
		$this->logPathDownload = GTFWConfiguration::GetValue('application', 'file_download_path');
	}
	
	function SetFileLog($jenis){
		$now=date('YmdHis');
		$this->pathFileLog=$this->logPathDownload."log_import_".$jenis."_".$now.".txt";
		$this->fileLog=fopen($this->logPathSave."log_import_".$jenis."_".$now.".txt","w");
		fputs($this->fileLog,"LOG IMPORT#".$_POST['jenis_nama']."#".date('d-m-Y H:i:s')."#".$_SESSION['username']."\r\n");
		fputs($this->fileLog,"=====================================detail import=========\r\n");
	}
	
	function CloseFileLog($jenis,$result){
		$berhasil=$result[0];
		$gagal=$result[1]-$result[0];
		fputs($this->fileLog,"\r\nRESUME\r\n");
		fputs($this->fileLog,"Total  ".$result[1]." data, berhasil diimport ".$berhasil." data, gagal diimport ".$gagal." data\r\n");
		fputs($this->fileLog,"=====================================last detail import====\r\n");
		fclose($this->fileLog);
	}
	
	function ValidTanggal($tanggal){
		$i=strpos($tanggal,'-');
		if ($i!=4) return false;
		return true;
		
	}

	function ImportRiwayatUnitKerja($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[5])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[8])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal SK</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Unit Kerja
				5	- TMT
				6	- SK Pejabat
				7	- SK Nomor
				8	- SK Tanggal
				9	- Status Mutasi
			*/
			if (($data[2]!='')&&($data[4]!='')) {
				$this->idMutasiUnitKerja=$this->KelolaUnitKerjaLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);
			}else{
				$this->idMutasiUnitKerja='';
			}
			if ($this->idMutasiUnitKerja!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportRiwayatGolongan($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[5])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[6])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT Berikut</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[9])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal SK</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Golongan
				5	- TMT
				6	- TMT Berikut
				7	- SK Pejabat
				8	- SK Nomor
				9	- SK Tanggal
				10	- Peraturan Dasar
				11	- Status Mutasi
			*/
			if (($data[2]!='')&&($data[4]!='')) {
				$this->idMutasiGolongan=$this->KelolaGolonganLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
			}else{
				$this->idMutasiGolongan='';
			}
			if ($this->idMutasiGolongan!=''){
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportRiwayatJabatanFungsional($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[5])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[8])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal SK</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Jabatan Fungsional
				5	- TMT
				6	- SK Pejabat
				7	- SK Nomor
				8	- SK Tanggal
				9	- Status Mutasi
			*/
			if (($data[2]!='')&&($data[4]!='')) {
				$this->idMutasiJabFung=$this->KelolaJabatanFungsionalLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);
			}else{
				$this->idMutasiJabFung='';
			}
			if ($this->idMutasiJabFung!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportRiwayatJabatanStruktural($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[5])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[8])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal SK</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Jabatan Struktural
				5	- TMT
				6	- SK Pejabat
				7	- SK Nomor
				8	- SK Tanggal
				9	- Status Mutasi
			*/
			if (($data[2]!='')&&($data[4]!='')) {
				$this->idMutasiJabStruk=$this->KelolaJabatanStrukturalLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]);
			}else{
				$this->idMutasiJabStruk='';
			}
			if ($this->idMutasiJabStruk!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportRiwayatPendidikan($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[6])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal Mulai</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[7])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal Selesai</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Jenjang Studi
				5	- Masa Studi
				6	- Tanggal Mulai
				7	- Tanggal Selesai
				8	- Perguruan Tinggi
				9	- Negara
				10	- Bidang Ilmu
				11	- Tahun Lulus
				12	- Dekan
				13	- Status
			*/
			if (($data[13]!='Selesai')&&($data[13]!='Masa Pendidikan'))$data[13]='Selesai';
			if (($data[2]!='')&&($data[4]!='')) {
				$this->idMutasiPendidikan=$this->KelolaPendidikanLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11],$data[12],$data[13]);
			}else{
				$this->idMutasiPendidikan='';
			}
			if ($this->idMutasiPendidikan!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportRiwayatGajiPokok($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			if (!$this->ValidTanggal($data[6])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[7])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT Berikut</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[10])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal SK</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Golongan
				5	- Gaji Pokok
				6	- TMT
				7	- TMT Berikut
				8	- SK Pejabat
				9	- SK Nomor
				10	- SK Tanggal
				11	- Status Mutasi
			*/
			if (($data[2]!='')&&($data[4]!='')&&($data[5]!='')) {
				$this->idMutasiGajiPokok=$this->KelolaGajiPokokLengkap($data[2],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
			}else{
				$this->idMutasiGajiPokok='';
			}
			
			if ($this->idMutasiGajiPokok!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		
		return array($success,$jumData,$add_pesan);
	}
	
	function ImportDUK($arrData){
		$jumData=sizeof($arrData)-3;
		$success=0;
		$add_pesan='';
		for ($i=4; $i<=sizeof($arrData); $i++){
			$data=$arrData[$i];
			$data[2]=str_replace(' ','',$data[2]);
			/*if (!$this->ValidTanggal($data[7])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT Pangkat Golongan</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[9])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT Jabatan Fungsional</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[11])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>TMT Jabatan Struktural</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}elseif (!$this->ValidTanggal($data[21])){
				$add_pesan .='<br>Format Tanggal untuk kolom <b>Tanggal Lahir</b> Salah untuk pegawai dengan nip '.$data[2].'. Format tanggal harus YYYY-MM-DD.';
				$add_pesan .='Jika memang kosong maka beri nilai 0000-00-00.';
				break;
			}*/
			/*
				1 	- Nomor
				2 	- NIP
				3 	- Nama
				4	- Jenis Kelamin
				5	- Jenis Pegawai
				6	- Pangkat/Golongan
				7	- TMT Pangkat/Golongan
				8	- Jabatan Fungsional
				9	- TMT jabatan fungsional
				10	- Jabatan Struktural
				11	- TMT Jabatan struktural
				12	- Masa Kerja Tahun
				13	- Masa Kerja Bulan
				14	- Tahun Diklat
				15	- Nama Diklat
				16	- Jumlah Jam Diklat
				17	- Nama Perguruan Tinggi Pendidikan Terakhir
				18	- Bidang ilmu Pendidikan Terakhir
				19	- Tahun Lulus
				20	- Tingkat Pendidikan
				21	- Tanggal Lahir
				22	- Unit Kerja
				23	- TMT Unit Kerja
				
				
			*/
			
			if ($data[2]!='') {
				$this->idPeg=$this->KelolaDataPegawaiDUK($data[2],addslashes($data[3]),$data[4],$data[5],$data[21]);
			}else{
				$this->idPeg='';
			}
			
			if ($this->idPeg!='') {
				$this->idGajiPeg=$this->KelolaReferensi($this->idPeg,'master_gaji');
				if ($data[6]!='') $this->idMutasiGolongan=$this->KelolaPangkatGolonganDUK($data[6],$data[7]);
				if ($data[8]!='') $this->idMutasiJabFung=$this->KelolaJabatanFungsionalDUK($data[8],$data[9]);
				if ($data[10]!='') $this->idMutasiJabStruk=$this->KelolaJabatanStrukturalDUK($data[10],$data[11]);
				if ($data[12]!=0) $this->idMutasiMasaKerja=$this->KelolaMasaKerjaDUK($data[12],$data[13]);
				if ($data[17]!='') $this->idMutasiPendidikan=$this->KelolaPendidikanDUK($data[17],$data[18],$data[19],$data[20]);
				if ($data[22]!='') $this->idMutasiUnitKerja=$this->KelolaUnitKerjaDUK($data[22],$data[23]);
			}
			
			if ($this->idPeg!='') {
				$success++;
				fputs($this->fileLog,"Data dengan nip ".$data[2]." berhasil diimport ke sistem.\r\n");
			}else{
				fputs($this->fileLog,"Data dengan nip ".$data[2]." gagal diimport ke sistem.\r\n");
			}
		}
		
		return array($success,$jumData,$add_pesan);
	}
	
	function KelolaDataPegawaiDUK($nip,$nama,$jk,$jpeg,$ttl){
		$nip=str_replace(' ','',$nip);
		if ((strtoupper($jk)=='LAKI-LAKI') || (strtoupper($jk)=='PRIA')){ $jk='L'; }else
		if ((strtoupper($jk)=='PEREMPUAN') || (strtoupper($jk)=='WANITA')){ $jk='P'; }
		
		$this->idJpeg=$this->KelolaReferensi($jpeg,'jenis_pegawai');
		
		$check = $this->Open($this->mSqlQueries['get_data_pegawai_duk'], array($nip));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_data_pegawai_duk'], array($nip,$nama,$jk,$this->idJpeg,$ttl,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_data_pegawai_duk'], array($nip,$nama,$jk,$this->idJpeg,$ttl));
		}
		
		$check= $this->Open($this->mSqlQueries['get_data_pegawai_duk'], array($nip));
		
		return $check[0]['id'];
	}
	
	function KelolaPangkatGolonganDUK($nama,$tmt){
		$this->idPangkatGolongan=$this->KelolaReferensi($nama,'pangkat_golongan');
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_pangkat_golongan_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_pangkat_golongan_duk'], array($this->idPangkatGolongan,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_pangkat_golongan_duk'], array($this->idPeg,$this->idJpeg,$this->idPangkatGolongan,$tmt,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_pangkat_golongan_duk'], array($this->idPeg,$this->idJpeg,$this->idPangkatGolongan,$tmt));
		}
		
		$check= $this->Open($this->mSqlQueries['get_pangkat_golongan_duk'], array($this->idPangkatGolongan,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaJabatanFungsionalDUK($nama,$tmt){
		$unix='jabatan_fungsional';
		$this->idJabFung=$this->KelolaReferensi($nama,$unix);
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabFung,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_duk'], array($this->idPeg,$this->idPangkatGolongan,$this->idJabFung,$tmt,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_duk'], array($this->idPeg,$this->idPangkatGolongan,$this->idJabFung,$tmt));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabFung,$this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaJabatanStrukturalDUK($nama,$tmt){
		$unix='jabatan_struktural';
		$this->idJabStruk=$this->KelolaReferensi($nama,$unix);
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabStruk,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_duk'], array($this->idPeg,$this->idPangkatGolongan,$this->idJabStruk,$tmt,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_duk'], array($this->idPeg,$this->idPangkatGolongan,$this->idJabStruk,$tmt));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabStruk,$this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaMasaKerjaDUK($tahun,$bulan){
		$unix='masa_kerja';
		$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_duk'], array($this->idPeg,$tahun,$bulan));
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaPendidikanDUK($nama,$bidang,$tahun,$tingkat){
		$unix='pendidikan';
		$this->idPendidikan=$this->KelolaReferensi($tingkat,$unix);
		
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idPendidikan,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_duk'], array($this->idPeg,$this->idPendidikan,$nama,$bidang,$tahun,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_duk'], array($this->idPeg,$this->idPendidikan,$nama,$bidang,$tahun));
		}
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idPendidikan,$this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaUnitKerjaDUK($nama,$tmt){
		$unix='unit_kerja';
		$this->idUnitKerja=$this->KelolaReferensi($nama,$unix);
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idUnitKerja,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_duk'], array($this->idPeg,$this->idUnitKerja,$tmt,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_duk'], array($this->idPeg,$this->idUnitKerja,$tmt));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idUnitKerja,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaUnitKerjaLengkap($nip,$nama,$tmt,$skPejabat,$skNo,$skTanggal,$status){
		$nip=str_replace(' ','',$nip);
		$unix='unit_kerja';
		$this->idUnitKerja=$this->KelolaReferensi($nama,$unix);
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idUnitKerja,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idUnitKerja,$tmt,$skPejabat,$skNo,$skTanggal,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idUnitKerja,$tmt,$skPejabat,$skNo,$skTanggal,$status));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idUnitKerja,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaGolonganLengkap($nip,$nama,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$peraturan,$status){
		$nip=str_replace(' ','',$nip);
		$unix='pangkat_golongan';
		$this->idGolongan=$this->KelolaReferensi($nama,$unix);
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idGolongan,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idGolongan,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$peraturan,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idGolongan,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$peraturan,$status));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idGolongan,$this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaJabatanFungsionalLengkap($nip,$nama,$tmt,$skPejabat,$skNo,$skTanggal,$status){
		$nip=str_replace(' ','',$nip);
		$unix='jabatan_fungsional';
		$this->idJabFung=$this->KelolaReferensi($nama,$unix);
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabFung,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idJabFung,$tmt,$skPejabat,$skNo,$skTanggal,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idJabFung,$tmt,$skPejabat,$skNo,$skTanggal,$status));
		}
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabFung,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaJabatanStrukturalLengkap($nip,$nama,$tmt,$skPejabat,$skNo,$skTanggal,$status){
		$nip=str_replace(' ','',$nip);
		$unix='jabatan_struktural';
		$this->idJabStruk=$this->KelolaReferensi($nama,$unix);
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_duk'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabStruk,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idJabStruk,$tmt,$skPejabat,$skNo,$skTanggal,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idJabStruk,$tmt,$skPejabat,$skNo,$skTanggal,$status));
		}
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idJabStruk,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaPendidikanLengkap($nip,$nama,$lama,$mulai,$selesai,$pt,$negara,$bidang,$tahunLulus,$dekan,$status){
		$nip=str_replace(' ','',$nip);
		$unix='pendidikan';
		$this->idPendidikan=$this->KelolaReferensi($nama,$unix);
		$this->idNegara=$this->KelolaReferensi($negara,'negara');
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idPendidikan,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idPendidikan,$lama,$mulai,$selesai,$pt,$this->idNegara,$bidang,$tahunLulus,$dekan,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idPendidikan,$lama,$mulai,$selesai,$pt,$this->idNegara,$bidang,$tahunLulus,$dekan,$status));
		}
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_duk'], array($this->idPendidikan,$this->idPeg));
		
		return $check[0]['id'];
	}
	
	function KelolaGajiPokokLengkap($nip,$nama,$gaji,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$status){
		$nip=str_replace(' ','',$nip);
		$unix='gaji_pokok';
		$this->idGolongan=$this->KelolaReferensi($nama,'pangkat_golongan');
		$this->idPeg=$this->KelolaReferensi($nip,'pegawai');
		$result=$this->Execute($this->mSqlQueries['nonaktifkan_'.$unix.'_lengkap'], array($this->idPeg));
		$check = $this->Open($this->mSqlQueries['get_'.$unix.'_lengkap'], array($this->idGolongan,$this->idPeg));
		if (sizeof($check)>0){
			$result=$this->Execute($this->mSqlQueries['update_'.$unix.'_lengkap'], array($this->idPeg,$this->idGolongan,$gaji,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$status,$check[0]['id']));
		}else{
			$result=$this->Execute($this->mSqlQueries['add_'.$unix.'_lengkap'], array($this->idPeg,$this->idGolongan,$gaji,$tmt,$tmtnext,$skPejabat,$skNo,$skTanggal,$status));
		}
		
		$check= $this->Open($this->mSqlQueries['get_'.$unix.'_lengkap'], array($this->idGolongan,$this->idPeg));
		return $check[0]['id'];
	}
	
	function KelolaReferensi($nama,$tipe_referensi){
		if ($tipe_referensi=='jenis_pegawai'){
			$table_name='sdm_ref_jenis_pegawai';
			$field_id='jnspegrId';
			$field_name='jnspegrNama';
		}else if ($tipe_referensi=='pangkat_golongan'){
			$table_name='sdm_ref_pangkat_golongan';
			$field_id='pktgolrId';
			$field_name='pktgolrId';
		}else if ($tipe_referensi=='jabatan_fungsional'){
			$table_name='pub_ref_jabatan_fungsional';
			$field_id='jabfungrId';
			$field_name='jabfungrNama';
		}else if ($tipe_referensi=='jabatan_struktural'){
			$table_name='sdm_ref_jabatan_struktural';
			$field_id='jabstrukrId';
			$field_name='jabstrukrNama';
		}else if ($tipe_referensi=='pendidikan'){
			$table_name='pub_ref_pendidikan';
			$field_id='pendId';
			$field_name='pendNama';
		}else if ($tipe_referensi=='unit_kerja'){
			$table_name='pub_satuan_kerja';
			$field_id='satkerId';
			$field_name='satkerNama';
			$adding_add=",satkerLevel,satkerParentId,satkerUnitId,satkerCreationDate";
			$value_adding_add=",'1.',1,2,now()";
		}else if ($tipe_referensi=='pegawai'){
			$table_name='pub_pegawai';
			$field_id='pegId';
			$field_name='pegKodeResmi';
		}else if ($tipe_referensi=='negara'){
			$table_name='pub_ref_satuan_wilayah';
			$field_id='satwilId';
			$field_name='satwilNama';
		}else if ($tipe_referensi=='master_gaji'){
			$table_name='sdm_ref_master_gaji';
			$field_id='mstgajiId';
			$field_name='mstgajiPegId';
			$adding_add=",mstgajiIsCash,mstgajiTanggalGaji,mstgajiIsAktif";
			$value_adding_add=",'Ya',1,'Tidak'";
		}
		
		$query_get='SELECT '.$field_id.' as id, '.$field_name.' as name FROM '.$table_name.' WHERE UPPER('.$field_name.')=UPPER(\''.$nama.'\')';
		$query_add='INSERT INTO '.$table_name.'('.$field_name.$adding_add.') VALUES(\''.$nama.'\''.$value_adding_add.') ';
		$query_update='UPDATE '.$table_name.' SET '.$field_name.'=\''.$nama.'\' WHERE '.$field_id.'=\'%s\'';
		
		$check = $this->Open($query_get,array());
		if (sizeof($check)>0){
			$result=$this->Execute($query_update,array($check[0]['id']));
		}else{
			$result=$this->Execute($query_add,array());
		}
		
		
		$check= $this->Open($query_get,array());
		
		return $check[0]['id'];
	}


   
}
?>
