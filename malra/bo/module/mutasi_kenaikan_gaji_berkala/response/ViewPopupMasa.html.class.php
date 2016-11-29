<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_kenaikan_gaji_berkala/business/AppPopupMasa.class.php';

class ViewPopupMasa extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/mutasi_kenaikan_gaji_berkala/template');
		$this->SetTemplateFile('view_popup_masa.html');
	}
   
    function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
	
	function ProcessRequest() {
		$Obj = new AppPopupMasa();
		if(isset($_GET['cari'])) {
			if(isset($_GET['masa'])) {
				$masa = Dispatcher::Instance()->Decrypt($_GET['masa']);
			} else {
				$masa = '';
			}
		}else{
      $masa = Dispatcher::Instance()->Decrypt($_GET['idMasa']);
    }
		
		//print_r($masa);
	//view
		$totalData = $Obj->GetCountData($masa);
		//print_r($totalData);
		$itemViewed = 5;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataMasa = $Obj->getData($startRec, $itemViewed, $masa);
		//print_r($dataMasa);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&masa=' . Dispatcher::Instance()->Encrypt($masa). '&cari=' . Dispatcher::Instance()->Encrypt(1));
		$dest = "popup-subcontent";
		Messenger::Instance()->SendToComponent('paging2', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage, $dest), Messenger::CurrentRequest);
		
		
		//print_r($dataMasa);
		
		$return['dataMasa'] = $dataMasa;
		$return['start'] = $startRec+1;

		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		  if(empty($data['dataMasa'])) {
			$this->mrTemplate->AddVar('data_masa', 'MASA_EMPTY', "YES");
      } else {
			$this->mrTemplate->AddVar('data_masa', 'MASA_EMPTY', "NO");
         $dataMasa = $data['dataMasa'];
         for ($i=0; $i<sizeof($dataMasa); $i++) {
            $no = $i+$data['start'];
            $dataMasa[$i]['number'] = $no;
            if ($no % 2 != 0) $dataMasa[$i]['class_name'] = 'table-common-even';
            else $dataMasa[$i]['class_name'] = '';
            
            $dataMasa[$i]['nominal1'] = "Rp. " . number_format($dataMasa[$i]['nominal'], 2, ',', '.');
            $this->mrTemplate->AddVars('data_masa_item', $dataMasa[$i], 'DATA_');
            $this->mrTemplate->parseTemplate('data_masa_item', 'a');	 
         }
      }
	}
}
?>
