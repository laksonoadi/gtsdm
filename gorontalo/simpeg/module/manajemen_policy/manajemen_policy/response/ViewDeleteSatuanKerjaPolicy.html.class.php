<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';


class ViewDeleteSatuanKerjaPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_delete_satuan_kerja_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $data['satker_policy'] = $this->mPolicy->GetSatkerPolicyById($_REQUEST['id']);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'satuanKerjaPolicy', 'view', 'html');
      $nav[0]['menu']="Regulation Department History";
      $title = "Regulation Department Deletion";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if($_POST['action'] == 'Yes'){
         $sukses=$this->DoDeleteSatkerPolicy();
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','SatuanKerjaPolicy','View','html').'&op=delete'.'&sukses='.$sukses);
      }elseif($_POST['action'] == 'No'){
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','SatuanKerjaPolicy','View','html'));
      }
      
      if(!empty($data['satker_policy'])){
         $satkerpolicy = $data['satker_policy'][0];
         $this->mrTemplate->addVars('content',$satkerpolicy,'');
         $article = explode(" ",strip_tags($satkerpolicy['ARTICLE']));
      }
      
      $this->mrTemplate->addVar('content','SUB','DeleteSatuanKerjaPolicy');
   }
   
   function DoDeleteSatkerPolicy(){
      if($this->mPolicy->DeleteSatkerPolicy($_POST['id']) == true){
         return 1;
      }
      return 0;
   }
}
?>