<?php
require_once Configuration::Instance()->GetValue('application', 'gtfw_base') . 'main/lib/pat_template/pat_template.php';

require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_cv/business/laporan.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pelatihan/business/mutasi_pelatihan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_seminar/business/mutasi_seminar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penghargaan/business/mutasi_penghargaan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penelitian/business/mutasi_penelitian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_kunjungan/business/mutasi_kunjungan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_bintang_tanda_jasa/business/MutasiBintangTandaJasa.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_istri_suami/business/istri_suami.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_anak/business/data_anak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_orang_tua/business/data_orang_tua.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewPdfLaporanCv extends PdfxResponse {
    var $mrTemplate;
    var $pdf;
    var $UNIT;
    
    function GetFileName() {
        // name it whatever you want
        return 'Daftar_Riwayat_'.date('Ymd').'.pdf';
    }
    
    function ProcessRequest() {
        $this->Obj = new Laporan();  
        $this->ObjPegawai = new DataPegawai();
        $this->pend = new MutasiPendidikan();
        $this->pel = new MutasiPelatihan();
        $this->sem = new MutasiSeminar();
        $this->peng = new MutasiPenghargaan();
        $this->penel = new MutasiPenelitian();
        $this->kunj = new MutasiKunjungan();
        $this->js = new MutasiJabatanStruktural();
        $this->pg = new MutasiPangkatGolongan();
        $this->btj = new MutasiBintangTandaJasa();
        $this->sutri = new IstriSuami();
        $this->anak = new Anak();
        $this->satker = new SatuanKerja();

        if(isset($_POST['id'])) {
            $pegId = $_POST['id'];
        } elseif(isset($_GET['id'])) {
            $pegId = $_GET['id'];
        } else {
            echo "Error: invalid/missing ID.";
            exit;
        }
        
        $data['dataPegawai'] = $this->Obj->GetDataDetail($pegId);
        $data['dataPend'] = $this->pend->GetListMutasiPendidikanVerifikasi($pegId);
        $data['dataPel'] = $this->pel->GetListMutasiPelatihanVerifikasi($pegId);
        $data['dataPeng'] = $this->peng->GetListMutasiPenghargaanVerifikasi($pegId);
        $data['dataKunj'] = $this->kunj->GetListMutasiKunjunganVerifikasi($pegId);
        $data['dataJabs'] = $this->js->GetListMutasiJabatanStruktural($pegId);
        $data['dataPangg'] = $this->pg->GetListMutasiPangkatGolongan($pegId);
        $data['dataBint'] = $this->btj->GetListMutasiVerifikasi($pegId);
        $data['dataSutri'] = $this->sutri->GetDataIstriVerifikasi($pegId);
        $data['dataAnak'] = $this->anak->GetDataAnakVerifikasi($pegId);
        $data['satkerInduk'] = $this->satker->GetSatkerDetail('1');
        
        $data['dataPegawai']=$data['dataPegawai'];
        $data['dataPegawai']['jenis_kelamin']=$data['dataPegawai']['jenkel']=='L'?'Laki-laki':'Perempuan';
        $data['dataPegawai']['ttl']=$data['dataPegawai']['tmplahir'].($data['dataPegawai']['tmplahir']==''?'':', ').date2string($data['dataPegawai']['tgllahir']);
        
        $this->ParseTemplate($data);
    }
    
    function ParseTemplate($data = NULL) {
        if(empty($data))
            return;
        
        $this->mrTemplate = new patTemplate();
        
        $template_dir = GTFWConfiguration::GetValue('application','docroot').'module/laporan_cv/template/';
        $template_file = 'view_pdf_laporan_cv.html';
        
        $this->mrTemplate->setBaseDir($template_dir);
        $this->mrTemplate->readTemplatesFromFile($template_file);
        
        $this->UNIT = 'pt';
        $this->pdf = new TCPDF('P', $this->UNIT, 'A4', TRUE, 'UTF-8', FALSE);
        $this->pdf->SetPrintHeader(FALSE);
        $this->pdf->SetPrintFooter(FALSE);
        $this->pdf->SetHeaderMargin(0);
        $this->pdf->SetFooterMargin(0);
        $this->pdf->SetMargins(36, 36, 36); // left, top, right 1/2 inch
        
        $margins = $this->pdf->getMargins();
        $content_width = $this->getPageWidth() - ($margins['left'] + $margins['right']);
        $this->mrTemplate->addGlobalVar('100', $content_width . $this->UNIT);
        
        $this->pdf->SetAutoPageBreak(TRUE, $margins['top']);
        $this->pdf->SetHtmlVSpace(array(
            'div' => array(
                0 => array('h' => 0.1, 'n' => 0.1),
                1 => array('h' => 0.1, 'n' => 0.1)
            )
        ));
        
        
        // ---- Templates variables adding begins here ---- //
        
        $this->mrTemplate->addGlobalVar('SATUAN_KERJA_INDUK', $data['satkerInduk']['satkerNama']);
        
        $this->mrTemplate->addVar('photo', 'IS_EMPTY', 'YES');
        // $this->mrTemplate->addVar('photo', 'URL_PHOTO', 'HUEHUEHUE');
        
        $this->mrTemplate->addVars('document', $data['dataPegawai']);
        $this->mrTemplate->addVar('document', 'DATE_TODAY', date2string(date('Y-m-d')));
        
        $this->addRowsOrEmpty('pendidikan', $data['dataPend']);
        $this->addRowsOrEmpty('pelatihan', $data['dataPel']);
        $this->addRowsOrEmpty('jabatan', $data['dataJabs']);
        $this->addRowsOrEmpty('penghargaan', $data['dataBint']);
        
        $this->addRowsOrEmpty('kunjungan', $data['dataKunj']);
        $this->addRowsOrEmpty('pasangan', $data['dataSutri']);
        $this->addRowsOrEmpty('anak', $data['dataAnak']);
        
        // ---- Templates variables adding ends here ---- //
        
        $html = $this->mrTemplate->getParsedTemplate();
        $this->pdf->AddPage();
        $this->pdf->writeHTML($html);
        $this->pdf->lastPage();
        $this->pdf->Output('Daftar_Riwayat-'.date('Ymd').'-'.$data['dataPegawai']['nama'].'.pdf', 'D');
    }
    
    function addRowsOrEmpty($template, $rows) {
        if(count($rows) > 0) {
            $this->mrTemplate->addVar($template, 'IS_EMPTY', 'NO');
            $this->mrTemplate->addRows($template, $rows);
        } else {
            $this->mrTemplate->addVar($template, 'IS_EMPTY', 'YES');
        }
    }
}

if(!function_exists('date2string')) {
    function date2string($date) {
        if($date == '0000-00-00')
            return $date;
        
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
        $arrtgl = explode('-',$date);
        if (sizeof($arrtgl)>2)
            return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
        else
            return $arrtgl[0];
    }
}
?>