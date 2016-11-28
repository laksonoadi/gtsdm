<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/list_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'main/function/date.php';

class ViewCetakLapCuti extends HtmlResponse {
   #var $Pesan;

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/list_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_cetak_lap_cuti.html');
   }
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-print.html');
      $this->SetTemplateFile('layout-common-print.html');
   }
   
   function ProcessRequest() {
      $Obj = new Cuti();
      $_GET = $_GET->AsArray();
      $idDec = Dispatcher::Instance()->Decrypt($_GET['dataId']); //tipe
      $idDec2 = Dispatcher::Instance()->Decrypt($_GET['dataId2']); //status
      
      $data_cetak = $Obj->GetDataCetak($idDec,$idDec2);

      #print_r($data_cetak); exit;
      $return['slip'] = $data_cetak;
      return $return;
   }
   
   function ParseTemplate($data = NULL) {
      if (empty($data)) {
         $this->mrTemplate->AddVar('data', 'SLIP_EMPTY', 'YES');
      } else {
         $lang=GTFWConfiguration::GetValue('application', 'button_lang');
         $this->mrTemplate->AddVar('data', 'SLIP_EMPTY', 'NO');
         #print_r($data['saldo']); exit;
         $no = 1;
         for ($i=0; $i<count($data['slip']); $i++) {
            $data['slip'][$i]['nomer'] = $no++;
            if ($lang=='eng'){
              $data['slip'][$i]['mulai']=$this->periode2stringEng($data['slip'][$i]['mulai']);
              $data['slip'][$i]['selesai']=$this->periode2stringEng($data['slip'][$i]['selesai']);
            }else{
              $data['slip'][$i]['mulai']=$this->periode2string($data['slip'][$i]['mulai']);
              $data['slip'][$i]['selesai']=$this->periode2string($data['slip'][$i]['selesai']);
            }
            
            $data['slip'][$i]['tglcuti'] = $data['slip'][$i]['mulai'].' - '.$data['slip'][$i]['selesai'];
            
            $this->mrTemplate->AddVars('data_slip_item', $data['slip'][$i], '');
            $this->mrTemplate->parseTemplate('data_slip_item', 'a');    
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
	  
       $bulan = substr($date,5,2);
  	   $tahun = substr($date,0,4);
  	   $tanggal = substr($date,8,2);
  	   return (int)$tanggal.' '.$bln[(int) $bulan].' '.$tahun; 
	}
   
}
?>