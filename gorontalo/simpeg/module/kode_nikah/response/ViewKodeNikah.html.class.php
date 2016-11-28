<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/kode_nikah.class.php';

class ViewKodeNikah extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/kode_nikah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_kode_nikah.html');    
   } 
   
  function ProcessRequest() {
      $kn_obj = new KodeNikah();  
	  
	  
	   //print_r($_POST);
      if (isset($_POST['namanikah'])) $kodenikah= $_POST['namanikah'];
      if(isset($_POST['check'])){
         $kn_check = $_POST['check']['kn'];
         $pKodeNikah = $kodenikah;
      }else{
         $pKodeNikah = '';
      }
         
    //create paging start here     
		$totalData = $kn_obj->GetCountKodeNikah();
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['kn'] = $kn_obj->GetListKodeNikah($pKodeNikah,$startRec, $itemViewed);
		//print_r($data['jabstruk']);
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule, 
		Dispatcher::Instance()->mSubModule, 
		Dispatcher::Instance()->mAction, 
		Dispatcher::Instance()->mType);
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), 
		Messenger::CurrentRequest);
	//create paging end here
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $data['pesan'] = $msg[0][1];
      $data['css'] = $msg[0][2];
      $data['start'] = $startRec+1;
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'MARRIAGE CODE REFERENCE');
           $label = "Marriage Code Reference";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI KODE NIKAH');
           $label = "Referensi Kode Nikah";
       }
      
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('kode_nikah', 'KodeNikah', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('kode_nikah', 'inputKodeNikah', 'view', 'html'));
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['kn'])) {
         $this->mrTemplate->AddVar('data_kode_nikah', 'DATA_EMPTY', 'YES');
      } else { 
			$urlDelete = Dispatcher::Instance()->GetUrl('kode_nikah', 'deleteKodeNikah', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('kode_nikah', 'KodeNikah', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete,$urlReturn), Messenger::NextRequest);
         $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
         
         $this->mrTemplate->AddVar('data_kode_nikah', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['kn']);
         foreach ($data['kn'] as $key => $value) {
            $no = $key + $data['start'];
			
			if($key == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
			if($key == ($len-1)) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);				
			$value['NUMBER'] = $no;
			
            $value['no'] = $no;
            if ($no % 2 != 0) {
               $value['class_name'] = 'table-common-even';
            } else {
               $value['class_name'] = '';
            }       
			   $idEnc = Dispatcher::Instance()->Encrypt($value['id']);
            $urlAccept = 'kode_nikah|deleteKodeNikah|do|html';
            $urlKembali = 'kode_nikah|KodeNikah|view|html';
            $dataName = $value['nama'];
            $value['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('kode_nikah','inputKodeNikah', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']);
            $this->mrTemplate->AddVars('data_kode_nikah_item', $value, 'KN_');
            $this->mrTemplate->parseTemplate('data_kode_nikah_item', 'a');
         }
      }
   }
}
?>
