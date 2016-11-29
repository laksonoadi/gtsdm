<?php

class PeriodeCuti extends Database {

  protected $mSqlFile= 'module/ref_periode_cuti/business/periode_cuti.sql.php';
  
  function __construct($connectionNumber=0) {
    parent::__construct($connectionNumber);   
		//  
  }
  //==GET==      
  function GetData ($offset, $limit) {
    $result = $this->Open($this->mSqlQueries['get_data'], array($offset,$limit));
	//print_r($this->getLastError());exit;
    return $result;
  }
  
  function GetCount ($data) {
    $result = $this->Open($this->mSqlQueries['get_count'], array($data['awal'],$data['akhir']));
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
    $return = $this->Execute($this->mSqlQueries['do_add'], array($data['awal'],$data['akhir'],$data['total'],$data['status']));	  
	//print_r($this->getLastError());exit;
    return $return;
  }  
  
  function Update($data) {
    $return = $this->Execute($this->mSqlQueries['do_update'], array($data['perId'],$data['awal'],$data['akhir'],$data['total'],$data['status'],$data['id']));         		  
    //$x = sprintf($this->mSqlQueries['do_update'], $data['pegId'],$data['awal'],$data['akhir'],$data['total'],$data['status'],$data['id']);
    #print_r($x);exit;
    //$this->mdebug();  
	//print_r($this->getLastError());exit;
    return $return;
  }   
  
  function Delete($id) {
    $id = $id['idDelete'];
    $ret = $this->Execute($this->mSqlQueries['do_delete'], array($id));		
	//print_r($this->getLastError());exit;
    return $ret;
  }
  
  function GetTahun(){
		$year = date('Y')+5;
		#$year2 = date('Y')-2;
		$year2 = '2007';
		$no=0;
		for($i=$year;$i>$year2;$i--){
			$arrYear[$no]['id']=$i;
			$arrYear[$no]['name']=$i;
			$no++;
		}
		return $arrYear;
	}

	function GetBulan(){
		$bulan = array();
		$bulan[0]['id']='01';
		$bulan[0]['name']='Januari';
		$bulan[1]['id']='02';
		$bulan[1]['name']='Februari';
		$bulan[2]['id']='03';
		$bulan[2]['name']='Maret';
		$bulan[3]['id']='04';
		$bulan[3]['name']='April';
		$bulan[4]['id']='05';
		$bulan[4]['name']='Mei';
		$bulan[5]['id']='06';
		$bulan[5]['name']='Juni';
		$bulan[6]['id']='07';
		$bulan[6]['name']='Juli';
		$bulan[7]['id']='08';
		$bulan[7]['name']='Agustus';
		$bulan[8]['id']='09';
		$bulan[8]['name']='September';
		$bulan[9]['id']='10';
		$bulan[9]['name']='Oktober';
		$bulan[10]['id']='11';
		$bulan[10]['name']='Nopember';
		$bulan[11]['id']='12';
		$bulan[11]['name']='Desember';
		return $bulan;
	}
	
	function GetBulanEng(){
		$bulan = array();
		$bulan[0]['id']='01';
		$bulan[0]['name']='January';
		$bulan[1]['id']='02';
		$bulan[1]['name']='February';
		$bulan[2]['id']='03';
		$bulan[2]['name']='March';
		$bulan[3]['id']='04';
		$bulan[3]['name']='April';
		$bulan[4]['id']='05';
		$bulan[4]['name']='May';
		$bulan[5]['id']='06';
		$bulan[5]['name']='June';
		$bulan[6]['id']='07';
		$bulan[6]['name']='July';
		$bulan[7]['id']='08';
		$bulan[7]['name']='August';
		$bulan[8]['id']='09';
		$bulan[8]['name']='September';
		$bulan[9]['id']='10';
		$bulan[9]['name']='October';
		$bulan[10]['id']='11';
		$bulan[10]['name']='November';
		$bulan[11]['id']='12';
		$bulan[11]['name']='December';
		return $bulan;
	}
}
?>
