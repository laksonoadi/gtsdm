<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/menu/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/Menu.class.php';

class ViewHome extends HtmlResponse {

   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/home/template');
      $this->SetTemplateFile('view_home.html');
   }

   function ProcessRequest() {
      $menuObj = new Menu();
      $menuObj->LoadSql('module/menu/business/'.GTFWConfiguration::GetValue( 'application',array('db_conn',0,'db_type')).'/menu.sql.php');
      $menu = $menuObj->ListAllAvailableSubMenuForGroup($_SESSION['username'],$_GET['dmmid']);
      //print_r($menu);exit;
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
      if (!empty($data)) {
         
      }
   }
}
?>
