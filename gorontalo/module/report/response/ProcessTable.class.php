<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ProcessTable {

   function Add() {
     $rep = new Report();
     $sub = 'table';
     if (isset($_POST['simpan'])) {
   	  if ($_POST['nama']!='' and $_POST['temp']!='') {
            if ($rep->DoInsertTable($_POST['nama'], $_POST['temp']->Raw(), $_POST['graphic'], $_POST['tempParam']->Raw())) 
               $err = 'add';
            else $err = 'add|fail';
   	  } else {
            $sub = 'addTable';
            $err = $_POST['tab_id'];
        }
     }
	 //print_r($_POST);
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }

   function Update() {
     $rep = new Report();
     $sub = 'table';
     if (isset($_POST['simpan'])) {
   	  if ($_POST['nama']!='' and $_POST['temp']!='') {
            if ($rep->DoUpdateTable($_POST['nama'], $_POST['temp']->Raw(), $_POST['graphic'], $_POST['tempParam']->Raw(), 
               $_POST['tab_id'])) $err = 'upd';
            else $err = 'upd|fail';
   	  } else {
            $sub = 'addTable';
            $err = $_POST['tab_id'];
        }
     }
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }

   function Delete() {
      $rep = new Report();	   
	   if ($rep->DoDeleteTable($_POST['idDelete'])) $err = 'del'; else $err = 'del|fail';
      return Dispatcher::Instance()->GetUrl('report', 'table', 'view', 'html')."&err=$err"; 
   }

}
?>
