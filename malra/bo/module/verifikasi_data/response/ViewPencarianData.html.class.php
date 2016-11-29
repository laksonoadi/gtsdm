<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/business/verifikasi_data.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';

class ViewPencarianData extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/verifikasi_data/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_pencarian_data.html');    
    } 
    
    function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		$data['pesan'] = $msg[0][1];
		$data['css'] = $msg[0][2];
		$data['start'] = $startRec+1;
		
		$Obj = new VerifikasiData;
		$this->ObjPegawai = new DataPegawai;
		$convert=array('15'=>15,'25'=>25,'50'=>50,'100'=>100,'250'=>250);
		$arrStatusData = $Obj->GetComboStatusData();
		$arrJenisData = $Obj->GetComboJenisData();
		
		if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['cari'])) {
				$this->POST=$_POST->AsArray();
  				$search_keyword = $_POST['search_keyword'];
				$itemViewed = 15;
  			} elseif(isset($_GET['cari'])) {
  				$search_keyword = Dispatcher::Instance()->Decrypt($_GET['search_keyword']->Raw());
				$itemViewed = Dispatcher::Instance()->Decrypt($_GET['item_viewed']->Raw());
  			} else {
  				$search_keyword = '';
				$itemViewed = 0;  
  			}
  		}
		
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$data['judul'] = $msg[0][0];
		$data['url_search'] = Dispatcher::Instance()->GetUrl('verifikasi_data', 'PencarianData', 'view', 'html');//$msg[0][1];
		
        //create paging 
		// $totalData = $Obj->GetCountDataPencarian($search_keyword);
		$totalData = $this->ObjPegawai->GetCountPegawaiByUserId($search_keyword, 'all');
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
  		// $data['list'] = $Obj->GetDataPencarian($startRec, $itemViewed, $search_keyword);
  		$data['list'] = $this->ObjPegawai->GetDataPegawaiByUserId($startRec, $itemViewed, $search_keyword, 'all');
  	
  		$url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&search_keyword=' . Dispatcher::Instance()->Encrypt($search_keyword).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
		
		$data['extend-url']= ''.
		'&search_keyword=' . Dispatcher::Instance()->Encrypt($search_keyword).
		'&item_viewed=' . Dispatcher::Instance()->Encrypt($itemViewed).
        '&cari=' . Dispatcher::Instance()->Encrypt(1);
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
		//create paging end here

		//set the language
		$lang=GTFWConfiguration::GetValue('application', 'button_lang');
		$labeldel=Dispatcher::Instance()->Encrypt($data['judul']);
		$data['arrStatusData']=$arrStatusData;
		$data['lang']=$lang;
		$data['search_keyword']=$search_keyword;
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'TITLE', $data['judul']);


		$this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['url_search'] );
		$this->mrTemplate->AddVar('content', 'SEARCH_KEYWORD', $data['search_keyword'] );
      
		if($data['pesan']) {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
      
		if (empty($data['list'])) {
			$this->mrTemplate->AddVar('data_list_data', 'DATA_EMPTY', 'YES');
			$this->mrTemplate->AddVar('data_list_data_no', 'SEARCH_KEYWORD', $data['search_keyword'] );
		} else {      
			$this->mrTemplate->AddVar('data_list_data', 'DATA_EMPTY', 'NO');
			$len = sizeof($data['list']);
			foreach ($data['list'] as $key => $value) {
				$no = $key + $data['start'];
          
				if($key == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
				if($key == ($len-1)) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);				
				$value['NUMBER'] = $no;
          
				$value['no'] = $no;
				if ($no % 2 != 0) {
					$value['class_name'] = 'table-common-even';
				} else {
					$value['class_name'] = '';
				}       
				$idEnc = Dispatcher::Instance()->Encrypt($value['id']);
				if (!file_exists(GTFWConfiguration::GetValue( 'application', 'file_save_path').$value['dokumen']) | empty($value['dokumen'])) { 
					$value['dokumen'] = '--Tidak ada dokumen yang diupload--';
				}else{
					$type = strtolower($value['dokumen']);
					while (strstr($type,'.')!=false){
						$type = strstr($type,'.');
						$type = substr ($type, 1);
					}
				
					$arrType = array(
								'zip' => 'zip.jpg',
								'tar' => 'zip.jpg',
								'rar' => 'zip.jpg',
								'pdf' => 'pdf.jpg',
								'txt' => 'text.jpg',
								'js'  => 'text.jpg',
								'css' => 'text.jpg',
								'php' => 'text.jpg',
								'asp' => 'text.jpg',
								'rtf' => 'word.jpg',
								'doc' => 'word.jpg',
								'docx' => 'word.jpg',
								'xls' => 'excel.jpg',
								'xlsx' => 'excel.jpg',
								'ppt' => 'ppt.jpg',
								'exe' => 'exe.jpg',
								'gif' => 'gambar.jpg',
								'png' => 'gambar.jpg',
								'jpeg'=> 'gambar.jpg',
								'jpg' => 'gambar.jpg',
								'bmp' => 'gambar.jpg',
								'mpeg'=> 'multimedia.jpg',
								'mp3'=> 'multimedia.jpg',
								'mp4'=> 'multimedia.jpg'
							);
					if ($arrType[$type]=='') $arrType[$type]='undefined.jpg';
					$value['ukuran'] = number_format(filesize(GTFWConfiguration::GetValue( 'application', 'file_save_path').$value['dokumen'])/1000,1,',','.').'  KB';
					$value['icon'] = '<td align="center"><a href='.GTFWConfiguration::GetValue( 'application', 'file_download_path').$value['dokumen'].' style="text-decoration:none" target="_Blank"><img border="0" src="'.GTFWConfiguration::GetValue( 'application', 'photo_download_path').$arrType[$type].'" width="45"></a><br/><font color="grey" size="0.5em">'.$value['ukuran'].'</font><td>';
					$value['dokumen'] = '<a href='.GTFWConfiguration::GetValue( 'application', 'file_download_path').$value['dokumen'].' style="text-decoration:none" target="_Blank">Download Berkas Klik Disini/Klik Icon</a>';
				}
				$value['urldata'] = GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'bo_download_path').'index.php?'.$value['urldata'];
				
				$value['url_pegdetail'] = Dispatcher::Instance()->GetUrl('data_pegawai', 'detailDataPegawai', 'view', 'html').'&dataId='.$value['id'];
				$value['url_status'] = Dispatcher::Instance()->GetUrl('mutasi_status', 'MutasiStatus', 'view', 'html').'&id='.$value['id'];
				$value['url_satker'] = Dispatcher::Instance()->GetUrl('mutasi_satuan_kerja', 'MutasiSatuanKerja', 'view', 'html').'&id='.$value['id'];
				$value['url_panggol'] = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan', 'MutasiPangkatGolongan', 'view', 'html').'&id='.$value['id'];
				$value['url_jabfung'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional', 'JabatanFungsional', 'view', 'html').'&id='.$value['id'];
				$value['url_jabstruk'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_struktural', 'MutasiJabatanStruktural', 'view', 'html').'&id='.$value['id'];
				$value['url_gaji'] = Dispatcher::Instance()->GetUrl('mutasi_kenaikan_gaji_berkala', 'MutasiKgb', 'view', 'html').'&id='.$value['id'];
				$value['url_didik'] = Dispatcher::Instance()->GetUrl('mutasi_pendidikan', 'MutasiPendidikan', 'view', 'html').'&id='.$value['id'];
				$value['url_latih'] = Dispatcher::Instance()->GetUrl('mutasi_pelatihan', 'MutasiPelatihan', 'view', 'html').'&id='.$value['id'];
				
				$value['URL_UPDATE_STATUS'] = $data['url_select'].'&id='.Dispatcher::Instance()->Encrypt($value['id_value']).'&referensi='.Dispatcher::Instance()->Encrypt($value['jenis_data']).$data['extend-url'];
				$this->mrTemplate->AddVars('data_list_data_item', $value, 'DATA_');
				$this->mrTemplate->parseTemplate('data_list_data_item', 'a');
			}
		}
    }
}
?>
