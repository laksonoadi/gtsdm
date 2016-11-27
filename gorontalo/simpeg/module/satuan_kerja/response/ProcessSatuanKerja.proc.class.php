<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class Process{
   var $POST;
   var $Pesan = array();
   var $user;
   
   function __construct() {
      $this->Obj = new SatuanKerja();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	}
	
	function SetPost($param){
      $this->POST = $param->AsArray();
   }
   
   function GetSatkerDetail($parentId){
      $return = $this->Obj->GetSatKerDetail($parentId);
	  return $return;
   }
   
   function GetSatkerList(){
      $return = $this->Obj->GetListSatKer();
	  return $return;
   }
   
   function GetLevelFromParent($parentId){
      $level= $this->GetSatkerDetail($parentId);
	  $parentLevel=$level['satkerLevel'];
      $result = $this->Obj->GetSatKerByLevel($parentLevel);
	  $tingkat=explode('.',$parentLevel);
	  for($i=0;$i<count($result);$i++){
	     $child=explode('.',$result[$i]['satkerLevel']);
		 if(count($tingkat) == (count($child) - 1)){
		    $last[]=end($child);
		 }
	  }
	  if (sizeof($last)>0) {
	   $return = max($last);
    }
	  //print_r($last);
	  return $return;
   }
   
   function AddSatker(){
   
      $child_max = $this->GetLevelFromParent($this->POST['satkerLevel']);
	  $detail=$this->GetSatkerDetail($this->POST['satkerLevel']);
	  $parentLevel=$detail['satkerLevel'];
	  $listSatker = $this->GetSatkerList();//print_r($listSatker);
	  if(empty($parentLevel) AND empty($child_max)){
	     if(!empty($listSatker)){
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
	     if(!empty($listSatker)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel;
	  }
	  $array=array('level'=>$level,'parent'=>$this->POST['satkerLevel'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user);
	  $result = $this->Obj->Add($array);
	  if ($result){
         return $result;
	  }else 
	  {
	     return false;
	   }
	   
   }
   
   function UpdateSatker(){
      $child_max = $this->GetLevelFromParent($this->POST['satkerLevel']);
	  $detail=$this->GetSatkerDetail($this->POST['satkerLevel']);
	  $parentLevel=$detail['satkerLevel'];
	  $listSatker = $this->GetSatkerList();
	  $satkerdata = $this->Obj->GetDataSatuanKerja($this->POST['satkerId']);
	  $satkerparent = $this->Obj->GetDataSatuanKerja($this->POST['satkerLevel']);


	  $cekparent = $this->Obj->GetCekLevelParent($this->POST['satkerId'],$satkerparent['satkerLevel']);
	  if(!empty($cekparent)){
	  	$newid = '1';
	  }else{
	  	$newid = '0';
	  }
	  // print_r($cekparent);exit();
	  
	  	
	  //$child_max +=1;
	  //$level=$parentLevel.'.'.$child_max;
	  if(empty($parentLevel) AND empty($child_max)){
	     if(!empty($listSatker)){
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
	     if(!empty($listSatker)){
		    $parentLevel = $this->CompareParentLevel();
		     
          }else{
			$parentLevel = 1;
		  }
        $level=$parentLevel;
	  }
	  // UpdateWOLevel
	  if($this->POST['satkerLevel'] == $satkerdata['satkerParentId'] && $newid == '0'){
	  	$array=array('parent'=>$this->POST['satkerLevel'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user,'id'=>$this->POST['satkerId']);
	  	$result = $this->Obj->UpdateWOLevel($array);
	  }
	  else{
		  $array=array('level'=>$level,'parent'=>$this->POST['satkerLevel'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user,'id'=>$this->POST['satkerId']);
		  $result = $this->Obj->Update($array);
		}
	  if ($result){
         return $result;
	  }else 
	  {
	     return false;
	   }
   }
   
   function CompareParentLevel(){
      $listSatker = $this->GetSatkerList();
	  for($a=0;$a<count($listSatker);$a++){
			    $listLevel=explode('.',$listSatker[$a]['satkerLevel']);
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
   
   function InputSatker(){
      $tmp = ($this->POST['op'] == 'edit')? '&satkerId='.$this->POST['satkerId'] : '';
	  if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
	     $rs_add = $this->AddSatker();
		 if($rs_add === true){
		    Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html', array($this->POST,'Penambahan data berhasil'),Messenger::NextRequest);
               $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html');
		 }else {
               Messenger::Instance()->Send('satuan_kerja', 'inputSatuanKerja', 'view', 'html', array($this->POST,'Lengkapi isian data'),Messenger::NextRequest);
               $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'inputSatuanKerja', 'view', 'html');
         }
	  }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
	  		// $this->SetDebugOn();
	     $rs_update = $this->UpdateSatker();
	     // echo $this->getLastError();
		 if ($rs_update === true){
		    if($_POST['smpn'] != 'drlist'){			
				Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html', array($this->POST,'Perubahan data berhasil'),Messenger::NextRequest);
				$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html').$tmp;
			}else{
			   Messenger::Instance()->Send('satuan_kerja', 'ListSatuanKerja', 'view', 'html', array($this->POST,'Perubahan data berhasil'),Messenger::NextRequest);
			   $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'ListSatuanKerja', 'view', 'html');
			}
		 }
	  }else if (isset($this->POST['btnbatal'])) {
		 if($_POST['smpn'] != 'drlist'){
			$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html').$tmp;
		}else{
			$urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'ListSatuanKerja', 'view', 'html');
		}
      }
	  return $urlRedirect;
   }
   
   function Delete()
   {
      //print_r($this->POST);exit();
      $result = $this->Obj->Delete($this->POST);
	  if ($result){
         $msg = array(1=>'Penghapusan Data Berhasil Dilakukan.', $this->cssDone);
	  }else 
	  {
	     $msg = array(1=>'Tidak Berhasil Menghapus Data!', $this->cssFail);
	   }
	   Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html',array($this->POST,'Penghapusan data berhasil'), $msg, Messenger::NextRequest);
	   $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html');
	   return $urlRedirect;
   }

}

?>