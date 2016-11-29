<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pak_kumulatif/business/mutasi_pak.class.php';

class ViewDetailMutasi extends HtmlResponse{
    function TemplateModule()
    {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/mutasi_pak_kumulatif/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_detail_mutasi.html');
    }

    function ProcessRequest()
    {
        $js = new MutasiPak();
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
           
			if(isset($_GET['dataId'])){
				$dataMutasi = $js->GetDataMutasiById($id,$dataId);
				$return['dataMutasiDetail'] = $js->GetDataUnsurPenilaian($dataId);
				$result=$dataMutasi[0];
				if(!empty($result)){
  			        $return['input']['id'] = $result['id'];
					$return['input']['pegId'] = $result['pegId'];
  			        $return['input']['tanggal_penetapan'] = $this->date2string($result['tgl_penetapan']);
  			        $return['input']['pejabat'] =  $result['pejabat'];
  			        $return['input']['mulai'] =  $this->date2string($result['mulai']);
  			        $return['input']['selesai'] =  $this->date2string($result['selesai']);
  			        $return['input']['nopak'] = $result['nopak'];
  			        $return['input']['jabatan'] = $result['diangkat_label'];
  			    }    
			}else{
				$return['input']['id'] = '';
				$return['input']['pegId'] = $dataPegawai[0]['id'];
				$return['input']['jabatan'] = $dataPegawai[0]['diangkat_label'];
  			    $return['input']['tanggal_penetapan'] = $this->date2string(date('Y-m-d'));
  			    $return['input']['mulai'] = $this->date2string(date('Y-m-d'));
  			    $return['input']['selesai'] = $this->date2string(date('Y-m-d'));
  			    $return['input']['pejabat'] = '';
  			    $return['input']['nopak'] = '';
			}
              
        }  
  	      
		$return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
		
        $return['dataPegawai'] = $dataPegawai;
    	$return['listDataPak'] = $listDataPak;
		$return['dataMutasi'] = $dataMutasi;
    	return $return;  
    }

    function ParseTemplate($data = NULL) {
        $dataPegawai = $data['dataPegawai'];
		$dataMutasi = $data['dataMutasi'];
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
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'xls').'&id='.$dataPegawai[0]['id'].'&dataId='.$dataMutasi[0]['id']);
        $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pak_kumulatif', 'MutasiPak', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
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
	   
		$dataDetail=$data['dataMutasiDetail'];
		$total_angka_kredit=0;
		if (empty($dataDetail)) {
			$this->mrTemplate->AddVar('tpl_kegiatan_list', 'KEGIATAN_LIST_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('tpl_kegiatan_list', 'KEGIATAN_LIST_EMPTY', 'NO');
			$unsur=''; $subunsur='';
			for ($i=0; $i<count($dataDetail); $i++) {
				if ($no % 2 != 0) {
					$dataDetail[$i]['class_name'] = 'table-common-even';
				}else{
					$dataDetail[$i]['class_name'] = '';
				}
				
				if ($unsur!=$dataDetail[$i]['unsur']){
					$unsur=$dataDetail[$i]['unsur'];
					$temp['kegiatan']='<b>'.$unsur.'</b>';
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'hidden');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'visible');
					$this->mrTemplate->AddVars('tpl_kegiatan_item', $temp, 'DATA_');
					$this->mrTemplate->parseTemplate('tpl_kegiatan_item', 'a');	 
				}
				if ($subunsur!=$dataDetail[$i]['subunsur']){
					$subunsur=$dataDetail[$i]['subunsur'];
					$temp['kegiatan']='<b><i>'.$subunsur.'</i></b>';
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'hidden');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'visible');
					$this->mrTemplate->AddVars('tpl_kegiatan_item', $temp, 'DATA_');
					$this->mrTemplate->parseTemplate('tpl_kegiatan_item', 'a');	 
				}
				
				if (!empty($dataDetail[$i]['lampiran'])){
					$dataDetail[$i]['pathlampiran'] = $data['link']['link_download'].$dataDetail[$i]['lampiran'];
					$this->mrTemplate->AddVars('lampiran_softcopy', $dataDetail[$i], 'DATA_');
					$this->mrTemplate->AddVars('lampiran_hardcopy', $dataDetail[$i], 'DATA_');
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'visible');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'hidden');
				}else{
					$this->mrTemplate->SetAttribute('lampiran_softcopy', 'visibility', 'hidden');
					$this->mrTemplate->SetAttribute('lampiran_hardcopy', 'visibility', 'visible');
				}
				
				$total_angka_kredit += $dataDetail[$i]['angka_kredit'];
				$this->mrTemplate->AddVars('tpl_kegiatan_item', $dataDetail[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('tpl_kegiatan_item', 'a');	 
      		}
  		}
		$this->mrTemplate->AddVar('content', 'TOTAL_ANGKA_KREDIT', $total_angka_kredit);
  	   
  	   
      
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
