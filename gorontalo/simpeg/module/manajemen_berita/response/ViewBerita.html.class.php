<?php
	require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_berita/business/berita.class.php';

	class ViewBerita extends HtmlResponse
	{
		function TemplateModule()
		{
			$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
				'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
			$this->SetTemplateFile('view_berita.html');
		}

		function ProcessRequest()
		{
			$publik = new Berita;
			// inisialisasi messaging
			$msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
			$this->Data = $msg[0][0];
			$this->Pesan = $msg[0][1];
			$this->css = $msg[0][2];
			// ---------
			$id = Dispatcher::Instance()->Decrypt($_GET['id']);
			
			if(isset($_GET['id'])){
				$result = $publik->GetDataById($id);
				if($result){
					$return['input']['id_ag']			= $result['ID'];
					$return['input']['title_ag']		= $result['TITLE'];
					$return['input']['article_ag']		= $result['ARTICLE'];
					$return['input']['url_ag']			= $result['URL'];
					$return['input']['foto_ag']			= $result['FOTO'];
					$return['input']['caption_ag']		= $result['CAPTION'];
					$return['input']['status_ag']		= $result['STATUS'];
					$return['input']['sender_ag']		= $result['SENDER'];
					$return['input']['datepost_ag']		= $result['DATE_POSTED'];
					$return['input']['readed_ag']		= $result['READED'];
					$return['input']['date_ag']			= $result['DATE'];
					
					Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_berita', array($result['DATE'], $tahun['start'], $tahun['end'], '', '', 'tanggal_berita'), Messenger::CurrentRequest);
				}else{
					unset($_GET['id']);
				}
			}else{
					$return['input']['id']		= '';
					$return['input']['nama']	= '';
					
					Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_berita', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'tanggal_berita'), Messenger::CurrentRequest);
			}
			

			//inisialisasi paging
			$itemViewed	= 10;
			$currPage	= 1;
			$startRec	= 0 ;

			if(isset($_GET['page']))
			{
				$currPage	= $_GET['page']->Integer()->Raw();
				if ($currPage > 0)
				$startRec 	= ($currPage-1) * $itemViewed;
				else $currPage = 1;
			}

			$return['start']	= $startRec+1;
			$totalData			= $publik->GetCount();
			$url				= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html');
			if (isset($_GET['id'])){
				$url .= '&id='.$id;
			}

			Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

			$return['link']['link_foto']	= GTFWConfiguration::GetValue( 'application', 'bo_download_path').'upload_file/file_berita/';
			$return['link']['url_action'] 		= Dispatcher::Instance()->GetUrl('manajemen_berita','InputBerita','do','html');
			if (isset($_GET['id'])){
				$return['link']['url_action'] 	.= '&id='.$id;
			}

			$return['link']['url_tambah']		= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html').'&aksi=ya';
			$return['link']['url_balik']		= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html');
			$return['link']['url_search']		= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html');
			$return['link']['url_detail']		= Dispatcher::Instance()->GetUrl('manajemen_berita','beritaDetail','view','html');
			$return['link']['url_edit']			= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html');
			if (isset($_GET['page'])){
				$return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
			}

			$lang=GTFWConfiguration::GetValue('application', 'button_lang');
			if ($lang=='eng'){
				$labeldel=Dispatcher::Instance()->Encrypt('Berita Data');
			}else{
				$labeldel=Dispatcher::Instance()->Encrypt('Data Berita');
			}

			$return['lang']=$lang; 
			$return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
				"&urlDelete=".Dispatcher::Instance()->Encrypt('manajemen_berita|DeleteBerita|do|html').
				"&urlReturn=".Dispatcher::Instance()->Encrypt('manajemen_berita|berita|view|html').
				"&label=".$labeldel;
			
			$return['dataSheet']	= $publik->GetData($startRec,$itemViewed);
			$GetId 					= $publik->getMaxId();
			$return['GetId']		= $GetId;
			
			if ($_GET['aksi']=='ya'){
				$return['display_list']='none';
			}else if(isset($_GET['id'])){
				$return['display_list']='none';
			}else{
				$return['display_form']='none';
			}			

			return $return;
		}

		function ParseTemplate($data = NULL)
		{
			$this->mrTemplate->AddVar('content', 'DISPLAY_LIST', $data['display_list']);
			$this->mrTemplate->AddVar('content', 'DISPLAY_FORM', $data['display_form']);
			
			
			$urlAdd = Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html').'&aksi=ya';
			$this->mrTemplate->addVar('toolbar','URL_ADD',$urlAdd);
			$this->mrTemplate->parseTemplate('toolbar');

			
			if($this->Pesan)
			{
				$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
				$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
				$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
			}

			if ($data['lang']=='eng'){
					$this->mrTemplate->AddVar('content', 'TITLE', 'NEWS MANAGEMENT');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Berita Data');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
				}else{
					$this->mrTemplate->AddVar('content', 'TITLE', 'MANAJEMEN BERITA');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Berita');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
			}
			
			
			/// FORM INPUT UNTUK UPDATE START
			$this->mrTemplate->AddVar('content', 'KODE', $data['input']['id_ag']);
			$this->mrTemplate->AddVar('content', 'TITLE_AG', $data['input']['title_ag']);
			$this->mrTemplate->AddVar('content', 'ARTICLE', $data['input']['article_ag']);
			$this->mrTemplate->AddVar('content', 'URL', $data['input']['url_ag']);
			
			if(!empty($data['input']['foto_ag'])){
				$this->mrTemplate->setAttribute('foto','visibility','visible');
				$this->mrTemplate->AddVar('foto', 'FOTO_VIEW', $data['link']['link_foto'].''.$data['input']['foto_ag']);
				$this->mrTemplate->AddVar('foto', 'FOTO_FISIK', $data['input']['foto_ag']);
			}
			
			$this->mrTemplate->AddVar('content', 'CAPTION', $data['input']['caption_ag']);
			$this->mrTemplate->AddVar('content', 'CHECKED', ($data['input']['status_ag'] == "1") ? "checked" : "");
			$this->mrTemplate->AddVar('content', 'SENDER', $data['input']['sender_ag']);
			$this->mrTemplate->AddVar('content', 'DATE_POST', $this->date2string($data['input']['datepost_ag']));
			$this->mrTemplate->AddVar('content', 'READED', $data['input']['readed_ag']);			
			$this->mrTemplate->AddVar('content', 'DATE', $this->date2string($data['input']['date_ag']));
			/// FORM INPUT UNTUK UPDATE END

			
			$this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);
			$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
			$this->mrTemplate->AddVar('content', 'URL_TAMBAH', $data['link']['url_tambah']);
			$this->mrTemplate->AddVar('content', 'URL_BALIK', $data['link']['url_balik']);

			if(empty($data['dataSheet'])){
				$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
				return NULL;
			}else{
				$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
			}
			$i		= $data['start'];
			$link	= $data['link'];
			foreach ($data['dataSheet'] as $value)
			{
				$data 				= $value;//print_r($data);
				$data['number'] 	= $i;
				$data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
				$data['status'] 	= ($data['STATUS'] == '1') ? 'Aktif' : 'Tidak Aktif';
				
				$data['url_edit'] 	= $link['url_edit'].'&id='.$data['ID'];
				$data['url_delete'] = $link['url_delete'].
					"&id=".Dispatcher::Instance()->Encrypt($data['ID']).
					"&dataName=".Dispatcher::Instance()->Encrypt($data['TITLE']);
				$data['url_detail'] 	= $link['url_detail'].'&id='.$data['ID'];

				$this->mrTemplate->AddVars('data_item', $data, '');
				$this->mrTemplate->parseTemplate('data_item', 'a');
				$i++;
			}
		}
		
		function gSamViewArr($param) {
			echo"<pre>";print_r($param);echo"</pre>";exit;
		}
		  
		function gSamViewEcho($param) {
			echo"<pre>";echo$param.'<br/>';exit;
		}
		
		function date2string($date) {
			$bln = array(
				1  => 'Januari',
				2  => 'Februari',
				3  => 'Maret',
				4  => 'April',
				5  => 'Mei',
				6  => 'Juni',
				7  => 'Juli',
				8  => 'Agustus',
				9  => 'September',
				10 => 'Oktober',
				11 => 'November',
				12 => 'Desember'					
			);
			$arrtgl = explode('-',$date);
			return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
		}

	}


	?>