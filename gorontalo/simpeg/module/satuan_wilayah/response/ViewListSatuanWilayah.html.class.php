<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_wilayah/business/satuan_wilayah.class.php';

class ViewListSatuanWilayah extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/satuan_wilayah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_list_satuan_wilayah.html');
   }
   
   function ProcessRequest() {
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
	  
	  $Obj = new SatuanWilayah();
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
    $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'UNIT AREA REFERENCE');
         $label = "Unit Area Reference";
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN WILAYAH');
         $label = "Referensi Satuan Wilayah";  
     }
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'ListSatuanWilayah', 'view', 'html') );
	  $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html') );
	  $this->mrTemplate->AddVar('content', 'INPUT', $data['filter']['input']);
	  $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html').'&op=add&smpn='.'drlist');
	  $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_wilayah|deleteSatuanWilayah|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_wilayah|satuanWilayah|view|html').
         "&label=".Dispatcher::Instance()->Encrypt($label);
	  if(empty($data['list'])){
	    $this->mrTemplate->AddVar('view_satwil', 'IS_EMPTY', 'YES');
	  }else{
	     $this->mrTemplate->AddVar('view_satwil', 'IS_EMPTY', 'NO');
		 $no = 1;
		 //print_r($data['list']);
		 foreach($data['list'] as $value){
		    if ($no % 2 != 0) {
                  $this->mrTemplate->AddVar('list_satwil', 'CLASS_NAME', 'table-common-even');
               } else {
                  $this->mrTemplate->AddVar('list_satwil', 'CLASS_NAME', '');
               }
			 $this->mrTemplate->AddVar('list_satwil', 'NUMBER', $no);
			 $this->mrTemplate->AddVars('list_satwil',$value,'' );
			 $this->mrTemplate->AddVar('list_satwil', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html').'&satwilId='.$value['satwilId'].'&smpn='.'drlist');
			 $this->mrTemplate->AddVar('list_satwil', 'URL_DELETE', $url_delete.
		       "&id=".Dispatcher::Instance()->Encrypt($value['satwilId']).
               "&dataName=".Dispatcher::Instance()->Encrypt($value['satwilNama']));
			   $this->mrTemplate->AddVar('satwil_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'deleteSatuanWilayah', 'do', 'html'));
			 $this->mrTemplate->parseTemplate('list_satwil', 'a');
			 $no++;
		 }
	  }
   }
}
?>