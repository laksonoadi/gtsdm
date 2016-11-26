<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/policy/business/Policy.class.php';

class ViewPolicySatuanKerjaList extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/policy/template');
        $this->SetTemplateFile('view_policy_satuan_kerja_list.html');
    }

   function ProcessRequest() {
        $list = new Policy();
        
        $nav[0]['url']='';
        $nav[0]['menu']='';
        $title = "Policies & Regulations";
      
        Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','hidden',''), Messenger::CurrentRequest);
      
        return $list->ListSatuanKerjaPolicy();
   }

   function ParseTemplate($data) {
      $list = new Policy();
      if(!empty($data)){
			$this->mrTemplate->AddVar('satkerpolicywrapper', 'IS_EMPTY', 'NO');
         foreach ($data as $item) { 
            $this->mrTemplate->ClearTemplate('jenispolicylist','a');
            $jenis=$list->ListJenisPolicy($item['id']);
            if (empty($jenis)) {
        			$this->mrTemplate->AddVar('jenis_policy', 'HISTORY_EMPTY', 'YES');
        		} else {
        		  $this->mrTemplate->AddVar('jenis_policy', 'HISTORY_EMPTY', 'NO');
              for ($i=0; $i<sizeof($jenis); $i++) {
                $this->mrTemplate->AddVar("jenispolicylist","JENIS_POLICY",$jenis[$i]['jenis_policy']);
                $this->mrTemplate->AddVar("jenispolicylist","TOTAL_POLICY",$jenis[$i]['total_policy']);
                if(empty($jenis[$i]['id'])){
                  $this->mrTemplate->AddVar("jenispolicylist","URL_POLICY",Dispatcher::Instance()->GetUrl('policy','policySatuanKerjaList','View','html'));
                }else{
                  $this->mrTemplate->AddVar("jenispolicylist","URL_POLICY",Dispatcher::Instance()->GetUrl('policy','policyList','View','html').'&satkerId='.$item['id'].'&jnspolicyId='.$jenis[$i]['id']);
                }
                $this->mrTemplate->ParseTemplate('jenispolicylist', 'a');
              }
            }
            
            $this->mrTemplate->AddVar("satkerpolicylist","ID",$item['id']);
            $this->mrTemplate->AddVar("satkerpolicylist","SATKER",$item['satker']);
            $this->mrTemplate->AddVar("satkerpolicylist","TOTAL_POLICY",$item['total_policy']);
            $this->mrTemplate->AddVar("satkerpolicylist","URL_POLICY",Dispatcher::Instance()->GetUrl('policy','policySatuanKerjaList','View','html'));
            $this->mrTemplate->ParseTemplate('satkerpolicylist', 'a');
         }
      } else {
			$this->mrTemplate->AddVar('satkerpolicywrapper', 'IS_EMPTY', 'YES');
      }
    }
}
?>
