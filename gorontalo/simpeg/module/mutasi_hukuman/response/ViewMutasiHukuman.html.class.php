<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_hukuman/business/mutasi_hukuman.class.php';

class ViewMutasiHukuman extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_hukuman/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_hukuman.html');
      }
      
      function ProcessRequest() 
      {
      $hkm = new MutasiHukuman();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
   
      $arrjh = $hkm->GetComboJenisHukuman();      
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $hkm->GetDataDetail($id);
         $dataHkm = $hkm->GetListMutasiHukuman($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $hkm->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['kat'] = $result['kat'];
                     $return['input']['jenis'] = $result['jenis'];
                     $return['input']['namahkm'] = $result['namahkm'];
                     $return['input']['mulai'] = $result['mulai'];
                     $return['input']['selesai'] = $result['selesai'];
                     $return['input']['ket'] = $result['ket'];
					 $return['input']['upload'] = $result['upload'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['kat'] = '';
            $return['input']['jenis'] = '';
            $return['input']['namahkm'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['selesai'] = date("Y-m-d");
            $return['input']['ket'] = '';
			$return['input']['upload'] = '';
            }
            
         }
         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	     
         
        $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
        //set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		if ($lang=='eng'){
			$stat_ringan="Easy";$stat_sedang="Moderate";$stat_berat="Heavy";
		}else{
			$stat_ringan="Ringan";$stat_sedang="Sedang";$stat_berat="Berat";
		}
		$return['lang']=$lang;
	  
        Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $arrjh, $return['input']['jenis'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
        $list_kat=array(array('id'=>'Ringan','name'=>$stat_ringan),array('id'=>'Sedang','name'=>$stat_sedang),array('id'=>'Berat','name'=>$stat_berat));
	    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'kat', array('kat', $list_kat, $return['input']['kat'], 'false', 'id="kat "  '), Messenger::CurrentRequest);
		Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
         
        $return['dataPegawai'] = $dataPegawai;
  		$return['dataHkm'] = $dataHkm;
  		   
  		return $return;  
      }
      
      function ParseTemplate($data = NULL)
      {
       if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $dataPegawai = $data['dataPegawai'];
      $dataHkm = $data['dataHkm'];
      
	  if($data['lang']=='eng') {
		$this->mrTemplate->AddVar('content', 'TITLE', 'DISCIPLINARY MUTATION');
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		$label = "Management of Disciplinary Mutation";
		$label_data = "Disciplinary Mutation Data";
      }else {
		$this->mrTemplate->AddVar('content', 'TITLE', 'RIWAYAT HUKUMAN');
		$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
		$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
		$this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
		$label = "Manajemen Data Mutasi Hukuman";
		$label_data = "Data Mutasi Hukuman";
	  }
	  
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'updateMutasiHukuman', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'addMutasiHukuman', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'MutasiHukuman', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
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
      
      if (empty($dataHkm)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'deleteMutasiHukuman', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_hukuman', 'MutasiHukuman', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataHkm); $i++) {
         $no = $i+$start;
         $dataHkm[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataHkm[$i]['class_name'] = 'table-common-even';
            }else{
            $dataHkm[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataHkm[$i]['id']);
      $urlAccept = 'mutasi_hukuman|deleteMutasiHukuman|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_hukuman|MutasiHukuman|view|html-id-'.$dataPegawai[0]['id'];
      
      $dataName = $dataHkm[$i]['namahkm'];
         $dataHkm[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label_data.'&dataName='.$dataName;
         $dataHkm[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_hukuman','MutasiHukuman', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataHkm[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_hukuman','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataHkm[$i]['mulai'] = $this->date2string($dataHkm[$i]['mulai']);
         $dataHkm[$i]['selesai'] = $this->date2string($dataHkm[$i]['selesai']);
         if($data['lang']=='eng') {
            if($dataHkm[$i]['kat'] == 'Berat'){ $dataHkm[$i]['kat'] = "Heavy";}
            if($dataHkm[$i]['kat'] == 'Sedang'){ $dataHkm[$i]['kat'] = "Moderate";}
            if($dataHkm[$i]['kat'] == 'Ringan'){ $dataHkm[$i]['kat'] = "Easy";}
         }
         
         if (!empty($dataHkm[$i]['upload'])){
			$dataHkm[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataHkm[$i]['upload'];
         }else{
			$dataHkm[$i]['LINK_DOWNLOAD_SK'] = '';
			$dataHkm[$i]['VIEW_DOWNLOAD'] = 'none';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataHkm[$i], 'HKM_');
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
