<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_kontrak_pegawai.class.php';

class ViewKontrakPegawai extends HtmlResponse {
   
   function TemplateModule() {
      	$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
	'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      	$this->SetTemplateFile('view_kontrak_pegawai.html');    
   } 
   
  function ProcessRequest() {
      $mpg_obj = new DataKontrakPegawai();  
	  //create paging start here
	  
	   //print_r($_POST);
      
      if (isset($_POST['t_nip'])) $nip = $_POST['t_nip']; 
      if(isset($_POST['check'])){
         $nip_check = $_POST['check']['nip'];
         $pNip = $nip;
      }else{
         $pNip = '';
      } 
  
      if (isset($_POST['t_nama'])) $nama= $_POST['t_nama'];
      if(isset($_POST['check'])){
         $nama_check = $_POST['check']['nama'];
         $pNama = $nama;
      }else{
         $pNama = '';
      }
            
		$totalData = $mpg_obj->GetCount($pNip,$pNama);
		$itemViewed = 50;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		
		$data['mpg'] = $mpg_obj->GetListPegawai($pNip,$pNama,$startRec, $itemViewed);
		//print_r($data['mpg']);
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule, 
		Dispatcher::Instance()->mSubModule, 
		Dispatcher::Instance()->mAction, 
		Dispatcher::Instance()->mType);
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), 
		Messenger::CurrentRequest);
	//create paging end here
      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $data['pesan'] = $msg[0][1];
      $data['css'] = $msg[0][2];
      $data['start'] = $startRec+1;
      //set the language
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      if ($lang=='eng'){
	$data['title']="EMPLOYEE CONTRACT DATA";
      }else{
	$data['title']="DATA KONTRAK PEGAWAI";
      }
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('data_pegawai', 'kontrakPegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html'));
      $this->mrTemplate->AddVar('content', 'TITLE', $data['title']);
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }
      
      if (empty($data['mpg'])) {
         $this->mrTemplate->AddVar('data_kontrak_pegawai', 'DATA_EMPTY', 'YES');
      } else {      
         $this->mrTemplate->AddVar('data_kontrak_pegawai', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['mpg']);
         foreach ($data['mpg'] as $key => $value) {
            $no = $key + $data['start'];
			
			if($key == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
			if($key == ($len-1)) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);				
			$value['NUMBER'] = $no;
			
            $value['no'] = $no;
            if ($no % 2 != 0) {
               $value['class_name'] = 'table-common-even';
            } else {
               $value['class_name'] = '';
            }       
			   $idEnc = Dispatcher::Instance()->Encrypt($value['id']);
            if(empty($value['jabstruk'])){
              $value['POSISI'] = $value['jabfung'];
            }elseif(empty($value['jabfung'])){
              $value['POSISI'] = $value['jabstruk'];
            }elseif(!empty($value['jabfung']) and !empty($value['jabstruk'])){
              $value['POSISI'] = $value['jabstruk'].' - '.$value['jabfung'];
            }else{
              $value['POSISI'] = "";
            }

	    
            $value['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai', 'view', 'html').'&pegId='.Dispatcher::Instance()->Encrypt($value['id']);
            $value['tgl_masuk'] = $this->date2string($value['tgl_masuk']);
            $value['tgl_keluar'] = $this->date2string($value['tgl_keluar']);
            $value['tgl_awal'] = $this->date2string($value['tgl_awal']);
            $value['tgl_akhir'] = $this->date2string($value['tgl_akhir']);
            $this->mrTemplate->AddVars('data_kontrak_pegawai_item', $value, 'PEG_');
            $this->mrTemplate->parseTemplate('data_kontrak_pegawai_item', 'a');
         }
      }
   }
   
   function date2string($date) {
	  $bln = array(
	            1  => '01',
					2  => '02',
					3  => '03',
					4  => '04',
					5  => '05',
					6  => '06',
					7  => '07',
					8  => '08',
					9  => '09',
					10 => '10',
					11 => '11',
					12 => '12'					
	               );
	  $arrtgl = explode('-',$date);
    return $arrtgl[2].'/'.$bln[(int) $arrtgl[1]].'/'.$arrtgl[0]; 
  }
}
?>
