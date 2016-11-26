<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak_komponen.class.php';

class ViewMutasiPak extends HtmlResponse{
    function TemplateModule()
    {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
        'module/mutasi_pak_kumulatif/'.GTFWConfiguration::GetValue('application', 'template_address').'');
        $this->SetTemplateFile('view_mutasi_pak.html');
    }
	
	function OtomatisasiKegiatan($pegId,$jabatan,$nip){
		//Ini Yang Integrasi Dengan gtAkademik
		$this->StatusIntegrasiAkademik=GTFWConfiguration::GetValue( 'application', 'status_integrasi_gtakademik');
		if ($this->StatusIntegrasiAkademik){
			$nomorkoneksi=GTFWConfiguration::GetValue( 'application', 'nomor_koneksi_gtakademik');
			if ($nomorkoneksi==''){
				$pengajaran=array();
				$bimbingan=array();
				$arrkoneksi = GTFWConfiguration::GetValue( 'application', 'koneksi_gtakademik');
				for ($i=0; $i<sizeof($arrkoneksi); $i++){
					$mgjIntegrasi = new MutasiPak($arrkoneksi[$i]);
					$mgjIntegrasi->connect();
					$tempdataMgj = $mgjIntegrasi->SinkronisasiKegiatanIntegrasiAkademik($pegId,$jabatan,$nip);
					//Pengajaran
					$temppengajaran=$tempdataMgj['pengajaran'];
					for ($j=0; $j<sizeof($temppengajaran); $j++){
						$jj=-1;
						for ($ii=0; $ii<sizeof($pengajaran); $ii++){
							if ($pengajaran[$ii]['otopakpengReferensi']==$temppengajaran[$j]['otopakpengReferensi']){
								$jj=$ii; break;
							}
						}
						
						if ($jj==-1){
							$pengajaran[]=$temppengajaran[$j];
						}else{
							$pengajaran[$jj]['otopakpengSks'] += $temppengajaran[$j]['otopakpengSks'];
						}
					}
					
					//Bimbingan Mahasiswa
					$tempbimbingan=$tempdataMgj['bimbingan'];
					for ($j=0; $j<sizeof($tempbimbingan); $j++){
						$bimbingan[]=$tempbimbingan[$j];
					}
				}
			}else{
				$mgjIntegrasi = new MutasiPak($nomorkoneksi);
				$mgjIntegrasi->connect();
				$tempdataMgj = $mgjIntegrasi->SinkronisasiKegiatanIntegrasiAkademik($pegId,$jabatan,$nip);
				$pengajaran=$tempdataMgj['pengajaran'];
				$bimbingan=$tempdataMgj['bimbingan'];
			}
		}
		//==End Integrasi dengan gtAkademik
		$js = new MutasiPak();
		$js->connect();
		$js->SinkronisasiKegiatan($pegId,$pengajaran,$bimbingan);
	}
      
    function ProcessRequest() 
    {
        $js = new MutasiPak();
        $msg = Messenger::Instance()->Receive(__FILE__);
        $this->Data = $msg[0][0];
    	$this->Pesan = $msg[0][1];
    	$this->css = $msg[0][2];
		
        $ObjDatPeg = new DataPegawai();
        $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
		
		//print_r($_GET['id']);exit;
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
			$return['input']['angka_kredit_pendidikan'] = $dataPegawai[0]['angka_kredit_pendidikan'];
           
		    $this->OtomatisasiKegiatan($id,$dataPegawai[0]['jabatan_fungsional'],$dataPegawai[0]['kode']);
			$js->connect();
			$arrUnitKerja = $js->GetComboUnitKerja();
			$arrJabatan = $js->GetComboJabatan($id);
			$listDataPak = $js->GetListMutasiPak($id);
           
			if(isset($_GET['dataId'])){
				$dataMutasi = $js->GetDataMutasiById($id,$dataId);
				$return['dataMutasiDetail'] = $js->GetDataUnsurPenilaian($dataId);
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
				$js2 = new MutasiPakKomponen();
				$return['dataMutasiDetail']=$js2->GetKegiatanOtomatis($id);
			}
              
        }
           
        if(empty($tahun['start'])){
			$tahun['start']=date("Y")-25;
  	    }
		$tahun['end'] = date("Y")+5;
           
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
  	    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_penetapan',array($return['input']['tgl_penetapan'], $tahun['start'], $tahun['end'], '', '', 'tgl_penetapan'), Messenger::CurrentRequest);
           
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
          
      	//set the language
      	$lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	if ($lang=='eng'){
      		$active="Active";$inactive="Inactive";
      	}else{
      		$active="Aktif";$inactive="Tidak Aktif";
      	}	
      	$data['lang']=$lang;
  		$arrKegiatan = $js->GetComboKegiatan();
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', array('unit_kerja', $arrUnitKerja, $return['input']['unit_kerja'], '', ''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabatan', array('jabatan', $arrJabatan, $return['input']['jabatan'], '', ''), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'kegiatan', array('kegiatan', $arrKegiatan, '', '', ''), Messenger::CurrentRequest);
  
  	      
        $return['dataPegawai'] = $dataPegawai;
    	$return['listDataPak'] = $listDataPak;
		$return['paksebelum'] = $js->CekPAKSebelum($id);
    	return $return;  
    }
      
    function ParseTemplate($data = NULL)
    {
        if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
        }
		
		if (isset($_GET['dataId'])){
			$this->mrTemplate->SetAttribute('edit_mode', 'visibility', 'visible');
		}else{
			if ($data['paksebelum']){
				$this->mrTemplate->AddVar('content', 'DISPLAY_FORM', 'none');
			}else{
				$this->mrTemplate->AddVar('content', 'DISPLAY_FORM', '');
			}
		}
      
		$dataPegawai = $data['dataPegawai'];
        $dataPak = $data['listDataPak'];

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
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'updateMutasiPak', 'do', 'html'));
        }else{
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'addMutasiPak', 'do', 'html'));
        }
      
        //$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'Pegawai', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'popupKegiatan', 'view', 'html') );
		$this->mrTemplate->AddVar('edit_mode', 'URL_CARI_OTO', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'popupKegiatan', 'view', 'html').'&tipe=otomatis&pegId='.$dataPegawai[0]['id'] );
        $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
        
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
      
		if (empty($dataPak)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
			$label = "Manajemen Mutasi Pengajuan Angka Kredit";
			$urlDelete = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'deleteMutasiPak', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'html');
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
				$urlAccept = 'mutasi_pak_kumulatif|deleteMutasiPak|do|html-id-'.$dataPegawai[0]['id'];
				$urlKembali = 'mutasi_pak_kumulatif|MutasiPak|view|html-id-'.$dataPegawai[0]['id'];
				$label = 'Data Pengajuan Angka Kredit';
				$dataPak[$i]['tanggal_penetapan'] = $this->date2string($dataPak[$i]['tanggal_penetapan']);
				$dataPak[$i]['mulai'] = $this->date2string($dataPak[$i]['mulai']);
				$dataPak[$i]['selesai'] = $this->date2string($dataPak[$i]['selesai']);
				$dataName = 'Periode '.$dataPak[$i]['mulai'].' s/d '.$dataPak[$i]['selesai'];
				$dataPak[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
				$dataPak[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','MutasiPak', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				if (($dataPak[$i]['ditetapkan']=='0')||($dataPak[$i]['ditetapkan']==0)){
					$dataPak[$i]['EDIT']='<a class="xhr dest_subcontent-element" href="'.$dataPak[$i]['URL_EDIT'].'" title="Edit"><img src="images/button-edit.gif" alt="Edit"/></a>';
					$dataPak[$i]['DELETE']='<a class="xhr dest_subcontent-element" href="'.$dataPak[$i]['URL_DELETE'].'" title="Hapus"><img src="images/button-delete.gif" alt="Hapus"/></a>';
				}else{
					$dataPak[$i]['EDIT']='Disetujui';
				}
				$dataPak[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				if (!empty($dataPak[$i]['upload'])){
					$dataPak[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataPak[$i]['upload'];
				} else{
					$dataPak[$i]['LINK_DOWNLOAD_SK'] = '';
				}
             
				$this->mrTemplate->AddVars('data_item', $dataPak[$i], 'PAK_');
				$this->mrTemplate->parseTemplate('data_item', 'a');	 
      		}
  		}
		
		$dataDetail=$data['dataMutasiDetail'];
		$total_angka_kredit=0;
		if (empty($dataDetail)) {
  			$this->mrTemplate->AddVar('tpl_kegiatan_list', 'KEGIATAN_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_kegiatan_list', 'KEGIATAN_LIST_EMPTY', 'NO');
			for ($i=0; $i<count($dataDetail); $i++) {
				if ($no % 2 != 0) {
					$dataDetail[$i]['class_name'] = 'table-common-even';
				}else{
					$dataDetail[$i]['class_name'] = '';
				}
				$total_angka_kredit += $dataDetail[$i]['angka_kredit'];
				if ($dataDetail[$i]['lampiran']!=''){
					$dataDetail[$i]['bukti']='';
					$dataDetail[$i]['pathlampiran'] = $data['link']['link_download'].$dataDetail[$i]['lampiran'];
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'visible');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'hidden');
					$this->mrTemplate->AddVars('lampiran_softcopy', $dataDetail[$i], 'DATA_');
					$this->mrTemplate->AddVars('lampiran_hardcopy', $dataDetail[$i], 'DATA_');
				}else{
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'hidden');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'visible');
				}
				$this->mrTemplate->AddVars('tpl_kegiatan_item', $dataDetail[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('tpl_kegiatan_item', 'a');	 
      		}
  		}
		$this->mrTemplate->AddVar('content', 'TOTAL_ANGKA_KREDIT', $total_angka_kredit);
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
