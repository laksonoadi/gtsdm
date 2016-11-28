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
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_bintang_tanda_jasa/business/MutasiBintangTandaJasa.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_istri_suami/business/istri_suami.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_anak/business/data_anak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_orang_tua/business/data_orang_tua.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_saudara_kandung/business/data_saudara_kandung.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_mertua/business/data_mertua.class.php';

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
      $this->tj = new MutasiBintangTandaJasa();

      $this->sutri = new IstriSuami();
      $this->anak = new Anak();
      $this->ortu = new OrangTua();
      $this->sdr = new SaudaraKandung();
      $this->mtua = new Mertua();
	  
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
      }else{
         $pNama = 0;
      }
       
      $data['dataPegawai']=$this->Obj->GetDataDetail($pegId);
      $data['dataPend'] = $this->pend->GetListMutasiPendidikanVerifikasi($pegId);
      $data['dataOrg'] = $this->org->GetListMutasiOrganisasiPegawaiVerifikasi($pegId);
      $data['dataPel'] = $this->pel->GetListMutasiPelatihanVerifikasi($pegId);
      // $data['dataSem'] = $this->sem->GetListMutasiSeminar($pegId);
      $data['dataPeng'] = $this->peng->GetListMutasiPenghargaanVerifikasi($pegId);
      // $data['dataPenel'] = $this->penel->GetListMutasiPenelitian($pegId);
      $data['dataKunj'] = $this->kunj->GetListMutasiKunjunganVerifikasi($pegId);
      $data['dataJabs'] = $this->js->GetListMutasiJabatanStruktural($pegId);     
      $data['dataTandaJasa'] = $this->tj->GetListMutasiVerifikasi($pegId);     


      $data['dataSutri'] = $this->sutri->GetDataIstriVerifikasi($pegId);
      
      $data['dataAnak'] = $this->anak->GetDataAnakVerifikasi($pegId);
      $data['dataOrtu'] = $this->ortu->GetDataOrtuVerifikasi($pegId);
      $data['dataSdr'] = $this->sdr->GetDataSdrVerifikasi($pegId);
      $data['dataMertua'] = $this->mtua->GetDataMertuaVerifikasi($pegId);
	  
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
      $this->mrTemplate->AddVar('content', 'URL_PDF', Dispatcher::Instance()->GetUrl('laporan_cv', 'PdfLaporanCv', 'view', 'pdfx').'&id='.$_GET['id'] );
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
  		
  		$dataTandaJasa=$data['dataTandaJasa'];
  		if (empty($dataTandaJasa)) {
        $this->mrTemplate->AddVar('data_tanda_jasa', 'DATA_EMPTY', 'YES');
    	} else {
    		$this->mrTemplate->AddVar('data_tanda_jasa', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataTandaJasa); $i++) {
          $dataTandaJasa[$i]['number'] = $i+1;
          $dataTandaJasa[$i]['class_name'] = 'table-common-even';
          $dataTandaJasa[$i]['tanggal'] = $this->date2string($dataTandaJasa[$i]['tanggal']);
          $this->mrTemplate->AddVars('data_tanda_jasa_item', $dataTandaJasa[$i]);
          $this->mrTemplate->parseTemplate('data_tanda_jasa_item', 'a');	 
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
      
      $dataSutri=$data['dataSutri'];
      if (empty($dataSutri)) {
        $this->mrTemplate->AddVar('data_suami_istri', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_suami_istri', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataSutri); $i++) {
          $dataSutri[$i]['number'] = $i+1; 
          
          $dataSutri[$i]['tgl_lahir'] = $this->date2string($dataSutri[$i]['tgl_lahir']);
          $dataSutri[$i]['tgl_nikah'] = $this->date2string($dataSutri[$i]['tgl_nikah']);
          $dataSutri[$i]['class_name'] = 'table-common-even';
          
          $this->mrTemplate->AddVars('data_suami_istri_item', $dataSutri[$i]);
          $this->mrTemplate->parseTemplate('data_suami_istri_item', 'a');  
        }
      }

       $dataAnak=$data['dataAnak'];
      if (empty($dataAnak)) {
        $this->mrTemplate->AddVar('data_anak', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_anak', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataAnak); $i++) {
          $dataAnak[$i]['number'] = $i+1; 
          
          $dataAnak[$i]['tgl_lahir'] = $this->date2string($dataAnak[$i]['tgl_lahir']);
          $dataAnak[$i]['tgl_nikah'] = $this->date2string($dataAnak[$i]['tgl_nikah']);
          $dataAnak[$i]['class_name'] = 'table-common-even';
          
          $this->mrTemplate->AddVars('data_anak_item', $dataAnak[$i]);
          $this->mrTemplate->parseTemplate('data_anak_item', 'a');  
        }
      }
  		

       $dataMertua=$data['dataMertua'];
       
      if (empty($dataMertua)) {
        $this->mrTemplate->AddVar('data_mertua', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_mertua', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataMertua); $i++) {
          $dataMertua[$i]['number'] = $i+1; 
          
          $dataMertua[$i]['tgl_lahir'] = $this->date2string($dataMertua[$i]['tgl_lahir']);
          $dataMertua[$i]['tgl_nikah'] = $this->date2string($dataMertua[$i]['tgl_nikah']);
          $dataMertua[$i]['class_name'] = 'table-common-even';
          
          $this->mrTemplate->AddVars('data_mertua_item', $dataMertua[$i]);
          $this->mrTemplate->parseTemplate('data_mertua_item', 'a');  
        }
      }

       $dataOrtu=$data['dataOrtu'];
       
      if (empty($dataOrtu)) {
        $this->mrTemplate->AddVar('data_orangtua', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_orangtua', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataOrtu); $i++) {
          $dataOrtu[$i]['number'] = $i+1; 
          
          $dataOrtu[$i]['tgl_lahir'] = $this->date2string($dataOrtu[$i]['tgl_lahir']);
          $dataOrtu[$i]['tgl_nikah'] = $this->date2string($dataOrtu[$i]['tgl_nikah']);
          $dataOrtu[$i]['class_name'] = 'table-common-even';
          
          $this->mrTemplate->AddVars('data_orangtua_item', $dataOrtu[$i]);
          $this->mrTemplate->parseTemplate('data_orangtua_item', 'a');  
        }
      }

      $dataSdr=$data['dataSdr'];
       
      if (empty($dataSdr)) {
        $this->mrTemplate->AddVar('data_saudara', 'DATA_EMPTY', 'YES');
      } else {
        $this->mrTemplate->AddVar('data_saudara', 'DATA_EMPTY', 'NO');
        for ($i=0; $i<count($dataSdr); $i++) {
          $dataSdr[$i]['number'] = $i+1; 
          
          $dataSdr[$i]['tgl_lahir'] = $this->date2string($dataSdr[$i]['tgl_lahir']);
          $dataSdr[$i]['tgl_nikah'] = $this->date2string($dataSdr[$i]['tgl_nikah']);
          $dataSdr[$i]['class_name'] = 'table-common-even';
          
          $this->mrTemplate->AddVars('data_saudara_item', $dataSdr[$i]);
          $this->mrTemplate->parseTemplate('data_saudara_item', 'a');  
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
