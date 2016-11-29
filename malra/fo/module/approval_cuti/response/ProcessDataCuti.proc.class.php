<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_cuti/business/cuti.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/email/business/Email.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class Process
{
   var $POST;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $decId2;
   var $pageView;
   
   function __construct() {
    $this->Obj = new Cuti();
    $this->ObjEmail = new Email();
    $this->pegawaiObj = new DataPegawai();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('approval_cuti', 'historyDataCuti', 'view', 'html');
	  $this->pageHistory = Dispatcher::Instance()->GetUrl('approval_cuti', 'historyDataCuti', 'view', 'html').'&pilihpegawai='.$_POST['pilihpegawai'].'&year='.$_POST['year'];
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
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
     
     #$this->startDate=$this->ObjEmail->IndonesianDate($_POST['tgl_mulai_year'].'-'.$_POST['tgl_mulai_mon'].'-'.$_POST['tgl_mulai_day'],'YYYY-MM-DD');
     #$this->endDate=$this->ObjEmail->IndonesianDate($_POST['tgl_selesai_year'].'-'.$_POST['tgl_selesai_mon'].'-'.$_POST['tgl_selesai_day'],'YYYY-MM-DD');
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      if($_GET['op'] == 'add'){
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->POST['id'];
      } elseif($_GET['op'] == 'edit') {
        $return = $this->pageHistory;
        $return .= "&dataId=".$this->POST['idPeg'];
      }
      
      return $return;
    } 
    /*if($this->POST['tgl_mulai_day'] == "0000" or $this->POST['tgl_mulai_mon'] == "00" or $this->POST['tgl_mulai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif($this->POST['tgl_selesai_day'] == "0000" or $this->POST['tgl_selesai_mon'] == "00" or $this->POST['tgl_selesai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['tipe']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['alasan']) == ''){
      $error = $this->msgReqDataEmpty;
    }*/
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('approval_cuti', 'dataCuti', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      if ($this->POST['op']=="edit"){ 
        $return = $this->pageHistory;
        $return .= "&dataId2=".$_GET['dataId2'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDataCuti(){
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    
    $periodeCuti = $this->Obj->GetPeriodeCutiByPegId($this->POST['idPeg']);
    
    $array=array('peg_id'=>$this->POST['idPeg'],'no_cuti'=>$this->POST['no_cuti'],'tgl_mulai'=>$a,'tgl_selesai'=>$b,
      'tipe'=>$this->POST['tipe'],'reduced'=>$this->POST['reduced'],'alasan'=>$this->POST['alasan'],'tggjwbker'=>$this->POST['tggjwbker'],'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
      'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],'per_id'=>$periodeCuti[0]['per_id']);
    $result = $this->Obj->Add($array);
    
    $lastId = $this->Obj->GetLastId();
    $dataCutiAdded = $this->Obj->GetDataCutiDet($lastId[0]['last_id']);

    if($array['reduced'] == 'Yes'){
      $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambil($dataCutiAdded[0]['durasi'],$dataCutiAdded[0]['durasi'],$this->POST['idPeg'],$periodeCuti[0]['per_id']);
    }elseif($array['reduced'] == 'No'){
      //no update periode cuti
    }
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataCuti(){ 
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    $c=$this->POST['tgl_stat_year'].'-'.$this->POST['tgl_stat_mon'].'-'.$this->POST['tgl_stat_day'];
    //print_r($_POST); exit();
    $dataCuti = $this->Obj->GetDataCutiDet($this->POST['id']);
    $tipe=$_POST['tipe']->raw(); 
    if ($tipe!=2){
       $periodeCuti = $this->Obj->GetPeriodeCutiByPegId($this->POST['pilihpegawai']);
    } else
    if ($tipe==2) {
       //Kompensasi Cuti
       $this->POST['reduced']='No';
       $periodeCuti = $this->Obj->GetPeriodeCutiKompensasiByPegId($this->POST['pilihpegawai']);
    }
    $array=array('peg_id'=>$this->POST['pilihpegawai'],'no_cuti'=>$this->POST['no_cuti'],'tgl_mulai'=>$a,'tgl_selesai'=>$b,
      'tipe'=>$this->POST['tipe'],'reduced'=>$this->POST['reduced'],'alasan'=>$this->POST['alasan'], 'tggjwbker'=>$this->POST['tggjwbker'],'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
      'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],'per_id'=>$periodeCuti[0]['per_id'],'status'=>$this->POST['status'],'tgl_status'=>$c,'id'=>$this->POST['id']);  
    
    $result = $this->Obj->UpdateApproved($array);
    $lastId=$this->POST['id'];
    if ($result) {
      for ($i=0; $i<sizeof($_POST['data']['tanggal']); $i++){
          if ($_POST['status']=='rejected'){
             $result=$this->Obj->UpdateStatusCutiTanggal('Tidak Aktif',$_POST['data']['id'][$i]);
          } else
          if ($_POST['data']['pilih'][$i]=='on'){
             $result=$this->Obj->UpdateStatusCutiTanggal('Aktif',$_POST['data']['id'][$i]);
          } else {
             $result=$this->Obj->UpdateStatusCutiTanggal('Tidak Aktif',$_POST['data']['id'][$i]);
          }
      }
    }
    
    $dataCutiUpdated = $this->Obj->GetDataCutiDet($this->POST['id']);
    
    if ($tipe!=2) {
    //Annual Cuti
         if (($dataCuti[0]['status']!='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCutiUpdated[0]['reduced'] == 'Yes')){
            //Sebelumnya tidak disetujui, kemudian dirubah disetujui dengan pengurangan jatah
            $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else 
          if (($dataCuti[0]['status']!='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCutiUpdated[0]['reduced'] == 'No')){
            //Sebelumnya tidak disetujui, kemudian dirubah disetujui dengan tidak ada pengurangan jatah
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']!='approved')&&($dataCuti[0]['reduced'] == 'Yes')){
            //Sebelumnya disetujui, kemudian dirubah tidak disetujui dengan sebelumnya ada pengurangan jatah
            $rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']!='approved')&&($dataCuti[0]['reduced'] == 'No')){
            //Sebelumnya disetujui, kemudian dirubah tidak disetujui dengan sebelumnya tanpa ada pengurangan jatah
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCuti[0]['reduced'] == 'Yes')&&($dataCutiUpdated[0]['reduced'] == 'Yes')){
            //Sebelumnya disetujui dan tetap disetujui dengan sebelumnya ada pengurangan jatah dan sekarang juga ada pengurangan jatah
            $rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
            $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCuti[0]['reduced'] == 'Yes')&&($dataCutiUpdated[0]['reduced'] == 'No')){
            //Sebelumnya disetujui dan tetap disetujui dengan sebelumnya ada pengurangan jatah dan sekarang juga tidak ada pengurangan jatah
            $rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCuti[0]['reduced'] == 'No')&&($dataCutiUpdated[0]['reduced'] == 'Yes')){
            //Sebelumnya disetujui dan tetap disetujui dengan sebelumnya tidak ada pengurangan jatah dan sekarang ada pengurangan jatah
            $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']=='approved')&&($dataCuti[0]['reduced'] == 'No')&&($dataCutiUpdated[0]['reduced'] == 'No')){
            //Sebelumnya disetujui dan tetap disetujui dengan sebelumnya ada pengurangan jatah dan sekarang juga ada pengurangan jatah
          }
    } else
    if ($tipe==2) {
    //Kompensasi Cuti
          if (($dataCuti[0]['status']!='approved')&&($dataCutiUpdated[0]['status']=='approved')){
            //Sebelumnya tidak disetujui, kemudian dirubah disetujui dengan pengurangan jatah
            $rs_periode_cuti = $this->Obj->UpdatePeriodeCutiKompensasiDiambilTambah($dataCutiUpdated[0]['durasi'],$dataCutiUpdated[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } else
          if (($dataCuti[0]['status']=='approved')&&($dataCutiUpdated[0]['status']!='approved')){
            //Sebelumnya disetujui, kemudian dirubah tidak disetujui dengan sebelumnya ada pengurangan jatah
            $rs_periode_cuti_return = $this->Obj->UpdatePeriodeCutiKompensasiDiambilKurang($dataCuti[0]['durasi'],$dataCuti[0]['durasi'],$this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
          } 
    }
    
    #exit;
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataCuti(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDataCuti();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
           Messenger::Instance()->Send('approval_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDataCuti();
        
        if ($rs_update==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataById($_POST['pilihpegawai']);
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');//$_POST['email_from'];
          $to=$_POST['email_to'];
          $cc=$_POST['email_cc'];
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{CUTI_STATUS}'; $arrBody[1]['with']=$_POST['status'];
          $arrBody[2]['replace']='{CUTI_ALASAN}'; $arrBody[2]['with']=$_POST['alasan'];
          $arrBody[3]['replace']='{CUTI_NUMBER}'; $arrBody[3]['with']=$_POST['no_cuti'];
          $body=$this->ObjEmail->getBodyEmail('email_cuti_approval',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_cuti_approval');
        
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
        }
        
        $return = $this->pageHistory;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->decId;
        if($rs_update == true){
           Messenger::Instance()->Send('approval_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgUpdateSuccess."<br/>".$kirim,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_cuti', 'historyDataCuti', 'view', 'html', array($this->POST,$this->msgUpdateFail."<br/>".$kirim,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('approval_cuti', 'dataCuti', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('approval_cuti', 'dataCuti', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageHistory;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>