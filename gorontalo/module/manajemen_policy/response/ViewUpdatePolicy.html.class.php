<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';


class ViewUpdatePolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $this->mPegawai = new DataPegawai();
      $data['policy'] = $this->mPolicy->GetPolicyById($_REQUEST['id']);
      
      //Combo tipe Policy
      $comboTipe=$this->mPolicy->GetComboTipe();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', array('tipe',$comboTipe,$data['policy'][0]['policyJnspolicyId'],'false',''), Messenger::CurrentRequest);
      
      $comboSatkerPolicy=$this->mPolicy->GetComboSatkerPolicy();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satkerpolicy', array('satkerpolicy',$comboSatkerPolicy,$data['policy'][0]['policySatkerpolicyId'],'false',''), Messenger::CurrentRequest);
      
      if($_POST['actionsave'] == 'Save'){
         $this->CheckRequiredFields();
         $data['action'] = 'Save';
         return $data;
      }
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_policy', array($data['policy'][0]['policyTanggalPolicy'], $tahun['start'], $tahun['end'], '', '', 'tanggal_policy'), Messenger::CurrentRequest);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'adminListPolicy', 'view', 'html');
      $nav[0]['menu']="Policy & Regulation History";
      $title = "Policy & Regulation Data";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($this->mMessage)){
         $this->ShowMessage();
      }elseif($data['action'] == 'Save'){
         $sukses=$this->DoUpdatePolicy();
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html').'&op=edit'.'&sukses='.$sukses);
         
      }
      
      if(!empty($data['policy'])){
         $policy = $data['policy'][0];
         $this->mrTemplate->addVars('content',$policy,'');
         $checked = $policy['policyIsAktif'] == '1' ? 'checked' : '';
         $this->mrTemplate->addVar('content','CHECKED',$checked);
      }
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html'));
       
      $this->mrTemplate->addVar('content','LABEL','Update Regulation');
      $this->mrTemplate->addVar('content','SUB','UpdatePolicy');
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      //$this->CheckRequired($_POST['title'], 'Judul harus diisi');
      //$this->CheckRequired($_POST['article'], 'Artikel harus diisi');
      //$this->CheckRequired($_POST['tanggal_policy'], 'Tanggal harus diisi');
   }
   
   function DoUpdatePolicy(){
      #parameter preparation
      $sender = $_SESSION['username'];
      $pegawai=$this->mPegawai->GetDataPegawaiByUserName($sender);
      $userId=$pegawai['peguserUserId'];
      $date = $_POST['tanggal_policy_year'].'-'.$_POST['tanggal_policy_mon'].'-'.$_POST['tanggal_policy_day'];
      $status = $_POST['status'] == '1' ? '1' : '0';
      $array=array( 'satkerpolicy'=>$_POST['satkerpolicy'],
                    'tipe'=>$_POST['tipe'],
                    'nama'=>$_POST['nama'],
                    'keterangan'=>$_POST['keterangan'],
                    'url'=>$_POST['url'],
                    'is_aktif'=>$status,
                    'pengirim'=>$sender,
                    'tgl_policy'=>$date,
                    'user_id'=>$userId,
                    'id'=>$_POST['id']
                  );
      $result=$this->mPolicy->UpdatePolicy($array);
      if ($result===false){
          return 0;
      }
      return 1;
   }
}
?>