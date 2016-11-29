<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user_portal/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/AppUser.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/email/business/Email.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewUser extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot').'module/user_portal/template');
      $this->SetTemplateFile('view_user.html');
   }
   
   function DoEmail($id,$pass, $file){
		    //Block Untuk Kirim Email
		    $this->pegawaiObj=new DataPegawai();
		    $this->ObjEmail=new Email();
        $dataPegawai=$this->pegawaiObj->GetDataPegawaiDetailByUserId($id);
        $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
        $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegEmail']);
          
        $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
        $arrBody[1]['replace']='{USERNAME}'; $arrBody[1]['with']=$dataPegawai['UserName'];
        $arrBody[2]['replace']='{PASSWORD}'; $arrBody[2]['with']=$pass;
        
        $body=$this->ObjEmail->getBodyEmail($file,$arrBody);
        $subject=$this->ObjEmail->getSubjectEmail($file);
        $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body); 
        //Akhir Kirim Email
		    return $kirim;
   }
   
   function NotDoEmail($id,$pass, $file){
		    //Block Untuk Kirim Email
		    $this->pegawaiObj=new DataPegawai();
		    $this->ObjEmail=new Email();
        $dataPegawai=$this->pegawaiObj->GetDataPegawaiDetailByUserId($id);
        
        $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
        $arrBody[1]['replace']='{USERNAME}'; $arrBody[1]['with']=$dataPegawai['UserName'];
        $arrBody[2]['replace']='{PASSWORD}'; $arrBody[2]['with']=$pass;
        
        $body=$this->ObjEmail->getBodyEmail($file,$arrBody);
        $kirim='<pre><font size=2>'.$body.'</font></pre>';
        //Akhir Kirim Email
		    return $kirim;
   }
   
   function ProcessRequest() {
   
      $msg = Messenger::Instance()->Receive(__FILE__);
      $return['Pesan'] = $msg[0][1];
      $return['css'] = $msg[0][2];
      
      $userObj = new AppUser();
      
      if (isset($_POST['username'])) $userName = $_POST['username']; 
         elseif (isset($_GET['uname'])) $userName = Dispatcher::Instance()->Decrypt($_GET['uname']);
                     
      if(isset($_POST['check'])){
         $user_check = $_POST['check']['user'];
         $pUserName = $userName;
      }elseif(isset($_GET['user_check'])){
         $user_check = Dispatcher::Instance()->Decrypt($_GET['user_check']);
         $pUserName = $userName;
      }else{
         $pUserName = '';
      } 
      
      if (isset($_POST['username'])) $realName = $_POST['realname']; 
         else if (isset($_GET['rname'])) $realName = Dispatcher::Instance()->Decrypt($_GET['rname']);
      
      if(isset($_POST['check'])){
          $real_check = $_POST['check']['real'];
          $pRealName = $realName ;
      }elseif(isset($_GET['real_check'])){
          $real_check = Dispatcher::Instance()->Decrypt($_GET['real_check']);
          $pRealName = $realName ;
      }else{
         $pRealName = '';
      }
      
      if (isset($_GET['cari'])) {
         $carii=explode("|", $_GET['cari']);
         $userName=$carii[0];
         $realName=$carii[1];
         $pUserName=$carii[0];
         $pRealName=$carii[1];
      }
      
      if ($_GET['status']=='aktifkan') {
         $status=$userObj->SetStatus('Yes',$_GET['id']);
         $return['Pesan'] = 'User Activate Successfully';
         $return['css'] = 'notebox-done';
      }else if ($_GET['status']=='nonaktifkan'){
         $status=$userObj->SetStatus('No',$_GET['id']);
         $return['Pesan'] = 'User Disabled Successfully';
         $return['css'] = 'notebox-done';
      }else if ($_POST['status']=='reset'){
         $reset=$userObj->ResetPassword($_POST['id']);
         if ($reset===false){
            $return['Pesan'] = 'Password Reset Failure';
            $return['css'] = 'notebox-warning';
         }else{
            $return['Pesan'] = 'Password Reset Successfully';
            $return['css'] = 'notebox-done';
            $Status=GTFWConfiguration::GetValue( 'application', 'email_notifications');
            if ($status==true){
              $kirim=$this->DoEmail($_POST['id'],$reset,'email_reset_password');
            }else{
              $kirim=$this->NotDoEmail($_POST['id'],$reset,'email_reset_password');
            }
            
            $return['Pesan'] .= '<br/>'.$kirim;
         }
      }
      
      $applicationId = GTFWConfiguration::GetValue('application', 'application_portal_id');
      
      $totalData = $userObj->GetCountDataUser($pUserName, $pRealName, $applicationId);
      $itemViewed = 10;
      $currPage = 1;
      $startRec = 0 ;
      if(isset($_GET['page'])) {
         $currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();  
         $startRec =($currPage-1) * $itemViewed;
      }
      
      $dataUser = $userObj->GetDataUser($startRec,$itemViewed, $pUserName, $pRealName, $applicationId);
      //print_r($totalData);
      if(!empty($dataUser)){
         $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 
                  Dispatcher::Instance()->mSubModule, 
                  Dispatcher::Instance()->mAction, 
                  Dispatcher::Instance()->mType. 
                  '&uname=' . Dispatcher::Instance()->Encrypt($userName). 
                  '&rname=' . Dispatcher::Instance()->Encrypt($realName).
                  '&user_check='. Dispatcher::Instance()->Encrypt($user_check).
                  '&real_check='. Dispatcher::Instance()->Encrypt($real_check)
                  );
         Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', 
            array($itemViewed,$totalData, $url, $currPage), 
            Messenger::CurrentRequest);
      }
            
      $return['dataUser'] = $dataUser;
      $return['start'] = $startRec+1;
      $return['search']['userName'] = $userName;
      $return['search']['realName'] = $realName;
      $return['check']['user'] = $user_check;
      $return['check']['real'] = $real_check;
      return $return;
   }

   function ParseTemplate($data = NULL) {
       
      if(($data['check']['real']!=''))
         $this->mrTemplate->AddVar('content', 'REAL_CHECKED', 'CHECKED');
      
      if(($data['check']['user']!=''))
      $this->mrTemplate->AddVar('content', 'USER_CHECKED', 'CHECKED');
      
      $cari=$data['search']['userName'].'|'.$data['search']['realName'];
      $this->mrTemplate->AddVar('content', 'USERNAME', $data['search']['userName']);
      $this->mrTemplate->AddVar('content', 'REALNAME', $data['search']['realName']);
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('user_portal', 'user', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'USER_URL_ADD', Dispatcher::Instance()->GetUrl('user_portal', 'inputUser', 'view', 'html') );

      if($data['Pesan']) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['Pesan']);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
      }

      if (empty($data['dataUser'])) {
         $this->mrTemplate->AddVar('data_user', 'USER_EMPTY', 'YES');
      } else {
         $this->mrTemplate->AddVar('data_user', 'USER_EMPTY', 'NO');
         $dataUser = $data['dataUser'];
         $len = sizeof($dataUser);
         
         for ($i=0; $i<$len; $i++) {
               $no = $i+$data['start'];
               $dataUser[$i]['number'] = $no;
               if ($no % 2 != 0) {
                  $dataUser[$i]['class_name'] = 'table-common-even';
               } else {
                  $dataUser[$i]['class_name'] = '';
               }
               
               $idEnc = Dispatcher::Instance()->Encrypt($dataUser[$i]['user_id']);
               $userEnc = Dispatcher::Instance()->Encrypt($dataUser[$i]['user_name']);
               $dataUser[$i]['url_edit'] = Dispatcher::Instance()->GetUrl('user_portal', 'inputUser', 'view', 'html') . 
                  '&usr=' . $idEnc .'&cari='.$cari;
                  
               if ($dataUser[$i]['is_active'] == 'Yes') {
                  $dataUser[$i]['status'] = 'Active';
                  $dataUser[$i]['aksi_status'] = 'Disable';
                  $dataUser[$i]['icon_status'] = 'check';
                  $dataUser[$i]['url_edit_status'] = Dispatcher::Instance()->GetUrl('user_portal', 'user', 'view', 'html') . '&id=' . $idEnc .'&status=nonaktifkan'.'&cari='.$cari;
               } else {
                  $dataUser[$i]['status'] = 'Unactive';
                  $dataUser[$i]['aksi_status'] = 'Activate';
                  $dataUser[$i]['icon_status'] = 'clipboard';
                  $dataUser[$i]['url_edit_status'] = Dispatcher::Instance()->GetUrl('user_portal', 'user', 'view', 'html') . '&id=' . $idEnc .'&status=aktifkan'.'&cari='.$cari;
               }
               
               $urlAccept = 'user_portal|deleteUser|do|html-cari-'.$cari;
               $urlReturn = 'user_portal|user|view|html-cari-'.$cari;
               $label = 'User';
               $dataName = $dataUser[$i]['user_name'];
               
               $dataUser[$i]['url_delete'] = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='. $urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
               $dataUser[$i]['url_updatepassword']=Dispatcher::Instance()->GetUrl('user_portal', 'user', 'view', 'html') . '&id=' . $idEnc .'&status='.md5('reset').'&cari='.$cari;
               /*$dataUser[$i]['url_updatepassword'] = Dispatcher::Instance()->GetUrl('user_portal', 'changePassword', 'view', 'html') . 
                  '&usr=' . $idEnc.'&cari='.$cari;*/
               if($_SESSION['username']==$dataUser[$i]['user_name']){
                  $dataUser[$i]['display_status'] = 'none';
               }else{
                  $dataUser[$i]['display_status'] = '';
               }
               $this->mrTemplate->AddVars('data_user_item', $dataUser[$i], 'USER_');
               $this->mrTemplate->parseTemplate('data_user_item', 'a');                                            
         }
      } 
   }
}
?>