<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/response/ProcessWizardPegawai.proc.class.php';

class DoAddWizardPegawai extends HtmlResponse {

    function TemplateModule() {
    }
   
    function ProcessRequest() {
        $ret = "html";
        $Obj = new Process($ret);
        //$obj = new Process();
        //set post
        $Obj->SetPost($_POST);
      
        $result = $Obj->InputDatpeg();
        $urlRedirect = '';
        if($result) {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardStatus', 'view', 'html', array(null, $Obj->statusMsg, $Obj->cssDone), Messenger::NextRequest);
            $id = $Obj->ObjPegawai->LastInsertId();
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardStatus', 'view', 'html').'&id='.$id;
        } else {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardPegawai', 'view', 'html', array($Obj->POST, $Obj->statusMsg, $Obj->statusCss), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardPegawai', 'view', 'html');
        }
        $this->RedirectTo($urlRedirect);
      
        return NULL;
    }

    function ParseTemplate($data = NULL) {
    }
}
?>
