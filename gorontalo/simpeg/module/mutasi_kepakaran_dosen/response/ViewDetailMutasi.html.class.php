<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';

   class ViewDetailMutasi extends HtmlResponse
   {
      function TemplateModule()
      {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
      'module/mutasi_pangkat_golongan/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_mutasi.html');
      }

      function ProcessRequest()
      {
         //set_time_limit(0);
         $pg = new MutasiPangkatGolongan;
         $msg = Messenger::Instance()->Receive(__FILE__);
         $this->Data = $msg[0][0];
         $this->Pesan = $msg[0][1];
         $this->css = $msg[0][2];
         // ---------
         $id = $_GET['dataId']->Integer()->Raw();
         $profilId = $_GET['id']->Integer()->Raw();
         
         $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_pangkat_golongan','MutasiKepakaranDosen','view','html').'&id='.$profilId;
         
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
         
		 if($data['lang']=='eng') {
			$title = "LECTURER EXPERTISE MUTATION DETAIL";
			$upload= "Decree document has not been uploaded.";
		 } else {
			$title = "DETAIL MUTASI BIDANG KEPAKARAN DOSEN";
			$upload= "File SK tidak di upload.";
		 }
		 
         $link = $data['link'];
         $this->mrTemplate->AddVar('content', 'TITLE', $title);
         $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
         
         
         if(!empty($data['profil'])){
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
			$value['tmt'] = $this->date2string($value['tmt']);
			$value['tgl_sk'] = $this->date2string($value['tgl_sk']); 
			$value['tgl_naik'] = $this->date2string($value['tgl_naik']);    
            if(!empty($value['gapok'])){
               $value['nominal_gapok']='Rp '.number_format($value['gapok'],2,",",".");
            }else{
               $value['nominal_gapok']='Rp '.number_format(0,2,",",".");
            }
            
            if(!empty($value['upload'])){
               $value['upload']=$value['upload'];
            }else{
               $value['upload']=$upload;
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
