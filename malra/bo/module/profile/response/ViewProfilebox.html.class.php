<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/profile/business/AppProfile.class.php';

class ViewProfilebox extends HtmlResponse {

	var $mComponentParameters;

	function TemplateModule() {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue( 'application', 'docroot') .'module/profile/template');
		$this->SetTemplateFile('view_profilebox.html');
	}

	function ProcessRequest() {
		$Obj=new AppProfile();
		$msg = Messenger::Instance()->Receive(__FILE__,$this->mComponentName);
		$idPeg = $msg[0][0];
		$return['profile']=$Obj->GetProfilePegawai($idPeg);
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$data['profile']['foto']) | empty($data['profile']['foto'])) { 
		 	$data['profile']['foto']='unknown.gif';
	  	}
		if (is_array($data['profile'])){
			$this->mrTemplate->AddVars('content', $data['profile'], 'PROFIL_');
		}
	}
}
?>
