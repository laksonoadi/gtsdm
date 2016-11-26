<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';


class ViewUpdateSatuanKerjaPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_satuan_kerja_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $this->mPegawai = new DataPegawai();
      $data['satker_policy'] = $this->mPolicy->GetSatkerPolicyById($_REQUEST['id']);
      
      //Combo tipe Policy
      $comboSatker=$this->mPolicy->GetComboSatker();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', array('satker',$comboSatker,$data['satker_policy'][0]['satkerpolicySatkerId'],'false',''), Messenger::CurrentRequest);
      
      if($_POST['actionsave'] == 'Save'){
         $this->CheckRequiredFields();
         $data['action'] = 'Save';
         return $data;
      }
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_satker_policy', array($data['satker_policy'][0]['satkerpolicyTgl'], $tahun['start'], $tahun['end'], '', '', 'tgl_satker_policy'), Messenger::CurrentRequest);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'satuanKerjaPolicy', 'view', 'html');
      $nav[0]['menu']="Regulation Department History";
      $title = "Regulation Department Data";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($this->mMessage)){
         $this->ShowMessage();
      }elseif($data['action'] == 'Save'){
         $sukses=$this->DoUpdateSatkerPolicy();
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','SatuanKerjaPolicy','View','html').'&op=edit'.'&sukses='.$sukses);
         
      }
      
      if(!empty($data['satker_policy'])){
         $satkerpolicy = $data['satker_policy'][0];
         $this->mrTemplate->addVars('content',$satkerpolicy,'');
         $checked = $satkerpolicy['satkerpolicyStatus'] == 'Aktif' ? 'checked' : '';
         $this->mrTemplate->addVar('content','CHECKED',$checked);
      }
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','satuanKerjaPolicy','View','html'));
      
      $this->mrTemplate->addVar('content','LABEL','Update Regulation Department');
      $this->mrTemplate->addVar('content','SUB','UpdateSatuanKerjaPolicy');
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      //$this->CheckRequired($_POST['title'], 'Judul harus diisi');
      //$this->CheckRequired($_POST['article'], 'Artikel harus diisi');
      //$this->CheckRequired($_POST['tanggal_policy'], 'Tanggal harus diisi');
   }
   
   function DoUpdateSatkerPolicy(){
      #parameter preparation
      $sender = $_SESSION['username'];
      $pegawai=$this->mPegawai->GetDataPegawaiByUserName($sender);
      $userId=$pegawai['peguserUserId'];
      $date = $_POST['tgl_satker_policy_year'].'-'.$_POST['tgl_satker_policy_mon'].'-'.$_POST['tgl_satker_policy_day'];
      $status = $_POST['status'] == 'Aktif' ? 'Aktif' : 'Tidak Aktif';
      $array=array( 'satker'=>$_POST['satker'],
                    'deskripsi'=>$_POST['deskripsi'],
                    'status'=>$status,
                    'tgl_satker_policy'=>$date,
                    'user_id'=>$userId,
                    'id'=>$_POST['id']
                  );
      $result=$this->mPolicy->UpdateSatkerPolicy($array);
      if ($result===false){
          return 0;
      }
      return 1;
   }
}
?>