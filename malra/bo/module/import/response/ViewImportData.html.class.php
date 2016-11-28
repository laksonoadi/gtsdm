<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/import/business/ImportData.class.php';

class ViewImportData extends HtmlResponse {
	
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') . 'module/import/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('import_data.html');
	}
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
		
		$path_log=GTFWConfiguration::GetValue( 'application', 'file_save_path');
		$download_log=GTFWConfiguration::GetValue( 'application', 'file_download_path');
		$dir = dir($path_log);
		$i=0;
		while (false !== ($entry = $dir->read())) {
			if ((strpos('X'.$entry,'log_import')==1)&&($entry!='..')&&($entry!='.')){
				$filelog=fopen($path_log.$entry,"r");
				$datafile=fgets($filelog,1000);
				fclose($filelog);
				$detail=explode('#',$datafile);
				$data[$i]['downloadlog']=$download_log.$entry;
				$data[$i]['jenis']=$detail[1];
				$data[$i]['tanggal']=$detail[2];
				$data[$i]['user']=$detail[3];
				$i++;
			}
		}
		
		return $data;
	}

	function ParseTemplate($data = NULL) {
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		$this->mrTemplate->AddVar('content','URL_ACTION',Dispatcher::Instance()->GetUrl('import','importData','do','html'));
		
		if(empty($data)){
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
		}else{
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
			
			for ($i=0; $i<sizeof($data); $i++){
				for ($j=$i; $j<sizeof($data); $j++){
					if ($data[$i]['tanggal']<$data[$j]['tanggal']){
						$temp=$data[$i];
						$data[$i]=$data[$j];
						$data[$j]=$temp;
					}
				}
			}
			for ($i=0; $i<sizeof($data); $i++){
				$data[$i]['number']=$i+1;
				$this->mrTemplate->AddVars('data_item', $data[$i], '');
				$this->mrTemplate->parseTemplate('data_item', 'a');
           
			}
		}
		
	}
}
?>
