<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/cetak_spt/business/cetakspt.class.php';

class ViewCetakSPTKetua extends HtmlResponse
{
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
    
      $this->objcetak = new cetakspt();
    
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
      }
      
      $data['spt'] = $this->objcetak->GetDataPegawaiDetailSPTKetua($pegId);
      // print_r($data['spt']);exit();
      
      
  		
  		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/cetak_spt_ketua.rtf");
  		

      if(!empty($data['spt'])){
        $spt = $data['spt'];
        // print_r($spt);exit();
        // $pegawai = $data['dataPegawai'];
        $contents = str_replace("[NOMORSPT1]",$spt['pubpeg_sk_1'], $contents); 
        $contents = str_replace("[NAMA_KEPALA]",$spt['pubpeg_jabat_nama'], $contents); 
        $contents = str_replace("[NIP_KEPALA]",$spt['pubpeg_jabat_nip'], $contents); 
        $contents = str_replace("[GOLONGAN_KEPALA]",$spt['pubpeg_jabat_panggol'], $contents); 
        $contents = str_replace("[JABATAN_KEPALA]",$spt['pubpeg_jabat_jabatan'], $contents); 
        $contents = str_replace("[NAMA]",$spt['pubpeg_nama'], $contents); 
        $contents = str_replace("[NIP]",$spt['pubpeg_nim'], $contents); 
        $contents = str_replace("[GOLONGAN]",$spt['pubpeg_panggol'], $contents); 


        $contents = str_replace("[NOMORSK]",$spt['pubpeg_sk_walkot'], $contents); 
        $contents = str_replace("[TANGGALSK]",$this->date2string($spt['pubpeg_sk_walkot_tgl']), $contents); 
        $contents = str_replace("[TGLMULAI]",$this->date2string($spt['pubpeg_tgl_lantik']), $contents); 

        $contents = str_replace("[TANGGALPELANTIKAN]",$this->date2string($spt['pubpeg_tgl_lantik']), $contents); 

        
        $contents = str_replace("[UNITKERJA]",$spt['pubpeg_unitkerja'], $contents); 

        
        $contents = str_replace("[STRUKTUR]",$spt['pubpeg_jabatan'], $contents); 
        $contents = str_replace("[PELANTIK]",'Wali Kota', $contents); 


        

        


        $contents = str_replace("[TEMBUSAN4]",$spt['pubpeg_tembusan4'], $contents); 
        $contents = str_replace("[TEMBUSAN5]",$spt['pubpeg_tembusan5'], $contents); 
        $contents = str_replace("[TEMBUSAN6]",$spt['pubpeg_tembusan6'], $contents); 
        $contents = str_replace("[TEMBUSAN7]",$spt['pubpeg_tembusan7'], $contents); 

        $contents = str_replace("[NOMORSPT2]",$spt['pubpeg_sk_2'], $contents); 
        $contents = str_replace("[NOMORSK2]",$spt['pubpeg_sk_walkot_menduduki'], $contents); 
        $contents = str_replace("[TANGGALSK2]",$this->date2string($spt['pubpeg_sk_walkot_menduduki_tgl']), $contents); 
        $contents = str_replace("[TGL_MENDUDUKI]",$this->date2string($spt['pubpeg_tgl_menduduki']), $contents); 


        $contents = str_replace("[JABATAN]",$spt['pubpeg_jabatan'], $contents); 
        $contents = str_replace("[JABATANSK2]",$spt['pubpeg_jabatan'], $contents); 
        
        $contents = str_replace("[UNIT]",$spt['pubpeg_unitkerja'], $contents); 
        $contents = str_replace("[UANG]",$spt['pubpeg_gaji'], $contents); 
        
        $dd = $this->Terbilang($spt['pubpeg_gaji']);

        $contents = str_replace("[UANG_TERBILANG]",$dd, $contents); 

        $contents = str_replace("[NOMORSPT3]",$spt['pubpeg_sk3'], $contents); 
        $contents = str_replace("[TGLMULAI2]",$this->date2string($spt['pubpeg_tgl_tgs']), $contents); 

        $contents = str_replace("[TANGGAL_SURAT1]",$this->date2string($spt['pubpeg_tglsurat_1']), $contents); 
        $contents = str_replace("[TANGGAL_SURAT2]",$this->date2string($spt['pubpeg_tglsurat_2']), $contents); 
        $contents = str_replace("[TANGGAL_SURAT3]",$this->date2string($spt['pubpeg_tglsurat_3']), $contents); 
        
        $jabatanfungsi = explode('/',$spt['pubpeg_jabat_panggol']);
        // print_r($jabatanfungsi);exit();
        $contents = str_replace("[JABATANFUNGSI]",$jabatanfungsi['0'], $contents); 
           
      }
   
  		$nama=str_replace(" ","_",$spt['pubpeg_nama']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=spt_kepala_".$nama.".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
   }

   // function terbilang($satuan)
   // {    $huruf = array ("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh","sebelas"); 
   //    if ($satuan < 12)      
   //     return " ".$huruf[$satuan];  
   //       elseif ($satuan < 20)       
   //        return terbilang($satuan - 10)." belas";   
   //         elseif ($satuan < 100)    
   //            return terbilang($satuan / 10)." puluh".terbilang($satuan % 10);   
   //             elseif ($satuan < 200)      
   //              return "seratus".terbilang($satuan - 100);  
   //                elseif ($satuan < 1000)     
   //                  return terbilang($satuan / 100)." ratus".terbilang($satuan % 100);   
   //                   elseif ($satuan < 2000)      
   //                    return "seribu".terbilang($satuan - 1000);    elseif ($satuan < 1000000)     
   //                      return terbilang($satuan / 1000)." ribu".terbilang($satuan % 1000);   
   //                       elseif ($satuan < 1000000000)     
   //                         return terbilang($satuan / 1000000)." juta".terbilang($satuan % 1000000);  
   //                           elseif ($satuan >= 1000000000)     
   //                             echo "Angka yang Anda masukkan terlalu besar"; 

   //                         }

function Terbilang($a) {
    $ambil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($a < 12)
        return " " . $ambil[$a];
    elseif ($a < 20)
        return $this->Terbilang($a - 10) . "Belas";
    elseif ($a < 100)
        return $this->Terbilang($a / 10) . " Puluh" . $this->Terbilang($a % 10);
    elseif ($a < 200)
        return " Seratus" . $this->Terbilang($a - 100);
    elseif ($a < 1000)
        return $this->Terbilang($a / 100) . " Ratus" . $this->Terbilang($a % 100);
    elseif ($a < 2000)
        return " Seribu" . $this->Terbilang($a - 1000);
    elseif ($a < 1000000)
        return $this->Terbilang($a / 1000) . " Ribu" . $this->Terbilang($a % 1000);
    elseif ($a < 1000000000)
        return $this->Terbilang($a / 1000000) . " Juta" . $this->Terbilang($a % 1000000);
}


   
   function date2string($date) {
  	   $bln = array(
  	            1  => 'Januari',
      					2  => 'Februari',
      					3  => 'Maret',
      					4  => 'April',
      					5  => 'Mei',
      					6  => 'Juni',
      					7  => 'Juli',
      					8  => 'Agustus',
      					9  => 'September',
      					10 => 'Oktober',
      					11 => 'November',
      					12 => 'Desember'					
  	               );
  	   $arrtgl = explode('-',$date);
  	   if (sizeof($arrtgl)>2)
  	     return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
  	   else
  	     return $arrtgl[0];
	}  
}
   

?>