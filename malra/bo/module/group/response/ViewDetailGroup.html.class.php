<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/group/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppGroup.class.php';

class ViewDetailGroup extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
       $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').
         'module/group/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_group.html');
   }

    function ProcessRequest() {
      
     $msg = Messenger::Instance()->Receive(__FILE__);
     $pegId = $_GET['groupId']->Integer()->Raw();
      $return['Pesan'] = $msg[0][1];
      $return['css'] = $msg[0][2];
    
    $applicationId = GTFWConfiguration::GetValue( 'application', 'application_id');
      
      $groupObj = new AppGroup();
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
     // print_r($pegId);
      $dataGroup = $groupObj->GetDataGroupByIdDetail($pegId, $applicationId, true);
// $dataGroup = $groupObj->GetDataGroup('', $applicationId, true);

      #print_r($dataGroup);
     $return['dataGroup'] = $dataGroup;
    $return['start'] = $startRec+1;
      return $return;
   }
   
 
   
   function ParseTemplate($data = NULL) {      

      // $dataPegawai[$i]['URL_DETAIL'] = Dispatcher::Instance()->GetUrl('data_istri_suami','dataIstriSuami', 'view', 'html').'&dataId='. $idpeg;
         if (empty($data['dataGroup'])) {
         $this->mrTemplate->AddVar('data_group', 'GROUP_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_group', 'GROUP_EMPTY', 'NO');
         $dataGroup = $data['dataGroup'];
         $len = sizeof($dataGroup);
         $menuName='';
         $idGroup='';
         $no=0;
         for ($i=0; $i<$len; $i++) {
            if($idGroup!=$dataGroup[$i]['group_id']){
               $no++;
               $menuBaru[$no]['no']=$no;
               $menuBaru[$no]['group_id']=$dataGroup[$i]['group_id'];
               $menuBaru[$no]['group_name']=$dataGroup[$i]['group_name'];
               $menuBaru[$no]['group_description']=$dataGroup[$i]['group_description'];
               $menuBaru[$no]['unit_kerja']=$dataGroup[$i]['unit_kerja'];
               $idGroup=$dataGroup[$i]['group_id'];
               $menuName='';
            }
            if($dataGroup[$i]['menu_name']!=$menuName){
               $menuBaru[$no]['hak_akses'] .='<strong>'.$dataGroup[$i]['menu_name'].'</strong><br>'.'&nbsp;&nbsp;'.$dataGroup[$i]['sub_menu'].'<br>';
               $menuName=$dataGroup[$i]['menu_name'];
            } else {
               $menuBaru[$no]['hak_akses'].='&nbsp;&nbsp;'.$dataGroup[$i]['sub_menu'].'<br>';
            }
         }
         
         
         $no=1;
         for($i=1;$i<count($menuBaru)+1;$i++){            
            $menuBaru[$i]['number'] = $no;
            if ($no % 2 != 0) {
               $dataGroup[$i]['class_name'] = 'table-common-even';
            } else {
               $dataGroup[$i]['class_name'] = '';
            }
            $no++;
            $idEnc = Dispatcher::Instance()->Encrypt($menuBaru[$i]['group_id']);
            $menuBaru[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('group', 'inputGroup', 'view', 'html') . '&grp=' . $idEnc;

            $idEnc = Dispatcher::Instance()->Encrypt($menuBaru[$i]['group_id']);
                        
            $urlAccept = 'group|deleteGroup|do|html-cari-'.$cari;
            $urlReturn = 'group|group|view|html-cari-'.$cari;
            $label = 'Group';
            $dataName = $menuBaru[$i]['group_name'];
            $menuBaru[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;

                  //$dataGroup[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('user', 'deleteGroup', 'do', 'html') . '&grp=' . $idEnc;

            $this->mrTemplate->AddVar('data_group_item', $menuBaru[$i], 'GROUP_URL_DETAIL');

            $homegroup = Dispatcher::Instance()->GetUrl('group', 'Group', 'view', 'html');
            $this->mrTemplate->AddVar('content',  'URL_ACTION_BACK',$homegroup);

            
                  $this->mrTemplate->AddVars('data_group_item', $menuBaru[$i], 'GROUP_');
                  $this->mrTemplate->parseTemplate('data_group_item', 'a');
         }

      }

  }
}

?>