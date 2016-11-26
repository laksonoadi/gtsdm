<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_lembur_kompensasi/business/lembur.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataLembur extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_lembur_kompensasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
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
           $return['input']['tgl_aju'] = $resultLembur[0]['tglaju'];
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
          
          //autonumber utk no.lembur
          $nmr = '0001/'.date('Y');
          $check = $Obj->CekNmrLembur($nmr);
          if($check[0]['no']!=""){
            $jmlDat = $Obj->GetTahunNo();
            $return['dataBaru'] = $Obj->GetNoBaru(date('Y'));
          }else{
            $jmlDat = "";
          }
          $return['dataNmr'] = $jmlDat;
      } else
      if($_GET['op'] == 'edit'){
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'historyDataLembur', 'view', 'html') . '&dataId=' . $_GET['dataId']);
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_lembur_kompensasi', 'inputDataLembur', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['nama']);
      $this->mrTemplate->AddVar('content', 'SPV', $dataPegawaiSpv[0]['spv']);
      $this->mrTemplate->AddVar('content', 'MOR', $dataPegawaiMor[0]['mor']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      //autonumber utk no.lembur
      $jmlDat = $data['dataNmr'];
      if(empty($jmlDat)){
        $nmr = '0001/'.date('Y');
        $tot=$nmr;
        //$this->mrTemplate->AddVar('content', 'NO_LEMBUR', $nmr);
      }else{
        /*$tot = 1;
        for ($i=0; $i<sizeof($jmlDat); $i++) {
          if($jmlDat[$i]['tahun'] == date('Y')){
            $tot++;
          }
        }*/
        $tot = $data['dataBaru'][0]['nmr'];
        if(($tot>=1) and ($tot<10)){
          $tot = '000'.$tot;
        }elseif(($tot>=10) and ($tot<100)){
          $tot = '00'.$tot;
        }elseif(($tot>=100) and ($tot<1000)){
          $tot = '0'.$tot;
        }else{
          $tot = $tot;
        }
        $tot.='/'.date('Y');
        //$this->mrTemplate->AddVar('content', 'NO_LEMBUR', $tot);
      }
      
      if (empty($dat['no'])){
          $dat['no']=$tot;
      }
      $this->mrTemplate->AddVars('content', $dat, '');
      
   }
}
   

?>