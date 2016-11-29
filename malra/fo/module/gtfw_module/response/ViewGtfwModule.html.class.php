<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/gtfw_module/business/GtfwModule.class.php';
   
class ViewGtfwModule extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gtfw_module/template/');
      $this->SetTemplateFile('view_gtfw_module.html');
   }
   
   function ProcessRequest()
   {
      $GtfwModule = new GtfwModule();
      $path_module=GTFWConfiguration::GetValue( 'application', 'docroot').'module/';
	  $AppId=GTFWConfiguration::GetValue( 'application', 'application_id');
  	  
  	  $dir = dir($path_module);
	  $i=0;
      while (false !== ($entry = $dir->read())) {
        if (($entry!='..')&&($entry!='.')){
            
			$path_submodule=$path_module.$entry.'/response/';
			$j=0; $jj=0;
			if (file_exists($path_submodule)){
				$dir2 = dir($path_submodule);
				while (false !== ($entry2 = $dir2->read())) {
					if (($entry2!='..')&&($entry2!='.')){
					    $Temp=$GtfwModule->PecahFile($entry2);
						if ($Temp['Action']!="Process") $j++;
						
						$GetModule=$GtfwModule->GetModuleByFile($entry,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
						If (sizeof($GetModule)>0) $jj++;
					}
				}
			}
			
			$listFile[$i]['module']=$entry;
			$listFile[$i]['jumlah_submodule']=$j;
			$listFile[$i]['terdaftar']=$jj;
			$listFile[$i]['belum_terdaftar']=$j-$jj;
			$i++;
        }
      }
  	  
  	  $return['dataSheet'] = $listFile;
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
     //tentukan value judul, button dll sesuai pilihan bahasa 
     
     $this->mrTemplate->AddVar('content', 'TITLE', 'REGISTER MODULE');
     $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Email Template Data');
     $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
      

	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
	  
	  
	  
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  }
	    $i = 1;
      $link = $data['link'];
  	  foreach ($data['dataSheet'] as $value)
      {
  	       $data = $value;//print_r($data);
  		     $data['number'] = $i;
  		     $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
  		     $data['url_detail'] = Dispatcher::Instance()->GetUrl('gtfw_module','gtfwSubModule','view','html')."&moduleName=".$data['module']."";
  		     $this->mrTemplate->AddVars('data_item', $data, '');
           $this->mrTemplate->parseTemplate('data_item', 'a');
           $i++;
  	  }
   }
}
   

?>