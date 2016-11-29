<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/mutasi_jabatan_fungsional/business/jabatan_fungsional.class.php';
   
class ViewDetailJabatan extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/mutasi_jabatan_fungsional/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_jabatan.html');
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
	  $id = $_GET['detailid']->Integer()->Raw();
	  $profilId = $_GET['profilId']->Integer()->Raw();
	  
	  $return['link']['url_back'] = Dispatcher::Instance()->GetUrl('mutasi_jabatan_fungsional','JabatanFungsional','view','html').'&id='.$profilId;
	  $return['link']['link_download']=GTFWConfiguration::GetValue( 'application', 'baseaddress').GTFWConfiguration::GetValue( 'application', 'basedir').'upload_file/file/';
        
	  if(isset($_GET['profilId'])){
	     $hasil_pegawai = $jabatan->GetDataDetail($profilId);
		 $return['profil']=$hasil_pegawai[0];
		 $tahun['start']=$hasil_pegawai[0]['masuk'];
		 if(isset($_GET['detailid'])){
		     $hasil_jabatan = $jabatan->GetJabatanDetail($hasil_pegawai[0]['id'],$id);
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
	  $link = $data['link'];
	  if($data['lang']=='eng') {
	  	$this->mrTemplate->AddVar('content', 'TITLE', 'FUNCTIONAL POSITION MUTATION DETAIL');
		$upload="Decree document has not been uploaded";
	  } else {
		$this->mrTemplate->AddVar('content', 'TITLE', 'DETAIL MUTASI JABATAN FUNGSIONAL');
		$upload="Dokumen belum diunggah.";
	  }
	  
	  $this->mrTemplate->AddVar('content', 'URL_BACK', $link['url_back']);
	  
	  
	  if(!empty($data['profil'])){
	  if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profil']['foto']) | empty($data['profil']['foto'])) { 
      		  $data['profil']['foto'] = 'unknown.gif';
      	  	}
		$this->mrTemplate->AddVars('content', $data['profil'], 'PROFIL_');
	  }
	  
	  // Filter Form
      
      // ---------
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  
	  $i = $data['start'];
     
		 $value=$data['dataSheet'];   
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
			 if(!empty($value['gapok'])){
			    $value['nominal_gapok']='Rp '.number_format($value['gapok'],2,",",".");
			 }else{
			    $value['nominal_gapok']='Rp '.number_format(0,2,",",".");
			 }
			 //$this->dumper($value);

			if(!empty($value['upload'])){
               			$value['upload']=$value['upload'];
        		}else{
        	       		$value['upload']=$upload;
	        	}

			if(($value['status']=='Aktif')&&($data['lang']=='eng')) {
				$value['status']="Active";
			} elseif(($value['status']=='Tidak Aktif')&&($data['lang']=='eng')) {
				$value['status']="Inactive";
			} else {
				$value['status']=$value['status'];
			}
	
	     $value['url_link_download'] = $data['link']['link_download'] . $value['upload'];
			 $this->mrTemplate->AddVars('data_item', $value, '');
		         $this->mrTemplate->parseTemplate('data_item', 'a');
		         $i++;
		  
	  }
   }
   
   function dumper($print){
	   echo"<pre>";print_r($print);echo"</pre>";
	}
	
	function GetNamaBulan($angka){
      switch($angka){
	     case "01":
		    $bulan="January";
		 break;
		 case "02":
		    $bulan="February";
		 break;
		 case "03":
		    $bulan="March";
		 break;
		 case "04":
		    $bulan="April";
		 break;
		 case "05":
		    $bulan="May";
		 break;
		 case "06":
		    $bulan="June";
		 break;
		 case "07":
		    $bulan="July";
		 break;
		 case "08":
		    $bulan="August";
		 break;
		 case "09":
		    $bulan="September";
		 break;
		 case "10":
		    $bulan="October";
		 break;
		 case "11":
		    $bulan="November";
		 break;
		 case "12":
		    $bulan="December";
		 break;
	  }
	  return $bulan;
   }
   
}
   

?>
