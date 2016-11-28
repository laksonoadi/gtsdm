<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/response/ProcessWizardPegawai.proc.class.php';

class DoAddWizardPangkatGolongan extends HtmlResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
        $ret = "html";
        $Obj = new Process($ret);
        //$obj = new Process();
        //set post
        $Obj->SetPost($_POST);
      
        $result = $Obj->InputPagol();
        $urlRedirect = '';
        $id = $_POST['pegId']->SqlString()->Raw();
        if($result) {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardSatuanKerja', 'view', 'html', array(null, $Obj->statusMsg, $Obj->cssDone), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardSatuanKerja', 'view', 'html').'&id='.$id;
        } else {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardPangkatGolongan', 'view', 'html', array($Obj->POST, $Obj->statusMsg, $Obj->statusCss), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardPangkatGolongan', 'view', 'html').'&id='.$id;
        }
        $this->RedirectTo($urlRedirect);
      
        return NULL;
    }

   function ParseTemplate($data = NULL) {
      
   }
}
?>
