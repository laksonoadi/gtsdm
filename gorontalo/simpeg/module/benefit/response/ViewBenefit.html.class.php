<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/benefit/business/benefit.class.php';

class ViewBenefit extends HtmlResponse{

  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_benefit.html');
  }
  
  function ProcessRequest(){
    $benefit = new Benefit;
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
      $result = $benefit->GetDataById($id);
      if($result){
         $return['input']['nama'] = $result['benefitNama'];
         $return['input']['uraian'] = $result['benefitUraian'];
         $return['input']['pengecualian'] = $result['benefitPengecualian'];
         $return['input']['tgl'] = $result['benefitTgl'];
      }else{
         unset($_GET['id']);
      }
    }else{
      $return['input']['nama']='';
      $return['input']['uraian']='';
      $return['input']['pengecualian']='';
      $return['input']['tgl']='';
    }
    //print_r($return['input']);
    // inisialisasi data filter
    if (isset($_POST['cari'])){
      $return['cari'] = $_POST['cari']->Raw();
    }
    elseif (isset($_GET['cari'])){
      $return['cari'] = $_GET['cari']->Raw();
    }else{
      $return['cari'] = '';
    }
    
    //inisialisasi paging
    $itemViewed = 20;
    $currPage = 1;
    $startRec = 0 ;
    
    if(isset($_GET['page'])){
      $currPage = $_GET['page']->Integer()->Raw();
      if ($currPage > 0){
         $startRec =($currPage-1) * $itemViewed;
      }else{
         $currPage = 1;
      }
    }
    
    $return['start'] = $startRec+1;
    $totalData = $benefit->GetCount($return['cari']);
    $url = Dispatcher::Instance()->GetUrl('benefit','benefit','view','html').'&cari='.$return['cari'];
    if (isset($_GET['id'])){ 
      $url .= '&id='.$id;
    }
    Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    //paging end here
    
    if($_GET['id'] == ''){
      $tgl=date('Y')+5;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl', 
         array(date("Y-m-d"),'2009',$tgl,'',''), Messenger::CurrentRequest);
    } else {
      $tgl=date('Y')+5;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl', 
         array($return['input']['tgl'],'2009',$tgl,'',''), Messenger::CurrentRequest);
    }     
    $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('benefit','inputBenefit','do','html');
    if (isset($_GET['id'])){ 
      $return['link']['url_action'] .= '&id='.$id;
    }
    
    $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('benefit','benefit','view','html');
    
    $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('benefit','benefit','view','html');
    if ($return['cari'] != ''){
      $return['link']['url_edit'] .= '&cari='.$return['cari'];
    }
    if (isset($_GET['page'])){
      $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
    }
    
    //translate bahasa
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Benefit / Claim Type Data');
    }else{
      $labeldel=Dispatcher::Instance()->Encrypt('Data Benefit / Tipe Klaim');
    }
    $return['lang']=$lang; 
    
    $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
    "&urlDelete=".Dispatcher::Instance()->Encrypt('benefit|deleteBenefit|do|html').
    "&urlReturn=".Dispatcher::Instance()->Encrypt('benefit|benefit|view|html').
    "&label=".$labeldel;
    $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('benefit', 'deleteBenefit', 'do', 'html');
    $return['dataSheet'] = $benefit->GetData($startRec,$itemViewed,$return['cari']);//print_r($return);
    
    return $return;
  }
  
  function ParseTemplate($data = NULL){
    if($this->Pesan){
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }

    //tentukan value judul, button dll sesuai pilihan bahasa 
    if ($data['lang']=='eng'){
      $this->mrTemplate->AddVar('content', 'TITLE', 'BENEFIT / CLAIM TYPE REFERENCE');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Benefit / Claim Type Data');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI BENEFIT / TIPE KLAIM');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Benefit / Tipe Klaim');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
    } 

    $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
    $this->mrTemplate->AddVar('content', 'NAMA', $data['input']['nama']);
    $this->mrTemplate->AddVar('content', 'URAIAN', $data['input']['uraian']);
    $this->mrTemplate->AddVar('content', 'PENGECUALIAN', $data['input']['pengecualian']);
    $this->mrTemplate->AddVar('content', 'TGL', $data['input']['tgl']);
    
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
    foreach ($data['dataSheet'] as $value){
      $data = $value;//print_r($data);
      $data['number'] = $i;
      $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
      $data['url_edit'] = $link['url_edit'].'&id='.$data['benefitId'];
      $data['url_delete'] = $link['url_delete'].
      "&id=".Dispatcher::Instance()->Encrypt($data['benefitId']).
      "&dataName=".Dispatcher::Instance()->Encrypt($data['benefitNama']);
      $data['url_delete_js'] = $link['url_delete_js'];
      $this->mrTemplate->AddVars('data_item', $data, '');
      $this->mrTemplate->parseTemplate('data_item', 'a');
      $i++;
    }
  }
}

?>