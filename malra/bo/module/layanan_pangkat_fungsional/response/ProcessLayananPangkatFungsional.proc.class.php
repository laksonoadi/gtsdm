<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_pangkat_fungsional/business/LayananPangkatFungsional.class.php';

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
		$this->Obj = new LayananPangkatFungsional;
		$this->_POST = $_POST->AsArray();
		$this->_POST['dataId'] = $this->decId = Dispatcher::Instance()->Decrypt($this->_POST['dataId']);
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = array('layanan_pangkat_fungsional','layananPangkatFungsional','view','html');
		$this->pageInput = array('layanan_pangkat_fungsional','inputLayananPangkatFungsional','view','html');
	}

	function update() {
		$detail = $this->Obj->GetSkPangkatById($this->decId);
        if(isset($this->_POST['agree_date_year']) && isset($this->_POST['agree_date_mon']) && isset($this->_POST['agree_date_day']))
            $this->_POST['agree_date'] = $this->_POST['agree_date_year'].'-'.$this->_POST['agree_date_mon'].'-'.$this->_POST['agree_date_day'];
        if(isset($this->_POST['start_sk_year']) && isset($this->_POST['start_sk_mon']) && isset($this->_POST['start_sk_day']))
            $this->_POST['start_sk'] = $this->_POST['start_sk_year'].'-'.$this->_POST['start_sk_mon'].'-'.$this->_POST['start_sk_day'];
        if(isset($this->_POST['issue_date_year']) && isset($this->_POST['issue_date_mon']) && isset($this->_POST['issue_date_day']))
            $this->_POST['issue_date'] = $this->_POST['issue_date_year'].'-'.$this->_POST['issue_date_mon'].'-'.$this->_POST['issue_date_day'];
		$param=array(
			$this->_POST['no_sk'],
			$this->_POST['agree_no'],
			$this->_POST['agree_date'],
			$detail['id_pend'],
			$detail['id_old_jab'],
			$this->_POST['old_ak'],
			$detail['id_satker'],
			$this->_POST['start_sk'],
			$this->_POST['new_pngkt_name'],
			$detail['new_pngkt'],
			$this->_POST['id_new_jab'],
			$this->_POST['name_new_jab'],
			$this->_POST['new_ak'],
			$detail['mk_thn'],
			$detail['mk_bln'],
			$detail['GjPokok'],
			$detail['id_ref_gjPokok'],
			$this->_POST['issue_place'],
			$this->_POST['issue_date'],
			$this->_POST['official_sk'],
			$detail['barcode_sk'],
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
		$pegawai = $this->Obj->GetDataPegawai($this->_POST['id_pktgol']);
        if(isset($this->_POST['agree_date_year']) && isset($this->_POST['agree_date_mon']) && isset($this->_POST['agree_date_day']))
            $this->_POST['agree_date'] = $this->_POST['agree_date_year'].'-'.$this->_POST['agree_date_mon'].'-'.$this->_POST['agree_date_day'];
        if(isset($this->_POST['start_sk_year']) && isset($this->_POST['start_sk_mon']) && isset($this->_POST['start_sk_day']))
            $this->_POST['start_sk'] = $this->_POST['start_sk_year'].'-'.$this->_POST['start_sk_mon'].'-'.$this->_POST['start_sk_day'];
        if(isset($this->_POST['issue_date_year']) && isset($this->_POST['issue_date_mon']) && isset($this->_POST['issue_date_day']))
            $this->_POST['issue_date'] = $this->_POST['issue_date_year'].'-'.$this->_POST['issue_date_mon'].'-'.$this->_POST['issue_date_day'];
		$param=array(
			$this->_POST['id_pktgol'],
			$this->_POST['no_sk'],
			$this->_POST['agree_no'],
			$this->_POST['agree_date'],
			$pegawai['pend_id'],
			$pegawai['old_jab_id'],
			$this->_POST['old_ak'],
			$pegawai['satker_id'],
			$this->_POST['start_sk'],
			$this->_POST['new_pngkt_name'],
			$pegawai['new_pngkt'],
			$this->_POST['id_new_jab'],
			$this->_POST['name_new_jab'],
			$this->_POST['new_ak'],
			$pegawai['masa_kerja_tahun'],
			$pegawai['masa_kerja_bulan'],
			$pegawai['new_gaji'],
			$pegawai['new_ref_gaji'],
			$this->_POST['issue_place'],
			$this->_POST['issue_date'],
			$this->_POST['official_sk'],
			NULL,
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