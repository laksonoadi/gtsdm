<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_pangkat_strategis/business/LayananPangkatStrategis.class.php';

class ViewInputLayananPangkatStrategis extends HtmlResponse {
   
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/layanan_pangkat_strategis/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
		$this->SetTemplateFile('input_layanan_pangkat_strategis.html');    
	} 
   
	public function ProcessRequest(){
		$this->Obj = new LayananPangkatStrategis();
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		if(isset($msg[0])){
			$data['input'] = $msg[0][0];
			$data['pesan'] = $msg[0][1];
			$data['css'] =$msg[0][2];
		} else {
			$data = array(
				'input'	=> array(),
				'pesan'	=> array(),
				'css'	=> array()
			);
		}
        
        if(isset($data['input']['agree_date_year']) && isset($data['input']['agree_date_mon']) && isset($data['input']['agree_date_day']))
            $data['input']['agree_date'] = $data['input']['agree_date_year'].'-'.$data['input']['agree_date_mon'].'-'.$data['input']['agree_date_day'];
        if(isset($data['input']['start_sk_year']) && isset($data['input']['start_sk_mon']) && isset($data['input']['start_sk_day']))
            $data['input']['start_sk'] = $data['input']['start_sk_year'].'-'.$data['input']['start_sk_mon'].'-'.$data['input']['start_sk_day'];
        if(isset($data['input']['issue_date_year']) && isset($data['input']['issue_date_mon']) && isset($data['input']['issue_date_day']))
            $data['input']['issue_date'] = $data['input']['issue_date_year'].'-'.$data['input']['issue_date_mon'].'-'.$data['input']['issue_date_day'];
		
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
        $data['id_pktgol'] = $id;
        $data['pegawai'] = $this->Obj->GetDataPegawai($id);
        $check = $this->Obj->CountSkPangkatByPktgolId($id);
        if($check > 0) {
            $data['sk_pangkat'] = $this->Obj->GetSkPangkatByPktgolId($id);
            $data['input'] = $data['sk_pangkat'];
        }
		
        $this->title = 'Layanan Kenaikan Pangkat Strategis';

		// links
		$this->listUrl = Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis','LayananPangkatStrategis','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis','inputLayananPangkatStrategis','do','html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('layanan_pangkat_strategis|deleteLayananPangkatStrategis|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('layanan_pangkat_strategis|LayananPangkatStrategis|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
        $date_start = 1970;
        $date_end = date('Y') + 5;
        
		// combo
		$arrJabfung = $this->Obj->getComboJabatanFungsional();
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'id_new_jab', array('id_new_jab', $arrJabfung, (isset($data['input']['id_new_jab']) ? $data['input']['id_new_jab'] : NULL), 'false', ''), Messenger::CurrentRequest);
        
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'agree_date', 
            array((isset($data['input']['agree_date']) ? $data['input']['agree_date'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'start_sk', 
            array((isset($data['input']['start_sk']) ? $data['input']['start_sk'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'issue_date', 
            array((isset($data['input']['issue_date']) ? $data['input']['issue_date'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
		// end of combo
		
		return $data;   
	}  
   
	public function ParseTemplate ($data = NULL){
		if ($data['pesan']){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
		
        $this->mrTemplate->addVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis', 'inputLayananPangkatStrategis', 'do', 'html'));
        
		$this->mrTemplate->AddVar('content', 'TITLE', strtoupper($this->title));
		$this->mrTemplate->AddVar('content', 'ID_PKTGOL', $data['id_pktgol']);
		$this->mrTemplate->AddVars('content', $data['input']);
        if(isset($data['input']['id'])) {
            $this->mrTemplate->addVar('button_cetak', 'SHOW', 'YES');
            $this->mrTemplate->addVar('button_cetak', 'URL_RTF', Dispatcher::Instance()->GetUrl('layanan_pangkat_strategis', 'RtfLayananPangkatStrategis', 'view', 'html').'&id='.$data['input']['id']);
            $pegawai = $data['input'];
        } else {
            $this->mrTemplate->addVar('button_cetak', 'SHOW', 'NO');
            $pegawai = $data['pegawai'];
        }
		
        $pegawai['old_jab_id'] = (!empty($pegawai['old_jabstruk_id']) ? $pegawai['old_jabstruk_id'] : $pegawai['old_jabfung_id']);
        $pegawai['old_jab_nama'] = (!empty($pegawai['old_jabstruk_id']) ? $pegawai['old_jabstruk_nama'] : $pegawai['old_jabfung_nama']);
        $pegawai['old_jab_tmt'] = (!empty($pegawai['old_jabstruk_id']) ? $pegawai['old_jabstruk_tmt'] : $pegawai['old_jabfung_tmt']);
        $pegawai['tgl_lahir'] = $this->date2string($pegawai['tgl_lahir']);
        $pegawai['tgl_naik'] = $this->date2string($pegawai['tgl_naik']);
        if(isset($pegawai['old_pngkt']) && $pegawai['old_pngkt'] != '')
            $pegawai['old_pngkt_text'] = $pegawai['old_pngkt_nama'].' ('.$pegawai['old_pngkt'].') / '.$this->date2string($pegawai['old_pngkt_tmt']);
        $pegawai['old_gaji'] = 'Rp '. $this->rupiah($pegawai['old_gaji']) .',-';
        $pegawai['new_gaji'] = 'Rp '. $this->rupiah($pegawai['new_gaji']) .',-';
        $this->mrTemplate->addVars('pegawai', $pegawai);
        $this->mrTemplate->addVars('content', $pegawai, 'PEGAWAI_');

		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', $this->listUrl);
	}
    
    function date2string($date) {
        if($date == '0000-00-00')
            return $date;
        
        $bln = array(
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );
        $arrtgl = explode('-',$date);
        if (sizeof($arrtgl)>2)
            return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
        else
            return $arrtgl[0];
    }
    
    function rupiah($money) {
        return number_format($money, 0, ',', '.');
    }
}
?>