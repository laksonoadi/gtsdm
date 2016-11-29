<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/tombol_aksi/business/tombol_aksi.class.php';

class ViewTombolAksi extends HtmlResponse {

    function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/tombol_aksi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_tombol_aksi.html');    
    } 
	
	function TemplateBase() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') . 'main/template/');
      $this->SetTemplateFile('document-blank.html');
      $this->SetTemplateFile('layout-blank.html');
	}
    
    function ProcessRequest() {
		$Obj = new TombolAksi;
		$GET = $_GET->AsArray();
		$data['aksi'] = $GET['aksi'];
		$data['jenis'] = $GET['jenis'];
		$data['params'] = explode(':',$GET['params']);
		$data['IsShow']=$Obj->GetReferensiTombolAksi($data['jenis'],$data['params']);
		return $data;
    }
    
    function ParseTemplate($data = NULL) {
		$this->mrTemplate->AddVar('content', 'ISSHOW', $data['IsShow']);
    }
}
?>
