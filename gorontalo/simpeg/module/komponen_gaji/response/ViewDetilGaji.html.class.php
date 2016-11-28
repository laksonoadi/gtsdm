<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppDetilGaji.class.php';

class ViewDetilGaji extends HtmlResponse {

	var $Pesan;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/komponen_gaji/'.GTFWConfiguration::GetValue('application', 'template_address').'');
		$this->SetTemplateFile('view_detil_gaji.html');
	}
	
	function GetTanggalIndonesia($tanggal) {
      $blnarr=array();
	   $blnarr[1]="Januari";
	   $blnarr[2]="Februari";
	   $blnarr[3]="Maret";
	   $blnarr[4]="April";
	   $blnarr[5]="Mei";
	   $blnarr[6]="Juni";
	   $blnarr[7]="Juli";
	   $blnarr[9]="September";
	   $blnarr[8]="Agustus";
	   $blnarr[10]="Oktober";
	   $blnarr[11]="November";
	   $blnarr[12]="Desember";
	
	   $tanggal=explode("-",$tanggal);	   
	   return $tanggal[2]." ".$blnarr[intval($tanggal[1])]." ".$tanggal[0];   
   }
	function ProcessRequest() {
		
		$msg = Messenger::Instance()->Receive(__FILE__);
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
		$this->Data = $msg[0][3];

		$Obj = new AppDetilGaji();
		$this->decId = Dispatcher::Instance()->Decrypt($_GET['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->decDetilId = Dispatcher::Instance()->Decrypt($_GET['id_detil']);
		$this->encDetilId = Dispatcher::Instance()->Encrypt($this->decDetilId);

		$kode = $_POST['kode'];
		$nama = $_POST['nama'];

		if($_GET['id_detil'] != "" && $_GET['input_error'] != "") {
         //update, tp gagal
         //data dari inputannya
         $input = $this->Data;
         $tgl_selected = $this->Data['tanggal_berlaku_year'];
         $tgl_selected .= "-" . $this->Data['tanggal_berlaku_mon'];
         $tgl_selected .= "-" . $this->Data['tanggal_berlaku_day'];
         $op = "edit";
      } elseif($_GET['id_detil'] != "") {
         //echo "mau updatde";
         //baru mw update
         //get data
         $input = $Obj->GetDataById($this->decDetilId);
         //print_r($input);
         $arr_tgl = explode("-", $input['tanggal_berlaku']);
         $tgl_selected = $arr_tgl[0];
         $tgl_selected .= "-" . (int) $arr_tgl[1];
         $tgl_selected .= "-" . $arr_tgl[2];
         $op = "edit";
      } elseif($_GET['input_error'] != "") {
         //add tp gagal
         //data dari inputannya
         $input = $this->Data;
         $tgl_selected = $this->Data['tanggal_berlaku_year'];
         $tgl_selected .= "-" . $this->Data['tanggal_berlaku_mon'];
         $tgl_selected .= "-" . $this->Data['tanggal_berlaku_day'];
         $op = "add";
		} else {
         //first view, mw add
         $input = array();
         $tgl_selected = date('Y-n-d');
         //echo $tgl_selected;
         $op = "add";
		}
      //echo $tgl_selected;
		
      /*GET INFORMASI*/
      $info = $Obj->GetInfo($this->decId);
      /*ENDOF GET INFORMASI */

		Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'tanggal_berlaku',
         array($tgl_selected, 1971, (date('Y')+10)), Messenger::CurrentRequest);

	//view
		$totalData = $Obj->GetCountData($this->decId, $kode, $nama);
		$itemViewed = 20;
		$currPage = 1;
		$startRec = 0 ;
		if(isset($_GET['page'])) {
			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec =($currPage-1) * $itemViewed;
		}
		$dataDetilGaji = $Obj->getData($startRec, $itemViewed, $this->decId, $kode, $nama);
		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType . '&dataId=' . $this->encId . '&id_detil=' . $this->encDetilId . '&cari=' . Dispatcher::Instance()->Encrypt(1));   
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);


		$return['dataDetilGaji'] = $dataDetilGaji;
		$return['start'] = $startRec+1;
		$return['info'] = $info;
		$return['input'] = $input;
		$return['op'] = $op;
		$return['kode'] = $kode;
		$return['nama'] = $nama;

		return $return;
	}
	
	function ParseTemplate($data = NULL) {
		if ($data['info']['otomatis']==1) {
			$data['info']['display_manual']='none';
			$data['info']['readonly_otomatis']='readonly';
		}else{
			$data['info']['display_otomatis']='none';
		}
		$this->mrTemplate->AddVar('content', 'DISPLAY_OTOMATIS', $data['info']['display_otomatis']);
		$this->mrTemplate->AddVar('content', 'READONLY_OTOMATIS', $data['info']['readonly_otomatis']);
		$this->mrTemplate->AddVar('content', 'DISPLAY_MANUAL', $data['info']['display_manual']);
		$this->mrTemplate->AddVar('content', 'KODE', $data['info']['kode']);
		$this->mrTemplate->AddVar('content', 'NAMA', $data['info']['nama']);
		$this->mrTemplate->AddVar('content', 'KETERANGAN', $data['info']['keterangan']);
		$this->mrTemplate->AddVar('content', 'JENIS', $data['info']['jenis']);

		$this->mrTemplate->AddVar('content', 'ID_DETIL', $data['input']['id_detil']);
		$this->mrTemplate->AddVar('content', 'KODE_DETIL', $data['input']['kode_detil']);
		$this->mrTemplate->AddVar('content', 'NAMA_DETIL', $data['input']['nama_detil']);

		$this->mrTemplate->AddVar('content', 'KODE_KOMPONEN', $data['kode']);
		$this->mrTemplate->AddVar('content', 'NAMA_KOMPONEN', $data['nama']);

		#print_r($_POST);
		if(($data['input']['id_detil']!='') AND (!isset($_POST['search']))){
			$this->mrTemplate->AddVar('content', 'EDIT_FUNCTION', 'setXObj()');	
		}

      if($data['input']['setting_detil'] == 'persen') {
         //persen
		   $this->mrTemplate->AddVar('content', 'PERSEN_DETIL', $data['input']['persen_detil']);
		   $this->mrTemplate->AddVar('content', 'PERSEN_IS_CHECKED', 'checked="checked"');
		   #$this->mrTemplate->AddVar('content', 'PERSEN_IS_SHOWN', '');
		   #$this->mrTemplate->AddVar('content', 'NOMINAL_IS_SHOWN', 'display:none');			
      } else {
         //nominal
			$this->mrTemplate->AddVar('content', 'SELECTED_VALUE', 'nominal');
		   $this->mrTemplate->AddVar('content', 'NOMINAL_DETIL', $data['input']['nominal_detil']);
		   $this->mrTemplate->AddVar('content', 'NOMINAL_IS_CHECKED', 'checked="checked"');
		   #$this->mrTemplate->AddVar('content', 'NOMINAL_IS_SHOWN', '');
		   #$this->mrTemplate->AddVar('content', 'PERSEN_IS_SHOWN', 'display:none');
      }
      
      if($data['op'] == 'edit') $url = 'updateDetilGaji';
      else $url = 'addDetilGaji';

	
		$this->mrTemplate->AddVar('content', 'URL_FILTER', Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html').'&dataId=' . $this->encId . '&id_detil=' . $this->encDetilId);

		$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('komponen_gaji', $url, 'do', 'html') . '&dataId=' . $this->encId . '&id_detil=' . $this->encDetilId);
		$this->mrTemplate->AddVar('content', 'URL_KEMBALI', Dispatcher::Instance()->GetUrl('komponen_gaji', 'gaji', 'view', 'html'));
		$this->mrTemplate->AddVar('content', 'URL_RELOAD', Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html') . '&dataId=' . $this->encId);

		if($this->Pesan) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
		}

		if (empty($data['dataDetilGaji'])) {
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
		} else {
         if(isset($_GET['page'])) {
			   $decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
            $encPage = Dispatcher::Instance()->Encrypt($decPage);
         } else {
			   $encPage = Dispatcher::Instance()->Encrypt(1);
         }
			$this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
		
//mulai bikin tombol delete
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
	     if ($lang=='eng'){
          $label="Salary Component Detail";
       }else{
          $label="Detil Komponen Gaji";  
       }
			
			$urlDelete = Dispatcher::Instance()->GetUrl('komponen_gaji', 'deleteDetilGaji', 'do', 'html') . '&dataId=' . $this->encId;
			$urlReturn = Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html') . '&dataId=' . $this->encId;
			Messenger::Instance()->Send('confirm', 'confirmDelete', 'do', 'html', array($label, $urlDelete, $urlReturn),Messenger::NextRequest);
			$this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
//selesai bikin tombol delete

			$dataDetilGaji = $data['dataDetilGaji'];
			for ($i=0; $i<sizeof($dataDetilGaji); $i++) {
				$no = $i+$data['start'];
				$dataDetilGaji[$i]['number'] = $no;
				if ($no % 2 != 0) $dataDetilGaji[$i]['class_name'] = 'table-common-even';
				else $dataDetilGaji[$i]['class_name'] = '';
				
				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);				
				if($i == sizeof($dataDetilGaji)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);
				$idEnc = Dispatcher::Instance()->Encrypt($dataDetilGaji[$i]['id']);
				$dataDetilGaji[$i]['manual']=$data['info']['display_manual'];
				if($dataDetilGaji[$i]['setting'] == "persen") $dataDetilGaji[$i]['nominal_prosentase'] = $dataDetilGaji[$i]['persen'] . " %";
				else $dataDetilGaji[$i]['nominal_prosentase'] = "Rp. " . number_format($dataDetilGaji[$i]['nominal'], 2, ',', '.');
				$dataDetilGaji[$i]['tanggal_berlaku'] = $this->GetTanggalIndonesia($dataDetilGaji[$i]['tanggal_berlaku']);
				$dataDetilGaji[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('komponen_gaji', 'detilGaji', 'view', 'html') . '&dataId=' . $this->encId . '&id_detil=' . $idEnc . '&page=' . $encPage . '&cari=' . $cari;

				$this->mrTemplate->AddVars('data_item', $dataDetilGaji[$i], 'DATA_');
				$this->mrTemplate->parseTemplate('data_item', 'a');	 
			}
		}
	}
}
?>
