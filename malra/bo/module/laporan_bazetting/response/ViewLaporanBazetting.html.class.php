<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_bazetting/business/laporan.class.php';
   
class ViewLaporanBazetting extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_bazetting/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_bazetting.html');
   }
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return str_replace('&nbsp;', '', $ArrData[$i]['name']);
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      set_time_limit(0);
      $this->Obj=new Laporan;
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = 'all';
  		}
			
		if(isset($_POST['jenis_jabatan'])) {
  				$this->jenis_jabatan = $_POST['jenis_jabatan'];
  		} elseif(isset($_GET['jenis_jabatan'])) {
  				$this->jenis_jabatan = Dispatcher::Instance()->Decrypt($_GET['jenis_jabatan']);
  		} else {
  				$this->jenis_jabatan = 'all';
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
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, 'true', ''), Messenger::CurrentRequest);
  		
  		$this->ComboJenisJabatan=$this->Obj->GetComboJenisJabatan();
  		$this->label_jenis_jabatan=$this->GetLabelFromCombo($this->ComboJenisJabatan,$this->jenis_jabatan);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_jabatan', 
        array('jenis_jabatan', $this->ComboJenisJabatan, $this->jenis_jabatan, 'true', 'style="width:150px"'), Messenger::CurrentRequest);
			
		//create paging 
      
		$totalData = $this->Obj->GetCountDataBazetting($this->unit_kerja, $this->jenis_jabatan);
		
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataBazetting($startRec, $itemViewed, $this->unit_kerja, $this->jenis_jabatan);
      
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&jenis_jabatan=' . Dispatcher::Instance()->Encrypt($this->jenis_jabatan)
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
		  $this->mrTemplate->AddVar('content', 'JUDUL_JENIS_PEGAWAI', $this->label_jenis_jabatan);
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_bazetting', 'laporanBazetting', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_bazetting', 'laporanBazetting', 'view', 'xls')
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&jenis_jabatan=' . Dispatcher::Instance()->Encrypt($this->jenis_jabatan)
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

              if(empty($dataPegawai[$i]['latihan_nama'])){
                $this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'YES');
              } else {
                  $this->mrTemplate->AddVar('list_latihan', 'EMPTY', 'NO');
                  $thn = $dataPegawai[$i]['latihan_tahun'];
                  $nama = $dataPegawai[$i]['latihan_nama'];
                  $jam = $dataPegawai[$i]['latihan_jam'];

                  $thn = explode('|', $thn);  
                  $nama = explode('|', $nama);
                  $jam = explode('|', $jam);

                  //echo '<pre/>';
                  // print_r($thn);
                  // print_r($nama);
                  // print_r($jam);
                  $this->mrTemplate->clearTemplate('thn');
                  if(!empty($thn)) {
                    foreach ($thn as $key => $th) {
                      //echo $th;
                      $this->mrTemplate->AddVar('thn', 'LATIHAN_TAHUN', $th);
                      $this->mrTemplate->parseTemplate('thn', 'a');
                    }
                  }

                  $this->mrTemplate->clearTemplate('nm');
                  if(!empty($nama)) {
                    foreach ($nama as $key => $nm) {
                      //echo $th;
                      $this->mrTemplate->AddVar('nm', 'LATIHAN_NAMA', $nm);
                      $this->mrTemplate->parseTemplate('nm', 'a');
                    }
                  }

                  $this->mrTemplate->clearTemplate('jm');
                  if(!empty($jam)) {
                    foreach ($jam as $key => $jm) {
                      //echo $th;
                      $this->mrTemplate->AddVar('jm', 'LATIHAN_JAM', $jm);
                      $this->mrTemplate->parseTemplate('jm', 'a');
                    }
                  }
                
              }

    				$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
    				$this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>