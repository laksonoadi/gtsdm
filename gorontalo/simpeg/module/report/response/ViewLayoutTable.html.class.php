<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ViewLayoutTable extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir($this->mrConfig->mApplication['docroot'] . 'module/report/template');
      $this->SetTemplateFile('view_layout.html');
   }

   function ProcessRequest() {
      $rep = new Report();
 
      $itemViewed = 10;
      $currPage = 1;
      $startRec = 0;
      if(isset($_GET['page'])) {
         $currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();  
         $startRec =($currPage-1) * $itemViewed;
      }
		if ($_POST['kunci']) $data['kunci'] = $_POST['kunci'];
		elseif ($_GET['kunci']) $data['kunci'] = $_GET['kunci']; 
      $totalData = $rep->GetTotalLayout($data['kunci']);
		$data['data'] = $rep->GetLayoutByNama($data['kunci'], $startRec, $startRec+$itemViewed);

      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, 
         Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType);
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', 
         array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

      if ($_GET['err']!='') {
         $err = explode('|',Dispatcher::Instance()->Decrypt($_GET['err']));
         $data['actionResult']['action'] = $err[0];
         $data['actionResult']['err'] = $err[1];
      }
      $data['page'] = $currPage;
      $data['itemsViewed'] = $itemViewed;
      return $data;
   }

   function ParseTemplate($data = NULL) {
     // $this->ButtonRendering();
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('report', 'layoutTable', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('report', 'addLayoutTable', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'KUNCI', $data['kunci']);

      if ($data['data'][0]['layout_id']!='') {
         $this->mrTemplate->addVar('content', 'PAGENAV', $pageNav);
         $this->mrTemplate->AddVar('data_query', 'QUERY', 'ADA');
   		for ($i=0;$i<sizeof($data['data']);$i++) {
   		   $data['data'][$i]['NO'] = (($data['page']-1)*$data['itemsViewed'])+$i+1;
   		   if ($i%2==0) $data['data'][$i]['class'] = 'table-common-even'; else $data['data'][$i]['class'] = '';
   		   $idEnc = Dispatcher::Instance()->Encrypt($data['data'][$i]['layout_id']);
            $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html') . 
               '&id=' . $idEnc.'&message='.
               Dispatcher::Instance()->Encrypt('Anda akan menghapus layout tabel ').'&dataName='.
               Dispatcher::Instance()->Encrypt($data['data'][$i]['layout_judul']).
               '&label='.Dispatcher::Instance()->Encrypt('Manajemen Layout Tabel').'&urlDelete='.
               Dispatcher::Instance()->Encrypt('report|deleteLayoutTable|do|html').'&urlReturn='.
               Dispatcher::Instance()->Encrypt('report|layoutTable|view|html');
   		   $this->mrTemplate->AddVar('list_query', 'URL_DELETE', $url_delete);
   		   $this->mrTemplate->AddVar('list_query', 'URL_EDIT', Dispatcher::Instance()->GetUrl('report', 'updateLayoutTable', 
               'view', 'html').'&lay_id='.$idEnc);
   			$this->mrTemplate->addVars('list_query', $data['data'][$i]);
   			$this->mrTemplate->parseTemplate('list_query','a');
   		}  
	   } else  $this->mrTemplate->AddVar('data_query', 'QUERY', 'KOSONG');
      $url = Dispatcher::Instance()->GetUrl('menu', 'listLaporan', 'view', 'html').'&menu_id=933&group_menu_id=931';  
      $this->mrTemplate->addVar('body', 'navigation', '&gt; <a href="'.$url.'">Pengaturan Template</a> &gt; Layout Tabel');
      if ($data['actionResult']['action']!=''){
         if ($data['actionResult']['err'] == "") {
            $class = 'notebox-done';
            if($data['actionResult']['action'] == 'add') 
               $isiPesan = 'Penambahan tabel/grafik berhasil dilakukan.';
            elseif($data['actionResult']['action'] == 'del') 
               $isiPesan = 'Penghapusan tabel/grafik berhasil dilakukan.';
            else 
               $isiPesan = 'Pengubahan tabel/grafik berhasil dilakukan.';
         } else {
            $class = 'notebox-warning';
            if($data['actionResult']['action'] == 'add') 
               $isiPesan = 'Penambahan tabel/grafik tidak berhasil.';
            else if($data['actionResult']['action'] == 'del') 
               $isiPesan = 'Penghapusan tabel/grafik tidak berhasil.';
            else 
               $isiPesan = 'Pengubahan tabel/grafik tidak berhasil.';
         }
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $isiPesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $class);
      } 
   }
}
?>
