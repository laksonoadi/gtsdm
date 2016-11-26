<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/menu/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/Menu.class.php';

class ViewSubmenu extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/menu/template');
      $this->SetTemplateFile('view_submenu.html');
   }

   function ProcessRequest() {
      $menuObj = new Menu();
      $menuObj->LoadSql('module/menu/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/menu.sql.php');
      if (($_GET['mod']!='home')&&(empty($_GET['dmmid']))) {
        $menuId=$menuObj->GetParentMenuIdByModule($_GET['mod']);
        // print_r($menuId);
        $_GET['dmmid']=$menuId[0]['dmmid'];
        $_GET['mid']=$menuId[0]['mid'];
        $_GET['menuId']=$menuId[0]['menuId2'];
      }
      $menu = $menuObj->ListAllAvailableSubMenuForGroup($_SESSION['username'],$_GET['dmmid']);
      
      return $menu;
   }
   
   function GetMenuKey ($name, $arrMenu, $len) {
      for ($i=0; $i<$len; $i++) {
         if ($name == $arrMenu[$i]) {
            return  $i;
         }
      }
      return -1;
   }

   function ParseTemplate($data = NULL) {
      if (!empty($_GET['dmmid'])){
            $this->mrTemplate->AddVar('home', 'IS_HOME', 'NO'); 
            for($i=0;$i<sizeof($data);$i++){
            $url='';
                if($data[$i]['ParentMenuId']=='0'){
                   $url = '&dmmid='.$data[$i]['MenuId'].'&mid='.$data[$i]['MenuId'];
                   
        					 $this->mrTemplate->addVar('icon_menu', 'MOUSE_UP', 'onMouseUp="ShowMenu('.$data[$i]['MenuId'].')"');
        				}
        				
        				if ($_GET['menuId']==$data[$i]['MenuId']){
        				    $data[$i]['MenuName'] = '<b>'.$data[$i]['MenuName'].'</b>';
                }
                $this->mrTemplate->addVar('icon_menu', 'LINK_URL', Dispatcher::Instance()->GetUrl($data[$i]['Module'], $data[$i]['SubModule'], $data[$i]['Action'], $data[$i]['Type']).$url);
                $this->mrTemplate->addVar('icon_menu', 'ICON_NAME', $data[$i]['IconPath']);
                $this->mrTemplate->addVar('icon_menu', 'LINK_NAME', $data[$i]['MenuName']);
    				
                $this->mrTemplate->parseTemplate('icon_menu', 'a');
             }         
      }else {
            $this->mrTemplate->AddVar('home', 'IS_HOME', 'YES');
      }
   }
}
?>
