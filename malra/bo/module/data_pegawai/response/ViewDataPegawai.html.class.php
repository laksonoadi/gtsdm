<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/business/verifikasi_data.class.php';
  
class ViewDataPegawai extends HtmlResponse
{
   var $Pesan;
   
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_pegawai.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new DataPegawai();
      if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nip_nama'])) {
  				$nip_nama = $_POST['nip_nama'];
  				$status_kerja = $_POST['status_kerja'];
  			} elseif(isset($_GET['nip_nama'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
  				$status_kerja = Dispatcher::Instance()->Decrypt($_GET['status_kerja']);     
  			} else {
  				$nip_nama = '';
  				$status_kerja = 'all';  
  			}
  		}
         
        $totalData = $Obj->GetCountPegawaiByUserId($nip_nama, $status_kerja);

  		$itemViewed = 50;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $Obj->GetDataPegawaiByUserId($startRec, $itemViewed, $nip_nama, $status_kerja);
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
        '&status_kerja=' . Dispatcher::Instance()->Encrypt($status_kerja).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	//create paging end here
          $lang = '';
      if ($lang=='eng'){
        $arrJenis[2]['id'] = "ns";
        $arrJenis[0]['name'] = "Already paid"; 
        $arrJenis[1]['name'] = "Unpaid";
        $arrJenis[2]['name'] = "Not specified";	
     }else{
        $arrJenis[0]['name'] = "Sudah Dibayar"; 
        $arrJenis[1]['name'] = "Belum Dibayar";
        //$arrJenis[2]['name'] = "Belum Ditentukan";
     }
		  $arrStatusKerja = $Obj->GetComboStatPeg();
      
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_kerja', array('status_kerja', $arrStatusKerja, $status_kerja, 'true', ' style="width:130px;" '), Messenger::CurrentRequest);


      $msg = Messenger::Instance()->Receive(__FILE__);
      if(isset($msg[0])){
      $this->Pesan = $msg[0][1];
      $this->css = $msg[0][2];        
      }
  
  		$return['dataPegawai'] = $dataPegawai;
  		$return['start'] = $startRec+1;
        
      $return['search']['nip_nama'] = $nip_nama;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";
    
	  if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nip_nama'])) {
  				$nip_nama = $_POST['nip_nama'];
  				$status_kerja = $_POST['status_kerja'];
  			} elseif(isset($_GET['nip_nama'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
  				$status_kerja = Dispatcher::Instance()->Decrypt($_GET['status_kerja']);     
  			} else {
  				$nip_nama = '';
  				$status_kerja = 'all';  
  			}
  		}
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $search = $data['search'];
		  $this->mrTemplate->AddVar('content', 'NIP_NAMA', $search['nip_nama']);
		  
		  $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYEE DATA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
           $label="Employee Data";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PEGAWAI');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');
           $label="Data Pegawai";  
       }
		  
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html').'&op=add');
      $this->mrTemplate->AddVar('content', 'URL_ADD_WIZARD', Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardPegawai', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('data_pegawai', 'laporanDataPegawai', 'view', 'xls').'&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).'&status_kerja=' . Dispatcher::Instance()->Encrypt($status_kerja));
	  $this->mrTemplate->AddVar('content', 'URL_CONTRACT', Dispatcher::Instance()->GetUrl('data_pegawai', 'kontrakPegawai', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html') );
            
      if (empty($data['dataPegawai'])) {
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'NO');
  			$dataPegawai = $data['dataPegawai'];
  
  //mulai bikin tombol delete
  			$urlDelete = Dispatcher::Instance()->GetUrl('data_pegawai', 'deleteDataPegawai', 'do', 'html');
  			$urlReturn = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html');
  			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
  			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
        $total=0;
  			for ($i=0; $i<sizeof($dataPegawai); $i++) {
  				$no = $i+$data['start'];
  				$dataPegawai[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataPegawai[$i]['class_name'] = 'table-common-even';
          }else{
            $dataPegawai[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataPegawai)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataPegawai[$i]['id']);
          
          $urlAccept = 'data_pegawai|deleteDataPegawai|do|html';
          $urlKembali = 'data_pegawai|dataPegawai|view|html';
          $dataName = $dataPegawai[$i]['nama'];
          $dataPegawai[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataPegawai[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_pegawai','inputDataPegawai', 'view', 'html').'&dataId='. $idEnc;
          $dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_pegawai','detailDataPegawai', 'view', 'html').'&dataId='. $idEnc;
            
  				$this->mrTemplate->AddVars('data_pegawai_item', $dataPegawai[$i], '');
  				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
  			}
      }
   }
}
   

?>