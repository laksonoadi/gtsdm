<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai_calon/business/data_pegawai.class.php';

class ViewInputDataPegawai extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/data_pegawai_calon/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('input_data_pegawai.html');
   }
   
   function PrepareData(){
      //print_r($_GET['smpn']);
      //get data dari detail data atau dari form
      if ($_GET['dataId'] != '') {
         $pegId = $_GET['dataId']->Integer()->Raw();
         $ObjDatPeg = new DataPegawai();
         $rs = $ObjDatPeg->GetDatPegDetail($pegId);//print_r($rs);
         $gol = $ObjDatPeg->GetPegGol($pegId);
         $jabstruk = $ObjDatPeg->GetPegStruk($pegId);
         $jabfung = $ObjDatPeg->GetPegFung($pegId);
         $satker = $ObjDatPeg->GetPegKer($pegId);
         $rs2 = $ObjDatPeg->GetPegNikah($pegId);
         
         //print_r($gol);
         //print_r($indukId);
         
         $this->Data = array('id'=>$rs['pegId'],'nip'=>$rs['pegKodeResmi'],'kodegateaccess'=>$rs['pegKodeGateAccess'],'kodeint'=>$rs['pegKodeInternal'],'kodelain'=>$rs['pegKodeLain'],
         'nama'=>$rs['pegNama'],'alamat'=>$rs['pegAlamat'],'kodepos'=>$rs['pegKodePos'],'telp'=>$rs['pegNoTelp'],
         'hp'=>$rs['pegNoHp'],'email'=>$rs['pegEmail'],'satwil'=>$rs['pegSatwilId'],'tmplahir'=>$rs['pegTmpLahir'],
         'tgllahir'=>$rs['pegTglLahir'],'jenkel'=>$rs['pegKelamin'],'jnsidlain'=>$rs['pegJenisIdLain'],'idlain'=>$rs['pegIdLain'],
         'agama'=>$rs['pegAgamaId'],'nikah'=>$rs['pegStatnikahId'],'goldar'=>$rs['pegGoldrhId'],'tinggibdn'=>$rs['pegTinggiBadan'],
         'beratbdn'=>$rs['pegBeratBadan'],'cacat'=>$rs['pegCacat'],'hobi'=>$rs['pegHobby'],'jnspegid'=>$rs['pegJnspegrId'],
         'pnstmt'=>$rs['pegPnsTmt'],'tglmasuk'=>$rs['pegTglMasukInstitusi'],'notaspen'=>$rs['pegNoTaspen'],'noaskes'=>$rs['pegNoAskes'],'statusnpwp'=>$rs['pegStatusNpwp'],
         'nonpwp'=>$rs['pegNoNpwp'],'usiapens'=>$rs['pegUsiaPensiun'],'statr'=>$rs['pegStatrId'],'geldep'=>$rs['pegGelarDepan'],'gelbel'=>$rs['pegGelarBelakang'],
         'foto'=>$rs['pegFoto'],'kodeabsen'=>$rs['pegKodeAbsen'],'rekening'=>$rs['rekening'],'bank'=>$rs['bank'],'bank_lab'=>$rs['bank_label'],'resipien'=>$rs['resipien'],'pktgol'=>$gol['nama'],'pktgolId'=>$gol['id'],
         'jabstruk'=>$jabstruk['nama'],'jabfung'=>$jabfung['nama'],'idSatker'=>$satker['id'],'satker'=>$satker['nama'],'kodenik'=>$rs2['id'],
         'katpeg'=>$rs2['katpeg'],'tippeg'=>$rs2['tippeg'],'dirspv'=>$rs2['dirspv'],'mor'=>$rs2['mor'],'pegLevelId'=>$rs['pegLevelId'],'pegStatusWargaNeg'=>$rs['pegStatusWargaNeg']);   
      } else {
         $this->Op = $post['op'];
         //print_r($this->Op);
         $this->Data = array('id'=>$post['pegId'],'nip'=>$post['pegKodeResmi'],'kodegateaccess'=>$rs['pegKodeGateAccess'],'kodeint'=>$post['pegKodeInternal'],'kodelain'=>$post['pegKodeLain'],
         'nama'=>$post['pegNama'],'alamat'=>$post['pegAlamat'],'kodepos'=>$post['pegKodePos'],'telp'=>$post['pegNoTelp'],
         'hp'=>$post['pegNoHp'],'email'=>$post['pegEmail'],'satwil'=>$post['pegSatwilId'],'tmplahir'=>$post['pegTmpLahir'],
         'tgllahir'=>$post['pegTglLahir'],'jenkel'=>$post['pegKelamin'],'jnsidlain'=>$post['pegJenisIdLain'],'idlain'=>$post['pegIdLain'],
         'agama'=>$post['pegAgamaId'],'nikah'=>$post['pegStatnikahId'],'goldar'=>$post['pegGoldrhId'],'tinggibdn'=>$post['pegTinggiBadan'],
         'beratbdn'=>$post['pegBeratBadan'],'cacat'=>$post['pegCacat'],'hobi'=>$post['pegHobby'],'jnspegid'=>$post['pegJnspegrId'],
         'pnstmt'=>date("Y-m-d"),'tglmasuk'=>date("Y-m-d"),'notaspen'=>$post['pegNoTaspen'],'noaskes'=>$post['pegNoAskes'],'statusnpwp'=>$rs['pegStatusNpwp'],
         'nonpwp'=>$post['pegNoNpwp'],'usiapens'=>$post['pegUsiaPensiun'],'statr'=>$post['pegStatrId'],'geldep'=>$post['pegGelarDepan'],
         'foto'=>$post['pegFoto'],'kodeabsen'=>$post['pegKodeAbsen'],'rekening'=>$post['rekening'],'bank'=>$post['bank'],'resipien'=>$post['resipien'],'pktgol'=>$post['nama'],'jabstruk'=>$post['nama'],
         'jabfung'=>$post['nama'],'idSatker'=>$post['id'],'satker'=>$post['nama'],'kodenik'=>$post['nama'],
         'katpeg'=>$post['katpeg'],'tippeg'=>$post['tippeg'],'dirspv'=>$post['dirspv'],'mor'=>$post['mor'],'pegLevelId'=>$post['pegLevelId'],'pegStatusWargaNeg'=>$post['pegStatusWargaNeg']);   
      }
    
       $msg = Messenger::Instance()->Receive(__FILE__);
       $post = $msg[0][0];
       $this->Pesan = $msg[0][1];
   }
   
   function ProcessRequest() {
      $this->PrepareData();
      $ObjDatPeg = new DataPegawai();
      
      //print_r($x);exit;
      
      $listAgama = $ObjDatPeg->GetComboAgama();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegAgamaId', 
         array('pegAgamaId',$listAgama,$this->Data['agama'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $listNikah = $ObjDatPeg->GetComboNikah();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegStatnikahId', 
         array('pegStatnikahId',$listNikah,$this->Data['nikah'],'false',' style="width:115px;"'), Messenger::CurrentRequest);
      $listGoldar = $ObjDatPeg->GetComboGoldar();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegGoldrhId', 
         array('pegGoldrhId',$listGoldar,$this->Data['goldar'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
      $listSatwil = $ObjDatPeg->GetComboSatwil();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegSatwilId', 
         array('pegSatwilId',$listSatwil,$this->Data['satwil'],'false',' style="width:130px;"'), Messenger::CurrentRequest);
      $listJenisPeg = $ObjDatPeg->GetComboJenisPeg();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegJnspegrId', 
         array('pegJnspegrId',$listJenisPeg,$this->Data['jnspegid'],'false',' style="width:130px;"'), Messenger::CurrentRequest);
      $listStatPeg = $ObjDatPeg->GetComboStatPeg();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegStatrId', 
         array('pegStatrId',$listStatPeg,$this->Data['statr'],'false',' style="width:130px;"'), Messenger::CurrentRequest);
      $listLevel = $ObjDatPeg->GetComboLevel();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegLevelId', 
         array('pegLevelId',$listLevel,$this->Data['pegLevelId'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
      /*$listPnsCpns = $ObjDatPeg->GetComboPnsCpns();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegPnsCpnsTkpddkrId', 
         array('pegPnsCpnsTkpddkrId',$listPnsCpns,$this->Data['pnscpns'],'false',' style="width:100px;"'), Messenger::CurrentRequest);
      */
      $listkatpeg[0]['id']="Academic";
      $listkatpeg[0]['name']="Academic";
      $listkatpeg[1]['id']="Non-Academic";
      $listkatpeg[1]['name']="Non-Academic";
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegKatPeg', 
         array('pegKatPeg',$listkatpeg,$this->Data['katpeg'],'',' style="width:130px;"'), Messenger::CurrentRequest);
      $listtippeg[0]['id']="Fulltimer";
      $listtippeg[0]['name']="Fulltimer";
      $listtippeg[1]['id']="Partimer";
      $listtippeg[1]['name']="Partimer";
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegTipPeg', 
         array('pegTipPeg',$listtippeg,$this->Data['tippeg'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      if ($lang=='eng'){
        $ll="Male";
        $pp="Female";
      }else{
        $ll="Laki-Laki";  
        $pp="Perempuan";
      }
      $kelamin = array(0=>array('id'=>L,'name'=>$ll),1=>array('id'=>P,'name'=>$pp));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegJenkel', 
         array('pegJenkel',$kelamin,$this->Data['jenkel'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      
      $listPktGol = $ObjDatPeg->GetComboGol();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pktgol', 
         array('pktgol',$listPktGol,'','false',' style="width:180px;"'), Messenger::CurrentRequest);
      $listJabStruk = $ObjDatPeg->GetComboStruk();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabstruk', 
         array('jabstruk',$listJabStruk,'','false',' style="width:290px;"'), Messenger::CurrentRequest);
      $listJabFung = $ObjDatPeg->GetComboFung();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabfung', 
         array('jabfung',$listJabFung,'','false',' style="width:190px;"'), Messenger::CurrentRequest);
      $listSatKer = $ObjDatPeg->GetComboKer();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satker', 
         array('satker',$listSatKer,'','false',' style="width:250px;" onchange="setDS()"'), Messenger::CurrentRequest);
      $listKodeNik = $ObjDatPeg->GetComboKodeNikah();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'kodenik', 
         array('kodenik',$listKodeNik,$this->Data['kodenik'],'false',' style="width:190px;"'), Messenger::CurrentRequest);
      
      if ($lang=='eng'){
        $lokal="Local";
        $asing="Expatriate";
      }else{
        $lokal="Lokal";  
        $asing="Asing";
      }
      $warganegara = array(0=>array('id'=>Lokal,'name'=>$lokal),1=>array('id'=>Asing,'name'=>$asing));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegStatusWargaNeg', 
         array('pegStatusWargaNeg',$warganegara,$this->Data['pegStatusWargaNeg'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      if ($lang=='eng'){
        $ya="Yes";
        $tidak="No";
      }else{
        $ya="Ya";  
        $tidak="Tidak";
      }
      $statusnpwp = array(0=>array('id'=>Ya,'name'=>$ya),1=>array('id'=>Tidak,'name'=>$tidak));
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'statusnpwp', 
         array('statusnpwp',$statusnpwp,$this->Data['statusnpwp'],'',' style="width:100px;"'), Messenger::CurrentRequest);
         
      $dataDir = $ObjDatPeg->GetDataAtas($this->Data['dirspv']);
      $dataMor = $ObjDatPeg->GetDataAtas($this->Data['mor']);
      $return['atasan']['namaDir'] = $dataDir['nama'];
      $return['atasan']['satDir'] = $dataDir['namaSat'];
      $return['atasan']['namaMor'] = $dataMor['nama'];
      $return['atasan']['satMor'] = $dataMor['namaSat'];
      
      $akhirT=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegTglLahir', 
         array($this->Data['tgllahir'],'1950',$akhirT,'',''), Messenger::CurrentRequest);
         
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegTglMasuk', 
         array($this->Data['tglmasuk'],'1990',$akhirT,'',''), Messenger::CurrentRequest);
         
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegPnsTmt', 
         array($this->Data['pnstmt'],'1980',$akhirT,'',''), Messenger::CurrentRequest);
      
      //$return = $this->Data;
      
      return $return;
   }
   
   function ParseTemplate($data = NULL) {
      if (isset ($this->Pesan)) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYEE DATA');
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA CALON PEGAWAI');  
       }
      
      if (isset($_GET['dataId'])){ 
         $id=$_GET['dataId'];
	       $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_pegawai_calon', 'inputDataPegawai', 'do', 'html').'&dataId='.$id);
         $this->mrTemplate->AddVar('content', 'OPERASI', 'edit');
         if ($buttonlang=='eng'){
            $tambah="Update";
         }else{
            $tambah="Ubah";  
         }   
	    }else{
         $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_pegawai_calon', 'inputDataPegawai', 'do', 'html'));
         $this->mrTemplate->AddVar('content', 'OPERASI', 'add');
         if ($buttonlang=='eng'){
            $tambah="Add";
         }else{
            $tambah="Tambah";  
         } 
      }
      
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVar('content', 'ID', $this->Data['id']);
      $this->mrTemplate->AddVar('content', 'NIP', $this->Data['nip']);
      $this->mrTemplate->AddVar('content', 'KODEGATEACCESS', $this->Data['kodegateaccess']);
      $this->mrTemplate->AddVar('content', 'KODEINT', $this->Data['kodeint']);
      $this->mrTemplate->AddVar('content', 'KODELAIN', $this->Data['kodelain']);
      $this->mrTemplate->AddVar('content', 'NAMA', $this->Data['nama']);
      $this->mrTemplate->AddVar('content', 'GELDEP', $this->Data['geldep']);
      $this->mrTemplate->AddVar('content', 'GELBEL', $this->Data['gelbel']);
      $this->mrTemplate->AddVar('content', 'TMPLAHIR', $this->Data['tmplahir']);
      $this->mrTemplate->AddVar('content', 'TGLLAHIR', $this->Data['tgllahir']);
      $this->mrTemplate->AddVar('content', 'JNSIDLAIN', $this->Data['jnsidlain']);
      $this->mrTemplate->AddVar('content', 'IDLAIN', $this->Data['idlain']);
      $this->mrTemplate->AddVar('content', 'JENKEL', $this->Data['jenkel']);
      $this->mrTemplate->AddVar('content', 'KEPER', $this->Data['keper']);
      $this->mrTemplate->AddVar('content', 'PEGSTATUSWARGANEG', $this->Data['pegStatusWargaNeg']);
      
      $this->mrTemplate->AddVar('content', 'ALAMAT', $this->Data['alamat']);
      $this->mrTemplate->AddVar('content', 'KODEPOS', $this->Data['kodepos']);
      $this->mrTemplate->AddVar('content', 'TELP', $this->Data['telp']);
      $this->mrTemplate->AddVar('content', 'HP', $this->Data['hp']);
      $this->mrTemplate->AddVar('content', 'EMAIL', $this->Data['email']);
      
      $this->mrTemplate->AddVar('content', 'TINGGIBDN', $this->Data['tinggibdn']);
      $this->mrTemplate->AddVar('content', 'BERATBDN', $this->Data['beratbdn']);
      $this->mrTemplate->AddVar('content', 'WARNARMBT', $this->Data['warnarmbt']);
      $this->mrTemplate->AddVar('content', 'WARNAKUL', $this->Data['warnakul']);
      $this->mrTemplate->AddVar('content', 'BENTUKMUKA', $this->Data['bentukmuka']);
      $this->mrTemplate->AddVar('content', 'CIRIKHAS', $this->Data['cirikhas']);
      $this->mrTemplate->AddVar('content', 'CACAT', $this->Data['cacat']);
      $this->mrTemplate->AddVar('content', 'HOBI', $this->Data['hobi']);
      
      $this->mrTemplate->AddVar('content', 'TGLMASUK', $this->Data['tglmasuk']);
      $this->mrTemplate->AddVar('content', 'PNSTMT', $this->Data['pnstmt']);
      $this->mrTemplate->AddVar('content', 'NOTASPEN', $this->Data['notaspen']);
      $this->mrTemplate->AddVar('content', 'NOASKES', $this->Data['noaskes']);
      $this->mrTemplate->AddVar('content', 'NONPWP', $this->Data['nonpwp']);
      $this->mrTemplate->AddVar('content', 'USIAPENS', $this->Data['usiapens']);
      $this->mrTemplate->AddVar('content', 'KODEABSEN', $this->Data['kodeabsen']);
      $this->mrTemplate->AddVar('content', 'REKENING', $this->Data['rekening']);
      $this->mrTemplate->AddVar('content', 'BANK', $this->Data['bank']);
      $this->mrTemplate->AddVar('content', 'BANK_LABEL', $this->Data['bank_lab']);
      $this->mrTemplate->AddVar('content', 'URL_POPUP_BANK', Dispatcher::Instance()->GetUrl('data_pegawai_calon', 'popupBank', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'RESIPIEN', $this->Data['resipien']);
    
	  $atasan=$data['atasan'];
	  $this->mrTemplate->AddVar('content', '3_IDP', $this->Data['dirspv']);
      $this->mrTemplate->AddVar('content', '3_NMP', $atasan['namaDir']);
      $this->mrTemplate->AddVar('content', '3_NMS', $atasan['satDir']);
      $this->mrTemplate->AddVar('content', '2_IDP', $this->Data['mor']);
      $this->mrTemplate->AddVar('content', '2_NMP', $atasan['namaMor']);
      $this->mrTemplate->AddVar('content', '2_NMS', $atasan['satMor']);
      
	  $this->mrTemplate->AddVar('content', 'FOTO', $this->Data['foto']);
      $this->mrTemplate->AddVar('content', 'FOTO2', $this->Data['foto']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$this->Data['foto']) | empty($this->Data['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  }
	  
	  $id1 = Dispatcher::Instance()->Encrypt("A");
      $id2 = Dispatcher::Instance()->Encrypt("B");
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_1', Dispatcher::Instance()->GetUrl('data_pegawai_calon', 'popupPegawai', 'view', 'html').'&dataPeg='.$id1.'&dataSatker='); 
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_2', Dispatcher::Instance()->GetUrl('data_pegawai_calon', 'popupPegawai', 'view', 'html').'&dataPeg='.$id2.'&dataSatker='); 
      
      if (empty($this->Data['pktgol'])) {
  			$this->mrTemplate->AddVar('pangkat_golongan', 'DATA1_EMPTY', 'YES');
  		} else {
  		  $this->mrTemplate->AddVar('pangkat_golongan', 'DATA1_EMPTY', 'NO');
  		  $this->mrTemplate->AddVar('pangkat_golongan_item', 'NAMA', $this->Data['pktgolId'].' - '.$this->Data['pktgol']);
  		  $this->mrTemplate->AddVar('pangkat_golongan_item', 'ID_GOL', $this->Data['pktgolId']);
  		}
  		if (empty($this->Data['jabstruk'])) {
  			$this->mrTemplate->AddVar('jabatan_1', 'DATA2_EMPTY', 'YES');
  			//$this->mrTemplate->AddVar('jabatan_1_item', 'ID_GOL', $this->Data['pktgolId']);
  		} else {
  		  $this->mrTemplate->AddVar('jabatan_1', 'DATA2_EMPTY', 'NO');
  		  $this->mrTemplate->AddVar('jabatan_1_item', 'NAMA', $this->Data['jabstruk']);
  		}
  		if (empty($this->Data['jabfung'])) {
  			$this->mrTemplate->AddVar('jabatan_2', 'DATA3_EMPTY', 'YES');
  		} else {
  		  $this->mrTemplate->AddVar('jabatan_2', 'DATA3_EMPTY', 'NO');
  		  $this->mrTemplate->AddVar('jabatan_2_item', 'NAMA', $this->Data['jabfung']);
  		}
  		if (empty($this->Data['satker'])) {
  			$this->mrTemplate->AddVar('sat_kerja', 'DATA4_EMPTY', 'YES');
  		} else {
  		  $this->mrTemplate->AddVar('sat_kerja', 'DATA4_EMPTY', 'NO');
  		  $this->mrTemplate->AddVar('sat_kerja_item', 'NAMA', $this->Data['satker']);
		  $this->mrTemplate->AddVar('sat_kerja_item', 'ID', $this->Data['idSatker']);
  		}
      
   }
}

?>