<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/template_cetak/business/AppTemplateCetak.class.php';

class ViewUploadTemplateCetak extends HtmlResponse{

   function TemplateModule(){
   	$this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/template_cetak/template');
         
		$this->SetTemplateFile('upload_template_cetak.html');
   }
   
   function ProcessRequest(){
   	$idDec = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
      $msg = Messenger::Instance()->Receive(__FILE__);
      $templateObj=new AppTemplateCetak();
      $dataVarCetak=$templateObj->GetVariableCetak();

      if($idDec==""){
         $idData=Dispatcher::Instance()->Decrypt($msg[0][0]['dataId']);
      }else{
         $idData=$idDec;
      }
      $dataTemplate=$templateObj->GetTemplateCetakById($idData);

      
      $return['Pesan'] = $msg[0][1];
      $return['dataId']=$idData;
      $return['dataTemplate']=$dataTemplate;
      $return['dataVarCetak']=$dataVarCetak;
      return $return;
   }
   
   function ParseTemplate($data=NULL){
      $dataVarCetak=$data['dataVarCetak'];
      $dataTemplate=$data['dataTemplate'];
      
      if ($data['Pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
		}
      
      if($data['dataId']==""){
         $judul="Upload";
         $this->mrTemplate->AddVar('nama_template', 'TEMPLATE_EMPTY',"NO"); 
         $this->mrTemplate->SetAttribute('is_default', 'visibility', 'hidden');
         $url="addTemplateCetak";
      }else{
         $judul="Ganti";
         $this->mrTemplate->AddVar('nama_template', 'TEMPLATE_EMPTY',"YES");

         $this->mrTemplate->AddVar('content', 'TEMPLATE_PATH',$dataTemplate[0]['template_path']);
         
         $dir = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
         $file = $dir . "/doc/".$dataTemplate[0]['template_path'];
         
         if(file_exists($file)){
            $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_NOT_FOUND',"NO");
            $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_PATH',"doc/".$dataTemplate[0]['template_path']);
            $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_FILE_NAME',$dataTemplate[0]['template_path']);
         }else{
            $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_NOT_FOUND',"YES");
            $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_FILE_NAME',$dataTemplate[0]['template_path']);     
         }
         
         if($dataTemplate[0]['template_is_default']=="Ya"){
            $this->mrTemplate->SetAttribute('is_default', 'visibility', 'visible');
         }else{
            $this->mrTemplate->SetAttribute('is_default', 'visibility', 'hidden');
         }
         $url="updateTemplateCetak";
      }
      
      $URL_ACTION=Dispatcher::Instance()->GetUrl('template_cetak', $url, 'do', 'html');
      
      $this->mrTemplate->AddVar('content', 'URL_ACTION',$URL_ACTION);
      $this->mrTemplate->AddVar('content', 'JUDUL',$judul);
      $this->mrTemplate->AddVar('content', 'DATA_ID',$data['dataId']);
      
          
  
      
      $this->mrTemplate->AddVar('content', 'TEMPLATE_NAMA',$dataTemplate[0]['template_nama']);
        
      
      $this->mrTemplate->AddVar('is_default', 'URL_CONTOH_TEMPLATE','module/template_cetak/contoh_file/contoh_'.$dataTemplate[0]['template_path']);     
      
      $this->mrTemplate->AddVar('is_default', 'CONTOH_TEMPLATE','contoh_'.$dataTemplate[0]['template_path']);
      
      
      
      for ($i=0; $i<sizeof($dataVarCetak); $i++) { 
         $no = $i+1;
         $dataVarCetak[$i]['number'] = $no;
         
         if ($no % 2 != 0) 
               $dataVarCetak[$i]['class_name'] = 'table-common-even';
         else 
               $dataVarCetak[$i]['class_name'] = '';
         
         $this->mrTemplate->AddVars('data_template_cetak_item', $dataVarCetak[$i],'TPL_');
         $this->mrTemplate->parseTemplate('data_template_cetak_item', 'a');	 
      }
   }
}
?>