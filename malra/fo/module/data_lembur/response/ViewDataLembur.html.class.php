<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_lembur/business/lembur.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataLembur extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_lembur.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
      
      if ($_GET['dataId'] != '') {
          $pegId = $_GET['dataId']->Integer()->Raw();
          $lemburId = $_GET['dataId2']->Integer()->Raw();
          $Obj = new Lembur();
          $rs = $Obj->GetDataById($pegId);
          $resultLembur = $Obj->GetDataLemburDet($lemburId);
          $rs['detail'] = $rs[0];
          $rs['spv'] = $rs[1];
          $rs['mor'] = $rs[2];
          
          if($resultLembur){
           $return['input']['lemburId'] = $resultLembur[0]['id'];
           $return['input']['id'] = $resultLembur[0]['peg_id'];
           $return['input']['no'] = $resultLembur[0]['no'];
           $return['input']['tgl_aju'] = $resultLembur[0]['tglaju'];
           $return['input']['start_jam'] = $resultLembur[0]['start_jam'];
           $return['input']['start_menit'] = $resultLembur[0]['start_menit'];
           $return['input']['end_jam'] = $resultLembur[0]['end_jam'];
           $return['input']['end_menit'] = $resultLembur[0]['end_menit'];
           $return['input']['alasan'] = $resultLembur[0]['alasan'];
           $return['input']['status'] = $resultLembur[0]['status'];
           $return['input']['tgl_stat'] = $resultLembur[0]['tglstat'];
          }else{
             unset($_GET['dataId']);
          }
      }
      
      if($_GET['op'] == 'add'){
          $y1=date('Y')+4;
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglaju', 
          array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglstat', 
          array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
      } elseif($_GET['op'] == 'edit'){
          $y1=date('Y')+4;
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglaju', 
          array($return['input']['tgl_aju'],'2003',$y1,'',''), Messenger::CurrentRequest);
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglstat', 
          array($return['input']['tgl_stat'],'2003',$y1,'',''), Messenger::CurrentRequest);
          $return['dataNmr']=$return['input']['no'];
      }
      
  		$return['dataPegawai'] = $rs['detail'];
  		$return['dataPegawaiSpv'] = $rs['spv'];
  		$return['dataPegawaiMor'] = $rs['mor'];
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataPegawaiSpv = $data['dataPegawaiSpv'];
      $dataPegawaiMor = $data['dataPegawaiMor'];
      $dat = $data['input'];
      if($this->Pesan)
      {
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'OVERTIME WORK DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA LEMBUR');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');  
       }
      
      for ($i=1;$i<=24;$i++) {
	  	  if (strlen($i) == 1) {
  			 $string = '0'.$i;
  		  } else {
  			 $string = $i;
  		  }
  		  
  		  if ($dat['start_jam']==$string){
  		      $start_jam_selected="selected";
        } else {
            $start_jam_selected="";
        }
        if ($dat['end_jam']==$string){
  		      $end_jam_selected="selected";  		      
        }else{
            $end_jam_selected="";
        }
  		  $this->mrTemplate->AddVar('start_jam','JAM',$string);
  		  $this->mrTemplate->AddVar('start_jam','JAM_SELECTED',$start_jam_selected);
  		  $this->mrTemplate->ParseTemplate('start_jam','a');
  		  $this->mrTemplate->AddVar('end_jam','JAM',$string);
  		  $this->mrTemplate->AddVar('end_jam','JAM_SELECTED',$end_jam_selected);
  		  $this->mrTemplate->ParseTemplate('end_jam','a');
  	  }
  	  
  	  for ($i=0;$i<=59;$i++) {
  	  	if (strlen($i) == 1) {
  			$string = '0'.$i;
  		  } else {
  			 $string = $i;
  		  }
  		  
  		  if ($dat['start_menit']==$string){
  		      $start_menit_selected="selected";
        }else{
            $start_menit_selected="";
        }
        if ($dat['end_menit']==$string){
  		      $end_menit_selected="selected";
        }else{
            $end_menit_selected="";
        }
  		  $this->mrTemplate->AddVar('start_menit','MENIT',$string);
  		  $this->mrTemplate->AddVar('start_menit','MENIT_SELECTED',$start_menit_selected);
  		  $this->mrTemplate->ParseTemplate('start_menit','a');
  		  $this->mrTemplate->AddVar('end_menit','MENIT',$string);
  		  $this->mrTemplate->AddVar('end_menit','MENIT_SELECTED',$end_menit_selected);
  		  $this->mrTemplate->ParseTemplate('end_menit','a');
  	  }
	  
      if(isset($_GET['dataId2'])){
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
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_lembur', 'historyDataLembur', 'view', 'html') . '&dataId=' . $_GET['dataId']);
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_lembur', 'inputDataLembur', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['nama']);
      $this->mrTemplate->AddVar('content', 'SPV', $dataPegawaiSpv[0]['spv']);
      $this->mrTemplate->AddVar('content', 'MOR', $dataPegawaiMor[0]['mor']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
	  if (!empty($dat)) $this->mrTemplate->AddVars('content', $dat, '');
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      //$this->mrTemplate->AddVars('content', $dat, '');
      
   }
}
   

?>