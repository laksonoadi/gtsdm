<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class ViewListPegawaiVerified extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_list_pegawai.html');    
    } 
    
    function ProcessRequest() {
		$Obj = new DataPegawai;
		$convert=array('15'=>15,'25'=>25,'50'=>50,'100'=>100,'250'=>250);
		if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['cari'])) {
				$this->POST=$_POST->AsArray();
  				$nip_nama = $_POST['nip_nama'];
				$status_kerja = $_POST['status_kerja'];
				$itemViewed = $convert[$this->POST['item_viewed']];
  			} elseif(isset($_GET['cari'])) {
  				$nip_nama = Dispatcher::Instance()->Decrypt($_GET['nip_nama']->Raw());
				$status_kerja = Dispatcher::Instance()->Decrypt($_GET['status_kerja']->Raw());
				$itemViewed = Dispatcher::Instance()->Decrypt($_GET['item_viewed']->Raw());
  			} else {
  				$nip_nama = '';
				$status_kerja = 'all';  
				$itemViewed = 15;  
  			}
  		}
		
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$data['judul'] = $msg[0][0];
		$data['url_search'] = $msg[0][1];
		$data['url_select'] = $msg[0][2];
		
		$arrStatusKerja = $Obj->GetComboStatPeg();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_kerja', array('status_kerja', $arrStatusKerja, $status_kerja, 'true', ''), Messenger::CurrentRequest);
		$arrItemViewed=array(
							array('id'=>15, 'name'=>'15 Data'),
							array('id'=>25, 'name'=>'25 Data'),
							array('id'=>50, 'name'=>'50 Data'),
							array('id'=>100, 'name'=>'100 Data'),
							array('id'=>250, 'name'=>'250 Data')
						);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'item_viewed', array('item_viewed', $arrItemViewed, $itemViewed, '', ''), Messenger::CurrentRequest);
		
        //create paging 
		$totalData = $Obj->GetCountPegawaiByUserIdVerified($nip_nama, $status_kerja);
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$data['list'] = $Obj->GetDataPegawaiByUserIdVerified($startRec, $itemViewed, $nip_nama, $status_kerja);
		
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
		'&status_kerja=' . Dispatcher::Instance()->Encrypt($status_kerja).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here
      
		$msg = Messenger::Instance()->Receive(__FILE__);
		$data['pesan'] = $msg[0][1];
		$data['css'] = $msg[0][2];
		$data['start'] = $startRec+1;

		//set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$labeldel=Dispatcher::Instance()->Encrypt($data['judul']);
		$data['lang']=$lang;
		$data['keyword']=$nip_nama;
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'TITLE', $data['judul']);


		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['url_search'] );
      
		if($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
      
		if (empty($data['list'])) {
			$this->mrTemplate->AddVar('data_list_pegawai', 'DATA_EMPTY', 'YES');
		} else {      
			$this->mrTemplate->AddVar('data_list_pegawai', 'DATA_EMPTY', 'NO');
			$len = sizeof($data['list']);
			foreach ($data['list'] as $key => $value) {
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
          
				if(empty($value['jabstruk'])){
					$value['POSISI'] = $value['jabfung'];
				}elseif(empty($value['jabfung'])){
					$value['POSISI'] = $value['jabstruk'];
				}elseif(!empty($value['jabfung']) and !empty($value['jabstruk'])){
					$value['POSISI'] = $value['jabstruk'].' - '.$value['jabfung'];
				}else{
					$value['POSISI'] = "";
				}
				$value['URL_DETAIL'] = $data['url_select'].'&id='.Dispatcher::Instance()->Encrypt($value['id']);
				$this->mrTemplate->AddVars('data_list_pegawai_item', $value, 'DATA_');
				$this->mrTemplate->parseTemplate('data_list_pegawai_item', 'a');
			}
		}
    }
}
?>