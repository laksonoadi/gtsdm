<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_dp4/business/mutasi_dp4.class.php';

   class ViewDetailMutasi extends HtmlResponse
   {
      function TemplateModule()
      {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/mutasi_dp4/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_mutasi.html');
      }

      function ProcessRequest()
      {
        $js = new MutasiDp4();
        
        $msg = Messenger::Instance()->Receive(__FILE__);
        $this->Data = $msg[0][0];
    		$this->Pesan = $msg[0][1];
    		$this->css = $msg[0][2];
        
        $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
        $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
        $this->profilId=$id;
        
        
        $tahun=array();
        if(isset($_GET['id'])){
           $dataPegawai = $js->GetDataDetail($id);
           $return['input']['nip'] = $dataPegawai[0]['kode'];
           $return['input']['no_seri'] = $dataPegawai[0]['no_seri'];
           $return['input']['tanggal_lahir'] = $this->date2string($dataPegawai[0]['tgl_lahir']);
           $return['input']['jenis_kelamin'] = $dataPegawai[0]['jenis_kelamin']=='L'?'Laki-laki':'Perempuan';
           $return['input']['pendidikan'] = $dataPegawai[0]['pendidikan_tertinggi'];
           $return['input']['pangkat_golongan'] = $dataPegawai[0]['pangkat_golongan'].' / '.$this->date2string($dataPegawai[0]['pangkat_golongan_tmt']);
           $return['input']['jabatan_fungsional'] = $dataPegawai[0]['jabatan_fungsional'].' / '.$this->date2string($dataPegawai[0]['jabatan_fungsional_tmt']);
           $return['input']['unit_kerja'] = $dataPegawai[0]['unit_kerja_id'];
           $return['input']['unit_kerja_label'] = $dataPegawai[0]['unit_kerja'];
           
           $arrUnitKerja = $js->GetComboUnitKerja();
           $arrJabatan = $js->GetComboJabatan($id);
           $listDataDp4 = $js->GetListMutasiDp4($id);
           $dataUnsur['utama'] = $js->GetDataUnsurPenilaian($id,$dataId,'Utama');
           $dataUnsur['penunjang'] = $js->GetDataUnsurPenilaian($id,$dataId,'Penunjang');
           
           if(isset($_GET['dataId'])){
              $dataMutasi = $js->GetDataMutasiById($id,$dataId);
              $result=$dataMutasi[0];
              if(!empty($result)){
                  $return['input']['pejabat_id'] = $result['pejabat_id'];
    			        $return['input']['pejabat_nip'] = $result['pejabat_nip'];
    			        $return['input']['pejabat_nama'] = $result['pejabat_nama'];
    			        $return['input']['pejabat_pangkat'] = $result['pejabat_pangkat'];
    			        $return['input']['pejabat_jabatan'] = $result['pejabat_jabatan'];
    			        $return['input']['pejabat_unit_kerja'] = $result['pejabat_unit_kerja'];
    			       
    			        $return['input']['atasan_pejabat_id'] = $result['atasan_pejabat_id'];
    			        $return['input']['atasan_pejabat_nip'] = $result['atasan_pejabat_nip'];
    			        $return['input']['atasan_pejabat_nama'] = $result['atasan_pejabat_nama'];
    			        $return['input']['atasan_pejabat_pangkat'] = $result['atasan_pejabat_pangkat'];
    			        $return['input']['atasan_pejabat_jabatan'] = $result['atasan_pejabat_jabatan'];
    			        $return['input']['atasan_pejabat_unit_kerja'] = $result['atasan_pejabat_unit_kerja'];
    			        
  			          $return['input']['id'] = $result['id'];
                  $return['input']['pegId'] = $id;
  			          $return['input']['tgl_buat'] = $this->date2string($result['tgl_buat']);
  			          $return['input']['tgl_pns'] = $this->date2string($result['tgl_pns']);
  			          $return['input']['tgl_diterima'] = $this->date2string($result['tgl_diterima']);
  			          $return['input']['mulai'] = $this->date2string($result['mulai']);
  			          $return['input']['selesai'] = $this->date2string($result['selesai']);
  			          
  			          $return['input']['kesetiaan'] = $result['kesetiaan'];
      			      $return['input']['prestasi_kerja'] = $result['prestasi_kerja'];
      			      $return['input']['tanggung_jawab'] = $result['tanggung_jawab'];
      			      $return['input']['ketaatan'] = $result['ketaatan'];
      			      $return['input']['kejujuran'] = $result['kejujuran'];
      			      $return['input']['kerjasama'] = $result['kerjasama'];
      			      $return['input']['prakarsa'] = $result['prakarsa'];
      			      $return['input']['kepemimpinan'] = $result['kepemimpinan'];
      			      $return['input']['keberatan'] = $result['keberatan'];
      			      $return['input']['tanggapan_keberatan'] = $result['tanggapan_keberatan'];
      			      $return['input']['keputusan_atasan'] = $result['keputusan_atasan'];
      			      $return['input']['lain_lain'] = $result['lain_lain'];
      			      $return['input']['nilai_yayasan'] = $result['nilai_yayasan'];
  			      }    
           }else{
               $return['input']['pejabat_id'] = '';
  			       $return['input']['pejabat_nip'] = '--belum dipilih--';
  			       $return['input']['pejabat_nama'] = '--belum dipilih--';
  			       $return['input']['pejabat_pangkat'] = '--belum dipilih--';
  			       $return['input']['pejabat_jabatan'] = '--belum dipilih--';
  			       $return['input']['pejabat_unit_kerja'] = '--belum dipilih--';
  			       
  			       $return['input']['atasan_pejabat_id'] = '';
  			       $return['input']['atasan_pejabat_nip'] = '--belum dipilih--';
  			       $return['input']['atasan_pejabat_nama'] = '--belum dipilih--';
  			       $return['input']['atasan_pejabat_pangkat'] = '--belum dipilih--';
  			       $return['input']['atasan_pejabat_jabatan'] = '--belum dipilih--';
  			       $return['input']['atasan_pejabat_unit_kerja'] = '--belum dipilih--';
  			       
               $return['input']['id'] = '';
               $return['input']['pegId'] = $dataPegawai[0]['id'];
  			       $return['input']['tgl_buat'] = date('Y-m-d');
  			       $return['input']['tgl_pns'] = date('Y-m-d');
  			       $return['input']['tgl_diterima'] = date('Y-m-d');
  			       $return['input']['mulai'] = date('Y-m-d');
  			       $return['input']['selesai'] = date('Y-m-d');
  			       
  			       $return['input']['kesetiaan'] = '';
  			       $return['input']['prestasi_kerja'] = '';
  			       $return['input']['tanggung_jawab'] = '';
  			       $return['input']['ketaatan'] = '';
  			       $return['input']['kejujuran'] = '';
  			       $return['input']['kerjasama'] = '';
  			       $return['input']['prakarsa'] = '';
  			       $return['input']['kepemimpinan'] = '';
  			       $return['input']['keberatan'] = '';
  			       $return['input']['tanggapan_keberatan'] = '';
  			       $return['input']['keputusan_atasan'] = '';
  			       $return['input']['lain_lain'] = '';
  			       $return['input']['nilai_yayasan'] = '';
           }
              
        }

        $return['dataPegawai'] = $dataPegawai;
    		$return['listDataDp4'] = $listDataDp4;
    		return $return;  
      }

      function ParseTemplate($data = NULL) {
      
        $dataPegawai = $data['dataPegawai'];
        $dataDp4 = $data['listDataDp4'];

    	  if($data['lang']=='eng') {
      		$this->mrTemplate->AddVar('content', 'TITLE', 'LECTURER CREDIT DETERMINATION MUTATION');
      		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Edit' : 'Add');
      		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Cancel ' : 'Reset');
      		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
        } else {
      		$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI PENETAPAN ANGKA KREDIT');
      		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Batal ' : 'Reset');
      		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
    	  }
	  
        if ( isset($_GET['dataId'])) {
           $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'updateMutasiDp4', 'do', 'html'));
        }else{
           $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'addMutasiDp4', 'do', 'html'));
        }
      
        
        $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'MutasiDp4', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
        
        $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
        $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
        $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
        $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
        if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai[0]['foto']) | empty($dataPegawai[0]['foto'])) { 
  		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
  	  	}else{
          $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
        }
      
        if(!empty($data['input'])){
  		    $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
  	   }
      
      }

      function dumper($print){
         echo"<pre>";print_r($print);echo"</pre>";
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
