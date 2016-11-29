<?php
class Policy extends Database {
   protected $mSqlFile = 'module/policy/business/policy.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //  
   }
   
   function ListPolicyTerbaru() {
      $result = $this->Open($this->mSqlQueries['list_policy_terbaru'], array());
	     return $result[0];
   }
   
   function ListBeberapaPolicy() {
      $result = $this->Open($this->mSqlQueries['list_beberapa_policy'], array());
	     return $result;
   }
   
   function ListBeberapaPolicy2() {
      $result = $this->Open($this->mSqlQueries['list_beberapa_policy2'], array());
	     return $result;
   }
   
   function ListPolicy($satkerId,$jnspolicyId) {
      $result = $this->Open($this->mSqlQueries['list_policy'], array($satkerId,$jnspolicyId));
	     return $result;
   }
   
   function ListPolicyFile($policyId) {
      $result = $this->Open($this->mSqlQueries['list_policy_file'], array($policyId));
      //print_r($this->getLastError());
	     return $result;
   }
   
   function ListSatuanKerjaPolicy() {
      $result = $this->Open($this->mSqlQueries['list_satuan_kerja_policy'], array());
	     return $result;
   }
   
   function ListJenisPolicy($satkerId) {
      $result = $this->Open($this->mSqlQueries['list_jenis_policy'], array($satkerId));
	     return $result;
   }
   
   function GetPolicyById($id) {
      $result = $this->Open($this->mSqlQueries['get_policy_by_id'], array($id));
	     return $result[0];
   }
   
   function GetSatuanKerjaPolicyById($id) {
      $result = $this->Open($this->mSqlQueries['get_satuan_kerja_policy_by_id'], array($id));
	     return $result[0];
   }
   
   function GetJenisPolicyById($id) {
      $result = $this->Open($this->mSqlQueries['get_jenis_policy_by_id'], array($id));
	     return $result[0];
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
   
   function ListNewestFile($num = 5) {
      $result = $this->Open($this->mSqlQueries['list_newest_file'], array($num));
      return $result;
   }
}
?>