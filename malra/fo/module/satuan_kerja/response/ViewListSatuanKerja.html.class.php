<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewListSatuanKerja extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/satuan_kerja/template');
      $this->SetTemplateFile('view_list_satuan_kerja.html');
   }
   
   function ProcessRequest() {
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
	  
	  $Obj = new SatuanKerja();
	  $filter = array();
	  if (isset($_POST['btncari'])) $filter = $_POST->AsArray();
      elseif (isset($_GET['page']) && is_array($this->Data)) $filter = $this->Data;
	  Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::NextRequest);
      $return['filter'] = $filter;//print_r($filter);
	  $totalData = $Obj->GetCount($filter);
	  $itemViewed = 20;
	  if (isset($_GET['page'])) $currPage = $_GET['page']->Integer()->Raw();
      if (!isset($currPage) OR $currPage < 1) $currPage = 1;
      $startRec = ($currPage - 1) * $itemViewed;
	  
	  $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType);
	  Messenger::Instance()->SendToComponent('paging', 'paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage), Messenger::CurrentRequest);
      $return['start'] = $startRec+1;
	  if ($totalData > 0) $return['list'] = $Obj->GetDataSearch($startRec, $itemViewed, $filter);
	  else $return['list']=array();
	  
	  return $return;
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('satuan_kerja', 'ListSatuanKerja', 'view', 'html') );
	  $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html') );
	  $this->mrTemplate->AddVar('content', 'INPUT', $data['filter']['input']);
	  $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('satuan_kerja', 'inputSatuanKerja', 'view', 'html').'&op=add&smpn='.'drlist');
	  $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_kerja|deleteSatuanKerja|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_kerja|satuanKerja|view|html').
         "&label=".Dispatcher::Instance()->Encrypt('Referensi Satuan Kerja');
	  if(empty($data['list'])){
	    $this->mrTemplate->AddVar('view_satker', 'IS_EMPTY', 'YES');
	  }else{
	     $this->mrTemplate->AddVar('view_satker', 'IS_EMPTY', 'NO');
		 $no = 1;
		 //print_r($data['list']);
		 foreach($data['list'] as $value){
		    if ($no % 2 != 0) {
                  $this->mrTemplate->AddVar('list_satker', 'CLASS_NAME', 'table-common-even');
               } else {
                  $this->mrTemplate->AddVar('list_satker', 'CLASS_NAME', '');
               }
			 $this->mrTemplate->AddVar('list_satker', 'NUMBER', $no);
			 $this->mrTemplate->AddVars('list_satker',$value,'' );
			 $this->mrTemplate->AddVar('list_satker', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_kerja','inputSatuanKerja', 'view','html').'&satkerId='.$value['satkerId'].'&smpn='.'drlist');
			 $this->mrTemplate->AddVar('list_satker', 'URL_DELETE', $url_delete.
		       "&id=".Dispatcher::Instance()->Encrypt($value['satkerId']).
               "&dataName=".Dispatcher::Instance()->Encrypt($value['satkerNama']));
			   $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_kerja', 'deleteSatuanKerja', 'do', 'html'));
			 $this->mrTemplate->parseTemplate('list_satker', 'a');
			 $no++;
		 }
	  }
   }
}
?>