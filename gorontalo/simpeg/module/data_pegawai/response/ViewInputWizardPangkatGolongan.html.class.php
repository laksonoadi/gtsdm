<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';

class ViewInputWizardPangkatGolongan extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_input_wizard_pangkat_golongan.html');
      }
      
      function ProcessRequest() 
      {
      	$pg = new MutasiPangkatGolongan();
      
      	$msg = Messenger::Instance()->Receive(__FILE__);
      	$this->Data = $msg[0][0];
        $this->Pesan = $msg[0][1];
        $this->css = $msg[0][2];
        $post = $this->Data;
      
      	$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      	$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      	$this->profilId=$id;
      
      	$arrjp = $pg->GetComboJenisPegawai();
      	$arrpktgol = $pg->GetComboPangkatGolongan();
      
        $tahun=array();
        $dataPegawai = $pg->GetDataDetail($id);
        if($post) {
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['jenisid'] = $post['jenis_pegawai'];
            $return['input']['golongan'] = $post['golongan_ref'];
            $return['input']['tmt'] = $post['tmt_day'].'-'.$post['tmt_mon'].'-'.$post['tmt_year'];
            $return['input']['pejabat'] = $post['pejabat'];
            $return['input']['nosk'] = $post['sk_no'];
            $return['input']['tgl_sk'] = $post['tgl_sk_day'].'-'.$post['tgl_sk_mon'].'-'.$post['tgl_sk_year'];
            $return['input']['dasar'] = $post['dasar'];
            $return['input']['tgl_naik'] = $post['tgl_naik_day'].'-'.$post['tgl_naik_mon'].'-'.$post['tgl_naik_year'];
            $return['input']['status'] = $post['status'];
        } else {
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['jenisid'] = '';
            $return['input']['golongan'] = '';
            $return['input']['tmt'] = date("Y-m-d");
            $return['input']['pejabat'] = '';
            $return['input']['nosk'] = '';
            $return['input']['tgl_sk'] = date("Y-m-d");
            $return['input']['dasar'] = '';
            $return['input']['tgl_naik'] = date("Y-m-d");
            $return['input']['status'] = '';
        }
        
        if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-50;
	}
        	

	$tahun['end'] = date("Y")+5;

	//set the language
	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	if ($lang=='eng'){
      		$labeldel=Dispatcher::Instance()->Encrypt('Grade Mutation');
		      $active = "Active"; $inactive = "Inactive";
      	}else{
      		$labeldel=Dispatcher::Instance()->Encrypt('Mutasi Pangkat/ Golongan');
		      $active = "Aktif"; $inactive = "Tidak Aktif";
      	}
      	$return['lang']=$lang;
         
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tmt', array($return['input']['tmt'], $tahun['start'], $tahun['end'], '', '', 'tmt'), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_naik', array($return['input']['tgl_naik'], $tahun['start'], $tahun['end'], '', '', 'tgl_naik'), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_pegawai', array('jenis_pegawai', $arrjp, $return['input']['jenisid'], '', ' style="width:100px;" '), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['golongan'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
        $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status " style="width:200px;" '), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;


  		   
  	return $return;  
      }
      
      function ParseTemplate($data = NULL)
      {
       if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $dataPegawai = $data['dataPegawai'];
      
      if ($data['lang']=='eng'){
      	$this->mrTemplate->AddVar('content', 'TITLE', 'GRADE MUTATION');
      	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
      	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
      	#$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
      }else{
      	$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI PANGKAT/GOLONGAN');
      	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah'); 
        #$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');   
      } 

      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      
        $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'addWizardPangkatGolongan', 'do', 'html'));
      
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_pegawai', 'DataPegawai', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'MutasiPangkatGolongan', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         
        $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		    $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	    }
      
      }
      
      function date2string($date) {
	   $bln = array(
	            1  => '01',
					2  => '02',
					3  => '03',
					4  => '04',
					5  => '05',
					6  => '06',
					7  => '07',
					8  => '08',
					9  => '09',
					10 => '10',
					11 => '11',
					12 => '12'					
	               );
	   $arrtgl = explode('-',$date);
	   return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];
	   
	}
   }
?>
