<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/hari_libur/business/hari_libur.class.php';

class ViewHariLibur extends HtmlResponse{

  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/hari_libur/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_hari_libur.html');
  }
  
  function ProcessRequest(){
    $harilibur = new HariLibur;
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
      $result = $harilibur->GetDataById($id);
      if($result){
         $return['input']['tgl'] = $result['hariliburTgl'];
         $return['input']['nama'] = $result['hariliburNama'];
         $return['input']['keterangan'] = $result['hariliburKeterangan'];
      }else{
         unset($_GET['id']);
      }
    }else{
      $return['input']['tgl']='';
      $return['input']['nama']='';
      $return['input']['keterangan']='';
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
    $totalData = $harilibur->GetCount($return['cari']);
    $url = Dispatcher::Instance()->GetUrl('hari_libur','hariLibur','view','html').'&cari='.$return['cari'];
    if (isset($_GET['id'])){ 
      $url .= '&id='.$id;
    }
    Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    //paging end here
    
    $y1=date('Y')+4;
    Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl', 
    array(date("Y-m-d"),'2003',$y1,'',''), Messenger::CurrentRequest);
          
    $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('hari_libur','inputHariLibur','do','html');
    if (isset($_GET['id'])){ 
      $return['link']['url_action'] .= '&id='.$id;
    }
    
    $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('hari_libur','hariLibur','view','html');
    
    $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('hari_libur','hariLibur','view','html');
    if ($return['cari'] != ''){
      $return['link']['url_edit'] .= '&cari='.$return['cari'];
    }
    if (isset($_GET['page'])){
      $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
    }
    
    //translate bahasa
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Claim Type Reference');
    }else{
      $labeldel=Dispatcher::Instance()->Encrypt('Data Jenis Klaim');
    }
    $return['lang']=$lang; 
    
    $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
    "&urlDelete=".Dispatcher::Instance()->Encrypt('hari_libur|deleteHariLibur|do|html').
    "&urlReturn=".Dispatcher::Instance()->Encrypt('hari_libur|hariLibur|view|html').
    "&label=".$labeldel;
    $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('hari_libur', 'deleteHariLibur', 'do', 'html');
    $return['dataSheet'] = $harilibur->GetData($startRec,$itemViewed,$return['cari']);//print_r($return);
    
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
      $this->mrTemplate->AddVar('content', 'TITLE', 'HOLIDAY REFERENCE');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Holiday Data');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI HARI LIBUR');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Hari Libur');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
    } 

    $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
    $this->mrTemplate->AddVar('content', 'NAMA', $data['input']['nama']);
    $this->mrTemplate->AddVar('content', 'KETERANGAN', $data['input']['keterangan']);
    
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
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
          $data['hariliburTgl'] = $this->periode2stringEng($data['hariliburTgl']);
       } else {
          $data['hariliburTgl'] = $this->periode2string($data['hariliburTgl']);
       }
      $data['url_edit'] = $link['url_edit'].'&id='.$data['hariliburId'];
      $data['url_delete'] = $link['url_delete'].
      "&id=".Dispatcher::Instance()->Encrypt($data['hariliburId']).
      "&dataName=".Dispatcher::Instance()->Encrypt($data['hariliburNama']);
      $data['url_delete_js'] = $link['url_delete_js'];
      $this->mrTemplate->AddVars('data_item', $data, '');
      $this->mrTemplate->parseTemplate('data_item', 'a');
      $i++;
    }
    
  }
  
  function periode2string($date) {
	   $bln = array(
	        1  => 'Januari',
					2  => 'Februari',
					3  => 'Maret',
					4  => 'April',
					5  => 'Mei',
					6  => 'Juni',
					7  => 'Juli',
					8  => 'Agustus',
					9  => 'September',
					10 => 'Oktober',
					11 => 'November',
					12 => 'Desember'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
  	}
  	
  	function periode2stringEng($date) {
  	   $bln = array(
  	        1  => 'January',
  					2  => 'February',
  					3  => 'March',
  					4  => 'April',
  					5  => 'May',
  					6  => 'June',
  					7  => 'July',
  					8  => 'August',
  					9  => 'September',
  					10 => 'October',
  					11 => 'November',
  					12 => 'December'					
  	               );
  	   $tanggal = substr($date,8,2);
  	   $bulan = substr($date,5,2);
  	   $tahun = substr($date,0,4);
  	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
  	}
}

?>