<?php  
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';

   class ViewDetailMutasi extends HtmlResponse
   {
      function TemplateModule()
      {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/mutasi_pendidikan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_mutasi.html');
      }

      function ProcessRequest()
      {
         //set_time_limit(0);
         $pg = new MutasiPendidikan;
         $msg = Messenger::Instance()->Receive(__FILE__);
         $this->Data = $msg[0][0];
         $this->Pesan = $msg[0][1];
         $this->css = $msg[0][2];
         // ---------
         $id = $_GET['dataId']->Integer()->Raw();
         $profilId = $_GET['id']->Integer()->Raw();
         
         $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_pendidikan','MutasiPendidikan','view','html').'&id='.$profilId;
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
      
      $lang=GTFWConfiguration::GetValue('application', 'button_lang');
      $data['lang']=$lang;
             
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
         if($data['lang'] = 'eng'){
            $this->mrTemplate->AddVar('content', 'TITLE', 'EDUCATION MUTATION');
         } else {
            $this->mrTemplate->AddVar('content', 'TITLE', 'RIWAYAT PENDIDIKAN');
         }
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
         $value['mulai'] = $this->date2string($value['mulai']);
         $value['selesai'] = $this->date2string($value['selesai']); 
         /*$value['tgl_naik'] = $this->date2string($value['tgl_naik']);    
            if(!empty($value['gapok'])){
               $value['nominal_gapok']='Rp '.number_format($value['gapok'],2,",",".");
            }else{
               $value['nominal_gapok']='Rp '.number_format(0,2,",",".");
            }
            
            if(!empty($value['upload'])){
               $value['upload']=$value['upload'];
            }else{
               $value['upload']="File SK tidak di upload";
            }*/
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
         11 => 'Nopember',
         12 => 'Desember'					
         );
         $arrtgl = explode('-',$date);
      return $arrtgl[2].'&nbsp;'.$bln[(int) $arrtgl[1]].'&nbsp;'.$arrtgl[0];
      }

   }
?>