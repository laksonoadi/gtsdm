<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/komponen_gaji/business/AppGaji.class.php';

class ViewExcelGaji extends XlsResponse {
   var $mWorksheets = array('Sample Format Data', 'Referensi Pengisian', 'Referensi Unit Kerja');
   
   //function __construct() {
   //}
/*
   function ViewExcelGaji() {
      $this->mWorksheets[] = 'Sheet3';
      $this->mWorksheets[] = 'Sheet4';
      //return $this->__construct();
   }
*/

   function GetFileName() {
      // name it whatever you want
      return 'EksportKomponenGaji.xls';
   }

   function ProcessRequest() {
		$Obj = new AppGaji();
      $sheet1 = $Obj->GetDataSheet1();
      $sheet2 = $Obj->GetDataSheet2();
      $sheet3 = $Obj->GetDataSheet3();

//print_r($sheet3);
      if (empty($sheet1)) {
         $this->mWorksheets['Sample Format Data']->write(0, 0, 'Data kosong');
      } else {
		   $fHeader= $this->mrWorkbook->add_format();
		   $fHeader->set_bold();
         $fHeader->set_size(14);
         $fHeader->set_align('vcenter');

		   $fTitle = $this->mrWorkbook->add_format();
		   $fTitle->set_bold();
         $fTitle->set_size(12);
         $fTitle->set_align('vcenter');

		   $fColHeader = $this->mrWorkbook->add_format();
         $fColHeader->set_border(1);
		   $fColHeader->set_bold();
         $fColHeader->set_size(10);
         $fColHeader->set_align('center');
		
		   $fColData = $this->mrWorkbook->add_format();
         $fColData->set_border(1);

//SHEET 1
         $header = array(
            "No",   
            "NIP",
            "NIDN",
            "No. Induk Dosen",
            "Unit",
            "Nama",
            "Alamat",
            "No. HP",
            "No. Telepon",
            "Status",
         );
         $no=0;
         for($i=0;$i<sizeof($header);$i++) {
            $this->mWorksheets['Sample Format Data']->write($no, $i, $header[$i], $fColHeader);
         }
         //sheet1 komponen2-e sko db, ben lengkap, hohoho, tapi namane gur dumi
         $sid = "";
         $detil_kode = array();
         $kolom=10;
         for($i=0;$i<sizeof($sheet2);$i++) {
            $detil_kode[$sheet2[$i]['id']][] = $sheet2[$i]['detil_kode'];
            if($sheet2[$i]['id'] != $sid) {
               //parent
               $this->mWorksheets['Sample Format Data']->write($no, $kolom, $sheet2[$i]['nama'], $fColHeader);
               for($j=0;$j<sizeof($detil_kode[$sheet2[$i]['id']]);$j++) {
                  $this->mWorksheets['Sample Format Data']->write(($no+$j+1), $kolom, $detil_kode[$sheet2[$i]['id']][$j], $fColData);
               }
               $sid = $sheet2[$i]['id'];
               $kolom++;
            }
         }

         $no=1; $x=1;
         for($i=0;$i<sizeof($sheet1);$i++) {
            $this->mWorksheets['Sample Format Data']->write($no, 0, $x, $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 1, $sheet1[$i]['nip'], $fColData);
            //$this->mWorksheets['Sample Format Data']->write($no, , $sheet1[$i]['status_dosen'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 2, $sheet1[$i]['nidn'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 3, $sheet1[$i]['no_induk_dosen'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 4, $sheet1[$i]['unitkerja'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 5, $sheet1[$i]['nama'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 6, $sheet1[$i]['alamat'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 7, $sheet1[$i]['hp'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 8, $sheet1[$i]['telp'], $fColData);
            $this->mWorksheets['Sample Format Data']->write($no, 9, $sheet1[$i]['status'], $fColData);
            $no++;$x++;
         }

//SHEET 2
//print_r($sheet2);
         $this->mWorksheets['Referensi Pengisian']->write(1, 2, 'Data Komponen Gaji', $fHeader);
         $no = 2;
         for($i=0;$i<sizeof($sheet2);) {

            if($sheet2[$i]['kode'] == $kode) {
               //sub
               $this->mWorksheets['Referensi Pengisian']->write($no, 0, $nomor, $fColData);
               $this->mWorksheets['Referensi Pengisian']->write($no, 1, $sheet2[$i]['detil_kode'], $fColData);
               $this->mWorksheets['Referensi Pengisian']->write($no, 2, $sheet2[$i]['detil_nama'], $fColData);
               //$this->mWorksheets['Referensi Pengisian']->write($no, 3, "", $fColData);
               //$this->mWorksheets['Referensi Pengisian']->write($no, 4, "", $fColData);
               $i++;$nomor++;
            } else {
               //parent
               $no++;$no++;
               $nomor = 1;
               $kode = $sheet2[$i]['kode'];
               $this->mWorksheets['Referensi Pengisian']->write($no, 0, $sheet2[$i]['nama'] . "(" . $sheet2[$i]['kode']. ")", $fTitle);
               /*
               $this->mWorksheets['Referensi Pengisian']->write($no, 1, $sheet2[$i]['kode'], $fColData);
               $this->mWorksheets['Referensi Pengisian']->write($no, 2, $sheet2[$i]['nama'], $fColData);
               $this->mWorksheets['Referensi Pengisian']->write($no, 3, $sheet2[$i]['keterangan'], $fColData);
               $this->mWorksheets['Referensi Pengisian']->write($no, 4, $sheet2[$i]['jenis'], $fColData);
               */
               $no++;
               $this->mWorksheets['Referensi Pengisian']->write($no, 0, 'No', $fColHeader);
               $this->mWorksheets['Referensi Pengisian']->write($no, 1, 'Kode', $fColHeader);
               $this->mWorksheets['Referensi Pengisian']->write($no, 2, 'Nama', $fColHeader);
               //$this->mWorksheets['Referensi Pengisian']->write($no, 3, 'Keterangan', $fColHeader);
               //$this->mWorksheets['Referensi Pengisian']->write($no, 4, 'Jenis', $fColHeader);
            }
            $no++;
         }

//SHEET 3
         $this->mWorksheets['Referensi Unit Kerja']->write(1, 2, 'Data Unit Kerja', $fHeader);
         $no = 2;
         for($i=0;$i<sizeof($sheet3);$i++) {
            if($sheet3[$i]['is_satker'] == 0 ) {
               //sub
               $this->mWorksheets['Referensi Unit Kerja']->write($no, 0, $nomor, $fColData);
               $this->mWorksheets['Referensi Unit Kerja']->write($no, 1, $sheet3[$i]['kode'], $fColData);
               $this->mWorksheets['Referensi Unit Kerja']->write($no, 2, $sheet3[$i]['nama'], $fColData);
               $nomor++;
            } else {
               //parent
               $no++;$no++;
               $nomor = 1;
               $this->mWorksheets['Referensi Unit Kerja']->write($no, 0, $sheet3[$i]['nama'] . "(" . $sheet3[$i]['kode']. ")", $fTitle);
            }
            $no++;
         }
      }
   }
}
?>
