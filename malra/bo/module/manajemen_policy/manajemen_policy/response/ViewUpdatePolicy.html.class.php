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
      $this->mPolicy			= new ManejemenPolicy();
      $this->mPegawai			= new DataPegawai();
	  $this->id					= $_REQUEST['id'];
      $data['policy']			= $this->mPolicy->GetPolicyById($_REQUEST['id']);
	  
      $data['PegawaiPolicy']	= $this->mPolicy->GetPegawaiPolicyById($_REQUEST['id']);
	  	  
	  // echo "<pre>";print_r($data['PegawaiPolicy']);exit;

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
	  
	  $this->url_delete = Dispatcher::Instance()->GetUrl('manajemen_policy','DeletePolicy','View','html');
	  
	  // $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		// "&urlDelete=".Dispatcher::Instance()->Encrypt('data_referensi_bank|DeleteBank|do|html').
		// "&urlReturn=".Dispatcher::Instance()->Encrypt('data_referensi_bank|bank|view|html').
		// "&label=".$labeldel;
	  
      
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
         $policy		= $data['policy'][0];
         $PegawaiPolicy	= $data['PegawaiPolicy'];
		 
		 if(!empty($PegawaiPolicy)){
			$recPeg	= sizeof($PegawaiPolicy);
			$this->mrTemplate->addVar('content','SELECT_PILIH','selected="selected"');
			$this->mrTemplate->addVar('content','INPUT_PILIH','1');
			$this->mrTemplate->addVar('content','REC_PEG',$recPeg);
		 }else{
			$this->mrTemplate->addVar('content','SELECT_ALL','selected="selected"');
			$this->mrTemplate->addVar('content','INPUT_PILIH','0');
			$this->mrTemplate->addVar('content','REC_PEG','');
		 }
		 


         $this->mrTemplate->addVars('content',$policy,'');
         $checked = $policy['policyIsAktif'] == '1' ? 'checked' : '';
		 
         $this->mrTemplate->addVar('content','CHECKED',$checked);
		 
			if(empty($data['PegawaiPolicy'])){
				$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
//				return NULL;
			}else{
				$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
			
			foreach ($data['PegawaiPolicy'] as $value)
			{
				$data 				= $value;//print_r($data);
				$link				= $data['link'];
				$data['number'] 	= $i;

				$data['url_delete'] = $this->url_delete.
					"&id=".Dispatcher::Instance()->Encrypt($this->id).
					"&idPeg=".Dispatcher::Instance()->Encrypt($data['id']).
					"&dataName=".Dispatcher::Instance()->Encrypt($data['namaPeg']);
				$this->mrTemplate->AddVars('data_item', $data, '');
				$this->mrTemplate->parseTemplate('data_item', 'a');
				$i++;
			}
			}
			
      }
      $this->mrTemplate->addVar('content','URL_BACK',Dispatcher::Instance()->GetUrl('manajemen_policy','AdminListPolicy','View','html'));
      $this->mrTemplate->addVar('content','URL_SELEKSI',Dispatcher::Instance()->GetUrl('manajemen_policy','popupPegawai','View','html'));
       
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
      $array=array( 
			'satkerpolicy'	=> $_POST['satkerpolicy'],
			'tipe'			=> $_POST['tipe'],
			'nama'			=> $_POST['nama'],
			'keterangan'	=> $_POST['keterangan'],
			'url'			=> $_POST['url'],
			'is_aktif'		=> $status,
			'pengirim'		=> $sender,
			'tgl_policy'	=> $date,
			'user_id'		=> $userId,
			'id'			=> $_POST['id']
		);
      $result	= $this->mPolicy->UpdatePolicy($array);
	  $seleksi	= $_POST['seleksi'];
	  if($seleksi != "all"){
		  $getId	= $_POST['id'];
		  $this->mPolicy->DeletePegIdPolicy($getId);
		  for ($galihsam=0; $galihsam<sizeof($_POST['data']['pjb']['nip']); $galihsam++)
		  {
			  $nip		= $_POST['data']['pjb']['nip'][$galihsam];
			  $pejabat	= array(
				'id'	=> $getId,
				'nip'	=> $nip
			  );
			  // echo "<pre>"; print_r($nip);
			  $addDetail	= $this->mPolicy->AddDetailPolicy($pejabat);
		  }
		  // exit;
      }
	  
      if ($result===false){
          return 0;
      }
      return 1;
   }
}
?>