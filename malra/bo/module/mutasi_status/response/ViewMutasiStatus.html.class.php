<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_status/business/mutasi_status.class.php';

class ViewMutasiStatus extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/mutasi_status/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_mutasi_status.html');
	}
      
	function ProcessRequest() {
		$pg = new MutasiStatus();
      
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
		$this->profilId=$id;
      
		$arrstatr = $pg->GetComboStatus();
      
		$tahun=array();
		if(isset($_GET['id'])){
			$dataPegawai = $pg->GetDataDetail($id);
			$dataStatr = $pg->GetListMutasiStatus($id);
			if(isset($_GET['dataId'])){
				$dataMutasi = $pg->GetDataMutasiById($id,$dataId);
				$result=$dataMutasi[0];
				if(!empty($result)){
					$return['input']['id'] = $result['id'];
					$return['input']['nip'] = $result['nip'];
					$return['input']['statr'] = $result['statr'];
					$return['input']['tmt'] = $result['tmt'];
					$return['input']['pejabat'] = $result['pejabat'];
					$return['input']['nosk'] = $result['nosk'];
					$return['input']['tgl_sk'] = $result['tgl_sk'];
					$return['input']['status'] = $result['status'];
					$return['input']['upload'] = $result['upload'];
				}    
			}else{
				$return['input']['id'] = '';
				$return['input']['nip'] = '';
				$return['input']['statr'] = '';
				$return['input']['tmt'] = date("Y-m-d");
				$return['input']['pejabat'] = '';
				$return['input']['nosk'] = '';
				$return['input']['tgl_sk'] = date("Y-m-d");
				$return['input']['status'] = '';
				$return['input']['upload'] = '';
				$return['input']['pktgol'] = '';
			}
		}
       
		if(empty($tahun['start'])){
			$tahun['start']=date("Y")-25;
		}        	

		$tahun['end'] = date("Y")+5;

		//set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($lang=='eng'){
			$labeldel=Dispatcher::Instance()->Encrypt('Working Unit Mutation');
			$active = "Active"; $inactive = "Inactive";
		}else{
			$labeldel=Dispatcher::Instance()->Encrypt('Mutasi Kedudukan Hukum');
			$active = "Aktif"; $inactive = "Tidak Aktif";
		}
		$return['lang']=$lang;
    
       
		Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tmt', array($return['input']['tmt'], $tahun['start'], $tahun['end'], '', '', 'tmt'), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
		$return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'statr', array('statr', $arrstatr, $return['input']['statr'], 'false', ' style="width:200px;"  onchange="setDS()" '), Messenger::CurrentRequest);
		$list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status" style="width:200px;"  '), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
		$return['dataPegawai'] = $dataPegawai;
		$return['dataStatr'] = $dataStatr;
		return $return;  
	}
      
	function ParseTemplate($data = NULL){
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      
		$dataPegawai = $data['dataPegawai'];
		$dataStatr = $data['dataStatr'];
      
		if ($data['lang']=='eng'){
			$this->mrTemplate->AddVar('content', 'TITLE', 'STATUS MUTATION');
			$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
		}else{
			$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI KEDUDUKAN HUKUM');
			$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
		} 

		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
     
		if ( isset($_GET['dataId'])) {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_status', 'updateMutasiStatus', 'do', 'html'));
		}else{
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_status', 'addMutasiStatus', 'do', 'html'));
		}
      
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_status', 'Pegawai', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_status', 'MutasiStatus', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
		$this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
		$this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
		$this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
			$this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
		}else{
			$this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
		}
    
     
       
		if(!empty($data['input'])){
			$data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
			$this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
		}
      
		if (empty($dataStatr)) {
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
		} else {
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
			$label = "Manajemen Data Mutasi Status";
			$urlDelete = Dispatcher::Instance()->GetUrl('mutasi_status', 'deleteMutasiStatus', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('mutasi_status', 'MutasiStatus', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

			$total=0;
			$start=1;
			for ($i=0; $i<count($dataStatr); $i++) {
				$no = $i+$start;
				$dataStatr[$i]['number'] = $no;
				if ($no % 2 != 0) {
					$dataStatr[$i]['class_name'] = 'table-common-even';
				}else{
					$dataStatr[$i]['class_name'] = '';
				}

				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
				$idEnc = Dispatcher::Instance()->Encrypt($dataStatr[$i]['id']);
				$urlAccept = 'mutasi_status|deleteMutasiStatus|do|html-id-'.$dataPegawai[0]['id'];
				$urlKembali = 'mutasi_status|MutasiStatus|view|html-id-'.$dataPegawai[0]['id'];
				$label = 'Data Mutasi Status';
				$dataName = $dataStatr[$i]['statrnama'];
				$dataStatr[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
				$dataStatr[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_status','MutasiStatus', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				$dataStatr[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_status','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				$dataStatr[$i]['tmt'] = $this->date2string($dataStatr[$i]['tmt']);
				$dataStatr[$i]['tgl_sk'] = $this->date2string($dataStatr[$i]['tgl_sk']);
				if (!empty($dataStatr[$i]['upload'])){
					$dataStatr[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataStatr[$i]['upload'];
				}else{
					$dataStatr[$i]['LINK_DOWNLOAD_SK'] = '';
					$dataStatr[$i]['VIEW_DOWNLOAD'] = 'none';
				}
         
				if(($dataStatr[$i]['status']=='Aktif')&&($data['lang']=='eng')) {
					$dataStatr[$i]['status']="Active";
				} elseif(($dataStatr[$i]['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
					$dataStatr[$i]['status']="Inactive";
				} else {
					$dataStatr[$i]['status']=$dataStatr[$i]['status'];
				}

				$this->mrTemplate->AddVars('data_item', $dataStatr[$i], 'STATR_');
				$this->mrTemplate->parseTemplate('data_item', 'a');	 
			}
		}
	}
      
	function date2string($date) {
		$bln = array(
					1  => '01',
			   		2  => '02',
				  	3  => '03',
					  4  => '04',
					  5  => '05',
					  6  => '06',
					  7  => '07',
					  8  => '08',
					  9  => '09',
					  10 => '10',
					  11 => '11',
					  12 => '12'					
	        );
		$arrtgl = explode('-',$date);
		return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];  
	}
}
?>
