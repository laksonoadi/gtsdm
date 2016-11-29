<?php

class VerifikasiData extends Database {

	protected $mSqlFile= 'module/verifikasi_data/business/verifikasi_data.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		$this->userid=Security::Authentication()->GetCurrentUser()->GetUserId();
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
   
	function GetCountDataNotifikasi($nip_nama='',$status='',$jenis='') {
		$jenisdata = $this->Open($this->mSqlQueries['get_jenis_data'], array($jenis));
		$referensiQuery = $jenisdata[0]['verifikasiReferensiQuery'];
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

		$querydata = str_replace('%referensiQuery%',$referensiQuery,$this->mSqlQueries['get_count_data_notifikasi_by_userid']);
		// $query = str_replace('%unitdata%',$resultunitgroup,$querydata);
		$query = str_replace('%unitdata%','',$querydata);

		if ($status!="all"){
			$query .= " AND status_data=".$status;
		}
		// $result=$this->Open($query,array('%'.$nip_nama.'%','%'.$nip_nama.'%',$this->userid,$this->userid));
		$result=$this->Open($query,array('%'.$nip_nama.'%','%'.$nip_nama.'%', $satkerlevel, $satker, $this->userid,$this->userid));
		return $result[0]['total'];
	}
	
	function GetDataNotifikasi($offset, $limit, $nip_nama='',$status='',$jenis='') { 
		$jenisdata = $this->Open($this->mSqlQueries['get_jenis_data'], array($jenis));
		$referensiQuery = $jenisdata[0]['verifikasiReferensiQuery'];
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

		$querydata = str_replace('%referensiQuery%',$referensiQuery,$this->mSqlQueries['get_data_notifikasi_by_userid']);
		// $query = str_replace('%unitdata%',$resultunitgroup,$querydata);
		$query = str_replace('%unitdata%','',$querydata);
		if ($status!="all"){
			$query .= " AND status_data=".$status;
		}
		$query .= " LIMIT ".$offset.",".$limit;
		
		$result=$this->Open($query,array('%'.$nip_nama.'%','%'.$nip_nama.'%',$satkerlevel, $satker, $this->userid,$this->userid));
		return $result;
	}
	
	function GetCountDataPencarian($search_keyword='') {
		$jenisdata = $this->Open($this->mSqlQueries['get_all_jenis_data'], array());
		
		if (sizeof($jenisdata)<=0) return 0;
		
		$referensiQuery = '('.$jenisdata[0]['verifikasiReferensiQuery'].')';
		for ($i=1; $i<sizeof($jenisdata); $i++){
			$referensiQuery .= ' UNION ('.$jenisdata[$i]['verifikasiReferensiQuery'].' ) ';
		}
		$userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
	    if(!empty($userId)){
	    $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
	    }
		foreach ($result as $key => $value) {
        $unitgrup .= $value['satkerId'].',';
      	}
      	$uGroupunit = str_replace(",","','",$unitgrup);
        $resultunitgroup = $uGroupunit.'0';
		$querydata = str_replace('%referensiQuery%',$referensiQuery,$this->mSqlQueries['get_count_data_pencarian_by_userid']);
		$query = str_replace('%unitdata%',$resultunitgroup,$querydata);
		
		$result=$this->Open($query,array('%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%',$this->userid,$this->userid));
		return $result[0]['total'];
		
	}
   
	function GetDataPencarian($offset, $limit, $search_keyword='') { 
		$jenisdata = $this->Open($this->mSqlQueries['get_all_jenis_data'], array());
		
		if (sizeof($jenisdata)<=0) return array();
		
		$referensiQuery = '('.$jenisdata[0]['verifikasiReferensiQuery'].')';
		for ($i=1; $i<sizeof($jenisdata); $i++){
			$referensiQuery .= ' UNION ('.$jenisdata[$i]['verifikasiReferensiQuery'].' ) ';
		}
		$userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();//$this->GetUserIdByUserName();

      
	    if(!empty($userId)){
	    $result = $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));  
	    }
		foreach ($result as $key => $value) {
        $unitgrup .= $value['satkerId'].',';
      	}
      	$uGroupunit = str_replace(",","','",$unitgrup);
        $resultunitgroup = $uGroupunit.'0';
		
		$querydata = str_replace('%referensiQuery%',$referensiQuery,$this->mSqlQueries['get_data_pencarian_by_userid']);
		$query = str_replace('%unitdata%',$resultunitgroup,$querydata);
		
		$query .= " LIMIT ".$offset.",".$limit;
		$result=$this->Open($query,array('%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%',$this->userid,$this->userid));
		//echo '<pre>'.vsprintf($query,array('%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%','%'.$search_keyword.'%',$this->userid,$this->userid)).'</pre>'; exit;
		return $result;
	}
	
	function GetPegawaiByNama($offset, $limit, $search_keyword='') {
		$query = $this->mSqlQueries['get_data_pencarian_by_name'];
		$where = '';
		$limit = " LIMIT $offset, $limit ";
		if($search_keyword !== '') {
			$where .= sprintf(' AND (pegNama LIKE "%%%1$s%%" OR pegKodeResmi LIKE "%%%1$s%%") ', $search_keyword);
		}
		
		$query = str_replace('--where--', $where, $query);
		$query = str_replace('--limit--', $limit, $query);
		$result = $this->Open($query, array());
		return $result;
	}
	function CountPegawaiByNama($search_keyword='') {
		$query = $this->mSqlQueries['count_data_pencarian_by_name'];
		$where = '';
		if($search_keyword !== '') {
			$where .= sprintf(' AND (pegNama LIKE "%%%1$s%%" OR pegKodeResmi LIKE "%%%1$s%%") ', $search_keyword);
		}
		
		$query = str_replace('--where--', $where, $query);
		$result = $this->Open($query, array());
		if($result) {
			return $result[0]['total'];
		}
	}
	
	function GetDataNotifikasiById($id,$jenis='') { 
		$jenisdata = $this->Open($this->mSqlQueries['get_jenis_data'], array($jenis));
		$referensiQuery = $jenisdata[0]['verifikasiReferensiQuery'];
		$query = str_replace('%referensiQuery%',$referensiQuery,$this->mSqlQueries['get_data_notifikasi_by_id']);
		$result=$this->Open($query,array($id));
		return $result;
	}
   
    function GetSatkerAndLevel() {
        $userId = Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId();
        return $this->Open($this->mSqlQueries['get_satker_and_level'], array($userId));
    }
   
	function GetComboStatusData(){
		$result = $this->Open($this->mSqlQueries['get_combo_status_data'], array());
		return $result;
	}
	
	function GetComboJenisData(){
		$result = $this->Open($this->mSqlQueries['get_combo_jenis_data'], array());
		return $result;
	}
   
   
	//----------------DO----------------
	function DoUpdateStatus($id,$referensi,$status) {	 
		$userId=Security::Authentication()->GetCurrentUser()->GetUserId();
		
		$result = $this->Open($this->mSqlQueries['get_id_referensi'],array($referensi));
		$idreferensi = $result[0]['id'];
		
		$return = $this->Execute($this->mSqlQueries['do_update_status'],array($idreferensi,$id,$status,$userId,$userId));
		return $return;
	}
   
}
?>
