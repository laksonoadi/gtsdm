<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_pegawai_fungsional/business/laporan.class.php';
   
class ViewLaporanPegawai extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_pegawai_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_pegawai.html');
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
  		
  		if(isset($_POST['jenis_pegawai'])) {
  				$this->jenis_pegawai = $_POST['jenis_pegawai'];
  		} elseif(isset($_GET['jenis_pegawai'])) {
  				$this->jenis_pegawai = Dispatcher::Instance()->Decrypt($_GET['jenis_pegawai']);
  		} else {
  				$this->jenis_pegawai = 'all';
  		}
  		
  		$this->ComboJabatanFungsional=$this->Obj->GetComboJabatanFungsional();
      if(isset($_POST['jabatan_fungsional'])) {
  				$this->jabatan_fungsional = $_POST['jabatan_fungsional'];
  		} elseif(isset($_GET['jabatan_fungsional'])) {
  				$this->jabatan_fungsional = Dispatcher::Instance()->Decrypt($_GET['jabatan_fungsional']);
  		} else {
  				$this->jabatan_fungsional = $this->ComboJabatanFungsional[0]['id'];
  		}
  		
      
      $this->ComboJenisPegawai=$this->Obj->GetComboJenisPegawai();
  		$this->label_jenis_pegawai=$this->GetLabelFromCombo($this->ComboJenisPegawai,$this->jenis_pegawai);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_pegawai', 
      array('jenis_pegawai', $this->ComboJenisPegawai, $this->jenis_pegawai, 'true', ''), Messenger::CurrentRequest);
            
  		$this->label_jabatan_fungsional=$this->GetLabelFromCombo($this->ComboJabatanFungsional,$this->jabatan_fungsional);
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabatan_fungsional', 
      array('jabatan_fungsional', $this->ComboJabatanFungsional, $this->jabatan_fungsional, '', ''), Messenger::CurrentRequest);
	
      //create paging 
      $this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
      $this->ComboFungsional=$this->Obj->GetComboFungsional($this->jabatan_fungsional);
      $totalData = sizeof($this->ComboUnitKerja);
		
  		$itemViewed = $totalData;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataPegawai($startRec, $itemViewed,$this->jabatan_fungsional,$this->jenis_pegawai);
  		
  		for ($i=0; $i<sizeof($dataPegawai); $i++){
  		  $this->dataJumlah[$dataPegawai[$i]['unit_kerja']][$dataPegawai[$i]['fungsional']][$dataPegawai[$i]['jenis_kelamin']]=$dataPegawai[$i]['jumlah'];
      }
      
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .'&jabatan_fungsional=' . Dispatcher::Instance()->Encrypt($this->jabatan_fungsional)
        .'&jenis_pegawai=' . Dispatcher::Instance()->Encrypt($this->jenis_pegawai)
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
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_pegawai_fungsional', 'laporanPegawai', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_pegawai_fungsional', 'laporanPegawai', 'view', 'xls')
        .'&jabatan_fungsional=' . Dispatcher::Instance()->Encrypt($this->jabatan_fungsional)
        .'&jenis_pegawai=' . Dispatcher::Instance()->Encrypt($this->jenis_pegawai)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
      $this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_pegawai_fungsional', 'rtfLaporanPegawai', 'view', 'html')
        .'&jabatan_fungsional=' . Dispatcher::Instance()->Encrypt($this->jabatan_fungsional)
        .'&jenis_pegawai=' . Dispatcher::Instance()->Encrypt($this->jenis_pegawai)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1));
      
      
        if (empty($this->ComboUnitKerja)) {
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
    		} else {
    			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
    			$encPage = Dispatcher::Instance()->Encrypt($decPage);
    			$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
    			
    			$totalKolomFungsional=sizeof($this->ComboFungsional);
    			$this->mrTemplate->AddVar('content', 'GOLONGAN_COLSPAN', $totalKolomFungsional*2);
    			
    			//Header Fungsional
    			for ($i=0; $i<$totalKolomFungsional; $i++){
    			   $this->mrTemplate->AddVar('kolom_fungsional','GOLONGAN',$this->ComboFungsional[$i]['name']);
    			   $this->mrTemplate->parseTemplate('kolom_fungsional', 'a');
          }
          
          //Header Nomor Kolom
    			for ($i=0; $i<=$totalKolomFungsional; $i++){
    			   $this->mrTemplate->AddVar('kolom_nomor_kolom','NOMOR_KOLOM',$i+3);
    			   $this->mrTemplate->parseTemplate('kolom_nomor_kolom', 'a');
          }
          
          //Header Jenis Kelamin + 1 dengan kolom jumlah
    			for ($i=0; $i<=$totalKolomFungsional; $i++){
    			   $this->mrTemplate->parseTemplate('kolom_kelamin', 'a');
          }
    			
    			$total=0;
    			$temp=sizeof($this->ComboUnitKerja);
    			$this->ComboUnitKerja[$temp]['id']='TOTAL';
    			$this->ComboUnitKerja[$temp]['name']='TOTAL';
    			for ($i=0; $i<sizeof($this->ComboUnitKerja); $i++) {
    					$no = $i+$data['start'];
    					if ($i<$temp) $dataUnit[$i]['no'] = $no;
    					if ($no % 2 != 0) {
        			  $dataUnit[$i]['class_name'] = 'table-common-even';
        			}else{
        				$dataUnit[$i]['class_name'] = '';
        			}
        			$dataUnit[$i]['unit_kerja']=$this->ComboUnitKerja[$i]['name'];
        			$total_l=0;
        			$total_p=0;
        			$this->mrTemplate->clearTemplate('jumlah', 'a');
        			for ($ii=0; $ii<$totalKolomFungsional; $ii++){
        			   $tempDataL=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboFungsional[$ii]['id']]['L'];
        			   $tempDataP=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboFungsional[$ii]['id']]['P'];
        			   
        			   if ($tempDataL=='') $tempDataL=0;
                 if ($tempDataP=='') $tempDataP=0;
                 
                 $this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboFungsional[$ii]['id']]['L'] += $tempDataL;
                 $this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboFungsional[$ii]['id']]['P'] += $tempDataP;
                 
                 $total_l +=$tempDataL;
                 $total_p +=$tempDataP;
                 
                 if ($tempDataL<=0){ $tempDataL=''; }
                 if ($tempDataP<=0){ $tempDataP=''; } 
                 
                 $this->mrTemplate->AddVar('jumlah','JML_L',$tempDataL);
                 $this->mrTemplate->AddVar('jumlah','JML_P',$tempDataP);
    			       $this->mrTemplate->parseTemplate('jumlah', 'a');
              }
              
              $this->mrTemplate->AddVar('jumlah','JML_L',$total_l);
              $this->mrTemplate->AddVar('jumlah','JML_P',$total_p);
    			    $this->mrTemplate->parseTemplate('jumlah', 'a');
              
    				  $this->mrTemplate->AddVars('table_item', $dataUnit[$i], '');
    				  $this->mrTemplate->parseTemplate('table_item', 'a');	 
    			}
      }      
   }
}
   

?>