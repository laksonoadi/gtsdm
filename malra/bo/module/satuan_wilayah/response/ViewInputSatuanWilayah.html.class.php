<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_wilayah/business/satuan_wilayah.class.php';

class ViewInputSatuanWilayah extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/satuan_wilayah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('input_satuan_wilayah.html');
   }
   
   function PrepareData(){
  // print_r($_GET['smpn']);
      //get data dari detail data atau dari form
      if ($_GET['satwilId'] != '') {
         $satwilId = $_GET['satwilId']->Integer()->Raw();
         $ObjSatwil = new SatuanWilayah();
		 $list=$ObjSatwil->GetListSatWil();
         $rs = $ObjSatwil->GetSatWilDetail($satwilId);//print_r($rs);
		 $level=explode('.',$rs['satwilLevel']);
		 if(count($level)==1){
			   $induk=0; 
		}else{
		   for($i=0;$i<(count($level)-1);$i++){
				   //echo '['.$i.']=>'.$level[$i];
				   if($i==0){
				      $induk=$level[0];
				   }elseif($i>0){
				      $induk=$list[$i]['satwilLevel'];
				   }
		    }
//print_r($induk);
		$indukId=$ObjSatwil->GetSatWilLevel($induk);//print_r($indukId);
      }
     $this->Data = array('id'=>$rs['satwilId'],'level'=>$indukId['satwilId'],'kode'=>$rs['satwilKode'],'nama'=>$rs['satwilNama'],'level_id'=>$rs['satwilLevel']);	  
   }else {
         $msg = Messenger::Instance()->Receive(__FILE__);
         $post = $msg[0][0];
         $this->Pesan = $msg[0][1];
         $this->Op = $post['op'];
         $this->Data = array('id'=>$post['satwilId'],'level'=>$post['satwilLevel'],'kode'=>$post['satwilKode'],'nama'=>$post['satwilNama'],'level_id'=>$post['level_id']);   
      }
   }
   
   function ProcessRequest() {
      $this->PrepareData();
	  $ObjSatwil = new SatuanWilayah();
	  $listInduk = $ObjSatwil->GetComboSatWil();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satwilLevel', 
         array('satwilLevel',$listInduk,$this->Data['level'],'false',' style="width:200px;"'), Messenger::CurrentRequest);
	  
	  $return = $this->Data;
	  return $return;
   }
   
   function ParseTemplate($data = NULL) {
      if (isset ($this->Pesan)) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      }
	  
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'do', 'html'));
	  
	  $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
     if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'UNIT AREA REFERENCE');
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN WILAYAH');  
     }
    
    if (($_REQUEST['op']=='add') || ($this->Op == 'add')) {
         $this->mrTemplate->AddVar('content', 'OPERASI', 'add');
         if ($buttonlang=='eng'){
             $tambah="Add";
         }else{
             $tambah="Tambah";  
         }
         
      } else {
         $this->mrTemplate->AddVar('content', 'OPERASI', 'edit');
         if ($buttonlang=='eng'){
             $tambah="Update";
         }else{
             $tambah="Ubah";  
         }
             
      }
	  
	  $this->mrTemplate->AddVar('content', 'JUDUL', $tambah);
      $this->mrTemplate->AddVar('content', 'ID', $this->Data['id']);
      $this->mrTemplate->AddVar('content', 'NAMA', $this->Data['nama']);
	  $this->mrTemplate->AddVar('content', 'KODE', $this->Data['kode']);
	  $this->mrTemplate->AddVar('content', 'LEVEL_ID', $this->Data['level_id']);
   }
}

?>