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


class ViewLaporanCv extends HtmlResponse {
   
   function TemplateModule() {
      	$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/laporan_cv/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      	$this->SetTemplateFile('view_laporan_cv.html');    
   } 
   
  function ProcessRequest() {
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
      }else{
         $pNama = 0;
      }*/
	  $_GET['id'] = $this->ObjPegawai->GetPegIdByUserName();
	  $pegId = $this->ObjPegawai->GetPegIdByUserName();
	  //print_r($pegId);exit;
       
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
		
	  
	    //set the language
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      if ($lang=='eng'){
      	$data['title']="CURRICULUM VITAE";
      }else{
      	$data['title']="DAFTAR RIWAYAT HIDUP";
      }
      $data['lang']=$lang;
	  
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('laporan_cv', 'pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_cv', 'rtfLaporanCv', 'view', 'html').'&id='.$_GET['id'] );
      $this->mrTemplate->AddVar('content', 'TITLE', $data['title']);
      $dataPegawai=$data['dataPegawai'];
      $dataPegawai['jenis_kelamin']=$dataPegawai['jenkel']=='L'?'Laki-laki':'Perempuan';
      $dataPegawai['ttl']=$dataPegawai['tmplahir'].($dataPegawai['tmplahir']==''?'':', ').$this->date2string($dataPegawai['tgllahir']);
      $this->mrTemplate->AddVars('content', $dataPegawai,'');
      
      $dataPend=$data['dataPend'];
      if (empty($dataPend)) {
  			$this->mrTemplate->AddVar('data_pendidikan', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_pendidikan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPend); $i++) {
          $dataPend[$i]['class_name'] = 'table-common-even';
          $dataPend[$i]['number'] = $i+1;
          $dataPend[$i]['mulai'] = $this->date2string($dataPend[$i]['mulai']);
          $dataPend[$i]['selesai'] = $this->date2string($dataPend[$i]['selesai']);
          $this->mrTemplate->AddVars('data_pendidikan_item', $dataPend[$i], 'PEND_');
          $this->mrTemplate->parseTemplate('data_pendidikan_item', 'a');	 
    		}
  		}
  		
  		$dataPel=$data['dataPel'];
  		if (empty($dataPel)) {
  			$this->mrTemplate->AddVar('data_pelatihan', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_pelatihan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPel); $i++) {
           $dataPel[$i]['class_name'] = 'table-common-even';
           $dataPel[$i]['number'] = $i+1;
           $dataPel[$i]['mulai'] = $this->date2string($dataPel[$i]['mulai']);
           $dataPel[$i]['selesai'] = $this->date2string($dataPel[$i]['selesai']);
           $this->mrTemplate->AddVars('data_pelatihan_item', $dataPel[$i], 'PEL_');
           $this->mrTemplate->parseTemplate('data_pelatihan_item', 'a');	 
  			}
  		}
  		
  		$dataSem=$data['dataSem'];
  		if (empty($dataSem)) {
  			$this->mrTemplate->AddVar('data_seminar', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_seminar', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataSem); $i++) {
           $dataSem[$i]['class_name'] = 'table-common-even';
           $dataSem[$i]['number'] = $i+1;
           $dataSem[$i]['mulai'] = $this->date2string($dataSem[$i]['mulai']);
           $dataSem[$i]['selesai'] = $this->date2string($dataSem[$i]['selesai']);
           $this->mrTemplate->AddVars('data_seminar_item', $dataSem[$i], 'PEL_');
           $this->mrTemplate->parseTemplate('data_seminar_item', 'a');	 
  			}
  		}
  		
  		$dataJabs=$data['dataJabs'];
  		if (empty($dataJabs)) {
        $this->mrTemplate->AddVar('data_jabatan', 'DATA_EMPTY', 'YES');
    	} else {
    		$this->mrTemplate->AddVar('data_jabatan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataJabs); $i++) {
          $dataJabs[$i]['number'] = $i+1;
          $dataJabs[$i]['class_name'] = 'table-common-even';
          $dataJabs[$i]['mulai'] = $this->date2string($dataJabs[$i]['mulai']);
          $dataJabs[$i]['selesai'] = $this->date2string($dataJabs[$i]['selesai']);
          $this->mrTemplate->AddVars('data_jabatan_item', $dataJabs[$i], 'JS_');
          $this->mrTemplate->parseTemplate('data_jabatan_item', 'a');	 
        }
      }
  		
  		$dataPeng=$data['dataPeng'];
  		if (empty($dataPeng)) {
  			$this->mrTemplate->AddVar('data_penghargaan', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_penghargaan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPeng); $i++) {
          $dataPeng[$i]['number'] = $i+1; 
          $dataPeng[$i]['class_name'] = 'table-common-even';
          $dataPeng[$i]['mulai'] = $this->date2string($dataPeng[$i]['mulai']);
          $dataPeng[$i]['selesai'] = $this->date2string($dataPeng[$i]['selesai']);
          $this->mrTemplate->AddVars('data_penghargaan_item', $dataPeng[$i], 'PENG_');
          $this->mrTemplate->parseTemplate('data_penghargaan_item', 'a');	 
  			}
  		}
  		
  		$dataPend=$data['dataPenel'];
  		if (empty($dataPend)) {
  			$this->mrTemplate->AddVar('data_penelitian', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_penelitian', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPend); $i++) {
            $dataPend[$i]['number'] = $i+1;
            $dataPend[$i]['class_name'] = 'table-common-even';
            $tipeId = $dataPend[$i]['tipeId'];
            if ($tipeId==1){
                $dataPend[$i]['jkaryalabel']='Buku';
                $dataPend[$i]['judul']=$dataPend[$i]['judulBuku'];
            } else if ($tipeId==2){
                $dataPend[$i]['jkaryalabel']='Artikel';
                $dataPend[$i]['judul']=$dataPend[$i]['judulArtikel'];
            } else if ($tipeId==3){
                $dataPend[$i]['jkaryalabel']='Penelitian';
                $dataPend[$i]['judul']=$dataPend[$i]['judulPenelitian'];
            } else if ($tipeId==4){
                $dataPend[$i]['jkaryalabel']='Publikasi';
                $dataPend[$i]['judul']=$dataPend[$i]['judulPublikasi'];
            }
            $this->mrTemplate->AddVars('data_penelitian_item', $dataPend[$i], 'PEN_');
            $this->mrTemplate->parseTemplate('data_penelitian_item', 'a');	 
    		 }
  		}
  		
  		$dataKunj=$data['dataKunj'];
  		if (empty($dataKunj)) {
  			$this->mrTemplate->AddVar('data_kunjungan', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_kunjungan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataKunj); $i++) {
            $dataKunj[$i]['number'] = $i+1;
            $dataKunj[$i]['class_name'] = 'table-common-even';
            $dataKunj[$i]['mulai'] = $this->date2string($dataKunj[$i]['mulai']);
            $dataKunj[$i]['selesai'] = $this->date2string($dataKunj[$i]['selesai']);
            $this->mrTemplate->AddVars('data_kunjungan_item', $dataKunj[$i], 'KUNJ_');
            $this->mrTemplate->parseTemplate('data_kunjungan_item', 'a');	 
  			}
  		}
  		
  		$dataOrg=$data['dataOrg'];
  		if (empty($dataOrg)) {
  			$this->mrTemplate->AddVar('data_organisasi', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_organisasi', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataOrg); $i++) {
           $dataOrg[$i]['class_name'] = 'table-common-even';
           $dataOrg[$i]['number'] = $i+1;         
           $dataOrg[$i]['selesai'] = $dataOrg[$i]['selesai']!= '0000'?$dataOrg[$i]['selesai']:'Sekarang';
           $this->mrTemplate->AddVars('data_organisasi_item', $dataOrg[$i], 'ORG_');
           $this->mrTemplate->parseTemplate('data_organisasi_item', 'a');	 
  			}
  		}
		
		$dataPas=$data['dataPasangan'];
  		if (empty($dataPas)) {
  			$this->mrTemplate->AddVar('data_pasangan', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_pasangan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPas); $i++) {
           $dataPas[$i]['class_name'] = 'table-common-even';
           $dataPas[$i]['number'] = $i+1;         
           $dataPas[$i]['tgl_lahir'] = $this->date2string($dataPas[$i]['tgl_lahir']);
		   $dataPas[$i]['tgl_nikah'] = $this->date2string($dataPas[$i]['tgl_nikah']);
           $this->mrTemplate->AddVars('data_pasangan_item', $dataPas[$i], 'PAS_');
           $this->mrTemplate->parseTemplate('data_pasangan_item', 'a');	 
  			}
  		}
		
		$dataAnak=$data['anak'];
  		if (empty($dataAnak)) {
  			$this->mrTemplate->AddVar('data_anak', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_anak', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataAnak); $i++) {
           $dataAnak[$i]['class_name'] = 'table-common-even';
           $dataAnak[$i]['number'] = $i+1;         
           $dataAnak[$i]['tgl_lahir'] = $this->date2string($dataAnak[$i]['tgl_lahir']);
           $this->mrTemplate->AddVars('data_anak_item', $dataAnak[$i], 'ANK_');
           $this->mrTemplate->parseTemplate('data_anak_item', 'a');	 
  			}
  		}
		
		$dataOrtu=$data['ortu'];
  		if (empty($dataOrtu)) {
  			$this->mrTemplate->AddVar('data_ortu', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_ortu', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataOrtu); $i++) {
           $dataOrtu[$i]['class_name'] = 'table-common-even';
           $dataOrtu[$i]['number'] = $i+1;         
           $dataOrtu[$i]['tgl_lahir'] = $this->date2string($dataOrtu[$i]['tgl_lahir']);
           $this->mrTemplate->AddVars('data_ortu_item', $dataOrtu[$i], 'ORT_');
           $this->mrTemplate->parseTemplate('data_ortu_item', 'a');	 
  			}
  		}
		
		$dataMertua=$data['mertua'];
  		if (empty($dataMertua)) {
  			$this->mrTemplate->AddVar('data_mertua', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_mertua', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataMertua); $i++) {
           $dataMertua[$i]['class_name'] = 'table-common-even';
           $dataMertua[$i]['number'] = $i+1;         
           $dataMertua[$i]['tgl_lahir'] = $this->date2string($dataMertua[$i]['tgl_lahir']);
           $this->mrTemplate->AddVars('data_mertua_item', $dataMertua[$i], 'MRT_');
           $this->mrTemplate->parseTemplate('data_mertua_item', 'a');	 
  			}
  		}
		
		$dataSdr=$data['saudara'];
  		if (empty($dataSdr)) {
  			$this->mrTemplate->AddVar('data_saudara', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_saudara', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataSdr); $i++) {
           $dataSdr[$i]['class_name'] = 'table-common-even';
           $dataSdr[$i]['number'] = $i+1;         
           $dataSdr[$i]['tgl_lahir'] = $this->date2string($dataSdr[$i]['tgl_lahir']);
           $this->mrTemplate->AddVars('data_saudara_item', $dataSdr[$i], 'SDR_');
           $this->mrTemplate->parseTemplate('data_saudara_item', 'a');	 
  			}
  		}
		
		$dataBea=$data['beasiswa'];
  		if (empty($dataBea)) {
  			$this->mrTemplate->AddVar('data_beasiswa', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_beasiswa', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataBea); $i++) {
           $dataBea[$i]['class_name'] = 'table-common-even';
           $dataBea[$i]['number'] = $i+1;         
           $dataBea[$i]['jangka_waktu'] = $dataBea[$i]['tahun'].'tahun'.','.$dataBea[$i]['bulan'].'bulan';
           $this->mrTemplate->AddVars('data_beasiswa_item', $dataBea[$i], 'BEA_');
           $this->mrTemplate->parseTemplate('data_beasiswa_item', 'a');	 
  			}
  		}
		
		$dataMengluar=$data['mengluar'];
  		if (empty($dataMengluar)) {
  			$this->mrTemplate->AddVar('data_mengajar_diluar', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_mengajar_diluar', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataMengluar); $i++) {
           $dataMengluar[$i]['class_name'] = 'table-common-even';
           $dataMengluar[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_mengajar_diluar_item', $dataMengluar[$i], 'MENGD_');
           $this->mrTemplate->parseTemplate('data_mengajar_diluar_item', 'a');	 
  			}
  		}
		
		$dataMeng=$data['mengajar'];
  		if (empty($dataMeng)) {
  			$this->mrTemplate->AddVar('data_mengajar', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_mengajar', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataMeng); $i++) {
           $dataMeng[$i]['class_name'] = 'table-common-even';
           $dataMeng[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_mengajar_item', $dataMeng[$i], 'MENG_');
           $this->mrTemplate->parseTemplate('data_mengajar_item', 'a');	 
  			}
  		}
		
		$dataKep=$data['kepakaran'];
  		if (empty($dataKep)) {
  			$this->mrTemplate->AddVar('data_kepakaran', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_kepakaran', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataKep); $i++) {
           $dataKep[$i]['class_name'] = 'table-common-even';
           $dataKep[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_kepakaran_item', $dataKep[$i], 'KEP_');
           $this->mrTemplate->parseTemplate('data_kepakaran_item', 'a');	 
  			}
  		}
		
		$dataMemb=$data['membimbing'];
  		if (empty($dataMemb)) {
  			$this->mrTemplate->AddVar('data_membimbing', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_membimbing', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataMemb); $i++) {
           $dataMemb[$i]['class_name'] = 'table-common-even';
           $dataMemb[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_membimbing_item', $dataMemb[$i], 'MEMB_');
           $this->mrTemplate->parseTemplate('data_membimbing_item', 'a');	 
  			}
  		}
		
		$dataPagol=$data['pangkat'];
  		if (empty($dataPagol)) {
  			$this->mrTemplate->AddVar('data_pangkat', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_pangkat', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPagol); $i++) {
           $dataPagol[$i]['class_name'] = 'table-common-even';
           $dataPagol[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_pangkat_item', $dataPagol[$i], 'PAGOL_');
           $this->mrTemplate->parseTemplate('data_pangkat_item', 'a');	 
  			}
  		}
		
		$dataPak=$data['pak'];
  		if (empty($dataPak)) {
  			$this->mrTemplate->AddVar('data_pak', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_pak', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPak); $i++) {
           $dataPak[$i]['class_name'] = 'table-common-even';
           $dataPak[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_pak_item', $dataPak[$i], 'PAK_');
           $this->mrTemplate->parseTemplate('data_pak_item', 'a');	 
  			}
  		}
		
		$dataPekerjaan=$data['dataPekerjaan'];
  		if (empty($dataPekerjaan)) {
        $this->mrTemplate->AddVar('data_pekerjaan', 'DATA_EMPTY', 'YES');
    	} else {
    		$this->mrTemplate->AddVar('data_pekerjaan', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataPekerjaan); $i++) {
          $dataPekerjaan[$i]['number'] = $i+1;
          $dataPekerjaan[$i]['class_name'] = 'table-common-even';
          //$dataPekerjaan[$i]['mulai'] = $this->date2string($dataPekerjaan[$i]['mulai']);
          //$dataPekerjaan[$i]['selesai'] = $this->date2string($dataPekerjaan[$i]['selesai']);
          $this->mrTemplate->AddVars('data_pekerjaan_item', $dataPekerjaan[$i], 'PEG_');
          $this->mrTemplate->parseTemplate('data_pekerjaan_item', 'a');	 
        }
      }
		
		$dataHuk=$data['huk'];
  		if (empty($dataHuk)) {
  			$this->mrTemplate->AddVar('data_hukuman', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data_hukuman', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataHuk); $i++) {
           $dataHuk[$i]['class_name'] = 'table-common-even';
           $dataHuk[$i]['number'] = $i+1;         
           $this->mrTemplate->AddVars('data_hukuman_item', $dataHuk[$i], 'HUK_');
           $this->mrTemplate->parseTemplate('data_hukuman_item', 'a');	 
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
