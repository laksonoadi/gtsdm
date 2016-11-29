<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/persetujuan_lembur/business/lembur.class.php';
   
class ViewInputAppLembur extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/persetujuan_lembur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_input_app_lembur.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Lembur();
      $rs = $Obj->GetDataById($pegId);
      $dat['detail'] = $rs[0][0];
      $dat['spv'] = $rs[1][0];
      $dat['mor'] = $rs[2][0];

        if ($_GET['dataId2'] != '') {
          $cutId = $_GET['dataId2']->Integer()->Raw();
          $dataLemburDet = $Obj->GetDataLemburDet($cutId);
          $result=$dataLemburDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no'];
            $return['input']['tglaju'] = $result['tglaju'];
            $return['input']['tglsubmit'] = $result['tglsub'];
            $return['input']['mulai'] = $result['mulai'];
            $return['input']['selesai'] = $result['selesai'];
            $return['input']['durasi'] = $result['durasi'];
            $return['input']['nama'] = $result['nama_lembur'];
            $return['input']['idlembur'] = $result['id_lembur'];
            $return['input']['alasan'] = $result['alasan'];
            $return['input']['status'] = $result['status'];
            $return['input']['tglstat'] = $result['tglstat'];
          }else{
            $return['input']['id'] = '';
            $return['input']['no'] = '';
            $return['input']['tglaju'] = '';
            $return['input']['tglsubmit'] = '';
            $return['input']['mulai'] = '';
            $return['input']['selesai'] = '';
            $return['input']['durasi'] = '';
            $return['input']['nama'] = '';
            $return['input']['idlembur'] = '';
            $return['input']['alasan'] = '';
            $return['input']['status'] = '';
            $return['input']['tglstat'] = '';
          }
      }
    }  
      $status[0]['id'] = "approved";
      $status[0]['name'] = "approved";
      $status[1]['id'] = "rejected";
      $status[1]['name'] = "rejected";
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
      array('status',$status,$result['status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $akhirT=date('Y')+5;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tglstat', 
      array(date("Y-m-d"),'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $dat['detail'];
  		$return['dataPegawaiSpv'] = $dat['spv'];
  		$return['dataPegawaiMor'] = $dat['mor'];
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
         $this->mrTemplate->AddVar('content', 'TITLE', 'OVERTIME WORK APPROVAL');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'PERSETUJUAN LEMBUR');
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'appDataLembur', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('persetujuan_lembur', 'inputAppLembur', 'do', 'html')); 
      
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_SPV', $dataPegawaiSpv['spv']);
      $this->mrTemplate->AddVar('content', 'DATA_MOR', $dataPegawaiMor['mor']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai['alamat']);
      $this->mrTemplate->AddVar('content', 'DATA_SATKER', $dataPegawai['satker']);
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if ($buttonlang=='eng'){
         $dat['tglaju'] = $this->periode2stringEng($dat['tglaju']);
         $dat['tglsub'] = $this->periode2stringEng($dat['tglsub']);
         $dat['durasi'] = $this->time2stringEng($dat['durasi']);
         $dat['tglstat'] = $this->periode2stringEng($dat['tglstat']);
       }else{
         $dat['tglaju'] = $this->periode2string($dat['tglaju']);
         $dat['tglsub'] = $this->periode2string($dat['tglsub']);
         $dat['durasi'] = $this->time2string($dat['durasi']);
         $dat['tglstat'] = $this->periode2string($dat['tglstat']);
       }
      
      $this->mrTemplate->AddVars('content', $dat, '');
      
   }
   
   function time2string($time) {
	   $hour = array(
	        '00'  => '0',
					'01'  => '1',
					'02'  => '2',
					'03'  => '3',
					'04'  => '4',
					'05'  => '5',
					'06'  => '6',
					'07'  => '7',
					'08'  => '8',
					'09'  => '9',
					'10' => '10',
					'11'  => '11',
					'12'  => '12',
					'13'  => '13',
					'14'  => '14',
					'15'  => '15',
					'16'  => '16',
					'17'  => '17',
					'18'  => '18',
					'19'  => '19',
					'20' => '20',
          '21'  => '21',
					'22'  => '22',
					'23'  => '23'					
	               );
	   $jam = substr($time,0,2);
	   $menit = substr($time,-2);
	   return $jam[(int)$hour].' jam '.$menit.' menit';
	}
	
	function time2stringEng($time) {
	   $hour = array(
	        '00'  => '0',
					'01'  => '1',
					'02'  => '2',
					'03'  => '3',
					'04'  => '4',
					'05'  => '5',
					'06'  => '6',
					'07'  => '7',
					'08'  => '8',
					'09'  => '9',
					'10' => '10',
					'11'  => '11',
					'12'  => '12',
					'13'  => '13',
					'14'  => '14',
					'15'  => '15',
					'16'  => '16',
					'17'  => '17',
					'18'  => '18',
					'19'  => '19',
					'20' => '20',
          '21'  => '21',
					'22'  => '22',
					'23'  => '23'					
	               );
	   $jam = substr($time,0,2);
	   $menit = substr($time,-2);
	   return $jam[(int)$hour].' hour '.$menit.' minutes';
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