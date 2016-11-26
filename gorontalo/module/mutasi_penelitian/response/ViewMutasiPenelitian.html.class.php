<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penelitian/business/mutasi_penelitian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewMutasiPenelitian extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_penelitian/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_penelitian.html');
      }
      
      function ProcessRequest() 
      {
      $pend = new MutasiPenelitian();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		  $this->Pesan = $msg[0][1];
		  $this->css = $msg[0][2];
      
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
      
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $tipe = Dispatcher::Instance()->Decrypt($_GET['tipe']->Raw());
      
      if (empty($tipe)) {$tipe=1; }
      
      $this->profilId=$id;
      
      $arrjenisbuku = $pend->GetComboJenisBuku();
      $arrjeniskarya = $pend->GetComboJenisKarya();
      $arrjenispenelitian = $pend->GetComboJenisPenelitian();
      $arrjenispublikasi = $pend->GetComboJenisPublikasi();
      $arrjeniskegiatan = $pend->GetComboJenisKegiatan();
      $arrasaldana = $pend->GetComboAsalDana();
      $arrperanan = $pend->GetComboPeranan();
        
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $pend->GetDataDetail($id);
         $dataPend = $pend->GetListMutasiPenelitian($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $pend->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0]; 
                  if(!empty($result) && ($tipe==1)){ 
                     $return['input']['jenis_buku_id'] = $result['pnltnJnsbukuId'];
                     $return['input']['judul_buku'] = $result['pnltnJudulBuku'];
                     $return['input']['jenis_kegiatan_id'] = $result['pnltnJnskegrId'];
                     $return['input']['peranan_id'] = $result['pnltnPpnltnrId'];
                     $return['input']['tahun'] = $result['pnltnTahun'];
                     $return['input']['penerbit'] = $result['pnltnPenerbit'];
                     $return['input']['keterangan'] = $result['pnltnKeterangan'];
					 $return['input']['upload'] = $result['upload'];
			           } else
                 if(!empty($result) && ($tipe==2)){
                     $return['input']['jenis_buku_id'] = $result['pnltnJnsbukuId'];
                     $return['input']['judul_artikel'] = $result['pnltnJudulArtikel'];
                     $return['input']['jenis_kegiatan_id'] = $result['pnltnJnskegrId'];
                     $return['input']['peranan_id'] = $result['pnltnPpnltnrId'];
                     $return['input']['tahun'] = $result['pnltnTahun'];
                     $return['input']['keterangan'] = $result['pnltnKeterangan'];
					 $return['input']['upload'] = $result['upload'];
			           } else
                 if(!empty($result) && ($tipe==3)){
                     $return['input']['jenis_karya_id'] = $result['pnltnJnskryrId'];
                     $return['input']['jenis_penelitian_id'] = $result['pnltnJnspenelitianId'];
                     $return['input']['judul_penelitian'] = $result['pnltnJudulKaryaIlmiah'];
                     $return['input']['peranan_id'] = $result['pnltnPpnltnrId'];
                     $return['input']['asal_dana_id'] = $result['pnltnAsldnrId'];
                     $return['input']['tahun'] = $result['pnltnTahun'];
                     $return['input']['keterangan'] = $result['pnltnKeterangan'];
					 $return['input']['upload'] = $result['upload'];
			           } else
                 if(!empty($result) && ($tipe==4)){
                     $return['input']['jenis_publikasi_id'] = $result['pnltnJnspublikasiId'];
                     $return['input']['judul_publikasi'] = $result['pnltnJudulPublikasi'];
                     $return['input']['peranan_id'] = $result['pnltnPpnltnrId'];
                     $return['input']['tahun'] = $result['pnltnTahun'];
                     $return['input']['keterangan'] = $result['pnltnKeterangan'];
					 $return['input']['upload'] = $result['upload'];
			           } 
				 $return['input']['upload'] = $result['upload'];
            }
            
         }
         
         if ($_GET['aksi']=='ya'){
          $return['display_list']='none';
         }else{
          $return['display_form']='none';
         }
         
         #if ($tipe==1){
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_buku_ref', array('jenis_buku_ref', $arrjenisbuku, $return['input']['jenis_buku_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_kegiatan_ref', array('jenis_kegiatan_ref', $arrjeniskegiatan, $return['input']['jenis_kegiatan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'peranan_ref', array('peranan_ref', $arrperanan, $return['input']['peranan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         #} else
         #if ($tipe==2){
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_buku_ref', array('jenis_buku_ref', $arrjenisbuku, $return['input']['jenis_buku_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_kegiatan_ref', array('jenis_kegiatan_ref', $arrjeniskegiatan, $return['input']['jenis_kegiatan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'peranan_ref', array('peranan_ref', $arrperanan, $return['input']['peranan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         #} else
         #if ($tipe==3){
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_karya_ref', array('jenis_karya_ref', $arrjeniskarya, $return['input']['jenis_karya_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_penelitian_ref', array('jenis_penelitian_ref', $arrjenispenelitian, $return['input']['jenis_penelitian_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'asal_dana_ref', array('asal_dana_ref', $arrasaldana, $return['input']['asal_dana_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'peranan_ref', array('peranan_ref', $arrperanan, $return['input']['peranan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         #} else
         #if ($tipe==4){
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_publikasi_ref', array('jenis_publikasi_ref', $arrjenispublikasi, $return['input']['jenis_publikasi_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
            Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'peranan_ref', array('peranan_ref', $arrperanan, $return['input']['peranan_id'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         #}
	     
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataPend'] = $dataPend;
  		   $return['tipe']= $tipe;
  		   $return['pegId']= $_GET['id'];
  		   $return['dataId']=$_GET['dataId'];
  		   
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
      $dataPend = $data['dataPend'];
      $tipe=$data['tipe'];
      //print_r($dataPegawai);
      //print_r($dataPend);
      $this->mrTemplate->AddVar('content', 'TITLE', 'PENELITIAN');
      $this->mrTemplate->AddVar('content', 'PEG_ID', $data['pegId']);
      $this->mrTemplate->AddVar('content', 'DATA_ID', $data['dataId']);
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
      $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? 'Batal' : 'Reset');
      $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      $this->mrTemplate->AddVar('content', 'URL_ITSELF', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'mutasiPenelitian', 'view', 'html'));
      
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'updateMutasiPenelitian', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'addMutasiPenelitian', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'URL_TAMBAH', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html').'&aksi=ya');
      $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'DISPLAY_LIST', $data['display_list']);
      $this->mrTemplate->AddVar('content', 'DISPLAY_FORM', $data['display_form']);
      
      if ($tipe==1){
         $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED1", "selected");
         $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'buku');
         if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_buku', $data['input'], '');
	       }
      } else
      if ($tipe==2){
         $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED2", "selected");
         $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'artikel');
         if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_artikel', $data['input'], '');
	       }
      } else
      if ($tipe==3){
        $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED3", "selected");
        $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'penelitian');
        if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_penelitian', $data['input'], '');
	       }
      } else
      if ($tipe==4){
        $this->mrTemplate->AddVar("tipe_penelitian", "SELECTED4", "selected");
        $this->mrTemplate->AddVar('data_tipe_penelitian', 'data_status', 'publikasi');
        if(!empty($data['input'])){
		        $this->mrTemplate->AddVars('data_publikasi', $data['input'], '');
	       }
      }
	  if(!empty($data['input'])){
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataPend)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Data Mutasi Penelitian";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'deleteMutasiPenelitian', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_penelitian', 'MutasiPenelitian', 'view', 'html');
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
		 $dataPend[$i]['jkaryalabel'] .= ' - '.$dataPend[$i]['jenis'];
         $urlAccept = 'mutasi_penelitian|deleteMutasiPenelitian|do|html-id-'.$dataPegawai[0]['id'];
         $urlKembali = 'mutasi_penelitian|MutasiPenelitian|view|html-id-'.$dataPegawai[0]['id'];
         $label = 'Data Mutasi Penelitian';
         $dataName = $dataPend[$i]['institusi'];
         $dataPend[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         $dataPend[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_penelitian','MutasiPenelitian', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&tipe='. $tipeId.'&dataId='. $idEnc.'&aksi=ya';
         $dataPend[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_penelitian','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&tipe='. $tipeId;
         if (!empty($dataPend[$i]['upload'])){
         $dataPend[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataPend[$i]['upload'];
         }
         else{
         $dataPend[$i]['LINK_DOWNLOAD_SK'] = '';
         }
         
      $this->mrTemplate->AddVars('data_item', $dataPend[$i], 'PEN_');
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