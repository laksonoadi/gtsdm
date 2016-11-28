<?php

set_time_limit(0);

require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_rekapitulasi/business/laporan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewLaporanPegawai extends HtmlResponse{
	function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_rekapitulasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_laporan_pegawai.html');
	}
   
	function GetLabelFromCombo($ArrData,$Nilai){
		for ($i=0; $i<sizeof($ArrData); $i++){
			if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
		}
		return '';
	}
   
	function ProcessRequest(){
		$this->Obj=new Laporan;
		$this->ObjSatker=new SatuanKerja;
  		
  		if(isset($_POST['cari'])) {
 			$this->Obj->filter['jabatan_fungsional'] = strval($_POST['jabatan_fungsional']);
			$this->Obj->filter['jenisfungsional'] = strval($_POST['jabatan_fungsional']);
			$this->Obj->filter['unit'] = strval($_POST['unit']);
			$this->Obj->filter['status'] = strval($_POST['status']);
			$this->Obj->filter['jenis'] = strval($_POST['jenis']);
			$this->Obj->filter['golongan'] = strval($_POST['golongan']);
			$this->Obj->filter['fungsional'] = strval($_POST['fungsional']);
			$this->Obj->filter['pendidikan'] = strval($_POST['pendidikan']);
			
			$this->berdasarkan = strval($_POST['berdasarkan']);
				
			$this->check_l=$_POST['L'];
			$this->check_p=$_POST['P'];
			$this->check_x=$_POST['X'];
			$this->check_t=$_POST['T'];
			$this->check_0=$_POST['0'];
			
			$this->jumlah=($this->check_l=='on'?1:0)+($this->check_p=='on'?1:0)+($this->check_x=='on'?1:0)+($this->check_t=='on'?1:0);
  		} else {
  			$this->Obj->filter['jabatan_fungsional'] = 'all';
			$this->Obj->filter['jenisfungsional'] = 'all';
			$this->Obj->filter['unit'] = 'all';
			$this->Obj->filter['status'] = 'all';
			$this->Obj->filter['jenis'] = 'all';
			$this->Obj->filter['golongan'] = 'all';
			$this->Obj->filter['fungsional'] = 'all';
			$this->Obj->filter['pendidikan'] = 'all';
			
			$this->berdasarkan = 'unit';
			
			$this->check_l='on';
			$this->check_p='on';
			$this->check_x='on';
			$this->check_t='on';
			$this->check_0='on';
			$this->jumlah=4;
  		}
		
		$this->Obj->getVariabelGlobal();
		$this->judul=$this->Obj->judul;
		$this->tanggal=$this->Obj->IndonesianDate(date('Y-m-d'),"YYYY-MM-DD");
		
      
		$this->ComboJabatanFungsional=$this->Obj->GetComboJabatanFungsional();
  		$this->label_jabatan_fungsional=$this->GetLabelFromCombo($this->ComboJabatanFungsional,$this->Obj->filter['jabatan_fungsional']);
		$this->label['jenisfungsional']=$this->label_jabatan_fungsional;
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jabatan_fungsional', array('jabatan_fungsional', $this->ComboJabatanFungsional, $this->Obj->filter['jabatan_fungsional'], 'true', ''), Messenger::CurrentRequest);
		
		// $this->ComboUnit=$this->Obj->GetComboUnitKerja(true);
		$this->ComboUnit=$this->ObjSatker->GetSatuanKerjaByUserId();
		$this->label['unit']=$this->GetLabelFromCombo($this->ComboUnit,$this->Obj->filter['unit']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit', array('unit', $this->ComboUnit, $this->Obj->filter['unit'], 'true', ' style="width:200px"'), Messenger::CurrentRequest);
		
		$this->ComboStatus=$this->Obj->GetComboVariabel2('status');
		$this->label['status']=$this->GetLabelFromCombo($this->ComboStatus,$this->Obj->filter['status']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $this->ComboStatus, $this->Obj->filter['status'], 'true', ''), Messenger::CurrentRequest);
		
		$this->ComboJenis=$this->Obj->GetComboVariabel2('jenis');
		$this->label['jenis']=$this->GetLabelFromCombo($this->ComboJenis,$this->Obj->filter['jenis']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis', array('jenis', $this->ComboJenis, $this->Obj->filter['jenis'], 'true', ''), Messenger::CurrentRequest);
		
		$this->ComboGolongan=$this->Obj->GetComboVariabel2('golongan');
		$this->label['golongan']=$this->GetLabelFromCombo($this->ComboGolongan,$this->Obj->filter['golongan']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan', array('golongan', $this->ComboGolongan, $this->Obj->filter['golongan'], 'true', ''), Messenger::CurrentRequest);
		
		$this->ComboFungsional=$this->Obj->GetComboVariabel2('fungsional');
		$this->label['fungsional']=$this->GetLabelFromCombo($this->ComboFungsional,$this->Obj->filter['fungsional']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'fungsional', array('fungsional', $this->ComboFungsional, $this->Obj->filter['fungsional'], 'true', 'style="width:500px"'), Messenger::CurrentRequest);
		
		$this->ComboPendidikan=$this->Obj->GetComboVariabel2('pendidikan');
		$this->label['pendidikan']=$this->GetLabelFromCombo($this->ComboPendidikan,$this->Obj->filter['pendidikan']);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'pendidikan', array('pendidikan', $this->ComboPendidikan, $this->Obj->filter['pendidikan'], 'true', ''), Messenger::CurrentRequest);
		
		$var=array_keys($this->Obj->query_filter);
		$this->kriteria=array();
		for ($i=0; $i<sizeof($var); $i++){
			if(($this->Obj->filter[$var[$i]] != "all")&&($this->Obj->filter[$var[$i]]!='')) {
				$this->kriteria[] = $this->label[$var[$i]];
			}
		}
		$this->kriteria = 'Dengan Kriteria ' . implode(', ', $this->kriteria);
	
		//create paging 
		$this->ComboUnitKerja=$this->Obj->GetComboUnitKerja();
		$this->ComboVariabel=$this->Obj->GetComboVariabel($this->berdasarkan,$this->Obj->filter['jabatan_fungsional']);
		$totalData = sizeof($this->ComboUnitKerja);
		
  		$itemViewed = $totalData;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		$dataPegawai = $this->Obj->GetDataPegawai($startRec, $itemViewed,$this->Obj->filter['jabatan_fungsional'],$this->berdasarkan);
  		
  		for ($i=0; $i<sizeof($dataPegawai); $i++){
			$this->dataJumlah[$dataPegawai[$i]['unit_kerja']][$dataPegawai[$i]['nama']][$dataPegawai[$i]['jenis_kelamin']]=$dataPegawai[$i]['jumlah'];
		}
		$this->addon_url =""
		.'&jabatan_fungsional=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['jabatan_fungsional'])
		.'&jenisfungsional=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['jenisfungsional'])
		.'&unit=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['unit'])
		.'&status=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['status'])
		.'&jenis=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['jenis'])
		.'&golongan=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['golongan'])
		.'&fungsional=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['fungsional'])
		.'&pendidikan=' . Dispatcher::Instance()->Encrypt($this->Obj->filter['pendidikan'])
		.'&berdasarkan=' . Dispatcher::Instance()->Encrypt($this->berdasarkan)
		.'&L=' . Dispatcher::Instance()->Encrypt($this->check_l)
		.'&P=' . Dispatcher::Instance()->Encrypt($this->check_p)
		.'&X=' . Dispatcher::Instance()->Encrypt($this->check_x)
		.'&T=' . Dispatcher::Instance()->Encrypt($this->check_t)
		.'&0=' . Dispatcher::Instance()->Encrypt($this->check_0)
		.'&jumlah=' . Dispatcher::Instance()->Encrypt($this->jumlah)
        .'&cari=' . Dispatcher::Instance()->Encrypt(1);
		
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType.$this->addon_url);
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here
        
		$msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataPegawai'] = $dataPegawai;
  		$return['start'] = $startRec+1;
        
  		return $return;
	}
   
	function ParseTemplate($data = NULL){
		
		if($this->Pesan){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}
		  
		$this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_rekapitulasi', 'laporanPegawai', 'view', 'html') );
		$this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_rekapitulasi', 'laporanPegawai', 'view', 'xls').$this->addon_url);
		
		$this->mrTemplate->AddVar('content', 'URL_RTF', Dispatcher::Instance()->GetUrl('laporan_rekapitulasi', 'rtfLaporanPegawai', 'view', 'html').$this->addon_url);
		
		$this->mrTemplate->AddVar('content', 'JUMLAH_CHECK', $this->jumlah);
		$this->mrTemplate->AddVar('content', 'SELECTED_'.strtoupper($this->berdasarkan), 'SELECTED');
		$this->mrTemplate->AddVar('content', 'JUDUL', $this->judul[$this->berdasarkan]);
		$this->mrTemplate->AddVar('content', 'FUNGSIONAL', $this->label_jabatan_fungsional);
		$this->mrTemplate->AddVar('content', 'TANGGAL', $this->tanggal);
		$this->mrTemplate->AddVar('content', 'KRITERIA', $this->kriteria);
		
		$this->mrTemplate->AddVar('kolom_status', 'JUMLAH_CHECK', $this->jumlah);
		$this->mrTemplate->AddVar('kolom_nomor_kolom', 'JUMLAH_CHECK', $this->jumlah);
		$this->mrTemplate->AddVar('kolom_status2', 'JUMLAH_CHECK', $this->jumlah);
		$this->mrTemplate->AddVar('kolom_nomor_kolom2', 'JUMLAH_CHECK', $this->jumlah);
		
		$this->mrTemplate->AddVar('content', 'CHECKED_L', $this->check_l=='on'?'checked':'');
		$this->mrTemplate->AddVar('content', 'CHECKED_P', $this->check_p=='on'?'checked':'');
		$this->mrTemplate->AddVar('content', 'CHECKED_X', $this->check_x=='on'?'checked':'');
		$this->mrTemplate->AddVar('content', 'CHECKED_T', $this->check_t=='on'?'checked':'');
		$this->mrTemplate->AddVar('content', 'CHECKED_0', $this->check_0=='on'?'checked':'');
      
      
        if (empty($this->ComboUnitKerja)) {
    		$this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
    	} else {
    		$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
			$encPage = Dispatcher::Instance()->Encrypt($decPage);
    		$this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
    			
    		$totalKolom=sizeof($this->ComboVariabel);
    		$this->mrTemplate->AddVar('content', 'GOLONGAN_COLSPAN', $totalKolom*$this->jumlah);
			$this->mrTemplate->AddVar('content', 'LEBAR_TABEL', strval(((($totalKolom+1)*$this->jumlah)*25)+500).'px');
    			
    		//Header Pendidikan
    		for ($i=0; $i<$totalKolom; $i++){
				$this->mrTemplate->AddVar('kolom_status','GOLONGAN',$this->ComboVariabel[$i]['name']);
				$this->mrTemplate->parseTemplate('kolom_status', 'a');
				
				$this->mrTemplate->AddVar('kolom_status2','GOLONGAN',$this->ComboVariabel[$i]['name']);
				$this->mrTemplate->parseTemplate('kolom_status2', 'a');
			}
          
			//Header Nomor Kolom
    		for ($i=0; $i<=$totalKolom; $i++){
				$this->mrTemplate->AddVar('kolom_nomor_kolom','NOMOR_KOLOM',$i+3);
				$this->mrTemplate->parseTemplate('kolom_nomor_kolom', 'a');
			}
          
			//Header Jenis Kelamin + 1 dengan kolom jumlah
    		for ($i=0; $i<=$totalKolom; $i++){
				$this->mrTemplate->AddVar('kolom_kelamin', 'L', $this->check_l=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin', 'P', $this->check_p=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin', 'X', $this->check_x=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin', 'T', $this->check_t=='on'?'':'none');
				$this->mrTemplate->parseTemplate('kolom_kelamin', 'a');
				
				$this->mrTemplate->AddVar('kolom_kelamin2', 'L', $this->check_l=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin2', 'P', $this->check_p=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin2', 'X', $this->check_x=='on'?'':'none');
				$this->mrTemplate->AddVar('kolom_kelamin2', 'T', $this->check_t=='on'?'':'none');
				$this->mrTemplate->parseTemplate('kolom_kelamin2', 'a');
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
				
				$spasi=''; for ($ii=0; $ii<$this->ComboUnitKerja[$i]['level']; $ii++) $spasi.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
				
        		$dataUnit[$i]['unit_kerja']=$spasi.$this->ComboUnitKerja[$i]['name'];
        		$total_l=0;
        		$total_p=0;
				$total_X=0;
				$total_T=0;
				
        		$this->mrTemplate->clearTemplate('jumlah', 'a');
        		for ($ii=0; $ii<$totalKolom; $ii++){
					$tempDataL=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['L'];
					$tempDataP=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['P'];
					$tempDataX=$this->dataJumlah[$this->ComboUnitKerja[$i]['id']][$this->ComboVariabel[$ii]['id']]['X'];
        			   
					if ($tempDataL=='') $tempDataL=0;
					if ($tempDataP=='') $tempDataP=0;
					if ($tempDataX=='') $tempDataX=0;
					
					//$tempDataT=($this->check_x=='on'?$tempDataX:0)+($this->check_p=='on'?$tempDataP:0)+($this->check_l=='on'?$tempDataL:0);
					$tempDataT=$tempDataX+$tempDataP+$tempDataL;
                 
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['L'] += $tempDataL;
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['P'] += $tempDataP;
					$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$ii]['id']]['X'] += $tempDataX;
                 
					$total_l +=$tempDataL;
					$total_p +=$tempDataP;
					$total_X +=$tempDataX;
					$total_T +=$tempDataT;
                 
					if ($tempDataL<=0){ $tempDataL=($this->check_0=='on'?'0':''); }
					if ($tempDataP<=0){ $tempDataP=($this->check_0=='on'?'0':''); } 
					if ($tempDataX<=0){ $tempDataX=($this->check_0=='on'?'0':''); } 
					if ($tempDataT<=0){ $tempDataT=($this->check_0=='on'?'0':''); } 
                 
					$this->mrTemplate->AddVar('jumlah','JML_L',$tempDataL);
					$this->mrTemplate->AddVar('jumlah','JML_P',$tempDataP);
					$this->mrTemplate->AddVar('jumlah','JML_X',$tempDataX);
					$this->mrTemplate->AddVar('jumlah','JML_T',$tempDataT);
					
					$this->mrTemplate->AddVar('jumlah', 'L', $this->check_l=='on'?'':'none');
					$this->mrTemplate->AddVar('jumlah', 'P', $this->check_p=='on'?'':'none');
					$this->mrTemplate->AddVar('jumlah', 'X', $this->check_x=='on'?'':'none');
					$this->mrTemplate->AddVar('jumlah', 'T', $this->check_t=='on'?'':'none');
					
    			    $this->mrTemplate->parseTemplate('jumlah', 'a');
				}
              
				$this->mrTemplate->AddVar('jumlah','JML_L',$total_l);
				$this->mrTemplate->AddVar('jumlah','JML_P',$total_p);
				$this->mrTemplate->AddVar('jumlah','JML_X',$total_X);
				$this->mrTemplate->AddVar('jumlah','JML_T',$total_T);
				
				$this->mrTemplate->AddVar('jumlah', 'L', $this->check_l=='on'?'':'none');
				$this->mrTemplate->AddVar('jumlah', 'P', $this->check_p=='on'?'':'none');
				$this->mrTemplate->AddVar('jumlah', 'X', $this->check_x=='on'?'':'none');
				$this->mrTemplate->AddVar('jumlah', 'T', $this->check_t=='on'?'':'none');
					
    			$this->mrTemplate->parseTemplate('jumlah', 'a');
              
    			$this->mrTemplate->AddVars('table_item', $dataUnit[$i], '');
    			$this->mrTemplate->parseTemplate('table_item', 'a');	 
    		}
			
			
			$ring_total['JML_L']=0;
			$ring_total['JML_P']=0;
			$ring_total['JML_X']=0;
			$ring_total['JML_T']=0;
			
			for ($i=0; $i<$totalKolom; $i++){
				$ringkasan['JML_L']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['L']/2;
				$ringkasan['JML_P']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['P']/2;
				$ringkasan['JML_X']=$this->dataJumlah[$this->ComboUnitKerja[$temp]['id']][$this->ComboVariabel[$i]['id']]['X']/2;
				$ringkasan['JML_T']=$ringkasan['JML_L']+$ringkasan['JML_P']+$ringkasan['JML_X'];
				if ($total_T>0){
					$ringkasan['PERSEN']=number_format(($ringkasan['JML_T']/$total_T)*100, 2, '.', '');
				}else{
					$ringkasan['PERSEN']=number_format(0, 2, '.', '');
				}
				
				$ringkasan['NO']=$i+1;
				$ringkasan['GOLONGAN']=$this->ComboVariabel[$i]['name'];
				
				$ringkasan['L']=$this->check_l=='on'?'':'none';
				$ringkasan['P']=$this->check_p=='on'?'':'none';
				$ringkasan['X']=$this->check_x=='on'?'':'none';
				$ringkasan['T']=$this->check_t=='on'?'':'none';
				
				$ring_total['JML_L'] += $ringkasan['JML_L'];
				$ring_total['JML_P'] += $ringkasan['JML_P'];
				$ring_total['JML_X'] += $ringkasan['JML_X'];
				$ring_total['JML_T'] += $ringkasan['JML_T'];
				$ring_total['PERSEN'] += $ringkasan['PERSEN'];
				
				$this->mrTemplate->AddVars('variable_item',$ringkasan,'');
				$this->mrTemplate->parseTemplate('variable_item', 'a');
			}

				$this->mrTemplate->AddVar('content', 'L', $this->check_l=='on'?'':'none');
				$this->mrTemplate->AddVar('content', 'P', $this->check_p=='on'?'':'none');
				$this->mrTemplate->AddVar('content', 'X', $this->check_x=='on'?'':'none');
				$this->mrTemplate->AddVar('content', 'T', $this->check_t=='on'?'':'none');
			
			$ring_total['GOLONGAN']='TOTAL';
			$ring_total['NO']='';
			$ring_total['PERSEN']=round($ring_total['PERSEN']);
			$ringkasan=$ring_total;
			$this->mrTemplate->AddVars('variable_item',$ringkasan,'');
			$this->mrTemplate->parseTemplate('variable_item', 'a');
		}      
	}
}
   

?>