<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/jabatan_struktural.class.php';

class ViewJabatanStruktural extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/jabatan_struktural/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('view_jabatan_struktural.html');    
   } 
   
  function ProcessRequest() {
      $jabstruk_obj = new JabatanStruktural();  
	  
	  
	   //print_r($_POST);
      if (isset($_POST['struktural'])) $jabstruk= $_POST['struktural'];
      if(isset($_POST['check'])){
         $js_check = $_POST['check']['js'];
         $pJabstruk = $jabstruk;
      }else{
         $pJabstruk = '';
      }
         
    //create paging start here     
		
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['jabstruk'] = $jabstruk_obj->GetListJabstruk($pJabstruk,$startRec, $itemViewed);
      $totalData = $jabstruk_obj->GetCountJabstruk($pJabstruk,$startRec, $itemViewed);
		//print_r($data['jabstruk']);
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
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'JabatanStruktural', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('jabatan_struktural', 'inputJabatanStruktural', 'view', 'html'));
      if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'STRUCTURAL POSITION');
      }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'JABATAN STRUKTURAL');
      }
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['jabstruk'])) {
         $this->mrTemplate->AddVar('data_jabstruk', 'DATA_EMPTY', 'YES');
      } else {
         /*
         $label = "Manajemen Referensi";
			$urlDelete = Dispatcher::Instance()->GetUrl('jabatan_struktural', 'deleteJabatanStruktural', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('jabatan_struktural', 'JabatanStruktural', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete,$urlReturn), Messenger::NextRequest);
         $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
         */
         
         $this->mrTemplate->AddVar('data_jabstruk', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['jabstruk']);
         foreach ($data['jabstruk'] as $key => $value) {
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
            $urlAccept = 'jabatan_struktural|deleteJabatanStruktural|do|html';
            $urlKembali = 'jabatan_struktural|JabatanStruktural|view|html';
            if ($data['lang']=='eng'){
               $label = 'Structural Position Reference';
            }else{
               $label = 'Referensi Jabatan Struktural'; 
            }
            $dataName = $value['nama'];
            $value['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('jabatan_struktural','inputJabatanStruktural', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']);
            $value['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('jabatan_struktural','DetailJabatanStruktural', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']);            
            $this->mrTemplate->AddVars('data_jabstruk_item', $value, 'JS_');
            $this->mrTemplate->parseTemplate('data_jabstruk_item', 'a');
         }
      }
   }
}
?>
