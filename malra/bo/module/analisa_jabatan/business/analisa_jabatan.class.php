<?php

class AnalisaJabatan extends Database {

   protected $mSqlFile= 'module/analisa_jabatan/business/analisa_jabatan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);  
      //   
   }

   

 function GetCountJabatanPegawai($nip_nama='',$satuan_kerja='',$najab='',$jenjab){ 
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      $str = '';
      $unitgrup ='';
      $strjab = '';
      if($satuan_kerja == 'all' OR $satuan_kerja == ''){
      
        if(!empty($userId)){
        $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
        }

        if($result){
        foreach ($result as $key => $value) {
          $unitgrup .= $value['satkerId'].',';
        }
          $uGroupunit = str_replace(",","','",$unitgrup);
          $resultunitgroup = $uGroupunit.'0';
        } else {
          $resultunitgroup = 0;
        } 

      } else {
        if(!empty($satuan_kerja)){
        $resultunitgroup = $satuan_kerja;          
        }
      }

      if(!empty($resultunitgroup) AND $jenjab == '1') {
      $str .= "AND d.satkerpegSatkerId IN ('".$resultunitgroup."') ";
      $str .= "AND b.jbtnStatus = 'Aktif' "; 
      }else {
      $str .= "OR b.jbtnStatus = 'Tidak Aktif' "; 
      $str .= "AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif')"; 
      // $str .= '';
      }

      if(!empty($nip_nama) AND $nip_nama != '' ){
      $str .= "AND c.pegNama LIKE '%".$nip_nama."%' OR c.pegKodeResmi LIKE '%".$nip_nama."%'";
      } else {
      $str .= '';
      }

        if(!empty($najab) AND $najab != '' ){
      $str .= "AND a.jabstrukrNama LIKE '%".$najab."%' ";
      } else {
      $str .= '';
      }

      if($jenjab == '2' ){
      $strjab .= "c.pegKodeResmi IS NULL AND ";
      $grp = 'GROUP BY a.jabstrukrId';
      } else {
      $str .= '';
      $grp = 'GROUP BY c.pegKodeResmi';
      }
      // echo "<pre>";
      // echo $jenjab.'<br/>'.$najab.'<br/>'.$resultunitgroup;
      // echo "</pre>";
      if($jenjab == '1' OR empty($jenjab)){
      $query = $this->mSqlQueries['count_all_jabatan_pegawai'];
      $query = str_replace('--jenjab--', $strjab, $query);
      $query = str_replace('--search--', $str, $query);
      $query = str_replace('--group--', $grp, $query);
      }
        
        if(!empty($satuan_kerja) && $jenjab == '2'){
        if($satuan_kerja != 'all'){
        $string .=" AND b.satkerId = '$satuan_kerja'"; 
                          
        }
        if(!empty($najab) AND $najab != '' )
        $string .= "AND a.jabstrukrNama LIKE '%".$najab."%' ";
                  
      $query = $this->mSqlQueries['get_count_empty_jabatan'];
      $query = str_replace('--search--', $string, $query);
      $query = str_replace('--group--', $grp, $query);
        }

              // echo "<pre>";
              //  // echo $this->debug_mode();
              // // print_r($error);
              // print_r($query);
              // // print_r($result);
              // echo "</pre>";
      

      $result = $this->Open(stripslashes($query), array());
      $result = count($result);
      return $result;
    }

     
 function GetAllJabatanPegawai($offset, $limit, $nip_nama='',$satuan_kerja='',$najab='',$jenjab){ 
      $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();
      $str = '';
      $unitgrup ='';
       $strjab = '';
      if($satuan_kerja == 'all' OR $satuan_kerja == ''){
      
        if(!empty($userId)){
        $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
        }

        if($result){
        foreach ($result as $key => $value) {
          $unitgrup .= $value['satkerId'].',';
        }
          $uGroupunit = str_replace(",","','",$unitgrup);
          $resultunitgroup = $uGroupunit.'0';
        } else {
          $resultunitgroup = 0;
        } 

      } else {
        if(!empty($satuan_kerja)){
        $resultunitgroup = $satuan_kerja;          
        }
      }

      if(!empty($resultunitgroup) AND $jenjab == '1'){ 
      $str .= "AND d.satkerpegSatkerId IN ('".$resultunitgroup."') ";
      $str .= "AND b.jbtnStatus = 'Aktif' ";
      }else {
      $str .= "OR b.jbtnStatus = 'Tidak Aktif' "; 
      $str .= "AND a.jabstrukrId NOT IN ( SELECT b.jbtnJabstrukrId FROM sdm_jabatan_struktural b WHERE b.jbtnStatus = 'Aktif') ";      
      // $str .= '';
      }

      if(!empty($nip_nama) AND $nip_nama != '' ){
      $str .= "AND c.pegNama LIKE '%".$nip_nama."%' OR c.pegKodeResmi LIKE '%".$nip_nama."%' ";
      } else {
      $str .= '';
      }


      if(!empty($najab) AND $najab != '' ){
      $str .= "AND a.jabstrukrNama LIKE '%".$najab."%' ";
      } else {
      $str .= '';
      }
      
      if($jenjab == '2' ){
      $strjab .= "c.pegKodeResmi IS NULL AND ";
      $grp = 'GROUP BY a.jabstrukrId';
      } else {
      $str .= '';
      $grp = 'GROUP BY c.pegKodeResmi';
      }
      // echo $offset.$limit;
      // if(!empty($offset) && !empty($limit)){
      $lim = "LIMIT $offset,$limit";  
      // }
      // echo "<pre>";
      // echo $nip_nama.'<br/>'.$najab.'<br/>'.$resultunitgroup;
      // echo "</pre>";
      if($jenjab == '1' OR empty($jenjab)){
      $query = $this->mSqlQueries['get_all_jabatan_pegawai'];
       $query = str_replace('--jenjab--', $strjab, $query);
      $query = str_replace('--search--', $str, $query);
      $query = str_replace('--limit--', $lim, $query);
      $query = str_replace('--group--', $grp, $query);
      }
      
      if(!empty($satuan_kerja) && $jenjab == '2'){
        if($satuan_kerja != 'all'){
        $string .=" AND b.satkerId = '$satuan_kerja'"; 
                             
        }
        if(!empty($najab) AND $najab != '' )
        $string .= "AND a.jabstrukrNama LIKE '%".$najab."%' ";

      $query = $this->mSqlQueries['get_empty_jabatan'];
      $query = str_replace('--search--', $string, $query);

        }


      

      $result = $this->Open(stripslashes($query), array());
      
      // $error = mysql_error();
      //         echo "<pre>";
      //          // echo $this->debug_mode();
      //         // print_r($error);
      //         print_r($query);
      //         // print_r($result);
      //         echo "</pre>";
      // // exit(); 

      return $result;
    }


   function GetComboJabatanStrukturalEmpty($JbId) {
    $result = $this->Open($this->mSqlQueries['get_combo_jabstruk_empty'], array($JbId));
    //echo sprintf($this->mSqlQueries['get_combo_jabfungjenisrid']);
    return $result;
   }

   function GetListSatKer(){
     
      $result = $this->Open($this->mSqlQueries['get_list_satker'], array());
    return $result;
   }

   function GetListAnggota($id){
     
      $result = $this->Open($this->mSqlQueries['get_list_staf'], array($id));
    return $result;
   }

   function GetTitle($id){
     
      $result = $this->Open($this->mSqlQueries['get_title'], array($id));
    return $result;
   }



   function GetCountAnggota($id){
     
      $result = $this->Open($this->mSqlQueries['get_count_staf'], array($id));
    return $result;
   }

   function GetSatKerDetail($id){
      $result = $this->Open($this->mSqlQueries['get_satker_detail'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }

    function GetKepalaStafDetail($id){
      $result = $this->Open($this->mSqlQueries['get_kepala_staf'], array($id)); 
    if($result)
       return $result[0];
    else
       return $result;
   }


   function GetSatKerLevel($level){
      $result = $this->Open($this->mSqlQueries['get_parent_level'], array($level));
    if($result){
       return $result[0];
    }else{
       return $result;
   }
   }
}
?>
