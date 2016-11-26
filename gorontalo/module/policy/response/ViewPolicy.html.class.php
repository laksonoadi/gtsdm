<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/policy/business/Policy.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class ViewPolicy extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/policy/template');
        $this->SetTemplateFile('view_policy.html');
    }

    function ProcessRequest() {
        $id = $_GET['id'];
        $DataPolicy = new Policy();
        return $DataPolicy->GetPolicyById($id);
    }

    function ParseTemplate($data) {
        $list = new Policy();
        $item=$data;
        $this->mrTemplate->AddVar("content","TITLE",$item['TITLE']);
        $this->mrTemplate->AddVar("content","ARTIKEL",$item['ARTIKEL']);
        $this->mrTemplate->AddVar("content","PENGIRIM",$item['PENGIRIM']);
        $this->mrTemplate->AddVar("content","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD'));
        //print_r($data);
        /*$this->mrTemplate->AddVar('content','TITLE',$data[0]['TITLE']);
        $this->mrTemplate->AddVar('content','ARTICLE',stripslashes($data[0]['ARTICLE']));
        $this->mrTemplate->AddVar('image','IMG_URL',GTFWConfiguration::GetValue('application', 'file_policy').$data[0]['FOTO']);*/
    }
}
?>