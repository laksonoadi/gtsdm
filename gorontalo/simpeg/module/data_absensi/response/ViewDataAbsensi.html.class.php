<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_absensi/business/AppAbsensi.class.php';
   
class ViewDataAbsensi extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_absensi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_absensi.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new AppAbsensi();
	  $this->Obj = new AppAbsensi();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
      
      if (!empty($_POST['btnsimpan'])){
        $this->POST=$_POST->AsArray();
        $pegawai=$Obj->GetKodeGateAccessByPegId($this->POST['pegawai']);
        $kodeAbsen=$pegawai['kode_absen'];
        $nama=$pegawai['nama'];
        $masuk=$this->POST['tglaju_year']."-".$this->POST['tglaju_mon']."-".$this->POST['tglaju_day']." ".$this->POST['start_jam'].":".$this->POST['start_menit'].":00";
        $keluar=$this->POST['tglaju_year']."-".$this->POST['tglaju_mon']."-".$this->POST['tglaju_day']." ".$this->POST['end_jam'].":".$this->POST['end_menit'].":00";
        if ($this->POST['dataId']!=''){
          $result=$Obj->DoUpdateAbsenManual($kodeAbsen,$nama,$masuk,$keluar,$this->POST['alasan'],$this->POST['dataId']);
          $label='Updated';
        }else{
          $result=$Obj->DoAddAbsenManual($kodeAbsen,$nama,$masuk,$keluar,$this->POST['alasan']);
          $label='Added';
        }
		
        if ($result){
          $this->Pesan=$label.' data presense manual berhasil';
          $this->css='notebox-done';
        }else{
          $this->Pesan=$label.' data presense manual gagal';
          $this->css='notebox-warning';
        }
      }
      
      if (!empty($_GET['dataId'])){
  		   $return['input']=$Obj->GetDataAbsenById($_GET['dataId']);
  		   $tgl=$return['input']['tgl'];
  		   $pegId=$return['input']['pegId'];
      }else{
         $tgl=date("Y-m-d");
      }
      
      $DataComboPegawai=$Obj->GetComboPegawai();
      $y1=date('Y')+4;
      
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegawai', array('pegawai',$DataComboPegawai,$pegId,'false',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglaju', array($tgl,'2003',$y1,'',''), Messenger::CurrentRequest);
  		
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dat = $data['input'];
      if($this->Pesan)
      {
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'MANUALLY PRESENSE DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA ABSENSI MANUAL');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
       }
      
      for ($i=1;$i<=24;$i++) {
        if ($i==$dat['masuk_jam']) {$selected1='Selected';} else {$selected1='';}
  	    if ($i==$dat['keluar_jam']) {$selected2='Selected';} else {$selected2='';}
	  	  if (strlen($i) == 1) {
  			 $string = '0'.$i;
  		  } else {
  			 $string = $i;
  		  }
  		  $this->mrTemplate->AddVar('start_jam','JAM',$string);
  		  $this->mrTemplate->AddVar('start_jam','SELECTED_START_JAM',$selected1);
  		  $this->mrTemplate->ParseTemplate('start_jam','a');
  		  $this->mrTemplate->AddVar('end_jam','JAM',$string);
  		  $this->mrTemplate->AddVar('end_jam','SELECTED_END_JAM',$selected2);
  		  $this->mrTemplate->ParseTemplate('end_jam','a');
  	  }
  	  
  	  for ($i=0;$i<=59;$i++) {
  	    if ($i==$dat['masuk_menit']) {$selected1='Selected';} else {$selected1='';}
  	    if ($i==$dat['keluar_menit']) {$selected2='Selected';} else {$selected2='';}
  	    
  	  	if (strlen($i) == 1) {
  			   $string = '0'.$i;
  		  } else {
  			   $string = $i;
  		  }
  		  $this->mrTemplate->AddVar('start_menit','MENIT',$string);
  		  $this->mrTemplate->AddVar('start_menit','SELECTED_START_MENIT',$selected1);
  		  $this->mrTemplate->ParseTemplate('start_menit','a');
  		  $this->mrTemplate->AddVar('end_menit','MENIT',$string);
  		  $this->mrTemplate->AddVar('end_menit','SELECTED_END_MENIT',$selected2);
  		  $this->mrTemplate->ParseTemplate('end_menit','a');
  	  }
	  
      if(isset($_GET['dataId'])){
        $op="edit";
        if ($buttonlang=='eng'){
          $oo=" Cancel ";
        }else{
          $oo=" Batal ";
        }
      }else{
        $op="add";
        $oo=" Reset ";
      }
      
	  $this->mrTemplate->addVar('content', 'HARILIBUR', $this->Obj->GetHariLibur());
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'ALASAN', $dat['alasan']);
      $this->mrTemplate->AddVar('content', 'ID', $dat['id']);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_absensi', 'dataAbsensi', 'view', 'html')); 
   }
}
   

?>