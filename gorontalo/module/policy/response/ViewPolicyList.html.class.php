<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/policy/business/Policy.class.php';

class ViewPolicyList extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/policy/template');
        $this->SetTemplateFile('view_policy_list.html');
    }

   function ProcessRequest() {
        $list = new Policy();
        $satkerId = $_GET['satkerId']->Integer()->Raw();
        $jnspolicyId= $_GET['jnspolicyId']->Integer()->Raw();
        $return['policy'] = $list->ListPolicy($satkerId,$jnspolicyId);
        $return['satker'] = $list->GetSatuanKerjaPolicyById($satkerId);
        $return['jenis'] = $list->GetJenisPolicyById($jnspolicyId);

        $nav[0]['url']=Dispatcher::Instance()->GetUrl('policy', 'policySatuanKerjaList', 'view', 'html');
        $nav[0]['menu']="Policies & Regulations";
        $title = "Policy & Regulation Details";
      
        Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','',''), Messenger::CurrentRequest);
      
        return $return;
    }

    function ParseTemplate($data) {
      $this->mrTemplate->AddVar("content","URL_BACK",Dispatcher::Instance()->GetUrl('policy','PolicySatuanKerjaList','View','html'));
      $this->mrTemplate->AddVar("content","SATKER",$data['satker']['nama']);
      $this->mrTemplate->AddVar("content","JENIS_POLICY",$data['jenis']['nama']);
      
      $list = new Policy();
      if(!empty($data['policy'])){
         foreach ($data['policy'] as $item) {
            $this->mrTemplate->ClearTemplate('policyfilelist','a');
            $file=$list->ListPolicyFile($item['ID']);
            if (empty($file)) {
        			$this->mrTemplate->AddVar('data_file', 'HISTORY_EMPTY', 'YES');
        		} else {
        		  $this->mrTemplate->AddVar('data_file', 'HISTORY_EMPTY', 'NO');
              for ($i=0; $i<sizeof($file); $i++) {
                if($file[$i]['is_download'] == 0){
                   $this->mrTemplate->AddVar('policyfilelist', 'IS_DOWNLOAD', '0');
                   $file_policy_type = $this->GetFiletype($file[$i]['file']);
                   if($file_policy_type=='swf'){
                      $popup="<a href=\"admin/upload_file/file_policy/{FILE}\" onclick=\"return hs.htmlExpand(this, { objectType: 'swf', width: 900,
		objectWidth: 900, objectHeight: 500, maincontentText: 'You need to upgrade your Flash player',
		swfOptions: { version: '7' }  } )\"
		class=\"highslide\">";
                      $this->mrTemplate->AddVar("policyfilelist","POPUP",$popup);
                   } else {
                      $popup = "<a href=\"javascript:Launch('admin/upload_file/file_policy/{FILE}')\">";
                      $this->mrTemplate->AddVar("policyfilelist","POPUP",$popup);
                   }                   
                }else{
                   $this->mrTemplate->AddVar('policyfilelist', 'IS_DOWNLOAD', '1');
                }
				
				$url_download=GTFWConfiguration::GetValue('application', 'policy_download_path');
                $this->mrTemplate->AddVar("policyfilelist","FILE",$url_download.$file[$i]['file']);
                $this->mrTemplate->AddVar("policyfilelist","NAMA",$file[$i]['file']);
                $this->mrTemplate->AddVar("policyfilelist","TANGGAL_UPLOAD",$list->IndonesianDate($file[$i]['tanggal_upload'],'YYYY-MM-DD'));
                $this->mrTemplate->ParseTemplate('policyfilelist', 'a');
              }
            }
            
            $this->mrTemplate->AddVar("policylist","TITLE",$item['TITLE']);
            $this->mrTemplate->AddVar("policylist","KATEGORI",$item['KATEGORI']);
            $this->mrTemplate->AddVar("policylist","SATKER",$item['SATKER']);
             $this->mrTemplate->AddVar("policylist","RINGKAS",$item['RINGKAS']);
            $this->mrTemplate->AddVar("policylist","URL_POLICY",Dispatcher::Instance()->GetUrl('policy','Policy','View','html').'&id='.$item['ID']);
            $this->mrTemplate->AddVar("policylist","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD'));
            $this->mrTemplate->ParseTemplate('policylist', 'a');
         }
      }else{
         
      }
    }
    
    function GetFiletype($Filename) { 

    if (substr_count($Filename, ".") == 0) {        // Check if there is a dot 

        return;                // Return Nothing 

    } else if (substr($Filename, -1) == ".") {        // Check if the string ends with . 

        return;                // Return Nothing 

    } else { 
        $FileType = strrchr ($Filename, ".");    // Split the string where the dot is 
        $FileType = substr($FileType, 1);    // Remove the dot 
        return $FileType;            // Return the filetype 
    } 
}
}
?>
