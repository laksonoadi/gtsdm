<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_pak/business/mutasi_pak.class.php';

class ViewMutasiPak extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_pak/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_pak.html');
      }
      
      function ProcessRequest() 
      {
        $js = new MutasiPak();
        
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
           $listDataPak = $js->GetListMutasiPak($id);
           $dataUnsur['utama'] = $js->GetDataUnsurPenilaian($id,$dataId,'Utama');
           $dataUnsur['penunjang'] = $js->GetDataUnsurPenilaian($id,$dataId,'Penunjang');
           
           if(isset($_GET['dataId'])){
              $dataMutasi = $js->GetDataMutasiById($id,$dataId);
              $result=$dataMutasi[0];
              if(!empty($result)){
  			          $return['input']['id'] = $result['id'];
                  $return['input']['pegId'] = $result['pegId'];
  			          $return['input']['tgl_penetapan'] = $result['tgl_penetapan'];
  			          $return['input']['pejabat'] = $result['pejabat'];
  			          $return['input']['mulai'] = $result['mulai'];
  			          $return['input']['selesai'] = $result['selesai'];
  			          $return['input']['nopak'] = $result['nopak'];
  			          $return['input']['jabatan'] = $result['diangkat'];
  			      }    
           }else{
               $return['input']['id'] = '';
               $return['input']['pegId'] = $dataPegawai[0]['id'];
               $return['input']['jabatan'] = $dataPegawai[0]['diangkat'];
  			       $return['input']['tgl_penetapan'] = date('Y-m-d');
  			       $return['input']['mulai'] = date('Y-m-d');
  			       $return['input']['selesai'] = date('Y-m-d');
  			       $return['input']['pejabat'] = '';
  			       $return['input']['nopak'] = '';
           }
              
        }
           
        if(empty($tahun['start'])){
  	       $tahun['start']=date("Y")-25;
  	    }
        $tahun['end'] = date("Y")+5;
           
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
  	    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_penetapan',array($return['input']['tgl_penetapan'], $tahun['start'], $tahun['end'], '', '', 'tgl_penetapan'), Messenger::CurrentRequest);
           
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
          
      	//set the language
      	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	if ($lang=='eng'){
      			$active="Active";$inactive="Inactive";
      	}else{
      			$active="Aktif";$inactive="Tidak Aktif";
      	}	
      	$data['lang']=$lang;
  		
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', array('unit_kerja', $arrUnitKerja, $return['input']['unit_kerja'], '', ''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabatan', array('jabatan', $arrJabatan, $return['input']['jabatan'], '', ''), Messenger::CurrentRequest);
  
  	      
        $return['dataPegawai'] = $dataPegawai;
    		$return['listDataPak'] = $listDataPak;
    		$return['dataUnsur'] = $dataUnsur;
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
        $dataPak = $data['listDataPak'];
        $unsurUtama = $data['dataUnsur']['utama'];
        $unsurPenunjang = $data['dataUnsur']['penunjang'];

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
           $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pak', 'updateMutasiPak', 'do', 'html'));
        }else{
           $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pak', 'addMutasiPak', 'do', 'html'));
        }
      
        $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pak', 'Pegawai', 'view', 'html') );
        $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_pak', 'MutasiPak', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
        
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
           
          $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
  		    $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
  	   }
  	   
  	   if (empty($unsurUtama)) {
  			 $this->mrTemplate->AddVar('unsur_utama', 'DATA_EMPTY', 'YES');
  		 } else {
  			 $this->mrTemplate->AddVar('unsur_utama', 'DATA_EMPTY', 'NO');
  			 $utama['jumlah']=count($unsurUtama);
         $utama['lama']=0;
         $utama['baru']=0;
         $utama['digunakan']=0;
         $utama['lebihan']=0;
         $start=1;
         for ($i=0; $i<count($unsurUtama); $i++) {
            $no = $i+$start;
            $unsurUtama[$i]['nomor'] = $no;
            $utama['lama'] +=$unsurUtama[$i]['lama'];
            $utama['baru'] +=$unsurUtama[$i]['baru'];
            $utama['digunakan'] +=$unsurUtama[$i]['digunakan'];
            $utama['lebihan'] +=$unsurUtama[$i]['lebihan'];
            $this->mrTemplate->AddVars('unsur_utama_item', $unsurUtama[$i], '');
            $this->mrTemplate->parseTemplate('unsur_utama_item', 'a');	 
      	 }
      	 $this->mrTemplate->AddVars('content', $utama, 'UTAMA_');
  		 }
  		 
  		 if (empty($unsurPenunjang)) {
  			 $this->mrTemplate->AddVar('unsur_penunjang', 'DATA_EMPTY', 'YES');
  		 } else {
  			 $this->mrTemplate->AddVar('unsur_penunjang', 'DATA_EMPTY', 'NO');
  			 $penunjang['jumlah']=count($unsurPenunjang);
         $penunjang['lama']=0;
         $penunjang['baru']=0;
         $penunjang['digunakan']=0;
         $penunjang['lebihan']=0;
         $start=1;
         for ($i=0; $i<count($unsurPenunjang); $i++) {
            $no = $i+$start;
            $unsurPenunjang[$i]['nomor'] = $no;
            $penunjang['lama'] +=$unsurPenunjang[$i]['lama'];
            $penunjang['baru'] +=$unsurPenunjang[$i]['baru'];
            $penunjang['digunakan'] +=$unsurPenunjang[$i]['digunakan'];
            $penunjang['lebihan'] +=$unsurPenunjang[$i]['lebihan'];
            $this->mrTemplate->AddVars('unsur_penunjang_item', $unsurPenunjang[$i], '');
            $this->mrTemplate->parseTemplate('unsur_penunjang_item', 'a');	 
      	 }
      	 $this->mrTemplate->AddVars('content', $penunjang, 'PENUNJANG_');
  		 }
  		 
  		 $tot['lama']=0+$utama['lama']+$penunjang['lama'];
  		 $tot['baru']=0+$utama['baru']+$penunjang['baru'];
  		 $tot['digunakan']=0+$utama['digunakan']+$penunjang['digunakan'];
  		 $tot['lebihan']=0+$utama['lebihan']+$penunjang['lebihan'];
  		 $this->mrTemplate->AddVars('content', $tot, 'TOTAL_');
      
       if (empty($dataPak)) {
  			 $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		 } else {
  			 $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
          $label = "Manajemen Mutasi Penetapan Angka Kredit";
          $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_jabatan_pak', 'deleteMutasiPak', 'do', 'html');
          $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_jabatan_pak', 'MutasiPak', 'view', 'html');
          Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
          $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

          $total=0;
          $start=1;
          for ($i=0; $i<count($dataPak); $i++) {
             $no = $i+$start;
             $dataPak[$i]['number'] = $no;
             if ($no % 2 != 0) {
                $dataPak[$i]['class_name'] = 'table-common-even';
             }else{
                $dataPak[$i]['class_name'] = '';
             }
    
            if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
            if($i == sizeof($dataPak)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
          
            $idEnc = Dispatcher::Instance()->Encrypt($dataPak[$i]['id']);
            $urlAccept = 'mutasi_pak|deleteMutasiPak|do|html-id-'.$dataPegawai[0]['id'];
            $urlKembali = 'mutasi_pak|MutasiPak|view|html-id-'.$dataPegawai[0]['id'];
            $label = 'Data Mutasi PAK';
            $dataName = $dataPak[$i]['nomor'];
            $dataPak[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
            $dataPak[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_pak','MutasiPak', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
            $dataPak[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_pak','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
            $dataPak[$i]['tanggal_penetapan'] = $this->date2string($dataPak[$i]['tanggal_penetapan']);
            $dataPak[$i]['mulai'] = $this->date2string($dataPak[$i]['mulai']);
            $dataPak[$i]['selesai'] = $this->date2string($dataPak[$i]['selesai']);
            if (!empty($dataPak[$i]['upload'])){
              $dataPak[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataPak[$i]['upload'];
            } else{
              $dataPak[$i]['LINK_DOWNLOAD_SK'] = '';
            }
             
            $this->mrTemplate->AddVars('data_item', $dataPak[$i], 'PAK_');
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
