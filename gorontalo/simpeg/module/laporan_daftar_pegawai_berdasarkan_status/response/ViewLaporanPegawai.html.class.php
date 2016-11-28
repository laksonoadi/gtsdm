<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_daftar_pegawai_berdasarkan_status/business/laporan.class.php';
   
class ViewLaporanPegawai extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_daftar_pegawai_berdasarkan_status/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_daftar_pegawai_berdasarkan_status.html');
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
      $this->tahun_awal=date('Y')-10;
      $this->tahun_akhir=date('Y')+10;
      
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
			
			if(isset($_POST['status_pegawai'])) {
  				$this->status_pegawai = $_POST['status_pegawai'];
  		} elseif(isset($_GET['status_pegawai'])) {
  				$this->status_pegawai = Dispatcher::Instance()->Decrypt($_GET['status_pegawai']);
  		} else {
  				$this->status_pegawai = 'all';
  		}
  		
  		
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
      array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, 'true', ''), Messenger::CurrentRequest);
  		
  		$this->ComboStatusPegawai=$this->Obj->GetComboStatusPegawai();
  		$this->label_status_pegawai=$this->GetLabelFromCombo($this->ComboStatusPegawai,$this->status_pegawai);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_pegawai', 
      array('status_pegawai', $this->ComboStatusPegawai, $this->status_pegawai, 'true', ''), Messenger::CurrentRequest);
      
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'awal', array($this->awal, $this->tahun_awal, $this->tahun_akhir, '', '', 'awal'), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'akhir', array($this->akhir, $this->tahun_awal, $this->tahun_akhir, '', '', 'akhir'), Messenger::CurrentRequest);
  					
      //create paging 
      $totalData = $this->Obj->GetCountDataPensiun($this->unit_kerja, $this->status_pegawai);
		
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataPensiun($startRec, $itemViewed,$this->unit_kerja, $this->status_pegawai);
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&status_pegawai=' . Dispatcher::Instance()->Encrypt($this->status_pegawai)
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
		  $this->mrTemplate->AddVar('content', 'JUDUL_PANGKAT_GOLONGAN', $this->label_status_pegawai);
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_daftar_pegawai_berdasarkan_status', 'laporanPegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_daftar_pegawai_berdasarkan_status', 'laporanPegawai', 'view', 'xls')
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&status_pegawai=' . Dispatcher::Instance()->Encrypt($this->status_pegawai)
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
        		$dataPegawai[$i]['tanggal_lahir']=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal_lahir'],'YYYY-MM-DD');
        		$dataPegawai[$i]['tanggal_pensiun']=$this->Obj->IndonesianDate($dataPegawai[$i]['tanggal_pensiun'],'YYYY-MM-DD');
    				$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
    				$this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>