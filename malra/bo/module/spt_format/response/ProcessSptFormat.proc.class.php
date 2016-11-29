<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/spt_format/business/sptformat.class.php';

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
		$this->Obj = new SptFormat;
		$this->_POST = $_POST->AsArray();
		$this->_POST['dataId'] = $this->decId = Dispatcher::Instance()->Decrypt($this->_POST['dataId']);
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
		$this->encId = $this->decId;
		$this->pageView = array('spt_format','sptFormat','view','html');
		$this->pageInput = array('spt_format','inputSptFormat','view','html');
		$this->validExt = array(
			'doc'	=> true,
			'docx'	=> true,
			'xls'	=> true,
			'xlsx'	=> true,
			'rtf'	=> true,
			'txt'	=> true
		);
	}

	function update(){
		$check = $this->check();
		if($check !== true) return $check;

		$content = $this->getContentUploadedFile('fileUpload');
		if($this->_POST['aktif'] > 0)$result = $this->Obj->nonaktif(array($this->_POST['kategori']));
		if($content[0] !== ''){
			$param=array($this->_POST['kategori'], $content[0], $content[1], $this->_POST['keterangan'], (int)$this->_POST['aktif'], $this->decId);
			$result = $this->Obj->fullUpdate($param);
		}else{
			$param=array($this->_POST['kategori'], $this->_POST['keterangan'], (int)$this->_POST['aktif'], $this->decId);
			$result = $this->Obj->update($param);
		}
		
		if ($result){
			$msg = array($this->_POST, 'Perubahan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageView;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Mengubah Data<br>Pastikan format file benar', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
		return $return;
	}

	public function add(){
		$check = $this->check();
		if($check !== true) return $check;
		
		$content = $this->getContentUploadedFile('fileUpload');
		$param=array($this->_POST['kategori'], $content[0], $content[1], $this->_POST['keterangan'], (int)$this->_POST['aktif']);
		if($this->_POST['aktif'] > 0)$result = $this->Obj->nonaktif(array($this->_POST['kategori']));
		$result = $this->Obj->add($param);
		
		if ($result){
			$msg = array($this->_POST, 'Penambahan Data Berhasil Dilakukan.', $this->cssDone);
			$return = $this->pageView;
		}else{
			$msg = array($this->_POST, 'Tidak Berhasil Menambah Data<br>Pastikan format file benar', $this->cssFail);
			$return = $this->pageInput;
		}
		
		Messenger::Instance()->Send($return[0],$return[1],$return[2],$return[3], $msg, Messenger::NextRequest);
		
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
			'kategori'		=> 'Kategori',
			'keterangan'	=> 'Keterangan'
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

	private function getContentUploadedFile($name){
		if(isset($_FILES[$name])){
			if(file_exists($_FILES[$name]['tmp_name'])){
				$tmp = explode('.', $_FILES[$name]['name']);
				$ext = end($tmp);
				if(isset($this->validExt[$ext]) and $this->validExt[$ext]){
					return array($ext, addslashes(@file_get_contents($_FILES[$name]['tmp_name'])));
				}
			}
		}
		return array('','');
	}
	
}
?>