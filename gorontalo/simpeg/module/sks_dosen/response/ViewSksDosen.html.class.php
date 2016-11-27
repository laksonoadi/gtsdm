<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/sks_dosen/business/'.GTFWConfiguration::GetValue('application',array('db_conn',0,'db_type')).'/sks_dosen.class.php';

class ViewSksDosen extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/sks_dosen/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
      $this->SetTemplateFile('view_sks_dosen.html');    
   } 
   
  function ProcessRequest() {
      $sks_obj = new SksDosen();  
	  
	  
	   //print_r($_POST);
      if (isset($_POST['tahun'])) $sksdosen= $_POST['tahun'];
      if(isset($_POST['check'])){
         $th_check = $_POST['check']['th'];
         $pSksDosen = $sksdosen;
      }else{
         $pSksDosen = '';
      }
         
    //create paging start here     
		$totalData = $sks_obj->GetCountSksDosen();
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['sks'] = $sks_obj->GetListSksDosen($pSksDosen,$startRec, $itemViewed);
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$data['lang']=$lang;  
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
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('sks_dosen', 'SksDosen', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('sks_dosen', 'inputSksDosen', 'view', 'html'));
      if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'TEACHING CREDIT');
      }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'SKS DOSEN');
      }
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['sks'])) {
         $this->mrTemplate->AddVar('data_sks', 'DATA_EMPTY', 'YES');
      } else {
         /*
         $label = "Referensi SKS DOSEN";
			$urlDelete = Dispatcher::Instance()->GetUrl('sks_dosen', 'deleteSksDosen', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('sks_dosen', 'SksDosen', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete,$urlReturn), Messenger::NextRequest);
         $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
         */
         
         $this->mrTemplate->AddVar('data_sks', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['sks']);
         foreach ($data['sks'] as $key => $value) {
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
            $urlAccept = 'sks_dosen|deleteSksDosen|do|html';
            $urlKembali = 'sks_dosen|SksDosen|view|html';
            if ($data['lang']=='eng'){
               $label = 'Teaching Credit';
               if ($value['status']=='Aktif'){
                  $value['status_label']='Active';
                  }
               else {
                   $value['status_label']='Inactive';
                   }
            }else{
               $label = 'Sks Dosen';
               if ($value['status']=='Aktif'){
                  $value['status_label']='Aktif';
                  }
               else {
                   $value['status_label']='Tidak Aktif';
                   } 
            }
            
            $dataName = $value['jabfung'];
            $value['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('sks_dosen','inputSksDosen', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']);
            $this->mrTemplate->AddVars('data_sks_item', $value, 'SD_');
            $this->mrTemplate->parseTemplate('data_sks_item', 'a');
         }
      }
   }
}
?>
