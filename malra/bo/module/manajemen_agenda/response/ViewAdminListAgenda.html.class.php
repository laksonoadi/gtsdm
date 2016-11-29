<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_agenda/business/ManajemenAgenda.class.php';


class ViewAdminListAgenda extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_admin_list_agenda.html');
   }
   
   function ProcessRequest() {
      $objectAgenda = new ManajemenAgenda();
      $this->mAgenda = new ManajemenAgenda();
      $sukses = $_GET['idSukses']->Raw();
	  //print_r($sukses);die;
	  if ($sukses == 1){
		$this->Pesan = 'Penambahan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }elseif ($sukses == 2){
		$this->Pesan = 'Perubahan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }elseif ($sukses == 3){
		$this->Pesan = 'Penghapusan data berhasil dilakukan';
		$this->css = 'notebox-done';
	  }
      if(isset($_GET['page'])){
         $page = $_GET['page']->Raw();
      }else{
         $page = 1;
      }
      
      $count = $objectAgenda->CountAgenda();
      $GLOBALS['parameters_set'] = array(
         'itemviewed' => 10,
         'totitems' => $count,
         'pagingurl' => Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,
                        Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction,
                        Dispatcher::Instance()->mType),
         'page' => $page
      );
      $offset = $GLOBALS['parameters_set']['itemviewed']*($GLOBALS['parameters_set']['page']-1);
      $limit = $GLOBALS['parameters_set']['itemviewed'];
      
      $data['list_agenda'] = $objectAgenda->ListAgenda($offset,$limit);
      return $data;

   }
   
   function ParseTemplate($data = NULL) {
    if(!empty($this->Pesan))
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if(!empty($data['list_agenda']) && count($data['list_agenda']) > 0){
         $this->mrTemplate->setAttribute('list_agenda','visibility','visible');
         $no = 1;
         foreach($data['list_agenda'] as $agenda){
            $this->mrTemplate->addVar('list_agenda','NO',$no);
            $this->mrTemplate->addVar('list_agenda','TITLE',$agenda['TITLE']);
            $this->mrTemplate->addVar('list_agenda','SENDER',$agenda['SENDER']);
            $this->mrTemplate->addVar('list_agenda','START_DATE',$this->mAgenda->IndonesianDate($agenda['START_DATE'],"YYYY-MM-DD"));
            $this->mrTemplate->addVar('list_agenda','STATUS',$agenda['STATUS'] == '1' ? 'Sticky' : 'Reguler');
            
            $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_agenda','UpdateAgenda','View','html').'&id='.$agenda['ID'];
            $this->mrTemplate->addVar('list_agenda','URL_EDIT',$urlEdit);
            $urlDetail = Dispatcher::Instance()->GetUrl('manajemen_agenda','DetailAgenda','View','html').'&id='.$agenda['ID'];
            $this->mrTemplate->addVar('list_agenda','URL_DETAIL',$urlDetail);
            $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_agenda','DeleteAgenda','View','html').'&id='.$agenda['ID'];
            $this->mrTemplate->addVar('list_agenda','URL_DELETE',$urlDelete);
            
            $this->mrTemplate->parseTemplate('list_agenda','a');
            $no++;
         }
      }else{
         $this->mrTemplate->setAttribute('empty_agenda','visibility','visible');
      }
      
      $urlAdd = Dispatcher::Instance()->GetUrl('manajemen_agenda','AddAgenda','View','html');
      $this->mrTemplate->addVar('toolbar','URL_ADD',$urlAdd);
      $this->mrTemplate->parseTemplate('toolbar');
   }
}
?>