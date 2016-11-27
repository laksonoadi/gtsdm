<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/balance_benefit/business/balance_benefit.class.php';

class ViewBalanceBenefit extends HtmlResponse{

  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/balance_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_balance_benefit.html');
  }
  
  function ProcessRequest(){
    $balanceBenefit = new BalanceBenefit;
    // inisialisasi messaging
    $msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
    $this->Data = $msg[0][0];
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
    // ---------
    $id = $_GET['id']->Integer()->Raw();

    if(isset($_GET['id'])){
      $result = $balanceBenefit->GetDataById($id);
      if($result){
         $return['input']['total'] = $result['balancebenefitTotal'];
         $return['input']['awal'] = $result['balancebenefitAwal'];
         $return['input']['akhir'] = $result['balancebenefitAkhir'];
         $return['input']['pegId'] = $result['balancebenefitPegId'];
         $return['input']['status'] = $result['balancebenefitStatus'];
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
    $totalData = $balanceBenefit->GetCount($return['cari']);
    $url = Dispatcher::Instance()->GetUrl('balance_benefit','balanceBenefit','view','html').'&cari='.$return['cari'];
    if (isset($_GET['id'])){ 
      $url .= '&id='.$id.'&pegId='.$pegId;
    }

    Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    //paging end here
    
    
    $searchAwal=$_POST['balancebenefitAwalSearch_year'].'-'.$_POST['balancebenefitAwalSearch_mon'].'-'.$_POST['balancebenefitAwalSearch_day'];
    $searchAkhir=$_POST['balancebenefitAkhirSearch_year'].'-'.$_POST['balancebenefitAkhirSearch_mon'].'-'.$_POST['balancebenefitAkhirSearch_day'];
    
    $getSearchAwal=$_GET['balancebenefitAwalSearch_year'].'-'.$_GET['balancebenefitAwalSearch_mon'].'-'.$_GET['balancebenefitAwalSearch_day'];
    $getSearchAkhir=$_GET['balancebenefitAkhirSearch_year'].'-'.$_GET['balancebenefitAkhirSearch_mon'].'-'.$_GET['balancebenefitAkhirSearch_day'];

    if (isset($searchAwal)){
      $return['balancebenefitAwalSearch'] = $searchAwal;
    }
    elseif (isset($getSearchAwal)){
      $return['balancebenefitAwalSearch'] = $getSearchAwal;
    }else{
      $return['balancebenefitAwalSearch'] = '';
    }
    
    if (isset($searchAkhir)){
      $return['balancebenefitAkhirSearch'] = $searchAkhir;
    }
    elseif (isset($getSearchAkhir)){
      $return['balancebenefitAkhirSearch'] = $getSearchAkhir;
    }else{
      $return['balancebenefitAkhirSearch'] = '';
    }
    
    #$return['balancebenefitAwalSearch'] = $searchAwal;
    #$return['balancebenefitAkhirSearch'] = $searchAkhir;
    
    
    
    $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('balance_benefit','inputBalanceBenefit','do','html');
    if (isset($_GET['id'])){ 
      $return['link']['url_action'] .= '&id='.$id .'&pegId='.$pegId; 
    }
    
    $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('balance_benefit','balanceBenefit','view','html').'&pegId='.$return['pegId'].'&balancebenefitAwalSearch='.$return['balancebenefitAwalSearch'].'&balancebenefitAkhirSearch='.$return['balancebenefitAkhirSearch'];
    
    
    
    $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('balance_benefit','balanceBenefit','view','html');
    /*if ($return['balancebenefitAwalSearch'] != ''){
      $return['link']['url_edit'] .= '&balancebenefitAwalSearch='.$return['balancebenefitAwalSearch'].'&balancebenefitAkhirSearch='.$return['balancebenefitAkhirSearch'];
    }*/
    if (isset($_GET['page'])){
      $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
    }
    /*
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $bulan = $balanceBenefit->GetBulanEng();
    }else{
      $bulan = $balanceBenefit->GetBulan();  
    }
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan_awal', 
  	  array('periode_bulan_awal', $bulan, $idBulan, 'none', ''), 
  	Messenger::CurrentRequest);
  		
    $year = $balanceBenefit->GetTahun();  
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun_awal', 
  	  array('periode_tahun_awal', $year, $idTahun, 'none', ''), 
  	Messenger::CurrentRequest);
  	
  	if ($lang=='eng'){
      $bulan1 = $balanceBenefit->GetBulanEng();
    }else{
      $bulan1 = $balanceBenefit->GetBulan();  
    }
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_bulan_akhir', 
  	  array('periode_bulan_akhir', $bulan1, $idBulan1, 'none', ''), 
  	Messenger::CurrentRequest);
  		
    $year1 = $balanceBenefit->GetTahun();  
  	Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'periode_tahun_akhir', 
  	  array('periode_tahun_akhir', $year1, $idTahun1, 'none', ''), 
  	Messenger::CurrentRequest);
  	*/
    	 
    //translate bahasa
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
      $labeldel=Dispatcher::Instance()->Encrypt('Benefit Balance Data');
    }else{
      $labeldel=Dispatcher::Instance()->Encrypt('Data Neraca Benefit');
    }
    $return['lang']=$lang; 
    
    $akhirT=date('Y')+5;
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'balancebenefitAwal', 
         array($return['input']['awal'],'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'balancebenefitAkhir', 
         array($return['input']['akhir'],'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'balancebenefitAwalSearch', 
         array('2009-01-01','2009',$akhirT,'2009',$akhirT), Messenger::CurrentRequest);
         
     Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'balancebenefitAkhirSearch', 
         array($akhirT.'-01-01','2009',$akhirT,'2009',$akhirT), Messenger::CurrentRequest);
     
    $searchAwal = $this->_POST['balancebenefitAwalSearch_year'].'-'.$this->_POST['balancebenefitAwalSearch_mon'].'-'.$this->_POST['balancebenefitAwalSearch_day'];
    $searchAkhir = $this->_POST['balancebenefitAkhirSearch_year'].'-'.$this->_POST['balancebenefitAkhirSearch_mon'].'-'.$this->_POST['balancebenefitAkhirSearch_day'];
         
    $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
    "&urlDelete=".Dispatcher::Instance()->Encrypt('balance_benefit|deleteBalanceBenefit|do|html').
    "&urlReturn=".Dispatcher::Instance()->Encrypt('balance_benefit|balanceBenefit|view|html').
    "&label=".$labeldel;
    $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('balance_benefit', 'deleteBalanceBenefit', 'do', 'html');
    
    #$return['dataSheet'] = $balanceBenefit->GetData($return['balancebenefitAwalSearch'],$return['balancebenefitAkhirSearch'],$startRec,$itemViewed);//print_r($return);
    $return['dataSheet'] = $balanceBenefit->GetData($startRec,$itemViewed,$return);//print_r($return);     
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
      $this->mrTemplate->AddVar('content', 'TITLE', 'BENEFIT BALANCE DATA');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Balance Benefit Data');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
    }else{
      $this->mrTemplate->AddVar('content', 'TITLE', 'DATA NERACA BENEFIT');
      $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Neraca Benefit');
      $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
    } 
    
    $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
    $this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('balance_benefit', 'pegawai', 'view', 'html'));
    $this->mrTemplate->AddVar('content', 'URL_HISTORY', Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html') . '&dataId=' . $data['pegId']);
    $this->mrTemplate->AddVar('content', 'TOTAL', $data['input']['total']);
    if($data['input']['status'] == 'Active'){
			$this->mrTemplate->AddVar('content', 'STATUS_CHECKED', 'checked="checked"');
		} else{
      $this->mrTemplate->AddVar('content','STATUS_CHECKED','');
    } 
				
    $this->mrTemplate->AddVar('content', 'BALANCEBENEFITAWAL', $data['input']['awal']);
    $this->mrTemplate->AddVar('content', 'BALANCEBENEFITAKHIR', $data['input']['akhir']);
    
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
      $data['url_edit'] = $link['url_edit'].'&id='.$data['balancebenefitId'].'&pegId='.$data['balancebenefitPegId'];
      $data['url_delete'] = $link['url_delete'].
      "&id=".Dispatcher::Instance()->Encrypt($data['balancebenefitId']);
      $data['url_delete_js'] = $link['url_delete_js'];
      $this->mrTemplate->AddVars('data_item', $data, '');
      $data['balancebenefitAwal'] = $this->periode2string($data['balancebenefitAwal']);
      $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITAWAL', $data['balancebenefitAwal']);
      $data['balancebenefitAkhir'] = $this->periode2string($data['balancebenefitAkhir']);
      $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITAKHIR', $data['balancebenefitAkhir']);
      $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITTOTAL', number_format($data['balancebenefitTotal'], 2, ',', '.'));
      $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITDIAMBIL', number_format($data['balancebenefitDiambil'], 2, ',', '.'));
      if($data['balancebenefitStatus'] == 'Active'){
        $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITSTATUS', '<b>'.$data['balancebenefitStatus'].'</b>');
      } else {
        $this->mrTemplate->AddVar('data_item', 'BALANCEBENEFITSTATUS', '<i>'.$data['balancebenefitStatus'].'</i>');
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