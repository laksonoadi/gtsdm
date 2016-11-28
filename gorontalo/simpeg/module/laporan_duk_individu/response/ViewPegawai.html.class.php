<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_duk_individu/business/laporan.class.php';
//require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_duk/business/laporan.class.php';

class ViewPegawai extends HtmlResponse {
   
   function TemplateModule() {
      	$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
		'module/laporan_duk_individu/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      	$this->SetTemplateFile('view_pegawai.html');    
   } 
   
  function ProcessRequest() {
      $mpg_obj = new Laporan();  
	  //create paging start here
	  
	   //print_r($_POST);
      
        if(isset($_POST['tampilkan'])){
        $tampilkan = $_POST['tampilkan'];
      }elseif(isset($_GET['tampilkan'])){
        $tampilkan = $_GET['tampilkan'];
      }else{
        $tampilkan='';
      }
      
      //create paging start here
      $totalData = $mpg_obj->GetCountDataDuk($tampilkan);

      $itemViewed = 15;
      $currPage = 1;
      $startRec = 0 ;
      if(isset($_GET['page'])) {
        $currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
        $startRec =($currPage-1) * $itemViewed;
      }
      
      $data['mpg'] = $mpg_obj->GetDataDuk($tampilkan, $startRec, $itemViewed);
      
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType).'&tampilkan='.$tampilkan;
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);

      
      $msg = Messenger::Instance()->Receive(__FILE__);
      $data['pesan'] = $msg[0][1];
      $data['css'] = $msg[0][2];
      $data['start'] = $startRec+1;
	  
	  //set the language
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      if ($lang=='eng'){
      	$data['title']="CURRICULUM VITAE DUK BASED";
      }else{
      	$data['title']="DAFTAR URUT KEPANGKATAN";
      }
      $data['lang']=$lang;
	  
      return $data;
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_duk_individu', 'Pegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'TITLE', $data['title']);
      
      if($data['pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }

      //print_r($data['mpg']);
      
      if (empty($data['mpg'])) {
         $this->mrTemplate->AddVar('duk_individu', 'DATA_EMPTY', 'YES');
      } else {      
         $this->mrTemplate->AddVar('duk_individu', 'DATA_EMPTY', 'NO');
         $len = sizeof($data['mpg']);
         foreach ($data['mpg'] as $key => $value) {
            $no = $key + $data['start'];
			

			
            $value['no'] = $no;
            if ($no % 2 != 0) {
               $value['class_name'] = 'table-common-even';
            } else {
               $value['class_name'] = '';
            }       
			   $idEnc = Dispatcher::Instance()->Encrypt($value['id']);
            //$urlAccept = 'mutasi_hukuman|deletePangkatGolongan|do|html';
            //$urlKembali = 'pangkat_golongan|PangkatGolongan|view|html';
            
            $value['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('laporan_duk_individu','laporanCv', 'view', 'html').'&id='.Dispatcher::Instance()->Encrypt($value['id']).'&urut='.$value['no'];
            $this->mrTemplate->AddVars('duk_individu_item', $value, 'MPG_');
            $this->mrTemplate->parseTemplate('duk_individu_item', 'a');
         }
      }
   }
}
?>