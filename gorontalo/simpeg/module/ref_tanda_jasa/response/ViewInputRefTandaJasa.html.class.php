<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_tanda_jasa/business/RefTandaJasa.class.php';

class ViewInputRefTandaJasa extends HtmlResponse {
   
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/ref_tanda_jasa/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
		$this->SetTemplateFile('input_ref_tanda_jasa.html');    
	} 
   
	public function ProcessRequest(){
		$obj = new RefTandaJasa();
		
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
		}else{
			$data['data'] = $obj->getById($id);
			$data['dataId'] = $data['data']['id'];
		}
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$data['lang'] =$lang;
		if($lang=='eng'){
			$this->title = 'Language References';
			$this->buttonLabel = array(
				'add'		=> 'Add',
				'update'	=> 'Update'
			);
		}else{
			$this->title = 'Referensi Tanda Jasa';
			$this->buttonLabel = array(
				'add'		=> 'Tambah',
				'update'	=> 'Ubah'
			);
		}

		// links
		$this->listUrl = Dispatcher::Instance()->GetUrl('ref_tanda_jasa','refTandaJasa','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('ref_tanda_jasa','inputRefTandaJasa','do','html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('ref_tanda_jasa|deleteRefTandaJasa|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('ref_tanda_jasa|refTandaJasa|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
		// combo
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
			$data['aktif_checked_0'] = $data['aktif_checked_1'] = '';
			$data['aktif_checked_' . (isset($data['aktif'])?(int)$data['aktif']:'1')] = 'checked';
		}else{
			$label = $this->buttonLabel['update'];
			$data = empty($data['input'])?$data['data']:$data['input'];
			$data['dataid'] = Dispatcher::Instance()->Encrypt($data['id']);
			$data['aktif_checked_0'] = $data['aktif_checked_1'] = '';
			$data['aktif_checked_' . (isset($data['aktif'])?(int)$data['aktif']:'1')] = 'checked';
		} 

		$this->mrTemplate->AddVar('content', 'URL_ACTION', $this->inputUrl);
		$this->mrTemplate->AddVar('content', 'BUTTONLABEL', $label);
		$this->mrTemplate->AddVar('content', 'JUDUL', $label);

		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', $this->listUrl);
		$this->mrTemplate->AddVars('content', $data);
	}
}
?>