<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_berita/business/ManejemenBerita.class.php';


class ViewDeleteBerita extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_delete_berita.html');
   }
   
   function ProcessRequest() {
      $this->mBerita = new ManejemenBerita();
	  $data['berita'] = $this->mBerita->GetBeritaById($_REQUEST['id']);
      $status = $_GET['statusDelete'];
      if ($status == '2'){
		$this->Pesan = 'Penambahan data gagal dilakukan';
		$this->css = 'notebox-warning';
	  }
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
   if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if($_POST['action'] == 'Yes'){
         $result = $this->DoDeleteBerita($data['berita'][0]['FOTO']);
		 if ($result == true){
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','AdminListBerita','View','html').'&statusDelete=1');
		 }else{
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','DeleteBerita','View','html').'&statusDelete=2');
		 }
      }elseif($_POST['action'] == 'No'){
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_berita','AdminListBerita','View','html'));
      }
      
      if(!empty($data['berita'])){
         $berita = $data['berita'][0];
         $this->mrTemplate->addVar('content','ID',$berita['ID']);
         $this->mrTemplate->addVar('content','TITLE',$berita['TITLE']);
         $article = (strlen($berita['ARTICLE']) > 110) ? substr($berita['ARTICLE'], 0, 97).'...' : $berita['ARTICLE'];
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($article));
         $this->mrTemplate->addVar('content','URL_MORE',
           Dispatcher::Instance()->GetUrl('manajemen_berita','DetailBerita','View','html').'&id='.$berita['ID']);
      }
      
      $this->mrTemplate->addVar('content','SUB','DeleteBerita');
   }
   
   function DoDeleteBerita($filename){
      if($this->mBerita->DeleteBerita($_POST['id']) == true){
         @unlink(GTFWConfiguration::GetValue('application', 'file_berita').$filename);
         return true;
      }
   }
}
?>