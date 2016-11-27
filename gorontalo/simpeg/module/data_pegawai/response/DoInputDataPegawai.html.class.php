<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/response/ProcessDataPegawai.proc.class.php';

class DoInputDataPegawai extends HtmlResponse {

    function TemplateModule() {
    }
   
    function ProcessRequest() {
        $ret = "html";
        $obj = new Process($ret);
        //$obj = new Process();
        //set post
        $obj->SetPost($_POST);
      
        $urlRedirect = $obj->InputDatpeg();     
        $this->RedirectTo($urlRedirect) ;      
      
        return NULL;
    }

    function ParseTemplate($data = NULL) {
    }
}
?>
