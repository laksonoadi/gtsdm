<?php
	require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_berita/business/berita.class.php';

	class ViewBeritaDetail extends HtmlResponse
	{
		function TemplateModule()
		{
			$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
				'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
			$this->SetTemplateFile('view_berita_detail.html');
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
				}else{
					unset($_GET['id']);
				}
			}

			$return['link']['url_balik']	= Dispatcher::Instance()->GetUrl('manajemen_berita','berita','view','html');
			$return['link']['link_foto']	= GTFWConfiguration::GetValue( 'application', 'bo_download_path').'upload_file/file_berita/';

			$lang=GTFWConfiguration::GetValue('application', 'button_lang');
			if ($lang=='eng'){
				$labeldel=Dispatcher::Instance()->Encrypt('Berita Reference');
			}else{
				$labeldel=Dispatcher::Instance()->Encrypt('Referensi Berita');
			}

			$return['lang']=$lang;
			return $return;
		}

		function ParseTemplate($data = NULL)
		{

			if ($data['lang']=='eng'){
					$this->mrTemplate->AddVar('content', 'TITLE', 'NEWS MANAGEMENT');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Detail Berita Data');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
				}else{
					$this->mrTemplate->AddVar('content', 'TITLE', 'MANAJEMEN BERITA');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Detail Data Berita');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
			}
			
			/// DATA
			$this->mrTemplate->AddVar('content', 'KODE', $data['input']['id_ag']);
			$this->mrTemplate->AddVar('content', 'TITLE_AG', $data['input']['title_ag']);
			$this->mrTemplate->AddVar('content', 'ARTICLE', $data['input']['article_ag']);
			$this->mrTemplate->AddVar('content', 'URL', $data['input']['url_ag']);
			$this->mrTemplate->AddVar('content', 'FOTO', $data['link']['link_foto'].''.$data['input']['foto_ag']);
			$this->mrTemplate->AddVar('content', 'CAPTION', $data['input']['caption_ag']);
			$this->mrTemplate->AddVar('content', 'STATUS', ($data['input']['status_ag'] == '1') ? 'Aktif' : 'Tidak Aktif');
			$this->mrTemplate->AddVar('content', 'SENDER', $data['input']['sender_ag']);
			$this->mrTemplate->AddVar('content', 'DATE_POST', $this->date2string($data['input']['datepost_ag']));
			$this->mrTemplate->AddVar('content', 'READED', $data['input']['readed_ag']);			
			$this->mrTemplate->AddVar('content', 'DATE', $this->date2string($data['input']['date_ag']));
			
			$this->mrTemplate->AddVar('content', 'URL_BALIK', $data['link']['url_balik']);
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