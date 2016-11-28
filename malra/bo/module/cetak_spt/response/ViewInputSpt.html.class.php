<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/cetak_spt/business/cetakspt.class.php';

class ViewInputSpt extends HtmlResponse
{
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/cetak_spt/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_input_spt.html');
  }
      
  function ProcessRequest() 
  {

    $pg = new cetakspt();
    
    // $pg = new MutasiSatuanKerja();
    // $js = new MutasiJabatanStruktural();
    
  
      
    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Data = isset($msg[0][0]) ? $msg[0][0] : NULL;
    $this->Pesan = isset($msg[0][1]) ? $msg[0][1] : NULL;
    $this->css = isset($msg[0][2]) ? $msg[0][2] : NULL;
      
    $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
    $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
    $this->profilId=$id;
    
    
    $arrjenpeg =  $pg->GetListJenisKepegawaian();
    
    $tahun=array();
    
    // $listjob = $pg->GetListMutasiSatuanKerja();
    

    $arrjp = $pg->GetComboJabatanStruktural();
    if(isset($_GET['id'])){
      $dataPegawai = $pg->GetDataDetail($id);
      $dataSatker = $pg->GetListMutasiSatuanKerja($id);
      $listjob = $pg->GetDataListJabatan($id);
      $unitspt = $pg->GetUnitSpt($id);
      $pegawaiDetail =$pg->GetDataDetailPegawai($id);
      $dataSpt = $pg->GetDataSptById($id);    
      $result=$dataSpt['0'];

       if(!empty($result)){
          $return['input']['id'] = $result['pubspt_pegId'];
          $return['input']['dataid'] = $result['dataid'];
          $return['input']['pubspt_nomor_spt'] = $result['pubspt_nomor_spt'];
          $return['input']['pubspt_sambutan'] = $result['pubspt_sambutan'];
          $return['input']['pubspt_tanggal'] = $result['pubspt_tanggal'];
          $return['input']['pubspt_panggoltlama'] = $result['pubspt_panggoltlama'];
          $return['input']['pubspt_jabatanlama'] = $result['pubspt_jabatanlama'];
          $return['input']['pubspt_jabatanbaru'] = $result['pubspt_jabatanbaru'];
          $return['input']['pubspt_kotattd'] = $result['pubspt_kotattd'];
          $return['input']['pubspt_tanggalttd'] = $result['pubspt_tanggalttd'];
          $return['input']['pubspt_satuanttd_id'] = $result['pubspt_satuanttd_id'];
          $return['input']['pubspt_panggolttd_id'] = $result['pubspt_panggolttd_id'];
          $return['input']['pubspt_nipttd'] = $result['pubspt_nipttd'];
          $return['input']['pubspt_namattd'] = $result['pubspt_namattd'];
          $return['input']['pubspt_jabatanttd_id'] = $result['pubspt_jabatanttd_id'];
          $return['input']['pubspt_tembusan4'] = $result['pubspt_tembusan4'];
          $return['input']['pubspt_tembusan5'] = $result['pubspt_tembusan5'];
          $return['input']['pubspt_tembusan6'] = $result['pubspt_tembusan6'];
          $return['input']['pubspt_tembusan7'] = $result['pubspt_tembusan7'];
          $return['input']['pubspt_tembusan8'] = $result['pubspt_tembusan8'];
        }

    }

    if(empty($tahun['start'])){
      $tahun['start']=date("Y")-25;
    }         

    $tahun['end'] = date("Y")+5;

    //set the language
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Working Unit Mutation');
      $active = "Active"; $inactive = "Inactive";
    }else{
      $labeldel=Dispatcher::Instance()->Encrypt('Mutasi Satuan Kerja');
      $active = "Aktif"; $inactive = "Tidak Aktif";
    }
    $return['lang']=$lang;
    
   
     $y1 = '2020';  
    
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tanggal', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tanggalttd', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabs_ref', array('jabs_ref', $arrjp, $return['input']['struktural'], '', ' style="width:280px;" '), Messenger::CurrentRequest);
    // print_r($return['input']);
  Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;
    $return['dataSatker'] = $dataSatker;
     $return['job'] = $listjob;
     // print_r($listjob);exit();
     $return['pegawai'] = $pegawaiDetail;
     $return['unitspt'] = $unitspt;
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
    $dataSatker = $data['dataSatker'];
    
    if(empty($data['input']['id'])){

      if(!empty($dataSatker)){
        $this->mrTemplate->AddVar('content', 'JABATANBARU', 'staf '.$dataSatker['0']['satkernama']);  
      }

    if(!empty($data['job'])){
      $this->mrTemplate->AddVar('content', 'JABATANLAMA', $data['job']['1']['jabatan'].' '.$data['job']['1']['satuan_kerja']);
      $this->mrTemplate->AddVar('content', 'JABATANBARU', $data['job']['0']['jabatan'].' '.$data['job']['0']['satuan_kerja']);
      
    }

    if(!empty($data['unitspt'])){
      $this->mrTemplate->AddVar('content', 'LOKASI', $data['unitspt']['0']['gol'].' / '.$data['unitspt']['0']['id']);
    }
    $this->mrTemplate->AddVar('content', 'ID', $data['pegawai']['id'] );
    $this->mrTemplate->AddVar('content', 'KOTA', 'Kota Gorontalo');
    $this->mrTemplate->AddVar('content', 'NAMATTD', 'H.ZAINUDIN RAHIM, S.Sos, M.Si');
    $this->mrTemplate->AddVar('content', 'NIPTTD', '19570721 198002 1 003');
    $this->mrTemplate->AddVar('content', 'JABSTRUKTURAL', 'Plh. SEKRETARIS DAERAH KOTA');
    $this->mrTemplate->AddVar('content', 'UNITKERJA', 'WALIKOTA GORONTALO');
    $this->mrTemplate->AddVar('content', 'JABFUNGSIONAL', 'PEMBINA UTAMA MUDA');
    if(!empty($data['job']['1']['satkernama'])){
      $this->mrTemplate->AddVar('content', 'HID', 'visible');
    }else{
      $this->mrTemplate->AddVar('content', 'HID', 'hidden');
    }
    if(!empty($data['pegawai'])){
      
      $gol_number = explode('/', $data['pegawai']['pangkat_golongan']);
      if($gol_number['0']=='IV'){
        $gol_number= '4';
        }
        if($gol_number['0']=='III'){
        $gol_number= '3';
        }
        if($gol_number['0']=='II'){
        $gol_number= '2';
        }
        if($gol_number['0']=='I'){
        $gol_number= '1';
        }
      $this->mrTemplate->AddVar('content', 'NOMOR_SK', '824.'.$gol_number.'/BKD-DIKLAT/III/');
    }
  }else{

         $this->mrTemplate->AddVar('content', 'ID', $data['input']['id'] );
         $this->mrTemplate->AddVar('content', 'INPUT_ID', $data['input']['dataid'] );
        $this->mrTemplate->AddVar('content', 'NOMOR_SK', $data['input']['pubspt_nomor_spt'] );
        $this->mrTemplate->AddVar('content', 'PENUGAS', $data['input']['pubspt_sambutan'] );
        // $this->mrTemplate->AddVar('content', 'NIPTTD', $data['input']['pubspt_tanggal'] );
        $this->mrTemplate->AddVar('content', 'LOKASI', $data['input']['pubspt_panggoltlama'] );
        $this->mrTemplate->AddVar('content', 'JABATANLAMA', $data['input']['pubspt_jabatanlama'] );
        $this->mrTemplate->AddVar('content', 'JABATANBARU', $data['input']['pubspt_jabatanbaru'] );
        $this->mrTemplate->AddVar('content', 'KOTA', $data['input']['pubspt_kotattd'] );
        // $this->mrTemplate->AddVar('content', 'UNITKERJA', $data['input']['pubspt_tanggalttd'] );
        $this->mrTemplate->AddVar('content', 'UNITKERJA', $data['input']['pubspt_satuanttd_id'] );
        $this->mrTemplate->AddVar('content', 'JABSTRUKTURAL', $data['input']['pubspt_panggolttd_id'] );
        $this->mrTemplate->AddVar('content', 'NIPTTD', $data['input']['pubspt_nipttd'] );
        $this->mrTemplate->AddVar('content', 'NAMATTD', $data['input']['pubspt_namattd'] );
        $this->mrTemplate->AddVar('content', 'JABFUNGSIONAL', $data['input']['pubspt_jabatanttd_id'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN4', $data['input']['pubspt_tembusan4'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN5', $data['input']['pubspt_tembusan5'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN6', $data['input']['pubspt_tembusan6'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN7', $data['input']['pubspt_tembusan7'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN8', $data['input']['pubspt_tembusan8'] );
        


  }
    $this->mrTemplate->AddVar('content', 'URL_CETAK', Dispatcher::Instance()->GetUrl('cetak_spt', 'CetakSPT', 'view', 'html').'&id='.$data['pegawai']['id']);  
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id']);  

    if ($data['lang']=='eng'){
      $this->mrTemplate->AddVar('content', 'TITLE', 'WORKING UNIT MUTATION');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI UNIT KERJA');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');  
    } 

    $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
     
    if ( isset($_GET['dataId'])) {
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('cetak_spt', 'updateSpt', 'do', 'html'));
    }else{
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('cetak_spt', 'addSpt', 'do', 'html'));
    }
      
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
    $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
    $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
    $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
    $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
      $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
    }else{
      $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
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
    return '   '.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];  
  }
}
?>
