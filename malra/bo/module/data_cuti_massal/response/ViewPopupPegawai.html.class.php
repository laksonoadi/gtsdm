<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/popup_pegawai.class.php';

class ViewPopupPegawai extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'].'module/data_cuti_massal/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_popup_pegawai.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$Obj = new PopupPegawai();
		if(isset($_GET['dataSatker'])) {
			//$dataSatker = $_GET['dataSatker']->Integer()->Raw();
			$dataSatker = Dispatcher::Instance()->Decrypt($_GET['dataSatker']);
		}
		if(isset($_GET['dataPeg'])) {
			//$dataPeg = $_GET['dataPeg']->Integer()->Raw();
			$dataPeg = Dispatcher::Instance()->Decrypt($_GET['dataPeg']);
		}
	//print_r($_GET['dataSatker']);
	
    if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['nama'])) {
				$nama = $_POST['nama'];
				$dataSatker = $_POST['dataSatker'];
				$dataPeg = $_POST['dataPeg'];
				//print_r('POST');
			} elseif(isset($_GET['nama'])) {
				$nama = Dispatcher::Instance()->Decrypt($_GET['nama']);
				//$dataSatker = $_GET['dataSatker']->Integer()->Raw();
				$dataSatker = Dispatcher::Instance()->Decrypt($_GET['dataSatker']);
				//$dataPeg = $_GET['dataPeg']->Integer()->Raw();
				$dataPeg = Dispatcher::Instance()->Decrypt($_GET['dataPeg']);
				//print_r('GET');
			} else {
				$nama = '';
			}
		}
		
		$return['dataSatker'] = $dataSatker;
		$return['dataPeg'] = $dataPeg;
		//print_r(' -awal, sat='.$dataSatker.' peg='.$dataPeg.'- ||');
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.
		'&nama='.Dispatcher::Instance()->Encrypt($nama).
		'&dataPeg='.Dispatcher::Instance()->Encrypt($dataPeg).
		'&dataSatker='.Dispatcher::Instance()->Encrypt($dataSatker).
		'&cari='.Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
		
		//print_r(' -kedua, sat='.$dataSatker.' peg='.$dataPeg.'- ||');
		if($dataPeg=="B"){
			$dataSatker=$dataSatker;
			//print_r(' -Ketiga B, sat='.$dataSatker.' peg='.$dataPeg.'- ||');
		}elseif($dataPeg=="A"){
			//view MOR
			//pencarian atasannya
			$levelPeg = $Obj->GetLevelPeg($dataSatker);
			$levelAtasan = explode('.',$levelPeg[0]['level']);
			$levelJml = sizeof($levelAtasan)-1;
			//print_r(' *Level='.$levelJml.'* ');
			if($levelJml != 0){
				$level = "";
				for($i=0;$i<$levelJml;$i++){
					if($level==""){
						$level=$levelAtasan[$i];
					}else{
						$level.='.'.$levelAtasan[$i];
					}
				}
			}else{
				$level = $levelAtasan[0];
			}
			//print_r(' *Level 2='.$level.'* ');
			
			$cariAtasan = $Obj->GetSatkerAtasan($level);
			$dataSatker=$cariAtasan[0]['idSatker'];
			//print_r(' -Ketiga A, sat='.$dataSatker.' peg='.$dataPeg.'- ||');
		}
		
		$totalData = $Obj->GetCountData($nama,$dataSatker);
		$itemViewed = 15;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataPegawai = $Obj->getData($startRec, $itemViewed,$nama,$dataSatker);
		Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage, $dest), Messenger::CurrentRequest);
		
		//print_r(' -terakhir, sat='.$dataSatker.' peg='.$dataPeg.'- ||');
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
				
		$return['dataPegawai'] = $dataPegawai;
		$return['cariAtasan'] = $cariAtasan;
		//print_r($dataKomponen);
		$return['start'] = $startRec+1;
		$return['search']['nama'] = $nama;
		
		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		$search = $data['search'];
		$this->mrTemplate->AddVar('content', 'NAMA', $search['nama']);
		$this->mrTemplate->AddVar('content', 'PEG', $data['dataPeg']);
		$this->mrTemplate->AddVar('content', 'SAT', $data['dataSatker']);
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html'));
		
		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		
		$cariData = $data['dataPegawai'];
		
		//print_r($cariData);
	  
		if(empty($cariData)) {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "YES");
		} else {
			$this->mrTemplate->AddVar('data_pegawai', 'DATA_EMPTY', "NO");
			for ($i=0; $i<sizeof($cariData); $i++) {
				$no = $i+$data['start'];
				$cariData[$i]['number'] = $no;
				if ($no % 2 != 0) $cariData[$i]['class_name'] = 'table-common-even';
				else $cariData[$i]['class_name'] = '';
				$cariData[$i]['data'] = $data['dataPeg'];
				$cariData[$i]['set_parent'] ='<a href="javascript:void(0)" onclick="addPegawaiItem(this, \''.$cariData[$i]['id'].'\',\''.$cariData[$i]['kode'].'\',\''.$cariData[$i]['nama'].'\')" onmouseover="status=\'Set preferences...\';return true" ><img src="images/button-check.gif" alt="Pilih"/></a>';
			    $this->mrTemplate->AddVars('data_pegawai_item', $cariData[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_pegawai_item', 'a');	 
			}
		}
	}
}
?>
