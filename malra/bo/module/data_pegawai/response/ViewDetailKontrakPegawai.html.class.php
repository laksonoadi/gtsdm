<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/data_pegawai/business/data_kontrak_pegawai.class.php';

   class ViewDetailKontrakPegawai extends HtmlResponse
   {
      function TemplateModule()
      {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/data_pegawai/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_kontrak_pegawai.html');
      }

      function ProcessRequest()
      {
         //set_time_limit(0);
         $pg = new DataKontrakPegawai;
         $msg = Messenger::Instance()->Receive(__FILE__);
         $this->Data = $msg[0][0];
         $this->Pesan = $msg[0][1];
         $this->css = $msg[0][2];
         // ---------

         $id = Dispatcher::Instance()->Decrypt($_GET['dataId']->Raw());
         $profilId = Dispatcher::Instance()->Decrypt($_GET['pegId']->Raw());
         
         $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('data_pegawai','historyKontrakPegawai','view','html').'&pegId='.$profilId.'&dataId='.$id;
         $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
    
         if(isset($_GET['dataId'])){
            $hasil_pegawai = $pg->GetDataDetail($profilId);
            $return['profil']=$hasil_pegawai[0];
            $tahun['start']=$hasil_pegawai[0]['masuk'];
            if(isset($_GET['dataId'])){
               $hasil_jabatan = $pg->GetDataKontrakPegawaiById($hasil_pegawai[0]['id'],$id);
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
      		$return['title']="CONTRACT EMPLOYEE DATA DETAIL";
		$return['upload']="Decree document has not been uploaded";
      	}else{
      		$return['title']="DETAIL DATA KONTRAK PEGAWAI";
		$return['upload']="Belum mengupload dokumen SK";
      	}
      	$return['lang']=$lang;

         
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
         
         $link = $data['link'];
         $this->mrTemplate->AddVar('content', 'TITLE', $data['title']);
         $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
         
         
         if(!empty($data['profil'])){
            if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profil']['foto']) | empty($data['profil']['foto'])) { 
      		  $data['profil']['foto'] = 'unknown.gif';
      	  	}
            $this->mrTemplate->AddVars('content', $data['profil'], 'PROFIL_');
         }
         
         // Filter Form
         if(empty($data['dataSheet'])){
            $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
            return NULL;
         }else{
            $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
         
         $i = $data['start'];
         $value=$data['dataSheet'];
         $value['tgl_awal'] = $this->date2string($value['tgl_awal']);
         $value['tgl_akhir'] = $this->date2string($value['tgl_akhir']); 
         $value['tgl_sk'] = $this->date2string($value['tgl_sk']);    
            if(!empty($value['upload'])){
               $value['upload']=$value['upload'];
            }else{
               $value['upload']=$data['upload'];
            }

        	if(($value['status']=='Aktif')&&($data['lang']=='eng')) {
        		$value['status']="Active";
        	} elseif(($value['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
        		$value['status']="Inactive";
        	} else {
        		$value['status']=$value['status'];
        	}
         
         if (!empty($value['upload'])){
            $value['LINK_DOWNLOAD_SK'] = $data['link']['link_download'].$value['upload'];
         } else {
            $value['LINK_DOWNLOAD_SK'] = '';
         }
        
         $this->mrTemplate->AddVars('data_item', $value, 'DETAIL_');
         $this->mrTemplate->parseTemplate('data_item', 'a');
         $i++;
         
         }
      }

      function dumper($print){
         echo"<pre>";print_r($print);echo"</pre>";
      }

      function date2string($date) {
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
