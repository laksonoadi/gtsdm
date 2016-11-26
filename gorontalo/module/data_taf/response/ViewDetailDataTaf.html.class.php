<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_taf/business/taf.class.php';
   
class ViewDetailDataTaf extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_taf/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_taf.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Taf();
      $rs = $Obj->GetDataById($pegId);
      $detailPegawai = $Obj->GetDetailPegawaiById($pegId);
      
        if ($_GET['dataId2'] != '') {
          $tafId = $_GET['dataId2']->Integer()->Raw();
          //detail taf
          $dataTafDet = $Obj->GetDataTafDet($tafId);
          $result=$dataTafDet[0];
          
          $resultTravel = $Obj->GetTravelByTafId($tafId);
          $resultTransport = $Obj->GetTransportByTafId($tafId);
          $resultAllowance = $Obj->GetAllowanceByTafId($tafId);
          $resultBudget = $Obj->GetBudgetByTafId($tafId);
          
          if(!empty($result)){
            $return['input']['id'] = $result['tafId'];
            $return['input']['taf_number'] = $result['tafNo'];
            $return['input']['tipe_taf'] = $result['jnstafNama'];
            $return['input']['tgl_aju'] = $result['tafTglPengajuan'];
            $return['input']['alasan'] = $result['tafAlasan'];
            $return['input']['supv_status'] = $result['tafStatusSpv'];
            $return['input']['supv_tanggal'] = $result['tafTglStatusSpv'];
            $return['input']['hrd_status'] = $result['tafStatusHRD'];
            $return['input']['hrd_tanggal'] = $result['tafTglStatusHRD'];
            $return['input']['fin_status'] = $result['tafStatusFin'];
            $return['input']['fin_tanggal'] = $result['tafTglStatusFin'];
            $return['input']['total_hari'] = $result['tafTotalHariKeseluruhan'];
            $return['input']['total_anggaran'] = $result['tafTotalAnggaran'];
            $return['data']['travel']=$resultTravel;
            $return['data']['transport']=$resultTransport;
            $return['data']['allowance']=$resultAllowance;
            $return['data']['budget']=$resultBudget;
          }else{
            $return['input']['id'] = '';
            $return['input']['taf_number'] = '';
            $return['input']['tipe_taf'] = '';
            $return['input']['tgl_aju'] = '';
            $return['input']['alasan'] = '';
            $return['input']['supv_status'] = '';
            $return['input']['supv_tanggal'] = '';
            $return['input']['hrd_status'] = '';
            $return['input']['hrd_tanggal'] = '';
            $return['input']['fin_status'] = '';
            $return['input']['fin_tanggal'] = '';
            $return['input']['total_hari'] = '';
            $return['input']['total_anggaran'] = '';
            $return['data']['travel']='';
            $return['data']['transport']='';
            $return['data']['allowance']='';
            $return['data']['budget']='';
          }
          
        }
      }
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['detailPegawai'] = $detailPegawai;
  		$return['dataTafDet'] = $dataTafDet;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $Obj = new Taf();
      $dataPegawai = $data['dataPegawai'];
      $detailPegawai = $data['detailPegawai'];
      $dataTafDet = $data['dataTafDet'];
      $dat = $data['input'];
      $dataTravel=$data['data']['travel'];
      $dataTransport=$data['data']['transport'];
      $dataAllowance=$data['data']['allowance'];
      $dataBudget=$data['data']['budget'];

      if($this->Pesan)
      {
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $idEnc = Dispatcher::Instance()->Encrypt($data['idPegawai']);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_taf', 'historyDataTaf', 'view', 'html') . '&dataId=' . $idEnc );
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'TRAVEL DATA');
         $dat['tgl_aju'] = $Obj->periode2stringEng($dat['tgl_aju']);
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PERJALANAN');
         $dat['tgl_aju'] = $Obj->periode2string($dat['tgl_aju']);
       }
      
      
      $this->mrTemplate->AddVars('content', $dat, 'DATA_');
      if($detailPegawai['jns_kelamin'] == 'L'){
        $detailPegawai['jns_kelamin'] = "Male";
      } else {
        $detailPegawai['jns_kelamin'] = "Female";
      }
      $this->mrTemplate->AddVars('detail_pegawai', $detailPegawai, 'DATA_');
      
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
  		if (empty($dataAllowance)) {
  			$this->mrTemplate->AddVar('tpl_allowance_list', 'ALLOWANCE_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_allowance_list', 'ALLOWANCE_LIST_EMPTY', 'NO');
  			$total=0;
  			for ($i=0; $i<sizeof($dataAllowance); $i++) {
  			
  			  //Header
  			  if ($i==0){
  			    $subtotal=0;
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
  			    $this->mrTemplate->AddVar('tpl_allowance_subtotal', 'DATA_SUB_TOTAL_LABEL',$subtotal_label);       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_subtotal', 'a');
          } else
          if ($dataAllowance[$i]['tujuan_id']!=$dataAllowance[$i+1]['tujuan_id']){
            $this->mrTemplate->AddVar('tpl_subtotal', 'SUBTOTAL', 'YES');
  			    $subtotal_label = $Obj->num_todisplay($subtotal); 
  			    $this->mrTemplate->AddVar('tpl_allowance_subtotal', 'DATA_SUB_TOTAL_LABEL',$subtotal_label);       
  				  $this->mrTemplate->parseTemplate('tpl_allowance_subtotal', 'a');
  				  $subtotal=0;
          } else {
            $this->mrTemplate->AddVar('tpl_subtotal', 'SUBTOTAL', 'NO');
          }
                 
  				$this->mrTemplate->parseTemplate('tpl_allowance_item', 'a');
  				$this->mrTemplate->clearTemplate('tpl_allowance_header');
  				$this->mrTemplate->clearTemplate('tpl_allowance_subtotal');
  			}
        
        $total_label = $Obj->num_todisplay($total);  
        $this->mrTemplate->AddVar('content', 'DATA_TOTAL_ALLOWANCE_LABEL', $total_label);    
  	 }
  	 
  	 //Budget
  		if (empty($dataBudget)) {
  			$this->mrTemplate->AddVar('tpl_budget_list', 'BUDGET_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_budget_list', 'BUDGET_LIST_EMPTY', 'NO');
  			$total=0;
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
       $total_label = $Obj->num_todisplay($total);  
       $this->mrTemplate->AddVar('content', 'TOTAL_BUDGET_LABEL', $total_label); 
  	 }
   }
}
   

?>