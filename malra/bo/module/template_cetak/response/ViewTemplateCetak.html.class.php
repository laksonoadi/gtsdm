<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 
   'module/template_cetak/business/AppTemplateCetak.class.php';

class ViewTemplateCetak extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/template_cetak/template');
         
		$this->SetTemplateFile('view_template_cetak.html');
	}
   
   function ProcessRequest(){
      $templateObj=new AppTemplateCetak();
      
      
      $dataTemplate=$templateObj->GetTemplateCetak();
      $totalData = $templateObj->GetCountDataTemplate();
      
   	$msg = Messenger::Instance()->Receive(__FILE__);
      
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0;
      
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
      
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', 
                                             array($itemViewed,$totalData, $url, $currPage), 
                                             Messenger::CurrentRequest);
      
      
      $return['Pesan'] = $msg[0][1];
		$return['css'] = $msg[0][2];
      $return['dataTemplate']=$dataTemplate;
      $return['start'] = $startRec+1;
      return $return;
   }
   
   function ParseTemplate($data=NULL){
      
      $dataTemplate=$data['dataTemplate'];
      
      if($data['Pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
     
      $url_upload=Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html');
      
      
      
      $this->mrTemplate->AddVar('content', 'URL_UPLOAD', $url_upload);
      
      if (empty($dataTemplate)) {
			$this->mrTemplate->AddVar('data_template_cetak', 'TEMPLATE_EMPTY', 'YES');
		} else {

      $this->mrTemplate->AddVar('data_template_cetak', 'TEMPLATE_EMPTY', 'NO');
      
         $label = "Template Cetak";
			$urlDelete = Dispatcher::Instance()->GetUrl('template_cetak', 'deleteTemplateCetak', 'do', 'html');
			$urlReturn = Dispatcher::Instance()->GetUrl('template_cetak', 'templateCetak', 'view', 'html');
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));

         for ($i=0; $i<sizeof($dataTemplate); $i++) {
         
            $no = $i+$data['start'];
            $dataTemplate[$i]['number'] = $no;
            
            if ($no % 2 != 0) 
                  $dataTemplate[$i]['class_name'] = 'table-common-even';
            else 
                  $dataTemplate[$i]['class_name'] = '';
            
            if($i == 0)
                  $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);		
                  
            if($i == sizeof($dataTemplate)-1) 
                  $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
            
            $idEnc = Dispatcher::Instance()->Encrypt($dataTemplate[$i]['template_id']);
            
            $dir = dirname(realpath($_SERVER['SCRIPT_FILENAME']));
            $file = $dir . "/doc/".$dataTemplate[$i]['template_path'];
            
            if(file_exists($file)){
               $this->mrTemplate->AddVar('tpl_nama_exist','TEMPLATE_NOT_FOUND',"NO");
               $this->mrTemplate->AddVar('tpl_nama_exist','TPL_TEMPLATE_PATH',$dataTemplate[$i]['template_path']);
               $dataTemplate[$i]['url_template'] = "doc/" . $dataTemplate[$i]['template_path'];
               $this->mrTemplate->AddVar('tpl_nama_exist','TPL_URL_TEMPLATE',$dataTemplate[$i]['url_template']);

            }else{
               $this->mrTemplate->AddVar('tpl_nama_exist', 'TEMPLATE_NOT_FOUND',"YES");
            }
         
            if($dataTemplate[$i]['template_is_default']=='Ya'){
               $this->mrTemplate->SetAttribute('is_default', 'visibility', 'hidden');
            }else{
               $this->mrTemplate->SetAttribute('is_default', 'visibility', 'visible');
            }
            
            $this->mrTemplate->AddVar('is_default','TPL_NUMBER',$dataTemplate[$i]['number']);
            $this->mrTemplate->AddVar('is_default','TPL_TEMPLATE_ID',$dataTemplate[$i]['template_id']);
            $this->mrTemplate->AddVar('is_default','TPL_TEMPLATE_NAMA',$dataTemplate[$i]['template_nama']);
            
            $dataTemplate[$i]['url_ganti'] = 
               Dispatcher::Instance()->GetUrl('template_cetak', 'uploadTemplateCetak', 'view', 'html') . 
               '&dataId=' . $idEnc;     
            $status=$dataTemplate[$i]['template_status'];
            
            $dataTemplate[$i]['url_update_status'] = 
               Dispatcher::Instance()->GetUrl('template_cetak', 'updateStatusTemplateCetak', 'do', 'html') .
               '&dataId=' . $idEnc.'&status='.$status;

            $this->mrTemplate->AddVars('data_template_cetak_item', $dataTemplate[$i],'TPL_');
            $this->mrTemplate->parseTemplate('data_template_cetak_item', 'a');	 
         }
      }   
   }
}
?>