<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_bkd/business/mutasi_bkd.class.php';

class ViewDetailMutasi extends HtmlResponse
{
  
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/mutasi_bkd/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_detail_mutasi.html');
  }

  function ProcessRequest()
  {
   	$pg				= new MutasiBkd();
   	$msg 			= Messenger::Instance()->Receive(__FILE__);
   	$this->Data 	= $msg[0][0];
	$this->Pesan	= $msg[0][1];
	$this->css 		= $msg[0][2];

    // ---------
    $id 			= $_GET['dataId']->Integer()->Raw();
    $profilId 		= $_GET['id']->Integer()->Raw();
	
	$return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_bkd','MutasiBkd','view','html').'&id='.$profilId;
    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
	if(isset($_GET['dataId'])){
		$dataTampil		= $pg->GetDetailBkd($id);
		$hasil_pegawai	= $pg->GetDataDetail($profilId);
		
		$dataPendidikan	= $pg->GetDataPendidikan($id);
		$dataPenelitian	= $pg->GetDataPenelitian($id);
		$dataPengabdian	= $pg->GetDataPengabdian($id);
		$dataPenunjang	= $pg->GetDataPenunjang($id);
		$dataProfesor	= $pg->GetDataProfesor($id);
	}

	$asesor1	= $dataTampil[0]['asesor1'];
	$asesor2	= $dataTampil[0]['asesor2'];		
	$dataAs1	= $pg->GetPegawaiFull($asesor1);
	$dataAs2	= $pg->GetPegawaiFull($asesor2);

// echo $dataPengabdian[0]['nmKeg'];
// exit;

	$return['profil']		= $hasil_pegawai[0];
	$return['dataTampil'] 	= $dataTampil;
    $return['dataAs1'] 		= $dataAs1;
    $return['dataAs2'] 		= $dataAs2;

    $return['dataPendidikan']	= $dataPendidikan;
    $return['dataPenelitian']	= $dataPenelitian;
    $return['dataPengabdian']	= $dataPengabdian;
    $return['dataPenunjang']	= $dataPenunjang;
    $return['dataProfesor']		= $dataProfesor;

	//set the language
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
    	$labeldel=Dispatcher::Instance()->Encrypt('Working Unit Mutation Detail');
		  $return['upload']="Decree document has not been uploaded";
    }else{
    	$labeldel=Dispatcher::Instance()->Encrypt('Detail Mutasi Satuan Kerja');
		  $return['upload']="Belum mengupload dokumen SK";
    }
    $return['lang']=$lang;

        
    return $return;
  }

  function ParseTemplate($data = NULL)
  {
    if ($data['lang']=='eng'){
	   	$this->mrTemplate->AddVar('content', 'TITLE', 'LOAD PERFORMANCE LECTURER DETAIL');
  	}else{
    	$this->mrTemplate->AddVar('content', 'TITLE', 'DETAIL BEBAN KINERJA DOSEN');  
    }

    if($this->Pesan){
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }
         
    $link = $data['link'];
    $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
    $this->mrTemplate->AddVar('content', 'URL_DOWNLOAD', $link['link_download']);
    
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profil']['foto']) | empty($data['profil']['foto'])) { 
		  $data['profil']['foto']='unknown.gif';
	  }
        
    if(!empty($data['profil'])){
      $this->mrTemplate->AddVars('content', $data['profil'], 'PROFIL_');
    }
	
	$dataTampil		= $data['dataTampil'];
    $dataAs1		= $data['dataAs1'];
    $dataAs2		= $data['dataAs2'];


    $dataPendidikan	= $data['dataPendidikan'];
    $dataPenelitian	= $data['dataPenelitian'];
    $dataPengabdian	= $data['dataPengabdian'];
    $dataPenunjang	= $data['dataPenunjang'];
    $dataProfesor	= $data['dataProfesor'];


		// DETAIL IDENTITAS DOSEN BEGIN --------------------------------------------------------------------------------------------------------
		$this->mrTemplate->AddVar('content', 'ID', $dataTampil[0]['id']);
		$this->mrTemplate->AddVar('content', 'NOSERTF', $dataTampil[0]['nosertifikasi']);
		$this->mrTemplate->AddVar('content', 'NAMA', $dataTampil[0]['nama']);
		$this->mrTemplate->AddVar('content', 'NIP', $dataTampil[0]['nip']);
		$this->mrTemplate->AddVar('content', 'NIDN', $dataTampil[0]['nidn']);
		$this->mrTemplate->AddVar('content', 'NAMAPT', $dataTampil[0]['namapt']);
		$this->mrTemplate->AddVar('content', 'ALAMATPT', $dataTampil[0]['almtpt']);
		$this->mrTemplate->AddVar('content', 'FAKULTAS', $dataTampil[0]['fakultas']);
		$this->mrTemplate->AddVar('content', 'PRODI', $dataTampil[0]['prodi']);
		$this->mrTemplate->AddVar('content', 'BIDANG', $dataTampil[0]['bidang']);
		$this->mrTemplate->AddVar('content', 'JABFUNG', $dataTampil[0]['jabfung']);
		$this->mrTemplate->AddVar('content', 'PKTGOL', $dataTampil[0]['pktgol']);
		$this->mrTemplate->AddVar('content', 'HP', $dataTampil[0]['nohp']);
		$this->mrTemplate->AddVar('content', 'JABFUNG', $dataTampil[0]['jabfung']);
		$this->mrTemplate->AddVar('content', 'PKTGOL', $dataTampil[0]['pktgol']);
		$this->mrTemplate->AddVar('content', 'S1', $dataTampil[0]['s1']);
		$this->mrTemplate->AddVar('content', 'S2', $dataTampil[0]['s2']);
		$this->mrTemplate->AddVar('content', 'S3', $dataTampil[0]['s3']);

		if($dataTampil[0]['jenis'] == DS){
			$this->mrTemplate->AddVar('content', 'JENIS', 'Dosen Biasa');
		}else if($dataTampil[0]['jenis'] == PR){
			$this->mrTemplate->AddVar('content', 'JENIS', 'Profesor');
		}else if($dataTampil[0]['jenis'] == DT){
			$this->mrTemplate->AddVar('content', 'JENIS', 'Dosen Dengan Tugas Tambahan');
		}else if($dataTampil[0]['jenis'] == PT){
			$this->mrTemplate->AddVar('content', 'JENIS', 'Profesor Dengan Tugas Tambahan');
		}

		$tahunAkdm	= $dataTampil[0]['thnakd'];
		$tahunAkdm2 = $tahunAkdm + 1;
		
		$this->mrTemplate->AddVar('content', 'THNAKD', $tahunAkdm.'/'.$tahunAkdm2);
		$this->mrTemplate->AddVar('content', 'SEMESTER', $dataTampil[0]['semester']);

		$this->mrTemplate->AddVar('content', 'NIP_1', $dataAs1[0]['nip']);
		$this->mrTemplate->AddVar('content', 'NAMA_1', $dataAs1[0]['nama']);
		$this->mrTemplate->AddVar('content', 'PANGKAT_1', $dataAs1[0]['pktgol']);
		$this->mrTemplate->AddVar('content', 'JABATAN_1', $dataAs1[0]['jabfung']);
		$this->mrTemplate->AddVar('content', 'UNIT_KERJA_1', $dataAs1[0]['satker']);

		$this->mrTemplate->AddVar('content', 'NIP_2', $dataAs2[0]['nip']);
		$this->mrTemplate->AddVar('content', 'NAMA_2', $dataAs2[0]['nama']);
		$this->mrTemplate->AddVar('content', 'PANGKAT_2', $dataAs2[0]['pktgol']);
		$this->mrTemplate->AddVar('content', 'JABATAN_2', $dataAs2[0]['jabfung']);
		$this->mrTemplate->AddVar('content', 'UNIT_KERJA_2', $dataAs2[0]['satker']);

		$this->mrTemplate->AddVar('content', 'TGLPENILAIAN', (empty($dataTampil[0]['tglPenilaian'])) ? '-' : $this->date2string($dataTampil[0]['tglPenilaian']));
		$this->mrTemplate->AddVar('content', 'KESIMPULAN', (empty($dataTampil[0]['kesimpulan'])) ? '-' : $dataTampil[0]['kesimpulan']);
		// DETAIL IDENTITAS DOSEN END --------------------------------------------------------------------------------------------------------

		// LIST PENDIDIKAN START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPendidikan)){
			$this->mrTemplate->AddVar('dataPendidikan', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPendidikan', 'DATA_EMPTY', 'NO');
			for ($a=0; $a<count($dataPendidikan); $a++) {
				$dataPendidikan[$a]['class_name']	= ($a % 2 == 0) ? '' : 'table-common-even';
				$dataPendidikan[$a]['url_download']	= GTFWConfiguration::GetValue( 'application', 'file_download_path');
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
				$dataPenelitian[$b]['class_name']	= ($b % 2 == 0) ? '' : 'table-common-even';
				$dataPenelitian[$b]['url_download']	= GTFWConfiguration::GetValue( 'application', 'file_download_path');
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
				$dataPengabdian[$c]['class_name']	= ($c % 2 == 0) ? '' : 'table-common-even';
				$dataPengabdian[$c]['url_download']	= GTFWConfiguration::GetValue( 'application', 'file_download_path');
				$this->mrTemplate->AddVars('data_item_pengabdian', $dataPengabdian[$c], 'PENGB_');
				$this->mrTemplate->parseTemplate('data_item_pengabdian', 'a');
			}
		}
		// LIST PENGABDIAN END ---------------------------------------------------------------------------------------------------------------
		
		// LIST PENGABDIAN START -------------------------------------------------------------------------------------------------------------
		if(empty($dataPenunjang)){
			$this->mrTemplate->AddVar('dataPenunjang', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataPenunjang', 'DATA_EMPTY', 'NO');
			for ($d=0; $d<count($dataPenunjang); $d++) {
				$dataPenunjang[$d]['class_name']	= ($d % 2 == 0) ? '' : 'table-common-even';
				$dataPenunjang[$d]['url_download']	= GTFWConfiguration::GetValue( 'application', 'file_download_path');
				$this->mrTemplate->AddVars('data_item_penunjang', $dataPenunjang[$d], 'PENUNJ_');
				$this->mrTemplate->parseTemplate('data_item_penunjang', 'a');
			}
		}
		// LIST PENGABDIAN END ---------------------------------------------------------------------------------------------------------------
		
		// LIST PROFESOR START -------------------------------------------------------------------------------------------------------------
		if(empty($dataProfesor)){
			$this->mrTemplate->AddVar('dataProfesor', 'DATA_EMPTY', 'YES');
			// return NULL;
		}else{
			$this->mrTemplate->AddVar('dataProfesor', 'DATA_EMPTY', 'NO');
			for ($e=0; $e<count($dataProfesor); $e++) {
				$dataProfesor[$e]['class_name'] 	= ($e % 2 == 0) ? '' : 'table-common-even';
				$dataProfesor[$e]['url_download']	= GTFWConfiguration::GetValue( 'application', 'file_download_path');
				$this->mrTemplate->AddVars('data_item_profesor', $dataProfesor[$e], 'PROF_');
				$this->mrTemplate->parseTemplate('data_item_profesor', 'a');
			}
		}
		// LIST PROFESOR END ---------------------------------------------------------------------------------------------------------------
		
		
		
		
    // $this->mrTemplate->AddVars('data_item', $value, '');
    // $this->mrTemplate->parseTemplate('data_item', 'a');
         
    }
  

  function dumper($print){
		echo"<pre>";print_r($print);echo"</pre>";
	}

       function date2string($date) {
         $bln = array(
         1  => 'Januari',
         2  => 'Februari',
         3  => 'Maret',
         4  => 'April',
         5  => 'Mei',
         6  => 'Juni',
         7  => 'Juli',
         8  => 'Agustus',
         9  => 'September',
         10 => 'Oktober',
         11 => 'November',
         12 => 'Desember'					
         );
         $arrtgl = explode('-',$date);
      return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
      }
      
      function date2stringEng($date) {
         $bln = array(
         1  => 'January',
         2  => 'February',
         3  => 'March',
         4  => 'April',
         5  => 'May',
         6  => 'June',
         7  => 'July',
         8  => 'August',
         9  => 'September',
         10 => 'October',
         11 => 'November',
         12 => 'December'					
         );
         $arrtgl = explode('-',$date);
      return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
      }
   }
?>
