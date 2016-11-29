<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tunjangan_kesehatan/business/tunjangan_kesehatan.class.php';
  
class ViewTunjanganKesehatan extends HtmlResponse
{
   var $Pesan;
   
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/tunjangan_kesehatan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_tunjangan_kesehatan.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new TunjanganKesehatan();
      if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['jenis_tunj'])) {
  				$jenis_tunj = $_POST['jenis_tunj'];
  			} elseif(isset($_GET['jenis_tunj'])) {
  				$jenis_tunj = Dispatcher::Instance()->Decrypt($_GET['jenis_tunj']);
  			} else {
  				$jenis_tunj = '';
  			}
  		}
         
      $totalData = $Obj->GetCountData($jenis_tunj);
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataTunjang = $Obj->GetData($startRec, $itemViewed, $jenis_tunj);
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&jenis_tunj=' . Dispatcher::Instance()->Encrypt($jenis_tunj).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	//create paging end here
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataTunjang'] = $dataTunjang;
  		$return['start'] = $startRec+1;
        
      $return['search']['jenis_tunj'] = $jenis_tunj;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $label="Health Benefits Reference";
           $this->mrTemplate->AddVar('content', 'TITLE', 'HEALTH BENEFITS REFERENCE');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
       }else{
           $label="Referensi Tunjangan Kesehatan";
           $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI TUNJANGAN KESEHATAN');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
       }
      
      $search = $data['search'];
		  $this->mrTemplate->AddVar('content', 'JENIS_TUNJ', $search['jenis_tunj']);
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'inputTunjanganKesehatan', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html') );
            
      if (empty($data['dataTunjang'])) {
  			$this->mrTemplate->AddVar('data_tunjang', 'TUNJANG_EMPTY', 'YES');
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('data_tunjang', 'TUNJANG_EMPTY', 'NO');
  			$dataTunjang = $data['dataTunjang'];
  
  //mulai bikin tombol delete
  			$urlDelete = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'deleteTunjanganKesehatan', 'do', 'html');
  			$urlReturn = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan', 'tunjanganKesehatan', 'view', 'html');
  			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
  			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
        $total=0;
  			for ($i=0; $i<sizeof($dataTunjang); $i++) {
  				$no = $i+$data['start'];
  				$dataTunjang[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataTunjang[$i]['class_name'] = 'table-common-even';
          }else{
            $dataTunjang[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataTunjang)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataTunjang[$i]['id']);
          
          $urlAccept = 'tunjangan_kesehatan|deleteTunjanganKesehatan|do|html';
          $urlKembali = 'tunjangan_kesehatan|TunjanganKesehatan|view|html';
          $dataName = $dataTunjang[$i]['nama'];
          $dataTunjang[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataTunjang[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan','inputTunjanganKesehatan', 'view', 'html').'&dataId='. $idEnc;
          //$dataTunjang[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('tunjangan_kesehatan','detailTunjanganKesehatan', 'view', 'html').'&dataId='. $idEnc;
          
          $dataTunjang[$i]['pla_uang'] = number_format($dataTunjang[$i]['pla_uang'], 2, ',', '.');
  				$this->mrTemplate->AddVars('data_tunjang_item', $dataTunjang[$i], '');
  				$this->mrTemplate->parseTemplate('data_tunjang_item', 'a');	 
  			}
      }
   }
}
   

?>