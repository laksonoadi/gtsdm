<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_nominatif/business/laporan.class.php';
   
class ViewLaporanNominatif extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_nominatif/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_nominatif.html');
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
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				// $this->unit_kerja = 'all';
  				$this->unit_kerja = $this->ComboUnitKerja[0]['id'];
  		}

      if(isset($_POST['jenis']))
        $this->jenis= $_POST['jenis'];
	  elseif(isset($_GET['jenis']))
	    $this->jenis = Dispatcher::Instance()->Decrypt($_GET['jenis']);
      else 
        $this->jenis='CPNS';
  		
  		//Ini yang mengatur multi unit by Wahyono
  		if ($_SESSION['unit_id']==1) {
			$true='true';
		}else{
			if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		}
		
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, $true, ''), Messenger::CurrentRequest);
 
    $this->ComboJenis=$this->Obj->GetComboVariabel2('jenis');
    $this->label['jenis']=$this->GetLabelFromCombo($this->ComboJenis,$this->jenis);
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', 
      array('jenis', $this->ComboJenis, $this->jenis, 'null', ''), Messenger::CurrentRequest);
     			
		//create paging 
		// $totalData = $this->Obj->GetCountDataNominatif($this->unit_kerja,$this->jenis);
		
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataNominatif($startRec, $itemViewed, $this->unit_kerja,$this->jenis);
		$totalData = $this->Obj->GetCountDataNominatif();
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&jenis=' . Dispatcher::Instance()->Encrypt($this->jenis)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here
      
		  $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
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
        
		  $this->mrTemplate->AddVar('content', 'JUDUL_UNIT_KERJA', $this->label_unit_kerja);
		  $this->mrTemplate->AddVar('content', 'JUDUL_PANGKAT_GOLONGAN', $this->label_pangkat_golongan);
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_nominatif', 'laporanNominatif', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_nominatif', 'laporanNominatif', 'view', 'xls')
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&jenis=' . Dispatcher::Instance()->Encrypt($this->jenis)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
      $this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_nominatif', 'rtfLaporanNominatif', 'view', 'html')
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
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
        			$dataPegawai[$i]['tanggal_pengangkatan_cpns']=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal_pengangkatan_cpns'],'YYYY-MM-DD');
					
    				  $this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
					  
					  if(empty($dataPegawai[$i]['latihan_nama'])){
						$this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'YES');
					  } else {
						  $this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'NO');
						  $thn = $dataPegawai[$i]['latihan_tahun'];
						  $nama = $dataPegawai[$i]['latihan_nama'];
						  $jam = $dataPegawai[$i]['latihan_jam'];

						  $thn = explode(',', $thn);  
						  $nama = explode(',', $nama);
						  $this->mrTemplate->clearTemplate('thn');
						  if(!empty($thn)) {
							foreach ($thn as $key => $th) {
							  $this->mrTemplate->AddVar('thn', 'LATIHAN_TAHUN', $th);
							  $this->mrTemplate->AddVar('thn', 'LATIHAN_NAMA', $nama[$key]);
							  $this->mrTemplate->parseTemplate('thn', 'a');
							}
						  }
						
					  }

    				  $this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>