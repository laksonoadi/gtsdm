<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/persetujuan_cuti/business/cuti.class.php';
   
class ViewInputAppCuti extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/persetujuan_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_input_app_cuti.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Cuti();
      $rs = $Obj->GetDataById($pegId);
        if ($_GET['dataId2'] != '') {
          $cutId = $_GET['dataId2']->Integer()->Raw();
          $dataCutiDet = $Obj->GetDataCutiDet($cutId);
          $result=$dataCutiDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no'];
            $return['input']['aju'] = $result['tglaju'];
            $return['input']['submit'] = $result['tglsub'];
            $return['input']['mulai'] = $result['tglmul'];
            $return['input']['selesai'] = $result['tglsel'];
            $return['input']['nama'] = $result['nama_cuti'];
            $return['input']['idcuti'] = $result['id_cuti'];
            $return['input']['alasan'] = $result['alasan'];
            $return['input']['status'] = $result['status'];
            $return['input']['tglstat'] = $result['tglstat'];
            $return['input']['tggjwb'] = $result['tggkerja'];
            $return['input']['pggjwb'] = $result['pggsmnt'];
            $return['input']['pggjwbk'] = $result['pggsmntk'];
          }else{
            $return['input']['id'] = '';
            $return['input']['no'] = '';
            $return['input']['aju'] = '';
            $return['input']['submit'] = '';
            $return['input']['mulai'] = '';
            $return['input']['selesai'] = '';
            $return['input']['nama'] = '';
            $return['input']['idcuti'] = '';
            $return['input']['alasan'] = '';
            $return['input']['status'] = '';
            $return['input']['tglstat'] = '';
            $return['input']['tggjwb'] = '';
            $return['input']['pggjwb'] = '';
            $return['input']['pggjwbk'] = '';
          }
          
          $appCutiDet = $Obj->GetAppCutiDet($cutId);
          //print_r($appCutiDet[1]);
          $result2 = $appCutiDet[0];
          $result3 = $appCutiDet[1];
          if(!empty($result2)){
            $return['input2']['ids'] = $result2['idsatker'];
            $return['input2']['nms'] = $result2['nmsatker'];
            $return['input2']['idp'] = $result2['idpegawai'];
            $return['input2']['nmp'] = $result2['nmpegawai'];
            $return['input2']['stat'] = $result2['status'];
          }else{
            $return['input2']['ids'] = '';
            $return['input2']['nms'] = '';
            $return['input2']['idp'] = '';
            $return['input2']['nmp'] = '';
            $return['input2']['stat'] = '';
          }
          if(!empty($result3)){
            $return['input3']['ids'] = $result3['idsatker'];
            $return['input3']['nms'] = $result3['nmsatker'];
            $return['input3']['idp'] = $result3['idpegawai'];
            $return['input3']['nmp'] = $result3['nmpegawai'];
            $return['input3']['stat'] = $result3['status'];
          }else{
            $return['input3']['ids'] = '';
            $return['input3']['nms'] = '';
            $return['input3']['idp'] = '';
            $return['input3']['nmp'] = '';
            $return['input3']['stat'] = '';
          }
        }
      }
      
      $status[0]['id'] = "approved";
      $status[0]['name'] = "approved";
      $status[1]['id'] = "rejected";
      $status[1]['name'] = "rejected";
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status1', 
      array('status1',$status,$result2['status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status2', 
      array('status2',$status,$result3['status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dat = $data['input'];
      $dat2 = $data['input2'];
      $dat3 = $data['input3'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE APPROVAL');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'PERSETUJUAN CUTI');
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'appDataCuti', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'inputAppCuti', 'do', 'html')); 
      
      $id1 = Dispatcher::Instance()->Encrypt(1);
      $id2 = Dispatcher::Instance()->Encrypt(2);
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_1', Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'popupPegawai', 'view', 'html').'&dataPeg='. $id1); 
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_2', Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'popupPegawai', 'view', 'html').'&dataPeg='. $id2); 
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai['alamat']);
      $this->mrTemplate->AddVar('content', 'DATA_SATKER', $dataPegawai['satker']);
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if ($buttonlang=='eng'){
         $dat['aju'] = $this->periode2stringEng($dat['aju']);
         $dat['submit'] = $this->periode2stringEng($dat['submit']);
         $dat['mulai'] = $this->periode2stringEng($dat['mulai']);
         $dat['selesai'] = $this->periode2stringEng($dat['selesai']);
         $dat['tglstat'] = $this->periode2stringEng($dat['tglstat']);
       }else{
         $dat['aju'] = $this->periode2string($dat['aju']);
         $dat['submit'] = $this->periode2string($dat['submit']);
         $dat['mulai'] = $this->periode2string($dat['mulai']);
         $dat['selesai'] = $this->periode2string($dat['selesai']);
         $dat['tglstat'] = $this->periode2string($dat['tglstat']);
       }
      
      $this->mrTemplate->AddVars('content', $dat, '');
      $this->mrTemplate->AddVars('content', $dat2, '2_');
      $this->mrTemplate->AddVars('content', $dat3, '3_');
      
   }
   
   function periode2string($date) {
	   $bln = array(
	        1  => 'Januari',
					2  => 'Februari',
					3  => 'Maret',
					4  => 'April',
					5  => 'Mei',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'Agustus',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Desember'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}
   

?>