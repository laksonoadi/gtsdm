<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_daftar/business/laporan.class.php';

class ViewInputWizardPegawai extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_input_wizard_pegawai.html');
   }
   
   function PrepareData(){
    $msg = Messenger::Instance()->Receive(__FILE__);
    $post = $msg[0][0];
    $this->Pesan = $msg[0][1];
      //print_r($_GET['smpn']);
      //get data dari detail data atau dari form
      if (isset($post)) {
      $this->Op = $post['op'];
      //print_r($this->Op);
      $this->Data = array('id'=>$post['pegId'],'nip'=>$post['datpegNip'],'kodegateaccess'=>$rs['pegKodeGateAccess'],'kodeint'=>$post['datpegKodeInter'],'kodelain'=>$post['pegKodeLain'],
      'nama'=>$post['datpegNama'],'alamat'=>$post['datpegAlamat'],'kodepos'=>$post['datpegKodePos'],'telp'=>$post['datpegNoTelp'],
      'hp'=>$post['datpegNoHp'],'email'=>$post['datpegEmail'],'satwil'=>$post['pegSatwilId'],'tmplahir'=>$post['datpegTmpLahir'],
      'tgllahir'=>$post['datpegTglLahir_day'].'-'.$post['datpegTglLahir_mon'].'-'.$post['datpegTglLahir_year'],
      'jenkel'=>$post['pegJenkel'],'jnsidlain'=>$post['datpegJenIdLain'],'idlain'=>$post['datpegIdLain'],
      'agama'=>$post['pegAgamaId'],'nikah'=>$post['pegStatnikahId'],'goldar'=>$post['pegGoldrhId'],'tinggibdn'=>$post['datpegTinggiBdn'],
      'beratbdn'=>$post['datpegBeratBdn'],'cacat'=>$post['datpegCacat'],'hobi'=>$post['datpegHobi'],'jnspegid'=>$post['pegJnspegrId'],
      'tglmasuk'=> isset($post['datpegTglMasuk_year']) ? $post['datpegTglMasuk_day'].'-'.$post['datpegTglMasuk_mon'].'-'.$post['datpegTglMasuk_year'] : date("Y-m-d"),
      'cpnstmt'=> isset($post['datpegCpnsTmt_year']) ? $post['datpegCpnsTmt_day'].'-'.$post['datpegCpnsTmt_mon'].'-'.$post['datpegCpnsTmt_year'] : date("Y-m-d"),
      'pnstmt'=> isset($post['datpegPnsTmt_year']) ? $post['datpegPnsTmt_day'].'-'.$post['datpegPnsTmt_mon'].'-'.$post['datpegPnsTmt_year'] : date("Y-m-d"),
      'notaspen'=>$post['pegNoTaspen'],'noaskes'=>$post['pegNoAskes'],'statusnpwp'=>$post['statusnpwp'],
      'nonpwp'=>$post['datpegNoNpwp'],'usiapens'=>$post['datpegUsiaPensiun'],'statr'=>$post['pegStatrId'],'geldep'=>$post['datpegGelDep'],'gelbel'=>$post['datpegGelBel'],
      'foto'=>$post['pegFoto'],'kodeabsen'=>$post['pegKodeAbsen'],'rekening'=>$post['rekening'],'bank'=>$post['bank'],'resipien'=>$post['resipien'],'pktgol'=>$post['nama'],'jabstruk'=>$post['nama'],
      'jabfung'=>$post['nama'],'idSatker'=>$post['id'],'satker'=>$post['nama'],'kodenik'=>$post['nama'],
      'katpeg'=>$post['katpeg'],'tippeg'=>$post['tippeg'],'dirspv'=>$post['dirspv'],'mor'=>$post['mor'],'pegLevelId'=>$post['pegLevelId'],'pegStatusWargaNeg'=>$post['pegStatusWargaNeg']
    ,'datpegSKCK'=>$post['datpegSKCK'],'pegNoKarpeg'=>$post['pegNoKarpeg'],'pegNoKpe'=>$post['pegNoKpe']
    ,'datpegNoKir'=>$post['datpegNoKir'],'noTaspen'=>$post['datpegNoTaspen'],'noAskes'=>$post['datpegNoAskes']
    ,'pegKelurahan'=>$post['datpegKelurahan'],'pegKecamatan'=>$post['datpegKecamatan']/* ,'datpegBahasa'=>$post['pegdatBahasa'] */
    ,'pegRumah'=>$post['datpegKepemilikanRumah'],'pegJenFungsional'=>$post['PegJenFungsional']
    );
      $this->inputBahasa = $post['pegdatBahasa'];
      // var_dump($this->Data['tglmasuk']);
    }
    
  
  if(empty($this->Data['usiapens']))$this->Data['usiapens'] = 58;
    
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
    $listSatwil = $ObjDatPeg->GetComboSatwilKota();
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
    
    Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'datpegTglLahir', array($this->Data['tgllahir'], null, null, '', '', 'datpegTglLahir'), Messenger::CurrentRequest);
    
  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
   if ($lang=='eng'){
  $listkatpeg[0]['id']="Academic";
  $listkatpeg[0]['name']="Academic";
  $listkatpeg[1]['id']="Non-Academic";
  $listkatpeg[1]['name']="Non-Academic";
  }else{
  $listkatpeg[0]['id']="Academic";
  $listkatpeg[0]['name']="Akademik";
  $listkatpeg[1]['id']="Non-Academic";
  $listkatpeg[1]['name']="Non-Akademik";
  }
  
  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegKatPeg', 
         array('pegKatPeg',$listkatpeg,$this->Data['katpeg'],'',' style="width:130px;"'), Messenger::CurrentRequest);
  $listtippeg[0]['id']="Fulltimer";
  $listtippeg[0]['name']="Fulltimer";
  $listtippeg[1]['id']="Partimer";
  $listtippeg[1]['name']="Partimer";
  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegTipPeg', 
         array('pegTipPeg',$listtippeg,$this->Data['tippeg'],'',' style="width:100px;"'), Messenger::CurrentRequest);
    
  
    
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
    
    $this->Obj=new Laporan;
    $this->ComboJabatanFungsional=$this->Obj->GetComboVariabel('jabatan_fungsional');
    $listPktGol = $ObjDatPeg->GetComboGol();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pktgol', 
         array('pktgol',$listPktGol,'','false',' style="width:180px;"'), Messenger::CurrentRequest);
    $listJabStruk = $ObjDatPeg->GetComboStruk();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabstruk', 
         array('jabstruk',$listJabStruk,'','false',' style="width:290px;"'), Messenger::CurrentRequest);
    $listJabFung = $ObjDatPeg->GetComboFung();
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabfung', 
         array('jabfung',$listJabFung,'','false',' style="width:190px;"'), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'PegJenFungsional', 
         array('PegJenFungsional',$this->ComboJabatanFungsional,$this->Data['pegJenFungsional'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
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
    $warganegara = array(0=>array('id'=>'Lokal','name'=>$lokal),1=>array('id'=>'Asing','name'=>$asing));
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pegStatusWargaNeg', 
         array('pegStatusWargaNeg',$warganegara,$this->Data['pegStatusWargaNeg'],'',' style="width:100px;"'), Messenger::CurrentRequest);
    
    if ($lang=='eng'){
        $ya="Yes";
        $tidak="No";
     }else{
        $ya="Ya";  
        $tidak="Tidak";
     }
    $statusnpwp = array(0=>array('id'=>'Ya','name'=>$ya),1=>array('id'=>'Tidak','name'=>$tidak));
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'statusnpwp', 
         array('statusnpwp',$statusnpwp,$this->Data['statusnpwp'],'',' style="width:100px;"'), Messenger::CurrentRequest);
         
  $dataDir = $ObjDatPeg->GetDataAtas($this->Data['dirspv']);
  $dataMor = $ObjDatPeg->GetDataAtas($this->Data['mor']);
  $return['atasan']['namaDir'] = $dataDir['nama'];
  $return['atasan']['satDir'] = $dataDir['namaSat'];
  $return['atasan']['namaMor'] = $dataMor['nama'];
  $return['atasan']['satMor'] = $dataMor['namaSat'];
  
    $akhirT=date('Y')+4;
    
    // print_r($this->ComboJabatanFungsional);exit();
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegTglLahir', 
         array($this->Data['tgllahir'],$akhirT-100,$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegTglMasuk', 
         array($this->Data['tglmasuk'],$akhirT-100,$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegPnsTmt', 
         array($this->Data['pnstmt'],$akhirT-100,$akhirT,'',''), Messenger::CurrentRequest);
     
   Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'datpegCpnsTmt', 
         array($this->Data['cpnstmt'],$akhirT-100,$akhirT,'',''), Messenger::CurrentRequest);

    // $this->ComboFungsional=$this->Obj->GetComboVariabel2('fungsional');
   
    // $this->label['fungsional']=$this->GetLabelFromCombo($this->ComboPangkat,$this->Obj->filter['fungsional']);
    // Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'fungsional',
    //  array('fungsional', $this->ComboFungsional, $this->Data['cpnstmt'], 'true', ''), Messenger::CurrentRequest);
    
    //$return = $this->Data;
  $return['bahasa'] = $ObjDatPeg->GetComboBahasa();
  
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
           $this->mrTemplate->AddVar('content', 'TITLE', 'DATA PEGAWAI');  
       }

		$this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_pegawai', 'DataPegawai', 'view', 'html'));
      
		$this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('data_pegawai', 'addWizardPegawai', 'do', 'html'));
		$this->mrTemplate->AddVar('content', 'OPERASI', 'add');
		if ($buttonlang=='eng'){
			$tambah="Add";
		}else{
			$tambah="Tambah";
		}
      
      $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVars('content', $this->Data);
      $this->mrTemplate->AddVar('content', 'BANK_LABEL', $this->Data['bank_lab']);
      $this->mrTemplate->AddVar('content', 'URL_POPUP_BANK', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupBank', 'view', 'html'));
    
    if(empty($this->Data['pegRumah']))$this->Data['pegRumah'] = 'TIDAK';
      $this->mrTemplate->AddVar('content', 'KEPEMILIKAN_RUMAH_YA', '');
      $this->mrTemplate->AddVar('content', 'KEPEMILIKAN_RUMAH_TIDAK', '');
      $this->mrTemplate->AddVar('content', 'KEPEMILIKAN_RUMAH_' . strtoupper($this->Data['pegRumah']), 'checked');
    
    $bahasa=$data['bahasa'];
    for($i = 0, $m = count($bahasa); $i < $m; ++$i){
    if(in_array($bahasa[$i]['id'], $this->inputBahasa))$bahasa[$i]['checked'] = 'checked';
    else $bahasa[$i]['checked'] = '';
    $this->mrTemplate->AddVars('bahasa', $bahasa[$i]);
    $this->mrTemplate->parseTemplate('bahasa', 'a');
    }
    
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
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_1', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id1.'&dataSatker='); 
      $this->mrTemplate->AddVar('content', 'URL_POPUP_PEG_2', Dispatcher::Instance()->GetUrl('data_pegawai', 'popupPegawai', 'view', 'html').'&dataPeg='.$id2.'&dataSatker='); 
      
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