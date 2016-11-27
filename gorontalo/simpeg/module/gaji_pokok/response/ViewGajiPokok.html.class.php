<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/gaji_pokok/business/gaji_pokok.class.php';
   
class ViewGajiPokok extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/gaji_pokok/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_gaji_pokok.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pangId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
      $Obj = new GajiPokok();
      $dataGapok = $Obj->GetGajiPokok($pangId);
      $namaPang = $Obj->GetNamaPang($pangId);
      $result1=$namaPang[0];
      $return['input']['nama'] = $result1['nama'];
        if ($_GET['dataId2'] != '') {
          $gapokId= Dispatcher::Instance()->Decrypt($_GET['dataId2']);
          $dataGapokDet = $Obj->GetGapokDet($pangId,$gapokId);
          $result=$dataGapokDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['masa'] = $result['masa'];
            //$return['input']['gapok'] = $result['gapok'];
            $return['input']['komp_label'] = $result['komp_label'];
            $return['input']['komp_id'] = $result['komp_id'];
          }else{
            $return['input']['id'] = '';
            $return['input']['masa'] = '';
            //$return['input']['gapok'] = '';
            $return['input']['komp_label'] = '';
            $return['input']['komp_id'] = '';
          }
        }
      }
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataGapok'] = $dataGapok;
  		$return['dataGapokDet'] = $dataGapokDet;
  		$return['idPangkat'] = $pangId;
  		//$return['namaPangkat'] = $namaPang1;
  		
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataGapok = $data['dataGapok'];
      //$dataGapokDet = $data['dataGapokDet'];
      $dat = $data['input'];
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
  	  if ($lang=='eng'){
  	      $label = 'Basic Salary Reference';
  	      $this->mrTemplate->AddVar('content', 'TITLE', 'BASIC SALARY REFERENCE');
  	      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
  	      $this->mrTemplate->AddVar('content', 'LABEL_RESET', isset($_GET['dataId2']) ? 'Cancel' : 'Reset');
      }else{
          $label = 'Data Gaji Pokok';
          $this->mrTemplate->AddVar('content', 'TITLE', 'Referensi Gaji Pokok');
          $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');
          $this->mrTemplate->AddVar('content', 'LABEL_RESET', isset($_GET['dataId2']) ? 'Batal' : 'Reset');
      }
         
      $this->mrTemplate->AddVar('content', 'URL_POPUP_KOMP', Dispatcher::Instance()->GetUrl('gaji_pokok', 'popupGaji', 'view', 'html')); 
      
      if(isset($_GET['dataId2'])){
        $op="edit";
      }else{
        $op="add";
      }
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'PANG', '('.$data['idPangkat'].' - '.$dat['nama'].')'); 
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('gaji_pokok', 'pangkat', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('gaji_pokok', 'inputGajiPokok', 'do', 'html')); 
            
      $this->mrTemplate->AddVar('content', 'ID1', $data['idPangkat']);
      $this->mrTemplate->AddVar('content', 'ID2', $dat['id']);
      $this->mrTemplate->AddVar('content', 'MASA', $dat['masa']);
      //$this->mrTemplate->AddVar('content', 'GAPOK', $dat['gapok']);
      $this->mrTemplate->AddVar('content', 'KOMP_LABEL', $dat['komp_label']);
      $this->mrTemplate->AddVar('content', 'KOMP', $dat['komp_id']);
      
      if (empty($dataGapok)) {
  			$this->mrTemplate->AddVar('gaji_pokok', 'DATA_EMPTY', 'YES');
  		} else {
  			$this->mrTemplate->AddVar('gaji_pokok', 'DATA_EMPTY', 'NO');
  
  //mulai bikin tombol delete
  			$total=0;
        $start=1;
  			$idEnc2 = Dispatcher::Instance()->Encrypt($data['idPangkat']);
        for ($i=0; $i<count($dataGapok); $i++) {
  				$no = $i+$start;
  				$dataGapok[$i]['number'] = $no;
  				if ($no % 2 != 0) {
            $dataGapok[$i]['class_name'] = 'table-common-even';
          }else{
            $dataGapok[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataGapok)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
  
  				$idEnc = Dispatcher::Instance()->Encrypt($dataGapok[$i]['id']);
          
          $urlAccept = 'gaji_pokok|deleteGajiPokok|do|html-dataId-'.$idEnc2;
          $urlKembali = 'gaji_pokok|gajiPokok|view|html-dataId-'.$idEnc2;
          
          $dataName = $dataGapok[$i]['id'];
          $dataGapok[$i]['URL_DELETE'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlKembali.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
          $dataGapok[$i]['URL_EDIT'] = Dispatcher::Instance()->GetUrl('gaji_pokok','gajiPokok', 'view', 'html').'&dataId2='. $idEnc.'&dataId='. $idEnc2;
          
          $dataGapok[$i]['PANGKAT'] = $data['idPangkat'].' - '.$dat['nama'];
          $dataGapok[$i]['KOMP'] = $dataGapok[$i]['komp1'].' - '.$dataGapok[$i]['komp2'];
          
  				$this->mrTemplate->AddVars('gaji_pokok_item', $dataGapok[$i], 'IN_');
  				$this->mrTemplate->parseTemplate('gaji_pokok_item', 'a');	 
  			}
  		}
   }
}
  
?>
