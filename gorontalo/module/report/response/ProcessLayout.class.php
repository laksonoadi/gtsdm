<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ProcessLayout {

   function Add() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
   	  if ($_POST['nama']!='' AND $_POST['judul']!='') {
           $menu = $rep->DoInsertMenu($_POST['submenu'], $_POST['nama'], $_FILES['icon']['name'], $_POST['urutan']);
           if ($_FILES['icon']['name']!='') {
               move_uploaded_file($_FILES['icon']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot') . 
                  'images/icons/'.$_FILES['icon']['name']); 
           }
          // $id = $rep->GetId('dummy_id', 'report_dummy_menu');
	        $layout = $rep->DoInsertLayout($_POST['judul'], $_POST['layout'], $_POST['submenu']);
           if ($layout) $err = 'add'; else $err = 'add|fail';
           $sub = 'layoutTable';
   	  } else {
            $sub = 'addLayoutTable';
            $err = $_POST['lay_id'];
        }
     } else $sub = 'layoutTable';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Update() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
   	  if ($_POST['nama']!='' AND $_POST['judul']!='') {
           if ($_FILES['icon']['name']!='') {
               if ($_POST['icon_old']!='') unlink(GTFWConfiguration::GetValue( 'application', 'docroot') . 
                  'images/icons/'.$_POST['icon_old']);
               move_uploaded_file($_FILES['icon']['tmp_name'], GTFWConfiguration::GetValue( 'application', 'docroot') . 
                  'images/icons/'.$_FILES['icon']['name']); 
               $file = $_FILES['icon']['name'];
           } else $file = $_POST['icon_old'];
           $layout = $rep->GetLayoutById($_POST['lay_id']);
           $menu = $rep->DoUpdateMenu($_POST['submenu'], $_POST['nama'], $file, $_POST['urutan'], $layout['dummy_id']);
	        $layout = $rep->DoUpdateLayout($_POST['judul'], $_POST['layout'], $_POST['lay_id']);
           if ($layout) $err = 'upd'; else $err = 'upd|fail';
           $sub = 'layoutTable';
   	  } else {
            $sub = 'updateLayoutTable';
            $err = $_POST['lay_id'];
        }
     } else $sub = 'layoutTable';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Delete() {
      $rep = new Report();	   
      $layout = $rep->GetLayoutById($_POST['idDelete']);
	   $menu = $rep->DoDeleteMenu($layout['dmmenuid']);
	   $dummy = $rep->DoDeleteDummyModule($layout['dmmenuid'], 504);
	   if ($rep->DoDeleteLayout($_POST['idDelete'])) $err = 'del'; else $err = 'del|fail';
      return Dispatcher::Instance()->GetUrl('report', 'layoutTable', 'view', 'html')."&err=$err"; 
   }

}
?>
