<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_bkd/business/MutasiBkd.class.php';

class ViewMutasiBkd extends HtmlResponse
{
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/mutasi_bkd/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_mutasi_bkd.html');
  }
      
  function ProcessRequest() 
  {
   	$pg				= new MutasiBkd();
   	$msg 			= Messenger::Instance()->Receive(__FILE__);
   	$this->Data 	= $msg[0][0];
	$this->Pesan	= $msg[0][1];
	$this->css 		= $msg[0][2];
      
	$id				= Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
    $dataId 		= Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId	= $id;
    $this->dataId	= $dataId;
	
    if(isset($_GET['id'])){
		$listBkd		= $pg->GetListBkd($id);
		$dataTampil		= $pg->GetPegInBkd($id);
		$dataPegFull	= $pg->GetPegawaiFull($id);
		$dataPegawai	= $pg->GetDataDetail($id);
		$dataSatker		= $pg->GetListMutasiSatuanKerja($id);
	}

    if(isset($_GET['dataId'])){
		$detailDosen	= $pg->GetDataDetailBkdDosen($id,$dataId);
		$dataPendidikan	= $pg->GetDataPendidikan($dataId);
		$dataPenelitian	= $pg->GetDataPenelitian($dataId);
		$dataPengabdian	= $pg->GetDataPengabdian($dataId);
		$dataPenunjang	= $pg->GetDataPenunjang($dataId);
		$dataProfesor	= $pg->GetDataProfesor($dataId);
	}

// echo $dataPendidikan[0]['nmKeg'];
// exit;

	$list_jenis=array(
				array('id'=>'DS','name'=>'Dosen Biasa'),
				array('id'=>'PR','name'=>'Profesor'),
				array('id'=>'DT','name'=>'Dosen Dengan Tugas Tambahan'),
				array('id'=>'PT','name'=>'Profesor Dengan Tugas Tambahan'));
	if(isset($_GET['dataId'])){
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
			array('jenis', $list_jenis, $detailDosen[0]['jenis'], 'false', 'id="jenis"'), 
			Messenger::CurrentRequest);
	}else{
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
			array('jenis', $list_jenis, '', 'false', 'id="jenis"'), 
			Messenger::CurrentRequest);
	}

	$arrrekomen = $pg->GetComboRekomendasi();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'rekomen', 
		array('rekomen', $arrrekomen, $return['input']['rekomen'], 'false', ''), 
		Messenger::CurrentRequest);
 
	$year = $pg->GetTahun();
	if(isset($_GET['dataId'])){
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'thnAkd', 
			array('thnAkd', $year, $detailDosen[0]['thnakd'], 'false', 'id="thnAkd"'), 
			Messenger::CurrentRequest);
	}else{
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'thnAkd', 
			array('thnAkd', $year, '', 'false', 'id="thnAkd"'), 
			Messenger::CurrentRequest);
	}
			
	$list_semester=array(
				   array('id'=>'Ganjil','name'=>'Ganjil'),
				   array('id'=>'Genap','name'=>'Genap'));
	if(isset($_GET['dataId'])){
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'semester', 
			array('semester', $list_semester, $detailDosen[0]['semester'], 'false', 'id="semester"'), 
			Messenger::CurrentRequest);
	}else{
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'semester', 
			array('semester', $list_semester, '', 'false', 'id="semester"'), 
			Messenger::CurrentRequest);
	}
		
	$asesor1	= $dataTampil[0]['asesor1'];
	$asesor2	= $dataTampil[0]['asesor2'];		
	$dataAs1	= $pg->GetPegawaiFull($asesor1);
	$dataAs2	= $pg->GetPegawaiFull($asesor2);

	//set the language
	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
		$labeldel=Dispatcher::Instance()->Encrypt('Load Performance Lecturer Mutation');
		$active = "Active"; $inactive = "Inactive";
    }else{
    	$labeldel=Dispatcher::Instance()->Encrypt('Mutasi Beban Kinerja Dosen');
		$active = "Aktif"; $inactive = "Tidak Aktif";
    }
    $return['lang']=$lang;
           
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);

    $return['listBkd'] 		= $listBkd;
    $return['dataTampil'] 	= $dataTampil;
    $return['dataAs1'] 		= $dataAs1;
    $return['dataAs2'] 		= $dataAs2;
	
    $return['dataPegFull'] 	= $dataPegFull;
    $return['dataPegawai'] 	= $dataPegawai;
    $return['dataSatker'] 	= $dataSatker;

    $return['detailDosen']		= $detailDosen;
    $return['dataPendidikan']	= $dataPendidikan;
    $return['dataPenelitian']	= $dataPenelitian;
    $return['dataPengabdian']	= $dataPengabdian;
    $return['dataPenunjang']	= $dataPenunjang;
    $return['dataProfesor']		= $dataProfesor;
	
    return $return;  
  }
      
  function ParseTemplate($data = NULL)
  {
	$pg	= new MutasiBkd();
    if($this->Pesan)
    {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }
      
    $listBkd		= $data['listBkd'];
    $dataTampil		= $data['dataTampil'];
    $dataAs1		= $data['dataAs1'];
    $dataAs2		= $data['dataAs2'];

    $dataPegFull	= $data['dataPegFull'];
    $dataPegawai	= $data['dataPegawai'];
    $dataSatker		= $data['dataSatker'];

    $detailDosen	= $data['detailDosen'];
    $dataPendidikan	= $data['dataPendidikan'];
    $dataPenelitian	= $data['dataPenelitian'];
    $dataPengabdian	= $data['dataPengabdian'];
    $dataPenunjang	= $data['dataPenunjang'];
    $dataProfesor	= $data['dataProfesor'];
	
    if ($data['lang']=='eng'){
     	$this->mrTemplate->AddVar('content', 'TITLE', 'LOAD PERFORMANCE LECTURER');
     	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
     	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Save');
    }else{
     	$this->mrTemplate->AddVar('content', 'TITLE', 'BEBAN KINERJA DOSEN');
     	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
     	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Simpan');  
     	$this->mrTemplate->AddVar('content', 'AKSI', isset($_GET['dataId']) ? 'Mengubah' : 'Menyimpan');  
    } 

	
    if($_GET['tabActive'] == 'pendidikan'){
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 1 })');
	}else if($_GET['tabActive'] == 'penelitian'){
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 2 })');
	}else if($_GET['tabActive'] == 'pengabdian'){
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 3 })');
	}else if($_GET['tabActive'] == 'penunjang'){
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 4 })');
	}else if($_GET['tabActive'] == 'profesor'){
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 5 })');
	}else{
		$this->mrTemplate->AddVar('content', 'TABPANEL', 'var tp1 = new Spry.Widget.TabbedPanels("tp1", { defaultTab: 0 })');	
	}
	
    $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
         
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_bkd', 'Pegawai', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_bkd', 'MutasiBkd', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
	$this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('mutasi_bkd', 'popupPegawai', 'view', 'html'));
      
	if (empty($listBkd)) {
		$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
	} else {
		$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
		
		// $label		= "Manajemen Mutasi Beban Kinerja Dosen";
		// $urlDelete	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html');
		// $urlReturn	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'MutasiBkd', 'view', 'html');
		// Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
		// $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

		// LIST SDM BKD begin
		$total	= 0;
		$start	= 1;
		for ($i=0; $i<count($listBkd); $i++) {
			$no = $i + $start;
			$listBkd[$i]['number'] = $no;
			$listBkd[$i]['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';

			if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
			if($i == sizeof($listBkd)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
	  
			$idEnc		= Dispatcher::Instance()->Encrypt($listBkd[$i]['idBkd']); 
			$urlAccept	= 'mutasi_bkd|deleteMutasiBkd|do|html-id-'.$listBkd[$i]['id'];
			$urlKembali	= 'mutasi_bkd|mutasiBkd|view|html-id-'.$listBkd[$i]['id'];
			$label 		= 'Data SDM Beban Kinerja Dosen';

			$listBkd[$i]['thnakd']		= $listBkd[$i]['thnakd'];
			$listBkd[$i]['thnakd_']		= $listBkd[$i]['thnakd'] + 1;
			$listBkd[$i]['semester']	= $listBkd[$i]['semester'];
			
			$jenisBkd					= $listBkd[$i]['jenis'];
			if($jenisBkd == "DS"){
				$listBkd[$i]['jenis']	= "Dosen Biasa";
			}else if($jenisBkd == "PR"){
				$listBkd[$i]['jenis']	= "Profesor";
			}else if($jenisBkd == "DT"){
				$listBkd[$i]['jenis']	= "Dosen Dengan Tugas Tambahan";
			}else if($jenisBkd == "PT"){
				$listBkd[$i]['jenis']	= "Profesor Dengan Tugas Tambahan";
			}
						
			$tanggal_pengajuan			= $listBkd[$i]['tanggal_pengajuan'];
				if($tanggal_pengajuan == NULL){
					$listBkd[$i]['tanggal_pengajuan']	= "-";
				}else{
					$listBkd[$i]['tanggal_pengajuan']	= $this->date2string($listBkd[$i]['tanggal_pengajuan']);
				}

			$tanggal_penilaian			= $listBkd[$i]['tanggal_penilaian'];
				if($tanggal_penilaian == NULL){
					$listBkd[$i]['tanggal_penilaian']	= "-";
				}else{
					$listBkd[$i]['tanggal_penilaian']	= $this->date2string($listBkd[$i]['tanggal_penilaian']);
				}

			$dataAs1	= $pg->GetPegawaiFull($listBkd[$i][asesor1]);
			$dataAs2	= $pg->GetPegawaiFull($listBkd[$i][asesor2]);
			$listBkd[$i]['ASESOR_1']	= $dataAs1[0]['nama'];
			$listBkd[$i]['ASESOR_2']	= $dataAs2[0]['nama'];

			// Count Record ===================================================================================================================
			$countRecPenddk				= $pg->GetCountRecPenddk($idEnc);
				$countRPenddk			= $countRecPenddk[0][countRPenddk];
			$countRecPenlt				= $pg->GetCountRecPenlt($idEnc);	
				$countRPenlt			= $countRecPenlt[0][countRPenlt];
			$countRecPengbd				= $pg->GetCountRecPengbd($idEnc);	
				$countRPengbd			= $countRecPengbd[0][countRPengbd];
			$countRecPenunj				= $pg->GetCountRecPenunj($idEnc);
				$countRPenunj			= $countRecPenunj[0][countRPenunj];
			$countRecProf				= $pg->GetCountRecProf($idEnc);
				$countRProf				= $countRecProf[0][countRProf];
			// Count Record ===================================================================================================================

			// Count Rekomendasi ==============================================================================================================
			$countRekPenddk				= $pg->GetCountRekPenddk($idEnc);
				$countPenddk			= $countRekPenddk[0][countPenddk];
			$countRekPenlt				= $pg->GetCountRekPenlt($idEnc);	
				$countPenlt				= $countRekPenlt[0][countPenlt];
			$countRekPengbd				= $pg->GetCountRekPengbd($idEnc);	
				$countPengbd			= $countRekPengbd[0][countPengbd];
			$countRekPenunj				= $pg->GetCountRekPenunj($idEnc);	
				$countPenunj			= $countRekPenunj[0][countPenunj];
			$countRekProf				= $pg->GetCountRekProf($idEnc);	
				$countProf				= $countRekProf[0][countProf];
			// Count Rekomendasi ==============================================================================================================
			
			
			$totalRec					= $countRPenddk + $countRPenlt + $countRPengbd + $countRPenunj + $countRProf;
			$statusRek					= $countPenddk + $countPenlt + $countPengbd + $countPenunj + $countProf;
			if($statusRek == 0){
				$listBkd[$i]['status']		= '<img src="images/lamp-red.gif" alt="Kosong"/>';
				$listBkd[$i]['link_aksi']	= '
					<a class="xhr dest_subcontent-element" href="{BKD_URL_EDIT}" title="Edit"><img src="images/button-edit.gif" alt="Edit"/></a>
					<a class="xhr dest_subcontent-element" href="{BKD_URL_DELETE}" title="Delete"><img src="images/button-delete.gif" alt="Delete"/></a>
					<a class="xhr dest_subcontent-element" href="{BKD_URL_DETAIL}" title="Detail"><img src="images/button-detail.gif" alt="Detail"/></a>';
			}else if($statusRek == $totalRec){
				$listBkd[$i]['status']		= '<img src="images/lamp-green.gif" alt="Full"/>';
				$listBkd[$i]['link_aksi']	= '<a class="xhr dest_subcontent-element" href="{BKD_URL_DETAIL}" title="Detail"><img src="images/button-detail.gif" alt="Detail"/></a>';
			}else{
				$listBkd[$i]['status']	= '<img src="images/lamp-yellow.gif" alt="Sebagian"/>';
				$listBkd[$i]['link_aksi']	= '
					<a class="xhr dest_subcontent-element" href="{BKD_URL_EDIT}" title="Edit"><img src="images/button-edit.gif" alt="Edit"/></a>
					<a class="xhr dest_subcontent-element" href="{BKD_URL_DETAIL}" title="Detail"><img src="images/button-detail.gif" alt="Detail"/></a>';
			}
										
			// $listBkd[$i]['status'] = $countRPenddk." + ".$countRPenlt." + ".$countRPengbd." + ".$countRPenunj." + ".$countRProf." = ".$totalRec."<br/> - <br/>".
			// $countPenddk." + ".$countPenlt." + ".$countPengbd." + ".$countPenunj." + ".$countProf." = ".$statusRek;
			// BOTTOM LIST BKD ---------------------------------------------------------------------------------------------------------------------------

			
			$thnAkd_2					= $listBkd[0]['thnakd'] + 1;
			
			$listBkd[$i]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$listBkd[0]['thnakd'].'/'.$thnAkd_2.' - '.$listBkd[0]['semester'];
			$listBkd[$i]['URL_EDIT']	= Dispatcher::Instance()->GetUrl('mutasi_bkd','mutasiBkd', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;

			$listBkd[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_bkd','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;

			if (!empty($listBkd[$i]['upload'])){
				$listBkd[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$listBkd[$i]['upload'];
			} else{
				$listBkd[$i]['LINK_DOWNLOAD_SK'] = '';
			}
		 
			$this->mrTemplate->AddVars('data_item', $listBkd[$i], 'BKD_');
			$this->mrTemplate->parseTemplate('data_item', 'a');	 
   		}
		// LIST SDM BKD end
	}

	
			if ( isset($_GET['dataId'])) {
				$this->mrTemplate->AddVar('content', 'DATA_ID_UPDATE', $this->dataId);
				$this->mrTemplate->AddVar('content', 'NAMAPT', $detailDosen[0]['namapt']);
				$this->mrTemplate->AddVar('content', 'ALAMATPT', $detailDosen[0]['almtpt']);
				$this->mrTemplate->AddVar('content', 'HP', $detailDosen[0]['nohp']);
				
				$dataAss1	= $pg->GetPegawaiFull($detailDosen[0][asesor1]);
				$dataAss2	= $pg->GetPegawaiFull($detailDosen[0][asesor2]);
				
				$this->mrTemplate->AddVar('content', 'PEG_ID_1', $dataAss1[0]['id']);
				$this->mrTemplate->AddVar('content', 'NIP_1', $dataAss1[0]['nip']);
				$this->mrTemplate->AddVar('content', 'NAMA_1', $dataAss1[0]['nama']);
				$this->mrTemplate->AddVar('content', 'PANGKAT_1', $dataAss1[0]['pktgol']);
				$this->mrTemplate->AddVar('content', 'JABATAN_1', $dataAss1[0]['jabfung']);
				$this->mrTemplate->AddVar('content', 'UNIT_KERJA_1', $dataAss1[0]['satker']);

				$this->mrTemplate->AddVar('content', 'PEG_ID_2', $dataAss2[0]['id']);
				$this->mrTemplate->AddVar('content', 'NIP_2', $dataAss2[0]['nip']);
				$this->mrTemplate->AddVar('content', 'NAMA_2', $dataAss2[0]['nama']);
				$this->mrTemplate->AddVar('content', 'PANGKAT_2', $dataAss2[0]['pktgol']);
				$this->mrTemplate->AddVar('content', 'JABATAN_2', $dataAss2[0]['jabfung']);
				$this->mrTemplate->AddVar('content', 'UNIT_KERJA_2', $dataAss2[0]['satker']);

				$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_bkd', 'updateMutasiBkd', 'do', 'html'));
			}else{
				$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_bkd', 'addMutasiBkd', 'do', 'html'));

				$this->mrTemplate->AddVar('content', 'HP', $dataPegFull[0]['nohp']);

				$this->mrTemplate->AddVar('content', 'PEG_ID_1', '');
				$this->mrTemplate->AddVar('content', 'NIP_1', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'NAMA_1', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'PANGKAT_1', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'JABATAN_1', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'UNIT_KERJA_1', '--- belum diisi ---');

				$this->mrTemplate->AddVar('content', 'PEG_ID_2', '');
				$this->mrTemplate->AddVar('content', 'NIP_2', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'NAMA_2', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'PANGKAT_2', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'JABATAN_2', '--- belum diisi ---');
				$this->mrTemplate->AddVar('content', 'UNIT_KERJA_2', '--- belum diisi ---');
			}

		// Form DOSEN start -------------------------------------------------------------------------------------------------------------------
			$this->mrTemplate->AddVar('content', 'ID', $dataPegFull[0]['id']);
			$this->mrTemplate->AddVar('content', 'NOSERTF', $dataPegFull[0]['nosertifikasi']);
			$this->mrTemplate->AddVar('content', 'NAMA', $dataPegFull[0]['nama']);
			$this->mrTemplate->AddVar('content', 'NIP', $dataPegFull[0]['nip']);
			$this->mrTemplate->AddVar('content', 'NIDN', $dataPegFull[0]['nidn']);
			$this->mrTemplate->AddVar('content', 'FAKULTAS', $dataPegFull[0]['satker']);
			$this->mrTemplate->AddVar('content', 'PRODI', $dataPegFull[0]['prodi']);
			$this->mrTemplate->AddVar('content', 'BIDANG', $dataPegFull[0]['bidang']);
			$this->mrTemplate->AddVar('content', 'JABFUNG', $dataPegFull[0]['jabfung']);
			$this->mrTemplate->AddVar('content', 'PKTGOL', $dataPegFull[0]['pktgol']);
			$this->mrTemplate->AddVar('content', 'TMPLAHIR', $dataPegFull[0]['tptlahir']);
			$this->mrTemplate->AddVar('content', 'TGLLAHIR', $this->date2string($dataPegFull[0]['tgllahir']));
			$this->mrTemplate->AddVar('content', 'TGLLAHIR_', $dataPegFull[0]['tgllahir']);
			$this->mrTemplate->AddVar('content', 'JABFUNG', $dataPegFull[0]['jabfung']);
			$this->mrTemplate->AddVar('content', 'JABFUNGID', $dataPegFull[0]['jabfungid']);
			$this->mrTemplate->AddVar('content', 'PKTGOL', $dataPegFull[0]['pktgol']);
			$this->mrTemplate->AddVar('content', 'PKTGOLID', $dataPegFull[0]['pktgolid']);
			$this->mrTemplate->AddVar('content', 'S1', $dataPegFull[0]['S1']);
			$this->mrTemplate->AddVar('content', 'S2', $dataPegFull[0]['S2']);
			$this->mrTemplate->AddVar('content', 'S3', $dataPegFull[0]['S3']);
			$this->mrTemplate->AddVar('content', 'BIDANGILMU', $dataPegFull[0]['bidang']);
		// Form DOSEN End --------------------------------------------------------------------------------------------------------------------


		// LIST PENDIDIKAN START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPendidikan)){
			$this->mrTemplate->AddVar('dataPendidikan', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPendidikan', 'DATA_EMPTY', 'NO');
			for ($a=0; $a<count($dataPendidikan); $a++) {
				$dataPendidikan[$a]['class_name'] = ($a % 2 == 0) ? '' : 'table-common-even';

				$idPend			= Dispatcher::Instance()->Encrypt($dataPendidikan[$a]['idFix']);
				$dataPendidikan[$a]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html').
					'&idDelete='.$idPend.
					'&idpegbkd='.$dataPegFull[0]['id'].
					'&idbkd='.$listBkd[0]['idBkd'].
					'&paramDelete=pendidikan';
				$dataPendidikan[$a]['LABEL']		= 'BKD Bidang Pendidikan';
				
				$this->mrTemplate->AddVars('data_item_pendidikan', $dataPendidikan[$a], 'PENDD_');
				$this->mrTemplate->parseTemplate('data_item_pendidikan', 'a');
			}
		}
		// LIST PENDIDIKAN END ---------------------------------------------------------------------------------------------------------------
		
		// LIST PENELITIAN START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPenelitian)){
			$this->mrTemplate->AddVar('dataPenelitian', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPenelitian', 'DATA_EMPTY', 'NO');
			for ($b=0; $b<count($dataPenelitian); $b++) {
				$dataPenelitian[$b]['class_name'] = ($b % 2 == 0) ? '' : 'table-common-even';

				$idPenlt		= Dispatcher::Instance()->Encrypt($dataPenelitian[$b]['idFix']);
				$dataPenelitian[$b]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html').
					'&idDelete='.$idPenlt.
					'&idpegbkd='.$dataPegFull[0]['id'].
					'&idbkd='.$listBkd[0]['idBkd'].
					'&paramDelete=penelitian';
				$dataPenelitian[$b]['LABEL']		= 'BKD Bidang Penelitian';

				$this->mrTemplate->AddVars('data_item_penelitian', $dataPenelitian[$b], 'PENLT_');
				$this->mrTemplate->parseTemplate('data_item_penelitian', 'a');	 
			}
		}
		// LIST PENELITIAN END ---------------------------------------------------------------------------------------------------------------

		
		// LIST PENGABDIAN START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPengabdian)){
			$this->mrTemplate->AddVar('dataPengabdian', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPengabdian', 'DATA_EMPTY', 'NO');
			for ($c=0; $c<count($dataPengabdian); $c++) {
				$dataPengabdian[$c]['class_name'] = ($c % 2 == 0) ? '' : 'table-common-even';

				$idPengb		= Dispatcher::Instance()->Encrypt($dataPengabdian[$c]['idFix']);
				$dataPengabdian[$c]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html').
					'&idDelete='.$idPengb.
					'&idpegbkd='.$dataPegFull[0]['id'].
					'&idbkd='.$listBkd[0]['idBkd'].
					'&paramDelete=pengabdian';
				$dataPengabdian[$c]['LABEL']		= 'BKD Bidang Pengabdian';

				$this->mrTemplate->AddVars('data_item_pengabdian', $dataPengabdian[$c], 'PENGB_');
				$this->mrTemplate->parseTemplate('data_item_pengabdian', 'a');
			}
		}
		// LIST PENGABDIAN END ---------------------------------------------------------------------------------------------------------------
		
		// LIST PENUNJANG START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPenunjang)){
			$this->mrTemplate->AddVar('dataPenunjang', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPenunjang', 'DATA_EMPTY', 'NO');
			for ($d=0; $d<count($dataPenunjang); $d++) {
				$dataPenunjang[$d]['class_name'] = ($d % 2 == 0) ? '' : 'table-common-even';

				$idPenunj	= Dispatcher::Instance()->Encrypt($dataPenunjang[$d]['idFix']);
				$dataPenunjang[$d]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html').
					'&idDelete='.$idPenunj.
					'&idpegbkd='.$dataPegFull[0]['id'].
					'&idbkd='.$listBkd[0]['idBkd'].
					'&paramDelete=penunjang';
				$dataPenunjang[$d]['LABEL']		= 'BKD Bidang Penunjang';

				$this->mrTemplate->AddVars('data_item_penunjang', $dataPenunjang[$d], 'PENUNJ_');
				$this->mrTemplate->parseTemplate('data_item_penunjang', 'a');
			}
		}
		// LIST PENUNJANG END ---------------------------------------------------------------------------------------------------------------
		
		// LIST PROFESOR START -------------------------------------------------------------------------------------------------------------
		if(empty($dataProfesor)){
			$this->mrTemplate->AddVar('dataProfesor', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataProfesor', 'DATA_EMPTY', 'NO');
			for ($e=0; $e<count($dataProfesor); $e++) {
				$dataProfesor[$e]['class_name'] = ($e % 2 == 0) ? '' : 'table-common-even';

				$idProf			= Dispatcher::Instance()->Encrypt($dataProfesor[$e]['idFix']);
				$dataProfesor[$e]['URL_DELETE']	= Dispatcher::Instance()->GetUrl('mutasi_bkd', 'deleteMutasiBkd', 'do', 'html').
					'&idDelete='.$idProf.
					'&idpegbkd='.$dataPegFull[0]['id'].
					'&idbkd='.$listBkd[0]['idBkd'].
					'&paramDelete=profesor';
				$dataProfesor[$e]['LABEL']		= 'BKD Bidang Profesor';

				$this->mrTemplate->AddVars('data_item_profesor', $dataProfesor[$e], 'PROF_');
				$this->mrTemplate->parseTemplate('data_item_profesor', 'a');
			}
		}
		// LIST PROFESOR END ---------------------------------------------------------------------------------------------------------------



			
    $id1 = Dispatcher::Instance()->Encrypt("A");
    $id2 = Dispatcher::Instance()->Encrypt("B");
    $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_1', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id1.'&dataSatker='); 
    $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_2', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id2.'&dataSatker='); 
           	
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

