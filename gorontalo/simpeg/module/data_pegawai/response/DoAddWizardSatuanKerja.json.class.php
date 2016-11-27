<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/mutasi_satuan_kerja/response/ProcessMutasiSatuanKerja.proc.class.php';

class DoAddWizardSatuanKerja extends JsonResponse {

   function TemplateModule() {
   }
   
   function ProcessRequest() {
        $ret = "json";
        $Obj = new Process($ret);
        //obj = new Process();
        //set post
        $Obj->SetPost($_POST);
      
        $result = $Obj->InputSatker();
        $urlRedirect = '';
        $id = $_POST['pegId']->SqlString()->Raw();
        if($result) {
            Messenger::Instance()->Send('data_pegawai', 'dataPegawai', 'view', 'html', array(null, $Obj->statusMsg, $Obj->cssDone), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'dataPegawai', 'view', 'html');
        } else {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardSatuanKerja', 'view', 'html', array($Obj->POST, $Obj->statusMsg, $Obj->statusCss), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardSatuanKerja', 'view', 'html').'&id='.$id;
        }
        return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
    }

   function ParseTemplate($data = NULL) {
   }
}
?>
