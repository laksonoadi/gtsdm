<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_cuti_massal/business/cuti_massal.class.php';
   
class ViewDataCutiMassal extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_cuti_massal/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_cuti_massal.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new CutiMassal();
      
      $y1=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'mulai', 
      array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'selesai', 
      array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
      
      //autonumber utk no.cuti
      $nmr = '0001/'.date('Y');
      #$check = $Obj->CekNmrCutiMassal($nmr);
      #print_r($check);exit;
      if($check[0]['no']!=""){
        $jmlDat = $Obj->GetTahunNo();
        $return['dataBaru'] = $Obj->GetNoBaru(date('Y'));
      }else{
        $jmlDat = "";
      }
      $return['dataNmr'] = $jmlDat;

      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];

  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'MASS LEAVE DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
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
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'historyDataCutiMassal', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'inputDataCutiMassal', 'do', 'html')); 
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEGAWAI', Dispatcher::Instance()->GetUrl('data_cuti_massal', 'popupPegawai', 'view', 'html'));
	  
      //autonumber utk no.cuti
      $jmlDat = $data['dataNmr'];
      if(empty($jmlDat)){
        $nmr = '0001/'.date('Y');
        $this->mrTemplate->AddVar('content', 'NO_CUTI_MASSAL', $nmr);
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
        $this->mrTemplate->AddVar('content', 'NO_CUTI_MASSAL', $tot);
      }
      
   }
}
   

?>