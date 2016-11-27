<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/gtfw_module/business/GtfwModule.class.php';
   
class ViewGtfwSubModule extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/gtfw_module/template/');
      $this->SetTemplateFile('view_gtfw_submodule.html');
   }
   
   function Register(){
		$GtfwModule = new GtfwModule();
		$this->modulName=$_GET['moduleName'];
		if ($GtfwModule->aktif=='bo'){
			$path_module=GTFWConfiguration::GetValue( 'application', 'docroot').'module/'.$this->modulName.'/response/';
			$AppId=GTFWConfiguration::GetValue( 'application', 'application_id');
		}else{
			$path_module=str_replace("bo","fo",GTFWConfiguration::GetValue( 'application', 'docroot')).'module/'.$this->modulName.'/response/';
			$AppId=GTFWConfiguration::GetValue( 'application', 'application_portal_id');
		}
		$this->RegisterStatus=false;
		$GtfwModule->StartTrans();
		$result=true;
		if (file_exists($path_module)){
			$dir = dir($path_module);
			while (false !== ($entry = $dir->read())) {
		        if (($entry!='..')&&($entry!='.')){
					$Temp=$GtfwModule->PecahFile($entry);
					$ActionList=array("Do","View","Popup","Input","Combo");
					if (in_array($Temp['Action'],$ActionList)){
						if ((isset($this->POST['btnsimpan']))&&($result===true)&&($this->POST['registerModule']=='on')){
							$moduleLengkap=$this->modulName."-".$Temp['SubModuleName']."-".$Temp['Action']."-".$Temp['Type'];
							if ($this->POST[$moduleLengkap]=='on'){
								$params=array(
											'Module'=>$this->modulName->Raw(),
											'LabelModule'=>'['.$AppId.'] '.$Temp['Action'].' '.$Temp['SubModuleName'].' '.$Temp['Type'],
											'SubModule'=>$Temp['SubModuleName'],
											'Action'=>$Temp['Action'],
											'Type'=>$Temp['Type'],
											'Access'=>$this->POST['Access-'.$moduleLengkap],
											'ApplicationId'=>$AppId
										);
										
								$result=$GtfwModule->RegisterModule($params);
								if ($result===true){
									$ModuleRegistered[]=$GtfwModule->LastRegisterModule();
									if ($this->POST['default']==$moduleLengkap){
										$DefaultModuleId=$GtfwModule->LastRegisterModule();
									}
								}else{
									$ModuleRegistered=array();
								}
								
							}
							
							$GetModule=$GtfwModule->GetModuleByFile($this->modulName,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
							$EditMenuId=$GetModule[0]['MenuId'];
							$this->POST['EditMenuId']=$EditMenuId;
							
							if ($this->POST['default']==$moduleLengkap){
								$EditDefaultModuleId=$GetModule[0]['ModuleId'];
								$this->POST['EditDefaultModuleId']=$EditDefaultModuleId;
							}
						}
					}
		        }
			}
			
			if ((isset($this->POST['btnsimpan']))&&($result===true)&&(!empty($ModuleRegistered))&&($this->POST['registerMenu']=='on')){
				$this->POST['Proses']='Jika Register Module dan Registerkan Menu Baru';
				//Jika Register Module dan Registerkan Menu Baru
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$DefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId
							);
				$result=$GtfwModule->RegisterMenu($param_menu);
				if ($result===true){
					$MenuRegistered=$GtfwModule->LastRegisterMenu();
					for ($jjj=0; $jjj<sizeof($ModuleRegistered); $jjj++) {
						$result=$GtfwModule->UpdateModuleMenuId($MenuRegistered,$ModuleRegistered[$jjj]);
					}
				}
			}else if((isset($this->POST['btnsimpan']))&&($result===true)&&($EditMenuId!='')){
				$this->POST['Proses']='Jika Hanya Register Module sedangkan Menu Sudah Ada';
				//Jika Hanya Register Module sedangkan Menu Sudah Ada
			    if ($EditDefaultModuleId!=''){
					$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$EditDefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId,
								'MenuId'=>$EditMenuId
							);
					$result=$GtfwModule->UpdateRegisterMenu($param_menu);
					$result=$GtfwModule->UpdateMenuModuleDefault($EditDefaultModuleId,$EditMenuId);
				}
				for ($jjj=0; $jjj<sizeof($ModuleRegistered); $jjj++) {
					$result=$GtfwModule->UpdateModuleMenuId($EditMenuId,$ModuleRegistered[$jjj]);
				}
			}else if((isset($this->POST['btnsimpan']))&&($EditMenuId!='')&&($EditDefaultModuleId!='')){
				$this->POST['Proses']='Jika Hanya merubah Default Menu';
				//Jika Hanya merubah Default Menu
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$EditDefaultModuleId,
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId,
								'MenuId'=>$EditMenuId
							);
				$result=$GtfwModule->UpdateRegisterMenu($param_menu);
				
				$result=$GtfwModule->UpdateMenuModuleDefault($EditDefaultModuleId,$EditMenuId);
			}else if ((isset($this->POST['btnsimpan']))&&($this->POST['registerMenu']=='on')&&($this->POST['registerModule']!='on')){
				$this->POST['Proses']='Jika Hanya Register Menu Saja';
				//Jika Hanya Register Menu Saja
				$GetModuleLain=$GtfwModule->GetModuleByFile('home','home','view','html',$AppId);
				$param_menu=array(
								'MenuParentId'=>$this->POST['MenuParentId'],
								'MenuName'=>$this->POST['MenuName'],
								'MenuDefaultModuleId'=>$GetModuleLain[0]['ModuleId'],
								'IsShow'=>$this->POST['IsShow'],
								'IconPath'=>$this->POST['IconPath'],
								'ApplicationId'=>$AppId
							);
				$result=$GtfwModule->RegisterMenu($param_menu);
			}
		}
		$GtfwModule->EndTrans($result);
		return $result;
   }
   
   function ProcessRequest()
   {
		$GtfwModule = new GtfwModule();
		
		$this->POST = $_POST->AsArray();
	  
		$this->modulName=$_GET['moduleName'];
		
		if ($GtfwModule->aktif=='bo'){
			$path_module=GTFWConfiguration::GetValue( 'application', 'docroot').'module/'.$this->modulName.'/response/';
			$AppId=GTFWConfiguration::GetValue( 'application', 'application_id');
		}else{
			$path_module=str_replace("bo","fo",GTFWConfiguration::GetValue( 'application', 'docroot')).'module/'.$this->modulName.'/response/';
			$AppId=GTFWConfiguration::GetValue( 'application', 'application_portal_id');
		}
		
		if (isset($this->POST['btnsimpan'])){
			$result=$this->Register();
			if($result===true){
				$this->Pesan=$this->POST['btnsimpan'].' Module dan '.$this->POST['btnsimpan'].' Menu Berhasil';
				$this->css='notebox-done';
			}else{
				$this->Pesan=$this->POST['btnsimpan'].' Module dan '.$this->POST['btnsimpan'].' Menu Gagal';
				$this->css='notebox-warning';
			}
		}
		
		$i=0;
		if (file_exists($path_module)){
			$dir = dir($path_module);
			while (false !== ($entry = $dir->read())) {
		        if (($entry!='..')&&($entry!='.')){
					$Temp=$GtfwModule->PecahFile($entry);
					$ActionList=array("Do","View","Popup","Input","Combo");
					if (in_array($Temp['Action'],$ActionList)) {
						$listFile[$i]=$Temp;
						$GetModule=$GtfwModule->GetModuleByFile($this->modulName,$Temp['SubModuleName'],$Temp['Action'],$Temp['Type'],$AppId);
						$listFile[$i]['name_checkbox']=$this->modulName."-".$Temp['SubModuleName']."-".$Temp['Action']."-".$Temp['Type'];
						If (sizeof($GetModule)==0) {
							$listFile[$i]['checkbox']='<input type=checkbox name="'.$listFile[$i]['name_checkbox'].'" checked>';
							$listFile[$i]['combobox']='<select name="Access-'.$listFile[$i]['name_checkbox'].'"><option value="Exclusive">Exclusive</option><option value="All">All</option></select>';
							$this->RegisterStatus=true;
						}else{
							$listFile[$i]['checkbox']='Sudah';
							$listFile[$i]['combobox']=$GetModule[0]['Access'];
							$this->MenuId=$GetModule[0]['MenuId'];
						}
						
						If (($GetModule[0]['ModuleId']==$GetModule[0]['MenuDefaultModuleId'])&&(sizeof($GetModule)>0)) $listFile[$i]["default"]='checked'; else $listFile[$i]["default"]='';
						
						$i++;
					}
		        }
			}
		}
		
		if (!empty($this->MenuId)){
			$readonly='readonly=true';
			$return['dataMenu']=$GtfwModule->GetMenuById($this->MenuId);
		}
		
		$IsShow = array(0=>array('id'=>'Yes','name'=>'Yes'),1=>array('id'=>'No','name'=>'No'));
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'IsShow', 
		array('IsShow',$IsShow,$return['dataMenu'][0]['IsShow'],'',' style="width:100px;" '.$readonly), Messenger::CurrentRequest);
		
		$MenuParentId = $GtfwModule->GetParentMenu($AppId);
		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'MenuParentId', 
		array('MenuParentId',$MenuParentId,$return['dataMenu'][0]['MenuParentId'],'',' style="width:300px;" '.$readonly), Messenger::CurrentRequest);
  	    
		$return['dataSheet'] = $listFile;
		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      if($this->Pesan)
      {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
     //tentukan value judul, button dll sesuai pilihan bahasa 
	 
	 if (empty($this->MenuId)){
		$this->mrTemplate->AddVar('content', 'CHECKED_REGISTER_MENU', '<input type="checkbox" name="registerMenu" checked>');
	 }else{
		$this->mrTemplate->AddVar('content', 'READONLY', 'readonly=true');
		$this->mrTemplate->AddVar('content', 'MENUNAME', $data['dataMenu'][0]['MenuName']);
		$this->mrTemplate->AddVar('content', 'ICONPATH', $data['dataMenu'][0]['IconPath']);
	 }
     
     $this->mrTemplate->AddVar('content', 'TITLE', 'REGISTER MODULE');
	 $this->mrTemplate->AddVar('content', 'MODULE', $this->modulName);
     $this->mrTemplate->AddVar('content', 'JUDUL_DATA', 'Email Template Data');
     $this->mrTemplate->AddVar('content', 'LABEL_ACTION', $this->RegisterStatus ? 'Register' : 'Update Register');
	 
	 $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('gtfw_module','gtfwModule','view','html'));
	 $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('gtfw_module','gtfwSubModule','view','html').'&moduleName='.$this->modulName);
	  
	  
	  
	  if(empty($data['dataSheet'])){
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'YES');
         return NULL;
	  }else{
	     $this->mrTemplate->AddVar('data', 'DATA_EMPTY', 'NO');
	  }
	    $i = 1;
      $link = $data['link'];
  	  foreach ($data['dataSheet'] as $value)
      {
  	        $data = $value;//print_r($data);
  		    $data['number'] = $i;
  		    $data['class_name'] = ($i % 2 == 0) ? '' : 'table-common-even';
  		    $data['url_edit'] = $link['url_edit']."&dataId='".$data['filename']."'";
  		    $this->mrTemplate->AddVars('data_item', $data, '');
            $this->mrTemplate->parseTemplate('data_item', 'a');
            $i++;
  	  }
   }
}
   

?>