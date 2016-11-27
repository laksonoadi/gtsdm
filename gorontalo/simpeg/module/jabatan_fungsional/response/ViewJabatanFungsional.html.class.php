<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jabatan_fungsional/business/jabatan_fungsional.class.php';
   
class ViewJabatanFungsional extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/jabatan_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_jabatan_fungsional.html');
   }
   
   function ProcessRequest()
   {
      $jabatan = new JabatanFungsional;
	  
		$msg = Messenger::Instance()->Receive(__FILE__);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      // ---------
	  $id = $_GET['id']->Integer()->Raw();
	  if (isset($_POST['cari'])){
         $return['cari'] = $_POST['cari']->Raw();
	  }
      elseif (isset($_GET['cari'])){
         $return['cari'] = $_GET['cari']->Raw();
	  } else $return['cari'] = '';
	  
	  if(isset($_GET['id'])){
	     $hasil = $jabatan->GetDataDetail($id);
		 //$this->dumper($hasil);
		 $result=$hasil[0];
		 if(!empty($result)){
		   $return['input']['nama'] = $result['nama'];
		   $return['input']['jenisid'] = $result['jenisid'];
		   $return['input']['tingkat'] = $result['tingkat'];
		   $return['input']['pensiun'] = $result['pensiun'];
		   $return['input']['gkompKode'] = $result['gkompId'];
		   $return['input']['gajiKode'] = $result['gajiId'];
		   $return['input']['skompKode'] = $result['skompId'];
		   $return['input']['sksKode'] = $result['sksId'];
		   $return['input']['max_sks'] = $result['max_sks'];
		   $return['input']['gajiNama'] = $result['gajiNama'];
		   $return['input']['sksNama'] = $result['sksNama'];
		 }
	  }else{
	     $return['input']['nama'] = '';
		   $return['input']['jenisid'] = '';
		   $return['input']['tingkat'] = '';
		   $return['input']['pensiun'] = '';
		   $return['input']['gkompKode'] = '';
		   $return['input']['gajiKode'] = '';
		   $return['input']['skompKode'] = '';
		   $return['input']['sksKode'] = '';
		   $return['input']['max_sks'] = '';
		   $return['input']['gajiNama'] = '';
		   $return['input']['sksNama'] = '';
	  }
	  
      
	  
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
	  $totalData = $jabatan->GetCount($return['cari']);
	  $url = Dispatcher::Instance()->GetUrl('jabatan_fungsional','JabatanFungsional','view','html').'&cari='.$return['cari'];
	  if (isset($_GET['id'])){ 
	     $url .= '&id='.$id;
	  }
	  Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	  $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('jabatan_fungsional','inputJabatanFungsional','do','html');
	  if (isset($_GET['id'])){ 
	     $return['link']['url_action'] .= '&id='.$id;
	  }
	  $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('jabatan_fungsional','JabatanFungsional','view','html');
	  $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('jabatan_fungsional','JabatanFungsional','view','html');
	  $url_popup = Dispatcher::Instance()->GetUrl('jabatan_fungsional', 'GajiSks', 'popup', 'html');
	  $return['link']['url_popup']['sks'] = $url_popup.'&cari=kompSks';
	  $return['link']['url_popup']['gaji'] = $url_popup.'&cari=kompGaji';
	  if (isset($_GET['id'])){ 
	     $return['link']['url_popup']['sks'] .= '&edit=edit'.'&kompSks='.$return['input']['skompKode'].'&sksId='.$return['input']['sksKode'];
		 $return['link']['url_popup']['gaji'] .= '&edit=edit'.'&kompGaji='.$return['input']['gkompKode'].'&gajiId='.$return['input']['gajiKode'];
	  }
	  if ($return['cari'] != ''){
	     $return['link']['url_edit'] .= '&cari='.$return['cari'];
	  }
	  if (isset($_GET['page'])){
     	  $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
	  }
	  
	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
	  if ($lang=='eng'){
	      $labeldel=Dispatcher::Instance()->Encrypt('Functional Position Reference');
     }else{
         $labeldel=Dispatcher::Instance()->Encrypt('Referensi Jabatan Fungsional');
     }
     $return['lang']=$lang;
	  $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('jabatan_fungsional|deleteJabatanFungsional|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('jabatan_fungsional|JabatanFungsional|view|html').
         "&label=".$labeldel;
	  $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('jabatan_fungsional', 'deleteJabatanFungsional', 'do', 'html');
	  $listJenisJabatan=$jabatan->GetJenisJabatan();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_jabatan', array('jenisid', $listJenisJabatan, $return['input']['jenisid'], 'true', 'id="jenisid "'), Messenger::CurrentRequest);
	  $listKomponenGaji=$jabatan->GetKomponenGaji();
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'komponen_gaji', array('gkompKode', $listKomponenGaji, $return['input']['gkompKode'], 'false', 'id="gkompKode"  OnChange="JsGaji(this.value);" '), Messenger::CurrentRequest);
	  Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'komponen_sks', array('skompKode', $listKomponenGaji, $return['input']['skompKode'], 'false', 'id="skompKode " OnChange="JsSKS(this.value);"'), Messenger::CurrentRequest);
	  
	  $return['dataSheet'] = $jabatan->GetData($startRec,$itemViewed,$return['cari']);//print_r($return);
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
     if ($data['lang']=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'FUNCTIONAL POSITION REFERENCE');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Functional Position Data');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI JABATAN FUNGSIONAL');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Jabatan Fungsional');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
     }
      
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
	  
	  $this->mrTemplate->AddVars('content', $data['input'], 'INPUT_');
	  //$this->mrTemplate->AddVars('content', $data['json']['gaji'], 'JSON_GAJI_');
	  //$this->mrTemplate->AddVars('content', $data['json']['sks'], 'JSON_SKS_');
	  // Filter Form
      $this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
	  $this->mrTemplate->AddVar('content', 'URL_POPUP_SKS', $data['link']['url_popup']['sks']);
	  $this->mrTemplate->AddVar('content', 'URL_POPUP_GAJI', $data['link']['url_popup']['gaji']);
      // ---------
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  
	  $i = $data['start'];
      $link = $data['link'];
	  
		  foreach ($data['dataSheet'] as $value)
	      {
		     $data = $value;//print_r($data);
			 $data['number'] = $i;
			 $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
			 $data['url_edit'] = $link['url_edit'].'&id='.$data['id'];
			 $data['url_delete'] = $link['url_delete'].
	            "&id=".Dispatcher::Instance()->Encrypt($data['id']).
	            "&dataName=".Dispatcher::Instance()->Encrypt($data['nama']);
	         $data['url_delete_js'] = $link['url_delete_js'];
			 if(!empty($data['gajiKode'])){
			    $data['gaji_nama']=$data['gajiNama'].'-'.$data['gkomNama'];
			 }
			 if(!empty($data['sksKode'])){
			    $data['sks_nama']=$data['sksNama'].'-'.$data['skomNama'];
			 }
			 $this->mrTemplate->AddVars('data_item', $data, '');
	         $this->mrTemplate->parseTemplate('data_item', 'a');
	         $i++;
		  }
	  }
   }
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
   
}
   

?>