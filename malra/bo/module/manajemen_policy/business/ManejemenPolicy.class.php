<?php
class ManejemenPolicy extends Database {
   //protected $mSqlFile = 'module/policy/business/policy.sql.php';
   //protected $mDbConfig = array('db_namespace' => 'Policy');
   
   protected $mSqlFile= 'module/manajemen_policy/business/businesspolicy.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }
   
   function GetQueryKeren($sql,$params) {
      foreach ($params as $k => $v) {
        if (is_array($v)) {
          $params[$k] = '~~' . join("~~,~~", $v) . '~~';
          $params[$k] = str_replace('~~', '\'', addslashes($params[$k]));
        } else {
          $params[$k] = addslashes($params[$k]);
        }
      }
      $param_serialized = '~~' . join("~~,~~", $params) . '~~';
      $param_serialized = str_replace('~~', '\'', addslashes($param_serialized));
      eval('$sql_parsed = sprintf("' . $sql . '", ' . $param_serialized . ');');
      //echo $sql_parsed;
      return $sql_parsed;
   }

   /*function ListPolicy($offset,$limit) {
      $dateFormat = '%d-%m-%Y';
      //echo sprintf($this->mSqlQueries['list_policy'],$dateFormat,$offset,$limit);
      return $this->Open($this->mSqlQueries['list_policy'], array($offset,$limit));
   }*/
   
   function ListPolicy($nama_deskripsi='', $satuan_kerja='', $jenis_policy='',$offset, $limit) {
      $str = " WHERE 1=1 ";
	  if($nama_deskripsi != ""){
  		  $str .= " AND (policyNama LIKE '%".$nama_deskripsi."%' OR policyKeterangan LIKE '%".$nama_deskripsi."%')";
      }
      
      if($satuan_kerja != "all"){
  		  $str .= " AND satkerpolicySatkerId=".$satuan_kerja;
      }
      
      if($jenis_policy != "all"){
  		  $str .= " AND policyJnspolicyId=".$jenis_policy;
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['list_policy'], array($str, $offset, $limit));
      //print_r(stripslashes($result));
	  // var_dump($result);
      return $this->Open(stripslashes($result), array()); 
   }
   

   function ListPolicyAktif($offset,$limit){
      $dateFormat = '%d-%m-%Y';
      return $this->Open($this->mSqlQueries['list_policy_aktif'], array($offset,$limit));
   }
   
   function ListTypePolicy(){
      return $this->Open($this->mSqlQueries['list_type_policy'], array());
   }

   /*function CountPolicy(){
      $buff = $this->Open($this->mSqlQueries['count_policy'], array());
      return $buff[0]['NUMBER'];
   }*/
   
   function CountPolicy($nama_deskripsi='', $satuan_kerja='', $jenis_policy=''){
      if($nama_deksripsi != ""){
  		  $str = " AND (policyNama LIKE '%".$nama_deskripsi."%' OR policyKeterangan LIKE '%".$nama_deskripsi."%')";
      }else{
        $str = "";
      }
      
      if($satuan_kerja != "all"){
  		  $str .= " AND policySatkerpolicyId=".$satuan_kerja;
      }else{
        $str .= "";
      }
      
      if($jenis_policy != "all"){
  		  $str .= " AND policyJnspolicyId=".$jenis_policy;
      }else{
        $str .= "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['count_policy'], array($str));

  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      /*if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		} */
  		
  		return $res2[0]['NUMBER'];
   }

   function CountPolicyAktif(){
      $buff = $this->Open($this->mSqlQueries['count_policy_aktif'], array());
      return $buff[0]['NUMBER'];
   }
   
   function GetComboJenisPolicy(){
      $result = $this->Open($this->mSqlQueries['get_combo_jenis_policy'], array());
	  return $result;
   }
   
   function GetComboSatuanKerja(){
      $result = $this->Open($this->mSqlQueries['get_combo_satuan_kerja'], array());
	  return $result;
   }
   
   function GetPolicyById($id){
      return $this->Open($this->mSqlQueries['get_policy_by_id'], array($id));
   }
   
   function GetTypePolicyById($id){
      return $this->Open($this->mSqlQueries['get_type_policy_by_id'], array($id));
   }
   
   function GetFilePolicyByPolicyId($id){
      return $this->Open($this->mSqlQueries['get_file_policy_by_policy_id'], array($id));
   }
   
   function GetFilePolicyById($id){
      return $this->Open($this->mSqlQueries['get_file_policy_by_id'], array($id));
   }
   
   function GetComboTipe(){
      return $this->Open($this->mSqlQueries['get_combo_tipe'], array());
   }
   
   function GetComboSatkerPolicy(){
      return $this->Open($this->mSqlQueries['get_combo_satker_policy'], array());
   }

   function AddPolicy($array){
   //echo vsprintf($this->mSqlQueries['add_policy'],$array);
      return $this->Execute($this->mSqlQueries['add_policy'],$array);
   }
   
   function UpdatePolicy($array){
      return $this->Execute($this->mSqlQueries['update_policy'],$array);
   }
   
   function DeletePolicy($id){
	//echo sprintf($this->mSqlQueries['delete_policy'],$id);exit;
      return $this->Execute($this->mSqlQueries['delete_policy'],array($id));
   }
   
   //Policy Department
   
   function ListSatkerPolicy($offset,$limit) {
      $dateFormat = '%d-%m-%Y';
      //echo sprintf($this->mSqlQueries['list_policy'],$dateFormat,$offset,$limit);
      return $this->Open($this->mSqlQueries['list_satker_policy'], array($offset,$limit));
   }
   
   function CountSatkerPolicy(){
      $buff = $this->Open($this->mSqlQueries['count_satker_policy'], array());
      return $buff[0]['NUMBER'];
   }
   
   function GetSatkerPolicyById($id){
      return $this->Open($this->mSqlQueries['get_satker_policy_by_id'], array($id));
   }
   
   function GetComboSatker(){
      return $this->Open($this->mSqlQueries['get_combo_satker'], array());
   }
   
   function AddSatkerPolicy($array){
      return $this->Execute($this->mSqlQueries['add_satker_policy'],$array);      
   }
   
   function UpdateSatkerPolicy($array){
      return $this->Execute($this->mSqlQueries['update_satker_policy'],$array);
   }
   
   function DeleteSatkerPolicy($id){
      return $this->Execute($this->mSqlQueries['delete_satker_policy'],array($id));
   }
   
   //Policy Type
   
   function AddType($nama){
      return $this->Execute($this->mSqlQueries['add_type'],array($nama));
   }
   
   function UpdateType($nama,$id){
      return $this->Execute($this->mSqlQueries['update_type'],array($nama,$id));
   }
   
   function DeleteType($id){
      return $this->Execute($this->mSqlQueries['delete_type'],array($id));
   }
   
   //Policy Files
   
   function AddFilePolicy($array){
      return $this->Execute($this->mSqlQueries['add_file_policy'],$array);
   }
   
   function UpdateFilePolicy($array){
      return $this->Execute($this->mSqlQueries['update_file_policy'],$array);
   }
   
   function UpdateStatusFilePolicy($status,$id){
      return $this->Execute($this->mSqlQueries['update_status_file_policy'],array($status,$id));
   }
   
   function UpdateIsDownloadFilePolicy($is_download,$id){
      return $this->Execute($this->mSqlQueries['update_is_download_file_policy'],array($is_download,$id));
   }
   
   function DeleteFilePolicy($id){
      return $this->Execute($this->mSqlQueries['delete_file_policy'],array($id));
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
			case "02" :	$StrResult = $Day." Febuary ".$Year;
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