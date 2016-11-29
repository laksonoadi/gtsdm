<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/pendapatan_lain/business/pendapatan_lain.class.php';
  
class ViewPendapatanLain extends HtmlResponse
{
   var $Pesan;
   
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/pendapatan_lain/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_pendapatan_lain.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new PendapatanLain();
      if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nip_nama'])) {
  				$nip_nama = $_POST['nip_nama'];
          $jenis = $_POST['jenis'];
          $idBulan = $_POST['periode_bulan'];
				  $idTahun = $_POST['periode_tahun'];
  			} elseif(isset($_GET['nip_nama'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
          $jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
          $idBulan = Dispatcher::Instance()->Decrypt($_GET['periode_bulan']);
				  $idTahun = Dispatcher::Instance()->Decrypt($_GET['periode_tahun']);
  			} else {
  				$nip_nama = '';
  				$jenis = '';
  				$idBulan=date('m');
  				$idTahun=date('Y');
  			}
  		}
         
      $totalData = $Obj->GetCountData($nip_nama,$jenis,$idBulan, $idTahun);
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataJenis = $Obj->GetData($startRec,$itemViewed,$nip_nama,$jenis,$idBulan,$idTahun);
  	  //print_r($jenis);
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
        '&jenis=' . Dispatcher::Instance()->Encrypt($jenis).
        '&periode_bulan=' . Dispatcher::Instance()->Encrypt($idBulan).
        '&periode_tahun=' . Dispatcher::Instance()->Encrypt($idTahun).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	//create paging end here
	    $listJenis = $Obj->GetComboJenis();
	    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
         array('jenis',$listJenis,$jenis,'true',' style="width:200px;"'), Messenger::CurrentRequest);
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    	if ($lang=='eng'){
        $bulan = $Obj->GetBulanEng();
      }else{
        $bulan = $Obj->GetBulan();  
      }
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan', 
  	     array('periode_bulan', $bulan, $idBulan, 'none', ''), 
  		 Messenger::CurrentRequest);
  		
      $year = $Obj->GetTahun();  
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun', 
  	     array('periode_tahun', $year, $idTahun, 'none', ''), 
  		 Messenger::CurrentRequest);
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataJenis'] = $dataJenis;
  		$return['start'] = $startRec+1;
        
      //print_r($dataJenis);
      $return['search']['nipnama'] = $nip_nama;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $search = $data['search'];
		  $this->mrTemplate->AddVar('content', 'NIP_NAMA', $search['nipnama']);
		  
		  $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'OTHER INCOME');
         $label = "Other Income";
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'PENDAPATAN LAIN');
         $label = "Pendapatan Lain";
       }
		  
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'inputPendapatanLain', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('pendapatan_lain', 'pendapatanLain', 'view', 'html') );
            
      if (empty($data['dataJenis'])) {
  			$this->mrTemplate->AddVar('data_pendapatan', 'NILAI_EMPTY', 'YES');
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('data_pendapatan', 'NILAI_EMPTY', 'NO');
  			$dataJenis = $data['dataJenis'];
  
  //mulai bikin tombol delete		
  			$urlDelete = Dispatcher::Instance()->GetUrl('pendapatan_lain', 'deletePendapatanLain', 'do', 'html');
  			$urlReturn = Dispatcher::Instance()->GetUrl('pendapatan_lain', 'pendapatanLain', 'view', 'html');
  			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
  			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
        $total=0;
  			for ($i=0; $i<sizeof($dataJenis); $i++) {
  				$no = $i+$data['start'];
  				$dataJenis[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataJenis[$i]['class_name'] = 'table-common-even';
          }else{
            $dataJenis[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataJenis)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataJenis[$i]['des']);
  				$tglEnc = Dispatcher::Instance()->Encrypt($dataJenis[$i]['tgl']);
          
          if($dataJenis[$i]['tgl']=="0000-00-00"){
            $dataJenis[$i]['tgl']="";
          }else{
            $dataJenis[$i]['tgl']=$this->periode2string($dataJenis[$i]['tgl']);
          }
          
          $urlAccept = 'pendapatan_lain|deletePendapatanLain|do|html';
          $urlKembali = 'pendapatan_lain|pendapatanLain|view|html';
          $dataName = $dataJenis[$i]['nama'].' ('.$dataJenis[$i]['des'].'), tanggal '.$dataJenis[$i]['tgl'];
          $dataJenis[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&tglId='.$tglEnc.'&label='.$label.'&dataName='.$dataName;
          $dataJenis[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('pendapatan_lain','inputPendapatanLain', 'view', 'html').'&dataId='. $idEnc.'&tglId='. $tglEnc;
          $dataJenis[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('pendapatan_lain','detailPendapatanLain', 'view', 'html').'&dataId='. $idEnc.'&tglId='. $tglEnc;
            
          $dataJenis[$i]['nominal'] = "Rp. ".number_format($dataJenis[$i]['nominal'], 2, ',', '.');
				
  				$this->mrTemplate->AddVars('data_pendapatan_item', $dataJenis[$i], 'NIL_');
  				$this->mrTemplate->parseTemplate('data_pendapatan_item', 'a');	 
  			}
      }
   }
   
   function periode2string($date) {
	   /*$bln = array(
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
	               );*/
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   $tanggal = substr($date,8,2);
	   //return (int)$tanggal.'/'.$bln[(int) $bulan].'/'.$tahun;
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
   

?>