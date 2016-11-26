<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_saudara_kandung/business/data_saudara_kandung.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataSaudaraKandung extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_saudara_kandung/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_saudara_kandung.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new SaudaraKandung();
      $rs = $Obj->GetDataById($pegId);
      $dataSdr = $Obj->GetDataSdr($pegId);
        if ($_GET['dataId2'] != '') {
          $sdrId = $_GET['dataId2']->Integer()->Raw();
          $dataSdrDet = $Obj->GetDataSdrDet($sdrId);
          $result=$dataSdrDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['jenkel'] = $result['jenkel'];
            $return['input']['nama'] = $result['nama'];
            $return['input']['tmpt'] = $result['tmpt'];
            $return['input']['lahir'] = $result['tgl_lahir'];
            $return['input']['kerja'] = $result['kerja'];
            $return['input']['ket'] = $result['ket'];
            $return['input']['educ'] = $result['educ'];
			$return['input']['mati'] = $result['meninggal_status'];
          }else{
            $return['input']['id'] = '';
            $return['input']['jenkel'] = '';
            $return['input']['nama'] = '';
            $return['input']['tmpt'] = '';
            $return['input']['lahir'] = '';
            $return['input']['kerja'] = '';
            $return['input']['ket'] = '';
            $return['input']['educ'] = '';
			$return['input']['mati'] = '';
          }
        }
      }
      
      if ($_GET['aksi']=='ya'){
        $return['display_list']='none';
      }else{
        $return['display_form']='none';
      }
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
  	   if ($lang=='eng'){
          $ll="Male";
          $pp="Female";
		  $yy="Yes";
          $nn="No";
		  
		  /*$educ = array(	0=>array('id'=>"BLM",'name'=>"Not Yet School"),
							1=>array('id'=>"TK",'name'=>"Kindergarden"),
							2=>array('id'=>"SD",'name'=>"Elementary School"),
							3=>array('id'=>"SMP",'name'=>"Junior School"),
							4=>array('id'=>"SMA",'name'=>"Senior School"),
							5=>array('id'=>"D1",'name'=>"Diploma 1"),
							6=>array('id'=>"D2",'name'=>"Diploma 2"),
							7=>array('id'=>"D3",'name'=>"Diploma 3"),
							8=>array('id'=>"S1",'name'=>"Bachelor (S1)"),
							9=>array('id'=>"S2",'name'=>"Master (S2)"),
							10=>array('id'=>"S3",'name'=>"Doctoral (S3)"));*/
       }else{
          $ll="Laki-Laki";  
          $pp="Perempuan";
		  $yy="Ya";
          $nn="Tidak";
		  
		  /*$educ = array(	0=>array('id'=>"Non",'name'=>"Belum/Tidak Sekolah"),
							1=>array('id'=>"TK",'name'=>"Taman Kanak-kanak"),
							2=>array('id'=>"SD",'name'=>"SD/MI"),
							3=>array('id'=>"SMP",'name'=>"SMP/MTs"),
							4=>array('id'=>"SMA",'name'=>"SMA/SMAK/MA"),
							5=>array('id'=>"D1",'name'=>"Diploma I (D1)"),
							6=>array('id'=>"D2",'name'=>"Diploma II (D2)"),
							7=>array('id'=>"D3",'name'=>"Diploma III (D3)"),
							8=>array('id'=>"S1",'name'=>"Sarjana (S1)"),
							9=>array('id'=>"S2",'name'=>"Master (S2)"),
							10=>array('id'=>"S3",'name'=>"Doctor (S3)"));*/
       }
	   
      $educ = $Obj->GetDataRefPendidikan();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'educ', 
      array('educ',$educ,$result['educ'],'',' style="width:100px;"'), Messenger::CurrentRequest);
	   
      $hub = array(0=>array('id'=>L,'name'=>$ll),1=>array('id'=>P,'name'=>$pp));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenkel', 
      array('jenkel',$hub,$result['jenkel'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $akhirT=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_lahir', 
      array($result['tgl_lahir'],$akhirT-100,$akhirT,'',''), Messenger::CurrentRequest);
      
	  $mati = array(0=>array('id'=>1,'name'=>$nn),1=>array('id'=>0,'name'=>$yy));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'mati', 
      array('mati',$mati,$result['meninggal_status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
	  
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataSdr'] = $dataSdr;
  		$return['dataSdrDet'] = $dataSdrDet;
  		$return['idPegawai'] = $pegId;
  		$return['lang'] = $lang;
  		//$return['idIstri'] = $istId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataSdr = $data['dataSdr'];
      $dataSdrDet = $data['dataSdrDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'SIBLING DATA');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
         $this->mrTemplate->AddVar('content', 'BUTTONLABEL', isset($_GET['dataId2']) ? 'Update' : 'Add');
         $op=isset($_GET['dataId2']) ? 'edit' : 'add';
         $oo=isset($_GET['dataId2']) ? 'Cancel' : 'Reset';
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA SAUDARA KANDUNG');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
         $this->mrTemplate->AddVar('content', 'BUTTONLABEL', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
         $op=isset($_GET['dataId2']) ? 'edit' : 'add';
         $oo=isset($_GET['dataId2']) ? 'Batal' : 'Reset';
     }
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_saudara_kandung', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_saudara_kandung', 'inputDataSaudaraKandung', 'do', 'html'));
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_saudara_kandung', 'dataSaudaraKandung', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'DISPLAY_LIST', $data['display_list']);
      $this->mrTemplate->AddVar('content', 'DISPLAY_FORM', $data['display_form']); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
      
      $this->mrTemplate->AddVar('content', 'INPUT_ID', $dat['id']);
      $this->mrTemplate->AddVar('content', 'INPUT_NAMA', $dat['nama']);
      $this->mrTemplate->AddVar('content', 'INPUT_TMPT', $dat['tmpt']);
      $this->mrTemplate->AddVar('content', 'INPUT_KERJA', $dat['kerja']);
      $this->mrTemplate->AddVar('content', 'INPUT_KET', $dat['ket']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if (empty($dataSdr)) {
  			$this->mrTemplate->AddVar('data_sdr', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_sdr', 'PEGAWAI_EMPTY', 'NO');
  
  //mulai bikin tombol delete
  			$total=0;
        $start=1;
  			$idEnc2 = Dispatcher::Instance()->Encrypt($data['idPegawai']);
        for ($i=0; $i<count($dataSdr); $i++) {
  				$no = $i+$start;
  				$dataSdr[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataSdr[$i]['class_name'] = 'table-common-even';
          }else{
            $dataSdr[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataSdr)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataSdr[$i]['id']);
          
          $urlAccept = 'data_saudara_kandung|deleteDataSaudaraKandung|do|html-dataId-'.$idEnc2;
          $urlKembali = 'data_saudara_kandung|dataSaudaraKandung|view|html-dataId-'.$idEnc2;
          $dataName = $dataSdr[$i]['nama'];
          $dataSdr[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataSdr[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_saudara_kandung','dataSaudaraKandung', 'view', 'html').'&dataId2='. $idEnc.'&dataId='. $idEnc2.'&aksi=ya';
            
          if($dataSdr[$i]['tgl_lahir']=="0000-00-00"){
            $dataSdr[$i]['tgl_lahir']="";
          }else{
            if ($data['lang']=='eng'){
               $dataSdr[$i]['tgl_lahir']=$this->periode2stringenglish($dataSdr[$i]['tgl_lahir']);   
            }else {
               $dataSdr[$i]['tgl_lahir']=$this->periode2string($dataSdr[$i]['tgl_lahir']);
            }
            
          }
  				$this->mrTemplate->AddVars('data_sdr_item', $dataSdr[$i], 'SDR_');
  				$this->mrTemplate->parseTemplate('data_sdr_item', 'a');	 
  			}
  		}
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
	   return $tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	function periode2stringenglish($date) {
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
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
   

?>