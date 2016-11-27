<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_cuti/business/cuti.class.php';
   
class ViewDetailDataCuti extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_cuti.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Cuti();
      $rs = $Obj->GetDataById($pegId);
      
        if ($_GET['dataId2'] != '') {
          $cutId = $_GET['dataId2']->Integer()->Raw();
          $dataCutiDet = $Obj->GetDataCutiDet($cutId);
          $result=$dataCutiDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no'];
            $return['input']['aju'] = $result['tglaju'];
            $return['input']['submit'] = $result['tglsub'];
            $return['input']['mulai'] = $result['tglmul'];
            $return['input']['selesai'] = $result['tglsel'];
			$return['input']['durasi'] = $result['durasi'];
            $return['input']['nama'] = $result['nama_cuti'];
            $return['input']['idcuti'] = $result['id_cuti'];
            $return['input']['alasan'] = $result['alasan'];
            $return['input']['status'] = $result['status'];
            $return['input']['tglstat'] = $result['tglstat'];
            $return['input']['tggjwb'] = $result['tggkerja'];
            $return['input']['pggjwb'] = $result['pggsmnt'];
            $return['input']['pggjwbk'] = $result['pggsmntk'];
          }else{
            $return['input']['id'] = '';
            $return['input']['no'] = '';
            $return['input']['aju'] = '';
            $return['input']['submit'] = '';
            $return['input']['mulai'] = '';
            $return['input']['selesai'] = '';
            $return['input']['nama'] = '';
            $return['input']['idcuti'] = '';
            $return['input']['alasan'] = '';
            $return['input']['status'] = '';
            $return['input']['tglstat'] = '';
            $return['input']['tggjwb'] = '';
            $return['input']['pggjwb'] = '';
            $return['input']['pggjwbk'] = '';
          }
        }
      }
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataIstriDet'] = $dataIstriDet;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataIstriDet = $data['dataIstriDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $idEnc = Dispatcher::Instance()->Encrypt($data['idPegawai']);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html') . '&dataId=' . $idEnc );
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai['alamat']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'DATA_FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'DATA_FOTO2', $dataPegawai['foto']);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
	  $dat['durasi']=$dat['durasi'].' hari';
	  
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE DATA');
         $dat['aju'] = $this->periode2stringEng($dat['aju']);
         $dat['submit'] = $this->periode2stringEng($dat['submit']);
         $dat['mulai'] = $this->periode2stringEng($dat['mulai']);
         $dat['selesai'] = $this->periode2stringEng($dat['selesai']);
         $dat['tglstat'] = $this->periode2stringEng($dat['tglstat']);
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI');
         $dat['aju'] = $this->periode2string($dat['aju']);
         $dat['submit'] = $this->periode2string($dat['submit']);
         $dat['mulai'] = $this->periode2string($dat['mulai']);
         $dat['selesai'] = $this->periode2string($dat['selesai']);
         $dat['tglstat'] = $this->periode2string($dat['tglstat']);
       }
	   
	   $status['request']='Dalam Proses';
	   $status['approved']='Disetujui';
	   $status['rejected']='Ditolak';
	   $dat['status']=$status[$dat['status']];
      
      $this->mrTemplate->AddVars('content', $dat, '');
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
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
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
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function dateDiff($dformat, $endDate, $beginDate)	{
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
		$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
		return $end_date - $start_date + 1;
	}
}
   

?>