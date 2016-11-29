<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/email/business/Email.class.php';
   
class ViewEmail extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/email/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_email.html');
   }
   
   function ProcessRequest()
   {
      $email = new email;
      $path_template=GTFWConfiguration::GetValue( 'application', 'template_email_path');
		  
		  if((isset($_POST['dataId']))&&(isset($_POST['btnsimpan']))){
		     $nama_file = $_POST['dataId'];
		     $file=$path_template.$nama_file;
		     $data=fopen($file,"w");
		     $_POST['template']=str_replace('[','{',$_POST['template']);
		     $_POST['template']=str_replace(']','}',$_POST['template']);
		     if (fwrite($data,$_POST['template'])){
		        $this->Pesan='Template Update Succesfully';
		        $this->css='notebox-done';
         }else{
            $this->Pesan='Template Update Failure';
            $this->css='notebox-warning';
         }
		     fclose($data);
		     
		     unset($_GET['dataId']);
		  }elseif((isset($_POST['dataId']))&&(isset($_POST['btnbatal']))){
		     unset($_GET['dataId']);
      }
	    
	    if(isset($_GET['dataId'])){
  	     $nama_file = str_replace("\'","",$_GET['dataId']);
	       $file=$path_template.$nama_file;
	       $data=fopen($file,"r");
         $body='';
         while (!feof($data)) {
      			$isi_data=fgets($data,10000);
      			$isi_data= str_replace('{','[',$isi_data);
      			$isi_data= str_replace('}',']',$isi_data);
            $body .= $isi_data;
      	 }
      	 fclose($data);
      	 
      	 $return['input']['namafile']=$nama_file;
      	 $return['input']['template']=$body;
	       $return['input']['display']='';
  	  }else{
  	     $return['input']['display']='none';
  	  }
	  	  
  	  $return['link']['url_action'] = Dispatcher::Instance()->GetUrl('email','email','view','html');
  	  if (isset($_GET['dataId'])){ 
  	     $return['link']['url_action'] .= '&dataId='.$nama_file;
  	  }
  	  $return['link']['url_edit'] = Dispatcher::Instance()->GetUrl('email','email','view','html');
  	  
  	  $dir = dir($path_template);
      while (false !== ($entry = $dir->read())) {
          if (strpos($entry, "email")==0){
                if (($entry!='..')&&($entry!='.')){
                  $listFile[]['filename']=$entry;
                }
                /*$entry = substr($entry, 0, strlen($entry)-16);
                $row['value'] = $this->SeperateUpcase($entry);
                $row['label'] = $entry."Action.class.php";
                $combo[] = $row;*/
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
     
     $this->mrTemplate->AddVar('content', 'TITLE', 'EMAIL TEMPLATE REFERENCE');
     $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Email Template Data');
     $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId']) ? 'Update' : 'Add');
      

	  $this->mrTemplate->AddVar('content', 'URL_ACTION', $data['link']['url_action']);//print_r($data['link']['url_action']);
	  
	  $this->mrTemplate->AddVar('content', 'NAMAFILE', $data['input']['namafile']);
	  $this->mrTemplate->AddVar('content', 'TEMPLATE', $data['input']['template']);
	  $this->mrTemplate->AddVar('content', 'DISPLAY', $data['input']['display']);
	  
	  
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
  		     $data['url_edit'] = $link['url_edit']."&dataId='".$data['filename']."'";
  		     $this->mrTemplate->AddVars('data_item', $data, '');
           $this->mrTemplate->parseTemplate('data_item', 'a');
           $i++;
  	  }
   }
}
   

?>