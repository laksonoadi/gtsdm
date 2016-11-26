<?php

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';

class ProcessQuery {

   function Add() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
   	  if ($_POST['nama']!='' and $_POST['temp']!='') {
            if ($rep->DoInsertQuery($_POST['nama'], $_POST['desk'], $_POST['temp']->Raw(), $_POST['param']->Raw(), $_POST['db'])) 
               $err = 'add';
            else $err = 'add|fail';
            $sub = 'query';
   	  } else {
            $sub = 'addQuery';
            $err = $_POST['que_id'];
        }
        //echo $sub;exit;
     } else $sub = 'query';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Update() {
     $rep = new Report();
     if (isset($_POST['simpan'])) {
        if ($_POST['nama']!='' and $_POST['temp']!='') {
           if ($rep->DoUpdateQuery($_POST['nama'], $_POST['desk'], $_POST['temp']->Raw(), $_POST['param']->Raw(), $_POST['db'],
               $_POST['que_id'])) $err = 'upd';
      	  else $err = 'upd|fail';
           $sub = 'query';
        } else {
            $sub = 'addQuery';
            $err = $_POST['que_id'];
        }
     } else $sub = 'query';
     return Dispatcher::Instance()->GetUrl('report', $sub, 'view', 'html')."&err=$err";
   }
   
   function Delete() {
      $rep = new Report();	   
	   if ($rep->DoDeleteQuery($_POST['idDelete'])) $err = 'del'; else $err = 'del|fail';
      return Dispatcher::Instance()->GetUrl('report', 'query', 'view', 'html')."&err=$err"; 
   }

}
?>
