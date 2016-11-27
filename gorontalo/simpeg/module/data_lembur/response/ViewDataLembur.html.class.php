<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_lembur/business/lembur.class.php';
   
class ViewDataLembur extends HtmlResponse
{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_data_lembur.html');
	}
   
	function ProcessRequest(){
		if ($_GET['dataId'] != '') {
			$pegId = $_GET['dataId']->Integer()->Raw();
			$Obj = new Lembur();
			$rs = $Obj->GetDataById($pegId);
			$rs['detail'] = $rs[0];
			$rs['spv'] = $rs[1];
			$rs['mor'] = $rs[2];
		  
			if ($_GET['dataId2'] != '') {
				$dataId = $_GET['dataId2']->Integer()->Raw();
				$rs2 = $Obj->GetDataLemburDet($dataId);
				$data['input']['dataId2']=$rs2[0]['id'];
				$data['input']['no_lembur']=$rs2[0]['no'];
				$data['input']['tglaju']=$rs2[0]['tglaju'];
				$data['input']['mulai_jam']=$rs2[0]['mulai_jam'];
				$data['input']['mulai_menit']=$rs2[0]['mulai_menit'];
				$data['input']['selesai_jam']=$rs2[0]['selesai_jam'];
				$data['input']['selesai_menit']=$rs2[0]['selesai_menit'];
				$data['input']['alasan']=$rs2[0]['alasan'];
				$data['input']['status']=$rs2[0]['status'];
				$data['input']['tglstat']=$rs2[0]['tglstat'];
				if ($data['input']['tglstat']=='') $data['input']['tglstat']=date('Y-m-d');
				$data['input']['op']='edit';
			}else{
				$data['input']['no_lembur']=$Obj->GetNumber('overtime');
				$data['input']['tglaju']=date('Y-m-d');
				$data['input']['mulai_jam']='';
				$data['input']['mulai_menit']='';
				$data['input']['selesai_jam']='';
				$data['input']['selesai_menit']='';
				$data['input']['alasan']='';
				$data['input']['status']='approved';
				$data['input']['tglstat']=date('Y-m-d');
				$data['input']['op']='add';
			}
		}
      
		$y1=date('Y')+4;
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglaju', 
		array($data['input']['tglaju'],'2003',$y1,'',''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglstat', 
		array($data['input']['tglstat'],'2003',$y1,'',''), Messenger::CurrentRequest);
		
		$tipe[0]['id'] = "request";
		$tipe[0]['name'] = "Dalam Proses";
		$tipe[1]['id'] = "approved";
		$tipe[1]['name'] = "Disetujui";
		$tipe[2]['id'] = "rejected";
		$tipe[2]['name'] = "Ditolak";
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
		array('status',$tipe,$data['input']['status'],'',' style="width:130px;"'), Messenger::CurrentRequest);

		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
  		
		$return['input'] = $data['input'];
		$return['dataPegawai'] = $rs['detail'];
		$return['dataPegawaiSpv'] = $rs['spv'];
		$return['dataPegawaiMor'] = $rs['mor'];
		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL){
		$dataPegawai = $data['dataPegawai'];
		$dataPegawaiSpv = $data['dataPegawaiSpv'];
		$dataPegawaiMor = $data['dataPegawaiMor'];
		$dat = $data['input'];
		if($this->Pesan){
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
      
		for ($i=0;$i<=23;$i++) {
			if (strlen($i) == 1) {
				$string = '0'.$i;
			} else {
				$string = $i;
			}
			
			$mulai_jam='';
			if ($dat['mulai_jam']==$string){$mulai_jam='selected';}
			$selesai_jam='';
			if ($dat['selesai_jam']==$string){$selesai_jam='selected';}
			
			$this->mrTemplate->AddVar('start_jam','JAM',$string);
			$this->mrTemplate->AddVar('start_jam','SELECTED',$mulai_jam);
			$this->mrTemplate->ParseTemplate('start_jam','a');
			$this->mrTemplate->AddVar('end_jam','JAM',$string);
			$this->mrTemplate->AddVar('end_jam','SELECTED',$selesai_jam);
			$this->mrTemplate->ParseTemplate('end_jam','a');
		}
  	  
		for ($i=0;$i<=59;$i++) {
			if (strlen($i) == 1) {
				$string = '0'.$i;
			} else {
				$string = $i;
			}
			
			$mulai_menit='';
			if ($dat['mulai_menit']==$string){$mulai_menit='selected';}
			$selesai_menit='';
			if ($dat['selesai_menit']==$string){$selesai_menit='selected';}
			
			$this->mrTemplate->AddVar('start_menit','MENIT',$string);
			$this->mrTemplate->AddVar('start_menit','SELECTED',$mulai_menit);
			$this->mrTemplate->ParseTemplate('start_menit','a');
			$this->mrTemplate->AddVar('end_menit','MENIT',$string);
			$this->mrTemplate->AddVar('end_menit','SELECTED',$selesai_menit);
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
      
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
			$this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
		}else{
			$this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
		}
      
		$this->mrTemplate->AddVars('content', $dat, 'INPUT_');
      
   }
}
   

?>