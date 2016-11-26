<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_cv/business/laporan.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_istri_suami/business/istri_suami.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_anak/business/data_anak.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_orang_tua/business/data_orang_tua.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_mertua/business/data_mertua.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_saudara_kandung/business/data_saudara_kandung.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pelatihan/business/mutasi_pelatihan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_seminar/business/mutasi_seminar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penghargaan/business/mutasi_penghargaan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penelitian/business/mutasi_penelitian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_kunjungan/business/mutasi_kunjungan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pekerjaan_pegawai/business/mutasi_pekerjaan_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_beasiswa/business/mutasi_beasiswa.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_mengajar_diluar/business/mutasi_mengajar_diluar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_kepakaran_dosen/business/mutasi_kepakaran_dosen.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_membimbing/business/mutasi_membimbing.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_mengajar/business/mutasi_mengajar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak/business/mutasi_pak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_hukuman/business/mutasi_hukuman.class.php';   

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
	  $this->peg = new MutasiPekerjaanPegawai();
      $this->pas = new IstriSuami();
	  $this->anak = new Anak();
	  $this->ortu = new OrangTua();
	  $this->mertua = new Mertua();
	  $this->sdr = new SaudaraKandung();
	  $this->bea = new MutasiBeasiswa();
	  $this->mengluar = new MutasiMengajarDiluar();
	  $this->kep = new MutasiKepakaranDosen();
	  $this->memb = new MutasiMembimbing();
	  $this->meng = new MutasiMengajar();
	  $this->pagol = new MutasiPangkatGolongan();
	  $this->pak = new MutasiPak();
	  $this->huk = new MutasiHukuman();
      
      /*if(isset($_GET['id'])){
         $pegId = $_GET['id'];
      }*/
	   $pegId = $this->ObjPegawai->GetPegIdByUserName();
      
      $data['dataPegawai']=$this->Obj->GetDataDetail($pegId);
      $data['dataPend'] = $this->pend->GetListMutasiPendidikan($pegId);
      $data['dataOrg'] = $this->org->GetListMutasiOrganisasiPegawai($pegId);
      $data['dataPel'] = $this->pel->GetListMutasiPelatihan($pegId);
      $data['dataSem'] = $this->sem->GetListMutasiSeminar($pegId);
      $data['dataPeng'] = $this->peng->GetListMutasiPenghargaan($pegId);
      $data['dataPenel'] = $this->penel->GetListMutasiPenelitian($pegId);
      $data['dataKunj'] = $this->kunj->GetListMutasiKunjungan($pegId);
      $data['dataJabs'] = $this->js->GetListMutasiJabatanStruktural($pegId);
	  $data['dataPekerjaan'] = $this->peg->GetListMutasiPekerjaanPegawai($pegId);
	  $data['dataPasangan'] = $this->pas->GetDataIstri($pegId);   
	  $data['anak'] = $this->anak->GetDataAnak($pegId);   	 
	  $data['ortu'] = $this->ortu->GetDataOrtu($pegId); 
	  $data['mertua'] = $this->mertua->GetDataMertua($pegId); 	
	  $data['saudara'] = $this->sdr->GetDataSdr($pegId); 
	  $data['beasiswa'] = $this->bea->GetListMutasiBeasiswa($pegId);
	  $data['mengluar'] = $this->mengluar->GetListMutasiMengajarDiluar($pegId);  	  
	  $data['kepakaran'] = $this->kep->GetListMutasiKepakaranDosen($pegId);  	  
	  $data['membimbing'] = $this->memb->GetListMutasiMembimbing($pegId);  	  
	  $data['mengajar'] = $this->meng->GetListMutasiMengajar($pegId);  	  
	  $data['pangkat'] = $this->pagol->GetListMutasiPangkatGolongan($pegId);  	  
	  $data['pak'] = $this->pak->GetListMutasiPak($pegId);  	  
	  $data['huk'] = $this->huk->GetListMutasiHukuman($pegId);
      $dataPegawai=$data['dataPegawai'];
      $dataPegawai['jenis_kelamin']=$dataPegawai['jenkel']=='L'?'Laki-laki':'Perempuan';
      $dataPegawai['ttl']=$dataPegawai['tmplahir'].($dataPegawai['tmplahir']==''?'':', ').$this->date2string($dataPegawai['tgllahir']);
  		
  		$contents = file_get_contents(str_replace("upload_file/file/","",GTFWConfiguration::GetValue( 'application', 'file_save_path'))."doc/template_daftar_riwayat_hidup.rtf");
  		$contents = str_replace("}{\lang1035\langfe255\langnp1035\insrsid13501539\charrsid7032298 ]}","]", $contents);
  		
  		//print_r($contents);  
  		$keys=array_keys($dataPegawai);
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[".strtoupper($keys[$i])."]",$dataPegawai[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jpendlabel','institusi','jurusan','lulus','neglabel','kepsek');
      $dataNilai=$data['dataPend'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[P".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','tipelabel','jenislabel','nama','tahun','jmljam','tempat');
      $dataNilai=$data['dataPel'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[K".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
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
      }
      
      unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','jabstruk','mulai','selesai');
      $dataNilai=$data['dataJabs'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[J".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      unset($keys); unset($dataNilai); unset($isi);
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
      $keys=array('no','jklabel','tujuan','neglabel','mulai','selesai');
      $dataNilai=$data['dataKunj'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[L".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
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
      
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','hub','nama','tgl_lahir','tgl_nikah','tunjang_status','meninggal_status');
      $dataNilai=$data['dataPasangan'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PS".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','nmr','jenkel','tgl_lahir','tunjang_status','meninggal_status');
      $dataNilai=$data['anak'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PA".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','hub','tgl_lahir','kerja','educ','meninggal_status');
      $dataNilai=$data['ortu'];
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
      $keys=array('no','nama','hub','tgl_lahir','kerja','educ','meninggal_status');
      $dataNilai=$data['mertua'];
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
      $keys=array('no','nama','jenkel','tgl_lahir','kerja','educ','meninggal_status');
      $dataNilai=$data['saudara'];
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
      $keys=array('no','pktgol','tmt','pejabat','nosk','tgl_sk','dasar','status');
      $dataNilai=$data['pangkat'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PT".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','tahun_terima','jenjang','nama','dana','jangka','keterangan');
      $dataNilai=$data['beasiswa'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
			$dataNilai[$i]['jangka'] =  $dataNilai[$i]['tahun'].' Tahun '.$dataNilai[$i]['bulan'].' bulan';
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[PB".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','semester','nama_mk','sks','kelas','status');
      $dataNilai=$data['mengajar'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[XM".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','univ','mk','status');
      $dataNilai=$data['mengluar'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[XL".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','semester','nim_mhs','nama_mhs','jenis','judul_ta','status');
      $dataNilai=$data['membimbing'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[XB".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','bidanglabel');
      $dataNilai=$data['kepakaran'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[XK".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','namahkm','jenis','kat','mulai','selesai');
      $dataNilai=$data['huk'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[HK".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
	  
	  unset($keys); unset($dataNilai); unset($isi);
      $keys=array('no','nama','jabatan','tanggungjawab','mulai','selesai','status');
      $dataNilai=$data['dataPekerjaan'];
    	for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
    	for ($i=0; $i<count($dataNilai); $i++){
    	    $dataNilai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
            $isi[$keys[$ii]] .=  $this->date2string($dataNilai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[X".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
  		
  		//echo "<pre>"; print_r($isi); echo "</pre>";
  		//exit();
  		$nama=str_replace(" ","_",$dataPegawai['nama']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=daftar_riwayat_hidup_".$nama.".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
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
  	   if (sizeof($arrtgl)>2)
  	     return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
  	   else
  	     return $arrtgl[0];
	}  
}
   

?>