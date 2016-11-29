<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewSatuanKerja extends HtmlResponse {
   var $unitkerjaId;
   var $Pesan;
   var $kerja;
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/satuan_kerja/template');
      $this->SetTemplateFile('view_satuan_kerja.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Pesan = $msg[0][1];
	  
	  $this->kerja = new SatuanKerja();
	  if (isset($_GET['satkerId'])){
         $satker_id = $_GET['satkerId']->Integer()->Raw();
         $return['satker_detail'] = $this->kerja->GetSatKerDetail($satker_id);//print_r($return['satker_detail']);
      }
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

      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('satuan_kerja', 'inputSatuanKerja', 'view', 'html').'&op=add');
      $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_kerja|deleteSatuanKerja|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_kerja|satuanKerja|view|html').
         "&label=".Dispatcher::Instance()->Encrypt('Referensi Satuan Kerja');
	  if (isset($this->Pesan)){         
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', 'notebox-done');
      }//print_r($data['satker_detail']);
	  if(!empty($data['satker_detail'])){
	    
	     $this->mrTemplate->SetAttribute('satker_detail', 'visibility', 'visible');
		 $this->mrTemplate->AddVar('satker_detail', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_kerja','inputSatuanKerja', 'view','html').'&satkerId='.$data['satker_detail']['satkerId']);
		 $this->mrTemplate->AddVar('satker_detail', 'UNIT', $data['satker_detail']['UnitName']);
		 $this->mrTemplate->AddVar('satker_detail', 'NAMA', $data['satker_detail']['satkerNama']);
		 $this->mrTemplate->AddVar('satker_detail', 'ID', $data['satker_detail']['satkerId']);
		 $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE', $url_delete.
		    "&id=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerNama']));
		 $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_kerja', 'deleteSatuanKerja', 'do', 'html'));	
	  }
	  
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
         //echo "$induk<br>\r\n";
			$indukId=$this->GetParentLevel($induk);
			if(empty($indukId)){
			   $indukId=0;
			}
			//echo($indukId);echo'<br>';
			$url_level=Dispatcher::Instance()->GetUrl('satuan_kerja','satuanKerja', 'view','html').'&unitId='.$list[$a]['satkerUnitId'].'&satkerId='.$list[$a]['satkerId'].'&smpn=';
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