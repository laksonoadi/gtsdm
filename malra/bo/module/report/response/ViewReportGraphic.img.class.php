<?php
/**
 * Class ViewLapJumMhsKelamin
 * Class menampilkan laporan jumlah mahasiswa baru berdasarkan jenis kelamin
 *
 * @package fo_mhs_baru
 * @author Choirul Ihwan
 * @version 1.0
 * @copyright Copyright (c) 2006 GamaTechno
 * @date 29 Sept 2006
 * @revision 0
 *
 */

require_once $this->mrConfig->mApplication['docroot'] . 'module/report/business/Report.class.php';
require $this->mrConfig->mApplication['docroot'] . 'module/additional_lib/ImgHelper.class.php';

class ViewReportGraphic extends ImgResponse {
   
   function ProcessRequest() {

//data tabel
function formatDataTable($array, $header){
   $awal =  1;
   
   for($i=0;$i<count($array);$i++):
       for($j=0; $j<count($header); $j++):
         if($header[$j] == $array[$i]['TAHUN'])
            $array[$i][$array[$i]['TAHUN']] = $array[$i]['JUMLAH'];
         else
            $array[$i][$header[$j]] = 0;
       endfor;
       
       unset($array[$i]['TAHUN']);
       unset($array[$i]['JUMLAH']);     
   endfor;     
   
        
   $k = 0;
   $jumlah_kanan = 0;

   for($j=0;$j<count($array); $j++){
      $arrData[$k]['UNIT_ID'] = $array[$j]['UNIT_ID'];
      $arrData[$k]['UNIT KERJA'] = $array[$j]['UNIT'];
      
      $l = 0;
      $arrData[$k][0] = 0;
      for($i=1; $i<=count($header); $i++):
         $arrData[$k][$i] = $array[$j][$header[$i-1]];        
      endfor;

      if($k>0 && $arrData[$k]['UNIT_ID'] != null):
         if($arrData[$k]['UNIT_ID'] == $arrData[$k-1]['UNIT_ID']):
            for($i=1; $i<=count($header); $i++):
               $arrData[$k][$i] += $arrData[$k-1][$i];
            endfor;
            
            unset($arrData[$k-1]);
            $arrData[$k-1] = $arrData[$k];
            unset($arrData[$k]);
            $k--;         
         endif;
      endif;
      
      $k++;      
   }
  
   for($j=0;$j<count($arrData); $j++){
      $jumlah_kanan = 0;
      
      unset($arrData[$j]['UNIT_ID']);
      
      for($i=1; $i<=count($header); $i++):
         $jumlah_kanan += $arrData[$j][$i];
         $arrData[$j][0] = $jumlah_kanan;
      endfor;
   }

   $result['arrData'] = $arrData;
   
   return $result;   
}
   
//query untuk ref
$rentang = $this->GetSetting('rentang');
$awal = (date('Y')-$rentang) + 1;
$judul .= '<br/>Tahun '.$awal.' s.d. '.date('Y');

for($i=0;$i<$rentang;$i++){
   $arrayHeader[$i] = $awal;
   $awal++;
}

$rep = new Report();
//query laporan
//[[query18 : 118 : Kepegawaian => Jumlah Pegawai Pensiu]]//
$query18118 = $rep->GetQueryById(118);

$gabTahun = join(",", $arrayHeader);

//format
$rep = new Report(18);
$array = $rep->RunQuery($query18118['query_sql'], array($gabTahun));

$arrData = formatDataTable($array, $arrayHeader);

//for graphic
   $thn_awal = (date('Y')-$rentang)+1;
      for($i=0;$i<$rentang;$i++):
         $label[] = $thn_awal;
         $thn_awal++;
      endfor;

for($i=0;$i<count($arrData['arrData']);$i++){ 
   if($arrData['arrData'][$i][0] != 0){      
      for($j=0;$j<count($label);$j++){         
         $data['tabel'][$i][$label[$j]] = $arrData['arrData'][$i][$j+1];
      }
      $arrFak[] = $arrData['arrData'][$i]['UNIT KERJA'];
   }
}

foreach($data['tabel'] as $value){
   $arrResult[] = $value;
}

$pie = new ImgHelper($arrResult, $legend, $arrFak, $legend_label);
$pie->createLineGraphSingleDatabase($label);


   }
}

?>