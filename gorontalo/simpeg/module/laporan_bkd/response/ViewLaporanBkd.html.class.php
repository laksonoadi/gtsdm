<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bkd/business/laporan.class.php';
   
class ViewLaporanBkd extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_bkd/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_bkd.html');
   }
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
		if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      $this->Obj=new Laporan;
  		if(isset($_POST['unit_kerja'])) {
  			$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  			$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  			$this->unit_kerja = 'all';
  		}
			
		if(isset($_POST['pangkat_golongan'])) {
  			$this->pangkat_golongan = $_POST['pangkat_golongan'];
  		} elseif(isset($_GET['pangkat_golongan'])) {
  			$this->pangkat_golongan = Dispatcher::Instance()->Decrypt($_GET['pangkat_golongan']);
  		} else {
  			$this->pangkat_golongan = 'all';
  		}
  				
		if(isset($_POST['fungsional'])) {
  			$this->fungsional = $_POST['fungsional'];
  		} elseif(isset($_GET['fungsional'])) {
  			$this->fungsional = Dispatcher::Instance()->Decrypt($_GET['fungsional']);
  		} else {
  			$this->fungsional = 'all';
  		}
  				
		if(isset($_POST['pendidikan'])) {
  			$this->pendidikan = $_POST['pendidikan'];
  		} elseif(isset($_GET['pendidikan'])) {
  			$this->pendidikan = Dispatcher::Instance()->Decrypt($_GET['pendidikan']);
  		} else {
  			$this->pendidikan = 'all';
  		}
  				
  		if ($_SESSION['unit_id']==1) {
			$true='true';
		}else{
			if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		}
		  		
  		$this->ComboUnitKerja	= $this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja	= $this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, $true, ''), Messenger::CurrentRequest);
  		
  		$this->ComboPangkatGolongan		= $this->Obj->GetComboPangkatGolongan();
  		$this->label_pangkat_golongan	= $this->GetLabelFromCombo($this->ComboPangkatGolongan,$this->pangkat_golongan);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pangkat_golongan', 
        array('pangkat_golongan', $this->ComboPangkatGolongan, $this->pangkat_golongan, 'true', ''), Messenger::CurrentRequest);
  		
		$this->ComboFungsional	= $this->Obj->GetComboFungsional();
  		$this->label_fungsional	= $this->GetLabelFromCombo($this->ComboFungsional,$this->fungsional);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'fungsional', 
        array('fungsional', $this->ComboFungsional, $this->fungsional, 'true', ''), Messenger::CurrentRequest);
		
		$this->ComboPendidikan	= $this->Obj->GetComboPendidikan();
  		$this->label_pendidikan	= $this->GetLabelFromCombo($this->ComboPendidikan,$this->pendidikan);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pendidikan', 
        array('pendidikan', $this->ComboPendidikan, $this->pendidikan, 'true', ''), Messenger::CurrentRequest);
		
		
// CREATE PAGING START ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$totalData	= $this->Obj->GetCountDataBkd($this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan);
		
  		$itemViewed = 15;
  		$currPage 	= 1;
  		$startRec 	= 0;
  		if(isset($_GET['page'])) {
  			$currPage	= (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec	= ($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai	= $this->Obj->GetDataBkd($startRec, $itemViewed, $this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan);
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja		=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&pangkat_golongan	=' . Dispatcher::Instance()->Encrypt($this->pangkat_golongan)
        .'&fungsional		=' . Dispatcher::Instance()->Encrypt($this->fungsional)
        .'&peendidikan		=' . Dispatcher::Instance()->Encrypt($this->pendidikan)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));

  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
// CREATE PAGING END ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      
	  
		$msg 			= Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan 	= $msg[0][1];
  		$this->css 		= $msg[0][2];
  
  		$return['dataPegawai']	= $dataPegawai;
  		$return['start'] 		= $startRec+1;
        
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
        
		$this->mrTemplate->AddVar('content', 'JUDUL_UNIT_KERJA', $this->label_unit_kerja);
		$this->mrTemplate->AddVar('content', 'JUDUL_PANGKAT_GOLONGAN', $this->label_pangkat_golongan);
		$this->mrTemplate->AddVar('content', 'JUDUL_FUNGSIONAL', $this->label_fungsional);
		$this->mrTemplate->AddVar('content', 'JUDUL_PENDIDIKAN', $this->label_pendidikan);

		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_bkd', 'laporanBkd', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('laporan_bkd', 'RekapitulasiBkd', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_bkd', 'laporanBkd', 'view', 'xls')
			.'&unit_kerja		=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
			.'&pangkat_golongan	=' . Dispatcher::Instance()->Encrypt($this->pangkat_golongan)
			.'&fungsional		=' . Dispatcher::Instance()->Encrypt($this->fungsional)
			.'&pendidikan		=' . Dispatcher::Instance()->Encrypt($this->pendidikan)
			.'&cari=' . Dispatcher::Instance()->Encrypt(1));
      
      
		if (empty($data['dataPegawai'])) {
			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
		} else {
			$decPage		= Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage		= Dispatcher::Instance()->Encrypt($decPage);
			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
			$dataPegawai 	= $data['dataPegawai'];
			$total = 0;
			for ($i=0; $i<sizeof($dataPegawai); $i++) {
				$no = $i+$data['start'];
				$dataPegawai[$i]['no'] = $no;
				if ($no % 2 != 0) {
					$dataPegawai[$i]['class_name'] = 'table-common-even';
				}else{
					$dataPegawai[$i]['class_name'] = '';
				}
				$dataPegawai[$i]['tanggal_lahir']	= $this->date2string($dataPegawai[$i]['tanggal_lahir']);
				$dataPegawai[$i]['golongan_tmt']	= $this->date2string($dataPegawai[$i]['golongan_tmt']);
				$dataPegawai[$i]['jabatan_tmt']		= $this->date2string($dataPegawai[$i]['jabatan_tmt']);

				$tanggal_pengajuan	= $dataPegawai[$i]['tanggal_pengajuan'];
				if($tanggal_pengajuan == NULL) $dataPegawai[$i]['tanggal_pengajuan']	= "-";
				else $dataPegawai[$i]['tanggal_pengajuan']	= $this->date2string($dataPegawai[$i]['tanggal_pengajuan']);

				$tanggal_penilaian	= $dataPegawai[$i]['tanggal_penilaian'];
				if($tanggal_penilaian == NULL) $dataPegawai[$i]['tanggal_penilaian']	= "-";
				else $dataPegawai[$i]['tanggal_penilaian']	= $this->date2string($dataPegawai[$i]['tanggal_penilaian']);

				// $dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('laporan_bkd','LaporanBkdDetail', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				$dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('laporan_bkd','LaporanBkdDetail', 'view', 'html').'&id='.$dataPegawai[$i]['id'];
				$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
				$this->mrTemplate->parseTemplate('table_item', 'a');	 
			}
		}
   }

	function date2string($date) {
		$bln = array(
			1  => '01',
			2  => '02',
			3  => '03',
			4  => '04',
			5  => '05',
			6  => '06',
			7  => '07',
			8  => '08',
			9  => '09',
			10 => '10',
			11 => '11',
			12 => '12'					
		);
    $arrtgl = explode('-',$date);
	return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];  
  }
   
   
   
   
   
   
   
}
   

?>