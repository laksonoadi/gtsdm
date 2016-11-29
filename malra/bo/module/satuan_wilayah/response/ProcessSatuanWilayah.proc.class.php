<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_wilayah/business/satuan_wilayah.class.php';

class Process{
   var $POST;
   var $Pesan = array();
   var $user;
   
   function __construct() {
      $this->Obj = new SatuanWilayah();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
     }
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
   }
   
   function GetSatwilDetail($parentId){
      $return = $this->Obj->GetSatWilDetail($parentId);
	  return $return;
   }
   
   function GetSatwilList(){
      $return = $this->Obj->GetListSatWil();
	  return $return;
   }
   
   function GetLevelFromParent($parentId){
      $level= $this->GetSatwilDetail($parentId);
	  $parentLevel=$level['satwilLevel'];
      $result = $this->Obj->GetSatWilByLevel($parentLevel);
	  $tingkat=explode('.',$parentLevel);
	  for($i=0;$i<count($result);$i++){
	     $child=explode('.',$result[$i]['satwilLevel']);
		 if(count($tingkat) == (count($child) - 1)){
		    $last[]=end($child);
		 }
	  }
	  $return = max($last);//print_r($last);exit();
	  return $return;
   }
   
   function AddSatwil(){
   
      $child_max = $this->GetLevelFromParent($this->POST['satwilLevel']);
	  $detail=$this->GetSatwilDetail($this->POST['satwilLevel']);
	  $parentLevel=$detail['satwilLevel'];
	  $listSatwil = $this->GetSatwilList();//print_r($listSatker);
	  if(empty($parentLevel) AND empty($child_max)){
	     if(!empty($listSatwil)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel; 		  
	  }elseif(!empty($parentLevel)){
		  if(!empty($child_max)){
		     $child_max = $child_max + 1;
		     
		  }else{
		     $child_max = 1;
		  }
		  $level=$parentLevel.'.'.$child_max;
	  }elseif(empty($parentLevel)){
	     if(!empty($listSatwil)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel;
	  }
	  $array=array('level'=>$level,'kode'=>$this->POST['satwilKode'],'nama'=>$this->POST['satwilNama'],'user'=>$this->user);
	  $result = $this->Obj->Add($array);
	  if ($result){
         return $result;
	  }else 
	  {
	     return false;
	   }
	   
   }
   
   function UpdateSatwil(){
      $child_max = $this->GetLevelFromParent($this->POST['satwilLevel']);
	  $detail=$this->GetSatwilDetail($this->POST['satwilLevel']);
	  $parentLevel=$detail['satwilLevel'];
	  $listSatwil = $this->GetSatwilList();
	  //$child_max +=1;
	  //$level=$parentLevel.'.'.$child_max;
	  if(empty($parentLevel) AND empty($child_max)){
	     if(!empty($listSatwil)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel; 		  
	  }elseif(!empty($parentLevel)){
		  if(!empty($child_max)){
		     $child_max = $child_max + 1;
		     
		  }else{
		     $child_max = 1;
		  }
		  $level=$parentLevel.'.'.$child_max;
	  }elseif(empty($parentLevel)){
	     if(!empty($listSatwil)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel;
	  }
	  $array=array('level'=>$level,'kode'=>$this->POST['satwilKode'],'nama'=>$this->POST['satwilNama'],'user'=>$this->user,'id'=>$this->POST['satwilId']);
	  $result = $this->Obj->Update($array);
	  if ($result){
         return $result;
	  }else 
	  {
	     return false;
	   }
   }
   
   function CompareParentLevel(){
      $listSatwil = $this->GetSatwilList();
	  for($a=0;$a<count($listSatwil);$a++){
			    $listLevel=explode('.',$listSatwil[$a]['satwilLevel']);
				if(count($listLevel)==1){ 
				   if($listLevel[0] !=NULL){
				      $max_value = $listLevel[0];
					  $parentLevel = $max_value+1;
				   }else{
				      $parentLevel = 1;
				   }
				}
			 }
	  return $parentLevel;
   }
   
   function InputSatwil(){
      $tmp = ($this->POST['op'] == 'edit')? '&satwilId='.$this->POST['satwilId'] : '';
	  if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
	     $rs_add = $this->AddSatwil();
		 if($rs_add === true){
		    Messenger::Instance()->Send('satuan_wilayah', 'SatuanWilayah', 'view', 'html', array($this->POST,$this->msgAddSuccess),Messenger::NextRequest);
               $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html');
		 }else {
               Messenger::Instance()->Send('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html', array($this->POST,$this->msgReqDataEmpty),Messenger::NextRequest);
               $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html');
         }
	  }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
	     $rs_update = $this->UpdateSatwil();
		 if ($rs_update === true){
		    if($_POST['smpn'] != 'drlist'){			
				Messenger::Instance()->Send('satuan_wilayah', 'SatuanWilayah', 'view', 'html', array($this->POST,$this->msgUpdateSuccess),Messenger::NextRequest);
				$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html').$tmp;
			}else{
			   Messenger::Instance()->Send('satuan_wilayah', 'ListSatuanWilayah', 'view', 'html', array($this->POST,$this->msgUpdateFail),Messenger::NextRequest);
			   $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'ListSatuanWilayah', 'view', 'html');
			}
		 }
	  }else if (isset($this->POST['btnbatal'])){
		 if($_POST['smpn'] != 'drlist'){
			$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html').$tmp;
		}else{
			$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'ListSatuanWilayah', 'view', 'html');
		}
      }
	  return $urlRedirect;
   }
   
   function Delete()
   {
      //print_r($this->POST);exit();
      $result = $this->Obj->Delete($this->POST);
	  if ($result){
         $msg = array(1=>$this->msgDeleteSuccess, $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>$this->msgDeleteFail, $this->cssFail);
	   }
	   Messenger::Instance()->Send('satuan_wilayah', 'SatuanWilayah', 'view', 'html',array($this->POST,$this->msgDeleteSuccess), $msg, Messenger::NextRequest);
	   $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html');
	   return $urlRedirect;
   }

}

?>