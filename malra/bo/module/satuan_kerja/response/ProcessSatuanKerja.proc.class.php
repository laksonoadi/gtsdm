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
   
      $child_max = $this->GetLevelFromParent($this->POST['satkerParentId']);
	  $detail=$this->GetSatkerDetail($this->POST['satkerParentId']);
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
	  $array=array('level'=>$level,'parent'=>$this->POST['satkerParentId'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user);
	  $result = $this->Obj->Add($array);
	  if ($result){
         return $result;
	  }else 
	  {
	     return false;
	   }
	   
   }
   
   function UpdateSatker(){
      $child_max = $this->GetLevelFromParent($this->POST['satkerParentId']);
	  $detail=$this->GetSatkerDetail($this->POST['satkerParentId']);
	  $parentLevel=$detail['satkerLevel'];
	  $listSatker = $this->GetSatkerList();
	  $satkerdata = $this->Obj->GetDataSatuanKerja($this->POST['satkerId']);
	  $satkerparent = $this->Obj->GetDataSatuanKerja($this->POST['satkerParentId']);


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
	  if($this->POST['satkerParentId'] == $satkerdata['satkerParentId'] && $newid == '0'){
	  	$array=array('parent'=>$this->POST['satkerParentId'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user,'id'=>$this->POST['satkerId']);
	  	$result = $this->Obj->UpdateWOLevel($array);
	  }
	  else{
		  $array=array('level'=>$level,'parent'=>$this->POST['satkerParentId'],'unit'=>$this->POST['satkerUnitId'],'nama'=>$this->POST['satkerNama'],'struktural'=>$this->POST['tpstr'],'user'=>$this->user,'id'=>$this->POST['satkerId']);
		  $result = $this->Obj->Update($array);
		  $result = $result && $this->Obj->UpdateDescendants($satkerdata['satkerLevel'], $level, $this->user);
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
   
   function MoveSatker() {
        if(empty($this->POST['satkerIds']) || !is_array($this->POST['satkerIds']) || !isset($this->POST['parentId'])) {
            $result = FALSE;
        } else {
            $result = TRUE;
        }
        
        $this->Obj->StartTrans();
        if($result) {
            $parent = $this->Obj->GetSatkerDetail($this->POST['parentId']);
            // Look for available index
            $parentsChildren = $this->Obj->GetSatkerByParentId($parent['satkerId']);
            $parentsChildrenIds = array();
            $index = array();
            foreach($parentsChildren as $parentsChild) {
                if(strpos($parentsChild['satkerLevel'], '.') === FALSE) {
                    $index[] = $parentsChild['satkerLevel'];
                } else {
                    $index[] = substr($parentsChild['satkerLevel'], strrpos($parentsChild['satkerLevel'], '.') + 1);
                }
                $parentsChildrenIds[] = $parentsChild['satkerId'];
            }
            $index = max($index) + 1;
            
            $changed = array();
            // Eliminate the ones that doesn't change parent
            foreach($this->POST['satkerIds'] as $k => $v) {
                if(!in_array($v, $parentsChildrenIds)) {
                    $changed[] = $v;
                }
            }
            
            // Start updating
            foreach($changed as $id) {
                $detail = $this->Obj->GetSatKerDetail($id);
                if(empty($detail)) {
                    $result = FALSE;
                    break;
                }
                
                $level = (!empty($parent['satkerLevel']) ? $parent['satkerLevel'].'.'.$index : $index);
                $parent_id = (!empty($parent['satkerId']) ? $parent['satkerId'] : '0');
                // Don't change anything except the level and parent
                $params = array(
                    'level' => $level,
                    'parent' => $parent_id,
                    'unit' => $detail['satkerUnitId'],
                    'nama' => $detail['satkerNama'],
                    'struktural' => $detail['satkerStruktural'],
                    'user' => $this->user,
                    'id' => $detail['satkerId']
                );
                $result = $result && $this->Obj->Update($params);
                
                $result = $result && $this->Obj->UpdateDescendants($detail['satkerLevel'], $level, $this->user);
                
                $index++;
            }
        }
        $this->Obj->EndTrans($result);
        // $this->Obj->EndTrans(FALSE);
        // var_dump($result);exit;
        
        if($result){
            Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html', array($this->POST, 'Pemindahan data berhasil', 'notebox-done'), Messenger::NextRequest);
        } else {
            Messenger::Instance()->Send('satuan_kerja', 'moveSatuanKerja', 'view', 'html', array($this->POST, 'Pemindahan data gagal', 'notebox-warning'), Messenger::NextRequest);
        }
        return $result;
   }
   
   function Delete()
   {
      //print_r($this->POST);exit();
      $this->Obj->StartTrans();
      $check = $this->Obj->CanDelete($this->POST);
      if(is_string($check)) {
          $msg = 'Tidak bisa menghapus data.<br/>'.$check;
          $css = 'notebox-alert';
      } elseif($check === TRUE) {
          $result = $this->Obj->Delete($this->POST);
          if ($result){
             $msg = 'Penghapusan Data Berhasil Dilakukan.';
             $css = 'notebox-done';
          } else {
             $msg = 'Tidak Berhasil Menghapus Data!';
             $css = 'notebox-warning';
          }
      } else {
          $result = FALSE;
      }
       $this->Obj->EndTrans($result);
       
	   Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html',array($this->POST,$msg, $css), Messenger::NextRequest);
       if($result) {
          $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html');
       } else {
          $urlRedirect = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html').'&satkerId='.$this->POST['idDelete'];
       }
	   return $urlRedirect;
   }

}

?>