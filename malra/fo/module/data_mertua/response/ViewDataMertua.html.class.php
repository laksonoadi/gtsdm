<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_mertua/business/data_mertua.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewDataMertua extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_mertua/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_data_mertua.html');
   }
   
   function ProcessRequest()
   {
      $ObjDatPeg = new DataPegawai();
      $_GET['dataId'] = $ObjDatPeg->GetPegIdByUserName();
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Mertua();
      $rs = $Obj->GetDataById($pegId);
      $dataMertua = $Obj->GetDataMertua($pegId);
        if ($_GET['dataId2'] != '') {
          $mertuaId = $_GET['dataId2']->Integer()->Raw();
          $dataMertuaDet = $Obj->GetDataMertuaDet($mertuaId);
          $result=$dataMertuaDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['hub'] = $result['hub'];
            $return['input']['nama'] = $result['nama'];
            $return['input']['tmpt'] = $result['tmpt'];
            $return['input']['lahir'] = $result['tgl_lahir'];
            $return['input']['kerja'] = $result['kerja'];
            $return['input']['ket'] = $result['ket'];
            $return['input']['educ'] = $result['educ'];
			$return['input']['mati'] = $result['meninggal_status'];
          }else{
            $return['input']['id'] = '';
            $return['input']['hub'] = '';
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
          $ll="Father";
          $pp="Mother";
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
          $ll="Ayah";  
          $pp="Ibu";
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
	   
      $hub = array(0=>array('id'=>Ayah,'name'=>$ll),1=>array('id'=>Ibu,'name'=>$pp));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'hub', 
      array('hub',$hub,$result['hub'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $end=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_lahir', 
      array($result['tgl_lahir'],$end-100,$end,'',''), Messenger::CurrentRequest);
	  
      $mati = array(0=>array('id'=>1,'name'=>$nn),1=>array('id'=>0,'name'=>$yy));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'mati', 
      array('mati',$mati,$result['meninggal_status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
	  
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataMertua'] = $dataMertua;
  		$return['dataMertuaDet'] = $dataMertuaDet;
  		$return['idPegawai'] = $pegId;
  		//$return['idIstri'] = $istId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataMertua = $data['dataMertua'];
      $dataMertuaDet = $data['dataMertuaDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'PARENTS IN LAW DATA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
           $label="Parents in Law Data";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA MERTUA');
           $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
           $label="Data Mertua";  
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
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_mertua', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_mertua', 'inputDataMertua', 'do', 'html'));
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('data_mertua', 'dataMertua', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_mertua', 'dataMertua', 'view', 'html'));
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
      
      if (empty($dataMertua)) {
  			$this->mrTemplate->AddVar('data_mertua', 'PEGAWAI_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_mertua', 'PEGAWAI_EMPTY', 'NO');
  
  //mulai bikin tombol delete
  			$total=0;
        $start=1;
  			$idEnc2 = Dispatcher::Instance()->Encrypt($data['idPegawai']);
        for ($i=0; $i<count($dataMertua); $i++) {
  				$no = $i+$start;
  				$dataMertua[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataMertua[$i]['class_name'] = 'table-common-even';
          }else{
            $dataMertua[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataMertua)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataMertua[$i]['id']);
          
          $urlAccept = 'data_mertua|deleteDataMertua|do|html-dataId-'.$idEnc2;
          $urlKembali = 'data_mertua|dataMertua|view|html-dataId-'.$idEnc2;
          $dataName = $dataMertua[$i]['nama'];
          $dataMertua[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataMertua[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_mertua','dataMertua', 'view', 'html').'&dataId2='. $idEnc.'&dataId='. $idEnc2.'&aksi=ya';
            
          if($dataMertua[$i]['tgl_lahir']=="0000-00-00"){
            $dataMertua[$i]['tgl_lahir']="";
          }else{
            $dataMertua[$i]['tgl_lahir']=$this->periode2string($dataMertua[$i]['tgl_lahir']);
          }
		  
		  if($buttonlang=="eng"){
			if($dataMertua[$i]['hub']=="Ayah"){
				$dataMertua[$i]['hub']="Father";
			}else{
				$dataMertua[$i]['hub']="Mother";
			}
		  }
  				$this->mrTemplate->AddVars('data_mertua_item', $dataMertua[$i], 'MERTU_');
  				$this->mrTemplate->parseTemplate('data_mertua_item', 'a');	 
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