<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_taf/business/taf.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataTaf extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_taf/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_taf.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      if ($_GET['dataId'] != '') {
        $pegId = $_GET['dataId']->Integer()->Raw();
        $tafId = $_GET['dataId2']->Integer()->Raw();
        $Obj = new Taf();
        $rs = $Obj->GetDataById($pegId);
        $detailPegawai = $Obj->GetDetailPegawaiById($pegId);
        
        $resultTaf = $Obj->GetDataTafDet($tafId);
        $resultTravel = $Obj->GetTravelByTafId($tafId);
        $resultTransport = $Obj->GetTransportByTafId($tafId);
        $resultAllowance = $Obj->GetAllowanceByTafId($tafId);
        $resultBudget = $Obj->GetBudgetByTafId($tafId);
        

        if($resultTaf){
         $return['input']['id'] = $resultTaf[0]['tafid'];
         $return['input']['no'] = $resultTaf[0]['tafNo'];
         $return['input']['tipe'] = $resultTaf[0]['tafJnstafId'];
         $return['input']['tgl_aju'] = $resultTaf[0]['tafTglPengajuan'];
         $return['input']['alasan'] = $resultTaf[0]['tafAlasan'];
         
         $return['data']['travel']=$resultTravel;
         $return['data']['transport']=$resultTransport;
         $return['data']['allowance']=$resultAllowance;
         $return['data']['budget']=$resultBudget;
         
        }else{
           unset($_GET['dataId']);
        }
        
        $this->Data = array('id_taf'=>$return['input']['id_taf'],'relasi_pasien'=>$return['input']['relasi_pasien']); 
      } else {
        $this->Data = array('id_taf'=>$post['jenis_taf'],'relasi_pasien'=>$post['input']['relasi_pasien']); 
      }
      
      $kebijakan = $Obj->GetDetailKebijakan($detailPegawai['grade']);
      $zona = $Obj->GetDetailZona();
      
      $nilai='';
      for ($i=0; $i<sizeof($kebijakan['kebijakan']); $i++){
         for ($ii=0; $ii<sizeof($kebijakan['kebijakan'][$i]); $ii++){
            $nilai .= "#|".$kebijakan['jenis_taf'][$i][$ii].'-'.$kebijakan['zona'][$i][$ii].'|';
            for ($iii=0; $iii<sizeof($kebijakan['kebijakan'][$i][$ii]);$iii++){
                $nilai .= $kebijakan['kebijakan'][$i][$ii][$iii]['kebijakan_id'];
                $nilai .= "-".$kebijakan['kebijakan'][$i][$ii][$iii]['deskripsi'];
                $nilai .= "-".$kebijakan['kebijakan'][$i][$ii][$iii]['allowance'];
                $nilai .= "-".$kebijakan['kebijakan'][$i][$ii][$iii]['curr_id'];
                $nilai .= "-".$kebijakan['kebijakan'][$i][$ii][$iii]['currency']."|";
            }
         }
      }
      $nilai .="#";
      
      $nilai_zona='|';
      for ($i=0; $i<sizeof($zona); $i++){
         $nilai_zona .= $zona[$i]['kota_id']."-".$zona[$i]['jenis_id']."-".$zona[$i]['zona_id']."|";
      }
      
      $tipe = Dispatcher::Instance()->Decrypt($_GET['tipe']->Raw());
      
      $return['input']['tgl_mulai']=date("Y-m-d");
      $return['input']['tgl_selesai']=date("Y-m-d");
      $return['input']['tgl_transport_etd']=date("Y-m-d");
      $return['input']['tgl_transport_eta']=date("Y-m-d");
      $return['input']['periode']=date("Y-m-d");
      $return['input']['start_jam'] = $resultLembur[0]['start_jam'];
      $return['input']['start_menit'] = $resultLembur[0]['start_menit'];
      $return['input']['end_jam'] = $resultLembur[0]['end_jam'];
      $return['input']['end_menit'] = $resultLembur[0]['end_menit'];
          
      if($_GET['op'] == 'add'){
          $return['input']['tgl_aju']=date("Y-m-d");
          if (empty($tipe)) { $tipe=1; }       
      }else{
          if (empty($tipe)) {$tipe=$return['input']['tipe']; }
      }
      
      $y1=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_aju', array($return['input']['tgl_aju'],'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_mulai', array($return['input']['tgl_mulai'],'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_selesai', array($return['input']['tgl_selesai'],'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_transport_etd', array($return['input']['tgl_transport_etd'],'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_transport_eta', array($return['input']['tgl_transport_eta'],'2003',$y1,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'periode', array($return['input']['periode'],'2003',$y1,'',''), Messenger::CurrentRequest);
      
      //Combo tipe TAF
      $comboTipe=$Obj->GetComboTipe();
      $url_itself=Dispatcher::Instance()->GetUrl('data_taf', 'dataTaf', 'view', 'html');
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', array('tipe',$comboTipe,$tipe,'false','  onChange="js_next(\''.$url_itself.'\')" '), Messenger::CurrentRequest);
      
      //Combo Tipe Transportasi
      $comboTipeTransport=$Obj->GetComboTipeTransportasi();
      $url_itself=Dispatcher::Instance()->GetUrl('data_taf', 'dataTaf', 'view', 'html');
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe_transport', array('tipe_transport',$comboTipeTransport,'','false','  '), Messenger::CurrentRequest);
      
      //Combo Tujuan
      $comboTujuan=$Obj->GetComboTujuan($tipe);
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tujuan_travel', array('tujuan_travel',$comboTujuan,'','false',' '), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tujuan_transport', array('tujuan_transport',$comboTujuan,'','false',' '), Messenger::CurrentRequest);
      
      //Combo Budget
      $comboBudget=$Obj->GetComboBudget();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'budget_id', array('budget_id',$comboBudget,'','false',' '), Messenger::CurrentRequest);
 
      
      //autonumber utk no.taf
      $nmr = '0001/'.date('Y');
      $check = $Obj->CekNmrTaf($nmr);
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
  		$return['dataTaf'] = $resultTaf;
  		$return['idPegawai'] = $pegId;
  		$return['nilai']=$nilai;
  		$return['nilai_zona']=$nilai_zona;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $Obj = new Taf();
      $dataPegawai = $data['dataPegawai'];
      $detailPegawai = $data['detailPegawai'];
      $dat = $data['input'];
      $dataTravel=$data['data']['travel'];
      $dataTransport=$data['data']['transport'];
      $dataAllowance=$data['data']['allowance'];
      $dataBudget=$data['data']['budget'];
      
      $this->mrTemplate->AddVar('content', 'URL_ITSELF', Dispatcher::Instance()->GetUrl('data_taf', 'dataTaf', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'OP', $_GET['op']);
      $this->mrTemplate->AddVar('content', 'ID_PEG', $detailPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_ID', $_GET['dataId2']->Integer()->Raw());
      $this->mrTemplate->AddVar('content', 'NILAI_KEBIJAKAN', $data['nilai']);
      $this->mrTemplate->AddVar('content', 'NILAI_ZONA', $data['nilai_zona']);

      if($this->Pesan)
      {
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      //Untuk Periode
      $tahunSekarang=date('Y');
      $bulanSekarang=date('m');
      for ($i=$tahunSekarang-10; $i<=$tahunSekarang+10; $i++){
           if ($tahunSekarang==$i){
  		        $selected="selected";
           } else {
              $selected="";
           }  
           $this->mrTemplate->AddVar('periode_tahun','TAHUN',$i);
  		     $this->mrTemplate->AddVar('periode_tahun','TAHUN_SELECTED',$selected);
  		     $this->mrTemplate->ParseTemplate('periode_tahun','a'); 
      }
      
      for ($i=1;$i<=12;$i++) {
	  	  if (strlen($i) == 1) {
  			 $string = '0'.$i;
  		  } else {
  			 $string = $i;
  		  }
  		  if ($bulanSekarang==$string){
  		      $selected="selected";
        } else {
            $selected="";
        }
        
  		  $this->mrTemplate->AddVar('periode_bulan','BULAN',$string);
  		  $this->mrTemplate->AddVar('periode_bulan','BULAN_SELECTED',$selected);
  		  $this->mrTemplate->ParseTemplate('periode_bulan','a');
  	  }
      
      //Untuk Jam
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
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
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
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_taf', 'historyDataTaf', 'view', 'html') . '&dataId=' . $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_taf', 'inputDataTaf', 'do', 'html')); 
      
      if($detailPegawai['jns_kelamin'] == 'L'){
        $detailPegawai['jns_kelamin'] = "Male";
      } else {
        $detailPegawai['jns_kelamin'] = "Female";
      }
      $this->mrTemplate->AddVars('detail_pegawai', $detailPegawai, 'DATA_');
      
      if($_GET['op'] == 'add'){
        //autonumber utk no.taf
        $jmlDat = $data['dataNmr'];
        if(empty($jmlDat)){
          $nmr = '0001/'.date('Y');
          $this->mrTemplate->AddVar('content', 'NO_TAF', $nmr);
        }else{
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
          $this->mrTemplate->AddVar('content', 'NO_TAF', $tot);
        }
      } else {
        $this->mrTemplate->AddVar('content', 'NO_TAF', $dat['no']);
      }
     
      
      $status = $dat['status'];
      if(empty($status)){       
        $this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:visible");
      } else {
        $this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:none");
      } 
      
      $this->mrTemplate->AddVars('content', $dat, 'DATA_');
      
      //Travel
  		if (empty($dataTravel)) {
  			$this->mrTemplate->AddVar('tpl_travel_list', 'TRAVEL_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_travel_list', 'TRAVEL_LIST_EMPTY', 'NO');
  			for ($i=0; $i<sizeof($dataTravel); $i++) {
  				if ($i % 2 != 0) {
            $dataTravel[$i]['class_name'] = 'table-common-even';
          }else{
            $dataTravel[$i]['class_name'] = '';
          }
          $dataTravel[$i]['tgl_awal_label'] = $Obj->periode2stringEngWithDay($dataTravel[$i]['tgl_awal']);
          $dataTravel[$i]['tgl_akhir_label'] = $Obj->periode2stringEngWithDay($dataTravel[$i]['tgl_akhir']);
          $this->mrTemplate->AddVars('tpl_travel_item', $dataTravel[$i], 'DATA_');       
  				$this->mrTemplate->parseTemplate('tpl_travel_item', 'a');
  			}   
  	 }
  	 
  	 //Tranposrt
  		if (empty($dataTransport)) {
  			$this->mrTemplate->AddVar('tpl_transport_list', 'TRANSPORT_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_transport_list', 'TRANSPORT_LIST_EMPTY', 'NO');
  			for ($i=0; $i<sizeof($dataTransport); $i++) {
  				if ($i % 2 != 0) {
            $dataTransport[$i]['class_name'] = 'table-common-even';
          }else{
            $dataTransport[$i]['class_name'] = '';
          }
          $dataTransport[$i]['tgl_awal_label'] = $Obj->periode2stringEng2($dataTransport[$i]['tgl_awal']);
          $dataTransport[$i]['tgl_akhir_label'] = $Obj->periode2stringEng2($dataTransport[$i]['tgl_akhir']);
          $dataTransport[$i]['anggaran_label'] = $Obj->num_todisplay($dataTransport[$i]['anggaran']);
          $this->mrTemplate->AddVars('tpl_transport_item', $dataTransport[$i], 'DATA_');       
  				$this->mrTemplate->parseTemplate('tpl_transport_item', 'a');
  			}   
  	 }
  	 
  	 //Allowance
  	 $total=0;
  		if (empty($dataAllowance)) {
  			$this->mrTemplate->AddVar('tpl_allowance_list', 'ALLOWANCE_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_allowance_list', 'ALLOWANCE_LIST_EMPTY', 'NO');
  			$j=0;
  			for ($i=0; $i<sizeof($dataAllowance); $i++) {
  			  $j++;
  			  $dataAllowance[$i]['nomor']=$j;
          $dataAllowance[$i]['jumlah_kebijakan']=$j;
          $dataAllowance[$i]['jumlah_kebijakan_plus1']=$j+1; 
  			  //Header
  			  if ($i==0){
  			    $subtotal=0; $j=1;
  			    $this->mrTemplate->AddVar('tpl_header', 'HEADER', 'YES');
  			    $temp=$dataAllowance[$i]['tujuan_id'];
  			    $this->mrTemplate->AddVars('tpl_allowance_header', $dataAllowance[$i], 'DATA_');       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_header', 'a');
          } else
          if ($temp!=$dataAllowance[$i]['tujuan_id']){
            $this->mrTemplate->AddVar('tpl_header', 'HEADER', 'YES');
            $temp=$dataAllowance[$i]['tujuan_id'];
  			    $this->mrTemplate->AddVars('tpl_allowance_header', $dataAllowance[$i], 'DATA_');       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_header', 'a');
          } else
          {
            $this->mrTemplate->AddVar('tpl_header', 'HEADER', 'NO');
          }
          
  			  $total +=$dataAllowance[$i]['total'];
  			  $subtotal +=$dataAllowance[$i]['total'];
  			  
  			  //Isi Datanya
  				if ($i % 2 != 0) {
            $dataAllowance[$i]['class_name'] = 'table-common-even';
          }else{
            $dataAllowance[$i]['class_name'] = '';
          }
          $dataAllowance[$i]['nilai_label'] = $Obj->num_todisplay($dataAllowance[$i]['nilai']);
          $dataAllowance[$i]['total_label'] = $Obj->num_todisplay($dataAllowance[$i]['total']);
          $this->mrTemplate->AddVars('tpl_allowance_item', $dataAllowance[$i], 'DATA_');
          
          //Ini Subtotalnya
          if ($i==sizeof($dataAllowance)-1){
  			    $this->mrTemplate->AddVar('tpl_subtotal', 'SUBTOTAL', 'YES');
  			    $subtotal_label = $Obj->num_todisplay($subtotal);
  			    $this->mrTemplate->AddVars('tpl_allowance_subtotal', $dataAllowance[$i], 'DATA_');
  			    $this->mrTemplate->AddVar('tpl_allowance_subtotal', 'DATA_SUB_TOTAL_LABEL',$subtotal_label);       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_subtotal', 'a');
          } else
          if ($dataAllowance[$i]['tujuan_id']!=$dataAllowance[$i+1]['tujuan_id']){
            $this->mrTemplate->AddVar('tpl_subtotal', 'SUBTOTAL', 'YES');
  			    $subtotal_label = $Obj->num_todisplay($subtotal); 
  			    $this->mrTemplate->AddVars('tpl_allowance_subtotal', $dataAllowance[$i], 'DATA_');
  			    $this->mrTemplate->AddVar('tpl_allowance_subtotal', 'DATA_SUB_TOTAL_LABEL',$subtotal_label);       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_subtotal', 'a');
  				  $subtotal=0; $j=0;
          } else {
            $this->mrTemplate->AddVar('tpl_subtotal', 'SUBTOTAL', 'NO');
          }
                 
  				$this->mrTemplate->parseTemplate('tpl_allowance_item', 'a');
  				$this->mrTemplate->clearTemplate('tpl_allowance_header');
  				$this->mrTemplate->clearTemplate('tpl_allowance_subtotal');
  			}    
  	 }
  	 $total_label = $Obj->num_todisplay($total);
  	 $this->mrTemplate->AddVar('content', 'TOTAL_ALLOWANCE', $total);  
     $this->mrTemplate->AddVar('content', 'DATA_TOTAL_ALLOWANCE_LABEL', $total_label);
  	 
  	 //Budget
  	 $total=0;
  		if (empty($dataBudget)) {
  			$this->mrTemplate->AddVar('tpl_budget_list', 'BUDGET_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_budget_list', 'BUDGET_LIST_EMPTY', 'NO');
  			for ($i=0; $i<sizeof($dataBudget); $i++) {
  			  $total += $dataBudget[$i]['anggaran'];
  				if ($i % 2 != 0) {
            $dataBudget[$i]['class_name'] = 'table-common-even';
          }else{
            $dataBudget[$i]['class_name'] = '';
          }
          $dataBudget[$i]['anggaran_label'] = $Obj->num_todisplay($dataBudget[$i]['anggaran']); 
          $this->mrTemplate->AddVars('tpl_budget_item', $dataBudget[$i], 'DATA_');       
  				$this->mrTemplate->parseTemplate('tpl_budget_item', 'a');
  		 }
      }
     $total_label = $Obj->num_todisplay($total);
     $this->mrTemplate->AddVar('content', 'TOTAL_BUDGET', $total);  
     $this->mrTemplate->AddVar('content', 'TOTAL_BUDGET_LABEL', $total_label);
   }
}
   

?>