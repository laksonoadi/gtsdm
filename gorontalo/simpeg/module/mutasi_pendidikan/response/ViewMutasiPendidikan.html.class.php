<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';

class ViewMutasiPendidikan extends HtmlResponse{
    function TemplateModule(){
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/mutasi_pendidikan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
        $this->SetTemplateFile('view_mutasi_pendidikan.html');
	}
      
    function ProcessRequest() {
		$pend = new MutasiPendidikan();
      
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		$dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
		$this->profilId=$id;
      
		$arrjp = $pend->GetComboTingkatPendidikan();
		$arrpg = $pend->GetComboPktGol();
		$arras = $pend->GetComboAsalDana();
		$arrneg = $pend->GetComboNegara();
        
        $tahun=array();
        if(isset($_GET['id'])){
			$dataPegawai = $pend->GetDataDetail($id);
			$dataPend = $pend->GetListMutasiPendidikan($id);
            if(isset($_GET['dataId'])){
				$dataMutasi = $pend->GetDataMutasiById($id,$dataId);
				$result=$dataMutasi[0]; 
				if(!empty($result)){
                    $return['input']['id'] = $result['id'];
                    $return['input']['nip'] = $result['nip'];
                    $return['input']['jpendid'] = $result['jpendid'];
                    $return['input']['institusi'] = $result['institusi'];
                    $return['input']['jurusan'] = $result['jurusan'];
                    $return['input']['lulus'] = $result['lulus'];
                    $return['input']['tempat'] = $result['tempat'];
                    $return['input']['kepsek'] = $result['kepsek'];
                    $return['input']['negid'] = $result['negid'];
                    $return['input']['asdanid'] = $result['asdanid'];
                    $return['input']['lama'] = $result['lama'];
                    $return['input']['mulai'] = $result['mulai'];
                    $return['input']['selesai'] = $result['selesai'];
                    $return['input']['ket'] = $result['ket'];
                    $return['input']['golmaxid'] = $result['golmaxid'];
                    $return['input']['istamat'] = $result['istamat'];
					$return['input']['upload'] = $result['upload'];
			    }    
            }else{
				$return['input']['id'] ='';
				$return['input']['nip'] ='';
				$return['input']['jpendid'] = '';
				$return['input']['institusi'] ='';
				$return['input']['jurusan'] ='';
				$return['input']['lulus'] = '';
				$return['input']['tempat'] = '';
				$return['input']['kepsek'] = '';
				$return['input']['negid'] = '';
				$return['input']['asdanid'] = '';
				$return['input']['lama'] = '';
				$return['input']['mulai'] = date("Y-m-d");
				$return['input']['selesai'] = date("Y-m-d");
				$return['input']['ket'] = '';
				$return['input']['golmaxid'] ='';
				$return['input']['istamat'] = '';
            }
            
        }
         
        if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-50;
	    }
        $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	     
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
          
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenispend', array('jenispend', $arrjp, $return['input']['jpendid'], 'false', ' style="width:100px;" '), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'negara', array('negara', $arrneg, $return['input']['negid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pktgol', array('pktgol', $arrpg, $return['input']['golmaxid'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'asdan', array('asdan', $arras, $return['input']['asdanid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            
        $list_istamat=array(array('id'=>'Selesai','name'=>'Selesai'),array('id'=>'Masa Pendidikan','name'=>'Masa Pendidikan'));
	    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'istamat', array('istamat', $list_istamat, $return['input']['istamat'], '', 'id="istamat "  '), Messenger::CurrentRequest);
	    $list_lama=array(array('id'=>'1','name'=>'1'),array('id'=>'2','name'=>'2'),array('id'=>'3','name'=>'3'),array('id'=>'4','name'=>'4'),array('id'=>'5','name'=>'5'),array('id'=>'6','name'=>'6'),array('id'=>'7','name'=>'7'),array('id'=>'8','name'=>'8'),array('id'=>'9','name'=>'9'),array('id'=>'10','name'=>'10'));
	    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'lama', array('lama', $list_lama, $return['input']['lama'], '', 'id="lama "  '), Messenger::CurrentRequest);
		
		Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
        
        $lang=GTFWConfiguration::GetValue('application', 'button_lang');
        $return['lang']=$lang;
      
        $return['dataPegawai'] = $dataPegawai;
  		$return['dataPend'] = $dataPend;
  		   
  		return $return;  
    }
      
    function ParseTemplate($data = NULL){
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
      
		$dataPegawai = $data['dataPegawai'];
		$dataPend = $data['dataPend'];
      
		if($data['lang'] = "eng"){
			$this->mrTemplate->AddVar('content', 'TITLE', 'RIWAYAT PENDIDIKAN');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
			$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
			$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		} else {
			$this->mrTemplate->AddVar('content', 'TITLE', 'MUTASI PENDIDIKAN');
			$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
			$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
			$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		}
		if ( isset($_GET['dataId'])) {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'updateMutasiPendidikan', 'do', 'html'));
		}else{
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'addMutasiPendidikan', 'do', 'html'));
		}
      
		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'Pegawai', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'MutasiPendidikan', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
		$this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
		$this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
		$this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
		$this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
		$this->Data['foto']=$dataPegawai[0]['foto'];
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
			$this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
			$this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
		}
      
      
		if(!empty($data['input'])){
			$data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
			$this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
		}
      
		if (empty($dataPend)) {
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
			$label = "Education Mutation Data Management";
			$urlDelete = Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'deleteMutasiPendidikan', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'MutasiPendidikan', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

			$total=0;
			$start=1;
			for ($i=0; $i<count($dataPend); $i++) {
				$no = $i+$start;
				$dataPend[$i]['number'] = $no;
				if ($no % 2 != 0) {
					$dataPend[$i]['class_name'] = 'table-common-even';
				}else{
					$dataPend[$i]['class_name'] = '';
				}

				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
				$idEnc = Dispatcher::Instance()->Encrypt($dataPend[$i]['id']);
				$urlAccept = 'mutasi_pendidikan|deleteMutasiPendidikan|do|html-id-'.$dataPegawai[0]['id'];
				$urlKembali = 'mutasi_pendidikan|MutasiPendidikan|view|html-id-'.$dataPegawai[0]['id'];
				$label = 'Data Mutasi Pendidikan';
				$dataName = $dataPend[$i]['jpendlabel'].' '.$dataPend[$i]['institusi'];
				$dataPend[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
				$dataPend[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				$dataPend[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
				$dataPend[$i]['mulai'] = $this->date2string($dataPend[$i]['mulai']);
				$dataPend[$i]['selesai'] = $this->date2string($dataPend[$i]['selesai']);
				if (!empty($dataPend[$i]['upload'])){
					$dataPend[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataPend[$i]['upload'];
				}else{
					$dataPend[$i]['LINK_DOWNLOAD_SK'] = '';
					$dataPend[$i]['VIEW_DOWNLOAD'] = 'none';
				}
         
				$this->mrTemplate->AddVars('data_item', $dataPend[$i], 'PEND_');
				$this->mrTemplate->parseTemplate('data_item', 'a');	 
  			}
  		}
    }
      
    function date2string($date) {
		$bln = array(
	            1  => '01',
					2  => '02',
					3  => '03',
					4  => '04',
					5  => '05',
					6  => '06',
					7  => '07',
					8  => '08',
					9  => '09',
					10 => '10',
					11 => '11',
					12 => '12'					
	               );
	   $arrtgl = explode('-',$date);
	   return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0];
	   
	}
}
?>