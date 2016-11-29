<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_benefit/business/benefit.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataBenefit extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_benefit.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      if ($_GET['dataId'] != '') {
        $pegId = $_GET['dataId']->Integer()->Raw();
        $benefitId = $_GET['dataId2']->Integer()->Raw();
        $Obj = new Benefit();
        $rs = $Obj->GetDataById($pegId);
        $detailPegawai = $Obj->GetDetailPegawaiById($pegId);
        $resultBenefit = $Obj->GetDataBenefitDet($benefitId);
        $resultKlaim = $Obj->GetDataKlaimFromBenefitId($benefitId);

        if($resultBenefit){
         $return['input']['id'] = $resultBenefit[0]['id'];
         $return['input']['no'] = $resultBenefit[0]['no'];
         $return['input']['balancebenefit_id'] = $resultBenefit[0]['balancebenefit_id'];
         $return['input']['peg_id'] = $resultBenefit[0]['peg_id'];
         $return['input']['nama_pasien'] = $resultBenefit[0]['nama_pasien'];
         $return['input']['relasi_pasien'] = $resultBenefit[0]['relasi_pasien'];
         $return['input']['id_benefit'] = $resultBenefit[0]['benefit_id'];
         $return['input']['nama_benefit'] = $resultBenefit[0]['nama_benefit'];
         $return['input']['tgl_benefit'] = $resultBenefit[0]['tgl_benefit'];
         $return['input']['tgl_submit'] = $resultBenefit[0]['tgl_submit'];
         $return['input']['tempat'] = $resultBenefit[0]['tempat'];
         $return['input']['total_klaim'] = $Obj->num_todisplay($resultBenefit[0]['total_klaim']);
         $return['input']['alasan'] = $resultBenefit[0]['alasan'];
         $return['input']['tgl_klaim'] = $resultBenefit[0]['tgl_klaim'];
         $return['input']['status'] = $resultBenefit[0]['status'];
         $return['input']['user_id'] = $resultBenefit[0]['user_id'];
         $return['data']['klaim']=$resultKlaim;
         
        }else{
           unset($_GET['dataId']);
        }
        
        $this->Data = array('id_benefit'=>$return['input']['id_benefit'],'relasi_pasien'=>$return['input']['relasi_pasien']); 
      } else {
        $this->Data = array('id_benefit'=>$post['jenis_benefit'],'relasi_pasien'=>$post['input']['relasi_pasien']); 
      }
      
      if(isset($_GET['dataId'])){
      
      }else{
        $return['input']['total_klaim']='';
      }
    
      if($_GET['op'] == 'add'){
        //combo jenis benefit
        $jenisBenefit = $Obj->GetComboJenisBenefit();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_benefit', 
        array('jenis_benefit',$jenisBenefit,'','false',' style="width:170px;"'), Messenger::CurrentRequest);
        
        //combo jenis klaim
        $jenisKlaim = $Obj->GetComboJenisKlaim();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_klaim', 
        array('jenis_klaim',$jenisKlaim,'','false',' style="width:170px;"'), Messenger::CurrentRequest);
        
        //combo relasi pasien
        $relasi[0]['id'] = "sendiri";
        $relasi[0]['name'] = "Sendiri";
        $relasi[1]['id'] = "istri";
        $relasi[1]['name'] = "Istri";
        $relasi[2]['id'] = "anak";
        $relasi[2]['name'] = "Anak";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'relasi_pasien', 
        array('relasi_pasien',$relasi,'','',' style="width:100px;"'), Messenger::CurrentRequest);
        
        //combo status benefit
        $status[0]['id'] = "approved";
        $status[0]['name'] = "approved";
        $status[1]['id'] = "rejected";
        $status[1]['name'] = "rejected";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
        array('status',$status,'approved','',' style="width:100px;"'), Messenger::CurrentRequest);  
      
        $y1=date('Y')+4;
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_benefit', 
        array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_klaim', 
        array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
      } else {      
        //combo jenis benefit
        $jenisBenefit = $Obj->GetComboJenisBenefit();
        
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_benefit', 
        array('jenis_benefit',$jenisBenefit,$return['input']['id_benefit'],'false',' style="width:170px;"'), Messenger::CurrentRequest);
        
        //combo jenis klaim
        $jenisKlaim = $Obj->GetComboJenisKlaim();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_klaim', 
        array('jenis_klaim',$jenisKlaim,'','false',' style="width:170px;"'), Messenger::CurrentRequest);
        
        //combo relasi pasien
        $relasi[0]['id'] = "sendiri";
        $relasi[0]['name'] = "Sendiri";
        $relasi[1]['id'] = "istri";
        $relasi[1]['name'] = "Istri";
        $relasi[2]['id'] = "anak";
        $relasi[2]['name'] = "Anak";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'relasi_pasien', 
        array('relasi_pasien',$relasi,$return['input']['relasi_pasien'],'',' style="width:100px;"'), Messenger::CurrentRequest);
        
        //combo status benefit
        $status[0]['id'] = "approved";
        $status[0]['name'] = "approved";
        $status[1]['id'] = "rejected";
        $status[1]['name'] = "rejected";
  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
        array('status',$status,$return['input']['status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
        
        $y1=date('Y')+4;
        if($return['input']['tgl_benefit'] == '0000-00-00'){
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_benefit', 
          array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        } else {
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_benefit', 
          array($return['input']['tgl_benefit'],'2003',$y1,'',''), Messenger::CurrentRequest);
        }
        
        if($return['input']['tgl_klaim'] == '0000-00-00'){
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_klaim', 
          array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
        } else {
          Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_klaim', 
          array($return['input']['tgl_klaim'],'2003',$y1,'',''), Messenger::CurrentRequest);
        }
      }
      
      //autonumber utk no.benefit
      $nmr = '0001/'.date('Y');
      $check = $Obj->CekNmrBenefit($nmr);
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
  		$return['detailPegawai'] = $detailPegawai;
  		$return['dataBenefit'] = $resultBenefit;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $detailPegawai = $data['detailPegawai'];
      $dat = $data['input'];

      if($this->Pesan)
      {
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      if (!empty($data['data']['klaim'])){
         $Obj = new Benefit();
         $this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'NO');
         $dataKlaim=$data['data']['klaim'];
         for ($i=0; $i<sizeof($dataKlaim); $i++){
          $dataKlaim[$i]['nilai_klaim_label']=$Obj->num_todisplay($dataKlaim[$i]['nilai_klaim']);
          $this->mrTemplate->AddVars('tpl_claim_item', $dataKlaim[$i], 'DATA_');
          $this->mrTemplate->parseTemplate('tpl_claim_item', 'a');	
        }
      } else {
         $this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'YES');
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'OUTPATIENT CLAIM DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA KLAIM RAWAT JALAN');
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html') . '&dataId=' . $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_benefit', 'inputDataBenefit', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
      
      if($detailPegawai['jns_kelamin'] == 'L'){
        $detailPegawai['jns_kelamin'] = "Male";
      } else {
        $detailPegawai['jns_kelamin'] = "Female";
      }
      $this->mrTemplate->AddVars('detail_pegawai', $detailPegawai, 'DATA_');
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if($_GET['op'] == 'add'){
        //autonumber utk no.benefit
        $jmlDat = $data['dataNmr'];
        if(empty($jmlDat)){
          $nmr = '0001/'.date('Y');
          $this->mrTemplate->AddVar('content', 'NO_BENEFIT', $nmr);
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
          $this->mrTemplate->AddVar('content', 'NO_BENEFIT', $tot);
        }
      } else {
        $this->mrTemplate->AddVar('content', 'NO_BENEFIT', $dat['no']);
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