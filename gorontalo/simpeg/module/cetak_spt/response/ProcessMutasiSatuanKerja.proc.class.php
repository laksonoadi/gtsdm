<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/cetak_spt/business/cetakspt.class.php';

class Process
{
   var $POST;
   //var $FILES;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $pageView;
   var $pageBack;
   
   function __construct($ret) {
      $this->Obj = new cetakspt();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataId']->Integer()->Raw();
      $this->profilId = $_POST['pegawaiId']->Integer()->Raw();
      $this->profilIdKetua = $_POST['id']->Integer()->Raw();
      // print_r($this->profilIdKetua);exit();
      $this->delId = $_GET['id']->Integer()->Raw();
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
      if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * and date field must be filled';
      }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
      }

      if($ret == "html"){
         $this->pageInput = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html').'&id='.$this->profilId.'&dataId='.$this->decId;
         $this->pageInputKetua = Dispatcher::Instance()->GetUrl('cetak_spt','InputSptKetua','view','html').'&id='.$this->profilIdKetua.'&dataId='.$this->profilIdKetua;
         $this->pageViewKetua = Dispatcher::Instance()->GetUrl('cetak_spt','InputSptKetua','view','html').'&id='.$this->profilIdKetua.'&dataId='.$this->profilIdKetua;
         $this->pageView = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html').'&id='.$this->profilId.'&dataId='.$this->decId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html').'&id='.$this->delId;
        }else{
         $this->pageInput = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html',true).'&id='.$this->profilId.'&dataId='.$this->decId;
         $this->pageInputKetua = Dispatcher::Instance()->GetUrl('cetak_spt','InputSptKetua','view','html',true).'&id='.$this->profilIdKetua.'&dataId='.$this->profilIdKetua;
         $this->pageViewKetua = Dispatcher::Instance()->GetUrl('cetak_spt','InputSptKetua','view','html',true).'&id='.$this->profilIdKetua.'&dataId='.$this->profilIdKetua;
         $this->pageView = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html',true).'&id='.$this->profilId.'&dataId='.$this->decId;
         $this->pageBack = Dispatcher::Instance()->GetUrl('cetak_spt','InputSpt','view','html').'&id='.$this->delId;
        }
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    // if (isset($this->POST['btnbatal'])) return $this->pageView;

    if (trim($this->POST['jabatanbaru']) == ''){
  $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }

  function CheckKetua (){
    // if (isset($this->POST['btnbatal'])) return $this->pageView;
    // print_r($this->POST);exit();
    if (trim($this->POST['nomor_sk_pelantikan']) == ''){
  $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_satuan_kerja','MutasiSatuanKerja','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInputKetua;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }
  
  function AddData(){
    
    // $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
    // $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];

    $array=array(
				'pubspt_pegId'=>$this->POST['pegawaiId'],
				'pubspt_nomor_golongan'=>$this->POST['nomor_sk'],
        'pubspt_nomor_spt'=>$this->POST['nomor_sk'],
				'pubspt_sambutan'=>$this->POST['penugas'],
				'pubspt_tanggal'=>$this->POST['tanggal_year'].'-'.$this->POST['tanggal_mon'].'-'.$this->POST['tanggal_day'],
				'pubspt_panggoltlama'=>$this->POST['lokasi'],
				'pubspt_jabatanlama'=>$this->POST['jabatanlama'],
				'pubspt_jabatanbaru'=>$this->POST['jabatanbaru'],
				'pubspt_kotattd'=>$this->POST['kota'],

        'pubspt_tanggalttd'=>$this->POST['tanggalttd_year'].'-'.$this->POST['tanggalttd_mon'].'-'.$this->POST['tanggalttd_day'],
        'pubspt_satuanttd_id'=>$this->POST['unitkerja'],
        'pubspt_panggolttd_id'=>$this->POST['jabfungsional'],
        'pubspt_nipttd'=>$this->POST['nipttd'],
        'pubspt_namattd'=>$this->POST['namattd'],
        'pubspt_jabatanttd_id'=>$this->POST['jabstruktural'],
        'pubspt_tembusan4'=>$this->POST['tembusan4'],
        'pubspt_tembusan5'=>$this->POST['tembusan5'],
        'pubspt_tembusan6'=>$this->POST['tembusan6'],
        'pubspt_tembusan7'=>$this->POST['tembusan7'],
        'pubspt_tembusan8'=>$this->POST['tembusan8']
			);
  // $this->dumper($array);exit();
    $result = $this->Obj->Add($array);
    if ($result){
	  $getId=$this->Obj->GetMaxSptId();
	  // if($array['status']=='Aktif'){
	  //    $stat_update=$this->Obj->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->profilId);
	  // }
      return $result;
    }else{
      return false;
    }
  }


   function AddDataKetua(){
    
    // $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
    // $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];

    $array=array(
        'pubpeg_sk_1'=>$this->POST['nomor_sk_pelantikan'],
        'pubpeg_jabat_nama'=>$this->POST['pejabat_nama'],
        'pubpeg_jabat_nip'=>$this->POST['pejabat_nip'],
        'pubpeg_jabat_panggol'=>$this->POST['pejabat_pangol'],
        'pubpeg_jabat_jabatan'=>$this->POST['pejabat_jabstruktural'],
        'pubpeg_nama'=>$this->POST['nama'],
        'pubpeg_nim'=>$this->POST['nip'],
        'pubpeg_panggol'=>$this->POST['pangol'],
        'pubpeg_sk_walkot'=>$this->POST['sk_walkot'],
        'pubpeg_sk_walkot_tgl'=>$this->POST['tgl_sk_wali_year'].'-'.$this->POST['tgl_sk_wali_mon'].'-'.$this->POST['tgl_sk_wali_day'],
        'pubpeg_jabatan'=>$this->POST['jabatanbaru'],
        'pubpeg_unitkerja'=>$this->POST['unitkerja'],
        'pubpeg_tgl_lantik'=>$this->POST['tgl_pelantikan_year'].'-'.$this->POST['tgl_pelantikan_mon'].'-'.$this->POST['tgl_pelantikan_day'],
        'pubspt_tembusan4'=>$this->POST['tembusan4'],
        'pubspt_tembusan5'=>$this->POST['tembusan5'],
        'pubspt_tembusan6'=>$this->POST['tembusan6'],
        'pubspt_tembusan7'=>$this->POST['tembusan7'],
        'pubpeg_sk_2'=>$this->POST['nomor_sk_menduduki'],
        'pubpeg_sk_walkot_menduduki'=>$this->POST['sk_walkot_duduk'],
        'pubpeg_sk_walkot_menduduki_tgl'=>$this->POST['tgl_sk_waklot_duduk_year'].'-'.$this->POST['tgl_sk_waklot_duduk_mon'].'-'.$this->POST['tgl_sk_waklot_duduk_day'],
        'pubpeg_eselon'=>$this->POST['eselon'],
        'pubpeg_tgl_menduduki'=>$this->POST['tgl_menduduki_year'].'-'.$this->POST['tgl_menduduki_mon'].'-'.$this->POST['tgl_menduduki_day'],
        'pubpeg_gaji'=>$this->POST['tunjangan'],
        'pubpeg_sk3'=>$this->POST['nomor_sk_pelaksana'],
        'pubpeg_tgl_tgs'=>$this->POST['tgl_pelaksana_tugas_year'].'-'.$this->POST['tgl_pelaksana_tugas_mon'].'-'.$this->POST['tgl_pelaksana_tugas_day'],
        'pubpeg_tglsurat_1'=>$this->POST['pubpeg_tglsurat_1_year'].'-'.$this->POST['pubpeg_tglsurat_1_mon'].'-'.$this->POST['pubpeg_tglsurat_1_day'],
        'pubpeg_tglsurat_2'=>$this->POST['pubpeg_tglsurat_2_year'].'-'.$this->POST['pubpeg_tglsurat_2_mon'].'-'.$this->POST['pubpeg_tglsurat_2_day'],
        'pubpeg_tglsurat_3'=>$this->POST['pubpeg_tglsurat_3_year'].'-'.$this->POST['pubpeg_tglsurat_3_mon'].'-'.$this->POST['pubpeg_tglsurat_3_day'],
        'pubpeg_idpeg'=>$this->POST['id']
        

      );
  // $this->dumper($array);exit();

    $result = $this->Obj->AddKetua($array);
    if ($result){
    $getId=$this->Obj->GetMaxSptId();
    // if($array['status']=='Aktif'){
    //    $stat_update=$this->Obj->UpdateStatus('Tidak Aktif',$getId[0]['MAXID'],$this->profilId);
    // }
      return $result;
    }else{
      return false;
    }
  }

  function UpdateDataKetua(){ 
     
   // $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
  //   $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
        'pubpeg_sk_1'=>$this->POST['nomor_sk_pelantikan'],
        'pubpeg_jabat_nama'=>$this->POST['pejabat_nama'],
        'pubpeg_jabat_nip'=>$this->POST['pejabat_nip'],
        'pubpeg_jabat_panggol'=>$this->POST['pejabat_pangol'],
        'pubpeg_jabat_jabatan'=>$this->POST['pejabat_jabstruktural'],
        'pubpeg_nama'=>$this->POST['nama'],
        'pubpeg_nim'=>$this->POST['nip'],
        'pubpeg_panggol'=>$this->POST['pangol'],
        'pubpeg_sk_walkot'=>$this->POST['sk_walkot'],
        'pubpeg_sk_walkot_tgl'=>$this->POST['tgl_sk_wali_year'].'-'.$this->POST['tgl_sk_wali_mon'].'-'.$this->POST['tgl_sk_wali_day'],
        'pubpeg_jabatan'=>$this->POST['jabatanbaru'],
        'pubpeg_unitkerja'=>$this->POST['unitkerja'],
        'pubpeg_tgl_lantik'=>$this->POST['tgl_pelantikan_year'].'-'.$this->POST['tgl_pelantikan_mon'].'-'.$this->POST['tgl_pelantikan_day'],
        'pubspt_tembusan4'=>$this->POST['tembusan4'],
        'pubspt_tembusan5'=>$this->POST['tembusan5'],
        'pubspt_tembusan6'=>$this->POST['tembusan6'],
        'pubspt_tembusan7'=>$this->POST['tembusan7'],
        'pubpeg_sk_2'=>$this->POST['nomor_sk_menduduki'],
        'pubpeg_sk_walkot_menduduki'=>$this->POST['sk_walkot_duduk'],
        'pubpeg_sk_walkot_menduduki_tgl'=>$this->POST['tgl_sk_waklot_duduk_year'].'-'.$this->POST['tgl_sk_waklot_duduk_mon'].'-'.$this->POST['tgl_sk_waklot_duduk_day'],
        'pubpeg_eselon'=>$this->POST['eselon'],
        'pubpeg_tgl_menduduki'=>$this->POST['tgl_menduduki_year'].'-'.$this->POST['tgl_menduduki_mon'].'-'.$this->POST['tgl_menduduki_day'],
        'pubpeg_gaji'=>$this->POST['tunjangan'],
        'pubpeg_sk3'=>$this->POST['nomor_sk_pelaksana'],
        'pubpeg_tgl_tgs'=>$this->POST['tgl_pelaksana_tugas_year'].'-'.$this->POST['tgl_pelaksana_tugas_mon'].'-'.$this->POST['tgl_pelaksana_tugas_day'],
        'pubpeg_tglsurat_1'=>$this->POST['pubpeg_tglsurat_1_year'].'-'.$this->POST['pubpeg_tglsurat_1_mon'].'-'.$this->POST['pubpeg_tglsurat_1_day'],
        'pubpeg_tglsurat_2'=>$this->POST['pubpeg_tglsurat_2_year'].'-'.$this->POST['pubpeg_tglsurat_2_mon'].'-'.$this->POST['pubpeg_tglsurat_2_day'],
        'pubpeg_tglsurat_3'=>$this->POST['pubpeg_tglsurat_3_year'].'-'.$this->POST['pubpeg_tglsurat_3_mon'].'-'.$this->POST['pubpeg_tglsurat_3_day'],
        'pubpeg_idpeg'=>$this->POST['id']

      );
   // print_r($this);exit();
    $result = $this->Obj->UpdateKetua($array);
    if ($result){
    
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData(){ 
     
	 // $tmt=$this->POST['tmt_year'].'-'.$this->POST['tmt_mon'].'-'.$this->POST['tmt_day'];
  //   $tgl_sk=$this->POST['tgl_sk_year'].'-'.$this->POST['tgl_sk_mon'].'-'.$this->POST['tgl_sk_day'];
    
    $array=array(
				'pubspt_pegId'=>$this->POST['pegawaiId'],
        'pubspt_nomor_golongan'=>$this->POST['nomor_sk'],
        'pubspt_nomor_spt'=>$this->POST['nomor_sk'],
        'pubspt_sambutan'=>$this->POST['penugas'],
        'pubspt_tanggal'=>$this->POST['tanggal_year'].'-'.$this->POST['tanggal_mon'].'-'.$this->POST['tanggal_day'],
        'pubspt_panggoltlama'=>$this->POST['lokasi'],
        'pubspt_jabatanlama'=>$this->POST['jabatanlama'],
        'pubspt_jabatanbaru'=>$this->POST['jabatanbaru'],
        'pubspt_kotattd'=>$this->POST['kota'],

        'pubspt_tanggalttd'=>$this->POST['tanggalttd_year'].'-'.$this->POST['tanggalttd_mon'].'-'.$this->POST['tanggalttd_day'],
        'pubspt_satuanttd_id'=>$this->POST['unitkerja'],
        'pubspt_panggolttd_id'=>$this->POST['jabfungsional'],
        'pubspt_nipttd'=>$this->POST['nipttd'],
        'pubspt_namattd'=>$this->POST['namattd'],
        'pubspt_jabatanttd_id'=>$this->POST['jabstruktural'],
        'pubspt_tembusan4'=>$this->POST['tembusan4'],
        'pubspt_tembusan5'=>$this->POST['tembusan5'],
        'pubspt_tembusan6'=>$this->POST['tembusan6'],
        'pubspt_tembusan7'=>$this->POST['tembusan7'],
        'pubspt_tembusan8'=>$this->POST['tembusan8']
			);
	 // print_r($this);exit();
    $result = $this->Obj->Update($array);
    if ($result){
	  
      return $result;
    }else{
      return false;
    }
  }
	
	function InputData(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) ) {
       // print_r($this);
        $rs_add = $this->AddData();
        if($rs_add){
          $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
          }else{
          $msg = array(1=>$this->msgAddFail, $this->cssFail);
          }
          Messenger::Instance()->Send('cetak_spt', 'InputSpt', 'view', 'html', $msg, Messenger::NextRequest);
          return $this->pageView;
      }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        
        $rs_update = $this->UpdateData();
        	
        if($rs_update){
          $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
          }else{
          $msg = array(1=>$this->msgAddFail, $this->cssFail);
          }
          Messenger::Instance()->Send('cetak_spt', 'InputSpt', 'view', 'html', $msg, Messenger::NextRequest);
          return $this->pageView;
      }
      // return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
   //$return .= "&id=".$this->delId;
	return $return;
   }
   
   function dumper($print){
	   // echo"<pre>";print_r($print);echo"</pre>";
	}

  function InputDataKetua(){
      $check = $this->CheckKetua();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) ) {
       // print_r($this);
        $rs_add = $this->AddDataKetua();
        if($rs_add){
          $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
          }else{
          $msg = array(1=>$this->msgAddFail, $this->cssFail);
          }
          Messenger::Instance()->Send('cetak_spt', 'InputSptKetua', 'view', 'html', $msg, Messenger::NextRequest);
          return $this->pageViewKetua;
      }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        
        $rs_update = $this->UpdateDataKetua();
          
        if($rs_update){
          $msg = array(1=>$this->msgAddSuccess, $this->cssDone);
          }else{
          $msg = array(1=>$this->msgAddFail, $this->cssFail);
          }
          Messenger::Instance()->Send('cetak_spt', 'InputSptKetua', 'view', 'html', $msg, Messenger::NextRequest);
          return $this->pageViewKetua;
      }
      // return $urlRedirect;
   }




}

?>
