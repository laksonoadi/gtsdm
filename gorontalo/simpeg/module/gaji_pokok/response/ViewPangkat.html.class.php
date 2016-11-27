<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gaji_pokok/business/gaji_pokok.class.php';
   
class ViewPangkat extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/gaji_pokok/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_pangkat.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new GajiPokok;
		  /*
        //create paging 
      $totalData = $Obj->GetCount($nip_nama);
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $Obj->GetData($startRec, $itemViewed, $nip_nama);
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	//create paging end here*/
      $dataPangkat = $Obj->GetData();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataPangkat'] = $dataPangkat;
  		$return['start'] = 1;
        
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
		  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
  	  if ($lang=='eng'){
  	      $this->mrTemplate->AddVar('content', 'TITLE', 'BASIC SALARY REFERENCE');
  	  }else{
          $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI GAJI POKOK');
      }      
      if (empty($data['dataPangkat'])) {
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'NO');
  			$dataPangkat = $data['dataPangkat'];
        $total=0;
  			for ($i=0; $i<sizeof($dataPangkat); $i++) {
  				$no = $i+$data['start'];
  				$dataPangkat[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataPangkat[$i]['class_name'] = 'table-common-even';
          }else{
            $dataPangkat[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataPangkat)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataPangkat[$i]['id']);
          $dataPangkat[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('gaji_pokok','gajiPokok', 'view', 'html').'&dataId='. $idEnc;
          
          $dataPangkat[$i]['PANGKAT'] = $dataPangkat[$i]['id'].' - '.$dataPangkat[$i]['nama'];  
          
  				$this->mrTemplate->AddVars('data_pegawai_item', $dataPangkat[$i], '');
  				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
  			}
      }
   }
}
   

?>