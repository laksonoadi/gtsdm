<?php

class Penghasilan extends Database {

   protected $mSqlFile= 'module/penghasilan/business/penghasilan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);     
   }
//==GET==      
   function GetData ($offset, $limit, $data) { 
     
      $arg='';
	  $params = array("%$data%");
	  if($limit !== NULL AND $offset !== NULL){
	     $arg.='LIMIT %s,%s';
		 array_push($params,$offset,$limit); 
	  }
      $result = $this->Open($this->mSqlQueries['get_data'].$arg,$params);
      return $result;
   }

   function GetCount ($data) {
     $result = $this->Open($this->mSqlQueries['get_count'], array("%$data%"));  
     if (!$result)
       return 0;
     else
       return $result[0]['total'];    
   }
   
   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }  
   
   function Add($data) {
      //echo($this->mSqlQueries['do_add']);print_r(array($data['nama'],$data['userId']));exit();   
      $return = $this->Execute($this->mSqlQueries['do_add'], array($data['nama'],$data['userId'])); 
      return $return;
   }  
	
   function Update($data) {
     $return = $this->Execute($this->mSqlQueries['do_update'], array($data['nama'],$data['userId'], $data['id']));         		  
		//$this->mdebug();  
	if (!$return) return (bool) $result; 
	$return = $this->ChangeOrder($data['id'], $data['order']);
      return $return;
   }   
	
	function Delete($id) {
      $id = $id['idDelete'];
	  $urutan = end($this->GetDataById($id));
	  $order=$urutan['phslUrutan'];
	   $return = $this->Execute($this->mSqlQueries['do_delete'], array($id));
	   if (!$return) return (bool) $result; 
		$this->Execute($this->mSqlQueries['update_penghasilan_order_delete'], array($order));
       return TRUE;
	}
	
	function MoveOrder($id, $move){
	   $result = $this->GetData();
	   if (!$result) return FALSE;
	   if ($move == 'up' AND $result[0]['phslId'] == $id) return FALSE;
	   $result = end($result);
	   if ($move == 'down' AND $result['phslId'] == $id) return FALSE;
	   $result = $this->GetDataById($id);
	   $Order = $result['phslUrutan'];
	   if ($move == 'up'){ 
	     $Order--;
		}
      else 
	  { 
	    $Order++;
	  }
	  //print_r($Order);exit();
	  return $this->ChangeOrder($id, $Order);
	}
	
	function ChangeOrder ($id, $Order){
	   $result = $this->GetData(); 
	   $result = end($result);
	   if (1>$Order OR $Order>$result['phslUrutan']) return FALSE;
	   $result = $this->GetDataById($id);//print_r($result);exit();
	   if ($result['phslUrutan'] == $Order) return TRUE;
	   $oldOrder = $result['phslUrutan'];
	   if ($oldOrder > $Order){
         $inc = 1;
         $start = $Order;
         $end = $oldOrder;
      }
      else{
         $inc = -1;
         $start = $oldOrder;
         $end = $Order;
      }
	  $this->Execute($this->mSqlQueries['mass_update_penghasilan_order'], array($inc, $start, $end));
      $this->Execute($this->mSqlQueries['update_penghasilan_order'], array($Order, $id));
      return TRUE;
	}
}
?>
