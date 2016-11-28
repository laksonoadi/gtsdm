<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_rekapitulasi_abk/business/laporan.class.php';
   
class ViewLaporanRekapitulasiAbk extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_rekapitulasi_abk/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_rekapitulasi_abk.html');
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
      
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja']->SqlString()->Raw();
  		} else {
  				$this->unit_kerja = '';
  		}
  		
  		$this->ComboUnitKerja = $this->Obj->GetComboUserUnitKerja();
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, 'false', 'style="width:700px"'), Messenger::CurrentRequest);
	
  		$dataPegawai = $this->Obj->GetDataPegawai(NULL, $this->unit_kerja);
      
        $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  
  		$return['dataPegawai'] = $dataPegawai;
        
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan){
        $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
        $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
        $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
		  
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_rekapitulasi_abk', 'laporanRekapitulasiAbk', 'view', 'html') );
      $dataPegawai = $data['dataPegawai'];
      
        if (empty($dataPegawai)) {
            $this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
        } else {
            $this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
            $str_lvl_offset = $dataPegawai[0]['lv'];
            $i = 0;
            foreach($dataPegawai as $item) {
                $item['no'] = $i + 1;
                if ($item['no'] % 2 != 0) {
                    $item['class_name'] = 'table-common-even';
                } else {
                    $item['class_name'] = '';
                }
                $space = 5;
                
                $item['nama'] = nl2br(wordwrap($item['pegawai_nama_gelar'], 40, "\n"));
                if($item['is_sub'] === '0') {
                    $str_lvl_offset = min($str_lvl_offset, $item['lv']);
                    $item['space'] = str_repeat('&nbsp;', max($item['lv'] - $str_lvl_offset, 0) * $space);
                    
                    $item['satker'] = $item['space'] . nl2br(wordwrap($item['satker_nama'], 40, "\n".$item['space']));
                    if(empty($item['jabatan_nama'])) {
                        $item['jabatan'] = '<strong class="jabatan_kosong" title="Unit Kerja: '.$item['satker_nama'].'">'. $item['satker'] .'</strong>';
                    } else {
                        $item['jabatan'] = '<strong title="Unit Kerja: '.$item['satker_nama'].'">'. $item['space'] . nl2br(wordwrap($item['jabatan_nama'], 40, "\n".$item['space'])) .'</strong>';
                    }
                    // $item['nama'] = '<strong>'. $item['nama'] .'</strong>';
                    $item['jabatan_eformasi'] = '';
                } else {
                    $item['space'] = str_repeat('&nbsp;', max($item['lv'] - $str_lvl_offset + 1, 0) * $space);
                    $item['satker'] = '';
                    if(empty($item['jabatan_nama'])) {
                        $item['jabatan'] = $item['space'] . '-';
                    } else {
                        $item['jabatan'] = $item['space'] . nl2br(wordwrap($item['jabatan_nama'], 40, "\n".$item['space']));
                    }
                }
                
                $sub_list = $item['sub_list'];
                $this->mrTemplate->clearTemplate('table_jabfung_list');
                $this->mrTemplate->clearTemplate('table_jabfung_item');
                if($item['sub_total'] > 0 && !empty($item['sub_list'])) {
                    $this->mrTemplate->AddVar('table_jabfung_list', 'EMPTY', 'NO');
                    foreach($item['sub_list'] as $sub_item) {
                        $sub_item['no'] = ++$i + 1;
                        
                        if ($sub_item['no'] % 2 != 0) {
                            $sub_item['class_name'] = 'table-common-even';
                        } else {
                            $sub_item['class_name'] = '';
                        }
                        
                        $space_nama = str_repeat('&nbsp;', $space);
                        $sub_item['nama'] = $space_nama . nl2br(wordwrap($sub_item['pegawai_nama_gelar'], 40, "\n".$space_nama));
                        
                        $space_jab = str_repeat('&nbsp;', max($item['lv'] - $str_lvl_offset + 1, 0) * $space);
                        if(empty($sub_item['jabatan_nama'])) {
                            $sub_item['jabatan'] = $space_jab . '-';
                        } else {
                            $sub_item['jabatan'] = $space_jab . nl2br(wordwrap($sub_item['jabatan_nama'], 40, "\n".$space_jab));
                        }
                        
                        $this->mrTemplate->AddVars('table_jabfung_item', $sub_item);
                        $this->mrTemplate->parseTemplate('table_jabfung_item', 'a');
                    }
                } else {
                    $this->mrTemplate->AddVar('table_jabfung_list', 'EMPTY', 'YES');
                }
                
                unset($item['sub_list']);
                $this->mrTemplate->AddVars('table_item', $item, '');
                $this->mrTemplate->parseTemplate('table_item', 'a');
                
                $i++;
            }
        }
   }
}
   

?>