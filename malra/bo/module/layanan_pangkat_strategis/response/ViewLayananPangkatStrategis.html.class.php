<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_pangkat_strategis/business/LayananPangkatStrategis.class.php';
   
class ViewLayananPangkatStrategis extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/layanan_pangkat_strategis/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_layanan_pangkat_strategis.html');
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
      $this->Obj = new LayananPangkatStrategis;
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} elseif(isset($_GET['unit_kerja'])) {
  				$this->unit_kerja = Dispatcher::Instance()->Decrypt($_GET['unit_kerja']);
  		} else {
  				$this->unit_kerja = '';
  		}
        if($this->unit_kerja == 'all') {
            $satker = $this->Obj->GetSatkerAndLevel();
            $this->unit_kerja = $satker[0]['satkerId'];
        }
		
		if(isset($_POST['pangkat_golongan'])) {
  				$this->pangkat_golongan = $_POST['pangkat_golongan'];
  		} elseif(isset($_GET['pangkat_golongan'])) {
  				$this->pangkat_golongan = Dispatcher::Instance()->Decrypt($_GET['pangkat_golongan']);
  		} else {
  				$this->pangkat_golongan = 'all';
  		}
		
		if(isset($_POST['status_sk'])) {
  				$this->status_sk = $_POST['status_sk'];
  		} elseif(isset($_GET['status_sk'])) {
  				$this->status_sk = Dispatcher::Instance()->Decrypt($_GET['status_sk']);
  		} else {
  				$this->status_sk = 'all';
  		}
        
        if(isset($_POST['awal_year'])) {
            $this->awal = $_POST['awal_year'].'-'.$_POST['awal_mon'].'-'.$_POST['awal_day'];
        } elseif(isset($_GET['awal'])) {
            $this->awal = $_GET['awal']->SqlString()->Raw();
        } else {
            $this->awal = date('Y-m-d', strtotime('first day of this month'));
        }
        
        if(isset($_POST['akhir_year'])) {
            $this->akhir = $_POST['akhir_year'].'-'.$_POST['akhir_mon'].'-'.$_POST['akhir_day'];
        } elseif(isset($_GET['akhir'])) {
            $this->akhir = $_GET['akhir']->SqlString()->Raw();
        } else {
            $this->akhir = date('Y-m-d', strtotime('last day of this month'));
        }
		
		//Ini yang mengatur multi unit by Wahyono
  		if ($_SESSION['unit_id']==1) {
			$true='true';
		}else{
			// if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		}
  		
  		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
  		$this->label_unit_kerja=$this->GetLabelFromCombo($this->ComboUnitKerja,$this->unit_kerja);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, 'false', ''), Messenger::CurrentRequest);
  		
  		$this->ComboPangkatGolongan=$this->Obj->GetComboPangkatGolongan();
  		$this->label_pangkat_golongan=$this->GetLabelFromCombo($this->ComboPangkatGolongan,$this->pangkat_golongan);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pangkat_golongan', 
        array('pangkat_golongan', $this->ComboPangkatGolongan, $this->pangkat_golongan, 'true', ''), Messenger::CurrentRequest);
  		
  		$this->ComboStatusSk=$this->Obj->GetComboStatusSk();
  		$this->label_status_sk=$this->GetLabelFromCombo($this->ComboStatusSk,$this->status_sk);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status_sk', 
        array('status_sk', $this->ComboStatusSk, $this->status_sk, 'true', ''), Messenger::CurrentRequest);
		
        $tahun_awal = 1970;
        $tahun_akhir = date('Y') + 10;
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'awal', array($this->awal, $tahun_awal, $tahun_akhir, TRUE, '', 'awal'), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'akhir', array($this->akhir, $tahun_awal, $tahun_akhir, TRUE, '', 'akhir'), Messenger::CurrentRequest);
        
		//create paging 
      
		$totalData = $this->Obj->GetCountListSkPangkat($this->unit_kerja, $this->pangkat_golongan, $this->status_sk, $this->awal, $this->akhir);
		
  		$itemViewed = 15;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetListSkPangkat($startRec, $itemViewed, $this->unit_kerja, $this->pangkat_golongan, $this->status_sk, $this->awal, $this->akhir);
      
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&unit_kerja=' . Dispatcher::Instance()->Encrypt($this->unit_kerja)
        .'&pangkat_golongan=' . Dispatcher::Instance()->Encrypt($this->pangkat_golongan)
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
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis', 'layananPangkatStrategis', 'view', 'html') );
      
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
                    
                    $dataPegawai[$i]['class_status'] = ($dataPegawai[$i]['status'] == '0' ? 'printed' : 'not-printed');
                    
                    $dataPegawai[$i]['url_input'] = Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis', 'inputLayananPangkatStrategis', 'view', 'html').'&id='.$dataPegawai[$i]['id_pangkat'];

    				$this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
    				$this->mrTemplate->parseTemplate('table_item', 'a');
    			}
      }      
   }
}
   

?>