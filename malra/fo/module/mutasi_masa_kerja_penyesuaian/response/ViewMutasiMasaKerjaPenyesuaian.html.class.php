<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_masa_kerja_penyesuaian/business/mutasi_masa_kerja_penyesuaian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
class ViewMutasiMasaKerjaPenyesuaian extends HtmlResponse
   {
      function TemplateModule()
      {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_masa_kerja_penyesuaian/'.GTFWConfiguration::GetValue('application', 'template_address').'');
         $this->SetTemplateFile('view_mutasi_masa_kerja_penyesuaian.html');
      }
      
      function ProcessRequest() 
      {
      $mkp = new MutasiMasaKerjaPenyesuaian();
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
		
      $ObjDatPeg = new DataPegawai();
      $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
	  
      $id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
      $dataId = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
      $this->profilId=$id;
      
      //$arrjp = $mkp->GetComboJabatanStruktural();
      
         $tahun=array();
         if(isset($_GET['id'])){
         $dataPegawai = $mkp->GetDataDetail($id);
         //$arrpktgol = $mkp->GetComboPangkatGolonganAll();
         $dataMkp = $mkp->GetListMutasiMasaKerjaPenyesuaian($id);
            if(isset($_GET['dataId'])){
               $dataMutasi = $mkp->GetDataMutasiById($id,$dataId);
               $result=$dataMutasi[0];
                  if(!empty($result)){
			            $return['input']['id'] = $result['id'];
                  $return['input']['nip'] = $result['nip'];
			            $return['input']['tahun'] = $result['tahun'];
			            $return['input']['bulan'] = $result['bulan'];
			            $return['input']['pejabat'] = $result['pejabat'];
			            $return['input']['nosk'] = $result['nosk'];
			            $return['input']['tgl_sk'] = $result['tgl_sk'];
			            $return['input']['upload'] = $result['upload'];
			            
			         }    
            }else{
            $return['input']['id'] = '';
            $return['input']['nip'] = '';
            $return['input']['tahun'] = '';
            $return['input']['bulan'] = '';
            $return['input']['pejabat'] = '';
            $return['input']['nosk'] = '';
            $return['input']['tgl_sk'] = date("Y-m-d");
            $return['input']['upload'] = '';
            //$arrpktgol = $mkp->GetComboPangkatGolongan($id);
            }
            
         }
         
         $lang=GTFWConfiguration::GetValue('application', 'button_lang');
        	if ($lang=='eng'){
  		      $bulan1 = "January";
  		      $bulan2 = "February";
  		      $bulan3 = "March";
  		      $bulan4 = "April";
  		      $bulan5 = "May";
  		      $bulan6 = "June";
  		      $bulan7 = "July";
  		      $bulan8 = "August";
  		      $bulan9 = "September";
  		      $bulan10 = "October";
  		      $bulan11 = "November";
  		      $bulan12 = "December";  		      
        	}else{
  		      $bulan1 = "Januari";
  		      $bulan2 = "Februari";
  		      $bulan3 = "Maret";
  		      $bulan4 = "April";
  		      $bulan5 = "Mei";
  		      $bulan6 = "Juni";
  		      $bulan7 = "Juli";
  		      $bulan8 = "Agustus";
  		      $bulan9 = "September";
  		      $bulan10 = "Oktober";
  		      $bulan11 = "November";
  		      $bulan12 = "Desember"; 
        	}
        	$return['lang']=$lang;
      	
      	 $list_bulan = array(
                          array('id'=>'01','name'=>$bulan1),
                          array('id'=>'02','name'=>$bulan2),
                          array('id'=>'03','name'=>$bulan3),
                          array('id'=>'04','name'=>$bulan4),
                          array('id'=>'05','name'=>$bulan5),
                          array('id'=>'06','name'=>$bulan6),
                          array('id'=>'07','name'=>$bulan7),
                          array('id'=>'08','name'=>$bulan8),
                          array('id'=>'09','name'=>$bulan9),
                          array('id'=>'10','name'=>$bulan10),
                          array('id'=>'11','name'=>$bulan11),
                          array('id'=>'12','name'=>$bulan12)                        
                       );
	       Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'bulan', array('bulan', $list_bulan, $return['input']['bulan'], '', 'id="bulan "  '), Messenger::CurrentRequest);


         
         if(empty($tahun['start'])){
	        $tahun['start']=date("Y")-25;
	        }
         $tahun['end'] = date("Y")+5;
         
      	//Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
      	//Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'selesai'), Messenger::CurrentRequest);
	      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tgl_sk',array($return['input']['tgl_sk'], $tahun['start'], $tahun['end'], '', '', 'tgl_sk'), Messenger::CurrentRequest);
         
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
         /*
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabs_ref', array('jabs_ref', $arrjp, $return['input']['struktural'], '', ' style="width:100px;" '), Messenger::CurrentRequest);
         Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('golongan_ref', $arrpktgol, $return['input']['pktgolid'], '', ' style="width:200px;" '), Messenger::CurrentRequest);
         $list_status=array(array('id'=>'Aktif','name'=>'Aktif'),array('id'=>'Tidak Aktif','name'=>'Tidak Aktif'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], '', 'id="status "  '), Messenger::CurrentRequest);
	      $list_eselon=array(array('id'=>'IA','name'=>'IA'),array('id'=>'IB','name'=>'IB'),array('id'=>'IIA','name'=>'IIA'),array('id'=>'IIB','name'=>'IIB'),array('id'=>'IIIA','name'=>'IIIA'),array('id'=>'IIIB','name'=>'IIIB'),array('id'=>'IVA','name'=>'IVA'),array('id'=>'IVB','name'=>'IVB'));
	      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'eselon', array('eselon', $list_eselon, $return['input']['eselon'], 'false', 'id="eselon "  '), Messenger::CurrentRequest);
         */
         $return['dataPegawai'] = $dataPegawai;
  		   $return['dataMkp'] = $dataMkp;
  		   
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
      $dataMkp = $data['dataMkp'];
      
      if ($data['lang']=='eng'){
        $this->mrTemplate->AddVar('content', 'TITLE', 'Employment Adjusment Period Mutation');
        $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
        $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Cancel ' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      } else {
        $this->mrTemplate->AddVar('content', 'TITLE', 'Mutasi Penyesuaian Masa Kerja');
        $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Ubah' : 'Tambah');
        $this->mrTemplate->AddVar('content', 'BUTTON', isset($_GET['dataId']) ? ' Batal ' : 'Reset');
        $this->mrTemplate->AddVar('content', 'TYPE', isset($_GET['dataId']) ? 'submit' : 'reset');
      }
      if ( isset($_GET['dataId'])) {
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'updateMutasiMasaKerjaPenyesuaian', 'do', 'html'));
      }else{
         $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'addMutasiMasaKerjaPenyesuaian', 'do', 'html'));
      }
      
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BALIK', Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'MutasiMasaKerjaPenyesuaian', 'view', 'html').'&id='.$dataPegawai[0]['id'] );
      
      $this->mrTemplate->AddVar('content', 'ID', $dataPegawai[0]['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $dataPegawai[0]['kode']);
      $this->mrTemplate->AddVar('content', 'NAMA', $dataPegawai[0]['name']);
      $this->mrTemplate->AddVar('content', 'ALAMAT', $dataPegawai[0]['alamat']);
     
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai[0]['foto']);
      }
      
      if(!empty($data['input'])){
         
         $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		   $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
      
      if (empty($dataMkp)) {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
  
  
      $label = "Manajemen Mutasi Masa Kerja Penyesuaian";
      $urlDelete = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'deleteMutasiMasaKerjaPenyesuaian', 'do', 'html');
      $urlReturn = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian', 'MutasiMasaKerjaPenyesuaian', 'view', 'html');
      Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
      $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

      $total=0;
      $start=1;
      for ($i=0; $i<count($dataMkp); $i++) {
         $no = $i+$start;
         $dataMkp[$i]['number'] = $no;
            if ($no % 2 != 0) {
            $dataMkp[$i]['class_name'] = 'table-common-even';
            }else{
            $dataMkp[$i]['class_name'] = '';
            }

      if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
      if($i == sizeof($dataMkp)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
      
      $idEnc = Dispatcher::Instance()->Encrypt($dataMkp[$i]['id']);
      $urlAccept = 'mutasi_masa_kerja_penyesuaian|deleteMutasiMasaKerjaPenyesuaian|do|html-id-'.$dataPegawai[0]['id'];
      $urlKembali = 'mutasi_masa_kerja_penyesuaian|MutasiMasaKerjaPenyesuaian|view|html-id-'.$dataPegawai[0]['id'];
      $label = 'Data Mutasi Masa Kerja Penyesuaian';
      $dataName = $dataMkp[$i]['jabstruk'];
        // $dataMkp[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
         //$dataMkp[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','MutasiMasaKerjaPenyesuaian', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         //$dataMkp[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('mutasi_masa_kerja_penyesuaian','detailMutasi', 'view', 'html').'&id='.$dataPegawai[0]['id'].'&dataId='. $idEnc;
         $dataMkp[$i]['tgl_sk'] = $this->date2string($dataMkp[$i]['tgl_sk']);
         if (!empty($dataMkp[$i]['upload'])){
         $dataMkp[$i]['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$dataMkp[$i]['upload'];
         }
         else{
         $dataMkp[$i]['LINK_DOWNLOAD_SK'] = '';
         }
      
      /*if ($data['lang']=='eng'){
  		  if($dataMkp[$i]['bulan'] == '01'){ $dataMkp[$i]['bulan'] = "January";}
  		  if($dataMkp[$i]['bulan'] == '02'){ $dataMkp[$i]['bulan'] = "February";}
  		  if($dataMkp[$i]['bulan'] == '03'){ $dataMkp[$i]['bulan'] = "March";}
  		  if($dataMkp[$i]['bulan'] == '04'){ $dataMkp[$i]['bulan'] = "April";}
  		  if($dataMkp[$i]['bulan'] == '05'){ $dataMkp[$i]['bulan'] = "May";}
  		  if($dataMkp[$i]['bulan'] == '06'){ $dataMkp[$i]['bulan'] = "June";}
  		  if($dataMkp[$i]['bulan'] == '07'){ $dataMkp[$i]['bulan'] = "July";}
  		  if($dataMkp[$i]['bulan'] == '08'){ $dataMkp[$i]['bulan'] = "August";}
  		  if($dataMkp[$i]['bulan'] == '09'){ $dataMkp[$i]['bulan'] = "September";}
  		  if($dataMkp[$i]['bulan'] == '10'){ $dataMkp[$i]['bulan'] = "October";}
  		  if($dataMkp[$i]['bulan'] == '11'){ $dataMkp[$i]['bulan'] = "November";}
  		  if($dataMkp[$i]['bulan'] == '12'){ $dataMkp[$i]['bulan'] = "December";} 		      
      } else {
  		  if($dataMkp[$i]['bulan'] == '01'){ $dataMkp[$i]['bulan'] = "Januari";}
  		  if($dataMkp[$i]['bulan'] == '02'){ $dataMkp[$i]['bulan'] = "Februari";}
  		  if($dataMkp[$i]['bulan'] == '03'){ $dataMkp[$i]['bulan'] = "Maret";}
  		  if($dataMkp[$i]['bulan'] == '04'){ $dataMkp[$i]['bulan'] = "April";}
  		  if($dataMkp[$i]['bulan'] == '05'){ $dataMkp[$i]['bulan'] = "Mei";}
  		  if($dataMkp[$i]['bulan'] == '06'){ $dataMkp[$i]['bulan'] = "Juni";}
  		  if($dataMkp[$i]['bulan'] == '07'){ $dataMkp[$i]['bulan'] = "Juli";}
  		  if($dataMkp[$i]['bulan'] == '08'){ $dataMkp[$i]['bulan'] = "Agustus";}
  		  if($dataMkp[$i]['bulan'] == '09'){ $dataMkp[$i]['bulan'] = "September";}
  		  if($dataMkp[$i]['bulan'] == '10'){ $dataMkp[$i]['bulan'] = "Oktober";}
  		  if($dataMkp[$i]['bulan'] == '11'){ $dataMkp[$i]['bulan'] = "November";}
  		  if($dataMkp[$i]['bulan'] == '12'){ $dataMkp[$i]['bulan'] = "Desember";} 
      }*/
         
      $this->mrTemplate->AddVars('data_item', $dataMkp[$i], 'MKP_');
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