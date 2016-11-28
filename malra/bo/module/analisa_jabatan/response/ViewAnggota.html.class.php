<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/analisa_jabatan/business/analisa_jabatan.class.php';


class ViewAnggota extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
        'module/'.Dispatcher::Instance()->mModule.'/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_anggota_staf.html');    
    } 
    
    function ProcessRequest() {
		
     $Obj = new AnalisaJabatan();

      
      if(isset($_GET['id'])) {   

      $data_staf = $Obj->GetListAnggota($_GET['id']);
      $jumlah_staf = $Obj->GetCountAnggota($_GET['id']);
      $kepala_staf = $Obj->GetKepalaStafDetail($_GET['id']);
      $titleunit = $Obj->GetTitle($_GET['id']);

      $titleunit = $Obj->GetTitle($_GET['id']);
    }
     

    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Data = $msg[0][0];
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
  // print_r($kepala_staf);
      
      $return['dataPegawai'] = $data_staf;
      $return['jumlah'] = $jumlah_staf;
      $return['kepala'] = $kepala_staf;
      $return['titleunit'] = $titleunit;
      
      return $return;
    }
      
    function ParseTemplate($data = NULL) {
      
      
      
    $this->mrTemplate->AddVar('content', 'NIP_NAMA',  $data['nip_nama']);
    $this->mrTemplate->AddVar('content', 'TOTAL',  $data['jumlah']['0']['total']);

    
    $this->mrTemplate->AddVar('content', 'TITLEUNIT',  $data['titleunit']['0']['nama']);

    $this->mrTemplate->AddVar('content', 'KOMPETENSI',  $data['titleunit']['0']['nama']);

    $this->mrTemplate->AddVar('content', 'TITLE',  'Struktur Anggota');
    if(!empty($data['kepala'])){
    $this->mrTemplate->AddVar('content', 'NAMA',  $data['kepala']['nama']);
    $this->mrTemplate->AddVar('content', 'JABATAN',  $data['kepala']['jabatan']);
    $this->mrTemplate->AddVar('content', 'NIP',  $data['kepala']['nip']);
    $this->mrTemplate->AddVar('content', 'KOMPETENSI',  $data['kepala']['kompetensi']);
    }else{
    $tidak_ada = 'Tidak Ada Data';
    $this->mrTemplate->AddVar('content', 'NAMA', $tidak_ada );
    $this->mrTemplate->AddVar('content', 'JABATAN',  $tidak_ada);
    $this->mrTemplate->AddVar('content', 'NIP',  $tidak_ada);
    $this->mrTemplate->AddVar('content', 'KOMPETENSI',  $tidak_ada);
    }
    

    // $this->mrTemplate->AddVar('content', 'NIP_NAMA',  $data['satuan_kerja']);
    $this->mrTemplate->AddVar('content', 'NAJAB',  $data['najab']);
    $this->mrTemplate->AddVar('content', 'URL_STRUKTUR', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'StrukturJabatan', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html') );
    
      if (!empty($data['dataPegawai'])) {
            $this->mrTemplate->addVar('data', 'DATA_EMPTY', 'NO');
            $no = 1;
            foreach ($data['dataPegawai'] as $val) {
                $val['no'] = $no;
                if($val['id_pegawai'] != NULL && $val['status'] == "Aktif"){
                $this->mrTemplate->AddVar('button', 'DATA_EMPTY','NO');  
                $this->mrTemplate->AddVar('button', 'URL_CETAK', Dispatcher::Instance()->GetUrl('cetak_spt', 'InputSptKetua', 'view', 'html').'&id='.$val['id_pegawai'].'&id_jab='.$val['id'] );                  
                }else{
                $this->mrTemplate->AddVar('button', 'DATA_EMPTY','YES');  
                }

                if($val['status'] == "Tidak Aktif"){
                $val['nip'] = '';
                $val['nama_pegawai'] = '';
                $val['satuan_kerja'] = '';                  
                }

                if($val['id_pegawai'] != NULL && $val['status'] == "Aktif"){
                $this->mrTemplate->AddVar('data_item', 'BTN_EDIT', 'Ganti');
                $this->mrTemplate->AddVar('data_item', 'BTN_COL', 'success');
                $this->mrTemplate->AddVar('data_item', 'BG_ROW', '#fff');
                $this->mrTemplate->AddVar('data_item', 'URL_EDIT', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'InputAnalisaJabatan', 'view', 'html').'&id='.$val['id_pegawai'].'&dataId='.$val['dataId'] );
                } else {                               
                $this->mrTemplate->AddVar('data_item', 'BTN_EDIT', 'Tambah');
                $this->mrTemplate->AddVar('data_item', 'BTN_COL', 'warning');
                $this->mrTemplate->AddVar('data_item', 'BG_ROW', 'yellow');
                $this->mrTemplate->AddVar('data_item', 'URL_EDIT', Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'Pegawai', 'view', 'html'));
                }
                  
                $this->mrTemplate->addVars('data_item', $val);
                $this->mrTemplate->parseTemplate('data_item', 'a');
                $no++;
            }
        } else {
            $this->mrTemplate->addVar('data', 'DATA_EMPTY', 'YES');
        }
    }
}
?>
