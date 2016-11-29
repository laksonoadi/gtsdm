<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_sertifikasi/business/sertifikasi.class.php';
   
class ViewDetailSertifikasi extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/sertifikasi_usulan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_detail_sertifikasi.html');
	}
   
	function ProcessRequest(){
		$Obj = new Sertifikasi();
		$_POST = $_POST->AsArray();
		if ($_POST['srtfkId']!=''){
			$srtfkId=$_POST['srtfkId'];
			$srtfkdetHasilAkhir=$_POST['srtfkdetHasilAkhir'];
		}else if ($_GET['srtfkId']!=''){
			$srtfkId=$_GET['srtfkId'];
			$srtfkdetHasilAkhir='ALL';
		}else{
			$this->srtfkPeriodeAwal=date("Y-")."01-01";
			$this->srtfkPeriodeAkhir=date("Y-")."12-31";
			$this->srtfkTahun=date("Y");
		} 
		
		if ($srtfkId!=''){
			$srtfk = $Obj->GetUsulanSertifikasiById($srtfkId);
			$this->data['srtfkId']=$srtfk[0]['srtfkId'];
			$this->data['srtfkPeriodeAwal']=$srtfk[0]['srtfkPeriodeAwal'];
			$this->data['srtfkPeriodeAwal_label']=$Obj->IndonesianDate($srtfk[0]['srtfkPeriodeAwal'],'YYYY-MM-DD');
			$this->data['srtfkPeriodeAkhir']=$srtfk[0]['srtfkPeriodeAkhir'];
			$this->data['srtfkPeriodeAkhir_label']=$Obj->IndonesianDate($srtfk[0]['srtfkPeriodeAkhir'],'YYYY-MM-DD');
			$this->data['srtfkTahun']=$srtfk[0]['srtfkTahun'];
			$this->data['srtfkdetHasilAkhir']=$srtfkdetHasilAkhir;
			$this->data[str_replace(' ','_',$srtfkdetHasilAkhir).'_SELECTED']='SELECTED';
			$this->datalist['srtfkPeserta'] = $Obj->GetListPesertaSertifikasiByIdDetail($srtfkId,$srtfkdetHasilAkhir);
		}


  		return $return;
	}
   
	function ParseTemplate($data = NULL){
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      
		$buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
		$this->mrTemplate->AddVars('content', $this->data,'');
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'historyDataSertifikasi', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'detailSertifikasi', 'view', 'html')); 
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'detailSertifikasi', 'view', 'xls').'&source=usulan&srtfkId='.$this->data['srtfkId'].'&srtfkdetHasilAkhir='.$this->data['srtfkdetHasilAkhir']);
		
		$cariData = $this->datalist['srtfkPeserta'];
		if(empty($cariData)) {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "YES");
		} else {
			$this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "NO");
			for ($i=0; $i<sizeof($cariData); $i++) {
				$cariData[$i]['number']=$i+1;
				$cariData[$i]['url_detail']=Dispatcher::Instance()->GetUrl('sertifikasi_usulan', 'detailPesertaSertifikasi', 'view', 'html').'&srtfkdetTahun='.$cariData[$i]['srtfkdetTahun'].'&srtfkdetPegId='.$cariData[$i]['srtfkdetPegId'].'&srtfkId='.$cariData[$i]['srtfkdetSrtfkId'];
			    $this->mrTemplate->AddVars('data_peserta_sertifikasi', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_peserta_sertifikasi', 'a');	 
			}
		}
	  
    }
}
   

?>