<?php 
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_kunjungan/business/mutasi_kunjungan.class.php';

class ViewMutasiKunjungan extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_kunjungan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_kunjungan.html');
      }
      
      function ProcessRequest() 
      {
      $kunj = new MutasiKunjungan();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
      $arrjp = $kunj->GetComboJenisKunjungan();
      $arras = $kunj->GetComboAsalDana();
      $arrneg = $kunj->GetComboNegara();
        
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $kunj->GetDataDetail($id);
         $dataKunj = $kunj->GetListMutasiKunjungan($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $kunj->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0]; 
                  if(!empty($result)){
                     $return['input']['id'] = $result['id'];
                     $return['input']['nip'] = $result['nip'];
                     $return['input']['jkid'] = $result['jkid'];
                     $return['input']['tujuan'] = $result['tujuan'];
                     $return['input']['negid'] = $result['negid'];
                     $return['input']['mulai'] = $result['mulai'];
                     $return['input']['selesai'] = $result['selesai'];
                     $return['input']['asdanid'] = $result['asdanid'];
                     $return['input']['ket'] = $result['ket'];
					 $return['input']['upload'] = $result['upload'];
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['jkid'] = '';
            $return['input']['tujuan'] = '';
            $return['input']['negid'] = '';
            $return['input']['mulai'] = date("Y-m-d");
            $return['input']['selesai'] = date("Y-m-d");
            $return['input']['asdanid'] = '';
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
	     
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
          
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jeniskunj', array('jeniskunj', $arrjp, $return['input']['jkid'], 'false', ' style="width:100px;" '), Messenger::CurrentRequest);
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'negara', array('negara', $arrneg, $return['input']['negid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'asdan', array('asdan', $arras, $return['input']['asdanid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
		 Messenger::Instance()->SendToComponent('profile', 'profilebox', 'view', 'html', 'profile', array($id ), Messenger::CurrentRequest);
         /*   
         $list_istamat=array(array('id'=>'Selesai','name'=>'Selesai'),array('id'=>'Masa Pendidikan','name'=>'Masa Pendidikan'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'istamat', array('istamat', $list_istamat, $return['input']['istamat'], '', 'id="istamat "  '), Messenger::CurrentRequest);
	      $list_istamat=array(array('id'=>'1','name'=>'1'),array('id'=>'2','name'=>'2'),array('id'=>'3','name'=>'3'),array('id'=>'4','name'=>'4'),array('id'=>'5','name'=>'5'),array('id'=>'6','name'=>'6'),array('id'=>'7','name'=>'7'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'lama', array('lama', $list_istamat, $return['input']['lama'], '', 'id="lama "  '), Messenger::CurrentRequest);
         */
         
         //set the language
      	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      	  $return['lang']=$lang;
      	  
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataKunj'] = $dataKunj;
  		   
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
      $dataKunj = $data['dataKunj'];
      //print_r($dataPegawai);
      //print_r($dataKunj);
      
      if($data['lang']=='eng') {
  		  $this->mrTemplate->AddVar('content', 'TITLE', 'OVERSEAS VISITS MUTATION');
  	  	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Edit' : 'Add');
  	  	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Cancel' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
  	  } else {
  		  $this->mrTemplate->AddVar('content', 'TITLE', 'Mutasi Kunjungan ke Luar Negeri');
  	  	$this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
  	  	$this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
  	  }

      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'updateMutasiKunjungan', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'addMutasiKunjungan', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'MutasiKunjungan', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
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
      
      if (empty($dataKunj)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
      if($data['lang']=='eng') {
        $label = "Overseas Visits Mutation Data Management";
      } else {
        $label = "Manajemen Data Mutasi Kunjungan ke Luar Negeri";
      }
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'deleteMutasiKunjungan', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_kunjungan', 'MutasiKunjungan', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataKunj); $i++) {
         $no = $i+$start;
         $dataKunj[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataKunj[$i]['class_name'] = 'table-common-even';
            }else{
            $dataKunj[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataIstri)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataKunj[$i]['id']);
      if($data['lang']=='eng') {
        $label = 'Overseas Visits Mutation Data';
      } else {
        $label = 'Data Mutasi Kunjungan ke Luar Negeri';
      }
      
		 $idEnc = Dispatcher::Instance()->Encrypt($dataKunj[$i]['id']);
		 $urlAccept = 'mutasi_kunjungan|deleteMutasiKunjungan|do|html-id-'.$dataPegawai[0]['id'];
		 $urlKembali = 'mutasi_kunjungan|MutasiKunjungan|view|html-id-'.$dataPegawai[0]['id'];
				
         $dataName = $dataKunj[$i]['tujuan'];
         $dataKunj[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataKunj[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_kunjungan','MutasiKunjungan', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataKunj[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_kunjungan','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataKunj[$i]['mulai'] = $this->date2string($dataKunj[$i]['mulai']);
         $dataKunj[$i]['selesai'] = $this->date2string($dataKunj[$i]['selesai']);
         if (!empty($dataKunj[$i]['upload'])){
         $dataKunj[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataKunj[$i]['upload'];
         }
         else{
         $dataKunj[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataKunj[$i], 'KUNJ_');
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