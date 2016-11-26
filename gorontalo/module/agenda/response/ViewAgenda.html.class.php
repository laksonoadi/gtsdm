<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/agenda/business/Agenda.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/components/business/GetSetting.class.php';

class ViewAgenda extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/agenda/template');
        $this->SetTemplateFile('view_agenda.html');
    }

    function ProcessRequest() {
        $id = $_GET['id'];
        $DataAgenda = new Agenda();
        return $DataAgenda->GetAgendaById($id);
    }

    function ParseTemplate($data) {
        $list = new Agenda();
		  $setting = new GetSetting();
        $item=$data;
		if ($item['FOTO']!=''){
			$path_foto = $setting->GetValueByKey('url_image_agenda');
			if(substr($path_foto, -1) != '/') $path_foto .= '/';
			// $foto='<img src="'.$path_foto.$item['FOTO'].'" width="250px" height="250px" hspace="5" align="left" title="'.$item['CAPTION_FOTO'].'" >';
			$foto = $path_foto.$item['FOTO'];
			$this->mrTemplate->AddVar("content", "FOTO", $foto);
			$this->mrTemplate->AddVar("content", "CAPTION_FOTO", $item['CAPTION_FOTO']);
		}
        $this->mrTemplate->AddVar("content","STICKY",$item['STICKY'] == 1 ? 'Ya' : 'Tidak');
        $this->mrTemplate->AddVar("content","TITLE",$item['TITLE']);
        $this->mrTemplate->AddVar("content","ARTIKEL",$item['ARTIKEL']);
        $this->mrTemplate->AddVar("content","TEMPAT",$item['TEMPAT']);
		$this->mrTemplate->AddVar("content","URL",$item['URL']);
        $this->mrTemplate->AddVar("content","MULAI",$list->IndonesianDate($item['MULAI'],'YYYY-MM-DD'));
        $this->mrTemplate->AddVar("content","SELESAI",$list->IndonesianDate($item['SELESAI'],'YYYY-MM-DD'));
        $this->mrTemplate->AddVar("content","PENGIRIM",$item['PENGIRIM']);
        $this->mrTemplate->AddVar("content","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD'));
        //print_r($data);
        /*$this->mrTemplate->AddVar('content','TITLE',$data[0]['TITLE']);
        $this->mrTemplate->AddVar('content','ARTICLE',stripslashes($data[0]['ARTICLE']));
        $this->mrTemplate->AddVar('image','IMG_URL',GTFWConfiguration::GetValue('application', 'file_agenda').$data[0]['FOTO']);*/
    }
}
?>