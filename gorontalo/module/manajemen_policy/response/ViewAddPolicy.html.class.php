<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';


class ViewAddPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_form_policy.html');
   }
   
   function ProcessRequest() {
      $this->mPolicy = new ManejemenPolicy();
      $this->mPegawai = new DataPegawai();
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_policy', array(date('Y-m-d'), $tahun['start'], $tahun['end'], '', '', 'tanggal_policy'), Messenger::CurrentRequest);
      
      //Combo tipe Policy
      $comboTipe=$this->mPolicy->GetComboTipe();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', array('tipe',$comboTipe,$tipe,'false',''), Messenger::CurrentRequest);
      
      $comboSatkerPolicy=$this->mPolicy->GetComboSatkerPolicy();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satkerpolicy', array('satkerpolicy',$comboSatkerPolicy,$satkerPolicy,'false',''), Messenger::CurrentRequest);
      
      $nav[0]['url']=Dispatcher::Instance()->GetUrl('manajemen_policy', 'adminListPolicy', 'view', 'html');
      $nav[0]['menu']="Policy & Regulation History";
      $title = "Policy & Regulation Data";
      
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
         $sukses=$this->DoAddPolicy();
         $this->RedirectTo(Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html').'&op=add'.'&sukses='.$sukses);
      }
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html'));
      
      $this->mrTemplate->addVar('content','LABEL','Add Regulation');
      $this->mrTemplate->addVar('content','SUB','AddPolicy');
      $this->mrTemplate->addVar('content','DATE',date('d/m/Y'));
      $this->mrTemplate->addVar('content','CHECKED','checked');
   }
   
   
   #=-=-=-=-=Added methods=-=-=-=-=-

   function CheckRequiredFields() {
      /*$this->CheckRequired($_POST['tipe'], 'Policy Type Must be Select');
      $this->CheckRequired($_POST['nama'], 'Policy Name Must be filled');*/
   }
   
   function DoUploadFile(){
      $fileSend = $_FILES['foto'];
      
      if(empty($fileSend['name'])){
         return true;
      }
      
      $buffExplodedName = explode('.',$fileSend['name']);
      $extensions = array_pop($buffExplodedName);
      
      $fileName = substr(md5(date('dmyDMYhms')),0,6).'.'.$extensions;
      $fileDir = GTFWConfiguration::GetValue('application', 'file_policy');
      
      if(move_uploaded_file($fileSend['tmp_name'],$fileDir.$fileName)){
         $this->mFileName = $fileName;
         return true;
      }else{
         return false;
      }
   }
   
   function DoAddPolicy(){
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
                    'user_id'=>$userId
                  );
      
      $result=$this->mPolicy->AddPolicy($array);
      if ($result===false){
          return 0;
      }
      return 1;
   }
}
?>