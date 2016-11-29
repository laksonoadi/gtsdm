<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_daftar_gaji/business/RefDaftarGaji.class.php';

class ViewInputRefDaftarGaji extends HtmlResponse {
   
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/ref_daftar_gaji/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
		$this->SetTemplateFile('input_ref_daftar_gaji.html');    
	} 
   
	public function ProcessRequest(){
		$obj = new refDaftarGaji();
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		if(isset($msg[0])){
			$data['input'] = $msg[0][0];
			$data['pesan'] = $msg[0][1];
			$data['css'] =$msg[0][2];
		}else{
			$data = array(
				'input'	=> array(),
				'pesan'	=> array(),
				'css'	=> array()
			);
		}
		
		if(isset($data['input']['dataId']))$data['input']['id'] = $data['input']['dataId'];
		
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		if(empty($id)){
			$data['dataId'] = $id = isset($data['input']['dataId']) ? $data['input']['dataId'] : '';
			$data['data'] = $data['input'];
		}else{
			$data['data'] = $obj->getById($id);
			$data['dataId'] = $data['data']['id'];
		}
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$data['lang'] =$lang;
		if($lang=='eng'){
			$this->title = 'Salary Reference';
			$this->buttonLabel = array(
				'add'		=> 'Add',
				'update'	=> 'Update'
			);
		}else{
			$this->title = 'Referensi Daftar Gaji';
			$this->buttonLabel = array(
				'add'		=> 'Tambah',
				'update'	=> 'Ubah'
			);
		}

		// links
		$this->listUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji','refDaftarGaji','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('ref_daftar_gaji','inputRefDaftarGaji','do','html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('ref_daftar_gaji|deleteRefDaftarGaji|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('ref_daftar_gaji|refDaftarGaji|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
		// combo
		$arrMasaKerja = $obj->getComboMasaKerja();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'masa_kerja', array('masa_kerja', $arrMasaKerja, (isset($data['data']['masa_kerja']) ? $data['data']['masa_kerja'] : null), 'false', 'style="width:100px"'), Messenger::CurrentRequest);

		$arrGolRuang = $obj->getComboGolonganRuang();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'gol_ruang', array('gol_ruang', $arrGolRuang, (isset($data['data']['gol_ruang']) ? $data['data']['gol_ruang'] : null), 'false', ''), Messenger::CurrentRequest);

		$years = range(2015,date('Y'));
		$arrYears = array();
		foreach ($years as $key => $val) {
			$arrYears[]  = array('id'=>$val,'name'=>$val);
		}
		// print_r($arrYears);

		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'period', array('period', $arrYears, (isset($data['data']['period']) ? $data['data']['period'] : null), 'false', 'style="width:100px"'), Messenger::CurrentRequest);
		// $arrGolongan = $obj->getComboGolongan();
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan', array('golongan', $arrGolongan, (isset($data['data']['golongan']) ? $data['data']['golongan'] : null), 'false', ''), Messenger::CurrentRequest);
		// $arrRuang = $obj->getComboRuang();
		// Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'ruang', array('ruang', $arrRuang, (isset($data['data']['ruang']) ? $data['data']['ruang'] : null), 'false', ''), Messenger::CurrentRequest);
		// end of combo
		
		return $data;   
	}  
   
	public function ParseTemplate ($data = NULL){
		if ($data['pesan']){
			$this->mrTemplate->SetAttribute('warning_box','visibility','visible');
			$this->mrTemplate->AddVar('warning_box','ISI_PESAN',$data['pesan']);
			$this->mrTemplate->AddVar('warning_box','CLASS_PESAN',$data['css']);
		}
		
		$this->mrTemplate->AddVar('content', 'TITLE', strtoupper($this->title));
		$this->mrTemplate->AddVars('content', $data['input']);
		
		if(empty($data['dataId'])){
			$label = $this->buttonLabel['add'];
			$data = empty($data['input'])?array():$data['input'];
		}else{
			$label = $this->buttonLabel['update'];
			$data = empty($data['input'])?$data['data']:$data['input'];
			$data['dataid'] = Dispatcher::Instance()->Encrypt($data['id']);
		} 

		$this->mrTemplate->AddVar('content', 'URL_ACTION', $this->inputUrl);
		$this->mrTemplate->AddVar('content', 'BUTTONLABEL', $label);
		$this->mrTemplate->AddVar('content', 'JUDUL', $label);

		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', $this->listUrl);
		$this->mrTemplate->AddVars('content', $data);
	}
}
?>