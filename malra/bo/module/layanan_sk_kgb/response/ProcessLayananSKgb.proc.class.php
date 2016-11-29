<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_sk_kgb/business/layanankgb.class.php';

class Process {
	private $_POST;
	private $Obj;
	private $user;
	private $pageView;
	private $pageInput;

	private $cssDone = "notebox-done";
	private $cssAlert = "notebox-alert";
	private $cssFail = "notebox-warning";

	private $return;
	private $decId;
	private $encId;

	public function __construct() {
		$this->Obj = new LayananKgb;
		$this->_POST = $_POST->AsArray();
		$this->_POST['dataId'] = $this->decId = Dispatcher::Instance()->Decrypt($this->_POST['dataId']);
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = array('layanan_sk_kgb','layananSKgb','view','html');
		$this->pageInput = array('layanan_sk_kgb','inputLayananSKgb','view','html');
	}

	function update() {
		$detail = $this->Obj->GetSkKgbById($this->decId);
        if(isset($this->_POST['tgl_sk_year']) && isset($this->_POST['tgl_sk_mon']) && isset($this->_POST['tgl_sk_day']))
            $this->_POST['tgl_sk'] = $this->_POST['tgl_sk_year'].'-'.$this->_POST['tgl_sk_mon'].'-'.$this->_POST['tgl_sk_day'];
        if(isset($this->_POST['start_sk_year']) && isset($this->_POST['start_sk_mon']) && isset($this->_POST['start_sk_day']))
            $this->_POST['start_sk'] = $this->_POST['start_sk_year'].'-'.$this->_POST['start_sk_mon'].'-'.$this->_POST['start_sk_day'];
        if(isset($this->_POST['next_sk_year']) && isset($this->_POST['next_sk_mon']) && isset($this->_POST['next_sk_day']))
            $this->_POST['next_sk'] = $this->_POST['next_sk_year'].'-'.$this->_POST['next_sk_mon'].'-'.$this->_POST['next_sk_day'];
        
		$param=array(
			$detail['id_peg'],
			$this->_POST['no_sk'],
			$this->_POST['tgl_sk'],
			$detail['id_pngkt'],
			$detail['mk_thn_old_kgb'],
			$detail['mk_bln_old_kgb'],
			$detail['new_id_gapok'],
			$this->_POST['new_gaji'],
			$this->_POST['masa_kerja_tahun'],
			$this->_POST['masa_kerja_bulan'],
			$this->_POST['start_sk'],
			$this->_POST['next_sk'],
			$this->_POST['pejabat_jbtn_sk'],
			$this->_POST['pejabat_sk'],
			$this->_POST['pejabat_pngkt_sk'],
			$this->_POST['pejabat_nip_sk'],
			trim($this->_POST['tembusan_sk']),
			$this->decId
		);
		$result = $this->Obj->update($param);
		
		if ($result){
			$msg = array(NULL, 'Penyimpanan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageInput;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menyimpan Data', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}

	public function add() {
		// echo "<pre>";
		// var_dump($_POST->AsArray());
		// echo "</pre>";
		// exit();
		$pegawai = $this->Obj->GetDataPegawai($this->_POST['id_kgb']);
		// var_dump($pegawai);
		// exit();
        if(isset($this->_POST['tgl_sk_year']) && isset($this->_POST['tgl_sk_mon']) && isset($this->_POST['tgl_sk_day']))
            $this->_POST['tgl_sk'] = $this->_POST['tgl_sk_year'].'-'.$this->_POST['tgl_sk_mon'].'-'.$this->_POST['tgl_sk_day'];
        if(isset($this->_POST['start_sk_year']) && isset($this->_POST['start_sk_mon']) && isset($this->_POST['start_sk_day']))
            $this->_POST['start_sk'] = $this->_POST['start_sk_year'].'-'.$this->_POST['start_sk_mon'].'-'.$this->_POST['start_sk_day'];
        if(isset($this->_POST['next_sk_year']) && isset($this->_POST['next_sk_mon']) && isset($this->_POST['next_sk_day']))
            $this->_POST['next_sk'] = $this->_POST['next_sk_year'].'-'.$this->_POST['next_sk_mon'].'-'.$this->_POST['next_sk_day'];
		
		$param=array(
			$this->_POST['id_kgb'],
			$pegawai['peg_id'],
			$this->_POST['no_sk'],
			$this->_POST['tgl_sk'],
			$pegawai['pngkt'],
			$pegawai['mk_thn_kgb_old'],
			$pegawai['mk_bln_kgb_old'],
			NULL,
			$this->_POST['new_gaji'],
			$this->_POST['masa_kerja_tahun'],
			$this->_POST['masa_kerja_bulan'],
			$this->_POST['start_sk'],
			$this->_POST['next_sk'],
			$this->_POST['pejabat_jbtn_sk'],
			$this->_POST['pejabat_sk'],
			$this->_POST['pejabat_pngkt_sk'],
			$this->_POST['pejabat_nip_sk'],
			trim($this->_POST['tembusan_sk'])
		);
		$result = $this->Obj->add($param);
		if ($result){
			$msg = array(NULL, 'Penyimpanan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageInput;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menyimpan Data', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}
}
?>