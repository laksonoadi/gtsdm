<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_kategori/business/sptkategori.class.php';

class ViewInputSptKategori extends HtmlResponse {
   
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/spt_kategori/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
		$this->SetTemplateFile('input_spt_kategori.html');    
	} 
   
	public function ProcessRequest(){
		$obj = new SptKategori();
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$data['input'] = $msg[0][0];
		$data['pesan'] = $msg[0][1];
		$data['css'] =$msg[0][2];

		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		if(empty($id))$id = $data['input']['dataId'];
		
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$data['lang'] =$lang;
		if($lang=='eng'){
			$this->title = 'SPT\' Category References';
			$this->buttonLabel = array(
				'add'		=> 'Add',
				'update'	=> 'Update'
			);
		}else{
			$this->title = 'Referensi Kategori SPT';
			$this->buttonLabel = array(
				'add'		=> 'Tambah',
				'update'	=> 'Ubah'
			);
		}

		// links
		$this->listUrl = Dispatcher::Instance()->GetUrl('spt_kategori','sptKategori','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('spt_kategori','inputSptKategori','do','html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('spt_kategori|deleteSptKategori|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('spt_kategori|sptKategori|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
		if(!empty($id))$data['data'] = $obj->getById($id);
		
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
		
		if(empty($data['data'])){
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