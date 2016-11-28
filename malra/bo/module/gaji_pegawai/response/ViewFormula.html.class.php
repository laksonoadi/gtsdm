<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppPayrollPegawai.class.php';

class ViewFormula extends HtmlResponse {
	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gaji_pegawai/template');
		$this->SetTemplateFile('view_formula.html');
	}
	
	function ProcessRequest() {
		$obj = new AppPayrollPegawai();
		$nip = Dispatcher::Instance()->Decrypt((string)$_GET['nip']);
		$idPeg = Dispatcher::Instance()->Decrypt((string)$_GET['idPeg']);
		$periode_year = Dispatcher::Instance()->Decrypt((string)$_GET['periode_year']);
		$periode_mon = Dispatcher::Instance()->Decrypt((string)$_GET['periode_mon']);
		$idFormula = $_GET['idFormula'];
		
		$temp=$obj->GetJumlahHari($periode_year.'-'.$periode_mon,$idPeg);
		$this->jumlahhari = $temp[0];
		$this->absen = $temp[0]-$temp[1];
		$this->kategori1 = $temp[2];
		$this->kategori2 = $temp[3];
		$this->kategori0 = $temp[4];
		$this->kategori3 = $temp[5];
		
		$jumlahhari = $this->jumlahhari;
		$absen = $this->absen;
		$variabel= $obj->GetVariabelPegawai($nip);
		$jenis = $variabel['jenis'];
		$jabatan = $variabel['jabatan_label'];
		$semester = $variabel['semester'];
		$studi = $variabel['studi'];
		$jenjang = $variabel['jenjang'];
		$dosen = $variabel['dosen'];
		
		//pembedaan gapok (swasta/negeri)
		$setGaji=GTFWConfiguration::GetValue('application', 'set_gaji');
		if ($setGaji=='swasta'){
			$dataGapok = $obj->GetGapokSwasta($idPeg);
		}else{
			$dataGapok = $obj->GetGapokNegeri($idPeg);
		}
		//sampai sini

		$komponenGajiPeg = $obj->GetKomponenGajiPegawai($nip);
		$formula = $obj->GetFormulaGaji($idFormula,$nip);
      
		for($i=0;$i<count($komponenGajiPeg);$i++){
			$kode[$i] = '['.$komponenGajiPeg[$i]['kompgajiKode'].']';
			if($komponenGajiPeg[$i]['id']==1){
				$value[$i] = (int)$dataGapok[0]['gapok'];
			}else{
				$value[$i] = $komponenGajiPeg[$i]['nominal'];
			}
		}
		
		$kode[]='[JUMLAHHARI]'; $value[]=$jumlahhari;
		$kode[]='[ABSEN]'; $value[]=$absen;
		$kode[]='[KATEGORI0]'; $value[]=$this->kategori0;
		$kode[]='[KATEGORI1]'; $value[]=$this->kategori1;
		$kode[]='[KATEGORI2]'; $value[]=$this->kategori2;
		$kode[]=$jabatan=='DOSEN'?'[ISDOSEN]':'[ISDOSEN]'; $value[]=$jabatan=='DOSEN'?1:0;
		if ($dosen=='DOSEN'){
			$kode[]='[DOSEN]'; $value[]=1;
			$kode[]='[KARYAWAN]'; $value[]=0;
		}else{
			$kode[]='[DOSEN]'; $value[]=0;
			$kode[]='[KARYAWAN]'; $value[]=1;
		}
	
		$newSql = str_replace($kode,$value,$formula);

		ob_start(); 
		if (is_integer(strpos($newSql,'$value'))){
			eval("$newSql");
		}else{
			eval("\$value=$newSql;");
		}
		$ret = ob_get_contents();
		ob_end_clean();
	
		if (preg_match("/Parse error/",$ret)) $value=0;
	
		if (is_array($value)) $value=0;
	
		$return['formula']['id_formula'] = $idFormula->mrVariable;
		$return['formula']['nominal'] = number_format($value, 2, ',', '.');
		$return['formula']['ori'] = $value;
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->addVars('content',$data['formula'],'PAYROLL_');
	}
}
?>
