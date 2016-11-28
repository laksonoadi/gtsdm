<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_cuti/business/cuti.class.php';

class ViewDataCuti extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/data_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_data_cuti.html');
	}
   
	function ProcessRequest(){
		if ($_GET['dataId'] != '') {
			$pegId = $_GET['dataId']->Integer()->Raw();
			$cutiId = $_GET['dataId2']->Integer()->Raw();
			$Obj = new Cuti();
			$this->Obj=$Obj;
			$rs = $Obj->GetDataById($pegId);
			$resultCuti = $Obj->GetDataCutiDet($cutiId);
			$this->periodeCuti = $Obj->GetPeriodeCutiByPegId($pegId);

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
				
				$return['input']['durasisebelum']=$resultCuti[0]['durasi'];
				$return['input']['statussebelum']=$resultCuti[0]['status'];
				$return['input']['reducedsebelum']=$resultCuti[0]['reduced'];
				$return['input']['sisacuti']=$this->periodeCuti[0]['cutipersisa'];

			}else{
				$return['input']['durasisebelum']=0;
				$return['input']['statussebelum']='request';
				$return['input']['reducedsebelum']='No';
				$return['input']['sisacuti']=$this->periodeCuti[0]['cutipersisa'];
				unset($_GET['dataId']);
			}
        
			$this->Data = array('id_cuti'=>$return['input']['id_cuti'],'reduced'=>$return['input']['reduced']); 
		} else {
			$this->Data = array('id_cuti'=>$post['tipe'],'reduced'=>$post['input']['reduced']); 
		}
      
		if(!isset($_GET['dataId'])){
			$return['input']['total']='';
		}
	  
		//combo reduced cuti
		$arrReduced[0]['id'] = "Yes";
		$arrReduced[0]['name'] = "Ya";
		$arrReduced[1]['id'] = "No";
		$arrReduced[1]['name'] = "Tidak";
	  
		//combo status cuti
		$arrStatus[0]['id'] = "request";
		$arrStatus[0]['name'] = "Dalam Proses";
		$arrStatus[1]['id'] = "approved";
		$arrStatus[1]['name'] = "Disetujui";
		$arrStatus[2]['id'] = "rejected";
		$arrStatus[2]['name'] = "Ditolak";
	  
		//combo tipe cuti
		$arrTipe = $Obj->GetComboTipe();
	  
		if($_GET['op'] == 'add'){
			$reduced='';
			$status='request';
			$tipe='';
			$tgl_awal=date("Y-m-d");
			$tgl_selesai=date("Y-m-d");
			$tgl_stat=date("Y-m-d");
		}else{
			$reduced=$return['input']['reduced'];
			$status=$return['input']['status'];
			$tipe=$return['input']['id_cuti'];
			$tgl_awal=$return['input']['tgl_awal'];
			$tgl_selesai=$return['input']['tgl_selesai'];
			if($return['input']['tgl_stat'] == '0000-00-00'){
				$tgl_stat=date("Y-m-d");
			} else {
				$tgl_stat=$return['input']['tgl_stat'];
			}
		}
	  
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', array('tipe',$arrTipe,$tipe,'false',' style="width:170px;"'), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'reduced', array('reduced',$arrReduced,$reduced,'false',' style="width:100px;" '), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status',$arrStatus,$status,'false',' style="width:150px;"'), Messenger::CurrentRequest);  
	  
		$y1=date('Y')+4;
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_mulai', array($tgl_awal,'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_selesai', array($tgl_selesai,'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_stat', array($tgl_stat,'2003',$y1,'',''), Messenger::CurrentRequest);
    
      
      
		$return['dataNmr'] = $jmlDat;

		$msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataCuti'] = $resultCuti;
  		$return['idPegawai'] = $pegId;
  		return $return;
	}
   
	function ParseTemplate($data = NULL){
		$dataPegawai = $data['dataPegawai'];
		$dat = $data['input'];

		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      
		$buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($buttonlang=='eng'){
			$this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE DATA');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
		}else{
			$this->mrTemplate->AddVar('content', 'TITLE', 'DATA CUTI');
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
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html') . '&dataId=' . $dataPegawai['id']);
		$this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_cuti', 'inputDataCuti', 'do', 'html')); 
      
		$this->mrTemplate->AddVar('content', 'ID', $dataPegawai['id']);
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
		$this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
		$this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
      
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
			$this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
			$this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
		}
      
		if($_GET['op'] != 'add'){
			$this->mrTemplate->AddVar('content', 'NO_CUTI', $dat['no']);
		}
     
      
		$status = $dat['status'];
		if(empty($status)){       
			$this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:visible");
		} else {
			$this->mrTemplate->AddVar('content', 'VISIBILITY_TR', "visibility:none");
		} 
      
		$dat['HARILIBUR']=$this->Obj->GetHariLibur();
		$this->mrTemplate->AddVars('content', $dat, 'DATA_');
	}
}
   

?>