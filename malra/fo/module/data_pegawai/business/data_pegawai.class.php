<?php

class DataPegawai extends Database {

   protected $mSqlFile= 'module/data_pegawai/business/data_pegawai.sql.php';
   
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
   
   function IsSupervisor($idPeg) {
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
   
   function GetComboPegawaiDepartement($id){
     $result = $this->Open($this->mSqlQueries['get_combo_pegawai_departement'], array($id));
	   return $result;
   }
  
//==GET== 
   function GetUserIdByUserName() {      
      $result = $this->Open($this->mSqlQueries['get_user_id_by_username'], array($_SESSION['username'])); 
	  if($result)
	     return $result[0]['userId'];
	  else
	     return $result;	  
   }
   
   function GetPegIdByUserName() {      
      $result = $this->Open($this->mSqlQueries['get_peg_id_by_username'], array($_SESSION['username'])); 
	  if($result)
	     return $result[0]['pegId'];
	  else
	     return $result;	  
   }
   
   function GetDataPegawaiByUserName() {      
      $result = $this->Open($this->mSqlQueries['get_data_pegawai_by_username'], array($_SESSION['username']));
      //echo sprintf($this->mSqlQueries['get_data_pegawai_by_username'],$_SESSION['username']);
      //print_r($this->getLastError());exit; 
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
   
   function GetCountData($nip_nama='') {
      if($nip_nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_count'], array($str));
  		$res2 = $this->Open(stripslashes($result), array());
  		$res3 = sizeof($res2); 
  		
      if (!$res3) {
  			return 0;
  		} else {
  			return $res3;
  		}  
   } 
   
   function GetData ($offset, $limit, $nip_nama='') { 
      if($nip_nama != ""){
  		  $str = " WHERE (pegKodeResmi LIKE '%".$nip_nama."%' OR pegNama LIKE '%".$nip_nama."%')";
      }else{
        $str = "";
      }
      
      $result = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($str, $offset, $limit));
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
	     return $result[0];
	  else
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
   
   function GetComboJenisPeg(){
      $result = $this->Open($this->mSqlQueries['get_combo_jenispeg'], array());
	  return $result;
   }
   
   function GetComboStatPeg(){
      $result = $this->Open($this->mSqlQueries['get_combo_statpeg'], array());
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
   
   function GetDataAtas($id){
      $result = $this->Open($this->mSqlQueries['get_data_atas'], array($id)); 
		if($result)
	     return $result[0];
	    else
	     return $result;
   }
   
   function GetDataRek($id){
      $result = $this->Open($this->mSqlQueries['get_data_rek'], array($id)); 
		  if (empty($result)) {return false; }
		  return true;
   }
   
   //----------------DO----------------
   function Add($data) {	 
      $params=array($data['nip'],$data['kodeInter'],$data['kodeLain'],
	  $data['nama'],$data['gelDep'],$data['tmpLahir'],$data['tglLahir'],
	  $data['idLain'],$data['jenKel'],$data['agama'],$data['statNikah'],
	  $data['alamat'],$data['kodePos'],$data['noTelp'],$data['noHp'],
	  $data['email'],$data['golDar'],$data['tinggiBdn'],$data['beratBdn'],
	  $data['cacat'],$data['hobi'],$data['tglmasuk'],$data['pnstmt'],
      $data['notaspen'],$data['noaskes'],$data['nonpwp'],$data['usiapens'],
	  $data['kodeabsen'],$data['jnsidlain'],$data['jnspeg'],$data['statpeg'],
	  $data['satwilpeg'],$data['foto'],$data['userId']);
      
      $return = $this->Execute($this->mSqlQueries['do_add'],$params);
      //print_r($this->getLastError());exit;  
      return $return;
   }
   
   function AddDataDetail($id1,$kodenik,$katpeg,$tippeg,$atas2,$atas1){
		$cek=$this->Open("SELECT * FROM sdm_pegawai_detail WHERE pegdtPegId='%s' ",array($id1));
		if(empty($cek)){
          $this->Execute($this->mSqlQueries['do_add_nik'],array($id1,$kodenik,$katpeg,$tippeg,$atas2,$atas1));
        }else{
          $this->Execute($this->mSqlQueries['do_update_nik'],array($kodenik,$katpeg,$tippeg,$atas2,$atas1,$id1));
        }
   }
   
   function AddDatGaji($id1,$kodenik,$satker,$gol,$struk,$fung,$data,$op,$p,$p2,$idStruk,$idFung,$dateNow,$katpeg,$tippeg,$atas1,$atas2,$bank,$rek,$user) {	 
      $this->StartTrans();
        if($p2=="in"){
          $this->Execute($this->mSqlQueries['do_add_nik'],array($id1,$kodenik,$katpeg,$tippeg,$atas2,$atas1));//sdm_pegawai_detail
        }else{
          $this->Execute($this->mSqlQueries['do_update_nik'],array($kodenik,$katpeg,$tippeg,$atas2,$atas1,$id1));
        }
	  
	  //print_r($this->getLastError());exit; 
      
      if(!empty($satker)){
        //$this->Execute($this->mSqlQueries['do_add_satker'],array($id1,$satker,$data['mulai']));//sdm_satuan_kerja_pegawai
      }
      
      
      if(!empty($rek)){
        $cek=$this->GetDataRek($id1);
        if ($cek){
           $this->Execute($this->mSqlQueries['do_update_data_rek'], array($bank,$rek,$user,$id1));
        } else {
           $this->Execute($this->mSqlQueries['do_add_data_rek'], array($id1,$bank,$rek,$user));
        }
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
   
	function AddDataRekening($id,$op,$bank,$rekening,$penerima){
		$this->Execute($this->mSqlQueries['do_delete_rekening'], array($id));
		$userId=$this->GetUserIdByUserName();
		$this->Execute($this->mSqlQueries['do_add_rekening'], array($id,$bank,$rekening,$penerima,$userId));
		
		return true;
	}
   
   function Update($data) {	   
      $params=array($data['nip'],$data['kodeGateAccess'],$data['kodeInter'],$data['kodeLain'],
	  $data['nama'],$data['gelDep'],$data['gelBel'],$data['tmpLahir'],$data['tglLahir'],
	  $data['idLain'],$data['jenKel'],$data['agama'],$data['statNikah'],
	  $data['alamat'],$data['kodePos'],$data['noTelp'],$data['noHp'],
	  $data['email'],$data['golDar'],$data['tinggiBdn'],$data['beratBdn'],
	  $data['cacat'],$data['rambut'],$data['muka'],$data['warna'],$data['ciri'],$data['hobi'],$data['tglmasuk'],$data['pnstmt'],
      $data['notaspen'],$data['noaskes'],$data['nonpwp'],$data['usiapens'],
	  $data['kodeabsen'],$data['jnsidlain'],$data['jnspeg'],$data['statpeg'],
	  $data['satwilpeg'],$data['foto'],$data['userId'],$data['id']);
      $return = $this->Execute($this->mSqlQueries['do_update'],$params);	  
      return $return;
   }  
   
    function Delete($id) {
      $this->StartTrans();
      $this->Execute($this->mSqlQueries['do_delete_3'], array($id));
      $this->Execute($this->mSqlQueries['do_delete_2'], array($id));
      $this->Execute($this->mSqlQueries['do_delete'], array($id));
      $result = $this->EndTrans(true);
      //print_r($this->getLastError());exit;	
      return $result;
    }
}
?>
