<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/tipe_cuti/business/tipe_cuti.class.php';
   
class ViewTipeCuti extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/tipe_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_tipe_cuti.html');
   }
   
   function ProcessRequest()
   {
      $cuti = new TipeCuti;
	  //print_r(Security::Authentication()->GetCurrentUser()->GetUserName());
	  // inisialisasi messaging
		$msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      // ---------
	  $id = $_GET['id']->Integer()->Raw();
	  //if(isset($this->Data['nama'])) $return['input']['nama'] = $this->Data['nama'];
	  if(isset($_GET['id'])){
	     $result = $cuti->GetDataById($id);
		 if($result){
		   $return['input']['nama'] = $result['tipecutiNama'];
		 }else{
		    unset($_GET['id']);
		 }
	  }else{
	     $return['input']['nama']='';
	  }
	  //print_r($return['input']);
	  // inisialisasi data filter
      if (isset($_POST['cari'])){
         $return['cari'] = $_POST['cari']->Raw();
	  }
      elseif (isset($_GET['cari'])){
         $return['cari'] = $_GET['cari']->Raw();
	  } else $return['cari'] = '';
	  
	  //inisialisasi paging
	  $itemViewed = 20;
      $currPage = 1;
      $startRec = 0 ;
	  
	  if(isset($_GET['page']))
      {
         $currPage = $_GET['page']->Integer()->Raw();
         if ($currPage > 0)
            $startRec =($currPage-1) * $itemViewed;
         else $currPage = 1;
      }
	  
	  $return['start'] = $startRec+1;
	  $totalData = $cuti->GetCount($return['cari']);
	  $url = Dispatcher::Instance()->GetUrl('tipe_cuti','tipeCuti','view','html').'&cari='.$return['cari'];
	  if (isset($_GET['id'])){ 
	     $url .= '&id='.$id;
	  }
	  Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	  $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('tipe_cuti','inputTipeCuti','do','html');
	  if (isset($_GET['id'])){ 
	     $return['link']['url_action'] .= '&id='.$id;
	  }
	  $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('tipe_cuti','tipeCuti','view','html');
	  $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('tipe_cuti','tipeCuti','view','html');
	  if ($return['cari'] != ''){
	     $return['link']['url_edit'] .= '&cari='.$return['cari'];
	  }
	  if (isset($_GET['page'])){
     	  $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
	  }
	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
	  if ($lang=='eng'){
	      $labeldel=Dispatcher::Instance()->Encrypt('Leave Type Reference');
     }else{
         $labeldel=Dispatcher::Instance()->Encrypt('Data Tipe Cuti');
     }
     $return['lang']=$lang; 
	  $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('tipe_cuti|deleteTipeCuti|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('tipe_cuti|tipeCuti|view|html').
         "&label=".$labeldel;
	  $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('tipe_cuti', 'deleteTipeCuti', 'do', 'html');
	  $return['dataSheet'] = $cuti->GetData($startRec,$itemViewed,$return['cari']);//print_r($return);
	  return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
     //tentukan value judul, button dll sesuai pilihan bahasa 
     if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE TYPE REFERENCE');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Leave Type Data');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI TIPE CUTI');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Tipe Cuti');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
     } 

	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
	  $this->mrTemplate->AddVar('content', 'NAMA', $data['input']['nama']);
	  
	  // Filter Form
      $this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
      // ---------
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  }
	  $i = $data['start'];
      $link = $data['link'];
	  foreach ($data['dataSheet'] as $value)
      {
	     $data = $value;//print_r($data);
		 $data['number'] = $i;
		 $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
		 $data['url_edit'] = $link['url_edit'].'&id='.$data['tipecutiId'];
		 $data['url_delete'] = $link['url_delete'].
            "&id=".Dispatcher::Instance()->Encrypt($data['tipecutiId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['tipecutiNama']);
         $data['url_delete_js'] = $link['url_delete_js'];
		 $this->mrTemplate->AddVars('data_item', $data, '');
         $this->mrTemplate->parseTemplate('data_item', 'a');
         $i++;
	  }
   }
}
   

?>