<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewQuery extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_query.html');
   }

   function ProcessRequest() {
      $rep = new Report();
      $data['itemViewed'] = 10;
      $currPage = 1;
      $startRec = 0;
      if(isset($_GET['page'])) {
         $data['currPage'] = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();  
         $startRec =($data['currPage']-1) * $data['itemViewed'];
      }
		if ($_POST['kunci']) $data['kunci'] = $_POST['kunci'];
		elseif ($_GET['kunci']) $data['kunci'] = $_GET['kunci']; 
      $totalData = $rep->GetTotalQuery($data['kunci']);
		$data['data'] = $rep->GetQueryByNama($data['kunci'], $startRec, $startRec+$data['itemViewed']);
//$startRec+1
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, 
         Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType);
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', 
         array($data['itemViewed'],$totalData, $url, $data['currPage']), Messenger::CurrentRequest);

      if ($_GET['err']!='') {
         $err = explode('|',Dispatcher::Instance()->Decrypt($_GET['err']));
         $data['actionResult']['action'] = $err[0];
         $data['actionResult']['err'] = $err[1];
      }
      return $data;
   }

   function ParseTemplate($data = NULL) {
		
      //$this->ButtonRendering();
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('report', 'query', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('report', 'addQuery', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'KUNCI', $data['kunci']);
      $pageLink = Dispatcher::Instance()->GetUrl('report', 'query', 'view', 'html').'&kunci='.$data['kunci'];

      if ($data['data'][0]['query_id']!='') {
         $this->mrTemplate->addVar('content', 'PAGENAV', $pageNav);
         $this->mrTemplate->AddVar('data_query', 'QUERY', 'ADA');
   		for ($i=0;$i<sizeof($data['data']);$i++) {
   		   if ($data['currPage']=='') $data['currPage']=1;
   		   $data['data'][$i]['NO'] = (($data['currPage']-1)*$data['itemViewed'])+$i+1;
   		   if ($i%2==0) $data['data'][$i]['class'] = 'table-common-even'; else $data['data'][$i]['class'] = '';
   			$this->mrTemplate->addVars('list_query', $data['data'][$i]);
            $idEnc=Dispatcher::Instance()->Encrypt($data['data'][$i]['query_id']);

            $url_edit=Dispatcher::Instance()->GetUrl('report', 'updateQuery', 'view', 'html') . '&id=' . $idEnc;            
            $this->mrTemplate->AddVar('list_query', 'URL_EDIT', $url_edit);

            $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html') . 
               '&id=' . $idEnc.'&message='.
               Dispatcher::Instance()->Encrypt('Anda akan menghapus query ').'&dataName='.
               Dispatcher::Instance()->Encrypt($data['data'][$i]['queryNama']).
               '&label='.Dispatcher::Instance()->Encrypt('Manajemen Query').'&urlDelete='.
               Dispatcher::Instance()->Encrypt('report|deleteQuery|do|html').'&urlReturn='.
               Dispatcher::Instance()->Encrypt('report|query|view|html');
            $this->mrTemplate->AddVar('content', 'QUERY_ID', $idEnc);
            $this->mrTemplate->AddVar('content', 'QUERY_ID', $idEnc);
            $this->mrTemplate->AddVar('list_query', 'URL_DELETE', $url_delete);   			
   			$this->mrTemplate->parseTemplate('list_query','a');
   		}  
	   } else  $this->mrTemplate->AddVar('data_query', 'QUERY', 'KOSONG');
   	
      if ($data['actionResult']['action']!=''){
         if ($data['actionResult']['err'] == "") {
            $class = 'notebox-done';
            if($data['actionResult']['action'] == 'add') 
               $isiPesan = 'Penambahan query berhasil dilakukan.';
            elseif($data['actionResult']['action'] == 'del') 
               $isiPesan = 'Penghapusan query berhasil dilakukan.';
            else 
               $isiPesan = 'Pengubahan query berhasil dilakukan.';
         } else {
            $class = 'notebox-warning';
            if($data['actionResult']['action'] == 'add') 
               $isiPesan = 'Penambahan query tidak berhasil.';
            else if($data['actionResult']['action'] == 'del') 
               $isiPesan = 'Penghapusan query tidak berhasil.';
            else 
               $isiPesan = 'Pengubahan query tidak berhasil.';
         }
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $isiPesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $class);
      }    
   }
}
?>
