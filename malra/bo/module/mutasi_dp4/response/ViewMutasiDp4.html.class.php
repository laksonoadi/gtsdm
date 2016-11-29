<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_dp4/business/mutasi_dp4.class.php';

class ViewMutasiDp4 extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_dp4/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_dp4.html');
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
  			          $return['input']['tgl_buat'] = $result['tgl_buat'];
  			          $return['input']['tgl_pns'] = $result['tgl_pns'];
  			          $return['input']['tgl_diterima'] = $result['tgl_diterima'];
  			          $return['input']['mulai'] = $result['mulai'];
  			          $return['input']['selesai'] = $result['selesai'];
  			          
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
  			       $return['input']['tgl_pns'] = $dataPegawai[0]['tgl_pns']=='0000-00-00'?date('Y-m-d'):$dataPegawai[0]['tgl_pns'];
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
           
        if(empty($tahun['start'])){
  	       $tahun['start']=date("Y")-25;
  	    }
        $tahun['end'] = date("Y")+5;
           
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
  	    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_buat',array($return['input']['tgl_buat'], $tahun['start'], $tahun['end'], '', '', 'tgl_buat'), Messenger::CurrentRequest);
  	    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_pns',array($return['input']['tgl_pns'], $tahun['start'], $tahun['end'], '', '', 'tgl_pns'), Messenger::CurrentRequest);
  	    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_diterima',array($return['input']['tgl_diterima'], $tahun['start'], $tahun['end'], '', '', 'tgl_diterima'), Messenger::CurrentRequest);
           
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
          
      	//set the language
      	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	$data['lang']=$lang;
  	      
        $return['dataPegawai'] = $dataPegawai;
    		$return['listDataDp4'] = $listDataDp4;
    		return $return;  
      }
      
      function ParseTemplate($data = NULL)
      {
         if($this->Pesan){
           $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
           $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
           $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
         }
      
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
      
        $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'Pegawai', 'view', 'html') );
        $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'MutasiDp4', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
        $this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('mutasi_dp4', 'popupPegawai', 'view', 'html'));
        
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
      
       if (empty($dataDp4)) {
  			 $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		 } else {
  			 $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
          $label = "Manajemen Mutasi DP4";
          $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_dp4', 'deleteMutasiDp4', 'do', 'html');
          $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_dp4', 'MutasiDp4', 'view', 'html');
          Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
          $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

          $total=0;
          $start=1;
          for ($i=0; $i<count($dataDp4); $i++) {
             $no = $i+$start;
             $dataDp4[$i]['number'] = $no;
             if ($no % 2 != 0) {
                $dataDp4[$i]['class_name'] = 'table-common-even';
             }else{
                $dataDp4[$i]['class_name'] = '';
             }
    
            if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
            if($i == sizeof($dataDp4)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
            $dataDp4[$i]['tanggal_penetapan'] = $this->date2string($dataDp4[$i]['tanggal_penetapan']);
            $dataDp4[$i]['mulai'] = $this->date2string($dataDp4[$i]['mulai']);
            $dataDp4[$i]['selesai'] = $this->date2string($dataDp4[$i]['selesai']);
            $dataDp4[$i]['tanggal_penilaian'] = $this->date2string($dataDp4[$i]['tanggal_penilaian']);
            $dataDp4[$i]['tanggal_diterima'] = $this->date2string($dataDp4[$i]['tanggal_diterima']);
          
            $idEnc = Dispatcher::Instance()->Encrypt($dataDp4[$i]['id']);
            $urlAccept = 'mutasi_dp4|deleteMutasiDp4|do|html-id-'.$dataPegawai[0]['id'];
            $urlKembali = 'mutasi_dp4|MutasiDp4|view|html-id-'.$dataPegawai[0]['id'];
            $label = 'Data Mutasi DP4';
            $dataName = $dataDp4[$i]['mulai'].' s/d '.$dataDp4[$i]['selesai'].' dari '.$dataDp4[$i]['yang_dinilai'];
            $dataDp4[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $dataDp4[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_dp4','MutasiDp4', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
            $dataDp4[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_dp4','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
             
            $this->mrTemplate->AddVars('data_item', $dataDp4[$i], 'DP4_');
            $this->mrTemplate->parseTemplate('data_item', 'a');	 
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
