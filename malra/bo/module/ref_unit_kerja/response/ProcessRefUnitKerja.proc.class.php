<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/ref_unit_kerja/business/RefUnitKerja.class.php';

class Process{
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
		$this->Obj = new refUnitKerja;
		$this->_POST = $_POST->AsArray();
		$this->_POST['dataId'] = $this->decId = Dispatcher::Instance()->Decrypt($this->_POST['dataId']);
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = array('ref_unit_kerja','refUnitKerja','view','html');
		$this->pageInput = array('ref_unit_kerja','inputRefUnitKerja','view','html');
	}

	public function update(){
		$check = $this->check();
		if($check !== true) return $check;
		
		// Sanitize first
		$input_nama = preg_replace('/\s+/', ' ', trim($this->_POST['nama']));
		$input_desc = trim($this->_POST['nama']);
		
		$param = array($input_nama, $input_desc, $this->decId);
		$result = $this->Obj->update($param);
		
		if ($result){
			$msg = array($this->_POST, 'Perubahan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageView;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Mengubah Data', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}

	public function add(){
		$check = $this->check();
		if($check !== true) return $check;
		
		// Sanitize first
		$input_nama = preg_replace('/\s+/', ' ', trim($this->_POST['nama']));
		$input_desc = trim($this->_POST['nama']);
		
		$param = array($input_nama, $input_desc);
		$result = $this->Obj->add($param);
		
		if ($result){
			$msg = array($this->_POST, 'Penambahan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageView;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menambah Data', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0], $return[1], $return[2], $return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}

	public function delete(){
		$kategori = array();
		$this->Obj->StartTrans();
		if(is_array($this->_POST['idDelete'])){
			$result = true;
			for($i = 0, $m = count($this->_POST['idDelete']); $i < $m; ++$i){
				$tmp = $this->Obj->getKategoriIdAktif($this->_POST['idDelete'][$i]);
				if($tmp)$kategori[] = $tmp;
				$result = $result && $this->Obj->Delete(array($this->_POST['idDelete'][$i]));
			}
		}else{
			$tmp = $this->Obj->getKategoriIdAktif($this->_POST['idDelete']);
			if($tmp)$kategori[] = $tmp;
			$result = $this->Obj->Delete(array($this->_POST['idDelete']));
		}
		$kategori = array_unique($kategori);
		for($i = 0, $m = count($kategori); $i < $m; ++$i){
			$result = $result && $this->Obj->aktivasi(array($kategori[$i]));
		}
		$this->Obj->EndTrans($result);
		
		if ($result){
			$msg = array($this->_POST, 'Penghapusan Data Berhasil Dilakukan.', $this->cssDone);
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menghapus Data', $this->cssFail);
		}
		
		$return = $this->pageView;
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}
	
	private function check(){
		if (isset($this->_POST['btnbalik'])) return $this->pageView;
		
		$mandatory = array(
			'nama'		=> 'Nama Unit Kerja'
		);
		foreach($mandatory as $key => $label){
			if(trim($this->_POST[$key]) == ''){
				$error[] = 'Field '. $label . ' harus diisi';
			}
		}

		if(!empty($error)){
			$return = $this->pageInput;
			$msg = array($this->_POST, implode('<br>', $error), $this->cssAlert);
			Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);

			return $return;
		}
		return true;
	}

}
?>