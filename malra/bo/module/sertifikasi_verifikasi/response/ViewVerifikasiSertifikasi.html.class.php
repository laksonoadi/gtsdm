<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_sertifikasi/business/sertifikasi.class.php';
   
class ViewVerifikasiSertifikasi extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/sertifikasi_verifikasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_verifikasi_sertifikasi.html');
	}
   
	function ProcessRequest(){
		$Obj = new Sertifikasi();
		
		$this->POST = $_POST->AsArray();
		if (isset($this->POST['btncari'])){
			$this->semua = $this->POST['semua']=='on'?'checked':'';
			$this->keyword = $this->POST['keyword'];
			$_GET['srtfkId']=$this->POST['srtfkId_cari'];
		}else{
			$this->keyword = 'XXXXXX';
		}
		
		
		
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
			
			$this->srtfkPeserta = $Obj->GetListPesertaSertifikasiById($_GET['srtfkId'],$this->keyword);
			
		}
      
		$y1=date('Y')+4;
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAwal', array($this->srtfkPeriodeAwal,'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'srtfkPeriodeAkhir', array($this->srtfkPeriodeAkhir,'2003',$y1,'',''), Messenger::CurrentRequest);
		
		$combo_tahun = $Obj->GetComboTahunUsulan();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'srtfkId_cari', array('srtfkId_cari', $combo_tahun, $this->POST['srtfkId_cari'], '', ''), Messenger::CurrentRequest);
      

		$msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];

  		return $return;
	}
   
	function ParseTemplate($data = NULL){
		$dataPegawai = $data['dataPegawai'];
		$dat = $data['input'];
		if(!empty($this->Pesan)){
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
		$this->mrTemplate->AddVar('content', 'SRTFKPERIODEAWAL', $this->srtfkPeriodeAwal);
		$this->mrTemplate->AddVar('content', 'SRTFKPERIODEAKHIR', $this->srtfkPeriodeAkhir);
		$this->mrTemplate->AddVar('content', 'SEMUA', $this->semua);
		$this->mrTemplate->AddVar('content', 'KEYWORD', $this->keyword=='XXXXXX'?'':$this->keyword);
		$this->mrTemplate->AddVar('content', 'KEYWORD_DISABLED', $this->semua=='checked'?'disabled':'');
      
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
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('sertifikasi_verifikasi', 'historyDataSertifikasi', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('sertifikasi_verifikasi', 'verifikasiSertifikasi', 'view', 'html').'&srtfkId='.$_GET['srtfkId']);
		$this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('sertifikasi_verifikasi', 'inputDataSertifikasi', 'do', 'html')); 
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'detailSertifikasi', 'view', 'xls').'&source=verifikasi&srtfkId='.($_GET['srtfkId']==''?'ALL':$_GET['srtfkId']).'&srtfkdetHasilAkhir=ALL');
		$this->mrTemplate->AddVar('content', 'URL_EXCEL_ALL', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'detailSertifikasi', 'view', 'xls').'&source=verifikasi&srtfkId=ALL&srtfkdetHasilAkhir=ALL');
		
		$cariData = $this->srtfkPeserta;
		if(empty($cariData)) {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "YES");
		} else {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "NO");
			for ($i=0; $i<sizeof($cariData); $i++) {
				$cariData[$i]['number']=$i+1;
				$cariData[$i]['srtfkdetVerifikasi_status']=$cariData[$i]['srtfkdetIsVerifikasi']==1?'lamp-green.gif':'lamp-red.gif';
			    $this->mrTemplate->AddVars('data_peserta_sertifikasi', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_peserta_sertifikasi', 'a');	 
			}
			$this->mrTemplate->AddVar('header', 'FIRST_NUMBER', '1');
			$this->mrTemplate->AddVar('header', 'LAST_NUMBER', $i);
		}
	  
    }
}
   

?>