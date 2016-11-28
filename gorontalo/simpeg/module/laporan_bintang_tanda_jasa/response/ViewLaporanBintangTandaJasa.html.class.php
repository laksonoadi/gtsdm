<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bintang_tanda_jasa/business/laporan.class.php';
   
class ViewLaporanBintangTandaJasa extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_bintang_tanda_jasa/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_bintang_tanda_jasa.html');
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
      $this->tahun_awal=date('Y')-80;
      $this->tahun_akhir=date('Y');
      
      if(isset($_POST['awal_year'])) {
  				$this->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
  		} elseif(isset($_GET['awal'])) {
  				$this->awal = Dispatcher::Instance()->Decrypt($_GET['awal']);
  		} else {
  				$this->awal = date('Y-m').'-01';
  		}
  		$this->label_awal=$this->Obj->IndonesianDate($this->awal,'YYYY-MM-DD');
  		
  		if(isset($_POST['akhir_year'])) {
  				$this->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];;
  		} elseif(isset($_GET['akhir'])) {
  				$this->akhir = Dispatcher::Instance()->Decrypt($_GET['akhir']);
  		} else {
  				$this->akhir = date('Y-m').'-'.$this->Obj->getLastDate(date('Y'),date('m'));
  		}
  		$this->label_akhir=$this->Obj->IndonesianDate($this->akhir,'YYYY-MM-DD');
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = 'all';
  		}
			
			if(isset($_POST['tanda_jasa'])) {
  				$this->tanda_jasa = $_POST['tanda_jasa'];
  		} elseif(isset($_GET['tanda_jasa'])) {
  				$this->tanda_jasa = Dispatcher::Instance()->Decrypt($_GET['tanda_jasa']);
  		} else {
  				$this->tanda_jasa = 'all';
  		}
  		
		//Ini yang mengatur multi unit by Wahyono
  		if ($_SESSION['unit_id']==1) {
			$true='true';
		}else{
			if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		}
  		
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, $true, ''), Messenger::CurrentRequest);
  		
  		$this->ComboTandaJasa=$this->Obj->GetComboTandaJasa();
  		$this->label_tanda_jasa=$this->GetLabelFromCombo($this->ComboTandaJasa,$this->tanda_jasa);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tanda_jasa', 
        array('tanda_jasa', $this->ComboTandaJasa, $this->tanda_jasa, 'true', ''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'awal', array($this->awal, $this->tahun_awal, $this->tahun_akhir, '', '', 'awal'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'akhir', array($this->akhir, $this->tahun_awal, $this->tahun_akhir, '', '', 'akhir'), Messenger::CurrentRequest);
  					
      //create paging 
      // $totalData = $this->Obj->GetCountDataBintangTandaJasa($this->awal,$this->akhir,$this->unit_kerja, $this->tanda_jasa);
		
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataBintangTandaJasa($startRec, $itemViewed, $this->awal,$this->akhir,$this->unit_kerja, $this->tanda_jasa);
      $totalData = $this->Obj->GetCountDataBintangTandaJasa();
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&tanda_jasa=' . Dispatcher::Instance()->Encrypt($this->tanda_jasa)
        .'&awal=' . Dispatcher::Instance()->Encrypt($this->awal)
        .'&akhir=' . Dispatcher::Instance()->Encrypt($this->akhir)
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
		  $this->mrTemplate->AddVar('content', 'JUDUL_TANDA_JASA', $this->label_tanda_jasa);
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_bintang_tanda_jasa', 'laporanBintangTandaJasa', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_bintang_tanda_jasa', 'laporanBintangTandaJasa', 'view', 'xls')
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&tanda_jasa=' . Dispatcher::Instance()->Encrypt($this->tanda_jasa)
        .'&awal=' . Dispatcher::Instance()->Encrypt($this->awal)
        .'&akhir=' . Dispatcher::Instance()->Encrypt($this->akhir)
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
        			} else {
        				$dataPegawai[$i]['class_name'] = '';
        			}
					
					$dataPegawai[$i]['tanggal']=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal'],'YYYY-MM-DD');
					$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
					$this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>