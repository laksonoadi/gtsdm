<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/pangkat_golongan/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/pangkat_golongan.class.php';

class ViewPangkatGolongan extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/pangkat_golongan/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('view_pangkat_golongan.html');    
   } 
   
  function ProcessRequest() {
      $pg_obj = new PangkatGolongan();  
	  //create paging start here
	  
	   //print_r($_POST);
      if (isset($_POST['pangkatgolongan'])) $pangkatgolongan= $_POST['pangkatgolongan'];
      if(isset($_POST['check'])){
         $pg_check = $_POST['check']['pg'];
         $pPangkatGolongan = $pangkatgolongan;
      }else{
         $pPangkatGolongan = '';
      }
         
         
		$totalData = $pg_obj->GetCountPangkatGolongan();
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['pg'] = $pg_obj->GetListPangkatGolongan($pPangkatGolongan,$startRec, $itemViewed);
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$data['lang']=$lang;
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
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('pangkat_golongan', 'PangkatGolongan', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('pangkat_golongan', 'inputPangkatGolongan', 'view', 'html'));
      if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'GRADE');
      }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'PANGKAT/GOLONGAN');
      }
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['pg'])) {
         $this->mrTemplate->AddVar('data_pangkat_golongan', 'DATA_EMPTY', 'YES');
      } else {
         /*
         $label = "Manajemen Referensi";
			$urlDelete = Dispatcher::Instance()->GetUrl('pangkat_golongan', 'deletePangkatGolongan', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('pangkat_golongan', 'PangkatGolongan', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete,$urlReturn), Messenger::NextRequest);
         $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
         */
         $this->mrTemplate->AddVar('data_pangkat_golongan', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['pg']);
         foreach ($data['pg'] as $key => $value) {
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
			   $idEnc = Dispatcher::Instance()->Encrypt($value['pangkat']);
            $urlAccept = 'pangkat_golongan|deletePangkatGolongan|do|html';
            $urlKembali = 'pangkat_golongan|PangkatGolongan|view|html';
            if ($data['lang']=='eng'){
               $label = 'Grade Reference';
            }else{
               $label = 'Referensi Pangkat/Golongan'; 
            }
            $dataName = $value['pangkat'];
            $value['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('pangkat_golongan','inputPangkatGolongan', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['pangkat']);
            $this->mrTemplate->AddVars('data_pangkat_golongan_item', $value, 'PG_');
            $this->mrTemplate->parseTemplate('data_pangkat_golongan_item', 'a');
         }
      }
   }
}
?>
