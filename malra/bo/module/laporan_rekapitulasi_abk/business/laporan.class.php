<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Laporan extends Database {

   protected $mSqlFile= 'module/laporan_rekapitulasi_abk/business/laporan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);   
      //
      
      for ($i=date('Y')-20; $i<=date('Y'); $i++){
        $this->tahun["'".$i."'"]=$i;
      }  
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
  

	function GetComboUserUnitKerja(){
        $Satker = new SatuanKerja();
        $result = $Satker->GetComboUserUnitKerja();
        return $result;
	}
   
   function GetDataPegawai($unit_id = NULL, $id = 0) {
      $query = $this->mSqlQueries['get_data'];
      $where = '';
      if(empty($id))
          return array();
      if($id != 0 && $id != '') {
          $where .= ' AND (a.satkerId = b.satkerId OR a.satkerLevel LIKE CONCAT(b.satkerLevel, ".%%")) ';
      }
      if(!empty($unit_id)) {
         $where .= sprintf(' AND a.satkerUnitId = "%s" ', $unit_id);
      }
      $query = str_replace('--where--', $where, $query);
      $query = str_replace('--unit_satker--', sprintf('%s', $id), $query);
      $data = $this->Open($query, array());
      // echo "<pre>"; var_dump($query); echo "</pre>";
      if(empty($data))
          return $data;
      
      // Start sorting out combo
      // Ordering in the query is vital to make sure the items get assigned a position from top to bottom (ascending) in level
      // NOTE: uses the same algorithm as method GetComboTreeSatuanKerja() in class SatuanKerja (satuan_kerja.class.php)
      /* $str_lvl_offset = $data[0]['lv']; // Find the fewest level */
      $combo = array();
      $subs = array();
      $pos_all = array();
      $pos_child = array();
      $i = 0;
      foreach($data as $k => $v) {
          if($v['lv'] == 0) {
              $parent_level = $v['satker_parent_id'];
          } else {
              $parent_level = substr($v['satker_level'], 0, strrpos($v['satker_level'], '.'));
          }
          
          if(!isset($pos_all[$parent_level])) {
              $pos_all[$parent_level] = $k;
              $pos_child[$parent_level] = $k;
              $position = $k;
          } else {
              $position = ++$pos_child[$parent_level];
          }
          if(isset($combo[$position])) {
              foreach($pos_all as $pk => $pv) {
                  if($pos_all[$pk] >= $position) {
                      if($pk != $parent_level) {
                        $pos_all[$pk]++;
                      }
                      $pos_child[$pk]++;
                  }
              }
          }
          $pos_all[$v['satker_level']] = $position;
          $pos_child[$v['satker_level']] = $position;
          /* $v['space'] = str_repeat('&nbsp;', max($v['lv'] - $str_lvl_offset, 0) * 4);
          $v['satker'] = $v['space'] . $v['satker_nama'];
          $v['jabatan'] = $v['space'] . $v['jabatan_nama']; */
          $v['sub_list'] = array();
          array_splice($combo, $position, 0, array($v));
          if($v['sub_total'] > 0) {
              $query = $this->mSqlQueries['get_sub_data'];
              $query = str_replace('--unit_satker--', $v['satker_id'], $query);
              $query = str_replace('--peg_list--', $v['sub_ids'], $query);
              $result = $this->Open($query, array());
              if($result) {
                  $subs[$v['satker_level']] = $result;
                  // $v['sub_list'] = $result;
                  if(isset($combo[$position])) {
                      // $pos_child[$parent_level] += $v['sub_total'];
                      $position = $pos_child[$v['satker_level']] + 1;
                      foreach($pos_child as $pk => $pv) {
                          if($pos_child[$pk] >= $pos_child[$v['satker_level']] && $pk != $v['satker_level']) {
                              $pos_child[$pk] += count($result) + 1;
                          }
                          if($pos_all[$pk] >= $position) {
                              $pos_all[$pk] += count($result) + 1;
                          }
                      }
                  }
                  array_splice($combo, $position, 0, $result);
              }
          }
      }
      
      /* $offset = 0;
      foreach($subs as $level => $sub) {
          array_splice($combo, $pos_child[$level] + 1 + $offset, 0, $sub);
          $offset += count($sub);
      } */
      // echo "<pre>"; var_dump($combo, $pos_child); echo "</pre>";
      return $combo;
   }
   
   
   
   function IndonesianDate($StrDate, $StrFormat)
	 {
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day." Januari ".$Year;
						break;
			case "02" :	$StrResult = $Day." Febuari ".$Year;
						break;
			case "03" :	$StrResult = $Day." Maret ".$Year;
						break;
			case "04" :	$StrResult = $Day." April ".$Year;
						break;
			case "05" :	$StrResult = $Day." Mei ".$Year;
						break;
			case "06" :	$StrResult = $Day." Juni ".$Year;
						break;
			case "07" :	$StrResult = $Day." Juli ".$Year;
						break;
			case "08" :	$StrResult = $Day." Agustus ".$Year;
						break;
			case "09" :	$StrResult = $Day." September ".$Year;
						break;
			case "10" :	$StrResult = $Day." Oktober ".$Year;
						break;
			case "11" :	$StrResult = $Day." November ".$Year;
						break;
			case "12" :	$StrResult = $Day." Desember ".$Year;
						break;
		} //end switch
		return $StrResult;
	}
	
	function IndonesianDate2($StrDate, $StrFormat)
	 {
		$StrFormat = strtoupper($StrFormat);
		switch ($StrFormat)
		{
			case "MM-DD-YYYY" :	list($Month, $Day, $Year) = explode("-", $StrDate);
								break;
			case "DD-MM-YYYY" :	list($Day, $Month, $Year) = explode("-", $StrDate);
								break;
			case "YYYY-MM-DD" :	list($Year, $Month, $Day) = explode("-", $StrDate);
								break;
			case "MM/DD/YYYY" :	list($Month, $Day, $Year) = explode("/", $StrDate);
								break;
			case "DD/MM/YYYY" :	list($Day, $Month, $Year) = explode("/", $StrDate);
								break;
			case "YYYY/MM/DD" :	list($Year, $Month, $Day) = explode("/", $StrDate);
								break;
		}//End switch

		switch ($Month)
		{
			case "01" :	$StrResult = $Day."-Jan-".$Year;
						break;
			case "02" :	$StrResult = $Day."-Feb-".$Year;
						break;
			case "03" :	$StrResult = $Day."-Mar-".$Year;
						break;
			case "04" :	$StrResult = $Day."-Apr-".$Year;
						break;
			case "05" :	$StrResult = $Day."-May-".$Year;
						break;
			case "06" :	$StrResult = $Day."-Jun-".$Year;
						break;
			case "07" :	$StrResult = $Day."-Jul-".$Year;
						break;
			case "08" :	$StrResult = $Day."-Aug-".$Year;
						break;
			case "09" :	$StrResult = $Day."-Sep-".$Year;
						break;
			case "10" :	$StrResult = $Day."-Okt-".$Year;
						break;
			case "11" :	$StrResult = $Day."-Nov-".$Year;
						break;
			case "12" :	$StrResult = $Day."-Des-".$Year;
						break;
		} //end switch
		return $StrResult;
	}
	
	function GetBulan($Month)
	 {  
	    
			if (($Month=='1')) return "Januari ";
			if (($Month=='2')) return "Febuari ";
			if (($Month=='3')) return "Maret ";
			if (($Month=='4')) return "April ";
		  if (($Month=='5')) return "Mei ";
			if (($Month=='6')) return "Juni ";
			if (($Month=='7')) return "Juli ";
		  if (($Month=='8')) return "Agustus ";
			if (($Month=='9')) return "September ";
			if (($Month=='10')) return "Oktober ";
			if (($Month=='11')) return "November ";
			if (($Month=='12')) return "Desember ";
			
			return "";
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
   
   function num_todisplay($num, $dfixed=true, $ddec=2) {
      // ex :  2980.87 -> 2.980,87
      if (is_numeric($num)) {
         $check = explode(".", $num);
         $dec = (isset($check[1])) ? strlen($check[1]) : 0;
         if ($dfixed == true) $dec = $ddec;
         $num = number_format($num, $dec, ',', '.');
      }
      return $num;
   }
}
?>
