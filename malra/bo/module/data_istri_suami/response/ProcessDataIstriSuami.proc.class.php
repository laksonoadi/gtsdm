<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_istri_suami/business/istri_suami.class.php';

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
    $this->Obj = new IstriSuami();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_istri_suami', 'dataIstriSuami', 'view', 'html');
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
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
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      $return = $this->pageView;
      $return .= "&dataId=".$this->POST['idPeg'];
      return $return;
    } 
    if ((trim($this->POST['no_kartu']) == '') or (trim($this->POST['nama']) == '')){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      if (($this->POST['op']=="edit") and (isset($this->POST['idIstri']))){ 
      $return .= "&dataId2=".$this->POST['idIstri'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  function CleanFilename($name) {
      if(!is_string($name)) {
          $name = (string) $name;
      }
      
      // Cannot start or end with a . or space
      $name = trim($name);
      $name = trim($name, '.');
      
      // Replace invalid characters with _
      $invalid = '/[^A-Za-z0-9-_.]/';
      $name = preg_replace($invalid, '_', $name);
      return $name;
  }
  
  function AddDatistri($file_akta_lahir, $file_akta_nikah, $file_akta_meninggal, $file_akta_cerai){
    $a=$this->POST['tgl_lahir_year'].'-'.$this->POST['tgl_lahir_mon'].'-'.$this->POST['tgl_lahir_day'];
    $b=$this->POST['tgl_nikah_year'].'-'.$this->POST['tgl_nikah_mon'].'-'.$this->POST['tgl_nikah_day'];
    $c=$this->POST['tgl_meninggal_year'].'-'.$this->POST['tgl_meninggal_mon'].'-'.$this->POST['tgl_meninggal_day'];
    $d=$this->POST['tgl_cerai_year'].'-'.$this->POST['tgl_cerai_mon'].'-'.$this->POST['tgl_cerai_day'];
    $e=$this->POST['tgl_npwp_year'].'-'.$this->POST['tgl_npwp_mon'].'-'.$this->POST['tgl_npwp_day'];
    
    $array=array(
      'id'=>$this->POST['idPeg'],'no_kartu'=>$this->POST['no_kartu'],'hubungan'=>$this->POST['hubungan'],
      'nama'=>$this->POST['nama'],'gelar_depan'=>$this->POST['gelar_depan'],'gelar_belakang'=>$this->POST['gelar_belakang'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'no_akta'=>$this->POST['no_akta'],'akta'=>$file_akta_lahir,
      'id_lain'=>$this->POST['id_lain'],'agama_id'=>$this->POST['agama_id'],'kerja'=>$this->POST['kerja'],'ket'=>$this->POST['ket'],'tunjangan'=>$this->POST['tunjangan'],
	  'npwp'=>$this->POST['npwp'],'tgl_npwp'=>$e,'telp'=>$this->POST['telp'],'no_askes'=>$this->POST['no_askes'],'educ'=>$this->POST['educ'],
      'tgl_nikah'=>$b,'no_akta_nikah'=>$this->POST['no_akta_nikah'],'akta_nikah'=>$file_akta_nikah,
      'mati'=>$this->POST['mati'],'tgl_meninggal'=>$c,'no_akta_meninggal'=>$this->POST['no_akta_meninggal'],'akta_meninggal'=>$file_akta_meninggal,
      'cerai'=>$this->POST['cerai'],'tgl_cerai'=>$d,'no_akta_cerai'=>$this->POST['no_akta_cerai'],'akta_cerai'=>$file_akta_cerai
    );
  
    $result = $this->Obj->Add($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDatistri($file_akta_lahir, $file_akta_nikah, $file_akta_meninggal, $file_akta_cerai){ 
    $a=$this->POST['tgl_lahir_year'].'-'.$this->POST['tgl_lahir_mon'].'-'.$this->POST['tgl_lahir_day'];
    $b=$this->POST['tgl_nikah_year'].'-'.$this->POST['tgl_nikah_mon'].'-'.$this->POST['tgl_nikah_day'];
    $c=$this->POST['tgl_meninggal_year'].'-'.$this->POST['tgl_meninggal_mon'].'-'.$this->POST['tgl_meninggal_day'];
    $d=$this->POST['tgl_cerai_year'].'-'.$this->POST['tgl_cerai_mon'].'-'.$this->POST['tgl_cerai_day'];
    $e=$this->POST['tgl_npwp_year'].'-'.$this->POST['tgl_npwp_mon'].'-'.$this->POST['tgl_npwp_day'];
    
    $array=array(
      'no_kartu'=>$this->POST['no_kartu'],'hubungan'=>$this->POST['hubungan'],
      'nama'=>$this->POST['nama'],'gelar_depan'=>$this->POST['gelar_depan'],'gelar_belakang'=>$this->POST['gelar_belakang'],
      'tmpt_lahir'=>$this->POST['tmpt_lahir'],'tgl_lahir'=>$a,'no_akta'=>$this->POST['no_akta'],'akta'=>$file_akta_lahir,
      'id_lain'=>$this->POST['id_lain'],'agama_id'=>$this->POST['agama_id'],'kerja'=>$this->POST['kerja'],'ket'=>$this->POST['ket'],'tunjangan'=>$this->POST['tunjangan'],
	  'npwp'=>$this->POST['npwp'],'tgl_npwp'=>$e,'telp'=>$this->POST['telp'],'no_askes'=>$this->POST['no_askes'],'educ'=>$this->POST['educ'],
      'tgl_nikah'=>$b,'no_akta_nikah'=>$this->POST['no_akta_nikah'],'akta_nikah'=>$file_akta_nikah,
      'mati'=>$this->POST['mati'],'tgl_meninggal'=>$c,'no_akta_meninggal'=>$this->POST['no_akta_meninggal'],'akta_meninggal'=>$file_akta_meninggal,
      'cerai'=>$this->POST['cerai'],'tgl_cerai'=>$d,'no_akta_cerai'=>$this->POST['no_akta_cerai'],'akta_cerai'=>$file_akta_cerai,
      'id'=>$this->POST['idIstri']
    );
  
    $result = $this->Obj->Update($array);
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDatistri(){
      $check = $this->Check();
      if ($check !== true) return $check;
      $peg = $this->Obj->GetDataById($this->POST['idPeg']);
      $this->POST['hubungan'] = ($peg['gender'] == 'P' ? 'Suami' : 'Istri');
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        // Nama file akta
        $path_akta = GTFWConfiguration::GetValue('application', 'akta_pasangan_path');
        
        $upload_akta_lahir = false;
        if(isset($_FILES['akta']) && $_FILES['akta']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta']['tmp_name'])) {
            $upload_akta_lahir = true;
            $file_akta_lahir = date('ymdHis').'-lahir-'.$this->CleanFilename($_FILES['akta']['name']);
        }
        $upload_akta_nikah = false;
        if(isset($_FILES['akta_nikah']) && $_FILES['akta_nikah']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_nikah']['tmp_name'])) {
            $upload_akta_nikah = true;
            $file_akta_nikah = date('ymdHis').'-nikah-'.$this->CleanFilename($_FILES['akta_nikah']['name']);
        }
        $upload_akta_meninggal = false;
        if(isset($_FILES['akta_meninggal']) && $_FILES['akta_meninggal']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_meninggal']['tmp_name'])) {
            $upload_akta_meninggal = true;
            $file_akta_meninggal = date('ymdHis').'-meninggal-'.$this->CleanFilename($_FILES['akta_meninggal']['name']);
        }
        $upload_akta_cerai = false;
        if(isset($_FILES['akta_cerai']) && $_FILES['akta_cerai']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_cerai']['tmp_name'])) {
            $upload_akta_cerai = true;
            $file_akta_cerai = date('ymdHis').'-cerai-'.$this->CleanFilename($_FILES['akta_cerai']['name']);
        }
        
        $rs_add = $this->AddDatistri($file_akta_lahir, $file_akta_nikah, $file_akta_meninggal, $file_akta_cerai);
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
            // Commit file akta
            if($upload_akta_lahir) {
                move_uploaded_file($_FILES['akta']['tmp_name'], $path_akta.$file_akta_lahir);
            }
            if($upload_akta_nikah) {
                move_uploaded_file($_FILES['akta_nikah']['tmp_name'], $path_akta.$file_akta_nikah);
            }
            if($upload_akta_meninggal) {
                move_uploaded_file($_FILES['akta_meninggal']['tmp_name'], $path_akta.$file_akta_meninggal);
            }
            if($upload_akta_cerai) {
                move_uploaded_file($_FILES['akta_cerai']['tmp_name'], $path_akta.$file_akta_cerai);
            }
            
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $detail = $this->Obj->GetDataIstriDet($this->POST['idIstri']);
        $detail = $detail[0];
        // Nama file akta
        $path_akta = GTFWConfiguration::GetValue('application', 'akta_pasangan_path');
        
        $upload_akta_lahir = false;
        if(isset($_FILES['akta']) && $_FILES['akta']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta']['tmp_name'])) {
            $upload_akta_lahir = true;
            $file_akta_lahir = date('ymdHis').'-lahir-'.$this->CleanFilename($_FILES['akta']['name']);
        } else {
            $file_akta_lahir = $detail['akta'];
        }
        $upload_akta_nikah = false;
        if(isset($_FILES['akta_nikah']) && $_FILES['akta_nikah']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_nikah']['tmp_name'])) {
            $upload_akta_nikah = true;
            $file_akta_nikah = date('ymdHis').'-nikah-'.$this->CleanFilename($_FILES['akta_nikah']['name']);
        } else {
            $file_akta_lahir = $detail['akta_nikah'];
        }
        $upload_akta_meninggal = false;
        if(isset($_FILES['akta_meninggal']) && $_FILES['akta_meninggal']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_meninggal']['tmp_name'])) {
            $upload_akta_meninggal = true;
            $file_akta_meninggal = date('ymdHis').'-meninggal-'.$this->CleanFilename($_FILES['akta_meninggal']['name']);
        } else {
            $file_akta_lahir = $detail['akta_meninggal'];
        }
        $upload_akta_cerai = false;
        if(isset($_FILES['akta_cerai']) && $_FILES['akta_cerai']['error'] == UPLOAD_ERR_OK && !empty($_FILES['akta_cerai']['tmp_name'])) {
            $upload_akta_cerai = true;
            $file_akta_cerai = date('ymdHis').'-cerai-'.$this->CleanFilename($_FILES['akta_cerai']['name']);
        } else {
            $file_akta_lahir = $detail['akta_cerai'];
        }
        
        $rs_update = $this->UpdateDatistri($file_akta_lahir, $file_akta_nikah, $file_akta_meninggal, $file_akta_cerai);
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_update == true){
            // Commit file akta
            if($upload_akta_lahir) {
                if(!empty($detail['akta'])) {
                    @unlink($path_akta.$detail['akta']);
                }
                move_uploaded_file($_FILES['akta']['tmp_name'], $path_akta.$file_akta_lahir);
            }
            if($upload_akta_nikah) {
                if(!empty($detail['akta_nikah'])) {
                    @unlink($path_akta.$detail['akta_nikah']);
                }
                move_uploaded_file($_FILES['akta_nikah']['tmp_name'], $path_akta.$file_akta_nikah);
            }
            if($upload_akta_meninggal) {
                if(!empty($detail['akta_meninggal'])) {
                    @unlink($path_akta.$detail['akta_meninggal']);
                }
                move_uploaded_file($_FILES['akta_meninggal']['tmp_name'], $path_akta.$file_akta_meninggal);
            }
            if($upload_akta_cerai) {
                if(!empty($detail['akta_cerai'])) {
                    @unlink($path_akta.$detail['akta_cerai']);
                }
                move_uploaded_file($_FILES['akta_cerai']['tmp_name'], $path_akta.$file_akta_cerai);
            }
            
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgUpdateSuccess,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgUpdateFail,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
    $deleteData = $this->Obj->Delete($this->POST['idDelete']); 
    if($deleteData == true) {
			Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_istri_suami', 'dataIstriSuami', 'view', 'html', array($this->POST,$this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>