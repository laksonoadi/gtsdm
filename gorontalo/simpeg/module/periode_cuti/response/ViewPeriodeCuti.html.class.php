<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/periode_cuti/business/periode_cuti.class.php';

class ViewPeriodeCuti extends HtmlResponse{

  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/periode_cuti/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_periode_cuti.html');
  }
  
  function ProcessRequest(){
    $periodeCuti = new PeriodeCuti;
    // inisialisasi messaging
    $msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
    $this->Data = $msg[0][0];
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
    // ---------
    $id = $_GET['id']->Integer()->Raw();

    if(isset($_GET['id'])){
      $result = $periodeCuti->GetDataById($id);
      if($result){
         $return['input']['total'] = $result['cutiperTotal'];
         $return['input']['awal'] = $result['cutiperAwal'];
         $return['input']['akhir'] = $result['cutiperAkhir'];
         $return['input']['pegId'] = $result['cutiperPegId'];
         $return['input']['status'] = $result['cutiperStatus'];
      }else{
         unset($_GET['id']);
      }
    }else{
      $return['input']['total']='';
      $return['input']['awal']='';
      $return['input']['akhir']='';
      $return['input']['pegId']='';
      $return['input']['status']='';
    }
		       
    $pegId = $_GET['pegId']->Integer()->Raw();
    
    $return['pegId'] = $pegId;

    // inisialisasi data filter
    /*if (isset($_POST['periode_bulan_awal'])){
      $return['periode_bulan_awal'] = $_POST['periode_bulan_awal']->Raw();
    }
    elseif (isset($_GET['periode_bulan_awal'])){
      $return['periode_bulan_awal'] = $_GET['periode_bulan_awal']->Raw();
    }else{
      $return['periode_bulan_awal'] = '';
    }
    
    if (isset($_POST['periode_tahun_awal'])){
      $return['periode_tahun_awal'] = $_POST['periode_tahun_awal']->Raw();
    }
    elseif (isset($_GET['periode_tahun_awal'])){
      $return['periode_tahun_awal'] = $_GET['periode_tahun_awal']->Raw();
    }else{
      $return['periode_tahun_awal'] = '';
    }
    
    if (isset($_POST['periode_bulan_akhir'])){
      $return['periode_bulan_akhir'] = $_POST['periode_bulan_akhir']->Raw();
    }
    elseif (isset($_GET['periode_bulan_akhir'])){
      $return['periode_bulan_akhir'] = $_GET['periode_bulan_akhir']->Raw();
    }else{
      $return['periode_bulan_akhir'] = '';
    }
    
    if (isset($_POST['periode_tahun_akhir'])){
      $return['periode_tahun_akhir'] = $_POST['periode_tahun_akhir']->Raw();
    }
    elseif (isset($_GET['periode_tahun_akhir'])){
      $return['periode_tahun_akhir'] = $_GET['periode_tahun_akhir']->Raw();
    }else{
      $return['periode_tahun_akhir'] = '';
    } */
    
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
    $totalData = $periodeCuti->GetCount($return['cari']);
    $url = Dispatcher::Instance()->GetUrl('periode_cuti','periodeCuti','view','html').'&cari='.$return['cari'];
    if (isset($_GET['id'])){ 
      $url .= '&id='.$id.'&pegId='.$pegId;
    }

    Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    //paging end here
    
    
    $searchAwal=$_POST['cutiperAwalSearch_year'].'-'.$_POST['cutiperAwalSearch_mon'].'-'.$_POST['cutiperAwalSearch_day'];
    $searchAkhir=$_POST['cutiperAkhirSearch_year'].'-'.$_POST['cutiperAkhirSearch_mon'].'-'.$_POST['cutiperAkhirSearch_day'];
    
    $getSearchAwal=$_GET['cutiperAwalSearch_year'].'-'.$_GET['cutiperAwalSearch_mon'].'-'.$_GET['cutiperAwalSearch_day'];
    $getSearchAkhir=$_GET['cutiperAkhirSearch_year'].'-'.$_GET['cutiperAkhirSearch_mon'].'-'.$_GET['cutiperAkhirSearch_day'];

    if (isset($searchAwal)){
      $return['cutiperAwalSearch'] = $searchAwal;
    }
    elseif (isset($getSearchAwal)){
      $return['cutiperAwalSearch'] = $getSearchAwal;
    }else{
      $return['cutiperAwalSearch'] = '';
    }
    
    if (isset($searchAkhir)){
      $return['cutiperAkhirSearch'] = $searchAkhir;
    }
    elseif (isset($getSearchAkhir)){
      $return['cutiperAkhirSearch'] = $getSearchAkhir;
    }else{
      $return['cutiperAkhirSearch'] = '';
    }
    
    #$return['cutiperAwalSearch'] = $searchAwal;
    #$return['cutiperAkhirSearch'] = $searchAkhir;
    
    
    
    $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('periode_cuti','inputPeriodeCuti','do','html');
    if (isset($_GET['id'])){ 
      $return['link']['url_action'] .= '&id='.$id .'&pegId='.$pegId; 
    }
    
    $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('periode_cuti','periodeCuti','view','html').'&pegId='.$return['pegId'].'&cutiperAwalSearch='.$return['cutiperAwalSearch'].'&cutiperAkhirSearch='.$return['cutiperAkhirSearch'];
    
    
    
    $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('periode_cuti','periodeCuti','view','html');
    /*if ($return['cutiperAwalSearch'] != ''){
      $return['link']['url_edit'] .= '&cutiperAwalSearch='.$return['cutiperAwalSearch'].'&cutiperAkhirSearch='.$return['cutiperAkhirSearch'];
    }*/
    if (isset($_GET['page'])){
      $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
    }
    /*
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $bulan = $periodeCuti->GetBulanEng();
    }else{
      $bulan = $periodeCuti->GetBulan();  
    }
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan_awal', 
  	  array('periode_bulan_awal', $bulan, $idBulan, 'none', ''), 
  	Messenger::CurrentRequest);
  		
    $year = $periodeCuti->GetTahun();  
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun_awal', 
  	  array('periode_tahun_awal', $year, $idTahun, 'none', ''), 
  	Messenger::CurrentRequest);
  	
  	if ($lang=='eng'){
      $bulan1 = $periodeCuti->GetBulanEng();
    }else{
      $bulan1 = $periodeCuti->GetBulan();  
    }
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan_akhir', 
  	  array('periode_bulan_akhir', $bulan1, $idBulan1, 'none', ''), 
  	Messenger::CurrentRequest);
  		
    $year1 = $periodeCuti->GetTahun();  
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun_akhir', 
  	  array('periode_tahun_akhir', $year1, $idTahun1, 'none', ''), 
  	Messenger::CurrentRequest);
  	*/
    	 
    //translate bahasa
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Leave Period');
    }else{
      $labeldel=Dispatcher::Instance()->Encrypt('Leave Period Data');
    }
    $return['lang']=$lang; 
    
    $akhirT=date('Y')+5;
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'cutiperAwal', 
         array($return['input']['awal'],'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'cutiperAkhir', 
         array($return['input']['akhir'],'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'cutiperAwalSearch', 
         array('2009-01-01','2009',$akhirT,'2009',$akhirT), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'cutiperAkhirSearch', 
         array($akhirT.'-01-01','2009',$akhirT,'2009',$akhirT), Messenger::CurrentRequest);
     
    $searchAwal = $this->_POST['cutiperAwalSearch_year'].'-'.$this->_POST['cutiperAwalSearch_mon'].'-'.$this->_POST['cutiperAwalSearch_day'];
    $searchAkhir = $this->_POST['cutiperAkhirSearch_year'].'-'.$this->_POST['cutiperAkhirSearch_mon'].'-'.$this->_POST['cutiperAkhirSearch_day'];
         
    $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
    "&urlDelete=".Dispatcher::Instance()->Encrypt('periode_cuti|deletePeriodeCuti|do|html').
    "&urlReturn=".Dispatcher::Instance()->Encrypt('periode_cuti|periodeCuti|view|html').
    "&label=".$labeldel;
    $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('periode_cuti', 'deletePeriodeCuti', 'do', 'html');
    
    #$return['dataSheet'] = $periodeCuti->GetData($return['cutiperAwalSearch'],$return['cutiperAkhirSearch'],$startRec,$itemViewed);//print_r($return);
    $return['dataSheet'] = $periodeCuti->GetData($startRec,$itemViewed,$return);//print_r($return);     
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
      $this->mrTemplate->AddVar('content', 'TITLE', 'LEAVE PERIOD');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Leave Period Data');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'PERIODE CUTI');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Periode Cuti');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
    } 
    
    $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('data_cuti', 'historyDataCuti', 'view', 'html') . '&dataId=' . $data['pegId']);
    $this->mrTemplate->AddVar('content', 'TOTAL', $data['input']['total']);
    if($data['input']['status'] == 'Active'){
			$this->mrTemplate->AddVar('content', 'STATUS_CHECKED', 'checked="checked"');
		} else{
      $this->mrTemplate->AddVar('content','STATUS_CHECKED','');
    } 
				
    $this->mrTemplate->AddVar('content', 'CUTIPERAWAL', $data['input']['awal']);
    $this->mrTemplate->AddVar('content', 'CUTIPERAKHIR', $data['input']['akhir']);
    
    // Filter Form
    $this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
    $this->mrTemplate->AddVar('content', 'PEG_ID', $data['pegId']); 
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
      $data['url_edit'] = $link['url_edit'].'&id='.$data['cutiperId'].'&pegId='.$data['cutiperPegId'];
      $data['url_delete'] = $link['url_delete'].
      "&id=".Dispatcher::Instance()->Encrypt($data['cutiperId']);
      $data['url_delete_js'] = $link['url_delete_js'];
      $this->mrTemplate->AddVars('data_item', $data, '');
      $data['cutiperAwal'] = $this->periode2string($data['cutiperAwal']);
      $this->mrTemplate->AddVar('data_item', 'CUTIPERAWAL', $data['cutiperAwal']);
      $data['cutiperAkhir'] = $this->periode2string($data['cutiperAkhir']);
      $this->mrTemplate->AddVar('data_item', 'CUTIPERAKHIR', $data['cutiperAkhir']);
      if($data['cutiperStatus'] == 'Active'){
        $this->mrTemplate->AddVar('data_item', 'CUTIPERSTATUS', '<b>'.$data['cutiperStatus'].'</b>');
      } else {
        $this->mrTemplate->AddVar('data_item', 'CUTIPERSTATUS', '<i>'.$data['cutiperStatus'].'</i>');
      }
     
      $this->mrTemplate->parseTemplate('data_item', 'a');
      $i++;
    }
  }
  
  function periode2string($date) {
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return $tanggal.'/'.$bulan.'/'.$tahun;
	}
}

?>