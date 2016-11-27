<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/informasi/business/popup_informasi.class.php';

class ViewPopupInformasi extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/informasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_informasi.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$this->Obj= new PopupInformasi();
		
		$return['user']=$this->Obj->GetUserLengkap();
		$return['pensiun']=$this->Obj->GetListPegawaiPensiun();
		$return['naik_pangkat']=$this->Obj->GetListPegawaiNaikPangkat();
		$return['naik_gaji']=$this->Obj->GetListPegawaiNaikGaji();
		// $return['cuti']=$this->Obj->GetListPegawaiCuti();
		// $return['lembur']=$this->Obj->GetListPegawaiLembur();
		$return['verifikasi']=$this->Obj->GetListPegawaiVerifikasi();
		// $return['pak']=$this->Obj->GetListPegawaiPAK();
		// $return['bkd']=$this->Obj->GetListPegawaiBKD();
		$return['ultah']=$this->Obj->GetCountDaftarPegawai();
		$return['satya'] = $this->Obj->GetCountDaftarPegawaiSatya();
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVars('content', $data['user'], '');
		
		$tipe=$_GET['tipe']->Raw();
		if ($tipe=='') $tipe='informasi';
		
		if (empty($data['ultah'])) {
					$this->mrTemplate->AddVar('notifikasi_pegawai_ultah', 'DATA_EMPTY', 'YES');
				} else {      
					$this->mrTemplate->AddVar('notifikasi_pegawai_ultah', 'DATA_EMPTY', 'NO');
					$this->mrTemplate->AddVar('notifikasi_pegawai_ultah_list', 'JUMLAH_ULTAH',$data['ultah']['0']['TOTAL']);
				}

			if (empty($data['satya'])) {
					$this->mrTemplate->AddVar('notifikasi_pegawai_satya', 'DATA_EMPTY', 'YES');
				} else {      
					$this->mrTemplate->AddVar('notifikasi_pegawai_satya', 'DATA_EMPTY', 'NO');
					$this->mrTemplate->AddVar('notifikasi_pegawai_satya_list', 'JUMLAH_SATYA',$data['satya']['0']['TOTAL']);
				}
				

		if ($tipe=='ringkasan'){
			$jumlah = 0;
			$this->mrTemplate->AddVar('tipe', 'TIPE', strtoupper($tipe));
			$val=array('pensiun','naik_pangkat','naik_gaji');
			for ($i=0; $i<sizeof($val); $i++){
				if (empty($data[$val[$i]])) {
				} else {      
					$list=$data[$val[$i]];
					$jumlah++;
				}
			}
			
			$val=array('bkd','verifikasi','pak','cuti','lembur');
			for ($i=0; $i<sizeof($val); $i++){
				if (empty($data[$val[$i]])) {
				} else {      
					$list=$data[$val[$i]];
					for ($j=0; $j<sizeof($list); $j++){
						$jumlah++;
					}
				}
			}
			$this->mrTemplate->AddVar('informasi_jumlah', 'JUMLAH', $jumlah);
			$this->mrTemplate->parseTemplate('informasi_jumlah', 'a');
		}elseif ($tipe=='notifikasi'){
			$this->mrTemplate->AddVar('tipe', 'TIPE', strtoupper($tipe));
			$val=array('pensiun','naik_pangkat','naik_gaji');
			for ($i=0; $i<sizeof($val); $i++){
				if (empty($data[$val[$i]])) {
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'YES');
				} else {      
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'NO');
					$list=$data[$val[$i]];
					
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i].'_list', 'TANGGAL_AWAL', date('Y-m-d'));
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i].'_list', 'TANGGAL_AKHIR', date('Y-m-d',strtotime('180 days')));
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i].'_list', 'JUMLAH_'.strtoupper($val[$i]), sizeof($list));
					$this->mrTemplate->parseTemplate($tipe.'_pegawai_'.$val[$i].'_list', 'a');
				}
			}
			
			$val=array('bkd','verifikasi','pak','cuti','lembur');
			for ($i=0; $i<sizeof($val); $i++){
				if (empty($data[$val[$i]])) {
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'YES');
				} else {      
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'NO');
					$list=$data[$val[$i]];
					for ($j=0; $j<sizeof($list); $j++){
						$list[$j]['tanggal']=$this->Obj->IndonesianDate($list[$j]['tanggal'],'YYYY-MM-DD');
						$this->mrTemplate->AddVars($tipe.'_pegawai_'.$val[$i].'_list', $list[$j], '');
						$this->mrTemplate->parseTemplate($tipe.'_pegawai_'.$val[$i].'_list', 'a');
					}
				}
			}
		}else{
			$this->mrTemplate->AddVar('tipe', 'TIPE', strtoupper($tipe));
			$val=array('pensiun','naik_pangkat','naik_gaji');
			for ($i=0; $i<sizeof($val); $i++){
				if (empty($data[$val[$i]])) {
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'YES');
				} else {      
					$this->mrTemplate->AddVar($tipe.'_pegawai_'.$val[$i], 'DATA_EMPTY', 'NO');
					$list=$data[$val[$i]];
					for ($j=0; $j<sizeof($list); $j++){
						$list[$j]['tanggal']=$this->Obj->IndonesianDate($list[$j]['tanggal'],'YYYY-MM-DD');
						$this->mrTemplate->AddVars($tipe.'_pegawai_'.$val[$i].'_list', $list[$j], '');
						$this->mrTemplate->parseTemplate($tipe.'_pegawai_'.$val[$i].'_list', 'a');
					}
				}
			}
		}
	}
}
?>
