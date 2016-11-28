<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_istri_suami/business/istri_suami.class.php';
   
class ViewDataIstriSuami extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_istri_suami/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_istri_suami.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new IstriSuami();
      $rs = $Obj->GetDataById($pegId);
      $dataIstri = $Obj->GetDataIstri($pegId);
        if ($_GET['dataId2'] != '') {
          $istId = $_GET['dataId2']->Integer()->Raw();
          $dataIstriDet = $Obj->GetDataIstriDet($istId);
          $result=$dataIstriDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no_kartu'];
            $return['input']['hub'] = $result['hub'];
            $return['input']['nama'] = $result['nama'];
            $return['input']['gelar_depan'] = $result['gelar_depan'];
            $return['input']['gelar_belakang'] = $result['gelar_belakang'];
            $return['input']['tmpt'] = $result['tmpt'];
            $return['input']['lahir'] = $result['tgl_lahir'];
            $return['input']['no_akta'] = $result['no_akta'];
            $return['input']['akta'] = $result['akta'];
            $return['input']['tgl_nikah'] = $result['tgl_nikah'];
            $return['input']['no_akta_nikah'] = $result['no_akta_nikah'];
            $return['input']['akta_nikah'] = $result['akta_nikah'];
            $return['input']['tgl_cerai'] = $result['tgl_cerai'];
            $return['input']['no_akta_cerai'] = $result['no_akta_cerai'];
            $return['input']['akta_cerai'] = $result['akta_cerai'];
            $return['input']['tgl_meninggal'] = $result['tgl_meninggal'];
            $return['input']['no_akta_meninggal'] = $result['no_akta_meninggal'];
            $return['input']['akta_meninggal'] = $result['akta_meninggal'];
            $return['input']['idlain'] = $result['id_lain'];
            $return['input']['agama_id'] = $result['agama_id'];
            $return['input']['kerja'] = $result['kerja'];
            $return['input']['ket'] = $result['ket'];
            $return['input']['no_askes'] = $result['no_askes'];
            $return['input']['tunjang'] = $result['tunjang_status'];
            $return['input']['tunjang_ori'] = $result['tunjang_status_ori'];
            $return['input']['mati'] = $result['meninggal_status'];
            $return['input']['mati_ori'] = $result['meninggal_status_ori'];
            $return['input']['cerai'] = $result['cerai_status'];
            $return['input']['cerai_ori'] = $result['cerai_status_ori'];
            $return['input']['npwp'] = $result['npwp'];
            $return['input']['tgl_npwp'] = $result['tgl_npwp'];
            $return['input']['telp'] = $result['telp'];
            $return['input']['educ'] = $result['educ'];
          }else{
            $return['input']['id'] = '';
            $return['input']['no'] = '';
            $return['input']['hub'] = '';
            $return['input']['nama'] = '';
            $return['input']['tmpt'] = '';
            $return['input']['lahir'] = '';
            $return['input']['no_akta'] = '';
            $return['input']['akta'] = '';
            $return['input']['tgl_nikah'] = '';
            $return['input']['no_akta_nikah'] = '';
            $return['input']['akta_nikah'] = '';
            $return['input']['tgl_cerai'] = '';
            $return['input']['no_akta_cerai'] = '';
            $return['input']['akta_cerai'] = '';
            $return['input']['tgl_meninggal'] = '';
            $return['input']['no_akta_meninggal'] = '';
            $return['input']['akta_meninggal'] = '';
            $return['input']['idlain'] = '';
            $return['input']['agama_id'] = '';
            $return['input']['kerja'] = '';
            $return['input']['ket'] = '';
            $return['input']['no_askes'] = '';
            $return['input']['tunjang'] = '';
            $return['input']['tunjang_ori'] = '';
            $return['input']['mati'] = '';
            $return['input']['mati_ori'] = '';
            $return['input']['cerai'] = '';
            $return['input']['cerai_ori'] = '';
            $return['input']['npwp'] = '';
            $return['input']['tgl_npwp'] = '';
            $return['input']['telp'] = '';
            $return['input']['educ'] = '';
          }
        }
      }
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      
        $listAgama = $Obj->GetComboAgama();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'agama_id', 
             array('agama_id',$listAgama,$result['agama_id'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
      
  	   if ($lang=='eng'){
          $ll="Wife";
          $pp="Husband";
          $yy="Ya";
          $nn="Tidak";
		  
		  $educ = array(	0=>array('id'=>"BLM",'name'=>"Not Yet School"),
							1=>array('id'=>"TK",'name'=>"Kindergarten"),
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
          $ll="Istri";  
          $pp="Suami";
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
	   $return['lang'] = $lang;
       $return['labelPasangan'] = (strtoupper($rs['gender']) == 'P' ? $pp : $ll);
  		
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'educ', 
      array('educ',$educ,$result['educ'],'',' style="width:180px;"'), Messenger::CurrentRequest);
	  
      /* $hub = array(0=>array('id'=>Istri,'name'=>$ll),1=>array('id'=>Suami,'name'=>$pp));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'hubungan', 
      array('hubungan',$hub,$result['hub'],'',' style="width:100px;"'), Messenger::CurrentRequest); */
      
      $tun = array(0=>array('id'=>0,'name'=>$yy),1=>array('id'=>1,'name'=>$nn));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tunjangan', 
      array('tunjangan',$tun,$result['tunjang_status_ori'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $mati = array(0=>array('id'=>1,'name'=>$nn),1=>array('id'=>0,'name'=>$yy));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'mati', 
      array('mati',$mati,$result['meninggal_status_ori'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $cerai = array(0=>array('id'=>0,'name'=>$nn),1=>array('id'=>1,'name'=>$yy));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'cerai', 
      array('cerai',$cerai,$result['cerai_status_ori'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $end=date('Y')+4;
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_lahir', 
      array($result['tgl_lahir'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_npwp', 
      array($result['tgl_npwp'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_nikah', 
      array($result['tgl_nikah'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_meninggal', 
      array($result['tgl_meninggal'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_cerai', 
      array($result['tgl_cerai'],$end-100,$end,'',''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($pegId), Messenger::CurrentRequest);

      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataIstri'] = $dataIstri;
  		$return['dataIstriDet'] = $dataIstriDet;
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
      $dataIstri = $data['dataIstri'];
      $dataIstriDet = $data['dataIstriDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'SPOUSE DATA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
           $label="Wife/Husband Data";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PASANGAN');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
           $label="Data Istri/Suami";  
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_istri_suami', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_istri_suami', 'inputDataIstriSuami', 'do', 'html')); 
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai['alamat']);
      
      /* $this->mrTemplate->AddVar('content', 'INPUT_ID', $dat['id']);
      $this->mrTemplate->AddVar('content', 'INPUT_NO', $dat['no']);
      $this->mrTemplate->AddVar('content', 'INPUT_NAMA', $dat['nama']);
      $this->mrTemplate->AddVar('content', 'INPUT_TMPT', $dat['tmpt']);
      $this->mrTemplate->AddVar('content', 'INPUT_IDLAIN', $dat['idlain']);
      $this->mrTemplate->AddVar('content', 'INPUT_KERJA', $dat['kerja']);
      $this->mrTemplate->AddVar('content', 'INPUT_KET', $dat['ket']);
      $this->mrTemplate->AddVar('content', 'INPUT_TELP', $dat['telp']);
      $this->mrTemplate->AddVar('content', 'INPUT_NPWP', $dat['npwp']); */
      $this->mrTemplate->AddVars('content', $dat, 'INPUT_');
      
      $this->mrTemplate->AddVar('content', 'LABEL_PASANGAN', $data['labelPasangan']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if (empty($dataIstri)) {
  			$this->mrTemplate->AddVar('data_istri', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_istri', 'PEGAWAI_EMPTY', 'NO');
  
  //mulai bikin tombol delete
  			$total=0;
        $start=1;
  			$idEnc2 = Dispatcher::Instance()->Encrypt($data['idPegawai']);
        for ($i=0; $i<count($dataIstri); $i++) {
  				$no = $i+$start;
  				$dataIstri[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataIstri[$i]['class_name'] = 'table-common-even';
          }else{
            $dataIstri[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataIstri[$i]['id']);
          
          $urlAccept = 'data_istri_suami|deleteDataIstriSuami|do|html-dataId-'.$idEnc2;
          $urlKembali = 'data_istri_suami|dataIstriSuami|view|html-dataId-'.$idEnc2;
          $dataName = $dataIstri[$i]['nama'];
          $dataIstri[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataIstri[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_istri_suami','dataIstriSuami', 'view', 'html').'&dataId2='. $idEnc.'&dataId='. $idEnc2;
            
          if($dataIstri[$i]['tgl_lahir']=="0000-00-00"){
            $dataIstri[$i]['tgl_lahir']="";
          }else{
            $dataIstri[$i]['tgl_lahir']=$this->periode2string($dataIstri[$i]['tgl_lahir']);
          }
          if($dataIstri[$i]['tgl_nikah']=="0000-00-00"){
            $dataIstri[$i]['tgl_nikah']="";
          }else{
            $dataIstri[$i]['tgl_nikah']=$this->periode2string($dataIstri[$i]['tgl_nikah']);
          }
		  if($data['lang']=="eng"){
			if($dataIstri[$i]['hub']=="Suami"){
				$dataIstri[$i]['hub']="Husband";
			}else{
				$dataIstri[$i]['hub']="Wife";
			}
		  }
  				$this->mrTemplate->AddVars('data_istri_item', $dataIstri[$i], 'ISTRI_');
  				$this->mrTemplate->parseTemplate('data_istri_item', 'a');	 
  			}
  		}
		
		
		if ($_GET['dataId2'] != '') {
			$this->mrTemplate->AddVar('content', 'LISTDATA', 'none');
		}else{
			$this->mrTemplate->AddVar('content', 'FORMDATA', 'none');
		}
        
        $this->mrTemplate->AddVar('content', 'PATH_AKTA', GTFWConfiguration::GetValue('application', 'akta_pasangan_path'));
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