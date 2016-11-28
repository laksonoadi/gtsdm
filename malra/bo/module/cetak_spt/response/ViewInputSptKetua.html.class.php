<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/cetak_spt/business/cetakspt.class.php';

class ViewInputSptKetua extends HtmlResponse
{
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/cetak_spt/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_input_spt_ketua.html');
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
    $arrjpdefault = $pg->GetJabatanStrukturalDefault();
    
    if(isset($_GET['id'])){
      $dataPegawai = $pg->GetDataDetail($id);
      $dataSatker = $pg->GetListMutasiSatuanKerja($id);
      $listjob = $pg->GetDataListJabatan($id);
      $unitspt = $pg->GetUnitSpt($id);
      $eselon = $pg->GetEselonById($id);
      $pegawaiDetail =$pg->GetDataDetailPegawai($id);
      $dataSpt = $pg->GetDataPegawaiDetailSPTKetua($id);    
      $result=$dataSpt;
      // print_r($result);exit();

       if(!empty($result)){
          // $return['input']['id'] = $result['pubspt_pegId'];
          $return['input']['pubpeg_sk_1'] = $result['pubpeg_sk_1'];
          $return['input']['pubpeg_jabat_nama'] = $result['pubpeg_jabat_nama'];
          $return['input']['pubpeg_jabat_nip'] = $result['pubpeg_jabat_nip'];
          $return['input']['pubpeg_jabat_panggol'] = $result['pubpeg_jabat_panggol'];
          $return['input']['pubpeg_jabat_jabatan'] = $result['pubpeg_jabat_jabatan'];
          $return['input']['pubpeg_nama'] = $result['pubpeg_nama'];
          $return['input']['pubpeg_nim'] = $result['pubpeg_nim'];
          $return['input']['pubpeg_panggol'] = $result['pubpeg_panggol'];
          $return['input']['pubpeg_sk_walkot'] = $result['pubpeg_sk_walkot'];
          $return['input']['pubpeg_sk_walkot_tgl'] = $result['pubpeg_sk_walkot_tgl'];
          $return['input']['pubpeg_jabatan'] = $result['pubpeg_jabatan'];
          $return['input']['pubpeg_unitkerja'] = $result['pubpeg_unitkerja'];
          $return['input']['pubpeg_tgl_lantik'] = $result['pubpeg_tgl_lantik'];
          $return['input']['pubpeg_tembusan4'] = $result['pubpeg_tembusan4'];
          $return['input']['pubpeg_tembusan5'] = $result['pubpeg_tembusan5'];
          $return['input']['pubpeg_tembusan6'] = $result['pubpeg_tembusan6'];
          $return['input']['pubpeg_tembusan7'] = $result['pubpeg_tembusan7'];

          $return['input']['pubpeg_sk_2'] = $result['pubpeg_sk_2'];
          $return['input']['pubpeg_sk_walkot_menduduki'] = $result['pubpeg_sk_walkot_menduduki'];
          $return['input']['pubpeg_sk_walkot_menduduki_tgl'] = $result['pubpeg_sk_walkot_menduduki_tgl'];
          $return['input']['pubpeg_eselon'] = $result['pubpeg_eselon'];
          $return['input']['pubpeg_tgl_menduduki'] = $result['pubpeg_tgl_menduduki'];
          $return['input']['pubpeg_gaji'] = $result['pubpeg_gaji'];
          $return['input']['pubpeg_sk3'] = $result['pubpeg_sk3'];
          $return['input']['pubpeg_tgl_tgs'] = $result['pubpeg_tgl_tgs'];
          $return['input']['pubpeg_tglsurat_1'] = $result['pubpeg_tglsurat_1'];

          $return['input']['pubpeg_tglsurat_2'] = $result['pubpeg_tglsurat_2'];
          $return['input']['pubpeg_tglsurat_3'] = $result['pubpeg_tglsurat_3'];
          $return['input']['pubpeg_idpeg'] = $result['pubpeg_idpeg'];
          

        }

    }

    if(!empty($eselon)){
    $return['input']['eselon'] = $eselon['0']['eselon'];
    }else{

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

    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_sk_wali', 
    array(date("Y-m-d"),'2010',$y1,$$return['input']['pubpeg_sk_walkot_tgl'],''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_pelantikan', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_menduduki', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_pelaksana_tugas', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);

    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_sk_waklot_duduk', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);

    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'pubpeg_tglsurat_1', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'pubpeg_tglsurat_2', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'pubpeg_tglsurat_3', 
    array(date("Y-m-d"),'2010',$y1,'',''), Messenger::CurrentRequest);  

    
    
    
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabs_ref', array('jabs_ref', $arrjp, $return['input']['struktural'], '', ' style="width:280px;" '), Messenger::CurrentRequest);

    $list_eselon=array(array('id'=>'-','name'=>'-'),array('id'=>'IA','name'=>'IA'),array('id'=>'IB','name'=>'IB'),array('id'=>'IIA','name'=>'IIA'),array('id'=>'IIB','name'=>'IIB'),array('id'=>'IIIA','name'=>'IIIA'),array('id'=>'IIIB','name'=>'IIIB'),array('id'=>'IVA','name'=>'IVA'),array('id'=>'IVB','name'=>'IVB'));
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'eselon', array('eselon', $list_eselon, $return['input']['eselon'], '', 'id="eselon " style="width:200px;"  '), Messenger::CurrentRequest);
    // print_r($return['input']);
  Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id), Messenger::CurrentRequest);
    $return['dataPegawai'] = $dataPegawai;
    $return['dataSatker'] = $dataSatker;
     $return['job'] = $listjob;
     // print_r($listjob);exit();
     $return['defaultpejabat']= $arrjpdefault;
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
    
    if(empty($data['input']['pubpeg_nim'])){

    if(!empty($data['job'])){
      $this->mrTemplate->AddVar('content', 'JABATANLAMA', $data['job']['1']['jabatan'].' '.$data['job']['1']['satuan_kerja']);
      $this->mrTemplate->AddVar('content', 'JABATANBARU', $data['job']['0']['jabatan'].' '.$data['job']['0']['satuan_kerja']);
      
    }
    if(!empty($data['unitspt'])){
      $this->mrTemplate->AddVar('content', 'LOKASI', $data['unitspt']['0']['gol'].' / '.$data['job']['0']['id']);
    }
    $this->mrTemplate->AddVar('content', 'ID', $data['pegawai']['id'] );
    $this->mrTemplate->AddVar('content', 'KOTA', 'Kota Gorontalo');
    $this->mrTemplate->AddVar('content', 'NAMATTD', 'H.ZAINUDIN RAHIM, S.Sos, M.Si');
    $this->mrTemplate->AddVar('content', 'NIPTTD', '19570721 198002 1 003');
    $this->mrTemplate->AddVar('content', 'JABSTRUKTURAL', 'Plh. SEKRETARIS DAERAH KOTA');
    $this->mrTemplate->AddVar('content', 'UNITKERJA', $dataSatker['0']['satkernama']);
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
      


    }

    $this->mrTemplate->AddVar('content', 'PANGOL_PEGAWAI', $dataPegawai['0']['pangkat_golongan']);
        $this->mrTemplate->AddVar('content', 'NIP_PEGAWAI', $dataPegawai['0']['nip']);
        $this->mrTemplate->AddVar('content', 'NAMA_PEGAWAI', $dataPegawai['0']['nama']);
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELANTIKAN', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELAKSANA', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_MENDUDUKI', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'SK_WALKOT', '821.2/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'SK_WALKOT_DUDUK', '821.2/BKD-DIKLAT/');
        
        $this->mrTemplate->AddVar('content', 'PEJABAT_NAMA', $data['defaultpejabat']['0']['nama']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_NIP', $data['defaultpejabat']['0']['nip']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_JABSTRUKTURAL', $data['defaultpejabat']['0']['jabstrukrNama']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_PANGOL', $data['defaultpejabat']['0']['pktgolrNama'].' / '.$data['defaultpejabat']['0']['id']);

  }else{

        
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELANTIKAN', $data['input']['pubpeg_sk_1'] );
        $this->mrTemplate->AddVar('content', 'PEJABAT_NAMA', $data['input']['pubpeg_jabat_nama'] );
        $this->mrTemplate->AddVar('content', 'PEJABAT_NIP', $data['input']['pubpeg_jabat_nip'] );
        $this->mrTemplate->AddVar('content', 'PEJABAT_JABSTRUKTURAL', $data['input']['pubpeg_jabat_jabatan'] );

        $this->mrTemplate->AddVar('content', 'PEJABAT_PANGOL', $data['input']['pubpeg_jabat_panggol'] );
        $this->mrTemplate->AddVar('content', 'SK_WALKOT', $data['input']['pubpeg_sk_walkot'] );
        $this->mrTemplate->AddVar('content', 'JABATANBARU', $data['input']['pubpeg_jabatan'] );
        $this->mrTemplate->AddVar('content', 'UNITKERJA', $data['input']['pubpeg_unitkerja'] );

        $this->mrTemplate->AddVar('content', 'NOMOR_SK_MENDUDUKI', $data['input']['pubpeg_sk_2'] );
        $this->mrTemplate->AddVar('content', 'SK_WALKOT_DUDUK', $data['input']['pubpeg_sk_walkot_menduduki'] );
        $this->mrTemplate->AddVar('content', 'TUNJANGAN', $data['input']['pubpeg_gaji'] );
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELAKSANA', $data['input']['pubpeg_sk3'] );


        $this->mrTemplate->AddVar('content', 'TEMBUSAN4', $data['input']['pubpeg_tembusan4'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN5', $data['input']['pubpeg_tembusan5'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN6', $data['input']['pubpeg_tembusan6'] );
        $this->mrTemplate->AddVar('content', 'TEMBUSAN7', $data['input']['pubpeg_tembusan7'] );
        
        // print_r($dataPegawai);
        
        $this->mrTemplate->AddVar('content', 'PANGOL_PEGAWAI', $dataPegawai['0']['pangkat_golongan']);
        $this->mrTemplate->AddVar('content', 'NIP_PEGAWAI', $dataPegawai['0']['nip']);
        $this->mrTemplate->AddVar('content', 'NAMA_PEGAWAI', $dataPegawai['0']['nama']);
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELANTIKAN', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_PELAKSANA', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'NOMOR_SK_MENDUDUKI', '800/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'SK_WALKOT', '821.2/BKD-DIKLAT/');
        $this->mrTemplate->AddVar('content', 'SK_WALKOT_DUDUK', '821.2/BKD-DIKLAT/');
        
        $this->mrTemplate->AddVar('content', 'PEJABAT_NAMA', $data['defaultpejabat']['0']['nama']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_NIP', $data['defaultpejabat']['0']['nip']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_JABSTRUKTURAL', $data['defaultpejabat']['0']['jabstrukrNama']);
        $this->mrTemplate->AddVar('content', 'PEJABAT_PANGOL', $data['defaultpejabat']['0']['pktgolrNama'].' / '.$data['defaultpejabat']['0']['id']);



  }
    $this->mrTemplate->AddVar('content', 'URL_CETAK', Dispatcher::Instance()->GetUrl('cetak_spt', 'CetakSPTKetua', 'view', 'html').'&id='.$data['pegawai']['id']);  
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html'));  

    if ($data['lang']=='eng'){
      $this->mrTemplate->AddVar('content', 'TITLE', 'WORKING UNIT MUTATION');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI UNIT KERJA');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($data['input']['pubpeg_nim']) ? 'Ubah' : 'Tambah');  
    } 

    $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
     
    if (!empty($data['input']['pubpeg_nim'])) {
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('cetak_spt', 'updateSptKetua', 'do', 'html'));
    }else{
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('cetak_spt', 'addSptKetua', 'do', 'html'));
    }
      
    $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      // print_r($dataPegawai);exit();
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
