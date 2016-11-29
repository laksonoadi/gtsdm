<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewInputSatuanKerja extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/satuan_kerja/template');
      $this->SetTemplateFile('input_satuan_kerja.html');
   }
   
   function PrepareData(){
  // print_r($_GET['smpn']);
      //get data dari detail data atau dari form
      if ($_GET['satkerId'] != '') {
         $satkerId = $_GET['satkerId']->Integer()->Raw();
         $ObjSatker = new SatuanKerja();
		 $list=$ObjSatker->GetListSatKer();
         $rs = $ObjSatker->GetSatKerDetail($satkerId);//print_r($rs);
         $skts = $ObjSatker->GetListSkts();
		 $level=explode('.',$rs['satkerLevel']);
		 if(count($level)==1){
			   $induk=0; 
		}else{
		   for($i=0;$i<(count($level)-1);$i++){
				   //echo '['.$i.']=>'.$level[$i];
				   if($i==0){
				      $induk=$level[0];
				   }elseif($i>0){
				      $induk=$list[$i]['satkerLevel'];
				   }
		    }
//print_r($induk);
		$indukId=$ObjSatker->GetSatKerLevel($induk);//print_r($indukId);
      }
     $this->Data = array('id'=>$rs['satkerId'],'level'=>$indukId['satkerId'],'unit'=>$rs['satkerUnitId'],'nama'=>$rs['satkerNama'],'level_id'=>$rs['satkerLevel']);	  
   }else {
         $msg = Messenger::Instance()->Receive(__FILE__);
         $post = $msg[0][0];
         $this->Pesan = $msg[0][1];
         $this->Op = $post['op'];
         $this->Data = array('id'=>$post['satkerId'],'level'=>$post['satkerLevel'],'unit'=>$post['satkerUnitId'],'nama'=>$post['satkerNama'],'level_id'=>$post['level_id']);   
      }
   }
   
   function ProcessRequest() {
    $this->PrepareData();
	  $ObjSatker = new SatuanKerja();
	  $listInduk = $ObjSatker->GetComboSatKer();
	  $arrTpstr = $ObjSatker->GetComboTipeStruktural();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satkerLevel', 
         array('satkerLevel',$listInduk,$this->Data['level'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
	  $listUnit = $ObjSatker->GetComboUnit();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satkerUnitId', 
         array('satkerUnitId',$listUnit,$this->Data['unit'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tpstr', array('tpstr', $arrTpstr, $skts[0]['sktsTpstrId'], 'false', ' style="width:200px;" '), Messenger::CurrentRequest);
	  $return = $this->Data;
	  return $return;
   }
   
   function ParseTemplate($data = NULL) {
      if (isset ($this->Pesan)) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      }
	  
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('satuan_kerja', 'InputSatuanKerja', 'do', 'html'));
	  if (($_REQUEST['op']=='add') || ($this->Op == 'add')) {
         $this->mrTemplate->AddVar('content', 'OPERASI', 'add');
         $tambah="Tambah";
      } else {
         $this->mrTemplate->AddVar('content', 'OPERASI', 'edit');
         $tambah="Ubah";     
      }
	  
	  $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
	  $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN KERJA');
    $this->mrTemplate->AddVar('content', 'ID', $this->Data['id']);
    $this->mrTemplate->AddVar('content', 'NAMA_UNIT', $this->Data['nama']);
    $this->mrTemplate->AddVar('content', 'LEVEL_ID', $this->Data['level_id']);
   }
}

?>