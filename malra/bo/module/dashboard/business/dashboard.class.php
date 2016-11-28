<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/verifikasi_data/business/verifikasi_data.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class dashboard extends Database {

    protected $mSqlFile= 'module/dashboard/business/dashboard.sql.php';
   
    function __construct($connectionNumber=0){
        parent::__construct($connectionNumber);
    }
   
    function GetQueryKeren($sql,$params){
        foreach ($params as $k => $v){
            if (is_array($v)){
                $params[$k] = '~~'.join("~~,~~", $v).'~~';
                $params[$k] = str_replace('~~', '\'', addslashes($params[$k]));
            }
            else{
                $params[$k] = addslashes($params[$k]);
            }
        }
        $param_serialized = '~~'.join("~~,~~", $params).'~~';
        $param_serialized = str_replace('~~', '\'', addslashes($param_serialized));
        eval('$sql_parsed = sprintf("'.$sql.'", '.$param_serialized . ');');
        //echo $sql_parsed;
        return $sql_parsed;
    }
   
    function GetCountPegawaiByUserId(){
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      // print_r($result);
      }
      if($result){
     $satker = $result[0]['satkerId'];
     // $satlev = $result[0]['satkerLevel'];
     $unitgrup = '';
     foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      // $uGroupunit = str_replace(",","','",$unitgrup);
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit."'0'";

      } else {
      $satker = 0;
      // $satlev = 0; 
      // $uGroupList = 0;
      $resultunitgroup = 0;
      }

        $result = $this->Open($this->mSqlQueries['get_count_pegawai_by_user_id'], array($satker));
        $res3 = $result[0]['total']; 
        if(!$result){
          return 0;
      }
      else{
        return $res3;
      }  
    }

    function GetCountAllPegawai(){
      $result = $this->Open($this->mSqlQueries['get_count_all_pegawai'],array());
      if($result)
        $res = $result[0]['total'];
      else
        $res = 0;

      return $res;
    }

    function GetCountJabatanPegawai(){
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      // print_r($result);
      }
      if($result){
     $satker = $result[0]['satkerId'];
     // $satlev = $result[0]['satkerLevel'];
     $unitgrup = '';
     foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';

      } else {
      $satker = 0;
      // $satlev = 0; 
      // $uGroupList = 0;
      $resultunitgroup = 0;
      }

        $result = $this->Open($this->mSqlQueries['count_all_jabatan_pegawai'], array($satker));
        // $result = $this->Open($this->mSqlQueries['count_all_jabatan_pegawai'], array($resultunitgroup));
        // echo "<pre>";
        // echo mysql_error();
        // print_r($result);
        // echo "</pre>";
        $res3 = $result[0]['total']; 
        if(!$result){
          return 0;
      }
      else{
        return $res3;
      }  
    }

    function GetCountUnJabatanPegawai(){
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      // print_r($result);
      }
      if($result){
     $satker = $result[0]['satkerId'];
     // $satlev = $result[0]['satkerLevel'];
     $unitgrup = '';
     foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';

      } else {
      // $satker = 0;
      // $satlev = 0; 
      // $uGroupList = 0;
      $resultunitgroup = 0;
      }

        $result = $this->Open($this->mSqlQueries['count_all_jabatan_pegawai_kosong'], array($resultunitgroup));
        // echo "<pre>";
        // echo mysql_error();
        // print_r($result);
        // echo "</pre>";
        $res3 = $result[0]['total']; 
        if(!$result){
          return 0;
      }
      else{
        return $res3;
      }  
    }

    

    function GetJenisUserPegawai($offset, $limit, $nip_nama='',$status=''){ 
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
      foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';
      
      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      } 
      // echo $status;
      if($status != 'all'){
      $status_kerja = 'AND a.pegStatrId =\''.$status.'\'';
      }else {
      $status_kerja = '1=1';
      }

      $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_user_id_verified'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$resultunitgroup,$satker,$status_kerja, (int)$offset, (int)$limit));

      $error = mysql_error();
      //         echo "<pre>";
      //          echo $this->debug_mode();
      //         print_r($error);
      //         print_r($result);
      //         echo "</pre>";
      // exit(); 
      if (!empty($error)){
        $result = $this->GetData($offset, $limit, $nip_nama,$status);
      }
      return $result;
    }


    function GetListJenisPegawai(){
      $result = $this->Open($this->mSqlQueries['get_list_jenis_pegawai_data'], array());
      return $result;
   }


    function GetListPegawaiMasukPertahun(){

      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
      foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';
      
      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      } 

      $result = $this->Open($this->mSqlQueries['get_count_pegawai_masuk_pertahun'], array($satker, $satlev));
      return $result;
   }

   

   function GetCountJenisPegawaiTotal($jenis_pegawai){

     $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
      foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';
      
      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      } 

      $result = $this->Open($this->mSqlQueries['get_jenis_pegawai_data'], array($satker,$satlev,$jenis_pegawai));

      return $result;

   }

    function GetCountPegawaiPensiun($nip_nama='',$status=''){
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
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';

      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      }

      if($status != 'all'){
     $status_kerja = "AND a.pegStatrId =$status";
      }else {
      $status_kerja = "1=1";
      }

         $result = $this->Open($this->mSqlQueries['get_count_pegawai_pensiun'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satker,$satlev,$status_kerja));
        $res3 = sizeof($result); 
        if(!$res3){
          return 0;
      }
      else{
        return $res3;
      }  
    }


function GetCountPejabatFungsionalTotal($jenis_pegawai = null){

     $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
      foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';
      
      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      } 
      // // echo $status;
      // if($status != 'all'){
      // $status_kerja = 'AND a.pegStatrId =\''.$status.'\'';
      // }else {
      // $status_kerja = '1=1';
      // }
     

        // var_dump(vsprintf($this->mSqlQueries['get_count_pegawai_fungsional'], array($satker)));
      $result = $this->Open($this->mSqlQueries['get_count_pegawai_fungsional'], array($satker));

      return $result;
      if(!empty($result)){
        return $result['0'];
      }else{
        return $result;
      }

   }

   function GetListPegawaiVerifikasi() { 
    if (!$this->HaveAccess('verifikasi_data')) return array();
    $Verifikasi = new VerifikasiData();
    
    $data = $Verifikasi->GetComboJenisData();
    for ($i=0; $i<sizeof($data); $i++){
      $result[$i]['jenisdata'] = $data[$i]['id'];
      $result[$i]['judul'] = $data[$i]['name'];
      $result[$i]['jumlah'] = $Verifikasi->GetCountDataNotifikasi('',1,$data[$i]['id']);
      if ($result[$i]['jumlah']>0) {
        $temp[]=$result[$i];
      }
    }
    $result=$temp;
    return $result;   
  }

  function GetCountDataPensiun($awal, $akhir, $unit_kerja, $pangkat_golongan) {

      $sql=$this->mSqlQueries['get_data_pensiun']; 
      $this->Obj = new SatuanKerja();

      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }

      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
      foreach ($result as $key => $value) {
        $unitgrup .= "'".$value['satkerId']."',";
      }
      $uGroupunit = $unitgrup;
        $resultunitgroup = $uGroupunit.'0';
      
      } else {
      $satker = 0;
      $satlev = 0; 
      $uGroupList = 0;
      }

      // $list=$this->Obj->GetListIdAnakUnitByUnitId($unit_kerja);
      // print_r($unitgrup);

      // if($unit_kerja != "all"){
        // $sql=str_replace('%unit_kerja%',' AND satkerpegSatkerId IN ('.$unitgrup.'0)' ,$sql);
        $sql=str_replace('%unit_kerja%','' ,$sql);
      // }else{
      //   $sql=str_replace('%unit_kerja%','',$sql);
      // }
      
      if($pangkat_golongan != "all"){
        $sql=str_replace('%pangkat_golongan%'," AND pktgolPktgolrId='".$pangkat_golongan."'",$sql);
      }else{
        $sql=str_replace('%pangkat_golongan%','',$sql);
      }
      
      $sql=str_replace('%limit%','',$sql);
      $result=$this->Open($sql, array($satker));
      // echo "<pre>";
      // echo $sql;
      // echo "</pre>";
      return sizeof($result);
   }


function isKabisat($thn) {
      // jika tahun habis dibagi 4, maka tahun kabisat
      if (($thn % 4) != 0) {
        return false;
      } // jika tidak habis dibagi 4, maka jika habis dibagi 100 dan 400 maka tahun kabisat
      else if ((($thn % 100) == 0) && (($thn % 400) != 0)) {
        return false;
      }
      else {
        return true;
      }
    }

   // mendapatkan tanggal terakhir dari sutau bulan
  function getLastDate($tahun,$bulan){
      $kabisat = $this->isKabisat($tahun);
      if ($kabisat == true)
         $febLastDate = 29;
      else
         $febLastDate = 28;
      
      if (($bulan=='1')) $bln=0;
      if (($bulan=='2')) $bln=1;
      if (($bulan=='3')) $bln=2;
      if (($bulan=='4')) $bln=3;
      if (($bulan=='5')) $bln=4;
      if (($bulan=='6')) $bln=5;
      if (($bulan=='7')) $bln=6;
      if (($bulan=='8')) $bln=7;
      if (($bulan=='9')) $bln=8;
      if (($bulan=='10')) $bln=9;
      if (($bulan=='11')) $bln=10;
      if (($bulan=='12')) $bln=11;
      
      $arrLastDate = array(31,$febLastDate,31,30,31,30,31,31,30,31,30,31);
      for ($i=0;$i<12;$i++){
         if ($i == $bln)  
            //$lastDate =  $tahun.'-'.$bulan.'-'.$arrLastDate[$i];
            $lastDate =  $arrLastDate[$i];
      }
      return $lastDate;
   }
   

}
?>
