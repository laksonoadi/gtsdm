<?php
class GetSetting extends Database {
   
   protected $mSqlFile = 'module/components/business/getsetting.sql.php';
   
   function __construct($connectionNumber = 0) {
      parent::__construct($connectionNumber);
   }
   
   function GetValueByKey($key) {
      $result = $this->Open($this->mSqlQueries['get_value_by_key'], array($key));
		if(isset($result[0]['value'])) {
			return $result[0]['value'];
		}
   }
   
}
?>