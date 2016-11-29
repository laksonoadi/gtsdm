<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataCuti extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/approval_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_cuti.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      
      if ($_GET['dataId'] != '') {
        $pegId = $_GET['dataId']->Integer()->Raw();
        $cutiId = $_GET['dataId2']->Integer()->Raw();
        $return['pilihpegawai'] = $_GET['pilihpegawai']->Integer()->Raw();
        $return['year'] = $_GET['year']->Integer()->Raw();
        
        $Obj = new Cuti();
        $rs = $Obj->GetDataById($return['pilihpegawai']);
        $resultCuti = $Obj->GetDataCutiDet($cutiId);
        $dataTanggal = $Obj->GetTanggalCuti($cutiId);
        
        $return['input']['email'] = $Obj->GetEmail($return['pilihpegawai']);
        
        $rs['sisa']=$Obj->GetSisaCuti($return['pilihpegawai'],$return['year']);
        $return['sisa_kompensasi']=$Obj->GetSisaCutiKompensasi($return['pilihpegawai'],$return['year']);

        if($resultCuti){
         $return['input']['id'] = $resultCuti[0]['id'];
         $return['input']['peg_id'] = $resultCuti[0]['peg_id'];
         $return['input']['no'] = $resultCuti[0]['no'];
         $return['input']['tgl_aju'] = $resultCuti[0]['tglaju'];
         $return['input']['tgl_sub'] = $resultCuti[0]['tglsub'];
         $return['input']['tgl_awal'] = $resultCuti[0]['tglmul'];
         $return['input']['tgl_selesai'] = $resultCuti[0]['tglsel'];
         $return['input']['id_cuti'] = $resultCuti[0]['id_cuti'];
         $return['input']['nama_cuti'] = $resultCuti[0]['nama_cuti'];
         $return['input']['reduced'] = $resultCuti[0]['reduced'];
         $return['input']['alasan'] = $resultCuti[0]['alasan'];
         $return['input']['status'] = $resultCuti[0]['status'];
         $return['input']['tgl_stat'] = $resultCuti[0]['tglstat'];
         $return['input']['tggjwbker'] = $resultCuti[0]['tggkerja'];
         $return['input']['pggjwbsmnt'] = $resultCuti[0]['pggsmnt'];
         $return['input']['pggjwbsmntk'] = $resultCuti[0]['pggsmntk'];
         $return['date_leave']=$dataTanggal;

        }else{
           unset($_GET['dataId']);
        }
        
        $this->Data = array('id_cuti'=>$return['input']['id_cuti'],'reduced'=>$return['input']['reduced']); 
      } else {
        $this->Data = array('id_cuti'=>$post['tipe'],'reduced'=>$post['input']['reduced']); 
      }
      
      if(isset($_GET['dataId'])){
      
      }else{
        $return['input']['total']='';
      }
    
      if($_GET['op'] == 'add'){
        //combo tipe cuti
        $tipe = $Obj->GetComboTipe();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', 
        array('tipe',$tipe,'','false',' style="width:170px;" disabled=true '), Messenger::CurrentRequest);
        
        //combo reduced cuti
        $reduced[0]['id'] = "Yes";
        $reduced[0]['name'] = "Yes";
        $reduced[1]['id'] = "No";
        $reduced[1]['name'] = "No";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'reduced', 
        array('reduced',$reduced,'','false',' style="width:100px;"'), Messenger::CurrentRequest);
        
        //combo status cuti
        $status[0]['id'] = "approved";
        $status[0]['name'] = "approved";
        $status[1]['id'] = "rejected";
        $status[1]['name'] = "rejected";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
        array('status',$status,'approved','false',' style="width:100px;"'), Messenger::CurrentRequest);  
      
        $y1=date('Y')+4;
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_mulai', 
        array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_selesai', 
        array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_stat', 
        array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
      } else {      
        //combo tipe cuti
        $tipe = $Obj->GetComboTipe();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', 
        array('tipe',$tipe,$return['input']['id_cuti'],'false',' style="width:170px;" '), Messenger::CurrentRequest);
        
        $return['input']['tipe_label']=$return['input']['nama_cuti'];
        $return['input']['tipe']=$return['input']['id_cuti'];
        //combo reduced cuti
        $reduced[0]['id'] = "Yes";
        $reduced[0]['name'] = "Yes";
        $reduced[1]['id'] = "No";
        $reduced[1]['name'] = "No";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'reduced', 
        array('reduced',$reduced,$return['input']['reduced'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
        
        //combo status cuti
        if($_GET['approved']=='no'){
          $status[0]['id'] = "request";
          $status[0]['name'] = "request";
          if ($return['input']['status']=='approved'){
             $status[1]['id'] = "rejected";
             $status[1]['name'] = "rejected";
          } else {
              $status[1]['id'] = "approved";
              $status[1]['name'] = "approved";
          }
          $return['input']['status']="request";
        } else {
          $status[0]['id'] = "approved";
          $status[0]['name'] = "approved";
          $status[1]['id'] = "rejected";
          $status[1]['name'] = "rejected";
        }
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
        array('status',$status,$return['input']['status'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
        
        $y1=date('Y')+4;
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_mulai', 
        array($return['input']['tgl_awal'],'2003',$y1,'',''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_selesai', 
        array($return['input']['tgl_selesai'],'2003',$y1,'',''), Messenger::CurrentRequest);
        
        if($return['input']['tgl_stat'] == '0000-00-00'){
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_stat', 
          array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        } else {
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_stat', 
          array($return['input']['tgl_stat'],'2003',$y1,'',''), Messenger::CurrentRequest);
        }
      }
      
      //autonumber utk no.cuti
      $nmr = '0001/'.date('Y');
      $check = $Obj->CekNmrCuti($nmr);
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
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataCuti'] = $resultCuti;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dat = $data['input'];
      
      if (sizeof($data['date_leave'])>0){
        $this->mrTemplate->AddVar('count_days', 'DAYS_EMPTY', 'NO');
        
        $Obj = new Cuti();
        $jum=0;
        for ($i=0; $i<sizeof($data['date_leave']); $i++){
          $tgl=$data['date_leave'][$i]['tanggal'];
          if ($data['date_leave'][$i]['status']=='Aktif'){
              $aktif='checked';
              $jum++;
          } else {
              $aktif='';
          }
          $this->mrTemplate->AddVar('data_count_days', 'NO', $i);
          $this->mrTemplate->AddVar('data_count_days', 'TANGGAL', $tgl);
          $this->mrTemplate->AddVar('data_count_days', 'CHECKED', $aktif);
          $this->mrTemplate->AddVar('data_count_days', 'TANGGAL_LABEL', $Obj->IndonesianDate($tgl,'YYYY-MM-DD'));
          $this->mrTemplate->AddVar('data_count_days', 'TANGGAL_ID', $data['date_leave'][$i]['id']);
          $this->mrTemplate->parseTemplate('data_count_days', 'a');	
        }
      } else {
        $this->mrTemplate->AddVar('count_days', 'DAYS_EMPTY', 'YES');
      }
      $dat['total_ambil']=$jum;

      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'APPROVAL LEAVE DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', $_GET['approved']=='no' ? 'Cancelling Approval' : 'Approval');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', $_GET['approved']=='no' ? 'Ubah' : 'Tambah');  
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
      $this->mrTemplate->AddVar('content', 'PILIHPEGAWAI', $data['pilihpegawai']);
      $this->mrTemplate->AddVar('content', 'YEAR', $data['year']);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('approval_cuti', 'historyDataCuti', 'view', 'html') . '&dataId=' . $dataPegawai['id'].'&pilihpegawai='.$data['pilihpegawai']);
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('approval_cuti', 'inputDataCuti', 'do', 'html').'&op='.$op); 
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      if ($data['sisa_kompensasi']>0) {
        $dat['sisa_cuti']=$dataPegawai['sisa'];
        $dat['sisa_cuti_kompensasi']=$data['sisa_kompensasi'];
        $dataPegawai['sisa'] .= ' reguler + '.$data['sisa_kompensasi'].' Compensation';
      } else {
        $dat['sisa_cuti']=$dataPegawai['sisa'];
        $dat['sisa_cuti_kompensasi']=0;
        $dataPegawai['sisa'] .= ' reguler';
      }
      $this->mrTemplate->AddVar('content', 'DATA_SISA', $dataPegawai['sisa']);
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if($_GET['op'] == 'add'){
        //autonumber utk no.cuti
        $jmlDat = $data['dataNmr'];
        if(empty($jmlDat)){
          $nmr = '0001/'.date('Y');
          $this->mrTemplate->AddVar('content', 'NO_CUTI', $nmr);
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
          $this->mrTemplate->AddVar('content', 'NO_CUTI', $tot);
        }
      } else {
        $this->mrTemplate->AddVar('content', 'NO_CUTI', $dat['no']);
      }
     
      
      $status = $dat['status'];
      if(empty($status)){       
        $this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:visible");
      } else {
        $this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:none");
      } 
      
      $this->mrTemplate->AddVars('content', $dat, 'DATA_');
   }
}
   

?>