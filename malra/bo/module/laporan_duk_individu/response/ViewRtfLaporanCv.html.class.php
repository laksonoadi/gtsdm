<?php

require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_duk_individu/business/laporan.class.php';
   
class ViewRtfLaporanCv extends HtmlResponse
{
   
   // function GetLabelFromCombo($ArrData,$Nilai){
   //    for ($i=0; $i<sizeof($ArrData); $i++){
   //      if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
   //    }
   //    return '--Semua--';
   // }
   
   function ProcessRequest()
   {
      $this->Obj = new Laporan();
      
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
         $data['urut'] = $_GET['urut'];
      }
      
      $data['dataPegawai']=$this->Obj->GetDataDukById($pegId);

      $data['dataJabs'] = $this->Obj->GetDataJabByDukById($pegId);

      $Pegawai = $data['dataPegawai'];

        //print_r($Pegawai);

        $dataPegawai['urut'] = $data['urut'];

        $dataPegawai['nama'] = $Pegawai[0]['nama'];
        
        $dataPegawai['nip'] = $Pegawai[0]['nip'];

        $dataPegawai['jenis_pegawai'] = $Pegawai[0]['jenis_pegawai'];
                
        $dataPegawai['jenis_kelamin']=$Pegawai[0]['jenis_kelamin']=='L'?'Laki-laki':'Perempuan';
        
        $dataPegawai['ttl']=$Pegawai[0]['tempat_lahir'].($Pegawai[0]['tempat_lahir']==''?'':', ').$this->date2string($Pegawai[0]['tanggal_lahir']);
        
        $dataPegawai['jabatan'] = $Pegawai[0]['jabatan'];
        
        $dataPegawai['unit'] = $Pegawai[0]['unit_kerja'];
        
        $dataPegawai['univ'] = $Pegawai[0]['pendidikan_nama'];
        
        $dataPegawai['thn_lulus'] = $Pegawai[0]['pendidikan_lulus'];
        
        $dataPegawai['jurusan'] = $Pegawai[0]['pendidikan_jurusan'];
        
        $dataPegawai['pend_tingkat'] = $Pegawai[0]['pendidikan_tingkat'];
        
        $mulai = explode('-', $Pegawai[0]['jabatan_tmt_mulai']);
        $dataPegawai['tgl_mulai'] = $mulai['2'];
        $dataPegawai['bln_mulai'] = $mulai['1'];
        $dataPegawai['thn_mulai'] = $mulai['0'];

        $selesai = explode('-', $Pegawai[0]['jabatan_tmt_selesai']);
        $dataPegawai['tgl_sel'] = $selesai['2'];
        $dataPegawai['bln_sel'] = $selesai['1'];
        $dataPegawai['thn_sel'] = $selesai['0'];

        $gol_tmt = explode('-', $Pegawai[0]['golongan_tmt']);
        $dataPegawai['tgl_gol'] = $gol_tmt['2'];
        $dataPegawai['bln_gol'] = $gol_tmt['1'];
        $dataPegawai['thn_gol'] = $gol_tmt['0'];
        
        $gol_id = $Pegawai[0]['golongan'];
        $gol_nama = $Pegawai[0]['gol_nama'];      
        $dataPegawai['golongan'] = $gol_id.' '.$gol_nama;
        
        $capeg_tmt = explode('-', $Pegawai[0]['capeg_tmt']);
        $dataPegawai['tgl_capeg'] = $capeg_tmt['2'];
        $dataPegawai['bln_capeg'] = $capeg_tmt['1'];
        $dataPegawai['thn_capeg'] = $capeg_tmt['0'];

        $dataPegawai['masa_kerja_tahun'] = $Pegawai[0]['masa_kerja_tahun'];
        $dataPegawai['masa_kerja_bulan'] = $Pegawai[0]['masa_kerja_bulan'];
      
      $contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_daftar_duk_perorangan.rtf");
      $contents = str_replace("}{\lang1035\langfe255\langnp1035\insrsid13501539\charrsid7032298 ]}","]", $contents);
      //print_r($contents); 
        //      print_r($dataPegawai);
       
      $keys=array_keys($dataPegawai);
      // echo '<pre>';
      // print_r($keys);
      // print_r($dataPegawai);
      // echo '</pre>';
      // exit();
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[".strtoupper($keys[$i])."]",$dataPegawai[$keys[$i]], $contents);  
      }
                    

      unset($keys); unset($temper); unset($isi);
          
      $thn  = $Pegawai[0]['latihan_tahun'];
      $nama = $Pegawai[0]['latihan_nama'];
      $jam  = $Pegawai[0]['latihan_jam'];

      $temper = array();
      $thn = explode(',', $thn);  
      $nama = explode(',', $nama);
      $jam = explode(',', $jam);

        $no = 1;
        foreach ($nama as $key => $val){
            $temper[$key]['no'] .= $no;
        $no++;
        }

        foreach ($thn as $key => $val){
            $temper[$key]['thn'] .= $val;
        }

        foreach ($nama as $key => $val){
            $temper[$key]['nama'] .= $val;
        }

        foreach ($jam as $key => $val){
            $temper[$key]['jam'] .= $val;
        }


      $keys = array('no','thn','nama','jam');
      for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
      for ($i=0; $i<count($temper); $i++){
          for ($ii=0; $ii<sizeof($keys);$ii++){
                $isi[$keys[$ii]] .=  $this->date2string($temper[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[P".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
      //print_r($isi);
      //exit();

      unset($keys); unset($jabatanPegawai); unset($isi);
      $keys=array('no','jbtn_nama','no_sk_jbtn','tgl_sk_jbtn','tmt_mulai','unit_kerja');
      $jabatanPegawai=$data['dataJabs'];
      for ($i=0; $i<sizeof($keys); $i++){$isi[$keys[$i]]='';}
      for ($i=0; $i<count($jabatanPegawai); $i++){
          $jabatanPegawai[$i]['no'] = ($i+1);
          for ($ii=0; $ii<sizeof($keys);$ii++){
                $isi[$keys[$ii]] .=  $this->date2string($jabatanPegawai[$i][$keys[$ii]]).'\par ';
          }
      }
      for ($i=0; $i<sizeof($keys);$i++){
        $contents = str_replace("[J".strtoupper($keys[$i])."]",$isi[$keys[$i]], $contents);  
      }
      
     //  echo "<pre>"; print_r($isi); echo "</pre>";
      //exit();
      $nama=str_replace(" ","_",$Pegawai[0]['nama']);
      header("Content-type: application/msword");
      header("Content-disposition: inline; filename=daftar_duk_perorangan_".$nama.".rtf");
      header("Content-length: " . strlen($contents));
      //print $contents;
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