<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/tunjangan_kesehatan/business/tunjangan_kesehatan.class.php';

class ViewInputTunjanganKesehatan extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/tunjangan_kesehatan/'.GTFWConfiguration::GetValue('application', 'template_address').'');

      $this->SetTemplateFile('view_input_tunjangan_kesehatan.html');
   }
   
   function ProcessRequest() {
      $Obj = new TunjanganKesehatan();
      if ($_GET['dataId'] != '') {
      $tunId = $_GET['dataId']->Integer()->Raw();
      $rs = $Obj->GetDatTunDetail($tunId);
      $result=$rs;
      //print_r($rs);
        if(!empty($result)){
          $return['input']['id'] = $result['id'];
          $return['input']['nikah'] = $result['nikah'];
          $return['input']['jenis'] = $result['jenis'];
          $return['input']['pla_uang'] = $result['pla_uang'];
          $return['input']['pla_persen'] = $result['pla_persen'];
          $return['input']['maks'] = $result['maks'];
          $return['input']['klaim'] = $result['klaim'];
          $return['input']['periode'] = $result['periode'];
        }else{
          $return['input']['id'] = '';
          $return['input']['nikah'] = '';
          $return['input']['jenis'] = '';
          $return['input']['pla_uang'] = '';
          $return['input']['pla_persen'] = '';
          $return['input']['maks'] = '';
          $return['input']['klaim'] = '';
          $return['input']['periode'] = '';
        }
	    }
	    $hub = $Obj->GetJenisTun();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
      array('jenis',$hub,$result['jenis'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
      
      $hub2 = $Obj->GetStatNikah();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'nikah', 
      array('nikah',$hub2,$result['nikah'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
            
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		$return['s'] = $tunId;
  		
	    return $return;
   }
   
   function ParseTemplate($data = NULL) {
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'HEALTH BENEFITS REFERENCE');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI TUNJANGAN KESEHATAN');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
       }
      if(isset($_GET['dataId'])){
        $op="edit";
      }else{
        $op="add";
      }
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('tunjangan_kesehatan','tunjanganKesehatan', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('tunjangan_kesehatan','inputTunjanganKesehatan', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'INPUT_ID', $dat['id']);
      $this->mrTemplate->AddVar('content', 'INPUT_NIKAH', $dat['nikah']);
      $this->mrTemplate->AddVar('content', 'INPUT_JENIS', $dat['jenis']);
      $this->mrTemplate->AddVar('content', 'INPUT_UANG', $dat['pla_uang']);
      $this->mrTemplate->AddVar('content', 'INPUT_PERSEN', $dat['pla_persen']);
      $this->mrTemplate->AddVar('content', 'INPUT_MAKS', $dat['maks']);
      $this->mrTemplate->AddVar('content', 'INPUT_KLAIM', $dat['klaim']);
      $this->mrTemplate->AddVar('content', 'INPUT_PERIODE', $dat['periode']);      
   }
}

?>