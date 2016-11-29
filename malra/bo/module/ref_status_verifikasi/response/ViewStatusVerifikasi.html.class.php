<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/ref_status_verifikasi/business/status_verifikasi.class.php';

class ViewStatusVerifikasi extends HtmlResponse{

	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/ref_status_verifikasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_status_verifikasi.html');
	}
  
	function ProcessRequest(){
		$statusVerifikasi = new StatusVerifikasi;
		// inisialisasi messaging
		$msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
		// ---------
		$id = $_GET['id']->Integer()->Raw();
		if(isset($_GET['id'])){
			$result = $statusVerifikasi->GetDataById($id);
			if($result){
				$return['input']['verstatName'] = $result['verstatName'];
				$return['input']['verstatIsApproved'] = $result['verstatIsApproved'];
				$return['input']['verstatIcon'] = $result['verstatIcon'];
				$return['input']['verstatId'] = $result['verstatId'];
			}else{
				unset($_GET['id']);
			}
		}else{
			$return['input']['verstatName'] = '';
			$return['input']['verstatIsApproved'] = '';
			$return['input']['verstatIcon'] = '';
			$return['input']['verstatId'] = '';
		}
		       
		$pegId = $_GET['pegId']->Integer()->Raw();
    
		$return['pegId'] = $pegId;
    
		//inisialisasi paging
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
    
		if(isset($_GET['page'])){
			$currPage = $_GET['page']->Integer()->Raw();
			if ($currPage > 0){
				$startRec =($currPage-1) * $itemViewed;
			}else{
				$currPage = 1;
			}
		}
    
		$return['start'] = $startRec+1;
		$totalData = $statusVerifikasi->GetCount($return['cari']);
		$url = Dispatcher::Instance()->GetUrl('ref_status_verifikasi','statusVerifikasi','view','html').'&cari='.$return['cari'];
		if (isset($_GET['id'])){ 
			$url .= '&id='.$id.'&pegId='.$pegId;
		}

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//paging end here
    
		$return['link']['url_action'] = Dispatcher::Instance()->GetUrl('ref_status_verifikasi','inputStatusVerifikasi','do','html');
		if (isset($_GET['id'])){ 
			$return['link']['url_action'] .= '&id='.$id .'&pegId='.$pegId; 
		}
    
		$return['link']['url_search'] = Dispatcher::Instance()->GetUrl('ref_status_verifikasi','statusVerifikasi','view','html').'&cutiperAwalSearch='.$return['cutiperAwalSearch'].'&cutiperAkhirSearch='.$return['cutiperAkhirSearch'];
    
		//print_r($return['link']['url_search']);die;
    
		$return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('ref_status_verifikasi','statusVerifikasi','view','html');
	
		if (isset($_GET['page'])){
			$return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
		}
    
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($lang=='eng'){
			$labeldel=Dispatcher::Instance()->Encrypt('Verification Status');
		}else{
			$labeldel=Dispatcher::Instance()->Encrypt('Status Verifikasi');
		}
		$return['lang']=$lang; 
         
		$return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('ref_status_verifikasi|deleteStatusVerifikasi|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('ref_status_verifikasi|statusVerifikasi|view|html').
		"&label=".$labeldel;
		$return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('ref_status_verifikasi', 'deleteStatusVerifikasi', 'do', 'html');
    
		$return['dataSheet'] = $statusVerifikasi->GetData($startRec,$itemViewed);
		
		return $return;
	}
  
	function ParseTemplate($data = NULL){
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
    
		//tentukan value judul, button dll sesuai pilihan bahasa 
		if ($data['lang']=='eng'){
			$this->mrTemplate->AddVar('content', 'TITLE', 'VERIFICATION STATU');
			$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Verification Status Data');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
		}else{
			$this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI STATUS VERIFIKASI');
			$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Status Verifikasi');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
		} 
    
		$this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);
		$this->mrTemplate->AddVars('content', $data['input'],'');
		
		if($data['input']['verstatIsApproved'] == '1'){
			$this->mrTemplate->AddVar('content', 'VERSTATISAPPROVED_CHECKED', 'checked="checked"');
		} else{
			$this->mrTemplate->AddVar('content','VERSTATISAPPROVED_CHECKED','');
		} 
				
		$this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
		//$this->mrTemplate->AddVar('content', 'PEG_ID', $data['perId']); 
		// ---------
		//print_r($data['dataSheet']);die;
		if(empty($data['dataSheet'])){
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
			return NULL;
		}else{
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
		}
		$i = $data['start'];
		$link = $data['link'];
		
		foreach ($data['dataSheet'] as $value){
			$data = $value; #print_r($data['periodecutiId']);die;
			$data['number'] = $i;
			$data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
			$data['url_edit'] = $link['url_edit'].'&id='.$data['verstatId'];
			$data['url_delete'] = $link['url_delete'].
			"&id=".Dispatcher::Instance()->Encrypt($data['verstatId']);
			$data['url_delete_js'] = $link['url_delete_js'];
			$this->mrTemplate->AddVars('data_item', $data, '');
			if($data['verstatIsApproved'] == '1'){
				$this->mrTemplate->AddVar('data_item', 'VERSTATISAPPROVED', '<b>Approved</b>');
			} else {
				$this->mrTemplate->AddVar('data_item', 'VERSTATISAPPROVED', '<i>-</i>');
			}
     
			$this->mrTemplate->parseTemplate('data_item', 'a');
			$i++;
		}
	}
  
	function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}

?>