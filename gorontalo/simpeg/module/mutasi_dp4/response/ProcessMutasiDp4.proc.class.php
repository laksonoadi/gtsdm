<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_dp4/business/mutasi_dp4.class.php';

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
   
   function __construct() {
      $this->Obj = new MutasiDp4();
      $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
      $this->decId = $_POST['dataId']->Integer()->Raw();
      $this->profilId = $_POST['pegId']->Integer()->Raw();
      $this->delId = $_GET['id']->Integer()->Raw();
      $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
      if ($this->lang=='eng'){
       	$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       	$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       	$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       	$this->msgReqDataEmpty='All field marked with * and date field must be filled';
    		$this->error='Data incomplete!';
    		$this->activeStatus='Active';$this->inactiveStatus='Inactive';
      }else{
       	$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       	$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       	$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       	$this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
    		$this->error='Data tidak lengkap!';
    		$this->activeStatus='Aktif';$this->inactiveStatus='Tidak Aktif';
      }
      
      $this->pageInput = Dispatcher::Instance()->GetUrl('mutasi_dp4','MutasiDp4','view','html').'&id='.$this->profilId;
      $this->pageView = Dispatcher::Instance()->GetUrl('mutasi_dp4','MutasiDp4','view','html').'&id='.$this->profilId;
      $this->pageBack = Dispatcher::Instance()->GetUrl('mutasi_dp4','MutasiDp4','view','html').'&id='.$this->delId;
      //print_r($this->pageView);exit();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray(); 
      //echo "<pre>"; print_r($this->POST); echo "</pre>"; exit();
      //$this->FILES = $param2->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])) {
      return $this->pageView;
    }
    
    if (isset($this->error)){
      $msg = array($this->POST, $this->error, $this->cssAlert);
      Messenger::Instance()->Send('mutasi_dp4','MutasiDp4','view','html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (isset($_GET['dataId'])){ 
      $return .= "&dataId=".$this->decId."&id=".$this->profilId;
      }
      return $return;
    }
    return true;
  }
  
         

  
  function AddData(){
    
    $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_buat=$this->POST['tgl_buat_year'].'-'.$this->POST['tgl_buat_mon'].'-'.$this->POST['tgl_buat_day'];
    $tgl_pns=$this->POST['tgl_pns_year'].'-'.$this->POST['tgl_pns_mon'].'-'.$this->POST['tgl_pns_day'];
    $tgl_diterima=$this->POST['tgl_diterima_year'].'-'.$this->POST['tgl_diterima_mon'].'-'.$this->POST['tgl_diterima_day'];
    
    $pegawai=$this->Obj->GetDataDetail($this->POST['pegId']); $pegawai=$pegawai[0];
    $pejabat=$this->Obj->GetDataDetail($this->POST['pejabat_id']); $pejabat=$pejabat[0];
    $atasanPejabat=$this->Obj->GetDataDetail($this->POST['atasan_pejabat_id']); $atasanPejabat=$atasanPejabat[0];
    
    $array=array(
    				'dpPeriode'=>$mulai,         
            'dpPeriodeAkhir'=>$selesai,         
            'dpPegKode'=>$this->POST['pegId'],         
            'dpPktgolrId'=>$pegawai['pangkat_id'],         
            'dpJabstrukrId'=>$pegawai['jabatan_id'],         
            'dpUkjrId'=>$pegawai['unit_kerja_id'],         
            'dpPenilaiPegKode'=>$this->POST['pejabat_id'],         
            'dpPenilaiPktgolrId'=>$pejabat['pangkat_id'],         
            'dpPenilaiJabstrukrId' =>$pejabat['jabatan_id'],        
            'dpPenilaiUkjrId'=>$pejabat['unit_kerja_id'],         
            'dpAtsnPenilaiPegKode'=>$this->POST['atasan_pejabat_id'],         
            'dpAtsnPenilaiPktgolrId'=>$atasanPejabat['pangkat_id'],         
            'dpAtsnPenilaiJabstrukrId'=>$atasanPejabat['jabatan_id'],         
            'dpAtsnPenilaiUkjrId'=>$atasanPejabat['unit_kerja_id'],         
            'dpKesetiaan'=>$this->POST['kesetiaan'],         
            'dpPrestasiKerja'=>$this->POST['prestasi_kerja'],         
            'dpTanggungJawab'=>$this->POST['tanggung_jawab'],         
            'dpKetaatan'=>$this->POST['ketaatan'],         
            'dpKejujuran'=>$this->POST['kejujuran'],         
            'dpKerjasama'=>$this->POST['kerjasama'],         
            'dpPrakarsa'=>$this->POST['prakarsa'],         
            'dpKepemimpinan'=>$this->POST['kepemimpinan'],         
            'dpKeberatanPnsDinilai'=>$this->POST['keberatan'],         
            'dpTanggapanAtasan'=>$this->POST['tanggapan_keberatan'],         
            'dpKeputusanAtasan'=>$this->POST['keputusan_atasan'],         
            'dpLain'=>$this->POST['lain_lain'],         
            'dpTanggalDibuat'=>$tgl_buat,         
            'dpTanggalDiterimaPns'=>$tgl_pns,         
            'dpTanggalDiterimaAtasan'=>$tgl_diterima,
            'dpNilaiDasarYayasan'=>$this->POST['nilai_yayasan']
		);
    //$this->dumper($array);exit();
    $this->Obj->StartTrans();
    $result = $this->Obj->Add($array);
    $this->Obj->EndTrans($result);
    
	  if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateData(){ 
     
	  $mulai=$this->POST['mulai_year'].'-'.$this->POST['mulai_mon'].'-'.$this->POST['mulai_day'];
    $selesai=$this->POST['selesai_year'].'-'.$this->POST['selesai_mon'].'-'.$this->POST['selesai_day'];
    $tgl_buat=$this->POST['tgl_buat_year'].'-'.$this->POST['tgl_buat_mon'].'-'.$this->POST['tgl_buat_day'];
    $tgl_pns=$this->POST['tgl_pns_year'].'-'.$this->POST['tgl_pns_mon'].'-'.$this->POST['tgl_pns_day'];
    $tgl_diterima=$this->POST['tgl_diterima_year'].'-'.$this->POST['tgl_diterima_mon'].'-'.$this->POST['tgl_diterima_day'];
    
    $pegawai=$this->Obj->GetDataDetail($this->POST['pegId']); $pegawai=$pegawai[0];
    $pejabat=$this->Obj->GetDataDetail($this->POST['pejabat_id']); $pejabat=$pejabat[0];
    $atasanPejabat=$this->Obj->GetDataDetail($this->POST['atasan_pejabat_id']); $atasanPejabat=$atasanPejabat[0];
    
    $array=array(
    				'dpPeriode'=>$mulai,         
            'dpPeriodeAkhir'=>$selesai,         
            'dpPegKode'=>$this->POST['pegId'],         
            'dpPktgolrId'=>$pegawai['pangkat_id'],         
            'dpJabstrukrId'=>$pegawai['jabatan_id'],         
            'dpUkjrId'=>$pegawai['unit_kerja_id'],         
            'dpPenilaiPegKode'=>$this->POST['pejabat_id'],         
            'dpPenilaiPktgolrId'=>$pejabat['pangkat_id'],         
            'dpPenilaiJabstrukrId' =>$pejabat['jabatan_id'],        
            'dpPenilaiUkjrId'=>$pejabat['unit_kerja_id'],         
            'dpAtsnPenilaiPegKode'=>$this->POST['atasan_pejabat_id'],         
            'dpAtsnPenilaiPktgolrId'=>$atasanPejabat['pangkat_id'],         
            'dpAtsnPenilaiJabstrukrId'=>$atasanPejabat['jabatan_id'],         
            'dpAtsnPenilaiUkjrId'=>$atasanPejabat['unit_kerja_id'],         
            'dpKesetiaan'=>$this->POST['kesetiaan'],         
            'dpPrestasiKerja'=>$this->POST['prestasi_kerja'],         
            'dpTanggungJawab'=>$this->POST['tanggung_jawab'],         
            'dpKetaatan'=>$this->POST['ketaatan'],         
            'dpKejujuran'=>$this->POST['kejujuran'],         
            'dpKerjasama'=>$this->POST['kerjasama'],         
            'dpPrakarsa'=>$this->POST['prakarsa'],         
            'dpKepemimpinan'=>$this->POST['kepemimpinan'],         
            'dpKeberatanPnsDinilai'=>$this->POST['keberatan'],         
            'dpTanggapanAtasan'=>$this->POST['tanggapan_keberatan'],         
            'dpKeputusanAtasan'=>$this->POST['keputusan_atasan'],         
            'dpLain'=>$this->POST['lain_lain'],         
            'dpTanggalDibuat'=>$tgl_buat,         
            'dpTanggalDiterimaPns'=>$tgl_pns,         
            'dpTanggalDiterimaAtasan'=>$tgl_diterima,
            'dpNilaiDasarYayasan'=>$this->POST['nilai_yayasan'],
            'dpId'=>$this->POST['dataId']
		);
		
	  $this->Obj->StartTrans();
    $result = $this->Obj->Update($array);
    $this->Obj->EndTrans($result);
    
	  if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputData(){
      //$check = $this->Check();
      //if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && empty($this->decId)) {
        $rs_add = $this->AddData();
        if($rs_add== true){
            Messenger::Instance()->Send('mutasi_dp4','MutasiDp4','view','html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
            return $this->pageView;
        }else{
            Messenger::Instance()->Send('mutasi_dp4','MutasiDp4','view','html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
            return $this->pageView;
        }
     }else if ((isset($this->POST['btnsimpan'])) && !empty($this->decId)) {
        $rs_update = $this->UpdateData();
        if($rs_update== true){
            Messenger::Instance()->Send('mutasi_dp4','MutasiDp4','view','html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
            return $this->pageView;
        }else{
            Messenger::Instance()->Send('mutasi_dp4','MutasiDp4','view','html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
            return $this->pageView;
        }
      }
      //return $urlRedirect;
   }
   
   function Delete(){
   //print_r($this->POST);exit();
   $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
   if($deleteData == true) {
			Messenger::Instance()->Send('mutasi_dp4', 'MutasiDp4', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('mutasi_dp4', 'MutasiDp4', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
   $return = $this->pageBack;
   //$return .= "&id=".$this->delId;
	return $return;
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}

?>
