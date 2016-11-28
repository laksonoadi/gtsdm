<?php
	require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/manajemen_agenda/business/agenda.class.php';

	class ViewAgendaDetail extends HtmlResponse
	{
		function TemplateModule()
		{
			$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
				'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
			$this->SetTemplateFile('view_agenda_detail.html');
		}

		function ProcessRequest()
		{
			$publik = new Agenda;
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
					$return['input']['start_date_ag']	= $result['START_DATE'];
					$return['input']['end_date_ag']		= $result['END_DATE'];
					$return['input']['location_ag']		= $result['LOCATION'];
					$return['input']['status_ag']		= $result['STATUS'];
				}else{
					unset($_GET['id']);
				}
			}

			$return['link']['url_balik']		= Dispatcher::Instance()->GetUrl('manajemen_agenda','agenda','view','html');
			$return['link']['link_foto']	= GTFWConfiguration::GetValue( 'application', 'bo_download_path').'upload_file/file_agenda/';

			$lang=GTFWConfiguration::GetValue('application', 'button_lang');
			if ($lang=='eng'){
				$labeldel=Dispatcher::Instance()->Encrypt('Agenda Reference');
			}else{
				$labeldel=Dispatcher::Instance()->Encrypt('Referensi Agenda');
			}

			$return['lang']=$lang;
			return $return;
		}

		function ParseTemplate($data = NULL)
		{

			if ($data['lang']=='eng'){
					$this->mrTemplate->AddVar('content', 'TITLE', 'PLAN MANAGEMENT');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Detail Agenda Data');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
				}else{
					$this->mrTemplate->AddVar('content', 'TITLE', 'MANAJEMEN AGENDA');
					$this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Detail Data Agenda');
					$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
			}
			
			
			/// DATA
			$this->mrTemplate->AddVar('content', 'KODE', $data['input']['id_ag']);
			$this->mrTemplate->AddVar('content', 'TITLE_AG', $data['input']['title_ag']);
			$this->mrTemplate->AddVar('content', 'ARTICLE', $data['input']['article_ag']);
			$this->mrTemplate->AddVar('content', 'URL', $data['input']['url_ag']);
			$this->mrTemplate->AddVar('content', 'FOTO', $data['link']['link_foto'].''.$data['input']['foto_ag']);
			$this->mrTemplate->AddVar('content', 'CAPTION', $data['input']['caption_ag']);
			$this->mrTemplate->AddVar('content', 'START_DATE', $this->date2string($data['input']['start_date_ag']));
			$this->mrTemplate->AddVar('content', 'END_DATE', $this->date2string($data['input']['end_date_ag']));
			$this->mrTemplate->AddVar('content', 'LOCATION', $data['input']['location_ag']);
			$this->mrTemplate->AddVar('content', 'STATUS', ($data['input']['status_ag'] == '1') ? 'Sticky' : 'Reguler');

			
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