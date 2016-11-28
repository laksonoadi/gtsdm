<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_duk_individu/business/laporan.class.php';

class ViewLaporanCv extends HtmlResponse {
   
   function TemplateModule() {
      	$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/laporan_duk_individu/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      	$this->SetTemplateFile('view_laporan_cv.html');    
   } 
   
  function ProcessRequest() {
      $this->Obj = new Laporan();  
	  
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
         $data['urut'] = $_GET['urut'];
      }else{
         $pNama = 0;
      }
       
      $data['dataPegawai']=$this->Obj->GetDataDukById($pegId);

      $data['dataJabs'] = $this->Obj->GetDataJabByDukById($pegId);     
		  
      //print_r($data);
	  
	    //set the language
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      if ($lang=='eng'){
      	$data['title']="DAFTAR URUT KEPEGAWAIAN";
      }else{
      	$data['title']="DAFTAR URUT KEPEGAWAIAN";
      }
      $data['lang']=$lang;
	  
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('laporan_duk_individu', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_duk_individu', 'rtfLaporanCv', 'view', 'html').'&id='.$_GET['id'].'&urut='.$_GET['urut']);
      $this->mrTemplate->AddVar('content', 'TITLE', $data['title']);

      $dataPegawai=$data['dataPegawai'];
      //print_r($dataPegawai);
        $dataPegawai['nama'] = $dataPegawai[0]['nama'];
        $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai['nama']);

        $dataPegawai['urut']=$data['urut'];
        $this->mrTemplate->AddVar('content', 'URUT', $dataPegawai['urut']);
        
        $dataPegawai['nip'] = $dataPegawai[0]['nip'];
        $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai['nip']);

        $dataPegawai['jns_peg'] = $dataPegawai[0]['jenis_pegawai'];
        $this->mrTemplate->AddVar('content', 'JENIS_PEG', $dataPegawai['jns_peg']);

        $dataPegawai['jenis_kelamin']=$dataPegawai[0]['jenis_kelamin']=='L'?'Laki-laki':'Perempuan';
        $this->mrTemplate->AddVar('content', 'JENIS_KELAMIN', $dataPegawai['jenis_kelamin']);

        $dataPegawai['ttl']=$dataPegawai[0]['tempat_lahir'].($dataPegawai[0]['tempat_lahir']==''?'':', ').$this->date2string($dataPegawai[0]['tanggal_lahir']);
        $this->mrTemplate->AddVar('content', 'TTL', $dataPegawai['ttl']);

        $dataPegawai['jabatan'] = $dataPegawai[0]['jabatan'];
        $this->mrTemplate->AddVar('content', 'JABATAN', $dataPegawai['jabatan']);
        
        $dataPegawai['unit'] = $dataPegawai[0]['unit_kerja'];
        $this->mrTemplate->AddVar('content', 'UNIT', $dataPegawai['unit']);
 
        $dataPegawai['univ'] = $dataPegawai[0]['pendidikan_nama'];
        $this->mrTemplate->AddVar('content', 'UNIV', $dataPegawai['univ']);

        $dataPegawai['tahun_lulus'] = $dataPegawai[0]['pendidikan_lulus'];
        $this->mrTemplate->AddVar('content', 'THN_LULUS', $dataPegawai['tahun_lulus']);

        $dataPegawai['jurusan'] = $dataPegawai[0]['pendidikan_jurusan'];
        $this->mrTemplate->AddVar('content', 'JURUSAN', $dataPegawai['jurusan']);

        $dataPegawai['pend_tingkat'] = $dataPegawai[0]['pendidikan_tingkat'];
        $this->mrTemplate->AddVar('content', 'pend_tingkat', $dataPegawai['pend_tingkat']);

        $mulai = explode('-', $dataPegawai[0]['jabatan_tmt_mulai']);
        $dataPegawai['mulai'] = 'TGL '.$mulai['2'].' BLN '.$mulai['1'].' THN '.$mulai['0'];
        $this->mrTemplate->AddVar('content', 'MULAI', $dataPegawai['mulai']);

        $selesai = explode('-', $dataPegawai[0]['jabatan_tmt_selesai']);
        $dataPegawai['selesai'] = 'TGL '.$selesai['2'].' BLN '.$selesai['1'].' THN '.$selesai['0'];
        $this->mrTemplate->AddVar('content', 'SELESAI', $dataPegawai['selesai']);

        $gol_tmt = explode('-', $dataPegawai[0]['golongan_tmt']);
        $dataPegawai['gol_tmt'] = 'TGL '.$gol_tmt['2'].' BLN '.$gol_tmt['1'].' THN '.$gol_tmt['0'];
        $this->mrTemplate->AddVar('content', 'GOL_TMT', $dataPegawai['gol_tmt']);
        
        $gol_id = $dataPegawai[0]['golongan'];
        $gol_nama = $dataPegawai[0]['gol_nama'];      
        $dataPegawai['pangkat_golongan'] = $gol_id.' '.$gol_nama;
        $this->mrTemplate->AddVar('content', 'PANGKAT_GOLONGAN', $dataPegawai['pangkat_golongan']);
        
        $capeg_tmt = explode('-', $dataPegawai[0]['capeg_tmt']);
        $dataPegawai['capeg_tmt'] = 'TGL '.$capeg_tmt['2'].' BLN '.$capeg_tmt['1'].' THN '.$capeg_tmt['0'];
        $this->mrTemplate->AddVar('content', 'CAPEG_TMT', $dataPegawai['capeg_tmt']);

        $masa_thn = $dataPegawai[0]['masa_kerja_tahun'];
        $masa_bln = $dataPegawai[0]['masa_kerja_bulan'];
        $dataPegawai['masa_kerja'] = $masa_thn.' THN '.$masa_bln.' BLN';

        $this->mrTemplate->AddVar('content', 'MASA_KERJA', $dataPegawai['masa_kerja']);

            if(empty($dataPegawai[0]['latihan_nama'])){
                $this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'YES');
              } else {
                  $this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'NO');
                  $thn = $dataPegawai[0]['latihan_tahun'];
                  $nama = $dataPegawai[0]['latihan_nama'];
                  $jam = $dataPegawai[0]['latihan_jam'];

                  // $thn = explode(',', $thn);  
                  // $nama = explode(',', $nama);
                  // $jam = explode(',', $jam);

                  $temp = array();
                  $thn = explode(',', $thn);  
                  $nama = explode(',', $nama);
                  $jam = explode(',', $jam);

                    foreach ($thn as $key => $val){
                        $temp[$key]['tahun'] = $val;
                    }

                    foreach ($nama as $key => $val){
                        $temp[$key]['nama'] = $val;
                    }

                    foreach ($jam as $key => $val){
                        $temp[$key]['jam'] = $val;
                    }
                    
                  //echo '<pre/>';
                  //print_r($temp);

                  $this->mrTemplate->clearTemplate('val');
                  if(!empty($temp)) {
                    $no = 1;
                    foreach ($temp as $key => $val) {
                      $this->mrTemplate->AddVar('val', 'NO', $no);
                      $this->mrTemplate->AddVar('val', 'TAHUN', $val['tahun']);
                      $this->mrTemplate->AddVar('val', 'NAMA', $val['nama']);
                      $this->mrTemplate->AddVar('val', 'JAM', $val['jam']);
                      $no++;
                      //$this->mrTemplate->AddVar('val', 'LATIHAN', $item);
                      $this->mrTemplate->parseTemplate('val', 'a');

                    }
                
                  }
                
              }
   

      $jabatanPegawai=$data['dataJabs'];
      //print_r($jabatanPegawai);
      if (empty($jabatanPegawai)) {
        $this->mrTemplate->AddVar('data_jabatan', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_jabatan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($jabatanPegawai); $i++) {
          $jabatanPegawai[$i]['class_name'] = 'table-common-even';
          $jabatanPegawai[$i]['number'] = $i+1;
          $jabatanPegawai[$i]['mulai'] = $this->date2string($jabatanPegawai[$i]['tmt_mulai']);
          // $dataPend[$i]['selesai'] = $this->date2string($jabatanPegawai[$i]['tmt_selesai']);
          $this->mrTemplate->AddVars('data_jabatan_item', $jabatanPegawai[$i], 'JAB_');
          $this->mrTemplate->parseTemplate('data_jabatan_item', 'a');   
        }
      }
   
   }
   
   function date2string($date) {
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
  	   return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
	}
}
?>