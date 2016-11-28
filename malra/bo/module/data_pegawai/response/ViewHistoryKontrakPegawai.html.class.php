<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/data_pegawai/business/data_kontrak_pegawai.class.php';

class ViewHistoryKontrakPegawai extends HtmlResponse
  {
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_history_kontrak_pegawai.html');
  }
      
  function ProcessRequest() 
  {
    $js = new DataKontrakPegawai();
      
    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
    $id = Dispatcher::Instance()->Decrypt($_GET['pegId']->Raw());
    $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId=$id;
      
    $tahun=array();
    if(isset($_GET['pegId'])){
      $dataPegawai = $js->GetDataDetail($id);
      $dataKontrak = $js->GetListHistoryKontrakPegawai($id);
      if(isset($_GET['dataId'])){
        $dataKontrak = $js->GetDataKontrakPegawaiById($id,$dataId);
        $result=$dataKontrak[0];
        if(!empty($result)){
          $return['input']['id'] = $result['id'];
          $return['input']['nip'] = $result['ni'];
			    $return['input']['tgl_awal'] = $result['tgl_awal'];
			    $return['input']['tgl_akhir'] = $result['tgl_akhir'];
			    $return['input']['pejabat'] = $result['pejabat'];
			    $return['input']['nosk'] = $result['nosk'];
			    $return['input']['tgl_sk'] = $result['tgl_sk'];
			    $return['input']['status'] = $result['status'];
			    $return['input']['tgl_status'] = $result['tgl_status'];
			    $return['input']['upload'] = $result['upload'];
			  }    
      }else{
        $return['input']['id'] = '';
        $return['input']['nip'] = $dataPegawai[0]['nip'];
        $return['input']['tgl_awal'] = date("Y-m-d");
			  $return['input']['tgl_akhir'] = date("Y-m-d");
        $return['input']['pejabat'] = '';
        $return['input']['nosk'] = '';
        $return['input']['tgl_sk'] = date("Y-m-d");
        $return['input']['status'] = '';
        $return['input']['tgl_status'] = date("Y-m-d");
        $return['input']['upload'] = '';
      }
    }
         
    if(empty($tahun['start'])){
      $tahun['start']=date("Y")-25;
	  }
    
    $tahun['end'] = date("Y")+5;
         
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_awal', array($return['input']['tgl_awal'], $tahun['start'], $tahun['end'], '', '', 'tgl_awal'), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_akhir', array($return['input']['tgl_akhir'], $tahun['start'], $tahun['end'], '', '', 'tgl_akhir'), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_status',array($return['input']['tgl_status'], $tahun['start'], $tahun['end'], '', '', 'tgl_status'), Messenger::CurrentRequest);
         
    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
       
    //set the language
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
    	$active = "Active"; $inactive = "Inactive";
    }else{
    	$active = "Aktif"; $inactive = "Tidak Aktif";
    }
    $return['lang']=$lang;

    $list_status=array(array('id'=>'Aktif','name'=>$active),array('id'=>'Tidak Aktif','name'=>$inactive));
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status "  '), Messenger::CurrentRequest);
    
    $return['dataPegawai'] = $dataPegawai;
  	$return['dataKontrak'] = $dataKontrak;
  	$return['dataId'] = $dataId;
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
    $dataKontrak = $data['dataKontrak'];
    if($data['lang']=='eng') {
    	$this->mrTemplate->AddVar('content', 'TITLE', 'Contract Employee Data');
     	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
     	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Cancel ' : 'Reset');
     	$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      $label = "Management of Contract Employee Data";
    } else {
      $this->mrTemplate->AddVar('content', 'TITLE', 'Data Kontrak Pegawai');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Batal ' : 'Reset');
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');	
	    $label = "Manajemen Data Kontrak Pegawai";
    }

    if ( isset($_GET['dataId'])) {
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'updateKontrakPegawai', 'do', 'html'));
    }else{
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'addKontrakPegawai', 'do', 'html'));
    }
      
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_pegawai', 'kontrakPegawai', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('data_pegawai', 'historyKontrakPegawai', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
    $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
    $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
    $this->mrTemplate->AddVar('content', 'KAT', $dataPegawai[0]['kategori']);
     
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
		  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  }else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
    }
      
    if(!empty($data['input'])){
      $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		  $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
		  $this->mrTemplate->AddVar('content', 'INPUT_NIP', $dataPegawai[0]['id']);
		  $this->mrTemplate->AddVar('content', 'INPUT_DATAID', $data['dataId']);
	  }
      
    if (empty($dataKontrak)) {
      $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  	} else {
  		$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
 
      $urlDelete = Dispatcher::Instance()->GetUrl('data_pegawai', 'deleteKontrakPegawai', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('data_pegawai', 'historyKontrakPegawai', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      
      for ($i=0; $i<count($dataKontrak); $i++) {
        $no = $i+$start;
        $dataKontrak[$i]['number'] = $no;
        if ($no % 2 != 0) {
          $dataKontrak[$i]['class_name'] = 'table-common-even';
        } else {
          $dataKontrak[$i]['class_name'] = '';
        }

        if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
        if($i == sizeof($dataKontrak)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
        $idEnc = Dispatcher::Instance()->Encrypt($dataKontrak[$i]['id']);
        $urlAccept = 'data_pegawai|deleteKontrakPegawai|do|html-id-'.$dataPegawai[0]['id'];
        $urlKembali = 'data_pegawai|HistoryKontrakPegawai|view|html-id-'.$dataPegawai[0]['id'];
        $label = 'Data Kontrak Pegawai';
        $dataName = $dataKontrak[$i]['kontrakpegTglAwal'];
        
        $dataKontrak[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&pegId='.$idEnc.'&label='.$label.'&dataName='.$dataName;
        $dataKontrak[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai', 'view', 'html').'&pegId='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
        $dataKontrak[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_pegawai','detailKontrakPegawai', 'view', 'html').'&pegId='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
        $dataKontrak[$i]['tgl_awal'] = $this->date2string($dataKontrak[$i]['tgl_awal']);
        $dataKontrak[$i]['tgl_akhir'] = $this->date2string($dataKontrak[$i]['tgl_akhir']);
        $dataKontrak[$i]['tgl_sk'] = $this->date2string($dataKontrak[$i]['tgl_sk']);
        
        if (!empty($dataKontrak[$i]['upload'])){
          $dataKontrak[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataKontrak[$i]['upload'];
        } else {
          $dataKontrak[$i]['LINK_DOWNLOAD_SK'] = '';
        }

      	if(($dataKontrak[$i]['status']=='Aktif')&&($data['lang']=='eng')) {
      		$dataKontrak[$i]['status']="Active";
      	} elseif(($dataKontrak[$i]['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
      		$dataKontrak[$i]['status']="Inactive";
      	} else {
      		$dataKontrak[$i]['status']=$dataKontrak[$i]['status'];
      	}

        $this->mrTemplate->AddVars('data_item', $dataKontrak[$i], 'KON_'); 
        $this->mrTemplate->parseTemplate('data_item', 'a');	 
      }
    }
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
