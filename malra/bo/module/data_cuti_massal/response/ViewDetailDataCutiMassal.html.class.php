<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_cuti_massal/business/cuti_massal.class.php';
   
class ViewDetailDataCutiMassal extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_cuti_massal/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_cuti_massal.html');
   }
   
   function ProcessRequest()
   {

      $Obj = new CutiMassal();
      
        if ($_GET['dataId'] != '') {
          $cutId = $_GET['dataId']->Integer()->Raw();
          $dataCutiMassalDet = $Obj->GetDataCutiMassalDet($cutId);
          $dataCutiMassalPegawai = $Obj->GetDataCutiMassalPegawai($cutId);
          $result=$dataCutiMassalDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['nama'] = $result['nama'];
            $return['input']['submit'] = $result['submit'];
            $return['input']['mulai'] = $result['mulai'];
            $return['input']['selesai'] = $result['selesai'];
            $return['input']['alasan'] = $result['alasan'];
            $return['input']['tidak_ikut'] = $result['tidak_ikut'];
			$return['input']['nopak'] = $result['nopak'];
			$return['input']['namapak'] = $result['namapak'];
            $return['input']['file'] = $result['file'];
          }else{
            $return['input']['id'] = '';
            $return['input']['nama'] = '';
            $return['input']['submit'] = '';
            $return['input']['mulai'] = '';
            $return['input']['selesai'] = '';
            $return['input']['alasan'] = '';
            $return['input']['tidak_ikut'] = '';
            $return['input']['file'] = '';
          }
        }

      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataCutiMassalDet'] = $dataCutiMassalDet;
  		$return['dataCutiMassalPegawai'] = $dataCutiMassalPegawai;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataCutiMassalDet = $data['dataCutiMassalDet'];
	  $dataCutiMassalDet = $data['dataCutiMassalDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html'));
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'MASS LEAVE DATA');
         $dat['submit'] = $this->periode2stringEng($dat['submit']);
         $dat['mulai'] = $this->periode2stringEng($dat['mulai']);
         $dat['selesai'] = $this->periode2stringEng($dat['selesai']);
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI MASSAL');
         $dat['submit'] = $this->periode2string($dat['submit']);
         $dat['mulai'] = $this->periode2string($dat['mulai']);
         $dat['selesai'] = $this->periode2string($dat['selesai']);
       }
      
      if(empty($data['dataCutiMassalDet'])) {
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'YES');
      } else {
		   $pegawai = $data['dataCutiMassalDet'];
         $this->mrTemplate->AddVar('data', 'IS_EMPTY', 'NO');
         for($i=0; $i<sizeof($pegawai); $i++) {
            /*if($pegawai[$i]['id'] == $pegawai[$i-1]['id']) {
               $pegawai[$i]['nama'] = "";
            }*/
          $this->mrTemplate->AddVars('data_item', $pegawai[$i], 'DATA_');
				  $this->mrTemplate->parseTemplate('data_item', 'a');	 
         }
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
	   return $bln[(int)$bulan].' '.(int)$tanggal.', '.$tahun;
	}
}
   

?>