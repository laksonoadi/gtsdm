<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_cv/business/laporan.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.class.php';
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
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_saudara_kandung/business/data_saudara_kandung.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_mertua/business/data_mertua.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewRtfLaporanCv extends HtmlResponse
{
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      $this->Obj = new Laporan();  
      $this->ObjPegawai = new DataPegawai();
      $this->pend = new MutasiPendidikan();
      $this->org = new MutasiOrganisasiPegawai();
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
      $this->ortu = new OrangTua();
      $this->sdr = new SaudaraKandung();
      $this->mtua = new Mertua();
      $this->satker = new SatuanKerja();
      
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
      }
      
      $data['dataPegawai']=$this->Obj->GetDataDetail($pegId);
      $data['dataPend'] = $this->pend->GetListMutasiPendidikanVerifikasi($pegId);
      $data['dataOrg'] = $this->org->GetListMutasiOrganisasiPegawai($pegId);
      $data['dataPel'] = $this->pel->GetListMutasiPelatihanVerifikasi($pegId);
      // $data['dataSem'] = $this->sem->GetListMutasiSeminar($pegId);
      $data['dataPeng'] = $this->peng->GetListMutasiPenghargaanVerifikasi($pegId);
      // $data['dataPenel'] = $this->penel->GetListMutasiPenelitian($pegId);
      $data['dataKunj'] = $this->kunj->GetListMutasiKunjunganVerifikasi($pegId);
      $data['dataJabs'] = $this->js->GetListMutasiJabatanStruktural($pegId);
      $data['dataPangg'] = $this->pg->GetListMutasiPangkatGolongan($pegId);
      $data['dataBint'] = $this->btj->GetListMutasiVerifikasi($pegId);
      $data['dataSutri'] = $this->sutri->GetDataIstriVerifikasi($pegId);
      $data['dataAnak'] = $this->anak->GetDataAnakVerifikasi($pegId);
      $data['dataOrtu'] = $this->ortu->GetDataOrtu($pegId);
      $data['dataSdr'] = $this->sdr->GetDataSdr($pegId);
      $data['dataMertua'] = $this->mtua->GetDataMertua($pegId);
      $data['satkerInduk'] = $this->satker->GetSatkerDetail('1');
      $dataPegawai=$data['dataPegawai'];
      $dataPegawai['jenis_kelamin']=$dataPegawai['jenkel']=='L'?'Laki-laki':'Perempuan';
      $dataPegawai['ttl']=$dataPegawai['tmplahir'].($dataPegawai['tmplahir']==''?'':', ').$this->date2string($dataPegawai['tgllahir']);
  		
  		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_daftar_riwayat_hidup.rtf");
  		$contents = str_replace("}{\lang1035\langfe255\langnp1035\insrsid13501539\charrsid7032298 ]}","]", $contents);
  		$contents = str_replace(array("\r\n", "\n", "\r"), "", $contents);
  		
  		$keys=array_keys($dataPegawai);
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[".strtoupper($keys[$i])."]",$dataPegawai[$keys[$i]], $contents);
      }
      $contents = str_replace("[DATE_TODAY]", $this->date2string(date('Y-m-d')), $contents);
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jpendlabel','institusi','jurusan','lulus','neglabel','kepsek');
      $dataNilai=$data['dataPend'];
      for($j = 0; $j < 10; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2string($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[P".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??P'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','mulai','selesai','durasi','tahun','tempat','ket');
      $dataNilai=$data['dataPel'];
      for($j = 0; $j < 10; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2indoformat($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
            $isi[$j]['durasi'] = $this->date2indoformat($dataNilai[$j]['mulai']) .' s/d '. $this->date2indoformat($dataNilai[$j]['selesai']);
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[PL".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??PL'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
      
      /* unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','tingkatlabel','mulai','penyelenggara','tempat');
      $dataNilai=$data['dataSem'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[S".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);
      } */
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','pktgol','golongan','tmt','pejabat','nosk','tgl_sk','dasar');
      $dataNilai=$data['dataPangg'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PT".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);
		  // var_dump("[PT".strtoupper($keys[$i])."]",$isi[$keys[$i]]);
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jabstruk','mulai','selesai','durasi','satuan_kerja_induk');
      $dataNilai=$data['dataJabs'];
      for($j = 0; $j < 10; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2indoformat($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
            $isi[$j]['durasi'] = $this->date2indoformat($dataNilai[$j]['mulai']) .' s/d '. $this->date2indoformat($dataNilai[$j]['selesai']);
            $isi[$j]['satuan_kerja_induk'] = $data['satkerInduk']['satkerNama'];
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[J".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??J'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
      
      /* unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jplabel','nama','tahun','pemberi');
      $dataNilai=$data['dataPeng'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[H".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      } */
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','tanda_jasa','sk_nomor','nama','sk_tahun','keterangan');
      $dataNilai=$data['dataBint'];
      for($j = 0; $j < 10; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2string($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
            $isi[$j]['nama'] = $dataNilai[$j]['tanda_jasa'] .' \par '. $dataNilai[$j]['sk_nomor'];
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[TJ".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??TJ'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','tmpt','tgl_lahir','tgl_nikah','kerja','ket');
      $dataNilai=$data['dataSutri'];
      for($j = 0; $j < 2; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2string($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[PS".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??PS'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
		
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','jenkel','tmpt','tgl_lahir','kerja','ket');
      $dataNilai=$data['dataAnak'];
      for($j = 0; $j < 5; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2string($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[PA".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??PA'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
		
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','hub','tmpt','tgl_lahir','kerja','ket');
      $dataNilai=$data['dataOrtu'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PO".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
		
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','jenkel','tmpt','tgl_lahir','kerja','ket');
      $dataNilai=$data['dataSdr'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PK".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
		
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','hub','tmpt','tgl_lahir','kerja','ket');
      $dataNilai=$data['dataMertua'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PM".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jkaryalabel','judul','peranan','tahun');
      $dataNilai=$data['dataPenel'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[I".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','neglabel','tujuan','mulai','selesai','durasi','ket');
      $dataNilai=$data['dataKunj'];
      for($j = 0; $j < 10; $j++) {
        $no = $j + 1;
        for($i = 0; $i < sizeof($keys); $i++) {
            if(isset($dataNilai[$j][$keys[$i]]))
                $isi[$j][$keys[$i]] = $this->date2indoformat($dataNilai[$j][$keys[$i]]);
            else
                $isi[$j][$keys[$i]] = '';
        }
        if(isset($dataNilai[$j])) {
            $isi[$j]['no'] = $no;
            $isi[$j]['durasi'] = $this->date2indoformat($dataNilai[$j]['mulai']) .' s/d '. $this->date2indoformat($dataNilai[$j]['selesai']);
        }
        for($i = 0; $i < sizeof($keys); $i++){
            $contents = str_replace("[KLN".strtoupper($keys[$i]).$no."]", $isi[$j][$keys[$i]], $contents);
            // $reg_pattern = '/\[(\}\{.*? )??KLN'.strtoupper($keys[$i]).'(\}\{.*? )??'.$no.'(\}\{.*? )??\]/';
            // $contents = preg_replace($reg_pattern, $isi[$j][$keys[$i]], $contents);
        }
      }
      
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','jabatan','mulai','selesai');
      $dataNilai=$data['dataOrg'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[O".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
		// exit;
  		
  		//echo "<pre>"; print_r($isi); echo "</pre>";
  		//exit();
  		$nama=str_replace(" ","_",$dataPegawai['nama']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=daftar_riwayat_hidup_".$nama.".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
   }
   
   function date2string($date) {
       if($date == '0000-00-00')
           return '00-00-0000';
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
    
   function date2indoformat($date) {
       if($date == '0000-00-00')
           return '00-00-0000';
  	   $arrtgl = explode('-',$date);
  	   if (sizeof($arrtgl)>2)
  	     return $arrtgl[2].'-'.$arrtgl[1].'-'.$arrtgl[0];
  	   else
  	     return $arrtgl[0];
	}  
}
   

?>