<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_orang_tua/business/data_orang_tua.class.php';
   
class ViewDataOrangTua extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_orang_tua/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_orang_tua.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new OrangTua();
      $rs = $Obj->GetDataById($pegId);
      $dataOrtu = $Obj->GetDataOrtu($pegId);
        if ($_GET['dataId2'] != '') {
          $ortuId = $_GET['dataId2']->Integer()->Raw();
          $dataOrtuDet = $Obj->GetDataOrtuDet($ortuId);
          $result=$dataOrtuDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['hub'] = $result['hub'];
            $return['input']['nama'] = $result['nama'];
            $return['input']['gelar_depan'] = $result['gelar_depan'];
            $return['input']['gelar_belakang'] = $result['gelar_belakang'];
            $return['input']['tmpt'] = $result['tmpt'];
            $return['input']['lahir'] = $result['tgl_lahir'];
            $return['input']['agama_id'] = $result['agama_id'];
            $return['input']['alamat'] = $result['alamat'];
            $return['input']['kerja'] = $result['kerja'];
            $return['input']['ket'] = $result['ket'];
            $return['input']['educ'] = $result['educ'];
            $return['input']['telp'] = $result['telp'];
            $return['input']['mati'] = $result['mati'];
            $return['input']['mati_ori'] = $result['meninggal_status_ori'];
            $return['input']['tgl_meninggal'] = $result['tgl_meninggal'];
            $return['input']['no_akta_meninggal'] = $result['no_akta_meninggal'];
            $return['input']['akta_meninggal'] = $result['akta_meninggal'];
          }else{
            $return['input']['id'] = '';
            $return['input']['hub'] = '';
            $return['input']['nama'] = '';
            $return['input']['gelar_depan'] = '';
            $return['input']['gelar_belakang'] = '';
            $return['input']['tmpt'] = '';
            $return['input']['lahir'] = '';
            $return['input']['agama_id'] = '';
            $return['input']['alamat'] = '';
            $return['input']['kerja'] = '';
            $return['input']['ket'] = '';
            $return['input']['educ'] = '';
            $return['input']['telp'] = '';
            $return['input']['mati'] = '';
            $return['input']['mati_ori'] = '';
            $return['input']['tgl_meninggal'] = '';
            $return['input']['no_akta_meninggal'] = '';
            $return['input']['akta_meninggal'] = '';
          }
        }
      }
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
  	   if ($lang=='eng'){
          $ll="Father";
          $pp="Mother";
          $yy="Yes";
          $nn="No";
		  
		  $educ = array(	0=>array('id'=>"BLM",'name'=>"Not Yet School"),
							1=>array('id'=>"TK",'name'=>"Kindergarden"),
							2=>array('id'=>"SD",'name'=>"Elementary School"),
							3=>array('id'=>"SMP",'name'=>"Junior School"),
							4=>array('id'=>"SMA",'name'=>"Senior School"),
							5=>array('id'=>"D1",'name'=>"Diploma 1"),
							6=>array('id'=>"D2",'name'=>"Diploma 2"),
							7=>array('id'=>"D3",'name'=>"Diploma 3"),
							8=>array('id'=>"S1",'name'=>"Bachelor (S1)"),
							9=>array('id'=>"S2",'name'=>"Master (S2)"),
							10=>array('id'=>"S3",'name'=>"Doctoral (S3)"));
       }else{
          $ll="Ayah";  
          $pp="Ibu";
          $yy="Ya";
          $nn="Tidak";
		  
		  $educ = array(	0=>array('id'=>"Non",'name'=>"Belum/Tidak Sekolah"),
							1=>array('id'=>"TK",'name'=>"Taman Kanak-kanak"),
							2=>array('id'=>"SD",'name'=>"SD/MI"),
							3=>array('id'=>"SMP",'name'=>"SMP/MTs"),
							4=>array('id'=>"SMA",'name'=>"SMA/SMAK/MA"),
							5=>array('id'=>"D1",'name'=>"Diploma I (D1)"),
							6=>array('id'=>"D2",'name'=>"Diploma II (D2)"),
							7=>array('id'=>"D3",'name'=>"Diploma III (D3)"),
							8=>array('id'=>"S1",'name'=>"Sarjana (S1)"),
							9=>array('id'=>"S2",'name'=>"Master (S2)"),
							10=>array('id'=>"S3",'name'=>"Doctor (S3)"));
       }
       
        $listAgama = $Obj->GetComboAgama();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'agama_id', 
             array('agama_id',$listAgama,$result['agama_id'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
	  
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'educ', 
      array('educ',$educ,$result['educ'],'',' style="width:180px;"'), Messenger::CurrentRequest);
	   
      $hub = array(0=>array('id'=>'Ayah','name'=>$ll),1=>array('id'=>'Ibu','name'=>$pp));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'hub', 
      array('hub',$hub,$result['hub'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $mati = array(0=>array('id'=>1,'name'=>$nn),1=>array('id'=>0,'name'=>$yy));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'mati', 
      array('mati',$mati,$result['meninggal_status_ori'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $end=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_lahir', 
      array($result['tgl_lahir'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_meninggal', 
      array($result['tgl_meninggal'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($pegId), Messenger::CurrentRequest);
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataOrtu'] = $dataOrtu;
  		$return['dataOrtuDet'] = $dataOrtuDet;
  		$return['idPegawai'] = $pegId;
      $return['id'] = $rs['id'];
  		//$return['idIstri'] = $istId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {

    $this->mrTemplate->AddVar('content', 'URL_PSNG', Dispatcher::Instance()->GetUrl('data_istri_suami','dataIstriSuami', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_ANAK', Dispatcher::Instance()->GetUrl('data_anak','dataAnak', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_ORTU', Dispatcher::Instance()->GetUrl('data_orang_tua','dataOrangTua', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_MERTUA', Dispatcher::Instance()->GetUrl('data_mertua','dataMertua', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_SDR', Dispatcher::Instance()->GetUrl('data_saudara_kandung','dataSaudaraKandung', 'view', 'html').'&dataId='. $data['id']); 
    $this->mrTemplate->AddVar('content', 'URL_PGW', Dispatcher::Instance()->GetUrl('data_pegawai','detailDataPegawai', 'view', 'html').'&dataId='. $data['id']); 

      $dataPegawai = $data['dataPegawai'];
      $dataOrtu = $data['dataOrtu'];
      $dataOrtuDet = $data['dataOrtuDet'];
      $dat = $data['input'];

      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'PARENTS DATA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
           $label="Parents Data";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA ORANG TUA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
           $label="Data Orang Tua";  
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
      //$this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('data_pegawai', 'inputDataPegawai', 'view', 'html').'&op=add');
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_orang_tua', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_orang_tua', 'inputDataOrangTua', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
      
      /* $this->mrTemplate->AddVar('content', 'INPUT_ID', $dat['id']);
      $this->mrTemplate->AddVar('content', 'INPUT_NAMA', $dat['nama']);
      $this->mrTemplate->AddVar('content', 'INPUT_TMPT', $dat['tmpt']);
      $this->mrTemplate->AddVar('content', 'INPUT_KERJA', $dat['kerja']);
      $this->mrTemplate->AddVar('content', 'INPUT_KET', $dat['ket']);
      $this->mrTemplate->AddVar('content', 'INPUT_TELP', $dat['telp']); */
      $this->mrTemplate->AddVars('content', $dat, 'INPUT_');
      
      $this->mrTemplate->AddVar('content', 'PATH_AKTA', GTFWConfiguration::GetValue('application', 'akta_ortu_path'));
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if (empty($dataOrtu)) {
  			$this->mrTemplate->AddVar('data_ortu', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_ortu', 'PEGAWAI_EMPTY', 'NO');
  
  //mulai bikin tombol delete
  			$total=0;
        $start=1;
  			$idEnc2 = Dispatcher::Instance()->Encrypt($data['idPegawai']);
        for ($i=0; $i<count($dataOrtu); $i++) {
  				$no = $i+$start;
  				$dataOrtu[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataOrtu[$i]['class_name'] = 'table-common-even';
          }else{
            $dataOrtu[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataOrtu)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataOrtu[$i]['id']);
          
          $urlAccept = 'data_orang_tua|deleteDataOrangTua|do|html-dataId-'.$idEnc2;
          $urlKembali = 'data_orang_tua|dataOrangTua|view|html-dataId-'.$idEnc2;
          $dataName = $dataOrtu[$i]['nama'];
          $dataOrtu[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataOrtu[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_orang_tua','dataOrangTua', 'view', 'html').'&dataId2='. $idEnc.'&dataId='. $idEnc2;
            
          if($dataOrtu[$i]['tgl_lahir']=="0000-00-00"){
            $dataOrtu[$i]['tgl_lahir']="";
          }else{
            $dataOrtu[$i]['tgl_lahir']=$this->periode2string($dataOrtu[$i]['tgl_lahir']);
          }
		  
		  if($buttonlang=="eng"){
			if($dataOrtu[$i]['hub']=="Ayah"){
				$dataOrtu[$i]['hub']="Father";
			}else{
				$dataOrtu[$i]['hub']="Mother";
			}
		  }
  				$this->mrTemplate->AddVars('data_ortu_item', $dataOrtu[$i], 'ORTU_');
  				$this->mrTemplate->parseTemplate('data_ortu_item', 'a');	 
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
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}
   

?>