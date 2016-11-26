<?php

class DoConfirmDelete extends HtmlResponse {
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .
			'module/confirm/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('confirm_delete.html');
	}
	function ProcessRequest() {
		if(!empty($_POST['id'])) {
			$_POST = $_POST->AsArray();
			$msg = Messenger::Instance()->Receive(__FILE__);
			$return[0]['label'] = $msg[0][0];
			$return[0]['urlDelete'] = $msg[0][1];
			$return[0]['urlReturn'] = $msg[0][2];
         //if(!empty($_POST['is_satker']))
           // $return[0]['message'] = $msg[0][3];

			for($i=0;$i<sizeof($_POST['id']);$i++) {
				$return[$i]['id'] = $_POST['id'][$i];
				$return[$i]['dataname'] = $_POST['name'][$_POST['id'][$i]];
				if(!empty($_POST['periode'])){
          $return[$i]['periode'] = $_POST['periode'][$i];
        }
        
            if($_POST['is_parent'][$_POST['id'][$i]]) 
               $return[0]['message'] = "<br />" . $msg[0][3];
			}
			$return[0]['multiple'] = "YES";
			$return[0]['emptydata'] = "NO";
			//print_r($msg);

		} elseif(trim($_GET['id']) != "") {

			$return[0]['id'] = Dispatcher::Instance()->Decrypt((string)$_GET['id']);
			$return[0]['tglId'] = Dispatcher::Instance()->Decrypt((string)$_GET['tglId']);
			$return[0]['dataname'] = Dispatcher::Instance()->Decrypt($_GET['dataName']);
			$return[0]['message'] = Dispatcher::Instance()->Decrypt($_GET['message']);
			
			$return[0]['multiple'] = "NO";
			$return[0]['emptydata'] = "NO";

			$deleteUrl = Dispatcher::Instance()->Decrypt((string)$_GET['urlDelete']);	 
			$urlDel = explode('-',$deleteUrl); //-> Array ( [0] => unitkerja|deleteUnitkerja|do|html [1] => cari [2] => )
			$newUrl = explode('|',$urlDel['0']); //-> Array ( [0] => unitkerja [1] => deleteUnitkerja [2] => do [3] => html )
			$par = explode('|',$urlDel['1']); //->Array ( [0] => cari )
			$val = explode('|',$urlDel['2']); //->Array ( [0] => )
			for($i=0;$i<count($par);$i++){
				$str .= '&'.$par[$i].'='.Dispatcher::Instance()->Encrypt($val[$i]);
			}
			$return[0]['urlDelete'] = Dispatcher::Instance()->GetUrl($newUrl['0'],$newUrl['1'],$newUrl['2'],$newUrl['3']).$str;

			$returnUrl = Dispatcher::Instance()->Decrypt((string)$_GET['urlReturn']);
			$urlRet = explode('-',$returnUrl);
			$newUrl = explode('|',$urlRet['0']);
			$par = explode('|',$urlRet['1']);
			$val = explode('|',$urlRet['2']);
			$str ='';
			for($i=0;$i<count($par);$i++) {
				$str .= '&'.$par[$i].'='.Dispatcher::Instance()->Encrypt($val[$i]);
			}
			//$aa = Dispatcher::Instance()->Encrypt($val[0]);
			$return[0]['urlReturn'] = Dispatcher::Instance()->GetUrl($newUrl['0'],$newUrl['1'],$newUrl['2'],$newUrl['3']).$str;
      //$return[0]['urlDel'] = $aa;
			$return[0]['label'] = Dispatcher::Instance()->Decrypt((string)$_GET['label']);

		} else {
			$msg = Messenger::Instance()->Receive(__FILE__);
			$return[0]['label'] = $msg[0][0];
			$return[0]['urlDelete'] = $msg[0][1];
			$return[0]['urlReturn'] = $msg[0][2];
			//$this->RedirectTo($return[0]['urlReturn']);
			$return[0]['emptydata'] = "YES";
		}
		return $return;
	 }
	
	function ParseTemplate($data = NULL) {
		#print_r($data);
		$this->mrTemplate->AddVar('content', 'LABEL', $data[0]['label']);
		$this->mrTemplate->AddVar('emptydata', 'MESSAGE', $data[0]['message']);
		$this->mrTemplate->AddVar('emptydata', 'LABEL', $data[0]['label']);
		$this->mrTemplate->AddVar('emptydata', 'FORM_ACTION_URL', $data[0]['urlDelete']);
		//$this->mrTemplate->AddVar('emptydata', 'URL', $data[0]['urlDel']);
		$this->mrTemplate->AddVar('emptydata', 'URL_KEMBALI', $data[0]['urlReturn']);
		if($data[0]['emptydata'] == "NO") {
			$this->mrTemplate->AddVar('emptydata', 'IS_EMPTY_DATA', 'NO');
			if($data[0]['multiple'] == "YES") {
				$this->mrTemplate->AddVar('multiple_delete', 'IS_MULTIPLE_DELETE', 'YES');
				for($i=0;$i<sizeof($data);$i++) {
					$this->mrTemplate->AddVars('multiple_delete_item', $data[$i], 'MULTI_');
					$this->mrTemplate->parseTemplate('multiple_delete_item', 'a');	 
				}
			} else {
				$this->mrTemplate->AddVar('multiple_delete', 'IS_MULTIPLE_DELETE', 'NO');
				$this->mrTemplate->AddVar('multiple_delete', "ID", $data[0]['id']);
        $this->mrTemplate->AddVar('multiple_delete', "TGL", $data[0]['tglId']);		
				$this->mrTemplate->AddVar('multiple_delete', 'DATANAME', $data[0]['dataname']);  
			}
		} else {
			$this->mrTemplate->AddVar('emptydata', 'IS_EMPTY_DATA', 'YES');
		}
	}
}
?>
