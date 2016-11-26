<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_lembur_kompensasi/business/lembur.class.php';
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
    $this->Obj = new Lembur();
    $this->ObjCuti = new Cuti();
    $this->ObjEmail = new Email();
    $this->pegawaiObj = new DataPegawai();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html');
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data Approval successfully';$this->msgUpdateFail='Data Approval failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * and date field must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
     }
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      $this->a=$this->POST['tglaju_year'].'-'.$this->POST['tglaju_mon'].'-'.$this->POST['tglaju_day'];
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      $return = $this->pageView;
      $return .= "&dataId=".$this->POST['idPeg'];
      return $return;
    } 
    /*if($this->POST['tglaju_day'] == "0000" or $this->POST['tglaju_mon'] == "00" or $this->POST['tglaju_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif($this->POST['tglstat_day'] == "0000" or $this->POST['tglstat_mon'] == "00" or $this->POST['tglstat_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['alasan']) == ''){
      $error = $this->msgReqDataEmpty;
    }*/
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDataLembur(){
    $a=$this->POST['tglaju_year'].'-'.$this->POST['tglaju_mon'].'-'.$this->POST['tglaju_day'];
    $b=$this->POST['tglstat_year'].'-'.$this->POST['tglstat_mon'].'-'.$this->POST['tglstat_day'];
    
    $c=$this->POST['start_jam'].':'.$this->POST['start_menit'].':00';
    $d=$this->POST['end_jam'].':'.$this->POST['end_menit'].':00';
    
    $array=array('id'=>$this->POST['idPeg'],'no_lembur'=>$this->POST['no_lembur'],'tglaju'=>$a,'mulai'=>$c,'selesai'=>$d,
      'alasan'=>$this->POST['alasan'],'tglstat'=>$b);
  
    $result = $this->Obj->Add($array);
    
    
    //$rs_periode_lembur = $this->Obj->UpdatePeriodeLemburDiambil($this->POST['idPeg']);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataLembur(){ 
    $a=$this->POST['tglaju_year'].'-'.$this->POST['tglaju_mon'].'-'.$this->POST['tglaju_day'];
    $b=$this->POST['tglstat_year'].'-'.$this->POST['tglstat_mon'].'-'.$this->POST['tglstat_day'];
    
    $array=array('id'=>$this->POST['pilihpegawai'],'tglaju'=>$a,'alasan'=>$this->POST['alasan'],$this->POST['status'],'tglstat'=>$b,'lembur_id'=>$this->POST['lemburId']);
    
    $dataLembur=$this->Obj->GetDataLemburDet($this->POST['lemburId']);
    $result = $this->Obj->Update($array);
    $dataLemburUpdated=$this->Obj->GetDataLemburDet($this->POST['lemburId']);
    
    $periodeCuti = $this->ObjCuti->GetPeriodeCutiKompensasiByPegId($this->POST['pilihpegawai']);
    
    if (($dataLembur[0]['status']!='approved')&&($dataLemburUpdated[0]['status']=='approved')){
        $this->Obj->TambahJatahCutiKompensasi($this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
    } else if (($dataLembur[0]['status']=='approved')&&($dataLemburUpdated[0]['status']!='approved')){
       $this->Obj->KurangiJatahCutiKompensasi($this->POST['pilihpegawai'],$periodeCuti[0]['per_id']);
    } 
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataLembur(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDataLembur();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
           Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      } else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_add = $this->UpdateDataLembur();
        
        if ($rs_add==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
          $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegId']);
          $cc=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtMor']);
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
          $arrBody[2]['replace']='{LEMBUR_ALASAN}'; $arrBody[2]['with']=$_POST['alasan'];
          $arrBody[3]['replace']='{LEMBUR_TANGGAL}'; $arrBody[3]['with']=$this->ObjEmail->IndonesianDate($this->a,'YYYY-MM-DD');
          $arrBody[4]['replace']='{LEMBUR_STATUS}'; $arrBody[4]['with']=$this->POST['status'];
          
          $body=$this->ObjEmail->getBodyEmail('email_lembur_kompensasi_approval',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_lembur_kompensasi_approval');
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
        }
        
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'].'&pilihpegawai='.$this->POST['pilihpegawai'].'&status='.$this->POST['status'];
        if($rs_add == true){
           Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgUpdateSuccess."<br/>".$kirim,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgUpdateFail."<br/>".$kirim,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    //$deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    $deleteData = $this->Obj->Delete($_GET['dataId']);
    if($deleteData == true) {
			Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('approval_lembur_kompensasi', 'historyDataLembur', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>