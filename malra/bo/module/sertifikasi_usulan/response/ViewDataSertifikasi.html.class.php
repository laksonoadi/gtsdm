<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_sertifikasi/business/sertifikasi.class.php';
   
class ViewDataSertifikasi extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/sertifikasi_usulan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_data_sertifikasi.html');
	}
   
	function ProcessRequest(){
		$Obj = new Sertifikasi();
		
		if ($_GET['srtfkId']==''){
			$this->srtfkPeriodeAwal=date("Y-")."01-01";
			$this->srtfkPeriodeAkhir=date("Y-")."12-31";
			$this->srtfkTahun=date("Y");
		} else {
			$srtfk = $Obj->GetUsulanSertifikasiById($_GET['srtfkId']);
			
			$this->srtfkId=$srtfk[0]['srtfkId'];
			$this->srtfkPeriodeAwal=$srtfk[0]['srtfkPeriodeAwal'];
			$this->srtfkPeriodeAkhir=$srtfk[0]['srtfkPeriodeAkhir'];
			$this->srtfkTahun=$srtfk[0]['srtfkTahun'];
			
			$this->srtfkPeserta = $Obj->GetListPesertaSertifikasiById($_GET['srtfkId']);
			
		}
      
		$y1=date('Y')+4;
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAwal', array($this->srtfkPeriodeAwal,'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAkhir', array($this->srtfkPeriodeAkhir,'2003',$y1,'',''), Messenger::CurrentRequest);
      

		$msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];

  		return $return;
	}
   
	function ParseTemplate($data = NULL){
		$dataPegawai = $data['dataPegawai'];
		$dat = $data['input'];
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      
		$buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($buttonlang=='eng'){
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['srtfkId']) ? 'Update' : 'Add');
		}else{
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['srtfkId']) ? 'Ubah' : 'Tambah');  
		}
		
		$this->mrTemplate->AddVar('content', 'SRTFKTAHUN', $this->srtfkTahun);
		$this->mrTemplate->AddVar('content', 'SRTFKID', $this->srtfkId);
      
		if(isset($_GET['srtfkId'])){
			$op="edit";
			if ($buttonlang=='eng'){
				$oo=" Cancel ";
			}else{
				$oo=" Batal ";
			}
		}else{
			$op="add";
			$oo=" Reset ";
		}
		$this->mrTemplate->AddVar('content', 'OP', $op);
		$this->mrTemplate->AddVar('content', 'BUTTON', $oo);
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'historyDataSertifikasi', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'inputDataSertifikasi', 'do', 'html')); 
		$this->mrTemplate->AddVar('content', 'URL_POPUP_PEGAWAI', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'popupPegawai', 'view', 'html'));
		
		$cariData = $this->srtfkPeserta;
		if(empty($cariData)) {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "YES");
		} else {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "NO");
			for ($i=0; $i<sizeof($cariData); $i++) {
				//print_r($cariData[$i]);
			    $this->mrTemplate->AddVars('data_peserta_sertifikasi', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_peserta_sertifikasi', 'a');	 
			}
		}
	  
    }
}
   

?>