<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';


class ViewAddSatuanKerjaPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_satuan_kerja_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $this->mPegawai = new DataPegawai();
      $this->mSatuanKerja = new SatuanKerja();
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_satker_policy', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'tgl_satker_policy'), Messenger::CurrentRequest);
      
      //Combo tipe Policy
      $comboSatker=$this->mSatuanKerja->GetComboSatuanKerja();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', array('satker',$comboSatker,$satker,'false',''), Messenger::CurrentRequest);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'satuanKerjaPolicy', 'view', 'html');
      $nav[0]['menu']="Regulation Department History";
      $title = "Regulation Department Data";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
      if($_POST['actionsave'] == 'Save'){
         $this->CheckRequiredFields();
         $data['action'] = 'Save';
         return $data;
      }
   }
   
   function ParseTemplate($data = NULL) {
      if (!empty($this->mMessage)){
         $this->ShowMessage();
      }elseif($data['action'] == 'Save'){
         $sukses=$this->DoAddSatkerPolicy();
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','satuanKerjaPolicy','View','html').'&op=add'.'&sukses='.$sukses);
      }
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','satuanKerjaPolicy','View','html'));
      
      $this->mrTemplate->addVar('content','LABEL','Add Regulation Department');
      $this->mrTemplate->addVar('content','SUB','AddSatuanKerjaPolicy');
      $this->mrTemplate->addVar('content','DATE',date('d/m/Y'));
      $this->mrTemplate->addVar('content','CHECKED','checked');
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      /*$this->CheckRequired($_POST['tipe'], 'Policy Type Must be Select');
      $this->CheckRequired($_POST['nama'], 'Policy Name Must be filled');*/
   }
   
   function DoAddSatkerPolicy(){
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
                    'user_id'=>$userId
                  );
      $result=$this->mPolicy->AddSatkerPolicy($array);
      if ($result===false){
          return 0;
      }
      return 1;
   }
}
?>