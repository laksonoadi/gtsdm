<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/dashboard/business/dashboard.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'main/lib/Graph/Graph.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/informasi/business/popup_informasi.class.php';

class ViewDashboard extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/dashboard/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_dashboard.html');
   }
   
   function ProcessRequest() {
      // Filter here since the module access is All
      if(!Security::Instance()->IsLoggedIn()) {
         Security::Instance()->Logout();
         Security::Instance()->RequestDenied();
      }
      
      $pegId = $_GET['dataId']->Integer()->Raw();
      $ObjGraph           = new GT_Graph();
      $ObjDatPeg = new dashboard();
      $this->ObjDatPeg = new dashboard();
     
      $najab = isset($_POST['najab'])?$_POST['najab']:'';
      $jenjab = isset($_POST['jenjab'])?$_POST['jenjab']:'1';
      $nip_nama = '';
      $status_kerja = 'all';  
      $this->awal = date('Y-m').'-01';
      $this->akhir = date('Y-m').'-'.$this->ObjDatPeg->getLastDate(date('Y'),date('m'));
      $this->pangkat_golongan = 'all';
      $satuan_kerja = '';
      //total pegawai   
      $totalpegawai = $ObjDatPeg->GetCountPegawaiByUserIdVerified();
      $totalAllpegawai = $ObjDatPeg->GetCountAllPegawai();   
      //total pegawai fungsional
      $totalpegawaiFungsional = $ObjDatPeg->GetCountPejabatFungsionalTotal();   
      //jenis pegawai pie chart
      // $jenispegawai = $ObjDatPeg->GetListJenisPegawai();
      //pegawai pensiun
      $totalpegawaipensiun = $ObjDatPeg->GetCountDataPensiun($this->awal,$this->akhir,$status_kerja, $this->pangkat_golongan);
      //jabatan terisi
      $totalData = $ObjDatPeg->GetCountJabatanPegawai();
      //total pegawai pertumbuhan pegawai
      $totalDataUnJab = $ObjDatPeg->GetCountUnJabatanPegawai();

      // $datapertumbuhan = $ObjDatPeg->GetListPegawaiMasukPertahun();
      
      $return = $this->Data;

      $return['total_pegawai'] = $totalpegawai;
      $return['total_all_pegawai'] = $totalAllpegawai;
      $return['total_jabatan_isi'] = $totalData;
      $return['total_jabatan_kosong'] = $totalDataUnJab;
      // $return['jenispegawai'] = $jenispegawai;
      $return['total_pegawai_pensiun'] = $totalpegawaipensiun;
      // $return['datapertumbuhan'] = $datapertumbuhan;
      $return['totalpegawaiFungsional'] = $totalpegawaiFungsional;


      // $this->Obj= new PopupInformasi();
    
    // $return['user']=$this->Obj->GetUserLengkap();
    // $return['pensiun']=$this->Obj->GetListPegawaiPensiun();
    // $return['naik_pangkat']=$this->Obj->GetListPegawaiNaikPangkat();
    // $return['naik_gaji']=$this->Obj->GetListPegawaiNaikGaji();
    // $return['cuti']=$this->Obj->GetListPegawaiCuti();
    // $return['lembur']=$this->Obj->GetListPegawaiLembur();
    // $return['verifikasi']=$this->Obj->GetListPegawaiVerifikasi();
    // $return['pak']=$this->Obj->GetListPegawaiPAK();
    // $return['bkd']=$this->Obj->GetListPegawaiBKD();
    // $return['ultah']=$this->Obj->GetCountDaftarPegawai();
    // $return['satya'] = $this->Obj->GetCountDaftarPegawaiSatya();

      return $return;
   }

   function GetCountData($level){

      $return=$this->ObjDatPeg->GetCountJenisPegawaiTotal($level);
      if(!empty($return)){
      return $return['0'];
      }else{
        return $return;
      }
   }
   
   function ParseTemplate($data = NULL) {      
      $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html')); 
      
      
          $this->mrTemplate->SetAttribute('proses_penerbitan', 'visibility', 'visible');
          // $this->mrTemplate->addVar('proses_penerbitan', 'PIE_TERBIT',$data['pie_terbit']);
          $this->mrTemplate->addVar('content', 'TOTAL_ALL_PEGAWAI', $data['total_all_pegawai']);
          //jumlah pegawai
          $persen_peg_aktif = ( $data['total_pegawai'] / $data['total_all_pegawai']) * 100 ;
          $this->mrTemplate->addVar('content', 'PERSEN_JUMLAH_ORANG',number_format($persen_peg_aktif,2));
          $this->mrTemplate->addVar('content', 'JUMLAH_ORANG',$data['total_pegawai']);
          $this->mrTemplate->addVar('content', 'URL_PEGAWAI',Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html'));
		  
          //jumlah jabatan terisi
          $total_jab = $data['total_jabatan_isi'] + $data['total_jabatan_kosong'];
          $persen_jab_isi = ($data['total_jabatan_isi'] / $total_jab) * 100 ;
          $this->mrTemplate->addVar('content', 'PERSEN_JABATAN_ISI',number_format($persen_jab_isi,2));
          $this->mrTemplate->addVar('content', 'JABATAN_ISI',$data['total_jabatan_isi']);
          $this->mrTemplate->addVar('content', 'URL_JABATAN','');    
          $this->mrTemplate->addVar('content', 'JABATAN_TOTAL', $total_jab);
          
          if(!empty($data['totalpegawaiFungsional'])){
          $persen_fungsional = ( $data['totalpegawaiFungsional']['0']['total'] / $data['total_all_pegawai']) * 100 ;
          $this->mrTemplate->addVar('content', 'PERSEN_TOTAL_FUNGSIONAL',number_format($persen_fungsional,2));
          $this->mrTemplate->addVar('content', 'TOTAL_FUNGSIONAL',$data['totalpegawaiFungsional']['0']['total']);
          }else{
          $this->mrTemplate->addVar('content', 'PERSEN_TOTAL_FUNGSIONAL',number_format(0,2));
          $this->mrTemplate->addVar('content', 'TOTAL_FUNGSIONAL','0');
          }
          
          $persen_pensiun = ( $data['total_pegawai_pensiun'] / $data['total_all_pegawai']) * 100 ;
          $this->mrTemplate->addVar('content', 'PERSEN_JUMLAH_PENSIUN',number_format($persen_pensiun,2));
          $this->mrTemplate->addVar('content', 'JUMLAH_PENSIUN',$data['total_pegawai_pensiun']);

          //title
          $this->mrTemplate->addVar('content', 'TITLE','DASHBOARD');    

          // foreach ($data['jenispegawai'] as $val) {
          //   $indukId=$this->GetCountData($val['id']);
          //   $this->mrTemplate->addVar('jenis_pegawai_pie', 'TOTAL',$indukId['total']);    
          //   $this->mrTemplate->addVars('jenis_pegawai_pie', $val);
          //   $this->mrTemplate->parseTemplate('jenis_pegawai_pie', 'a');
          // }

          // foreach ($data['datapertumbuhan'] as $val) {
          //   $this->mrTemplate->addVar('tahun_pertumbuhan_pegawai', 'TAG_TAHUN',$val['tahun']);    
          //   $this->mrTemplate->addVars('tahun_pertumbuhan_pegawai', $val);
          //   $this->mrTemplate->parseTemplate('tahun_pertumbuhan_pegawai', 'a');
          // }

          // foreach ($data['datapertumbuhan'] as $val) {
          //   $this->mrTemplate->addVar('pertumbuhan_pegawai', 'TOTAL_DATA',$val['total']);    
          //   $this->mrTemplate->addVars('pertumbuhan_pegawai', $val);
          //   $this->mrTemplate->parseTemplate('pertumbuhan_pegawai', 'a');
          // }

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
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}

?>