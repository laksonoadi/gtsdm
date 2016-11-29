<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/persetujuan_cuti/business/cuti.class.php';

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
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'appDataCuti', 'view', 'html');
    $this->pageInput = Dispatcher::Instance()->GetUrl('persetujuan_cuti', 'inputAppCuti', 'view', 'html');
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
      $return = $this->pageView;
      return $return;
    } 
    if(trim($this->POST['peg_label_1']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['peg_label_2']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('persetujuan_cuti', 'inputAppCuti', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageInput;
      if (($this->POST['op']=="edit") and (isset($this->POST['idCuti']))){ 
      $return .= "&dataId2=".$this->POST['idCuti'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function AddDataCuti(){
    $array=array('id'=>$this->POST['idCuti'],'idsatker1'=>$this->POST['satId1'],'idsatker2'=>$this->POST['satId2'],'pegId1'=>$this->POST['pegId1'],
      'pegId2'=>$this->POST['pegId2'],'status1'=>$this->POST['status1'],'status2'=>$this->POST['status2']);
    
    if($this->POST['status1']==$this->POST['status2']){
      $tes="yes";
    }else{
      $tes="no";
    }
  
    $result = $this->Obj->Add($array,$tes);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	/*function UpdateDataCuti(){ 
    $a=$this->POST['tgl_mulai_year'].'-'.$this->POST['tgl_mulai_mon'].'-'.$this->POST['tgl_mulai_day'];
    $b=$this->POST['tgl_selesai_year'].'-'.$this->POST['tgl_selesai_mon'].'-'.$this->POST['tgl_selesai_day'];
    
    $array=array('no_kartu'=>$this->POST['no_kartu'],'hubungan'=>$this->POST['hubungan'],'nama'=>$this->POST['nama'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'tgl_selesai'=>$b,'id_lain'=>$this->POST['id_lain'],
      'kerja'=>$this->POST['kerja'],'ket'=>$this->POST['ket'],'tunjangan'=>$this->POST['tunjangan'],'mati'=>$this->POST['mati'],
      'id'=>$this->POST['idIstri']);
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }*/
	
	function InputDataCuti(){
      $check = $this->Check();
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_add = $this->AddDataCuti();
        $return = $this->pageView;
        if($rs_add == true){
           Messenger::Instance()->Send('persetujuan_cuti', 'appDataCuti', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('persetujuan_cuti', 'appDataCuti', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }/*else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDatistri();
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_update == true){
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,'Update Data Berhasil Dilakukan',$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,'Update Data Gagal Dilakukan',$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }*/
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('persetujuan_cuti', 'appDataCuti', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('persetujuan_cuti', 'appDataCuti', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    return $return;
   }
}

?>