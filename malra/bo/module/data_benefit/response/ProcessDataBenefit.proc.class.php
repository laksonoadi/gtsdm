<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_benefit/business/benefit.class.php';

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
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_benefit', 'dataBenefit', 'view', 'html');
	  $this->pageHistory = Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html');
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
    if($this->POST['tgl_mulai_day'] == "0000" or $this->POST['tgl_mulai_mon'] == "00" or $this->POST['tgl_mulai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif($this->POST['tgl_selesai_day'] == "0000" or $this->POST['tgl_selesai_mon'] == "00" or $this->POST['tgl_selesai_year'] == "00"){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['tipe']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['alasan']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_benefit', 'dataBenefit', 'view', 'html', $msg, Messenger::NextRequest);
      
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
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    
    $balanceBenefit = $this->Obj->GetBalanceBenefitByPegId($this->POST['idPeg']);
    
    $array=array('peg_id'=>$this->POST['idPeg'],'no_benefit'=>$this->POST['no_benefit'],'tgl_mulai'=>$a,'tgl_selesai'=>$b,
      'tipe'=>$this->POST['tipe'],'reduced'=>$this->POST['reduced'],'alasan'=>$this->POST['alasan'],'tggjwbker'=>$this->POST['tggjwbker'],'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
      'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],'per_id'=>$balanceBenefit[0]['per_id']);
    $result = $this->Obj->Add($array);
    
    $lastId = $this->Obj->GetLastId();
    $dataBenefitAdded = $this->Obj->GetDataBenefitDet($lastId[0]['last_id']);

    if($array['reduced'] == 'Yes'){
      $rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambil($dataBenefitAdded[0]['durasi'],$dataBenefitAdded[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
    }elseif($array['reduced'] == 'No'){
      //no update balance benefit
    }
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataBenefit(){ 
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    $c=$this->POST['tgl_stat_year'].'-'.$this->POST['tgl_stat_mon'].'-'.$this->POST['tgl_stat_day'];
    
    $dataBenefit = $this->Obj->GetDataBenefitDet($this->POST['id']);
    $balanceBenefit = $this->Obj->GetBalanceBenefitByPegId($this->POST['idPeg']);

    $array=array('peg_id'=>$this->POST['idPeg'],'no_benefit'=>$this->POST['no_benefit'],'tgl_mulai'=>$a,'tgl_selesai'=>$b,
      'tipe'=>$this->POST['tipe'],'reduced'=>$this->POST['reduced'],'alasan'=>$this->POST['alasan'],'status'=>$this->POST['status'],'tglstat'=>$c, 'tggjwbker'=>$this->POST['tggjwbker'],'pggjwbsmnt'=>$this->POST['pggjwbsmnt'],
      'pggjwbsmntk'=>$this->POST['pggjwbsmntk'],'per_id'=>$balanceBenefit[0]['per_id'],'id'=>$this->POST['id']);  
    
    $result = $this->Obj->Update($array);
    $dataBenefitUpdated = $this->Obj->GetDataBenefitDet($this->POST['id']);
    
    if($dataBenefit[0]['reduced'] == 'Yes'){
      if($array['reduced'] == 'Yes'){
        $rs_balance_benefit_return = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefit[0]['durasi'],$dataBenefit[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
        $rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambilTambah($dataBenefitUpdated[0]['durasi'],$dataBenefitUpdated[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
      }elseif($array['reduced'] == 'No'){
        $rs_balance_benefit_return = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefit[0]['durasi'],$dataBenefit[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
        #$rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambilTambah($dataBenefitUpdated[0]['durasi'],$dataBenefitUpdated[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
      }
    } elseif($dataBenefit[0]['reduced'] == 'No') {
      if($array['reduced'] == 'Yes'){
        $rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambilTambah($dataBenefitUpdated[0]['durasi'],$dataBenefitUpdated[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
        //$rs_balance_benefit_return = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefit[0]['durasi'],$dataBenefit[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
      }elseif($array['reduced'] == 'No'){
        //no update balance benefit
        //$rs_balance_benefit_return = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefitUpdated[0]['durasi'],$dataBenefitUpdated[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
        //$rs_balance_benefit = $this->Obj->UpdateBalanceBenefitDiambilKurang($dataBenefit[0]['durasi'],$dataBenefit[0]['durasi'],$this->POST['idPeg'],$balanceBenefit[0]['per_id']);
      }
    }
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
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
           Messenger::Instance()->Send('data_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDataBenefit();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->decId;
        if($rs_update == true){
           Messenger::Instance()->Send('data_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_benefit', 'historyDataBenefit', 'view', 'html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('data_benefit', 'dataBenefit', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_benefit', 'dataBenefit', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>