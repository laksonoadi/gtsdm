<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_kategori/business/sptkategori.class.php';

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
		$this->Obj = new SptKategori;
		$this->_POST = $_POST->AsArray();
		$this->_POST['dataId'] = $this->decId = Dispatcher::Instance()->Decrypt($this->_POST['dataId']);
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = array('spt_kategori','sptKategori','view','html');
		$this->pageInput = array('spt_kategori','inputSptKategori','view','html');
	}

	function update(){
		$check = $this->check();
		if($check !== true) return $check;

		$param=array($this->_POST['nama'], $this->decId);
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
		
		$param=array($this->_POST['nama']);
		$result = $this->Obj->add($param);
		
		if ($result){
			$msg = array($this->_POST, 'Penambahan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageView;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menambah Data', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}

	public function delete(){
		if(is_array($this->_POST['idDelete'])){
			$this->Obj->StartTrans();
			$result = true;
			for($i = 0, $m = count($this->_POST['idDelete']); $i < $m; ++$i){
				$result = $result && $this->Obj->Delete(array($this->_POST['idDelete'][$i]));
			}
			$this->Obj->EndTrans($result);
		}else{
			$result = $this->Obj->Delete(array($this->_POST['idDelete']));
		}
		
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
		
		$mandatory = array('nama' => 'Nama');
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