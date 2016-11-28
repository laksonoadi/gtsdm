<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pegawai_ultah/business/laporan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewLaporanPegawai extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_pegawai_ultah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_laporan_daftar.html');
	}
   
	// function GetLabelFromCombo($ArrData,$Nilai){
	// 	for ($i=0; $i<sizeof($ArrData); $i++){
	// 		if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
	// 	}
	// 	return '--Semua--';
	// }
   
	function ProcessRequest(){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$this->Obj=new Laporan;
   	$this->ObjSatker = new SatuanKerja();
		
		// $this->Obj->getVariabelGlobal();
		$this->judul=$this->Obj->judul;
		$this->tanggal=$this->Obj->IndonesianDate(date('Y-m-d'),"YYYY-MM-DD");
		
  		if(isset($_POST['cari'])) {
			// for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
			// 	$this->Obj->filter[$this->Obj->varFilter[$i]] = strval($_POST[$this->Obj->varFilter[$i]]);
			// }
			
			$this->Obj->berdasarkan = strval($_POST['berdasarkan']);
			$this->Obj->urutan = strval($_POST['urutan']);
			
  		}elseif(isset($_GET['cari'])) {
 		// 	for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
			// 	$this->Obj->filter[$this->Obj->varFilter[$i]] = Dispatcher::Instance()->Decrypt(strval($_GET[$this->Obj->varFilter[$i]]));
			// }
			
			$this->Obj->berdasarkan = strval($_GET['berdasarkan']);
			$this->Obj->urutan = strval($_GET['urutan']);
			
  		}else {
  	// 		for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
			// 	$this->Obj->filter[$this->Obj->varFilter[$i]] = 'all';
			// }
			$this->Obj->berdasarkan = 'bulan_ini';
			$this->Obj->urutan = 'ASC';
  		}

  		if($this->Obj->berdasarkan == 'tanggal'){
  			$this->Obj->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
  			$this->Obj->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];
  		}else{
  			$this->Obj->awal = '';
  			$this->Obj->akhir = '';
  		}
		
		$this->Obj->getVariabelGlobal();
		
		$field=array_keys($this->Obj->field);
		$this->lebarKolom=20;
		// 
		$filterfieldget = '&berdasarkan='.$this->Obj->berdasarkan.'&urutan='.$this->Obj->urutan.'&awal='.$this->Obj->awal.'&akhir='.$this->Obj->akhir;
		
		// for ($i=0; $i<sizeof($field)-1; $i++){
		// 	$this->field[$i]['nama']=$field[$i];
		// 	$this->field[$i]['caption']=$this->Obj->caption[$field[$i]][0];
			
		// 	if ((strval($_POST[$field[$i]])=='on')&&(isset($_POST['cari']))) {
		// 		$this->showKolom['v'.$field[$i]]='';
		// 		$this->field[$i]['checked']='checked=true';
		// 		$this->lebarKolom += 50;
		// 	}elseif ((strval($_GET[$field[$i]])=='on')&&(isset($_GET['cari']))) {
		// 		$this->showKolom['v'.$field[$i]]='';
		// 		$this->field[$i]['checked']='checked=true';
		// 		$this->lebarKolom += 50;
		// 	}elseif(!isset($_GET['cari']) && !isset($_POST['cari'])){
		// 		$this->showKolom['v'.$field[$i]]='';
		// 		$this->field[$i]['checked']='checked=true';
		// 		$this->lebarKolom += 50;
		// 	}else{
		// 		$this->showKolom['v'.$field[$i]]='none';
		// 		$this->field[$i]['checked']='';
		// 	}
			
		// 	if ($this->field[$i]['checked']!=''){
		// 		$filterfieldget .='&'.$field[$i].'=on';
		// 	}
		// }
		
      
		// $this->ComboJabatanFungsional=$this->Obj->GetComboVariabel('jabatan_fungsional');
  // 		$this->label_jabatan_fungsional=$this->GetLabelFromCombo($this->ComboJabatanFungsional,$this->Obj->filter['jabatan_fungsional']);
		// $this->label['jenisfungsional']=$this->label_jabatan_fungsional;
  // 		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabatan_fungsional', array('jabatan_fungsional', $this->ComboJabatanFungsional, $this->Obj->filter['jabatan_fungsional'], 'true', ''), Messenger::CurrentRequest);
		
		// // $this->ComboUnit=$this->Obj->GetComboVariabel('unit');
		// $this->ComboUnit=$this->ObjSatker->GetComboSatuanKerja();
		// $this->label['unit']=$this->GetLabelFromCombo($this->ComboUnit,$this->Obj->filter['unit']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit', array('unit', $this->ComboUnit, $this->Obj->filter['unit'], 'true', ' style="width:200px"'), Messenger::CurrentRequest);
		
		// $this->ComboStatus=$this->Obj->GetComboVariabel2('status');
		// $this->label['status']=$this->GetLabelFromCombo($this->ComboStatus,$this->Obj->filter['status']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $this->ComboStatus, $this->Obj->filter['status'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboJenis=$this->Obj->GetComboVariabel2('jenis');
		// $this->label['jenis']=$this->GetLabelFromCombo($this->ComboJenis,$this->Obj->filter['jenis']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $this->ComboJenis, $this->Obj->filter['jenis'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboGolongan=$this->Obj->GetComboVariabel2('golongan');
		// $this->label['golongan']=$this->GetLabelFromCombo($this->ComboGolongan,$this->Obj->filter['golongan']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan', array('golongan', $this->ComboGolongan, $this->Obj->filter['golongan'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboFungsional=$this->Obj->GetComboVariabel2('fungsional');
		// $this->label['fungsional']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['fungsional']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'fungsional', array('fungsional', $this->ComboFungsional, $this->Obj->filter['fungsional'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboPendidikan=$this->Obj->GetComboVariabel2('pendidikan');
		// $this->label['pendidikan']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['pendidikan']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pendidikan', array('pendidikan', $this->ComboPendidikan, $this->Obj->filter['pendidikan'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboAgama=$this->Obj->GetComboVariabel2('agama');
		// $this->label['agama']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['agama']);
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'agama', array('agama', $this->ComboAgama, $this->Obj->filter['agama'], 'true', ''), Messenger::CurrentRequest);
  		
  // 		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  // 		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  // 		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
  //       array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, $true, ''), Messenger::CurrentRequest);
  		
  // 		$this->ComboPangkatGolongan=$this->Obj->GetComboPangkatGolongan();
  // 		$this->label_pangkat_golongan=$this->GetLabelFromCombo($this->ComboPangkatGolongan,$this->pangkat_golongan);
  // 		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pangkat_golongan', 
  //       array('pangkat_golongan', $this->ComboPangkatGolongan, $this->pangkat_golongan, 'true', ''), Messenger::CurrentRequest);
  		
  // 		$this->ComboJenisKelamin=$this->Obj->GetComboJenisKelamin();
  // 		$this->label_jenis_kelamin=$this->GetLabelFromCombo($this->ComboJenisKelamin,$this->jenis_kelamin);
  // 		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_kelamin', 
  //       array('jenis_kelamin', $this->ComboJenisKelamin, $this->jenis_kelamin, 'true', ''), Messenger::CurrentRequest);
  		
  // 		$this->ComboJenisPegawai=$this->Obj->GetComboJenisPegawai();
  // 		$this->label_jenis_pegawai=$this->GetLabelFromCombo($this->ComboJenisPegawai,$this->jenis_pegawai);
  // 		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_pegawai', 
  //       array('jenis_pegawai', $this->ComboJenisPegawai, $this->jenis_pegawai, 'true', ''), Messenger::CurrentRequest);
		
		//create paging 
		$this->filterget='&cari=' . Dispatcher::Instance()->Encrypt(1);
		// for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
		// 	$this->filterget .='&'.$this->Obj->varFilter[$i].'='.Dispatcher::Instance()->Encrypt($this->Obj->filter[$this->Obj->varFilter[$i]]);
		// }
		
		$this->filterget .= $filterfieldget;
		
		$totalData = $this->Obj->GetCountDaftarPegawai();
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDaftarPegawai($startRec, $itemViewed);
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .$this->filterget);

  		Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'awal', array($this->awal, $this->tahun_awal, $this->tahun_akhir, false, '', 'awal'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'akhir', array($this->akhir, $this->tahun_awal, $this->tahun_akhir, false, '', 'akhir'), Messenger::CurrentRequest);
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here
		
		$return['dataPegawai']= $dataPegawai;
        $return['startRec']=$startRec;
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
		$this->mrTemplate->AddVar('content', 'JUDUL_JENIS_KELAMIN', $this->label_jenis_kelamin);
		$this->mrTemplate->AddVar('content', 'JUDUL_JENIS_PEGAWAI', $this->label_jenis_pegawai);
		
		// $this->mrTemplate->AddVars('content', $this->showKolom, '');
		// $this->mrTemplate->AddVar('content', 'LEBAR_KOLOM',$this->lebarKolom);
		$this->mrTemplate->AddVar('content', 'SELECTED_'.$this->Obj->urutan,'selected="selected"');
		// $this->mrTemplate->AddVar('content', 'SELECTED_'.strtoupper($this->Obj->berdasarkan),'SELECTED');
		
		// for ($i=0; $i<sizeof($this->field); $i++) {
  //   		$this->mrTemplate->AddVars('field_item', $this->field[$i], 'FIELD_');
  //   		$this->mrTemplate->parseTemplate('field_item', 'a');	 
  //   	}
		  
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_pegawai_ultah', 'laporanPegawai', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_pegawai_ultah', 'laporanDaftar', 'view', 'xls')
        .$this->filterget);
      
      
        if (empty($data['dataPegawai'])) {
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
    	} else {
    			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
    			$encPage = Dispatcher::Instance()->Encrypt($decPage);
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
    			$dataPegawai = $data['dataPegawai'];
    			$total=0;
    			for ($i=0; $i<sizeof($dataPegawai); $i++) {
    				$no = $i+1+$data['startRec'];
    				$dataPegawai[$i]['no'] = $no;
    				if ($no % 2 != 0) {
						$dataPegawai[$i]['class_name'] = 'table-common-even';
					}else{
        				$dataPegawai[$i]['class_name'] = '';
        			}
					
					$this->mrTemplate->AddVars('table_item', $this->showKolom, '');
    				$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
    				$this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
		}      
   }
}
   

?>