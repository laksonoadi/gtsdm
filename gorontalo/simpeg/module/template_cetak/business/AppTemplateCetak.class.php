<?php
class AppTemplateCetak extends Database{

	protected $mSqlFile= 'module/template_cetak/business/apptemplatecetak.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		
	}
   
   function GetCountDataTemplate(){
      $result = $this->Open($this->mSqlQueries['get_count_data_template'], array());
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
   }
   
   function GetTemplateCetak(){
      $result =$this->Open($this->mSqlQueries['get_data_template'], array());
      return $result;
   }
   
   function GetTemplateCetakById($idTemplate){
      $result=$this->Open($this->mSqlQueries['get_data_template_by_id'],array($idTemplate));
      return $result;
   }
   
   function GetVariableCetak(){
      $result=$this->Open($this->mSqlQueries['get_variable_cetak'],array());
      return $result;
   }
   
   //do
   
   function DoAddTemplate($templateNama, $templatePath){
      $result=$this->Execute($this->mSqlQueries['do_add_template'],array($templateNama,$templatePath));
      return $result;
   }
   function DoUpdateStatusTemplate($status,$idTemplate){
   
      if($status=="Aktif"){
         $statusUpdate="Tidak";
      }else{
         $statusUpdate="Ya";
      }

      $result=$this->Execute($this->mSqlQueries['do_update_status'],array($statusUpdate,$idTemplate));
      return $result;
   }
   
   function DoUpdateTemplate($templateNama, $templatePath,$idTemplate){
      $result=$this->Execute($this->mSqlQueries['do_update_template'],array($templateNama, $templatePath,$idTemplate));
      return $result;
   }
   
   function DoDeleteTemplateById($idTemplate){
      $result=$this->Execute($this->mSqlQueries['do_delete_template_by_id'],array($idTemplate));
      return $result;
   }
   
   function DoDeleteTemplateByArrayId($arrId){
      $idTemplate=implode("', '",$arrId);
      $result=$this->Execute($this->mSqlQueries['do_delete_template_by_array_id'],array($idTemplate));
      return $result;
   }
}
?>
