<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ProcessLayoutGraphic {

   function Add() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
   	  if ($_POST['judul']!='') {
           $grafik = $rep->DoInsertGraphic($_POST['judul'], $_POST['tabel'], $_POST['layout']);
           if ($grafik) $err = 'add'; else $err = 'add|fail';
           $sub = 'layoutGraphic';
   	  } else {
            $sub = 'addLayoutGraphic';
            $err = $_POST['graph_id'];
        }
     } else $sub = 'layoutGraphic';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Update() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
   	  if ($_POST['judul']!='') {
           $grafik = $rep->DoUpdateGraphic($_POST['judul'], $_POST['tabel'], $_POST['layout'], $_POST['graph_id']);
           if ($grafik) $err = 'upd'; else $err = 'upd|fail';
           $sub = 'layoutGraphic';
   	  } else {
            $sub = 'updateLayoutGraphic';
            $err = $_POST['graph_id'];
        }
     } else $sub = 'layoutGraphic';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Delete() {
      $rep = new Report();
	   if ($rep->DoDeleteGraphic($_POST['idDelete'])) $err = 'del'; else $err = 'del|fail';
      return Dispatcher::Instance()->GetUrl('report', 'layoutGraphic', 'view', 'html')."&err=$err"; 
   }

}
?>
