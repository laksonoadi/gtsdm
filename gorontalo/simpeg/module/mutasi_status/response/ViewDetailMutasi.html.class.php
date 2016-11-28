<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_status/business/mutasi_status.class.php';

class ViewDetailMutasi extends HtmlResponse
{
  
  function TemplateModule()
  {
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
    'module/mutasi_status/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_detail_mutasi.html');
  }

  function ProcessRequest()
  {
    //set_time_limit(0);
    $pg = new MutasiStatus;
    $msg = Messenger::Instance()->Receive(__FILE__);
    $this->Data = $msg[0][0];
    $this->Pesan = $msg[0][1];
    $this->css = $msg[0][2];
    // ---------
    $id = $_GET['dataId']->Integer()->Raw();
    $profilId = $_GET['id']->Integer()->Raw();
         
    $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_status','MutasiStatus','view','html').'&id='.$profilId;
    $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
         
    if(isset($_GET['id'])){
      $hasil_pegawai = $pg->GetDataDetail($profilId);
      $return['profil']=$hasil_pegawai[0];
      $tahun['start']=$hasil_pegawai[0]['masuk'];
      if(isset($_GET['dataId'])){
        $hasil_jabatan = $pg->GetDataMutasiById($hasil_pegawai[0]['id'],$id);
        $result=$hasil_jabatan[0];
        if(!empty($result)){
          $return['dataSheet']=$result;
        }
      }else{
        $return['dataSheet']=array();
      }
    }

	  //set the language
    $lang=GTFWConfiguration::GetValue('application', 'button_lang');
    if ($lang=='eng'){
    	$labeldel=Dispatcher::Instance()->Encrypt('Working Unit Mutation Detail');
		  $return['upload']="Decree document has not been uploaded";
    }else{
    	$labeldel=Dispatcher::Instance()->Encrypt('Detail Mutasi Status');
		  $return['upload']="Belum mengupload dokumen SK";
    }
    $return['lang']=$lang;

         
    return $return;
  }

  function ParseTemplate($data = NULL)
  {
    if ($data['lang']=='eng'){
	   	$this->mrTemplate->AddVar('content', 'TITLE', 'WORKING UNIT MUTATION DETAIL');
  	}else{
    	$this->mrTemplate->AddVar('content', 'TITLE', 'DETAIL MUTASI SATUAN KERJA');  
    } 

    if($this->Pesan){
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }
         
    $link = $data['link'];
    $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
    
    if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profil']['foto']) | empty($data['profil']['foto'])) { 
		  $data['profil']['foto']='unknown.gif';
	  }
        
    if(!empty($data['profil'])){
      $this->mrTemplate->AddVars('content', $data['profil'], 'PROFIL_');
    }
         
    if(empty($data['dataSheet'])){
      $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
      return NULL;
    }else{
      $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
       
      $i = $data['start'];
      $value=$data['dataSheet'];
      if ($data['lang']=='eng'){
        $value['tmt'] = $this->date2stringEng($value['tmt']);
        $value['tgl_sk'] = $this->date2stringEng($value['tgl_sk']); 
      }else{
        $value['tmt'] = $this->date2string($value['tmt']);
        $value['tgl_sk'] = $this->date2string($value['tgl_sk']); 
      }
    }
     
         
    if(!empty($value['upload'])){
      $value['upload']=$value['upload'];
    }else{
      $value['upload']=$data['upload'];
    }
    $value['link_download']=$data['link']['link_download'].$value['upload'];
	  if(($value['status']=='Aktif')&&($data['lang']=='eng')) {
		  $value['status']="Active";
	  } elseif(($value['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
		  $value['status']="Inactive";
	  } else {
		  $value['status']=$value['status'];
	  }
         
    $this->mrTemplate->AddVars('data_item', $value, 'DETAIL_');
    $this->mrTemplate->parseTemplate('data_item', 'a');
    $i++;
         
    }
  

  function dumper($print){
    echo"<pre>";print_r($print);echo"</pre>";
  }

       function date2string($date) {
         $bln = array(
         1  => 'Januari',
         2  => 'Februari',
         3  => 'Maret',
         4  => 'April',
         5  => 'Mei',
         6  => 'Juni',
         7  => 'Juli',
         8  => 'Agustus',
         9  => 'September',
         10 => 'Oktober',
         11 => 'November',
         12 => 'Desember'					
         );
         $arrtgl = explode('-',$date);
      return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
      }
      
      function date2stringEng($date) {
         $bln = array(
         1  => 'January',
         2  => 'February',
         3  => 'March',
         4  => 'April',
         5  => 'May',
         6  => 'June',
         7  => 'July',
         8  => 'August',
         9  => 'September',
         10 => 'October',
         11 => 'November',
         12 => 'December'					
         );
         $arrtgl = explode('-',$date);
      return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
      }
   }
?>
