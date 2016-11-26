<?php
//By Wahyono
class GtfwModule extends Database {
   protected $mSqlFile = 'module/gtfw_module/business/gtfw_module.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber); 
	}
   
	function PecahFile($file){
        $file="X".$file;
		$Action=array("Do","View","Popup","Process","Input","Combo","Destroy");
		//Actionnya
		for ($i=0; $i<sizeof($Action); $i++){
			if (strpos($file,$Action[$i])==1){
				$return['Action']=$Action[$i];
				$file=str_replace("X".$Action[$i],"",$file);
				Break;
			}
		}
		
		//Submodule Name
		$i=strpos($file,".");
		$return["SubModuleName"]=substr($file,0,$i);
		$file=str_replace($return["SubModuleName"].".","",$file);
		
		//Type Module
		$i=strpos($file,".");
		$return["Type"]=substr($file,0,$i);
		return $return;
	}
	
	function GetModuleByFile($Module,$SubModule,$Action,$Type,$AppId){
		$result = $this->Open($this->mSqlQueries['get_module_by_file'], array($Module,$SubModule,$Action,$Type,$AppId));
		return $result;
	}
	
	function GetParentMenu($AppId){
		$result = $this->Open($this->mSqlQueries['get_parent_menu'], array($AppId));
		$result2[0]['id']=0;
		$result2[0]['name']='Tidak Ada Parent';
		for ($i=0; $i<sizeof($result); $i++){
			$result2[$i+1]=$result[$i];
		}
		$result=$result2;
		return $result;
	}
	
	function GetMenuById($Id){
		$result = $this->Open($this->mSqlQueries['get_menu_by_id'], array($Id));
		return $result;
	}
	
	function RegisterModule($params){
		$result = $this->Execute($this->mSqlQueries['register_module'], $params);
		return $result;
	}
	
	function LastRegisterModule(){
		$result = $this->Open($this->mSqlQueries['last_register_module'], array());
		return $result[0]['last_id'];
	}
	
	function RegisterMenu($params){
		$result = $this->Execute($this->mSqlQueries['register_menu'], $params);
		return $result;
	}
	
	function UpdateRegisterMenu($params){
		$result = $this->Execute($this->mSqlQueries['update_register_menu'], $params);
		return $result;
	}
	
	function LastRegisterMenu(){
		$result = $this->Open($this->mSqlQueries['last_register_menu'], array());
		return $result[0]['last_id'];
	}
	
	function UpdateModuleMenuId($menuId,$arrModule){
		$result = $this->Execute($this->mSqlQueries['update_module_menu_id'], array($menuId,$arrModule));
		return $result;
	}
	
	function UpdateMenuModuleDefault($moduleId,$menuId){
		$result = $this->Execute($this->mSqlQueries['update_menu_module_default'], array($moduleId,$menuId));
		return $result;
	}
   
	function IndonesianDate($StrDate, $StrFormat)
	{
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day." January ".$Year;
						break;
			case "02" :	$StrResult = $Day." February ".$Year;
						break;
			case "03" :	$StrResult = $Day." March ".$Year;
						break;
			case "04" :	$StrResult = $Day." April ".$Year;
						break;
			case "05" :	$StrResult = $Day." May ".$Year;
						break;
			case "06" :	$StrResult = $Day." June ".$Year;
						break;
			case "07" :	$StrResult = $Day." July ".$Year;
						break;
			case "08" :	$StrResult = $Day." August ".$Year;
						break;
			case "09" :	$StrResult = $Day." September ".$Year;
						break;
			case "10" :	$StrResult = $Day." October ".$Year;
						break;
			case "11" :	$StrResult = $Day." November ".$Year;
						break;
			case "12" :	$StrResult = $Day." December ".$Year;
						break;
		} //end switch
		return $StrResult;
	}
}
?>