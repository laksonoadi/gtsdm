<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/policy/business/Policy.class.php';

class ViewPolicyFileSideList extends HtmlResponse {
   function TemplateModule() {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
            'module/policy/template');
         $this->SetTemplateFile('view_policy_file_side_list.html');
   }

   function ProcessRequest() {
         $params = $this->mComponentParameters;
         
         // Display 5 by default
         $num = isset($params['num']) ? (int)$params['num'] : 5;
         
         $list = new Policy();
         
         return $list->ListNewestFile($num);
   }

   function ParseTemplate($data) {
      $list = new Agenda();
      if(!empty($data)) {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'NO');
         foreach ($data as $item) {
            $fileinfo = pathinfo($item['file']);
            
            $item['filename'] = $fileinfo['basename'];
            $item['url_file'] = $item['file'];
            $item['tanggal'] = $list->IndonesianDate($item['tanggal_upload'],'YYYY-MM-DD');
            $item['url_policy'] = Dispatcher::Instance()->GetUrl('policy', 'policyList', 'View', 'html').'&satkerId='.$item['satkerpolicyId'].'&jnspolicyId='.$item['jnspolicyId'];
            $this->mrTemplate->AddVars('item', $item);
            $this->mrTemplate->ParseTemplate('item', 'a');
         }
      } else {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'YES');
      }
   }
   
   
}
?>
