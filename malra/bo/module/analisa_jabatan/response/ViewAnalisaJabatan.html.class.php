<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/analisa_jabatan/business/analisa_jabatan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewAnalisaJabatan extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
        'module/'.Dispatcher::Instance()->mModule.'/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_analisa_jabatan.html');    
    } 
    
    function ProcessRequest() {
		
     $Obj = new AnalisaJabatan();

      $objSatker = new SatuanKerja();
      $arrsatker = $objSatker->GetComboSatuanKerja();
          if($_GET['nip_nama']){
          $nip_nama = isset($_GET['nip_nama'])?$_GET['nip_nama']:'';     
          }
          if($_POST['nip_nama']){
          $nip_nama = isset($_POST['nip_nama'])?$_POST['nip_nama']:''; 
                     
          }
          $satuan_kerja = isset($_POST['unit'])?$_POST['unit']:'all';
          $najab = isset($_POST['najab'])?$_POST['najab']:'';
          $jenjab = isset($_POST['jenjab'])?$_POST['jenjab']:'1';
      // }
      // print_r($_POST);
         
      $totalData = $Obj->GetCountJabatanPegawai($nip_nama, $satuan_kerja,$najab,$jenjab);
      $total = $totalData;
      $itemViewed = 50;
      $currPage = 1;
      $startRec = 0 ;
      if(isset($_GET['page'])) {
        $currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
        $startRec =($currPage-1) * $itemViewed;
      }
      
      $dataPegawai = $Obj->GetAllJabatanPegawai($startRec, $itemViewed, $nip_nama, $satuan_kerja,$najab,$jenjab);

      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nip_nama=' . Dispatcher::Instance()->Encrypt($nip_nama).
        '&unit=' . Dispatcher::Instance()->Encrypt($satuan_kerja).
        '&najab=' . Dispatcher::Instance()->Encrypt($najab).
        '&jenjab=' . Dispatcher::Instance()->Encrypt($jenjab).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));

      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit', array('unit', $arrsatker, $satuan_kerja, 'true', ' style="width:200px;"  onchange="setDS()" '), Messenger::CurrentRequest);
  
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$total, $url, $currPage), Messenger::CurrentRequest);

    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Data = $msg[0][0];
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
  
      $return['dataPegawai'] = $dataPegawai;
      $return['start'] = $startRec+1;
      $return['total'] = $total;
        
      $return['nip_nama'] = $nip_nama;
      $return['najab'] = $najab;
      $return['jenjab'] = $jenjab;
      return $return;
    }
      
    function ParseTemplate($data = NULL) {
      if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $data['lang'] = '';
      if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'Analisa Jabatan');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Analisa Jabatan');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
      }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'Analisa Jabatan');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Analisa Jabatan');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
      }

    $this->mrTemplate->AddVar('content', 'NIP_NAMA',  $data['nip_nama']);
    // $this->mrTemplate->AddVar('content', 'NIP_NAMA',  $data['satuan_kerja']);
    $this->mrTemplate->AddVar('content', 'NAJAB',  $data['najab']);
    $this->mrTemplate->AddVar('content', 'URL_STRUKTUR', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'StrukturJabatan', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('analisa_jabatan', 'AnalisaJabatan', 'view', 'html') );
      if (!empty($data['dataPegawai']) AND count($data['total'])>0) {
            $this->mrTemplate->addVar('data', 'DATA_EMPTY', 'NO');
            $no = $data['start'];
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
