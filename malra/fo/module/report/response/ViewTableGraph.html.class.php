<?php

require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/report/business/Report.class.php';

class ViewLicenseRequestGraphic extends ImgResponse {

   function ProcessRequest() {
   

      if ($gtype == "pie") {
         if (!empty ($data) && $brd == "lamatunggu") {
            $lenData = sizeof($data);
            $loop = 0;
            for ($i=0; $i<$lenData; $i++){
               if ($data[$i]['sisa_waktu'] > 0) {
                  $temp[$loop] = $data[$i];
                  $loop++;
               }
            }
            $data = $temp;
         } 
         $keys = array_keys($data[0]);
         $pieData = array();
         $pieLegend = array();
         $len = sizeof($data);
         $appStatistic = new AppStatistic();
         for ($i=0; $i<$len; $i++){
            $pieData[$i] = $data[$i][$keys[1]];
            
            if ($keys[0]=="bulan") {
               $pieLegend[$i] = $appStatistic->GetStringBulan($data[$i][$keys[0]]);
            } else {
               $pieLegend[$i] = $data[$i][$keys[0]];
            }
         }
         
         $graph = new PieGraph(600,400,"auto");
         $graph->SetShadow();
         
         $p1 = new PiePlot3D($pieData);
         $p1->SetLegends($pieLegend);
         $p1->SetTheme("earth");
         $p1->SetCenter(0.28);
         $p1->SetSize(0.5);
         $p1->SetHeight(2);
         $p1->SetAngle(45);
         $p1->Explode(array(0,20,0,30));
         
         $graph->legend->Pos(0.02,0.02);
         $graph->Add($p1);
      } else {
         $keys = array_keys($data[0]);
         $graph = new Graph(600,400,"auto");
         
         $markType = array(MARK_SQUARE, MARK_UTRIANGLE, MARK_DTRIANGLE, MARK_DIAMOND, MARK_FILLEDCIRCLE, MARK_CROSS, MARK_STAR, MARK_X);
         $colorType = array('red', 'green', 'blue', 'orange', 'yellow', 'black', "cyan", "magenta");
         
         
         $len = sizeof($data);
         $loopMark = 0;
         $loopColor = 0;
         for ($i=0; $i<$len; $i++) {
            $yData= array($data[$i]['jan'], $data[$i]['feb'], $data[$i]['mar'], $data[$i]['apr'],$data[$i]['mei'],
               $data[$i]['jun'],$data[$i]['jul'],$data[$i]['agus'],$data[$i]['sept'],$data[$i]['okt'],
               $data[$i]['nov'], $data[$i]['des']);
            $p[$i] = new LinePlot($yData);
            $p[$i]->mark->SetType($markType[$loopMark]);
            $p[$i]->mark->SetFillColor($colorType[$loopColor]);
            $p[$i]->mark->SetWidth(4);
            $p[$i]->SetColor($colorType[$loopColor]);
            $strlegend = str_replace(array('PERIJINAN', 'PERIZINAN', 'IZIN', 'IJIN'), '', strtoupper($data[$i][$keys[0]]));
            $p[$i]->SetLegend($strlegend);
            $p[$i]->SetCenter();
            $graph->Add($p[$i]);
            $loopColor++;
            if ($loopColor == 8){
               $loopColor = 0;
               $loopMark++;
            }
         }
         $graph->SetScale("textlin");
         $graph->xaxis->title->Set("Bulan");
         $graph->xaxis->SetTickLabels(array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', 'Nov', 'Des'));
         $graph->yaxis->title->Set("Total");
         $graph ->legend->Pos( 0.01,0.4,"right" ,"center");     
         $graph->img->SetMargin(40,200,20,40);
      }
      $graph->legend->SetFont(FF_FONT1,FS_NORMAL, '1');
      $graph->Stroke();
      
   }

}
?>
