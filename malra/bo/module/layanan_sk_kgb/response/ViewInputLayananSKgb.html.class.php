<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_sk_kgb/business/layanankgb.class.php';

class ViewInputLayananSKgb extends HtmlResponse {
   
	public function TemplateModule(){
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/layanan_sk_kgb/'.GTFWConfiguration::GetValue('application', 'template_address').'/');
		$this->SetTemplateFile('view_input_layanan_kgb.html');    
	} 
   
	public function ProcessRequest(){
		$this->Obj = new LayananKgb();
		
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
        
        if(isset($data['input']['tgl_sk_year']) && isset($data['input']['tgl_sk_mon']) && isset($data['input']['tgl_sk_day']))
            $data['input']['tgl_sk'] = $data['input']['tgl_sk_year'].'-'.$data['input']['tgl_sk_mon'].'-'.$data['input']['tgl_sk_day'];
        
        if(isset($data['input']['start_sk_year']) && isset($data['input']['start_sk_mon']) && isset($data['input']['start_sk_day']))
            $data['input']['start_sk'] = $data['input']['start_sk_year'].'-'.$data['input']['start_sk_mon'].'-'.$data['input']['start_sk_day'];
    
        if(isset($data['input']['next_sk_year']) && isset($data['input']['next_sk_mon']) && isset($data['input']['next_sk_day']))
            $data['input']['next_sk'] = $data['input']['next_sk_year'].'-'.$data['input']['next_sk_mon'].'-'.$data['input']['next_sk_day'];


        if(isset($data['input']['issue_date_year']) && isset($data['input']['issue_date_mon']) && isset($data['input']['issue_date_day']))
            $data['input']['issue_date'] = $data['input']['issue_date_year'].'-'.$data['input']['issue_date_mon'].'-'.$data['input']['issue_date_day'];
		
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
        // print_r($id);
        $data['id_kgb'] = $id;
        $data['pegawai'] = $this->Obj->GetDataPegawai($id);
        $check = $this->Obj->CountSkKgbByKgbId($id);
        if($check > 0) {
            $data['sk_pangkat'] = $this->Obj->GetSkKgbByKgbId($id);
            $data['input'] = $data['sk_pangkat'];
            // echo "<pre>"; var_dump($data['input']); echo "</pre>";
        }
		
        $this->title = 'Layanan Kenaikan Gaji Berkala';

		// links
		$this->listUrl = Dispatcher::Instance()->GetUrl('layanan_sk_kgb','LayananSKgb','view','html');
		$this->inputUrl = Dispatcher::Instance()->GetUrl('layanan_sk_kgb','inputLayananSKgb','do','html');
		$this->deleteUrl = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
		"&urlDelete=".Dispatcher::Instance()->Encrypt('layanan_sk_kgb|deleteLayananSKgb|do|html').
		"&urlReturn=".Dispatcher::Instance()->Encrypt('layanan_sk_kgb|LayananSKgb|view|html').
		"&label=".Dispatcher::Instance()->Encrypt($this->title);
		
		// end of links
		
        $date_start = 1970;
        $date_end = date('Y') + 5;
        
		// combo
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_sk', 
            array((isset($data['input']['tgl_sk']) ? $data['input']['tgl_sk'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
   
        Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'start_sk', 
            array((isset($data['input']['start_sk']) ? $data['input']['start_sk'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
  
       Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'next_sk', 
            array((isset($data['input']['next_sk']) ? $data['input']['next_sk'] : date('Y-m-d')), $date_start, $date_end, 'true', ''), Messenger::CurrentRequest);
		// end of combo

		return $data;   
	}  
   
	public function ParseTemplate ($data = NULL){
		if ($data['pesan']){
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
		
        $this->mrTemplate->addVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('layanan_sk_kgb', 'inputLayananSKgb', 'do', 'html'));
        
		$this->mrTemplate->AddVar('content', 'TITLE', strtoupper($this->title));
		$this->mrTemplate->AddVar('content', 'ID_KGB', $data['id_kgb']);
		$this->mrTemplate->AddVars('content', $data['input']);
        if(isset($data['input']['id'])) {
            $this->mrTemplate->addVar('button_cetak', 'SHOW', 'YES');
            $this->mrTemplate->addVar('button_cetak', 'URL_RTF', Dispatcher::Instance()->GetUrl('layanan_sk_kgb', 'CetakSkLayananKgb', 'view', 'html').'&id='.$data['input']['id']);
            $pegawai = $data['input'];
        } else {
            $this->mrTemplate->addVar('button_cetak', 'SHOW', 'NO');
            $pegawai = $data['pegawai'];
            $pegawai['pejabat_jbtn_sk'] = $pegawai['pjbt_kgb_old'];
            $this->mrTemplate->addVars('content', $pegawai);
        }
		
        $pegawai['kgb_berlaku_old'] = $this->date2string($pegawai['kgb_berlaku_old']);
        $pegawai['tgl_kgb_lalu'] = $this->date2string($pegawai['tgl_kgb_lalu']);
        $pegawai['gaji_kgb'] = 'Rp '. $this->rupiah($pegawai['gaji_kgb']) .',-';
        
        // $pegawai['new_gaji'] = 'Rp '. $this->rupiah($pegawai['new_gaji']) .',-';

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