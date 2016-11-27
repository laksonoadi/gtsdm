<?php

class AppHistoryGajiPegawai extends Database {

	protected $mSqlFile= 'module/gaji_pegawai/business/apphistorygajipegawai.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		//$this->mrDbEngine->debug = 1;
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
		return $sql_parsed;
	}
		
	function GetData($offset, $limit, $dataId, $tampilkan, $periode_year, $periode_mon) {
      if($tampilkan == "semua") {
         $str_tampilkan = " AND EXTRACT(YEAR FROM a.gajipegPeriode) = '".$periode_year."'";
      } else {
         //satu periode
         $periode = $periode_year . $periode_mon;
         $str_tampilkan = " AND EXTRACT(YEAR_MONTH FROM a.gajipegPeriode) = '".$periode."' ";
      }
      $sql = $this->GetQueryKeren($this->mSqlQueries['get_data'], array($dataId, $str_tampilkan));
      $sql = stripslashes($sql);
		$result = $this->Open($sql, array());
      //echo $sql;
		return $result;
	}

	function GetCountData($dataId, $tampilkan, $periode_year, $periode_mon) {
      if($tampilkan == "semua") {
         $str_tampilkan = "AND EXTRACT(YEAR FROM a.gajipegPeriode) = '".$periode_year."'";
      } else {
         //satu periode
         $periode = $periode_year . $periode_mon;
         $str_tampilkan = " AND EXTRACT(YEAR_MONTH FROM a.gajipegPeriode) = '".$periode."' ";
      }
      $sql = $this->GetQueryKeren($this->mSqlQueries['get_count_data'], array($dataId, $str_tampilkan));
      $sql = stripslashes($sql);
		$result = $this->Open($sql, array());
      //echo $sql;
		if (!$result) {
			return 0;
		} else {
			return $result[0]['total'];
		}
	}
   
   function GetGapokSwasta($id){
      $result = $this->Open($this->mSqlQueries['get_gapok_swasta'],array($id));
      //print_r($this->getLastError());exit;
      return $result;
   }
   
   function GetGapokNegeri($id){
      $result = $this->Open($this->mSqlQueries['get_gapok_negeri'],array($id));
      //print_r($this->getLastError());exit;
      return $result;
   }
	
	function GetTahun(){
		$year = date('Y')+3;
		$no=0;
		for($i=$year;$i>1990;$i--){
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
	
	function GetDataById($id) {      
      $result = $this->Open($this->mSqlQueries['get_data_by_id'], array($id)); 
	  if($result)
	     return $result[0];
	  else
	     return $result;	  
   }
   
   #tambahan untuk cetak slip gaji
   function GetDataCetakBackUp($dataId, $periode) {
      $sql = $this->GetQueryKeren($this->mSqlQueries['get_data_cetak'], array($dataId, $periode));
      $sql = stripslashes($sql);
      $result = $this->Open($sql, array());
      //echo $sql;
      #
      #
      return $result;
   }
   
   function GetDataCetak($dataId, $periode) {
      $result['header'] = $this->Open($this->mSqlQueries['get_data_cetak'], array($dataId, $periode));
	  $temp = $this->Open($this->mSqlQueries['get_data_cetak_det'], array($dataId, $periode,$dataId, $periode));
	  
	  $j=0; $jj=0; $jjj=0;
	  for ($i=0; $i<sizeof($temp); $i++){
		if ($temp[$i]['jenis']=='POTONGAN'){
			$result['potongan'][$j]=$temp[$i];
			$j++;
		}elseif (strpos($temp[$i]['kolom'],'Tunjangan Lain')===false){
			$result['tunjangan'][$jjj]=$temp[$i];
			$jjj++;
		}else{
			$temp[$i]['kolom']=str_replace('(Tunjangan Lain)','',$temp[$i]['kolom']);
			$result['lain'][$jj]=$temp[$i];
			$jj++;
		}
	  }
      
      return $result;
   }

}
?>
