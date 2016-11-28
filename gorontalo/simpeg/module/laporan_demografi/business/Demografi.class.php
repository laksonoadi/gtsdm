<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Demografi extends Database {

  protected $mSqlFile= 'module/laporan_demografi/business/demografi.sql.php';
  
  function __construct($connectionNumber=0) {
    parent::__construct($connectionNumber);     
  }
  
  function GetListUnitKerja() { 
    //$result = $this->Open($this->mSqlQueries['get_list_unit_kerja'], array());
	$this->Obj = new SatuanKerja();
	$result = $this->Obj->GetSatuanKerjaByUserId();
    return $result;
  }
  
  ////STATUS...............................................
  function GetList($val) { 

      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      // print_r($result);
      }
      if($result){
     $satker = $result[0]['satkerId'];
     $satlev = $result[0]['satkerLevel'];
     $unitgrup = '';
     foreach ($result as $key => $value) {
        $unitgrup .= $value['satkerId'].',';
      }
      $uGroupunit = str_replace(",","','",$unitgrup);
        $resultunitgroup = $uGroupunit.'0';

      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      }

      if($status != 'all'){
     $status_kerja = "AND l.statrId =$status ";
      }else {
      $status_kerja = "AND 1=1";
      }

    $result = $this->Open($this->mSqlQueries['get_list_'.$val], array($resultunitgroup));
    return $result;
  }
  
  function GetJumlah($val,$unit,$status) { 
    if (($val=='umur')||($val=='lama_kerja')){
      $result = $this->Open($this->mSqlQueries['get_jumlah_'.$val], array($unit,$status[0],$status[1]));
    }else{
      $result = $this->Open($this->mSqlQueries['get_jumlah_'.$val], array($unit,$status[0]));
    }
    
    return $result[0]['jumlah'];
  }

  function GetComboUnitKerjaLike($combo=false){
    $sql=$this->mSqlQueries['get_combo_unit_kerja_like'];
    
    $filter='';
    if(($this->filter['unit'] != "all")&&($this->filter['unit']!='')){
      // print_r($this->filter['unit']);
      $filter .= $this->query_filter['unit'];
    }else{
    $filter .='AND (`satkerLevel` LIKE CONCAT(%s, ".%%") OR `satkerId` = %s)';  
    }
    $sql=str_replace('%filter%',$filter,$sql);

    $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
        if(!empty($userId)){
        $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
        // print_r($result);
        }
        if($result){
       $satker = $result[0]['satkerId'];
       $satlev = $result[0]['satkerLevel'];
       $unitgrup = '';
       foreach ($result as $key => $value) {
          $unitgrup .= $value['satkerId'].',';
        }
        $uGroupunit = str_replace(",","','",$unitgrup);
          $resultunitgroup = $uGroupunit.'0';

        } else {
        $satker = 0;
        $satlev = 0; 
        $uGroupList = 0;
        }

        if($status != 'all'){
       $status_kerja = "AND l.statrId =$status ";
        }else {
        $status_kerja = "AND 1=1";
        }
    $satkerlevel = $result['0']['satkerLevel'];
    $result=$this->Open($sql,array($satkerlevel, $satker));
    // print_r($sql);
    // print_r($resultunitgroup);
    if(($this->filter['unit'] == "all")&&($combo==false)){
      $i=sizeof($result);
      $result[$i]['id']=99999;
      $result[$i]['name']='Belum Diset';
    }
    
    return $result;
  }
}
?>
