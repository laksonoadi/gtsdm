<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pak_kegiatan/business/pak.class.php';

class ViewPak extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/pak_kegiatan/template/');
      $this->SetTemplateFile('view_pak.html');    
   } 
   
  function ProcessRequest() {
      $pak_obj = new Pak();  
	  
	  
	   //print_r($_POST);
      if (isset($_POST['penetapan'])) $pak= $_POST['penetapan'];
      if(isset($_POST['check'])){
         $pak_check = $_POST['check']['pak'];
         $pPak = $pak;
      }else{
         $pPak = '';
      }
         
    //create paging start here     
		$totalData = $pak_obj->GetCountPak();
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['pak'] = $pak_obj->GetListPak($pPak,$startRec, $itemViewed);
		
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
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('pak_kegiatan', 'Pak', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('pak_kegiatan', 'inputPak', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'TITLE', 'KEGIATAN ANGKA KREDIT DOSEN');
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['pak'])) {
         $this->mrTemplate->AddVar('data_pak', 'DATA_EMPTY', 'YES');
      } else {
         $label = "Manajemen Referensi";
		 $urlDelete = Dispatcher::Instance()->GetUrl('pak_kegiatan', 'deletePak', 'do', 'html');
		 $urlReturn = Dispatcher::Instance()->GetUrl('pak_kegiatan', 'Pak', 'view', 'html');
		 Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete,$urlReturn), Messenger::NextRequest);
         $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
         
         $this->mrTemplate->AddVar('data_pak', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['pak']);
         foreach ($data['pak'] as $key => $value) {
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
            $urlAccept = 'pak_kegiatan|deletePak|do|html';
            $urlKembali = 'pak_kegiatan|Pak|view|html';
            $label = 'Kegiatan Angka Kredit Dosen';
            $dataName = $value['nama'];
            $value['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('pak_kegiatan','inputPak', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']);
            $this->mrTemplate->AddVars('data_pak_item', $value, 'PAK_');
            $this->mrTemplate->parseTemplate('data_pak_item', 'a');
         }
      }
   }
}
?>
