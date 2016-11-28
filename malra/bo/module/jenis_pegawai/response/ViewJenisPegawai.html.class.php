<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/jenis_pegawai/business/jenis_pegawai.class.php';
   
class ViewJenisPegawai extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/jenis_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_jenis_pegawai.html');
   }
   
   function ProcessRequest()
   {
      $jenispegawai = new JenisPegawai;
	  //print_r(Security::Authentication()->GetCurrentUser()->GetUserName());
	  // inisialisasi messaging
		$msg = Messenger::Instance()->Receive(__FILE__);//print_r($msg);
      $this->Data = $msg[0][0];
		$this->Pesan = $msg[0][1];
		$this->css = $msg[0][2];
      // ---------
	  $filter = array();
      
      if (isset($_POST['btncari'])){
		$filter = $_POST->AsArray();
	  }elseif (isset($_GET['page']) && is_array($this->Data)){ 
	    $filter = $this->Data;
	   }
	  Messenger::Instance()->Send(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType, array($filter), Messenger::NextRequest);
      $return['filter'] = $filter;//print_r($return['filter']);
	  
	  $id = $_GET['id']->Integer()->Raw();
	  //if(isset($this->Data['nama'])) $return['input']['nama'] = $this->Data['nama'];
	  if(isset($_GET['id'])){
	     $result = $jenispegawai->GetDataById($id);
		 if($result){
		   $return['input']['nama'] = $result['jnspegrNama'];
		   $return['input']['urut'] = $result['jnspegrUrut'];
		 }else{
		    unset($_GET['id']);
		 }
	  }else{
        $return['input']['nama']='';
        $return['input']['urut'] = '';
	  }
	  //print_r($return['input']);
	  // inisialisasi data filter
      if (isset($_POST['cari'])){
         $return['cari'] = $_POST['cari']->Raw();
	  }
      elseif (isset($_GET['cari'])){
         $return['cari'] = $_GET['cari']->Raw();
	  } else $return['cari'] = '';
	  
	  //inisialisasi paging
	  $itemViewed = 15;
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
	  $totalData = $jenispegawai->GetCount($return['cari']);
	  $url = Dispatcher::Instance()->GetUrl('jenis_pegawai','JenisPegawai','view','html').'&cari='.$return['cari'];
	  if (isset($_GET['id'])){ 
	     $url .= '&id='.$id;
	  }
	  Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
	  $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('jenis_pegawai','inputJenisPegawai','do','html');
	  if (isset($_GET['id'])){ 
	     $return['link']['url_action'] .= '&id='.$id;
	  }
	  $return['link']['url_search'] = Dispatcher::Instance()->GetUrl('jenis_pegawai','JenisPegawai','view','html');
	  $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('jenis_pegawai','JenisPegawai','view','html');
	  if ($return['cari'] != ''){
	     $return['link']['url_edit'] .= '&cari='.$return['cari'];
	  }
	  if (isset($_GET['page'])){
     	  $return['link']['url_edit'] .= '&page='.$_GET['page']->Integer()->Raw();
	  }
	  
	  $lang=GTFWConfiguration::GetValue('application', 'button_lang');
	  if ($lang=='eng'){
        $label="Employees Type Reference";
     }else{
        $label="Referensi Jenis Pegawai";  
     }
     $return['lang']=$lang;
	  
	  $return['link']['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('jenis_pegawai|deleteJenisPegawai|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('jenis_pegawai|JenisPegawai|view|html').
         "&label=".Dispatcher::Instance()->Encrypt($label);
	  $return['link']['url_delete_js'] = Dispatcher::Instance()->GetUrl('jenis_pegawai', 'JenisPegawai', 'do', 'html');
	  $dataSheet = $jenispegawai->GetData($startRec,$itemViewed,$return['cari']);//print_r($return);
	  $return['dataSheet'] = $dataSheet;
	  
    $tipe = array(0=>array('id'=>"PROBATION",'name'=>"Probation"),1=>array('id'=>"CONTRACT",'name'=>"Contract"),
                  2=>array('id'=>"PERMANENT",'name'=>"Permanent"));
    Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'tipe', 
    array('tipe',$tipe,$result['jnspegrTipeKontrak'],'',' style="width:100px;"'), Messenger::CurrentRequest);
	  
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
         $this->mrTemplate->AddVar('content', 'TITLE', 'EMPLOYMENT STATUS REFERENCE');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Employment Status Data');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Update' : 'Add');
     }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI STATUS PEGAWAI');
         $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Data Status Pegawai');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['id']) ? 'Ubah' : 'Tambah');  
     }
      
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
	  $this->mrTemplate->AddVar('content', 'NAMA', $data['input']['nama']);
	  $this->mrTemplate->AddVar('content', 'URUT', $data['input']['urut']);
	  
	  // Filter Form
      $this->mrTemplate->AddVar('content', 'CARI', $data['cari']);
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', $data['link']['url_search']);
      // ---------
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  }
	  $i = $data['start'];
      $link = $data['link'];
	  foreach ($data['dataSheet'] as $value)
      {
	     $data = $value;//print_r($data);
		 $data['number'] = $i;
		 $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
		 $data['url_edit'] = $link['url_edit'].'&id='.$data['jnspegrId'];
		 $data['url_delete'] = $link['url_delete'].
            "&id=".Dispatcher::Instance()->Encrypt($data['jnspegrId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['jnspegrNama']);
         $data['url_delete_js'] = $link['url_delete_js'];
		 $this->mrTemplate->AddVars('data_item', $data, '');
         $this->mrTemplate->parseTemplate('data_item', 'a');
         $i++;
	  }
   }
}
   

?>