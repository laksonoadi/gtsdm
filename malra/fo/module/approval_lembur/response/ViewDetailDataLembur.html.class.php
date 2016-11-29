<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_lembur/business/lembur.class.php';
   
class ViewDetailDataLembur extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/approval_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_lembur.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Lembur();
      $rs = $Obj->GetDataById($pegId);
      $rs['detail'] = $rs[0];
      $rs['spv'] = $rs[1];
      $rs['mor'] = $rs[2];
      
      
      
        if ($_GET['dataId2'] != '') {
          $lemburId = $_GET['dataId2']->Integer()->Raw();
          $dataLemburDet = $Obj->GetDataLemburDet($lemburId);
          $result=$dataLemburDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no'];
            $return['input']['aju'] = $result['tglaju'];
            $return['input']['submit'] = $result['tglsub'];
            $return['input']['mulai'] = $result['mulai'];
            $return['input']['selesai'] = $result['selesai'];
            $return['input']['nama'] = $result['nama_lembur'];
            $return['input']['idlembur'] = $result['id_lembur'];
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
            $return['input']['idlembur'] = '';
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
  		
  		//set the language
      	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	  $return['lang']=$lang;
      	  
  		$return['dataPegawai'] = $rs['detail'];
  		$return['dataPegawaiSpv'] = $rs['spv'];
  		$return['dataPegawaiMor'] = $rs['mor'];
  		$return['dataIstriDet'] = $dataIstriDet;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataPegawaiSpv = $data['dataPegawaiSpv'];
      $dataPegawaiMor = $data['dataPegawaiMor'];
      $dataIstriDet = $data['dataIstriDet'];
      $dat = $data['input'];

      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $idEnc = Dispatcher::Instance()->Encrypt($data['idPegawai']);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('approval_lembur', 'historyDataLembur', 'view', 'html') . '&dataId=' . $idEnc );
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai[0]['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai[0]['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_SPV', $dataPegawaiSpv[0]['spv']);
      $this->mrTemplate->AddVar('content', 'DATA_MOR', $dataPegawaiMor[0]['mor']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai[0]['alamat']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'DATA_FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'DATA_FOTO2', $dataPegawai[0]['foto']);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE DATA');
         $dat['aju'] = $this->periode2stringEng($dat['aju']);
         $dat['submit'] = $this->periode2stringEng($dat['submit']);
         $dat['tglstat'] = $this->periode2stringEng($dat['tglstat']);
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI');
         $dat['aju'] = $this->periode2string($dat['aju']);
         $dat['submit'] = $this->periode2string($dat['submit']);
         $dat['tglstat'] = $this->periode2string($dat['tglstat']);
       }
      
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
}
   

?>