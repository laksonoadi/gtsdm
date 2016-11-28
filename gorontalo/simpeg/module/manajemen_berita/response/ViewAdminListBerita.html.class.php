<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_berita/business/ManejemenBerita.class.php';


class ViewAdminListBerita extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_berita/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_admin_list_berita.html');
   }
   
   function ProcessRequest() {
      $this->mBerita = new ManejemenBerita();
      $status = $_GET['statusAdd']->Raw();
	  $status2 = $_GET['statusUpdate']->Raw();
	  $status3 = $_GET['statusDelete']->Raw();
	  //print_r($sukses);die;
	  if ($status == '1'){
		$this->Pesan = 'Penambahan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }
	  if ($status2 == '1'){
		$this->Pesan = 'Perubahan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }
	  if ($status3 == '1'){
		$this->Pesan = 'Penghapusan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }
      if(isset($_GET['page'])){
         $page = $_GET['page']->Raw();
      }else{
         $page = 1;
      }
      
      
      $count = $this->mBerita->CountBerita();
      $GLOBALS['parameters_set'] = array(
         'itemviewed' => 10/*GTFWConfiguration::GetValue('application', 'paging')*/,
         'totitems' => $count,
         'pagingurl' => Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,
                        Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction,
                        Dispatcher::Instance()->mType),
         'page' => $page
      );
      $offset = $GLOBALS['parameters_set']['itemviewed']*($page-1);
      $limit = $GLOBALS['parameters_set']['itemviewed'];
      $data['list_berita'] = $this->mBerita->ListBerita($offset,$limit);

      return $data;

   }
   
   function ParseTemplate($data = NULL) {
   if(!empty($this->Pesan))
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if(!empty($data['list_berita']) && count($data['list_berita']) > 0){
         $this->mrTemplate->setAttribute('list_berita','visibility','visible');
         $no = 1;
         foreach($data['list_berita'] as $berita){
            $this->mrTemplate->addVar('list_berita','NO',$no);
            $this->mrTemplate->addVar('list_berita','TITLE',$berita['TITLE']);
            $this->mrTemplate->addVar('list_berita','SENDER',$berita['SENDER']);
            $this->mrTemplate->addVar('list_berita','DATE_NEWS',$this->mBerita->IndonesianDate($berita['DATE_NEWS'], 'DD-MM-YYYY'));
            $this->mrTemplate->addVar('list_berita','DATE_POSTED',$this->mBerita->IndonesianDate($berita['DATE_POSTED'], 'DD-MM-YYYY'));
            $this->mrTemplate->addVar('list_berita','STATUS',$berita['STATUS'] == '1' ? 'Aktif' : 'Tidak Aktif');
            
            $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_berita','UpdateBerita','View','html').'&id='.$berita['ID'];
            $this->mrTemplate->addVar('list_berita','URL_EDIT',$urlEdit);
            $urlDetail = Dispatcher::Instance()->GetUrl('manajemen_berita','DetailBerita','View','html').'&id='.$berita['ID'];
            $this->mrTemplate->addVar('list_berita','URL_DETAIL',$urlDetail);
            $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_berita','DeleteBerita','View','html').'&id='.$berita['ID'];
            $this->mrTemplate->addVar('list_berita','URL_DELETE',$urlDelete);
            
            $this->mrTemplate->parseTemplate('list_berita','a');
            $no++;
         }
      }else{
         $this->mrTemplate->setAttribute('empty_berita','visibility','visible');
      }
      
      $urlAdd = Dispatcher::Instance()->GetUrl('manajemen_berita','AddBerita','View','html');
      $this->mrTemplate->addVar('toolbar','URL_ADD',$urlAdd);
      $this->mrTemplate->parseTemplate('toolbar');
   }
}
?>