<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/pendapatan_lain/business/pendapatan_lain.class.php';

class ViewInputPendapatanLain extends HtmlResponse {
	var $Data;
	var $Pesan;
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/pendapatan_lain/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('input_pendapatan_lain.html');
	}
	
	function ProcessRequest() {
		$Obj = new PendapatanLain();
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->Data = $msg[0][0];

    $idDec = Dispatcher::Instance()->Decrypt((string)$_GET['dataId']);
    $tglDec = Dispatcher::Instance()->Decrypt((string)$_GET['tglId']);
	  $dataPendapatan = $Obj->GetDataById($idDec,$tglDec);
	  //print_r($idDec);
	  //print_r($_GET['tglId']);
	  $result=$dataPendapatan;
    if(!empty($result)){
      $return['input']['id'] = $result['id'];
      $return['input']['nama'] = $result['nama'];
      $return['input']['tgl'] = $result['tgl'];
      $return['input']['nominal'] = $result['nominal'];
      $return['input']['des'] = $result['des'];
    }else{
      $return['input']['id'] = '';
      $return['input']['nama'] = '';
      $return['input']['tgl'] = '';
      $return['input']['nominal'] = '';
      $return['input']['des'] = '';
    }
		
		$pegawai = $Obj->GetPegawaiById($idDec,$tglDec);
    
    $listJenis = $Obj->GetComboJenis();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
       array('jenis',$listJenis,$result['id'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
    
    $year = date('Y')+4;
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tanggal', 
      array($result['tgl'],'2003',$year,'',''), Messenger::CurrentRequest);
      
		$return['pegawai'] = $pegawai;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if ($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
		}
		$dat = $data['input'];
		//print_r($dataGajiPegawai);
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
    
		if ($_REQUEST['dataId']=='') {
			 if ($lang=='eng'){
          $tambah="Add";
       }else{
          $tambah="Tambah";  
       }
			$op="add";
		} else {
			 if ($lang=='eng'){
          $tambah="Update";
       }else{
          $tambah="Ubah";  
       }
			$op="edit";
		}
    
    $this->mrTemplate->AddVar('content', 'LABEL_ACTION', $tambah);
    $this->mrTemplate->AddVar('content', 'OP', $op);
		$this->mrTemplate->AddVar('content', 'ID', $dat['des']);
		$this->mrTemplate->AddVar('content', 'TGL_ID', $dat['tgl']);
		$dat['nominal'] = "Rp. ".number_format($dat['nominal'], 2, ',', '.');
		$this->mrTemplate->AddVar('content', 'NOMINAL', $dat['nominal']);
		$this->mrTemplate->AddVar('content', 'DESKRIPSI', $dat['des']);
		
		if(empty($dat['id'])) {
      $this->mrTemplate->AddVar('detil_pend', 'PEND_EMPTY', "YES");
    } else {
      $this->mrTemplate->AddVar('detil_pend', 'PEND_EMPTY', "NO");
      $this->mrTemplate->AddVar('detil_pend_item', 'NAMA', $dat['nama']);
      $this->mrTemplate->AddVar('detil_pend_item', 'ID', $dat['id']);
		}
		if(empty($dat['tgl'])) {
      $this->mrTemplate->AddVar('detil_pend_2', 'PEND2_EMPTY', "YES");
    } else {
      $this->mrTemplate->AddVar('detil_pend_2', 'PEND2_EMPTY', "NO");
      $dataa=$this->periode2string($dat['tgl']);
      $this->mrTemplate->AddVar('detil_pend2_item', 'TANGGAL', $dataa);
		}
		if(empty($dat['des'])) {
      $this->mrTemplate->AddVar('detil_pend_3', 'PEND3_EMPTY', "YES");
    } else {
      $this->mrTemplate->AddVar('detil_pend_3', 'PEND3_EMPTY', "NO");
      $this->mrTemplate->AddVar('detil_pend3_item', 'DESKRIPSI', $dat['des']);
		}
		
		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'inputPendapatanLain', 'do', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_POPUP_PEGAWAI', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'popupPegawai', 'view', 'html'));

    if(empty($data['pegawai'])) {
      $this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "YES");
    } else {
      $this->mrTemplate->AddVar('detil_pegawai', 'DATA_EMPTY', "NO");
      $pegawai = $data['pegawai'];
      for ($i=0; $i<sizeof($pegawai); $i++) {
        $pegawai[$i]['coba']=$i;
        $this->mrTemplate->AddVars('data_pegawai_item', $pegawai[$i], 'DATA_');
        $this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
      }
    }
	}
	
	function periode2string($date) {
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
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   $tanggal = substr($date,8,2);
	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun;
	}
}
?>
