<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jabatan_fungsional/business/jabatan_fungsional.class.php';

//set Label for module
//require_once GTFWConfiguration::GetValue( 'application', 'docroot') .'module/label/response/Label.proc.class.php';

class PopupGajiSks extends HtmlResponse
{

	function TemplateBase()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-common-popup.html');
      $this->SetTemplateFile('layout-common-popup.html');
   }
   
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/jabatan_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('pop_up_gaji_sks.html');
   }
   
   function ProcessRequest()
   {
      $Obj = new JabatanFungsional;
      
      // inisialisasi messaging
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      // End
      
      // Inisialisasi filter
      if($_GET['cari']->Raw()=='kompSks'){
	     $return['komponen']='kompSks';
	  }elseif($_GET['cari']->Raw()=='kompGaji'){
	     $return['komponen']='kompGaji';
	  }
	  //print_r($_GET['cari']->Raw());
      if (isset($_POST['btncari']))
      {
         $CARI_KOMPONEN = $_POST['komponen_gaji']->Raw();
         
      }
      elseif (isset($_GET['edit'])){
	     if(isset($_GET['kompSks'])){
		    $CARI_KOMPONEN = $_GET['kompSks']->Integer()->Raw();
			if(isset($_GET['sksId'])){
			   $this->decId = $_GET['sksId']->Integer()->Raw();
			} 
		 }elseif(isset($_GET['kompGaji'])){
		    $CARI_KOMPONEN = $_GET['kompGaji']->Integer()->Raw();
			if(isset($_GET['gajiId'])){
			   $this->decId = $_GET['gajiId']->Integer()->Raw();
			}
		 }
	  }else{
	     $CARI_KOMPONEN = '';
	  }
         $return['cari']=$CARI_KOMPONEN;
      
      // End of inisialisasi filter
      
      // Render Combobox....
      $listKomponenGaji = $Obj->GetKomponenGaji();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'komponen_gaji', array('komponen_gaji', $listKomponenGaji, $return['cari'], "false", 'id="komponen_gaji"'), Messenger::CurrentRequest);
      
      // ---------
      
      // Inisialisasi link URL
      $return['url']['search'] = Dispatcher::Instance()->GetUrl('jabatan_fungsional', 'GajiSks', 'popup', 'html').'&cari='.$return['komponen'];
      // ---------
      
      // Inisialisasi komponen paging
      $allData = $Obj->GetGajiDetail($return['cari']);
	  $totalData = count($allData);
      $itemViewed = 10;
      $currPage = 1;
      if (isset($_GET['page'])) $currPage = $_GET['page']->Integer()->Raw();
      if ($currPage < 1) $currPage = 1;
      $startRec = ($currPage-1) * $itemViewed;
      
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType) . '&cari=' . Dispatcher::Instance()->Encrypt($cari);
		$dest = "popup-subcontent";
      Messenger::Instance()->SendToComponent('paging', 'paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $dest), Messenger::CurrentRequest);
      $return['start'] = $startRec+1;
      // ---------
      
      // Generate data structure
      $gajiDetail = $Obj->GetGajiDetailRange($return['cari'], $startRec, $itemViewed);
      
      // End of generate data
      $return['datasheet']=$gajiDetail;
      return $return;
   }
   
   
   
	function ParseTemplate($data = NULL)
   {
      //set Label
      
      
      // Render URL
	  if(!empty($data['url']['search'])){
		$this->mrTemplate->AddVars('content', $data['url'], 'URL_');
		//$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['url']['search']);
	  }
      // ---------
      
      // Render filter box
	  if(!empty($data['cari'])){
		//$this->mrTemplate->AddVars('content', $data['cari'], '');
	  }
      // ---------
      
      // Render tabel data
		if (empty($data['datasheet']))
		{
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
			return;
		}
		else{
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
      
			$i = $data['start'];
	      foreach ($data['datasheet'] as $value)
	      {
				// Kelompok
			$value['number']=$i;
			if($value['seting']=='nominal'){
			   $value['nilai_nominal']='Rp '.number_format($value['nominal'],2,",",".");
			   $value['nilai_persen']= "0 %";
			}elseif($value['seting']=='persen'){
			   $value['nilai_nominal']='Rp '.number_format(0,2,",",".");
			   $value['nilai_persen']= $value['persen']." %";
			}
			$value['ident']=$data['komponen'];//$this->dumper($value);
			$this->mrTemplate->AddVars('data_item', $value, 'DATA_');
	         $this->mrTemplate->parseTemplate('data_item', 'a');
	         $i++;
	         
	      }
	  }
      // ---------
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
}
?>
