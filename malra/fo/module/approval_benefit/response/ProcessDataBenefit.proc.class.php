<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_benefit/business/benefit.class.php';
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
    $this->Obj = new Benefit();
    $this->ObjEmail = new Email();
    $this->pegawaiObj = new DataPegawai();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('approval_benefit', 'historyDataBenefit', 'view', 'html');
	  $this->pageHistory = Dispatcher::Instance()->GetUrl('approval_benefit', 'historyDataBenefit', 'view', 'html');
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
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      $this->POST['total_klaim']=$this->Obj->num_toprocess($this->POST['total_klaim']);
      $this->POST['approval_total_klaim']=$this->Obj->num_toprocess($this->POST['approval_total_klaim']);
      for ($i=0; $i<sizeof($this->POST['data']['nilai_klaim']); $i++){
         $this->POST['data']['nilai_klaim'][$i]=$this->Obj->num_toprocess($this->POST['data']['nilai_klaim'][$i]);
         $this->POST['data']['approval_nilai_klaim'][$i]=$this->Obj->num_toprocess($this->POST['data']['approval_nilai_klaim'][$i]);
      }
      $this->a=$this->POST['tgl_benefit_year'].'-'.$this->POST['tgl_benefit_mon'].'-'.$this->POST['tgl_benefit_day'];
      $this->b=$this->POST['tgl_klaim_year'].'-'.$this->POST['tgl_klaim_mon'].'-'.$this->POST['tgl_klaim_day'];
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
    if($this->POST['tgl_benefit_day'] == "00" or $this->POST['tgl_benefit_mon'] == "00" or $this->POST['tgl_benefit_year'] == "0000"){
      $error = $this->msgReqDataEmpty;
    }elseif($this->POST['tgl_klaim_day'] == "00" or $this->POST['tgl_klaim_mon'] == "00" or $this->POST['tgl_klaim_year'] == "0000"){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['nama_pasien']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['relasi_pasien']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['jenis_benefit']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['tempat']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', $msg, Messenger::NextRequest);
      
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
  
  function AddDataBenefit(){
    $a=$this->POST['tgl_benefit_year'].'-'.$this->POST['tgl_benefit_mon'].'-'.$this->POST['tgl_benefit_day'];
    $b=$this->POST['tgl_klaim_year'].'-'.$this->POST['tgl_klaim_mon'].'-'.$this->POST['tgl_klaim_day'];
    
    $balanceBenefit = $this->Obj->GetBalanceBenefitByPegId($this->POST['idPeg']);
    
    $array=array('no_benefit'=>$this->POST['no_benefit'],'per_id'=>$balanceBenefit[0]['per_id'],
                  'peg_id'=>$this->POST['idPeg'],'nama_pasien'=>$this->POST['nama_pasien'],
                  'relasi_pasien'=>$this->POST['relasi_pasien'],'jenis_benefit'=>$this->POST['jenis_benefit'],
                  'benefit_tgl'=>$a,
                  'benefit_tempat'=>$this->POST['tempat'],'total_klaim'=>$this->POST['total_klaim'],
                  'alasan'=>$this->POST['alasan'],'tgl_klaim'=>$b);
    
    $this->Obj->StartTrans();
    $result = $this->Obj->Add($array);
    
    if ($result) {
      $lastId = $this->Obj->GetLastId();
      $lastId = $lastId[0]['last_id'];
      for ($i=0; $i<sizeof($this->POST['data']['tipe_klaim']); $i++){
          $a=$lastId;
          $b=$this->POST['data']['tipe_klaim'][$i];
          $c=$this->POST['data']['nilai_klaim'][$i];
          $d=$this->POST['data']['file_klaim'][$i];
          $this->Obj->AddKlaim($a,$b,$c,$d);
      }
    }
    $this->Obj->EndTrans(true);
    
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataBenefit(){ 
    $a=$this->POST['tgl_benefit_year'].'-'.$this->POST['tgl_benefit_mon'].'-'.$this->POST['tgl_benefit_day'];
    $b=$this->POST['tgl_klaim_year'].'-'.$this->POST['tgl_klaim_mon'].'-'.$this->POST['tgl_klaim_day'];
    $c=$this->POST['tgl_stat_year'].'-'.$this->POST['tgl_stat_mon'].'-'.$this->POST['tgl_stat_day'];
    
    $dataBenefit = $this->Obj->GetDataBenefitDet($this->POST['id']);
    $balanceBenefit = $this->Obj->GetBalanceBenefitByPegId($this->POST['pilihpegawai']);

    $array=array('no_benefit'=>$this->POST['no_benefit'],'per_id'=>$balanceBenefit[0]['per_id'],
                  'peg_id'=>$this->POST['pilihpegawai'],'nama_pasien'=>$this->POST['nama_pasien'],
                  'relasi_pasien'=>$this->POST['relasi_pasien'],'jenis_benefit'=>$this->POST['jenis_benefit'],
                  'benefit_tgl'=>$a,
                  'benefit_tempat'=>$this->POST['tempat'],'total_klaim'=>$this->POST['approval_total_klaim'],
                  'alasan'=>$this->POST['alasan'],'tgl_klaim'=>$b,
                  'status'=>$this->POST['status'],'tgl_status'=>$c,
                  'id_benefit'=>$this->POST['id']);
    
    $this->Obj->StartTrans();          
    $result = $this->Obj->UpdateApproval($array);
    if ($result) {
      $dataBenefitUpdated = $this->Obj->GetDataBenefitDet($this->POST['id']);
      
      for ($i=0; $i<sizeof($this->POST['data']['tipe_klaim']); $i++){          
          $a=$this->POST['data']['approval_nilai_klaim'][$i];
          $b=$this->POST['data']['id_klaim'][$i];
          $this->Obj->UpdateNilaiKlaim($a,$b);
      }
      
      if (($dataBenefit[0]['status']!='approved')&&($dataBenefitUpdated[0]['status']=='approved')){
        //Sebelumnya tidak disetujui, kemudian dirubah disetujui dengan pengurangan jatah
        $rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambilTambah($dataBenefitUpdated[0]['total_klaim'],$dataBenefitUpdated[0]['total_klaim'],$this->POST['pilihpegawai'],$balanceBenefit[0]['per_id']);
      } else
      if (($dataBenefit[0]['status']=='approved')&&($dataBenefitUpdated[0]['status']!='approved')){
        //Sebelumnya disetujui, kemudian dirubah tidak disetujui dengan sebelumnya ada pengurangan jatah
        $rs_balance_benefit_return = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefit[0]['total_klaim'],$dataBenefit[0]['total_klaim'],$this->POST['pilihpegawai'],$balanceBenefit[0]['per_id']);
      }
    }
    $this->Obj->EndTrans(true);
    
    #exit;
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataBenefit(){
	    
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDataBenefit();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&pilihpegawai=".$this->POST['pilihpegawai'];
        if($rs_add == true){
           Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDataBenefit();
        
        if ($rs_update==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
          $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegId']);
          $cc=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtMor']);
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
          $arrBody[2]['replace']='{BENEFIT_TIPE}'; $arrBody[2]['with']=$this->Obj->GetJenisBenefitById($_POST['jenis_benefit']);
          $arrBody[3]['replace']='{BENEFIT_TANGGAL_KLAIM}'; $arrBody[3]['with']=$this->ObjEmail->IndonesianDate($this->b,'YYYY-MM-DD');
          $arrBody[4]['replace']='{BENEFIT_TANGGAL}'; $arrBody[4]['with']=$this->ObjEmail->IndonesianDate($this->a,'YYYY-MM-DD');
          $arrBody[5]['replace']='{BENEFIT_TEMPAT}'; $arrBody[5]['with']=$_POST['tempat'];
          $arrBody[6]['replace']='{BENEFIT_ALASAN}'; $arrBody[6]['with']=$_POST['alasan'];
          $arrBody[7]['replace']='{BENEFIT_TOTAL_KLAIM}'; $arrBody[7]['with']=$_POST['total_klaim'];
          $arrBody[8]['replace']='{BENEFIT_STATUS}'; $arrBody[8]['with']=$_POST['status'];
          $arrBody[9]['replace']='{BENEFIT_NUMBER}'; $arrBody[9]['with']=$_POST['no_benefit'];
          
          $body=$this->ObjEmail->getBodyEmail('email_benefit_approval',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_benefit_approval');
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
        }
        
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->decId."&pilihpegawai=".$this->POST['pilihpegawai'];
        if($rs_update == true){
           Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgUpdateSuccess."<br/>".$kirim,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgUpdateFail."<br/>".$kirim,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('approval_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>