<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_agenda/business/ManajemenAgenda.class.php';


class ViewDetailAgenda extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_agenda.html');
   }
   
   function ProcessRequest() {
      $this->mAgenda = new ManajemenAgenda();
      $data['agenda'] = $this->mAgenda->GetAgendaById($_REQUEST['id']);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if(!empty($data['agenda'])){
         $agenda = $data['agenda'][0];
         $this->mrTemplate->addVar('content','ID',$agenda['ID']);
         $this->mrTemplate->addVar('content','TITLE',$agenda['TITLE']);
         $this->mrTemplate->addVar('content','LOCATION',$agenda['LOCATION']);
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($agenda['ARTICLE']));
         $this->mrTemplate->addVar('content','URL',$agenda['URL']);
         $this->mrTemplate->addVar('content','FOTO',GTFWConfiguration::GetValue('application', 'file_agenda_url').$agenda['FOTO']);
         $this->mrTemplate->addVar('content','CAPTION',$agenda['CAPTION']);
         $this->mrTemplate->addVar('content','START_DATE',$this->mAgenda->IndonesianDate($agenda['START_DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $this->mrTemplate->addVar('content','END_DATE',$this->mAgenda->IndonesianDate($agenda['END_DATE'],"yyyy-mm-dd","dd/mm/yyyy"));
         $this->mrTemplate->addVar('content','STATUS',$agenda['STATUS'] == '1' ? 'Sticky' : 'Reguler');
         
         $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_agenda','UpdateAgenda','View','html').'&id='.$agenda['ID'];
         $this->mrTemplate->addVar('content','URL_EDIT',$urlEdit);
         $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_agenda','DeleteAgenda','View','html').'&id='.$agenda['ID'];
         $this->mrTemplate->addVar('content','URL_DELETE',$urlDelete);
         $urlBack = Dispatcher::Instance()->GetUrl('manajemen_agenda','AdminListAgenda','View','html');
         $this->mrTemplate->addVar('content','URL_BACK',$urlBack);
      }
   }
}
?>