<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/gaji_pegawai/business/AppPayrollPegawai.class.php';
class ProcessPayrollPegawai {

	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	function __construct() {
		$this->obj = new AppPayrollPegawai();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('gaji_pegawai', 'gajiPegawai', 'view', 'html');
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='data can not be deleted';
       $this->msgReqDataEmpty='All field marked with * must be filled';
      }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='data tidak dapat dihapus';
       $this->msgReqDataEmpty='Semua data bertanda * harus diisi';
      }
	}

	function Add() {
      $arrKey = array_keys($this->_POST);
      $arrValue = array_values($this->_POST);
      $idPegawai = $this->_POST['idPegawai'];
	  
      #insert gaji pegawai mst
      $jml_total = 0;
      for($p=0; $p<sizeof($arrKey); $p++) {
         if((substr($arrKey[$p], 0, 8) == 'payroll_')&&($arrKey[$p]!='payroll_8')&&($arrKey[$p]!='payroll_9')) {
            $jml_total += $arrValue[$p];
         }
      }
      $periode=$this->obj->getIdPeg($this->_POST['idPegawai']);
      $s = date('d');
	    $periode_gaji = $this->_POST['periode_tahun'].'-'.$this->_POST['periode_bulan'].'-'.'01';
	    $ada="f";
	    for ($i=0; $i<sizeof($periode); $i++){
		    $tanggal=explode("-",$periode[$i]['periode']);
  		  if (($tanggal[0]==$this->_POST['periode_tahun'])and($tanggal[1]==$this->_POST['periode_bulan'])) {
  			  $id=$periode[$i]['id'];
  			  $tanggalId=$periode[$i]['periode'];
          $ada="t"; 
  			  break;
  		  }
	    }
      
      if($this->_POST['gapok']!=""){
        $jml_total += $this->_POST['gapok'];
      }
      
      /*if($this->_POST['lainLain']!=""){
        $jml_total += $this->_POST['lainLain'];
        $aa="t";
      }else{
        $aa="f";
      }*/
      
      if($this->_POST['pendapatanLainTotal']!=""){
        $jml_total += $this->_POST['pendapatanLainTotal'];
        $aa="t";
      }else{
        $aa="f";
      }
      
      if($this->_POST['thr']!=""){
        $jml_total += $this->_POST['thr'];
        $aa="t";
      }else{
        $aa="f";
      }
      
      if($this->_POST['potongan']!=""){
        $jml_total += $this->_POST['potonganGajiTotal'];
        $aa="t";
      }else{
        $aa="f";
      }
      
      if(!empty($jml_total)) {
          if($ada == "f"){
            #$jml_total = chunk_split(base64_encode($jml_total));
            $jml_total = $jml_total;
            $insert_gaji_mst = $this->obj->AddGajiPegwaiMst($idPegawai, $jml_total, $periode_gaji);
            //print_r($insert_gaji_mst);
          }else{
            #$jml_total = chunk_split(base64_encode($jml_total));
            $jml_total = $jml_total;
            $insert_gaji_mst = $this->obj->UpdateGajiPegwaiMst($id, $jml_total);
	          if($insert_gaji_mst) { $this->obj->DeleteDetailGajiPegawai($id);}
          }
      }
      if($insert_gaji_mst) {
         $maxId =  $this->obj->GetMaxIdGajiPeg();
         $j=0;
         if(($this->_POST['gapok']!="") and ($ada == "f")){
          $this->obj->AddDetailGajiPegwaiGapok($maxId,$periode_gaji,$this->_POST['gapok']);
         }elseif(($this->_POST['gapok']!="") and ($ada == "t")){
          $this->obj->AddDetailGajiPegwaiGapok($id,$tanggalId,$this->_POST['gapok']);
         }
         for($i=2;$i<count($arrKey);$i++){
            if(substr($arrKey[$i], 0, 8) == 'payroll_') {
              $newArrKey[$j] = str_replace('payroll_','',$arrKey[$i]);
              $newArrValue[$j] = $arrValue[$i];
              $i++;
              $arrManual[$j] = $arrValue[$i];
              if(($newArrValue[$j]!=0)||($newArrValue[$j]==0)){
                if($ada == "f"){
                  $return = $this->obj->AddDetailGajiPegwai($maxId,$newArrKey[$j],$newArrValue[$j],$arrManual[$j], $periode_gaji,$idPegawai,$aa); 
                }else{
                  $return = $this->obj->AddDetailGajiPegwai($id,$newArrKey[$j],$newArrValue[$j],$arrManual[$j], $tanggalId,$idPegawai,$aa);
                }
              } #print_r($newArrValue[$j]);
              //$this->CekUpdate($maxId,$newArrKey[$j],$newArrValue[$j],$arrManual[$j], $periode_gaji,$id2,$id);
            $j++;
            }
         }
      }
      
      if ($return === true) {
	      Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
      } else {
	      Messenger::Instance()->Send('gaji_pegawai', 'gajiPegawai', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
      }
		return $this->pageView;
	}
	
	/*function CekUpdate($maxId,$newArrKey,$newArrValue,$arrManual, $periode_gaji,$id2,$id){
       $cekIdDetil = $this->obj->GetIdDetilGajiPegPer($id, $newArrKey);
       $ada2="f";
        for ($i=0; $i<sizeof($cekIdDetil); $i++){
    	    $tanggal2=explode("-",$cekIdDetil[$i]['periode2']);
    	  if (($tanggal2[0]==$this->_POST['periode_tahun'])and($tanggal2[1]==$this->_POST['periode_bulan'])) {
    		  $id2=$cekIdDetil[$i]['id2'];
          $ada2="t"; 
    		break;
    	  }
        }
        if($ada == "f"){
          $return = $this->obj->AddDetailGajiPegwai($maxId,$newArrKey,$newArrValue,$arrManual, $periode_gaji);
        }else{
          $return = $this->obj->UpdateDetailGajiPegwai($maxId,$newArrKey,$newArrValue,$arrManual, $periode_gaji,$id2);
        }   
  }*/
}
?>
