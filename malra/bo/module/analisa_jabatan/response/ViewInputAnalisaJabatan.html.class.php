<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/analisa_jabatan/business/analisa_jabatan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';

class ViewInputAnalisaJabatan extends HtmlResponse
  {
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/analisa_jabatan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_input_analisa_jabatan.html');
  }
      
  function ProcessRequest() 
  {
    // echo '<pre>';
    $js = new MutasiJabatanStruktural();
    $analjab = new AnalisaJabatan();  
    $msg = Messenger::Instance()->Receive(__FILE__);
  //   $this->Data = $msg[0][0];
		// $this->Pesan = $msg[0][1];
		// $this->css = $msg[0][2];
      
    $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
    $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId=$id;
      
    
      
    $tahun=array();
    if(isset($_GET['id'])){
      $dataPegawai = $js->GetDataDetail($id);
      // print_r($dataPegawai);
      #$arrpktgol = $js->GetComboPangkatGolonganAll();
      $arrpktgol = $js->GetComboPangkatGolongan($id);
      // print_r($arrpktgol);
      $dataJabs = $js->GetListMutasiJabatanStruktural($id);
      // print_r($dataJabs);

      if(isset($_GET['dataId'])){
        $dataMutasi = $js->GetDataMutasiById($id,$dataId);
        $result=$dataMutasi[0];
        // print_r($result);
        if(!empty($result)){
          $return['input']['id'] = $result['id'];
          $return['input']['nip'] = $result['nip'];
			    $return['input']['struktural'] = $result['struktural'];
			    $return['input']['eselon'] = $result['eselon'];
			    $return['input']['pktgolid'] = $result['pktgolid'];
			    $return['input']['mulai'] = $result['mulai'];
			    $return['input']['selesai'] = $result['selesai'];
			    $return['input']['pejabat'] = $result['pejabat'];
			    $return['input']['nosk'] = $result['nosk'];
			    $return['input']['tgl_sk'] = $result['tgl_sk'];
			    $return['input']['status'] = $result['status'];
			    $return['input']['upload'] = $result['upload'];
			  }    
      }else{
        $return['input']['id'] = '';
        $return['input']['nip'] = '';
        $return['input']['struktural'] = '';
        $return['input']['eselon'] = '';
        $return['input']['pktgolid'] = '';
        $return['input']['mulai'] = date("Y-m-d");
        $return['input']['selesai'] = date("Y-m-d");
        $return['input']['pejabat'] = '';
        $return['input']['nosk'] = '';
        $return['input']['tgl_sk'] = date("Y-m-d");
        $return['input']['status'] = '';
        $return['input']['upload'] = '';
        $arrpktgol = $js->GetComboPangkatGolongan($id);
      }
    }

    $arrjp = $analjab->GetComboJabatanStrukturalEmpty($return['input']['struktural']);


      foreach ($dataJabs as $key => $value) {
        if($value['struktural'] == $return['input']['struktural']){
          $return['jabstruk'] = $value['jabstruk'];
        }
      }

    if(empty($tahun['start'])){
      $tahun['start']=date("Y")-25;
	  }
    
    $tahun['end'] = date("Y")+5;
         
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
       
    //set the language
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
    	$active = "Active"; $inactive = "Inactive";
    }else{
    	$active = "Aktif"; $inactive = "Tidak Aktif";
    }
    $return['lang']=$lang;
 
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabs_ref', array('jabs_ref', $arrjp, $return['input']['struktural'], 'false', ' style="width:280px;" '), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['pktgolid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
    
    $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status " style="width:200px;" '), Messenger::CurrentRequest);
	      
    $list_eselon=array(array('id'=>'-','name'=>'-'),array('id'=>'IA','name'=>'IA'),array('id'=>'IB','name'=>'IB'),array('id'=>'IIA','name'=>'IIA'),array('id'=>'IIB','name'=>'IIB'),array('id'=>'IIIA','name'=>'IIIA'),array('id'=>'IIIB','name'=>'IIIB'),array('id'=>'IVA','name'=>'IVA'),array('id'=>'IVB','name'=>'IVB'));
	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'eselon', array('eselon', $list_eselon, $return['input']['eselon'], '', 'id="eselon " style="width:200px;"  '), Messenger::CurrentRequest);
	Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
        
    $return['dataPegawai'] = $dataPegawai;
  	$return['dataJabs'] = $dataJabs;
  	    // echo '</pre>';

  	return $return;  
  }
      
  function ParseTemplate($data = NULL)
  {
    if($this->Pesan)
    {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }
      
    $dataPegawai = $data['dataPegawai'];
    $dataJabs = $data['dataJabs'];
    // if($data['lang']=='eng') {
    // 	$this->mrTemplate->AddVar('content', 'TITLE', 'Structural Position Mutation');
    //  	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ganti' : 'Add');
    //  	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Cancel ' : 'Reset');
    //  	$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
    //   $label = "Management of Structural Position Mutation";
    // } else {
      $this->mrTemplate->AddVar('content', 'TITLE', 'Mutasi Jabatan Struktural');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', 'Ganti');
      $this->mrTemplate->AddVar('content', 'LABEL_INPUT', 'GANTI JABATAN');
      
      $this->mrTemplate->AddVar('content', 'BUTTON', 'Batal');
      $this->mrTemplate->AddVar('content', 'TYPE', 'submit');	
	    $label = "Manajemen Mutasi Jabatan Struktural";
    // }

    // if ( isset($_GET['dataId'])) {
    //   $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'updateMutasiJabatanStruktural', 'do', 'html'));
    // }else{
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'addMutasiJabatanStruktural', 'do', 'html'));
    // }
      
    // $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html').'&nip_nama='.$dataPegawai[0]['kode'] );
      
    $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
    $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
    $this->mrTemplate->AddVar('content', 'INPUT_STRUKTURAL', $dataJabs[0]['struktural']);
    $this->mrTemplate->AddVar('content', 'INPUT_ID', $_GET['dataId']);
    $this->mrTemplate->AddVar('content', 'NAMA_STRUKTURAL', $data['jabstruk']);
    $this->mrTemplate->AddVar('content', 'INPUT_NOSK', $data['input']['nosk']);
    $this->mrTemplate->AddVar('content', 'INPUT_PEJABAT', $data['input']['pejabat']);
     
    
     
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  }else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
    }
      
   //  if(!empty($data['input'])){
   //    $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		 //  $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  // }
      
   //  if (empty($dataJabs)) {
   //    $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  	// } else {
  	// 	$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
 
      // $urlDelete = Dispatcher::Instance()->GetUrl('analisa_jabatan', 'deleteMutasiJabatanStruktural', 'do', 'html');
      // $urlReturn = Dispatcher::Instance()->GetUrl('analisa_jabatan', 'MutasiJabatanStruktural', 'view', 'html');
      // Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      // $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      // $total=0;
      // $start=1;
      
    //   for ($i=0; $i<count($dataJabs); $i++) {
    //     $no = $i+$start;
    //     $dataJabs[$i]['number'] = $no;
    //     if ($no % 2 != 0) {
    //       $dataJabs[$i]['class_name'] = 'table-common-even';
    //     } else {
    //       $dataJabs[$i]['class_name'] = '';
    //     }

    //     if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
    //     if($i == sizeof($dataJabs)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
    //     $idEnc = Dispatcher::Instance()->Encrypt($dataJabs[$i]['id']);
    //     $urlAccept = 'analisa_jabatan|deleteMutasiJabatanStruktural|do|html-id-'.$dataPegawai[0]['id'];
    //     $urlKembali = 'analisa_jabatan|MutasiJabatanStruktural|view|html-id-'.$dataPegawai[0]['id'];
    //     $label = 'Data Mutasi Jabatan Struktural';
    //     $dataName = $dataJabs[$i]['jabstruk'];
        
    //     $dataJabs[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
    //     $dataJabs[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('analisa_jabatan','MutasiJabatanStruktural', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
    //     $dataJabs[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('analisa_jabatan','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
    //     $dataJabs[$i]['mulai'] = $this->date2string($dataJabs[$i]['mulai']);
    //     $dataJabs[$i]['selesai'] = $this->date2string($dataJabs[$i]['selesai']);
        
    //     if (!empty($dataJabs[$i]['upload'])){
    //       $dataJabs[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataJabs[$i]['upload'];
    //     } else {
    //       $dataJabs[$i]['LINK_DOWNLOAD_SK'] = '';
		  // $dataJabs[$i]['VIEW_DOWNLOAD'] = 'none';
    //     }

    //   	if(($dataJabs[$i]['status']=='Aktif')&&($data['lang']=='eng')) {
    //   		$dataJabs[$i]['status']="Active";
    //   	} elseif(($dataJabs[$i]['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
    //   		$dataJabs[$i]['status']="Inactive";
    //   	} else {
    //   		$dataJabs[$i]['status']=$dataJabs[$i]['status'];
    //   	}

    //     $this->mrTemplate->AddVars('data_item', $dataJabs[$i], 'JS_');
    //     $this->mrTemplate->parseTemplate('data_item', 'a');	 
    //   }
    // }
  }
      
  function date2string($date) {
	  $bln = array(
	            1  => '01',
					2  => '02',
					3  => '03',
					4  => '04',
					5  => '05',
					6  => '06',
					7  => '07',
					8  => '08',
					9  => '09',
					10 => '10',
					11 => '11',
					12 => '12'					
	               );
	  $arrtgl = explode('-',$date);
    return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0]; 
  }
}
?>