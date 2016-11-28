<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/response/ProcessWizardPegawai.proc.class.php';

class DoAddWizardStatus extends JsonResponse {

	function TemplateModule() {
	}
   
	function ProcessRequest() {
        $ret = "json";
        $Obj = new Process($ret);
        //obj = new Process();
        //set post
        $Obj->SetPost($_POST);
      
        $result = $Obj->InputStatus();
        $urlRedirect = '';
        $id = $_POST['pegId']->SqlString()->Raw();
        if($result) {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardPangkatGolongan', 'view', 'html', array(null, $Obj->statusMsg, $Obj->cssDone), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardPangkatGolongan', 'view', 'html').'&id='.$id;
        } else {
            Messenger::Instance()->Send('data_pegawai', 'inputWizardStatus', 'view', 'html', array($Obj->POST, $Obj->statusMsg, $Obj->statusCss), Messenger::NextRequest);
            $urlRedirect = Dispatcher::Instance()->GetUrl('data_pegawai', 'inputWizardStatus', 'view', 'html').'&id='.$id;
        }
        return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
    }

	function ParseTemplate($data = NULL) {
	}
}
?>
