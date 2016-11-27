<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewStrukturJabatan extends HtmlResponse {
   var $unitkerjaId;
   var $Pesan;
   var $kerja;
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/analisa_jabatan/template');
      $this->SetTemplateFile('view_struktur_jabatan.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Pesan = $msg[0][1];
	  
	  $this->kerja = new SatuanKerja();
	  // if (isset($_GET['satkerId'])){
   //       $satker_id = $_GET['satkerId']->Integer()->Raw();
   //       $return['satker_detail'] = $this->kerja->GetSatKerDetail($satker_id);//print_r($return['satker_detail']);
   //    }
	  $return['list'] = $this->kerja->GetListSatKer();
	  return $return;
   }
   
   function GetParentLevel($level){
      $return=$this->kerja->GetSatKerLevel($level);
	  return $return['satkerId'];
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('satuan_kerja', 'SatuanKerja', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN KERJA');
      $this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('satuan_kerja', 'ListSatuanKerja', 'view', 'html') );

    
	  
	  if(empty($data['list'])){
	     $this->mrTemplate->AddVar('satker', 'IS_EMPTY', 'YES');
	  }else{
	     $this->mrTemplate->AddVar('satker', 'IS_EMPTY', 'NO');
		 $strX = "<script type='text/javascript'>
         d = new dTree('d');
         d.add(0,-1,'Daftar Satuan Kerja');";
         $this->mrTemplate->addVar("satker_treex", "SATKER_TREE_STRX", $strX);
		
		 $list=$data['list'];
		 
		 
		for($a=0;$a<count($list);$a++){
		    //$a=6
		    $level=explode('.',$list[$a]['satkerLevel']);
			//$induk=0;

			if(count($level)==1){
			   $induk=0;
			   
			}else{
              array_pop($level);
               $induk = implode('.', $level);
			}

         
			//cek ini induk jadi berat
			$indukId=$this->GetParentLevel($induk);

			if(empty($indukId)){
			   $indukId=0;
			}
			//echo($indukId);echo'<br>';
			$url_level=Dispatcher::Instance()->GetUrl('analisa_jabatan','Anggota', 'view','html').'&id='.$list[$a]['satkerId'].'&smpn=';
			//$url_level=htmlentities($url_level);
			$str=$str."d.add(".$list[$a]['satkerId'].",".$indukId.",'".$list[$a]['satkerNama']."', '" .$url_level. "');";
		     //$coba=$list[$a]['satkerId'].'('.$list[$a]['satkerLevel'].')-'.$indukId.'('.$induk.')';
		 }//echo($coba);echo"<br>";
		 $strY = "document.getElementById('div_satker').innerHTML =  d.toString(); </script>"; 
		 $str=$strX.$str.$strY;
		 $this->mrTemplate->addVar("satker_tree", "SATKER_TREE_STR", $str); 
	  }
   
   }
}


?>