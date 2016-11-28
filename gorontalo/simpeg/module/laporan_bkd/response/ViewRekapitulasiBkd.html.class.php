<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bkd/business/laporan.class.php';
   
class ViewRekapitulasiBkd extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_bkd/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_rekapitulasi_bkd.html');
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
		

// CREATE LIST START ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$dataFakultas	= $this->Obj->GetDataFakultas($this->unit_kerja);

		for ($i=0; $i<sizeof($dataFakultas); $i++) {
			$dataPegawai[$dataFakultas[$i][idFak]]	= $this->Obj->GetDataRekapitulasiBkd($this->unit_kerja, $this->pangkat_golongan, $this->fungsional, $this->pendidikan, $dataFakultas[$i][nameFak]);
			$paramQuery		= $dataPegawai[$dataFakultas[$i][idFak]];
			$pegId			= $paramQuery[0][id];
			
			// get sks ganjil '''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
			$getSksPendGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPendGanjil($pegId);
			$getSksPenlGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenlGanjil($pegId);
			$getSksPengGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPengGanjil($pegId);
			$getSksPenuGanjil[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenuGanjil($pegId);

			// get sks genap ''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
			$getSksPendGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPendGenap($pegId);
			$getSksPenlGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenlGenap($pegId);
			$getSksPengGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPengGenap($pegId);
			$getSksPenuGenap[$dataFakultas[$i][idFak]]	= $this->Obj->getSksPenuGenap($pegId);

			$getSksProf[$dataFakultas[$i][idFak]]	= $this->Obj->getSksProf($pegId);

		}
		// print_r($getSksPenlGanjil);
		// exit;

// CREATE LIST END ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$msg 			= Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan 	= $msg[0][1];
  		$this->css 		= $msg[0][2];
  
  		$return['dataFakultas']		= $dataFakultas;
		$return['dataPegawai']		= $dataPegawai;
		
		$return['getSksPendGanjil']	= $getSksPendGanjil;
		$return['getSksPenlGanjil']	= $getSksPenlGanjil;
		$return['getSksPengGanjil']	= $getSksPengGanjil;
		$return['getSksPenuGanjil']	= $getSksPenuGanjil;

		$return['getSksPendGenap']	= $getSksPendGenap;
		$return['getSksPenlGenap']	= $getSksPenlGenap;
		$return['getSksPengGenap']	= $getSksPengGenap;
		$return['getSksPenuGenap']	= $getSksPenuGenap;
		
		$return['getSksProf']		= $getSksProf;

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

		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_bkd', 'rekapitulasiBkd', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_DETAIL_REKAP', Dispatcher::Instance()->GetUrl('laporan_bkd', 'laporanBkd', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_bkd', 'rekapitulasiBkd', 'view', 'xls')
			.'&unit_kerja		=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
			.'&pangkat_golongan	=' . Dispatcher::Instance()->Encrypt($this->pangkat_golongan)
			.'&fungsional		=' . Dispatcher::Instance()->Encrypt($this->fungsional)
			.'&pendidikan		=' . Dispatcher::Instance()->Encrypt($this->pendidikan)
			.'&cari=' . Dispatcher::Instance()->Encrypt(1));
      
      
		if (empty($data['dataFakultas'])) {
			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
		} else {
			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
			$dataFakultas 		= $data['dataFakultas'];
			$dataPegawai 		= $data['dataPegawai'];

			$getSksPendGanjil 	= $data['getSksPendGanjil'];
			$getSksPenlGanjil 	= $data['getSksPenlGanjil'];
			$getSksPengGanjil 	= $data['getSksPengGanjil'];
			$getSksPenuGanjil 	= $data['getSksPenuGanjil'];

			$getSksPendGenap 	= $data['getSksPendGenap'];
			$getSksPenlGenap 	= $data['getSksPenlGenap'];
			$getSksPengGenap 	= $data['getSksPengGenap'];
			$getSksPenuGenap 	= $data['getSksPenuGenap'];
			
			$getSksProf		 	= $data['getSksProf'];

			
			for ($i=0; $i<sizeof($dataFakultas); $i++) {
				$dataPegawaiTemp 		= $dataPegawai[$dataFakultas[$i][idFak]];
				
				$getSksPendGanjilTemp	= $getSksPendGanjil[$dataFakultas[$i][idFak]];
				$getSksPenlGanjilTemp	= $getSksPenlGanjil[$dataFakultas[$i][idFak]];
				$getSksPengGanjilTemp	= $getSksPengGanjil[$dataFakultas[$i][idFak]];
				$getSksPenuGanjilTemp	= $getSksPenuGanjil[$dataFakultas[$i][idFak]];

				$getSksPendGenapTemp	= $getSksPendGenap[$dataFakultas[$i][idFak]];
				$getSksPenlGenapTemp	= $getSksPenlGenap[$dataFakultas[$i][idFak]];
				$getSksPengGenapTemp	= $getSksPengGenap[$dataFakultas[$i][idFak]];
				$getSksPenuGenapTemp	= $getSksPenuGenap[$dataFakultas[$i][idFak]];
				
				$getSksProfTemp			= $getSksProf[$dataFakultas[$i][idFak]];
				
					if (empty($dataPegawaiTemp)) {
						$this->mrTemplate->AddVar('list_peg', 'EMPTY', 'YES');
					} else {
						$this->mrTemplate->AddVar('list_peg', 'EMPTY', 'NO');

						$this->mrTemplate->clearTemplate('data_item', 'a');
						$start	= 1;
						for ($ii=0; $ii<sizeof($dataPegawaiTemp); $ii++) {
							$no = $ii + $start;
							if ($no % 2 != 0) {
								$dataPegawaiTemp[$ii]['class_name'] = 'table-common-even';
							}else{
								$dataPegawaiTemp[$ii]['class_name'] = '';
							}
							
							$this->mrTemplate->AddVar('data_item','NO',$no);

							$this->mrTemplate->AddVar('data_item','NAMA',$dataPegawaiTemp[$ii]['nama']);
							$this->mrTemplate->AddVar('data_item','NO_SERTIFIKASI',$dataPegawaiTemp[$ii]['no_sertifikasi']);
							$this->mrTemplate->AddVar('data_item','STATUS',$dataPegawaiTemp[$ii]['bkdJenis']);
							$this->mrTemplate->AddVar('data_item','SEMESTER',$dataPegawaiTemp[$ii]['semester']);
							$this->mrTemplate->AddVar('data_item','KESIMPULAN',$dataPegawaiTemp[$ii]['kesimpulan']);


							$this->mrTemplate->AddVar('data_item', 'SUM_PEND_GANJIL', ($getSksPendGanjilTemp[$ii][sum_pend_ganjil] == '') ? '0' : $getSksPendGanjilTemp[$ii][sum_pend_ganjil]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENL_GANJIL', ($getSksPenlGanjilTemp[$ii][sum_penl_ganjil] == '') ? '0' : $getSksPenlGanjilTemp[$ii][sum_penl_ganjil]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENG_GANJIL', ($getSksPengGanjilTemp[$ii][sum_peng_ganjil] == '') ? '0' : $getSksPengGanjilTemp[$ii][sum_peng_ganjil]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENU_GANJIL', ($getSksPenuGanjilTemp[$ii][sum_penu_ganjil] == '') ? '0' : $getSksPenuGanjilTemp[$ii][sum_penu_ganjil]);

							$this->mrTemplate->AddVar('data_item', 'SUM_PEND_GENAP', ($getSksPendGenapTemp[$ii][sum_pend_genap] == '') ? '0' : $getSksPendGenapTemp[$ii][sum_pend_genap]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENL_GENAP', ($getSksPenlGenapTemp[$ii][sum_penl_genap] == '') ? '0' : $getSksPenlGenapTemp[$ii][sum_penl_genap]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENG_GENAP', ($getSksPengGenapTemp[$ii][sum_peng_genap] == '') ? '0' : $getSksPengGenapTemp[$ii][sum_peng_genap]);
							$this->mrTemplate->AddVar('data_item', 'SUM_PENU_GENAP', ($getSksPenuGenapTemp[$ii][sum_penu_genap] == '') ? '0' : $getSksPenuGenapTemp[$ii][sum_penu_genap]);

							$this->mrTemplate->AddVar('data_item', 'SUM_PROF', ($getSksProfTemp[$ii][sum_prof] == '') ? '0' : $getSksProfTemp[$ii][sum_prof]);

							
							$this->mrTemplate->parseTemplate('data_item', 'a');
						}

					}

				$this->mrTemplate->AddVars('table_item', $dataFakultas[$i], '');
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