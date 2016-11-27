<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_benefit/business/benefit.class.php';
   
class ViewPegawai extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_pegawai.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new Benefit;
		  if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nip_nama'])) {
  				$nip_nama = $_POST['nip_nama'];
  			} elseif(isset($_GET['nip_nama'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']);
  			} else {
  				$nip_nama = '';
  			}
  		}
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
	//create paging end here
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataPegawai'] = $dataPegawai;
  		$return['start'] = $startRec+1;
        
      $return['search']['nip_nama'] = $nip_nama;
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
		  $this->mrTemplate->AddVar('content', 'NIP_NAMA', $search['nip_nama']);
		  
		  $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'BENEFIT CLAIM DATA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA KLAIM BENEFIT');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
       }
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_benefit', 'pegawai', 'view', 'html') );
            
      if (empty($data['dataPegawai'])) {
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('data_pegawai', 'PEGAWAI_EMPTY', 'NO');
  			$dataPegawai = $data['dataPegawai'];
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
          $dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_benefit','dataBenefit', 'view', 'html').'&dataId='. $idEnc;
          $dataPegawai[$i]['URL_HISTORY'] = Dispatcher::Instance()->GetUrl('data_benefit','historyDataBenefit', 'view', 'html').'&dataId='. $idEnc;
            
  				$this->mrTemplate->AddVars('data_pegawai_item', $dataPegawai[$i], '');
  				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
  			}
      }
   }
}
   

?>