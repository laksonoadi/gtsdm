<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_agenda/business/ManajemenAgenda.class.php';


class ViewDeleteAgenda extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_agenda/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_delete_agenda.html');
   }
   
   function ProcessRequest() {
      $this->mAgenda = new ManajemenAgenda();
      $data['agenda'] = $this->mAgenda->GetAgendaById($_REQUEST['id']);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if($_POST['action'] == 'Yes'){
         $this->DoDeleteAgenda($data['agenda'][0]['FOTO']);
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_agenda','AdminListAgenda','View','html'));
      }elseif($_POST['action'] == 'No'){
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_agenda','AdminListAgenda','View','html'));
      }
      
      if(!empty($data['agenda'])){
         $agenda = $data['agenda'][0];
         $this->mrTemplate->addVar('content','ID',$agenda['ID']);
         $this->mrTemplate->addVar('content','TITLE',$agenda['TITLE']);
         $article = explode(" ",strip_tags($agenda['ARTICLE']));
         $impArticle = '';
         for($i=0; $i<40; $i++){
            $impArticle .= $article[$i].' ';
         }
         unset($article);
         $this->mrTemplate->addVar('content','ARTICLE',stripslashes($impArticle));
         $this->mrTemplate->addVar('content','URL_MORE',
           Dispatcher::Instance()->GetUrl('manajemen_agenda','DetailAgenda','View','html').'&id='.$agenda['ID']);
      }
      
      $this->mrTemplate->addVar('content','SUB','DeleteAgenda');
   }
   
   function DoDeleteAgenda($filename){
      if($this->mAgenda->DeleteAgenda($_POST['id']) == true){
         @unlink(GTFWConfiguration::GetValue('application', 'file_agenda').$filename);
         return true;
      }
   }
}
?>