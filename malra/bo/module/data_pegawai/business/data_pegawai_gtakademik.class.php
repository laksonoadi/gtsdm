<?php

class DataPegawaiGtakademik extends Database {

   protected $mSqlFile= 'module/data_pegawai/business/data_pegawai_gtakademik.sql.php';
   
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
   
   //----------------DO----------------
   function AddPegawai($data,$kategori) {
		$params=array(	$data['nip'],$data['nama'],$data['gelDep'],
						$data['gelBel'],$data['tglLahir'],$data['jenKel'],
						$data['alamat'],$data['kodePos'],$data['noTelp'],
						$data['noHp'],$data['notaspen']
				);
				
		$params2=array(	$data['nip'],$data['kodeLain'],$data['foto']);
      
		$return = $this->Execute($this->mSqlQueries['do_add_pegawai'],$params);
		if (($kategori=='Academic')&&($return===true)){
			$return = $this->Execute($this->mSqlQueries['do_update_jenis_pegawai'],array('1',$data['nip']));
			$return = $this->Execute($this->mSqlQueries['do_add_dosen'],$params2);
		}
		
		if (($kategori!='Academic')){
			$return = $this->Execute($this->mSqlQueries['do_update_jenis_pegawai'],array('2',$data['nip']));
		}
		
		return $return;
   }

   function DeletePegawai($nip) {
	$return = $this->Execute($this->mSqlQueries['do_delete_dosen'],array($nip));
	$return = $this->Execute($this->mSqlQueries['do_delete_pegawai'],array($nip));
	return $return;
   }
   
}
?>
