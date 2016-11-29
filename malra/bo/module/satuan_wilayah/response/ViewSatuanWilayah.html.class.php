<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_wilayah/business/satuan_wilayah.class.php';

class ViewSatuanWilayah extends HtmlResponse {
   var $unitwilayahId;
   var $Pesan;
   var $wilayah;
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/satuan_wilayah/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_satuan_wilayah.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Pesan = $msg[0][1];
	  
	  $this->wilayah = new SatuanWilayah();
	  if (isset($_GET['satwilId'])){
         $satwil_id = $_GET['satwilId']->Integer()->Raw();
         $return['satwil_detail'] = $this->wilayah->GetSatWilDetail($satwil_id);//print_r($return['satker_detail']);
      }
	  $return['list'] = $this->wilayah->GetListSatWil();
	  return $return;
   }
   
   function GetParentLevel($level){
      $return=$this->wilayah->GetSatWilLevel($level);
	  return $return['satwilId'];
   }
   
   function ParseTemplate($data = NULL) {
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
           $this->mrTemplate->AddVar('content', 'TITLE', 'UNIT AREA REFERENCE');
           $label = "Unit Area Reference";
       }else{
           $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN WILAYAH');
           $label = "Referensi Satuan Wilayah";  
       }
      
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html') );

      $this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'ListSatuanWilayah', 'view', 'html') );

      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html').'&op=add');
      $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_wilayah|deleteSatuanWilayah|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_wilayah|satuanWilayah|view|html').
         "&label=".Dispatcher::Instance()->Encrypt($label);
	  if (isset($this->Pesan)){         
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', 'notebox-done');
      }//print_r($data['satker_detail']);
	  if(!empty($data['satwil_detail'])){
	    
	     $this->mrTemplate->SetAttribute('satwil_detail', 'visibility', 'visible');
		 $this->mrTemplate->AddVar('satwil_detail', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_wilayah', 'inputSatuanWilayah', 'view', 'html').'&satwilId='.$data['satwil_detail']['satwilId']);
		 $this->mrTemplate->AddVar('satwil_detail', 'KODE', $data['satwil_detail']['satwilKode']);
		 $this->mrTemplate->AddVar('satwil_detail', 'NAMA', $data['satwil_detail']['satwilNama']);
		 $this->mrTemplate->AddVar('satwil_detail', 'ID', $data['satwil_detail']['satwilId']);
		 $this->mrTemplate->AddVar('satwil_detail', 'URL_DELETE', $url_delete.
		    "&id=".Dispatcher::Instance()->Encrypt($data['satwil_detail']['satwilId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['satwil_detail']['satwilNama']));
		 $this->mrTemplate->AddVar('satwil_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_wilayah', 'deleteSatuanWilayah', 'do', 'html'));	
	  }
	  
	  if(empty($data['list'])){
	     $this->mrTemplate->AddVar('satwil', 'IS_EMPTY', 'YES');
	  }else{
	     $this->mrTemplate->AddVar('satwil', 'IS_EMPTY', 'NO');
		 $strX = "<script type='text/javascript'>
         d = new dTree('d');
         d.add(0,-1,'');";
         $this->mrTemplate->addVar("satwil_treex", "SATWIL_TREE_STRX", $strX);
		
		 $list=$data['list'];
		 
		 
		for($a=0;$a<count($list);$a++){
		    //$a=6
		    $level=explode('.',$list[$a]['satwilLevel']);
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
			$url_level=Dispatcher::Instance()->GetUrl('satuan_wilayah', 'SatuanWilayah', 'view', 'html').'&satwilId='.$list[$a]['satwilId'].'&smpn=';
			//$url_level=htmlentities($url_level);
			$str=$str."d.add(".$list[$a]['satwilId'].",".$indukId.",'".$list[$a]['satwilNama']."', '" .$url_level. "');";
		     //$coba=$list[$a]['satkerId'].'('.$list[$a]['satkerLevel'].')-'.$indukId.'('.$induk.')';
		 }//echo($coba);echo"<br>";
		 $strY = "document.getElementById('div_satwil').innerHTML =  d.toString(); </script>"; 
		 $str=$strX.$str.$strY;
		 $this->mrTemplate->addVar("satwil_tree", "SATWIL_TREE_STR", $str); 
	  }
   
   }
}


?>