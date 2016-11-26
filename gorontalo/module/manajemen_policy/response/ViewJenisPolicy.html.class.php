<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';


class ViewJenisPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_jenis_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      
      if ($_POST['simpan']=='Update'){
          if ($this->DoUpdate()==true){
              $data['message']='Perubahan data berhasil dilakukan';
              $data['css']='notebox-done';
          }else{
              $data['message']='Perubahan data gagal dilakukan';
              $data['css']='notebox-warning';
          }
      }else if ($_POST['simpan']=='Add'){
          if ($this->DoAdd()==true){
              $data['message']='Penambahan data berhasil dilakukan';
              $data['css']='notebox-done';
          }else{
              $data['message']='Penambahan data gagal dilakukan';
              $data['css']='notebox-warning';
          }
      }else if ($_GET['op']=='delete'){
          if ($this->DoDelete($_GET['id'])==true){
            $data['message']='Penghapusan data berhasil dilakukan';
            $data['css']='notebox-done';
          }else{
            $data['message']='Penghapusan data gagal dilakukan';
            $data['css']='notebox-warning';
          }
      }
      
      if (!empty($_GET['id'])){
        $data['policy'] = $this->mPolicy->GetTypePolicyById($_REQUEST['id']);
      }
      $data['list_type_policy'] = $this->mPolicy->ListTypePolicy();
      
      $nav[0]['url']='';
      $nav[0]['menu']='';
      $title = "Policy & Regulation Category History";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','hidden',''), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($data['message'])){
         $this->mrTemplate->setAttribute('message','visibility','visible');
         $this->mrTemplate->addVar('message','MESSAGE',$data['message']);
         $this->mrTemplate->addVar('message','CSS',$data['css']);
      }
      
      $urlAction = Dispatcher::Instance()->GetUrl('manajemen_policy','JenisPolicy','View','html');
      $this->mrTemplate->addVar('content','URL_ACTION',$urlAction);
   
      if(!empty($data['policy'])){
         $type = $data['policy'][0];
         $type['jnspolicyTgl']=$this->mPolicy->IndonesianDate($type['jnspolicyTgl'],"yyyy-mm-dd");
         $type['label_aksi']='Update';
         $this->mrTemplate->addVars('content',$type,'');
      }else{
        $type['label_aksi']='Add';
        $this->mrTemplate->addVars('content',$type,'');
      }
      
      if(!empty($data['list_type_policy'][0]['jnspolicyId'])){
         $this->mrTemplate->setAttribute('list_type_policy','visibility','visible');
         $no = 1;
         foreach($data['list_type_policy'] as $list){
            $list['no']=$no;
            $list['url_edit'] = Dispatcher::Instance()->GetUrl('manajemen_policy','jenisPolicy','View','html').'&id='.$list['jnspolicyId'];
            $list['url_delete'] = Dispatcher::Instance()->GetUrl('manajemen_policy','jenisPolicy','View','html').'&id='.$list['jnspolicyId'].'&op=delete';
            $list['jnspolicyTgl'] = $this->mPolicy->IndonesianDate($list['jnspolicyTgl'],'YYYY-MM-DD');
            $this->mrTemplate->addVars('list_type_policy',$list,'');
            $this->mrTemplate->parseTemplate('list_type_policy','a');
            $no++;
         }
      }else{
         //$this->mrTemplate->setAttribute('empty_file_policy','visibility','visible');
      }
   }
   
   function DoAdd(){
      #parameter preparation
      return $this->mPolicy->AddType($_POST['nama']);
   }
   
   function DoUpdate(){
      return $this->mPolicy->UpdateType($_POST['nama'],$_POST['id']);
   }
   
    function DoDelete($id){
      return $this->mPolicy->DeleteType($id);
   }
}
?>