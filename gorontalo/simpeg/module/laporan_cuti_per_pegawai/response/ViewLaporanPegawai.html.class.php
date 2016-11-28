<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_cuti_per_pegawai/business/laporan.class.php';
   
class ViewLaporanPegawai extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_cuti_per_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_cuti_per_pegawai.html');
   }
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      $this->Obj=new Laporan;
	    
  		if(isset($_POST['pegawai'])) {
  				$this->nip = $_POST['pegawai'];
  		} elseif(isset($_GET['pegawai'])) {
  				$this->nip = Dispatcher::Instance()->Decrypt($_GET['pegawai']);
  		} else {
  				$this->nip = 'all';
  		}
	//print_r($this->nip);die;
  	
      	
		$this->ComboNip=$this->Obj->GetComboNip();
  		$this->label_nip=$this->GetLabelFromCombo($this->ComboNip,$this->nip);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'nip', 
      array('nip', $this->ComboNip, $this->nip, 'true', ''), Messenger::CurrentRequest);
  		        					
      //create paging 
	  $jumlah_cuti= $this->Obj->GetCountDataCuti($this->nip);
	  
      $totalData = $jumlah_cuti[0]['jumlah'];
		//print_r($jumlah_cuti);die;
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataCuti($startRec, $itemViewed, $this->nip);
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&nip=' . Dispatcher::Instance()->Encrypt($this->nip)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here
      
		  $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
		//print_r($dataPegawai);die;
  		$return['dataPegawai'] = $dataPegawai;
		
  		$return['start'] = $startRec+1;
        
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
        
		  $this->mrTemplate->AddVar('content', 'JUDUL_UNIT_KERJA', $this->label_nip);
		  $this->mrTemplate->AddVar('content', 'JUDUL_PANGKAT_GOLONGAN', $this->label_pangkat_golongan);
		  $this->mrTemplate->AddVar('content', 'URL_POPUP_PEGAWAIPOPUP', Dispatcher::Instance()->GetUrl('laporan_cuti_per_pegawai', 'popupPegawai', 'view', 'html') );
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_cuti_per_pegawai', 'laporanPegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_cuti_per_pegawai', 'laporanPegawai', 'view', 'xls')
        .'&nip=' . Dispatcher::Instance()->Encrypt($this->nip)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
      $this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_cuti_per_pegawai', 'rtfLaporanGuruBesar', 'view', 'html')
        .'&nip=' . Dispatcher::Instance()->Encrypt($this->nip)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
      
      
        if (empty($data['dataPegawai'])) {
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
    		} else {
    			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
    			$encPage = Dispatcher::Instance()->Encrypt($decPage);
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
    			$dataPegawai = $data['dataPegawai'];
    			$total=0;
    			for ($i=0; $i<sizeof($dataPegawai); $i++) {
    					$no = $i+$data['start'];
    					$dataPegawai[$i]['no'] = $no;
    					if ($no % 2 != 0) {
        			  $dataPegawai[$i]['class_name'] = 'table-common-even';
        			}else{
        				$dataPegawai[$i]['class_name'] = '';
        			}
    				  $this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
    				  $this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>