<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';


class ViewDeletePolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_delete_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $data['policy'] = $this->mPolicy->GetPolicyById($_REQUEST['id']);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'adminListPolicy', 'view', 'html');
      $nav[0]['menu']="Policy & Regulation History";
      $title = "Policy & Regulation Deletion";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if($_POST['action'] == 'Yes'){
		 if($_POST['idPeg'] != ''){
			// echo "Pegawai";
			$sukses=$this->DoDeletePegPolicy();
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','UpdatePolicy','View','html').'&id='.$_POST['id'].'&sukses='.$sukses);
		 }else{
			// echo "Policy";
			$sukses=$this->DoDeletePolicy();
			$this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html').'&op=delete'.'&sukses='.$sukses);
		 }
		 // exit;
      }elseif($_POST['action'] == 'No'){
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html'));
      }
      
      if(!empty($data['policy'])){
		 // echo "<pre>";print_r($data['policy']);exit;
         $policy = $data['policy'][0];
         $this->mrTemplate->addVars('content',$policy,'');
         $article = explode(" ",strip_tags($policy['ARTICLE']));
      }
      $this->mrTemplate->addVar('content','IDPEG',$_REQUEST['idPeg']);
      
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html'));
      $this->mrTemplate->addVar('content','SUB','DeletePolicy');
   }
   
   function DoDeletePolicy(){
      if($this->mPolicy->DeletePegPolicy($_POST['id']) == true){
		 $this->mPolicy->DeletePolicy($_POST['id']);
         return 1;
      }
      return 0;
   }
   
   function DoDeletePegPolicy(){
      if($this->mPolicy->DeletePegIdPolicy($_POST['idPeg']) == true){
         return 1;
      }
      return 0;
   }
}
?>