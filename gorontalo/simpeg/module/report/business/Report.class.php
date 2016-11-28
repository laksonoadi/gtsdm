<?php

class Report extends Database {
	
	protected $mSqlFile='module/report/business/Report.sql.php';
	
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
		
	}

	function setTanggal($tanggal) {
      $bln=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus',
        'September','Oktober','November','Desember');
      $tlen = sizeof($bln);  
      $tgl=explode('-',$tanggal);
      for ($t=0;$t<$tlen;$t++) {
         if ($tgl[1]==$t+1) $tgl[1]=$bln[$t];
      }
      $tanggal=$tgl[2].' '.$tgl[1].' '.$tgl[0];
      return $tanggal;
   }

// Query   
   function GetQuery() {
     // 
      $data =  $this->GetAllDataAsArray($this->mSqlQueries['get_query'], array());
   //   
      return $data;
   }

   function GetQueryByNama($kunci, $start, $limit) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_query_by_nama'], array('%'.$kunci.'%', $start, $limit));
		return $result;
   }

   function GetTotalQuery($kunci) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_total_query'], array('%'.$kunci.'%', '%'.$kunci.'%'));
      return $result[0]['total'];
   }

   function GetQueryById($id) {

      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_query_by_id'], array($id));

      return $result[0];
   }

   function GetRetribusiById($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_retribusi_by_id'], array($id));
      return $result[0];
   }

   function DoInsertQuery($nama, $desc, $query, $param, $koneksi) {
      //$id = $this->GetId('QUERY_ID', 'REPORT_QUERY');
      //if ($id=='') $id=1;
	 // 
      $query = str_replace("'", "''", $query);
      $param = str_replace("'", "''", $param);
     return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_query'], array($nama, $desc, $query, $param, $koneksi));
	// 
   }

   function DoUpdateQuery($nama, $desc, $query, $param, $koneksi, $id) {
   //
      $query = str_replace("'", "''", $query);
      $param = str_replace("'", "''", $param);
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_query'], array($nama, $desc, $query, $param, $koneksi, $id));
	 // 
   }

   function DoDeleteQuery($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_query'], array($id));
   }

   function ShowTables() {
      return $this->GetAllDataAsArray($this->mSqlQueries['show_tables'], array());
   }

   function ShowColumsTables($table) {
      return $this->GetAllDataAsArray($this->mSqlQueries['show_colums_tables'], array($table));
   }

   function RunQuery($query, $param) {
      return $this->GetAllDataAsArray($query, $param);
   }

//=======Tabel

   function GetTable() {
		
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_table'], array());
		
		return $result;
   }

   function GetTableByNama($kunci, $start, $limit) {
      return $this->GetAllDataAsArray($this->mSqlQueries['get_table_by_nama'], array('%'.$kunci.'%', $start, $limit));
   }

   function GetTotalTable($kunci) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_total_table'], array('%'.$kunci.'%'));
      return $result[0]['total'];
   }

   function GetTableById($id) {
		
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_table_by_id'], array($id));
		
      return $result[0];
   }

   function DoInsertTable($nama, $phpCode, $isGraphic, $param) {
   //
      //$id = $this->GetId('TABLE_ID', 'REPORT_TABLE');
      //if ($id=='') $id=1;
      $phpCode = str_replace("'", "''", $phpCode);
      $param_code = str_replace("'", "''", $param);
	
      $result = $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_table'], array($nama, $phpCode, $isGraphic, $param_code));
      
      
      return $result;      
   }

   function DoUpdateTable($nama, $phpCode, $isGraphic, $param, $id) {
   //
      $phpCode = str_replace("'", "''", $phpCode);
      $param_code = str_replace("'", "''", $param);
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_table'], array($nama, $phpCode, $isGraphic, $param_code, $id));
      //     
      
      return $result;
   }

   function DoDeleteTable($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_table'], array($id));
   }
   
//====
   
   function HeaderTableForRunQuery($array) {
      if (isset($array[0])) {
          //if($_SESSION['user_level_id'] != 1 && $array[0]['FAKULTAS'] != '')
            //unset($array[0]['FAKULTAS']);

         $kolom = array_keys($array[0]); 
         $header = '<table class="table-common">
           <tr>';
         for ($i=0;$i<sizeof($kolom);$i++) {
            $header .= '<th> '.$kolom[$i].' </th>';
         }
         $header .= '</tr>';
      }
      return $header;
   }

   function HeaderTable($array, $hidden = NULL, $width = NULL) {
      if (isset($array[0][0])) {
         $data = '<table width="100%" class="table-common" border="1" cellpadding="0" cellspacing="0">';
         for ($i=0;$i<sizeof($array);$i++) {
            $data .= '<tr align="center" bgcolor="#CCCCCC">';
            for ($j=0;$j<sizeof($array[$i]);$j++) {
               if ($array[$i][$j]!=$array[$i-1][$j]) {
                  $a = $b = 0;
                  $row = $col = '';
                  if ($array[$i][$j]==$array[$i+1][$j] and $i!=sizeof($array)-1) {
                     for ($k=0;$k<sizeof($array);$k++) {
                        if ($array[$i][$j]==$array[$i+$k][$j]) $a++;
                        else break;
                     }
                     $row = ' rowspan="'.$a.'"';
                  }
                  if ($array[$i][$j]==$array[$i][$j+1] and $j!=sizeof($array[$i])-1) {
                     for ($k=0;$k<sizeof($array[$i])-$j;$k++) {
                        if ($array[$i][$j]==$array[$i][$j+$k]) $b++;
                        else break;
                     }
                     $col = ' colspan="'.$b.'"';
                  }
                  if($hidden[$j] != 'ya')
                     $data .='<th width="'.$width[$j].'" '.$row.$col.'>'.$array[$i][$j].'</th>';
                  if ($b!=0) $j += ($b-1);
               }
            }
            $data .= '</tr>';
         }
      } else $data = 'Header Tidak Valid';
      return $data;      
   }
   
   function DetailTable($arrayHeader, $arrayData) {
      if (isset($arrayHeader[0][0])) {         
         $data = '<table width="100%" class="table-details">';
         foreach($arrayHeader[0] as $key=>$value){
            $data.='<tr>
                        <td>'.$value.'</td>
                        <td>:</td>
                        <td>'.$arrayData[0][$key].'</td>
                   </tr>';
         }
         
      } else $data = 'Header Tidak Valid';
      return $data;      
   }

   function DataTable($array, $align=NULL, $width=NULL, $format=NULL, $separator=NULL, $rowspan=NULL, $link=NULL, 
      $url_link=NULL, $hidden=NULL) {
      if (isset($array[0])) {
         $kolom = array_keys($array[0]); 
         $GLOBALS['jum_kolom'] = count(array_keys($array[0]));

         for ($i=0;$i<sizeof($array);$i++) {
            if ($i%2==0) $class = 'table-common-even'; else $class = '';
            $data .= '<tr class="'.$class.'">';
            for ($j=0;$j<sizeof($kolom);$j++) {
               $nilai = $array[$i][$kolom[$j]];
               if ($format[$j]=='uang') $nilai = $this->FormatCurrency($array[$i][$kolom[$j]]);
               elseif ($separator[$j]=='ya') $nilai = number_format($array[$i][$kolom[$j]], 0, ',', '.');

               //add for link
               if($url_link[$i][$j] != ''):
                  $tag_link_open = '<a class="xhr dest_subcontent-element" href="'.$url_link[$i][$j].'">';
                  $tag_link_close = '</a>';
               else:
                  $tag_link_open = '';
                  $tag_link_close = '';
               endif;
               
               if ($rowspan[$j]=='ya') {
                  if ($array[$i][$kolom[$j]]!=$array[$i-1][$kolom[$j]]) {
                     $a = 0;
                     for ($k=0;$k<sizeof($array);$k++) {
                        if ($array[$i][$kolom[$j]]==$array[$k][$kolom[$j]]) $a++;
                     }
                     $data .= '<td rowspan="'.$a.'" width="'.$width[$j].'" align="'.$align[$j].'">'.$tag_link_open.$nilai.$tag_link_close.'</td>';
                  }
               } 
               else 
                  if($hidden[$j] != 'ya')
                     $data .= '<td width="'.$width[$j].'" align="'.$align[$j].'">'.$tag_link_open.$nilai.$tag_link_close.'</td>';

             
            }
            $data .= '</tr>';
         }
         $data .=  '</tr>';
      } else $data = 'Data tidak ditemukan';
      return $data;
   }
   
   function DataList($array, $url = "", $urlhover=""){      
      if(!empty($array)){
         $data = "<ul>";
         foreach($array as $value){
            if($url == "")
               $data .= "<li>".$value['data']."</li>";
            else{
               
               //below is using baloon
               /*$code = "<a id='mynewanchor' class='xhr dest_subcontent-element' href='".$url."'>".$value['data']."</a>
                        <script type='text/javascript'>
                           var hb4 = new HelpBalloon({
                              dataURL: '".$urlhover.'&pid='.$value['id']."',
                              icon: $('mynewanchor'),
                              balloonDimensions: [1200, 200],
                              useEvent: ['mouseover']                              
                           });
                        </script>"*/
                       ;
               //end using balloon
               
               $urlhover = $urlhover.'&pid='.$value['id'];               
               $code = "<a href=\"#\" onclick=\"javascript:showPopup('".$urlhover."', '', 400, 500);\">".$value['data']."</a>";               
               $data .= "<li>".$code."</li>";
               
            }
         }
         $data .= "</ul>";
         
         return $data;
      }
   }

   function FooterTable($array=NULL, $colspan=NULL, $separator=NULL, $align=NULL, $format=NULL, $hidden=NULL) {
      if (isset($array)) {
         $kolom = array_keys($array); 
         $data .= '<tr class="table-common-even" bgcolor="#CCCCCC">';
         for ($i=0;$i<sizeof($array);$i++) {
            $col = '';
            $nilai = $array[$kolom[$i]];
            if ($colspan[$i]!='' or $colspan[$i]!=0) $col = ' colspan="'.$colspan[$i].'"';
            if ($separator[$i]=='ya') $nilai = $this->FormatSeparator($array[$kolom[$i]]);
            if ($format[$i]=='uang') $nilai = $this->FormatCurrency($array[$kolom[$i]]);
            if($hidden[$i] != 'ya') $data .='<td '.$col.' align="'.$align[$i].'"><b>'.$nilai.'</b></td>';
         }
         $data .= '</tr>';
      } else $data = 'Footer tidak valid';
      return $data;
   }

   function CloseTable() {
      $footer = '</table>';
      return $footer;
   }

   function EmptyTable() {
      return '
         <div class="notebox-alert">
            Data Statistik Tidak Ditemukan
         </div>';
   }

   function SetGrafikLine() {
      $ydata  = array(11,3, 8,12,5 ,1,9, 13,5,7 );
      
      // Create the graph. These two calls are always required
      $graph  = new Graph(350, 250,"auto");    
      $graph->SetScale( "textlin");
      
      // Create the linear plot
      $lineplot =new LinePlot($ydata);
      $lineplot ->SetColor("blue");
      
      // Add the plot to the graph
      $graph->Add( $lineplot);
      
      // Display the graph
      $graph->Stroke();
    }

   function SetGrafik($type, $panjang, $lebar, $pieData, $pieLegend) {
      if ($type == "pie") {
         $graph = new PieGraph($panjang,$lebar,"auto");
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
      } elseif ($gtype == "garis") {
      
      } else {
         $graph = new Graph($panjang,$lebar,"auto");
         
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
   
//=======

   function GetLayout() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_layout'], array());
		return $result;
   }

   function GetLayoutByNama($kunci, $start, $limit) {
      return $this->GetAllDataAsArray($this->mSqlQueries['get_layout_by_nama'], array('%'.$kunci.'%', $start, $limit));
   }

   function GetTotalLayout($kunci) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_total_layout'], array('%'.$kunci.'%', '%'.$kunci.'%'));
      return $result[0]['total'];
   }

   function GetLayoutById($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_layout_by_id'], array($id));
      return $result[0];
   }

   function GetBridgeById($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_bridge_by_id'], array($id));
      return $result[0];
   }

   function DoInsertLayout($judul, $layout, $menuId) {
      //$id = $this->GetId('layout_id', 'report_layout');
	  
      //if ($id=='') $id=1;      
       return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_layout'], array($judul, $layout, $menuId));
	 //
   }

   function DoUpdateLayout($judul, $template, $id) {
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_layout'], array($judul, $template, $id));
   }

   function DoDeleteLayout($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_layout'], array($id));
   }
   
   function DoDeleteMenu($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_menu'], array($id));
   }

   function GetSubMenu() {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_sub_menu'], array());
		
		return $result;
   }

   function GetSubMenuById($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_sub_menu_by_id'], array($id));
      return $result[0];
   }

   function DoInsertMenu($parentId, $nama, $ikon, $urutan) {
      //$id = $this->GetId('DUMMY_ID', 'REPORT_DUMMY_MENU');
      //if ($id=='') $id=1;      
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_menu'], array($parentId, $nama, $ikon, $urutan));
   }     

   function DoInsertBridge() {
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_bridge'], array());
   }     

   function DoUpdateMenu($parent, $nama, $icon, $order, $id) {
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_menu'], array($parent, $nama, $icon, $order, $id));
   }     
   
	function FormatCurrency($nilai) {
		$formatted = sprintf("%01.2f", $nilai);
		$koma	   = str_replace(".", ",", $formatted);

		$nilai_x  = explode(",", $koma);
		$dpn_koma = $nilai_x[0];
		if ($dpn_koma < 0) {
			$sign	  = '-';
			$dpn_koma = substr($dpn_koma, 1);
		}
		else {
			$sign = '';
		}

		$pj_nilai = strlen($dpn_koma);

		if ($pj_nilai > 3) {
			$pj_depan_koma = $pj_nilai;

			$blk_koma = $nilai_x[1];

			$pj_awal_depan_koma  = $pj_depan_koma % 3;
			$pj_akhir_depan_koma = $pj_depan_koma - $pj_awal_depan_koma;

			$awal_depan_koma  = substr($dpn_koma, 0, $pj_awal_depan_koma);
			$akhir_depan_koma = substr($dpn_koma, $pj_awal_depan_koma, $pj_akhir_depan_koma);

			if ($awal_depan_koma <> '') {
				$bil .= $awal_depan_koma . ".";
			}
			else {
				$bil .= '';
			}

			$jml_ttk_akhir_depan_koma = $pj_akhir_depan_koma / 3;

			for ($i = 0; $i < $jml_ttk_akhir_depan_koma; $i++) {
				$awal = $i * 3;
				$akhir_depan_koma_ke[$i] = substr($akhir_depan_koma, $awal, 3);
			}

			for ($i = 0; $i < $jml_ttk_akhir_depan_koma; $i++) {
				if ($i <> ($jml_ttk_akhir_depan_koma - 1)) {
					$bil .= $akhir_depan_koma_ke[$i] . ".";
				}
				else {
					$bil .= $akhir_depan_koma_ke[$i];
				}
			}

			$bil .= "," . $blk_koma;
		}
		else {
			$bil = $koma;
		}

		$bil = $sign . $bil;

		return $bil;
	}
   function FormatSeparator($nilai) {
      $nilai = $this->FormatCurrency($nilai);
      return substr($nilai, 0, strlen($nilai)-3); 
   }

//=== graphic

   function GetGraphicByNama($kunci, $start, $limit) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_graphic_by_nama'], array('%'.$kunci.'%', $start, $limit));
		return $result;
   }

   function GetTotalGraphic($kunci) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_total_graphic'], array('%'.$kunci.'%'));
      return $result[0]['total'];
   }

   function GetGraphicById($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_graphic_by_id'], array($id));
      return $result[0];
   }

   function DoInsertGraphic($judul, $tableId, $layoutId) {
     // $id = $this->GetId('GRAPHIC_ID', 'REPORT_GRAPHIC');
      //if ($id=='') $id=1;      
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_graphic'], array($judul, $tableId, $layoutId));
   }     

   function DoUpdateGraphic($judul, $tableId, $layoutId, $id) {
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_graphic'], array($judul, $tableId, $layoutId, $id));
   }     

   function DoDeleteGraphic($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_graphic'], array($id));
   }

//added by choirul to form array header
//header sia
   function FormatHeaderTableSiaWithProdi($arrField, $jml_baris = 1){
      if($_SESSION['user_level_id'] != '1'):
         unset($arrField[0]);
         $j = 0;
         for($i=1;$i<=count($arrField);$i++):
            $arrData[$j] = $arrField[$i];
            $j++;
         endfor;
         $arrHeader[0] = $arrData;
      
      else:
         for($i=$awal;$i<$jml_baris;$i++):
            $arrHeader[0] = $arrField;
         endfor;
      endif;
      
      return $arrHeader;
   }

   function formatDataTableSiaWithProdi($array, $col){   
      for($i=0; $i<count($array); $i++):
         if($_SESSION['user_level_id'] != '1'):
            unset($array[$i]['fakultas']);
            $k = 0;
            for($j=1;$j<count($col);$j++):
               $arrData[$i][$k] = $array[$i][$col[$j]];
               $k++;
            endfor;
            
         else:
            
            for($j=0;$j<count($col);$j++):
               if(is_null($array[$i][$col[$j]]) || trim($array[$i][$col[$j]]) == '')
                  $arrData[$i][$j] = '-';
               else
                  $arrData[$i][$j] = $array[$i][$col[$j]];
            endfor;
         endif;
   
      endfor; 
      
      return $arrData;
   }
   
   //Parameter

   function CountParam($name) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['count_param'], array('%'.$name.'%'));
      return $result[0]['total'];
   }   
   
   function ListParam($name, $start, $limit) {
      return $this->GetAllDataAsArray($this->mSqlQueries['list_param'], array('%'.$name.'%', $start, $limit));
   }

   function GetParamById($id) {
      return $this->GetAllDataAsArray($this->mSqlQueries['get_param_by_id'], array($id));
   }
   
   function DoInsertParam($nama, $jenis, $php) {
   
      //$id = $this->GetId('PARAM_ID', 'REPORT_PARAM');
      //if ($id=='') $id=1;
      $php = str_replace("'", "''", $php);
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_param'], array($nama, $jenis, $php));
	 // 
   }     

   function DoUpdateParam($nama, $jenis, $php, $id) {
      $php = str_replace("'", "''", $php);
      return $this->ExecuteUpdateQuery($this->mSqlQueries['do_update_param'], array($nama, $jenis, $php, $id));
   }     

   function DoDeleteParam($id) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_param'], array($id));
   }
   
   function SetDropdown($nama, $value, $nilai, $select, $js='',$all='') {
      $data = '<select '.$js.' name="'.$nama.'">';
      if ($all==1) $data .= '<option>';
      for ($i=0;$i<sizeof($value);$i++) {
         if ($value[$i] == $select) $selected = 'selected'; else $selected = '';
         $data .= '<option value="'.$value[$i].'" '.$selected.'>'.$nilai[$i];
      }
      $data .= '</select>';
      return $data;
   }
   
   function SetInputText($nama, $value, $size=25, $type='text', $check='') {
      return '<input name="'.$nama.'" value="'.$value.'" size="'.$size.'" type="'.$type.'" '.$check.'>';
   }

   function NilaiDropdown($value, $nilai, $select) {
      for ($i=0;$i<sizeof($value);$i++) {
         if ($value[$i] == $select) {
            $nSelected = $nilai[$i];
            break;
         }
      }
      return $nSelected;
   }

   function SetRadio($nama, $value, $nilai, $check, $js='') {
      for ($i=0;$i<sizeof($value);$i++) {
         if ($value[$i]==$check) $checked = 'checked'; else $checked = '';
         $data .= '<input '.$js[$i].' type="radio" name="'.$nama.'" value="'.$value[$i].'" '.$checked.'>'.$nilai[$i];
      }
      return $data;
   }

   function DoInsertDummyModule($menuId, $moduleId) {
//      $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_dummy_module'], array($menuId, 504));
  //    $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_dummy_module'], array($menuId, 517));
//  printf($this->mSqlQueries['do_insert_dummy_module'], $menuId, $moduleId);exit;
      return $this->ExecuteInsertQuery($this->mSqlQueries['do_insert_dummy_module'], array($menuId, $moduleId));//518));
   }     

   function DoDeleteDummyModule($menuId, $moduleId) {
      return $this->ExecuteDeleteQuery($this->mSqlQueries['do_delete_dummy_module'], array($menuId, $moduleId));
   }
   
   function headerFilter() {
      return '
         <form name="frmSearch" method="post" action="'.$_SERVER['REQUEST_URI'].'" 
            class="dataquest xhr_simple_form dest_subcontent-element" id="filterbox">
         <table id="tabel"> 
            <tbody>';
   }

   function bodyFilter($label, $filter, $js='', $width='25%', $class='') {
      return '
         <tr class="'.$class.'" '.$js.'>
            <th width="'.$width.'">'.$label.'</th>
            <th>'.$filter.'</th>
         </tr>';
   }

   function BodyFilterArray($array, $width=NULL, $js='', $class='',$colspan=NULL,$align=NULL) {
      for ($i=0;$i<sizeof($array);$i++) {
         $data .= '<th nowrap colspan='.$colspan[$i].' width="'.$width[$i].'" style="text-align:'.$align[$i].'">'.$array[$i].'</th>';
      }
      return '
         <tr class="'.$class.'" '.$js.'>
            '.$data.'
         </tr>';
   }

   function BodyHeaderFilter($label,$colspan=2) {
      return '<tr class="subhead">
                  <th colspan="'.$colspan.'">'.$label.'</th>
               </tr>';
   }

   function footerFilter() {
      return '        
         <tr>
            <td>&nbsp;</td>
            <td>
               <input name="btncari" value=" Tampilkan &raquo;" class="buttonSubmit" type="submit">
            </td>
         </tr>
      </tbody></table></form><br>';
   }
   
   function Grafik($link) {
      return '<img src="'.$link.'" />';
   }
   
   function GetGraphicByIdLayout($id) {
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_graphic_by_id_layout'], array($id));
      return $result[0];
   }
   
   function GetBulan($blni) {
      $bln=array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
      return $bln[$blni-1];
   }

   function CekLayout($layoutId, $groupId) { //echo $layoutId.', '.$groupId;
      $result = $this->GetAllDataAsArray($this->mSqlQueries['get_menu_report_group'], array($layoutId, $groupId));
      //print_r($result);
      if (empty($result)) return FALSE; else return TRUE;
   }

   function headerRetribusi($judul='Retribusi') {
      return '
         <form method="post" action="'.$_SERVER['REQUEST_URI'].'" id="filterbox" name="form_estimasi"
            class="xhr_simple_form dest_subcontent-element">
            <table class="table-edit" width="100%">
               <tbody><tr class="subhead">
                  <th colspan="2">'.$judul.'</th>
               </tr>';
   }

   function footerRetribusi() {
      return '        
         <tr class="buttons">
                  <th>&nbsp;</th>
                  <td>
                     <input name="btncari" value=" Hitung " class="buttonSubmit" type="submit">
                  </td>
               </tr>
            </tbody></table>
         </form>';
   }

   function headerHasilRetribusi($judul='Retribusi',$colspan=2) {
      return '<br /><br />
         <h2>Perhitungan Tarif '.$judul.'</h2>
         <table class="table-details" width="100%">
            <tbody><tr class="subhead">
               <th colspan="'.$colspan.'">Data</th>
            </tr>';
   }

   function footerHasilRetribusi($izin) {
      $url = Dispatcher::Instance()->GetUrl('retribusi', 'retribusi', 'popup', 'html').'&izin='.$izin;
      return '        
         </tbody></table>
         <br>
         <div class="pageBar">
            <div class="toolbar">
               <input name="btncari" value="Set Retribusi" class="inputButton" onclick="window.open(\''.$url.'\',
                  \'\',\'height=600,resizable=yes,scrollbars=yes,width=750\'); return false;" type="button">
            </div>
         </div>';
               //<input name="btncari" value="Set Retribusi" class="inputButton" onclick="window.open(\'/sip/back/index.php?mod=retribusi&amp;sub=PemohonIjinIMB&amp;act=popup&amp;typ=html','','height=600,resizable=yes,scrollbars=yes,width=750'); return false;" type="button">
               //<a class="xhr dest_subcontent-element" href="/sip/back/index.php?mod=retribusi&amp;sub=EstimasiRetribusiIMB&amp;act=view&amp;typ=html" title="Kembali">Kembali</a>
   }

   function Retribusi($judul='Retribusi', $tarif, $rumus='') {
      //param
      $data['filter'] = $this->headerRetribusi($judul);
      $p = 'aa';
      for ($i=0;$i<sizeof($tarif);$i++) {
         if ($tarif[$i][0][0]!='') {
            $data['filter'] .= $this->bodyFilter($tarif[$i][2], $this->SetDropdown($p, $tarif[$i][0], $tarif[$i][1], 
               $_POST[$p],'',1), '', '35%');
         } else $data['filter'] .= $this->bodyFilter($tarif[$i][2], $this->SetInputText($p, $_POST[$p]));
         $p++;
      }
      $data['filter'] .= $this->footerRetribusi();
      //body
      $p = 'aa';
      if (isset($_POST['aa'])) {
         $data['tabel'] = $this->headerHasilRetribusi($judul);
         for ($i=0;$i<sizeof($tarif);$i++) {
            $data['tabel'] .= $this->bodyFilter($tarif[$i][2], $_POST[$p]->Raw());
            $total += $_POST[$p]->Raw();
            $tab[$i] = $_POST[$p]->Raw();
            $_SESSION['data_retribusi'][$i]['nilai'] = $_POST[$p]->Raw();
            $_SESSION['data_retribusi'][$i]['brt_id'] = $tarif[$i][3];
            $p++;
         }
         
         if ($rumus!='') $total = eval('return '.$rumus.';'); 
         $_SESSION['data_retribusi']['jumlah_tarif'] = $total;
         $data['tabel'] .= $this->bodyFilter('Jumlah retribusi', 'Rp '.number_format($total,2,',','.'));
         $data['tabel'] .= '</table>';
         //print_r($_SESSION['data_retribusi']);
      }

      return $data;
   }

   function GetBuilderRetribusi($izinId,$retribusiId='') {
      if ($retribusiId!='') $tarif = $this->Open($this->mSqlQueries['get_builder_retribusi_by_brji_id'], array($retribusiId));
      else $tarif = $this->Open($this->mSqlQueries['get_builder_retribusi'], array($izinId));
      $a = -1;
      for ($i=0;$i<sizeof($tarif);$i++) {
         if ($tarif[$i]['tarifid']!=$tarif[$i-1]['tarifid']) {
            $a++;
            $tariff[$a][0][] = $tarif[$i]['tarifnilai'];
            $tariff[$a][1][] = $tarif[$i]['tarif'];
            $tariff[$a][2] = $tarif[$i]['label'];
            $tariff[$a][3] = $tarif[$i]['tarifid'];
         } else {
            $tariff[$a][0][] = $tarif[$i]['tarifnilai'];
            $tariff[$a][1][] = $tarif[$i]['tarif'];
         }
      }
      return $tariff;
   }

   function SetBuilderRetribusi($retId,$izinId='') {
      $ret = $this->GetRetribusiById($retId);
      if ($izinId!='') $ret['brji_nama'] = 'Retribusi';
      $data = $this->Retribusi($ret['brji_nama'], $this->GetBuilderRetribusi($ret['brji_jenisizinid']), 
         $ret['brji_formula']);
      //print_r($ret);
      //exit;
      $data['cetak'] = 'hidden';
      $data['none'] = true;
      $data['izin'] = $ret['brji_jenisizinid'];
      if ($izinId!='') $data['izin'] = $izinId;
      return $data;
   }

   function GetInstansi() {
      require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/user/business/AppUser.class.php';
      $userObj = new AppUser();
      $daUser = $userObj->GetDataUserByUsername($_SESSION['username']);
      $dataUser = $userObj->GetDataUserById($daUser['user_id']);
      $inst = $dataUser[0]['instansi_id'];//echo $inst.'as';
      if ($inst==999) $inst='%%';
      return $inst;
   }

}
?>
