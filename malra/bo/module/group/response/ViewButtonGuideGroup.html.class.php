<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/group/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppGroup.class.php';

class ViewButtonGuideGroup extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
       $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/group/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_button_guide_group.html');
   }

    function ProcessRequest() {
      $applicationId = GTFWConfiguration::GetValue( 'application', 'application_id');
      
      $groupObj = new AppGroup();
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      $groupId = Security::Authentication()->GetCurrentUser()->GetActiveUserGroupId();
      
      $dataGroup = $groupObj->GetDataGuideGroupByIdDetail($groupId, $applicationId);
      
      $return['dataGroup'] = $dataGroup;
      return $return;
   }
   
 
   
   function ParseTemplate($data = NULL) {
      $dataGroup = $data['dataGroup'];
      if(!empty($dataGroup[0])) {
         $path_panduan = GTFWConfiguration::GetValue('application', 'panduan_group_path');
         $this->mrTemplate->AddVar('button_guide', 'URL', $path_panduan.$dataGroup[0]['guidefile']);
         $this->mrTemplate->AddVar('button_guide', 'GROUP_NAME', $dataGroup[0]['groupname']);
      }
  }
}

?>