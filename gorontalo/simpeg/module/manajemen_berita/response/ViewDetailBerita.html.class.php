<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_berita/business/ManejemenBerita.class.php';


class ViewDetailBerita extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_berita.html');
   }
   
   function ProcessRequest() {
      $this->mBerita = new ManejemenBerita();
      $data['berita'] = $this->mBerita->GetBeritaById($_REQUEST['id']);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if(!empty($data['berita'])){
         $berita = $data['berita'][0];
         $this->mrTemplate->addVar('content','ID',$berita['ID']);
         $this->mrTemplate->addVar('content','TITLE',$berita['TITLE']);
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($berita['ARTICLE']));
         $this->mrTemplate->addVar('content','URL',$berita['URL']);
         $this->mrTemplate->addVar('content','FOTO',GTFWConfiguration::GetValue('application', 'file_berita').$berita['FOTO']);
			if($berita['FOTO'] != '') {
				$this->mrTemplate->addVar('foto', 'FOTO_EMPTY', 'NO');
				$this->mrTemplate->addVar('foto','FOTO',GTFWConfiguration::GetValue('application', 'file_berita').$berita['FOTO']);
			} else {
				$this->mrTemplate->addVar('foto', 'FOTO_EMPTY', 'YES');
			}
         $this->mrTemplate->addVar('content','CAPTION',$berita['CAPTION']);
         $this->mrTemplate->addVar('content','DATE',$this->mBerita->IndonesianDate($berita['DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $this->mrTemplate->addVar('content','STATUS',$berita['STATUS'] == '1' ? 'Aktif' : 'Tidak Aktif');
         
         $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_berita','UpdateBerita','View','html').'&id='.$berita['ID'];
         $this->mrTemplate->addVar('content','URL_EDIT',$urlEdit);
         $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_berita','DeleteBerita','View','html').'&id='.$berita['ID'];
         $this->mrTemplate->addVar('content','URL_DELETE',$urlDelete);
         $urlBack = Dispatcher::Instance()->GetUrl('manajemen_berita','AdminListBerita','View','html');
         $this->mrTemplate->addVar('content','URL_BACK',$urlBack);
      }
   }
}
?>