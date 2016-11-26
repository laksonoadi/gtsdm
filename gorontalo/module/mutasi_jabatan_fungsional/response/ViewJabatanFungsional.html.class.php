<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_fungsional/business/jabatan_fungsional.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';
   
class ViewJabatanFungsional extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_jabatan_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_jabatan_fungsional.html');
   }
   
   function ProcessRequest()
   {
      //set_time_limit(0);
      $jabatan = new JabatanFungsional;
	  
		$msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      // ---------
    $ObjDatPeg = new DataPegawai();
    $_GET['id'] = $ObjDatPeg->GetPegIdByUserName();
	  $id = $_GET['id']->Integer()->Raw();
	  $jabatanId = $_GET['editId']->Integer()->Raw();
	  $this->profilId=$id;
	  
	  if(isset($_SESSION['mutasi'])){
	     $return['cari']=$_SESSION['mutasi']['cari'];
		 $return['list']=$_SESSION['mutasi']['list'];
	  }
	  
	  $tahun=array();
	  if(isset($_GET['id'])){
	     $hasil_pegawai = $jabatan->GetDataDetail($id);
		 $return['profil']=$hasil_pegawai[0];
		 $tahun['start']=$hasil_pegawai[0]['masuk'];
		 if(isset($_GET['editId'])){
		     $hasil_jabatan = $jabatan->GetJabatanDetail($hasil_pegawai[0]['id'],$jabatanId);
			 $result=$hasil_jabatan[0];
			 if(!empty($result)){
			   $return['input']['kode'] = $result['kode'];
			   $return['input']['ref_jab'] = $result['ref_jab'];
			   $return['input']['gol'] = $result['gol'];
			   $return['input']['skskode'] = $result['skskode'];
			   $return['input']['sksnama'] = $result['sksnama'];
			   $return['input']['sksmaks'] = $result['sksmaks'];
			   $return['input']['ak'] = $result['ak'];
			   $return['input']['mulai'] = $result['mulai'];
			   $return['input']['selesai'] = $result['selesai'];
			   $return['input']['sk'] = $result['sk'];
			   $return['input']['sk_no'] = $result['sk_no'];
			   $return['input']['sk_tgl'] = $result['sk_tgl'];
			   $return['input']['status'] = $result['status'];
			   $return['input']['upload'] = $result['upload'];
			   $return['input']['ref_nama'] = $result['ref_nama'];
			   $return['input']['pkt_nama'] = $result['pkt_nama'];
			 }
		 }else{
			   $return['input']['kode'] = '';
			   $return['input']['ref_jab'] = '';
			   $return['input']['gol'] = '';
			   $return['input']['skskode'] = '';
			   $return['input']['sksnama'] = '';
			   $return['input']['ak'] = 0;
			   $return['input']['mulai'] = date("Y-m-d");
			   $return['input']['selesai'] = date("Y-m-d");
			   $return['input']['sk'] = '';
			   $return['input']['sk_no'] = '';
			   $return['input']['sk_tgl'] = date("Y-m-d");
			   $return['input']['status'] = '';
			   $return['input']['upload'] = '';
			   $return['input']['ref_nama'] = '';
			   $return['input']['pkt_nama'] = '';
			   $return['input']['sksmaks'] = 0;
			 }
	  }
	  if(empty($tahun['start'])){
	     $tahun['start']=date("Y")-25;
	  }
      $tahun['end'] = date("Y")+5;
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'mulai', array($return['input']['mulai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'selesai', array($return['input']['selesai'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('tanggal', 'Tanggal', 'view', 'html', 'sk_tgl', array($return['input']['sk_tgl'], $tahun['start'], $tahun['end'], '', '', 'mulai'), Messenger::CurrentRequest);
	  //inisialisasi paging
	  $itemViewed = 20;
      $currPage = 1;
      $startRec = 0 ;
	  
	  if(isset($_GET['page']))
      {
         $currPage = $_GET['page']->Integer()->Raw();
         if ($currPage > 0)
            $startRec =($currPage-1) * $itemViewed;
         else $currPage = 1;
      }
	  
	  $return['start'] = $startRec+1;
	  if(isset($_GET['id'])){
		$totalData = $jabatan->GetCountJabatan($hasil_pegawai[0]['kode']);
	  }ELSE{
	    $totalData = 0;
	  }
	  $url = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&cari='.$return['cari'];
	  if (isset($_GET['id'])){ 
	     $url .= '&id='.$id;
	  }
	  Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	  $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','inputJabatanFungsional','do','html');
	  
	  if (isset($_GET['id'])){ 
	  
	     $return['link']['url_action'] .= '&profil='.$id;
		 if (isset($_GET['editId'])){
		    $return['link']['url_action'] .= '&editId='.$jabatanId;
		 }
	  }
	  $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html');
	  $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$id;
	  $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','pegawai','view','html');
	  $return['link']['url_detail'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','detailJabatan','view','html');
	  $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'file_download_path');
	  if ($return['cari'] != ''){
	     $return['link']['url_edit'] .= '&cari='.$return['cari'];
	  }
	  if (isset($_GET['page'])){
     	  $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
	  }
	  $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('mutasi_jabatan_fungsional|deleteJabatanFungsional|do|html').'&profil='.$id.
         "&urlReturn=".Dispatcher::Instance()->Encrypt('mutasi_jabatan_fungsional|pegawai|view|html').
         "&label=".Dispatcher::Instance()->Encrypt('Jabatan Fungsional');
	  $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional', 'deleteJabatanFungsional', 'do', 'html');
	  $listJenisJabatan=$jabatan->GetRefJabatan();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_jabatan', array('ref_jab', $listJenisJabatan, $return['input']['ref_jab'], 'true', 'id="ref_jab "'), Messenger::CurrentRequest);
	  $listPangkatGolongan=$jabatan->GetRefGolongan();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'golongan_ref', array('gol', $listPangkatGolongan, $return['input']['gol'], 'false', 'id="gol"  '), Messenger::CurrentRequest);
	  
	  $list_status=array(array('id'=>'Aktif','name'=>'Aktif'),array('id'=>'Tidak Aktif','name'=>'Tidak Aktif'));
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', array('status', $list_status, $return['input']['status'], 'false', 'id="status "  '), Messenger::CurrentRequest);
	  if(isset($_GET['id'])){
		$return['dataSheet'] = $jabatan->GetJabatan($startRec,$itemViewed,$hasil_pegawai[0]['id']);//$this->dumper($return['dataSheet']);
	  }else{
	     $return['dataSheet'] ==array();
	  }
    
    return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
	  $_SESSION['mutasi']['cari']=$data['cari'];
	  $_SESSION['mutasi']['list']=$data['list'];
	  $link = $data['link'];
	  $this->mrTemplate->AddVar('content', 'TITLE', 'JABATAN FUNGSIONAL');
	  $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
	  $this->mrTemplate->AddVar('content', 'URL_POPUP_KOMP', Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional', 'popupGaji', 'view', 'html')); 
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);
	  $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['editId']) ? 'Ubah' : 'Tambah');
	  if(!empty($data['profil'])){
	   if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profil']['foto']) | empty($data['profil']['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'PROFIL_FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'PROFIL_FOTO2', $data['profil']['foto']);
      }
		$this->mrTemplate->AddVars('content', $data['profil'], 'PROFIL_');
	  }
	  if(!empty($data['input'])){
	    $data['input']['link_download']=$data['link']['link_download'].$data['input']['upload'];
		$this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  }
	  // Filter Form
      
      // ---------
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  
	  $i = $data['start'];
      
		  foreach ($data['dataSheet'] as $value)
	      {
		     //$this->dumper($value);
			 $value['number'] = $i;
			 $value['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
			 $value['url_edit'] = $link['url_edit'].'&editId='.$value['id'];
			 $value['url_detail'] = $link['url_detail'].'&detailid='.$value['id'].'&profilId='.$this->profilId;
			 $value['link_download'] = $data['link']['link_download'].$value['upload'];
			 $value['url_delete'] = $link['url_delete'].
	            "&id=".Dispatcher::Instance()->Encrypt($value['id']).
	            "&dataName=".Dispatcher::Instance()->Encrypt($value['sk_no']);
	         $value['url_delete_js'] = $link['url_delete_js'];
			 if(!empty($value['gol'])){
			    $value['pkt_gol']=$value['gol'].' '.$value['pkt_nama'];
			 }
			 if($value['mulai']!='0000-00-00'){
			    $pisah_mulai=explode('-',$value['mulai']);
				$bulan=$this->GetNamaBulan($pisah_mulai[1]);
				$value['arr_mulai']=$pisah_mulai[2].' '.$bulan.' '.$pisah_mulai[0];
			 }
			 if($value['selesai']!='0000-00-00'){
			    $pisah_selesai=explode('-',$value['selesai']);
				$bulan=$this->GetNamaBulan($pisah_selesai[1]);
				$value['arr_selesai']=$pisah_selesai[2].' '.$bulan.' '.$pisah_selesai[0];
			 }
			 if($value['sk_tgl']!='0000-00-00'){
			    $pisah_sk_tgl=explode('-',$value['sk_tgl']);
				$bulan=$this->GetNamaBulan($pisah_sk_tgl[1]);
				$value['arr_sk_tgl']=$pisah_sk_tgl[2].' '.$bulan.' '.$pisah_sk_tgl[0];
			 }
			 $this->mrTemplate->AddVars('data_item', $value, '');
	         $this->mrTemplate->parseTemplate('data_item', 'a');
	         $i++;
		  }
	  }
   }
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
	
	function GetNamaBulan($angka){
      switch($angka){
	     case "01":
		    $bulan="Januari";
		 break;
		 case "02":
		    $bulan="Februari";
		 break;
		 case "03":
		    $bulan="Maret";
		 break;
		 case "04":
		    $bulan="April";
		 break;
		 case "05":
		    $bulan="Mei";
		 break;
		 case "06":
		    $bulan="Juni";
		 break;
		 case "07":
		    $bulan="Juli";
		 break;
		 case "08":
		    $bulan="Agustus";
		 break;
		 case "09":
		    $bulan="September";
		 break;
		 case "10":
		    $bulan="Oktober";
		 break;
		 case "11":
		    $bulan="November";
		 break;
		 case "12":
		    $bulan="Desember";
		 break;
	  }
	  return $bulan;
   }
   
}
   

?>