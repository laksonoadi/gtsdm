<?php

class DataPegawai extends Database {

    protected $mSqlFile= 'module/data_pegawai/business/data_pegawai.sql.php';
    
    var $user;
    
    function __construct($connectionNumber=0){
        parent::__construct($connectionNumber);
        
        $this->user = Security::Authentication()->GetCurrentUser()->GetUserId();
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
        echo $sql_parsed;
        return $sql_parsed;
    }
   
    function GetCountPegawaiByUserId($nip_nama='',$status=''){
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
     $status_kerja = "AND l.statrId =$status";
      }else {
      $status_kerja = "AND 1=1";
      }
      // $satkerlevel = $result['0']['satkerLevel'].'%';
      $satkerlevel = $result['0']['satkerLevel'];
        // print_r($result['0']['satkerLevel']);
        $query = $this->mSqlQueries['get_count_pegawai_by_user_id'];
        $query = str_replace('--status--', $status_kerja, $query);
        
        // Temporary fix (user)
        if(!in_array($this->user, array(60, 61))) {
            $query = str_replace('--user--', ' AND a.pegUserId = '. $this->user, $query);
        } else {
            $query = str_replace('--user--', '', $query);
        }

        // print_r($query);
        // $result = $this->Open(stripslashes($query), array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satkerlevel, $satker));
        $result = $this->Open(stripslashes($query), array('%'.$nip_nama.'%','%'.$nip_nama.'%'));
        $result = count($result);
        // print_r($result);

         // $result = $this->Open($this->mSqlQueries['get_count_pegawai_by_user_id'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$resultunitgroup,$satker,$status_kerja));
        // print_r($result);
        // $res3 = sizeof($result); 
        
        if(!$result){
          return 0;
      }
      else{
        return $result;
      }  

    }
   
    function GetDataPegawaiByUserId($offset, $limit, $nip_nama='',$status=''){ 
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
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
      // echo $status;
      if($status != 'all'){
      $status_kerja = 'AND l.statrId =\''.$status.'\'';
      }else {
      $status_kerja = 'AND 1=1';
      }
      // $satkerlevel = $result['0']['satkerLevel'].'%';
      $satkerlevel = $result['0']['satkerLevel'];

      // $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_user_id'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$resultunitgroup,$satker,$status_kerja, (int)$offset, (int)$limit));

        $query = $this->mSqlQueries['get_data_pegawai_by_user_id'];
        $query = str_replace('--status--', $status_kerja, $query);
      
      // Temporary fix (user)
      if(!in_array($this->user, array(60, 61))) {
          $query = str_replace('--user--', ' AND a.pegUserId = '. $this->user, $query);
      } else {
          $query = str_replace('--user--', '', $query);
      }

        // $result = $this->Open(stripslashes($query), array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satkerlevel, $satker, (int)$offset, (int)$limit));
        $result = $this->Open(stripslashes($query), array('%'.$nip_nama.'%','%'.$nip_nama.'%', (int)$offset, (int)$limit));
        

      $error = mysql_error();
      //         echo "<pre>";
      //          echo $this->debug_mode();
      //         print_r($error);
      //         print_r($result);
      //         echo "</pre>";
      // exit(); 
      // if (!empty($error)){
      //   $result = $this->GetData($status, $offset, $limit);
      // }
      return $result;
    }
   
    function GetDataPegawaiByUserIdCetak ($nip_nama='',$status=''){ 
        $userId= Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
        $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_user_id_cetak'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$userId,$userId,$status,$status));
      return $result;    
    }
   
    function IsSupervisor($idPeg){
        $result = $this->Open($this->mSqlQueries['is_supervisor'], array($idPeg));
        if (empty($result)) {
            return false;
        }
      return true;  
    }
   
    function GetComboPegawaiBawahan($id){
        $result = $this->Open($this->mSqlQueries['get_combo_pegawai_bawahan'], array($id));
      return $result;
    }
   
    function GetUserIdByUserName(){      
        $result = $this->Open($this->mSqlQueries['get_user_id_by_username'], array($_SESSION['username'])); 
      if($result)
          return $result[0]['userId'];
      else
          return $result;   
    }
  
    function GetPegIdByUserName(){      
        $result = $this->Open($this->mSqlQueries['get_peg_id_by_username'], array($_SESSION['username'])); 
      if($result)
          return $result[0]['pegId'];
      else
          return $result;
    }
   
    function GetDataPegawaiByUserName(){      
        $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_username'], array($_SESSION['username'])); 
      return $result[0];
    }
   
   function GetDataPegawaiDetailById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_pegawai_detail_by_id'], array($id)); 
      return $result[0];
   }

   function GetDataPegawaiDetailSPT($id) {      
      $result = $this->Open($this->mSqlQueries['get_spt_pegawai'], array($id)); 
      return $result[0];
   }
   
   
   function GetDataPegawaiDetailByUserId($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_pegawai_detail_by_user_id'], array($id)); 
      return $result[0];
   }
   
   function GetEmailById($id) {      
      $result = $this->Open($this->mSqlQueries['get_email_by_id'], array($id)); 
      return $result[0]['email'];
   }
   
   function GetEmailByNip($nip) {      
      $result = $this->Open($this->mSqlQueries['get_email_by_nip'], array($nip)); 
      return $result[0]['email'];
   }
  
//==GET== 
   function GetCountData($nip_nama='', $status_kerja = '') {
      /*if($nip_nama != ""){
        $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }*/
      
      if(($nip_nama != "") and ($status_kerja != "all")){
        $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND
        pegStatrId = '".$status_kerja."' "; 
      }elseif(($nip_nama != "") and ($status_kerja == "all")){
        $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') ";
        //AND d.satkerpegSatkerId IS NULL";
      }elseif(($nip_nama == "") and ($status_kerja != "all")){
        $str = " AND pegStatrId = '".$status_kerja."' ";
      }else{
        $str = "";
      }
      
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
        if(!empty($userId)){
            $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
        }
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
      $query = $this->mSqlQueries['get_count'];
      
      // Temporary fix (user)
      if(!in_array($this->user, array(60, 61))) {
          $query = str_replace('--user--', ' AND pegUserId = '. $this->user, $query);
      } else {
          $query = str_replace('--user--', '', $query);
      }
      
      // $result = $this->GetQueryKeren($query, array($str, $satker, $satkerlevel));
      $result = $this->GetQueryKeren($query, array($str));
      $res2 = $this->Open(stripslashes($result), array());
      $res3 = sizeof($res2); 
      
      if (!$res3) {
        return 0;
      } else {
        return $res3;
      }  
   } 
   
   function GetData ($offset, $limit, $nip_nama='',$status_kerja='') { 
      /*if($nip_nama != ""){
        $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }*/
       
      if(($nip_nama != "") and ($status_kerja != "all")){
        $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%') AND
        pegStatrId = '".$status_kerja."'"; 
      }elseif(($nip_nama != "") and ($status_kerja == "all")){
        $str = " AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
        //AND d.satkerpegSatkerId IS NULL";
      }elseif(($nip_nama == "") and ($status_kerja != "all")){
        $str = " AND pegStatrId = '".$status_kerja."'";
      }else{
        $str = "";
      }
      
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
        if(!empty($userId)){
            $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
        }
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
        
      $query = $this->mSqlQueries['get_data'];
      
      // Temporary fix (user)
      if(!in_array($this->user, array(60, 61))) {
          $query = str_replace('--user--', ' AND pegUserId = '. $this->user, $query);
      } else {
          $query = str_replace('--user--', '', $query);
      }
      // $result = $this->GetQueryKeren($query, array($str, $satker, $satkerlevel, $offset, $limit));
      $result = $this->GetQueryKeren($query, array($str, $offset, $limit));

      return $this->Open(stripslashes($result), array());    
   } 

   function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;    
   }
   
   function GetDataId($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_id'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;    
   }
   
   function GetIdStruk($id) {      
      $result = $this->Open($this->mSqlQueries['get_id_struk'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;    
   }
   
   function GetIdFung($id) {      
      $result = $this->Open($this->mSqlQueries['get_id_fung'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;    
   }
   
   function GetKodeNikah($id) {      
      $result = $this->Open($this->mSqlQueries['get_kode_nikah'], array($id)); 
      return $result;   
   } 
   
   function GetDatPegDetail($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetStatusKerja($id){
      $result = $this->Open($this->mSqlQueries['get_status_kerja_id'], array($id));
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail2($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail2'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail3($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail3'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail4($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail4'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail5($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail5'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail6($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail6'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }
   
   function GetDatPegDetail7($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_detail7'], array($id)); 
    if($result)
       return $result['0'];
    else
       return $result;
   }
   
   function GetDatPegBahasa($id){
      $result = $this->Open($this->mSqlQueries['get_datpeg_bahasa'], array($id)); 
    return $result;
   }
   
   function getNip($id){
      $result = $this->Open($this->mSqlQueries['get_nip'], array($id)); 
      return $result;
   }
   
   function GetComboAgama(){
      $result = $this->Open($this->mSqlQueries['get_combo_agama'], array());
    return $result;
   }
   
   function GetComboNikah(){
      $result = $this->Open($this->mSqlQueries['get_combo_nikah'], array());
    return $result;
   }
   
   function GetComboGoldar(){
      $result = $this->Open($this->mSqlQueries['get_combo_goldar'], array());
    return $result;
   }
   
   function GetComboSatwil(){
      $result = $this->Open($this->mSqlQueries['get_combo_satwil'], array());
    return $result;
   }

   function GetComboSatwilKota(){
      $result = $this->Open($this->mSqlQueries['get_combo_satwil_kota'], array());
    return $result;
   }
   
   
   function GetComboJenisPeg(){
      $result = $this->Open($this->mSqlQueries['get_combo_jenispeg'], array());
    return $result;
   }
   
   function GetComboStatPeg(){
      $result = $this->Open($this->mSqlQueries['get_combo_statpeg'], array());
    return $result;
   }
   
   function GetComboLevel(){
      $result = $this->Open($this->mSqlQueries['get_combo_level'], array());
    return $result;
   }
   
   function GetComboPnsCpns(){
      $result = $this->Open($this->mSqlQueries['get_combo_pnscpns'], array());
    return $result;
   }
   
   function GetPegGol($id){
      $result = $this->Open($this->mSqlQueries['get_peg_gol'], array($id)); 
      if($result)
       return $result[0];
      else
       return $result;
   }
   
   function GetPegStruk($id){
      $result = $this->Open($this->mSqlQueries['get_peg_struk'], array($id)); 
      if($result)
       return $result[0];
      else
       return $result;
   }
   
   function GetPegFung($id){
      $result = $this->Open($this->mSqlQueries['get_peg_fung'], array($id)); 
      if($result)
       return $result[0];
      else
       return $result;
   }
   
   function GetPegKer($id){
      $result = $this->Open($this->mSqlQueries['get_peg_ker'], array($id)); 
      if($result)
       return $result[0];
      else
       return $result;
   }
   
   function GetPegNikah($id){
      $result = $this->Open($this->mSqlQueries['get_peg_nikah'], array($id)); 
      if($result)
       return $result[0];
      else
       return $result;
   }
   
   function GetComboGol(){
      $result = $this->Open($this->mSqlQueries['get_combo_gol'], array());
      return $result;
   }
   
   function GetComboStruk(){
      $result = $this->Open($this->mSqlQueries['get_combo_struk'], array());
      return $result;
   }
   
   function GetComboFung(){
      $result = $this->Open($this->mSqlQueries['get_combo_fung'], array());
      return $result;
   }
   
   function GetComboKer(){
      $result = $this->Open($this->mSqlQueries['get_combo_ker'], array());
      return $result;
   }
   
   function GetComboKodeNikah(){
      $result = $this->Open($this->mSqlQueries['get_combo_kode_nikah'], array());
      return $result;
   }
   
   function GetComboBahasa(){
      $result = $this->Open($this->mSqlQueries['get_combo_bahasa'], array());
      return $result;
   }
   
   function GetDataAtas($id){
      $result = $this->Open($this->mSqlQueries['get_data_atas'], array($id)); 
    if($result)
       return $result[0];
      else
       return $result;
   }
   
   //----------------DO----------------
   function Add($data) {   
      $params=array($data['nip'],$data['kodeGateAccess'],$data['kodeInter'],$data['kodeLain'],
    $data['nama'],$data['gelDep'],$data['gelBel'],$data['tmpLahir'],$data['tglLahir'],$data['noAkta'],$data['akta'],
    $data['idLain'],$data['jenKel'],$data['agama'],$data['statNikah'],
    $data['alamat'],$data['kodePos'],$data['noTelp'],$data['noHp'],
    $data['email'],$data['golDar'],$data['tinggiBdn'],$data['beratBdn'],
    $data['cacat'],$data['hobi'],$data['tglmasuk'],$data['pnstmt'],$data['cpnstmt'],
      $data['notaspen'],$data['noaskes'],$data['statusnpwp'],$data['nonpwp'],$data['tglnpwp'],$data['usiapens'],
    $data['kodeabsen'],$data['jnsidlain'],$data['jnspeg'],$data['statpeg'],
    $data['satwilpeg'],$data['datpegAsalDesa'],$data['datpegAsalKecamatan'],$data['asalsatwil'],
    $data['pegLevelId'],$data['pegStatusWargaNeg'],$data['foto'],$data['userId'],
    $data['idSkck'],$data['kir'],$data['tglkir'],$data['noskbn'],$data['tglskbn'],$data['noskkb'],$data['tglskkb'],
    $data['kelurahan'],$data['kecamatan'],$data['kepemilikanRumah']
    ,$data['pegNoKarpeg'],$data['pegNoKpe'],$data['PegJenFungsional']
    );
      
      $return = $this->Execute($this->mSqlQueries['do_add'],$params);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddDatGaji($id1,$kodenik,$satker,$gol,$struk,$fung,$data,$op,$p,$p2,$idStruk,$idFung,$dateNow,$katpeg,$tippeg,$atas1,$atas2,$bank,$rek,$res,$user) {   
      $this->StartTrans();
        $cek=$this->Open("SELECT * FROM sdm_pegawai_detail WHERE pegdtPegId='%s' ",array($id1));
    if(empty($cek)){
          $this->Execute($this->mSqlQueries['do_add_nik'],array($id1,$kodenik,$katpeg,$tippeg,$atas2,$atas1));//sdm_pegawai_detail
        }else{
          $this->Execute($this->mSqlQueries['do_update_nik'],array($kodenik,$katpeg,$tippeg,$atas2,$atas1,$id1));
        }
    
    //print_r($this->getLastError());exit; 
      
      if(!empty($satker)){
        $this->Execute($this->mSqlQueries['do_add_satker'],array($id1,$satker,$data['mulai']));//sdm_satuan_kerja_pegawai
      }
      
      if(!empty($rek)){
        $this->Execute($this->mSqlQueries['do_add_data_rek'], array($id1,$bank,$rek,$res,$user));
      }
      
      if(!empty($gol)){
        if($p=="in"){
          $this->Execute($this->mSqlQueries['do_add_gol'],array($id1,$data['jnspeg'],$gol,$data['mulai']));//sdm_pangkat_golongan
        }
        if(!empty($struk)){
          $this->Execute($this->mSqlQueries['do_add_struk'],array($id1,$struk,$gol,$data['mulai']));//sdm_jabatan_struktural
          $this->Execute($this->mSqlQueries['do_add_komp_gaji'],array($id1,$idStruk,$dateNow));
        }
        if(!empty($fung)){
          $this->Execute($this->mSqlQueries['do_add_fung'],array($id1,$fung,$gol,$data['mulai']));//sdm_jabatan_fungsional
          $this->Execute($this->mSqlQueries['do_add_komp_gaji'],array($id1,$idFung,$dateNow));
        }
      }
      if($op=="input"){
        $this->Execute($this->mSqlQueries['do_add_2'],array($id1));
      }
      $result = $this->EndTrans(true);
      //print_r($this->getLastError());exit;
      return $result;
   }
   
   function Update($data) {    
      $params=array($data['nip'],$data['kodeGateAccess'],$data['kodeInter'],$data['kodeLain'],
    $data['nama'],$data['gelDep'],$data['gelBel'],$data['tmpLahir'],$data['tglLahir'],$data['noAkta'],$data['akta'],
    $data['idLain'],$data['jenKel'],$data['agama'],$data['statNikah'],
    $data['alamat'],$data['kodePos'],$data['noTelp'],$data['noHp'],
    $data['email'],$data['golDar'],$data['tinggiBdn'],$data['beratBdn'],
    $data['cacat'],$data['hobi'],$data['tglmasuk'],$data['pnstmt'],$data['cpnstmt'],
      $data['notaspen'],$data['noaskes'],$data['statusnpwp'],$data['nonpwp'],$data['tglnpwp'],$data['usiapens'],
    $data['kodeabsen'],$data['jnsidlain'],$data['jnspeg'],$data['statpeg'],
    $data['satwilpeg'],$data['datpegAsalDesa'],$data['datpegAsalKecamatan'],$data['asalsatwil'],
    $data['pegLevelId'],$data['pegStatusWargaNeg'],$data['foto'],$data['userId'],
    $data['idSkck'],$data['kir'],$data['tglkir'],$data['noskbn'],$data['tglskbn'],$data['noskkb'],$data['tglskkb'],
    $data['kelurahan'],$data['kecamatan'],$data['kepemilikanRumah']
    ,$data['pegNoKarpeg'],$data['pegNoKpe'],$data['PegJenFungsional']
    ,$data['id']);

    // var_dump(vsprintf($this->mSqlQueries['do_update'],$params));exit;
      $return = $this->Execute($this->mSqlQueries['do_update'],$params);    
      return $return;
   }
   
    function UpdateCustom($data, $id) {
        $query = $this->mSqlQueries['do_update_custom'];
        
        $fieldset = '';
        $keys = array_keys($data);
        $last_key = end($keys);
        foreach($data as $key => $value) {
            $fieldset .= "    ".$key." = '%s'";
            // If not last item
            if($key !== $last_key) {
                $fieldset .= ",\n";
            }
        }
        
        $data[] = $id;
        
        $query = str_replace('--fieldset--', $fieldset, $query);
        
        $return = $this->Execute($query, $data);
        return $return;
    }
   
   function AddDataRekening($id,$op,$bank,$rekening,$penerima){
    $this->Execute($this->mSqlQueries['do_delete_rekening'], array($id));
    $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId(); //$this->GetUserIdByUserName();
    $this->Execute($this->mSqlQueries['do_add_rekening'], array($id,$bank,$rekening,$penerima,$userId));
    
    return true;
   }
   
   function AddDataBahasa($id,$data){
    $this->Execute($this->mSqlQueries['do_delete_bahasa'], array($id));
    for($i = 0, $m = count($data); $i < $m; ++$i){
      $this->Execute($this->mSqlQueries['do_add_bahasa'], array($id,$data[$i]));
    }
    return true;
   }
   
    function Delete($id) {
      $this->StartTrans();
    $this->Execute($this->mSqlQueries['do_delete_rekening'], array($id));
      $this->Execute($this->mSqlQueries['do_delete_3'], array($id));
      $this->Execute($this->mSqlQueries['do_delete_2'], array($id));
      $this->Execute($this->mSqlQueries['do_delete'], array($id));
      $result = $this->EndTrans(true); //delete "true" in endtrans for setup cannot delete data if have relation
      
      return $result;
    }

    function GetCountPegawaiByUserIdVerified($nip_nama='',$status=''){
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

      // $satkerlevel = $result['0']['satkerLevel'].'%';
      $satkerlevel = $result['0']['satkerLevel'];

         // $result = $this->Open($this->mSqlQueries['get_count_pegawai_by_user_id_verified'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$resultunitgroup,$satker,$status_kerja));
         $sql=$this->mSqlQueries['get_count_pegawai_by_user_id_verified'];
         
      // Temporary fix (user)
      if(!in_array($this->user, array(60, 61))) {
          $sql = str_replace('--user--', ' AND a.pegUserId = '. $this->user, $sql);
      } else {
          $sql = str_replace('--user--', '', $sql);
      }
      
      $sql=str_replace('%status%',$status_kerja,$sql);
      // $result=$this->Open($sql,array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satkerlevel,$satker));
      $result=$this->Open($sql,array('%'.$nip_nama.'%','%'.$nip_nama.'%'));
        $res3 = sizeof($result); 
        if(!$res3){
          return 0;
      }
      else{
        return $res3;
      }  
    }

    function GetDataPegawaiByUserIdVerified($offset, $limit, $nip_nama='',$status=''){ 
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      
      if(!empty($userId)){
      $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
      
      }
      if($result){
      $satker = $result[0]['satkerId'];
      $satlev = $result[0]['satkerLevel'];
      $unitgrup  = '';
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
      // echo $status;
      // $sql=$this->mSqlQueries['get_data_pegawai_by_user_id_verified'];
     

      if($status != 'all'){
      $status_kerja = "AND l.statrId =$status" ;
      }else {
      $status_kerja = 'AND 1=1';
      }
      // $sql=$this->mSqlQueries['get_combo_'.$variabel];
      $sql=$this->mSqlQueries['get_data_pegawai_by_user_id_verified'];
      $sql=str_replace('%status%',$status_kerja,$sql);
      // $satkerlevel = $result['0']['satkerLevel'].'%';
      $satkerlevel = $result['0']['satkerLevel'];
      
      // Temporary fix (user)
      if(!in_array($this->user, array(60, 61))) {
          $sql = str_replace('--user--', ' AND a.pegUserId = '. $this->user, $sql);
      } else {
          $sql = str_replace('--user--', '', $sql);
      }
      
      // $result=$this->Open($sql,array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satkerlevel,$satker, (int)$offset, (int)$limit));
      $result=$this->Open($sql,array('%'.$nip_nama.'%','%'.$nip_nama.'%', (int)$offset, (int)$limit));
      // $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_user_id_verified'], array('%'.$nip_nama.'%','%'.$nip_nama.'%',$resultunitgroup,$satker,$status_kerja, (int)$offset, (int)$limit));

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

    function GetCountDataVerified($nip_nama='', $status_kerja = '') {
      /*if($nip_nama != ""){
        $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }*/
      
      if(($nip_nama != "") and ($status_kerja != "all")){
        $str = " AND (verdataStatus='3' AND verdataVerifikasiId='19' AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')) AND
        pegStatrId = '".$status_kerja."'"; 
      }elseif(($nip_nama != "") and ($status_kerja == "all")){
        $str = " AND (verdataStatus='3' AND verdataVerifikasiId='19' AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%'))";
        //AND d.satkerpegSatkerId IS NULL";
      }elseif(($nip_nama == "") and ($status_kerja != "all")){
        $str = " AND pegStatrId = '".$status_kerja."'";
      }else{
        $str = "";
      }
      
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
        if(!empty($userId)){
            $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
        }
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
      $str .= "AND (satkerLevel LIKE CONCAT('".$satkerlevel."', '.%') OR satkerId = '".$satker."')";
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count_verified'], array($str));
      $res2 = $this->Open(stripslashes($result), array());
      $res3 = sizeof($res2); 
      
      if (!$res3) {
        return 0;
      } else {
        return $res3;
      }  
   }

   function GetDataVerified ($offset, $limit, $nip_nama='',$status_kerja='') { 
      /*if($nip_nama != ""){
        $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }*/
       
      if(($nip_nama != "") and ($status_kerja != "all")){
        $str = " AND (verdataStatus='3' AND verdataVerifikasiId='19' AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')) AND
        pegStatrId = '".$status_kerja."'"; 
      }elseif(($nip_nama != "") and ($status_kerja == "all")){
        $str = " AND (verdataStatus='3' AND verdataVerifikasiId='19' AND (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%'))";
        //AND d.satkerpegSatkerId IS NULL";
      }elseif(($nip_nama == "") and ($status_kerja != "all")){
        $str = " AND verdataStatus='3' AND verdataVerifikasiId='19' AND  pegStatrId = '".$status_kerja."'";
      }else{
        $str = "";
      }
      
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
        if(!empty($userId)){
            $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
        }
        if($result) {
            $satker = $result[0]['satkerId'];
            $satkerlevel = $result[0]['satkerLevel'];
            foreach ($result as $key => $value) {
                $unitgrup .= $value['satkerId'].',';
            }
            $uGroupunit = str_replace(",","','",$unitgrup);
            $resultunitgroup = $uGroupunit.'0';
        } else {
            $satker = 0;
            $satkerlevel = 0;
        }
      $str .= "AND (satkerLevel LIKE CONCAT('".$satkerlevel."', '.%') OR satkerId = '".$satker."')";
      
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data_verified'], array($str, $offset, $limit));

      return $this->Open(stripslashes($result), array());    
   } 

}
?>
