<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_pangkat_fungsional/business/LayananPangkatFungsional.class.php';
    
class ViewRtfLayananPangkatFungsional extends HtmlResponse
{
    function GetLabelFromCombo($ArrData,$Nilai){
        for ($i=0; $i<sizeof($ArrData); $i++){
          if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
        }
        return '--Semua--';
    }
    
    function ProcessRequest()
    {
        $this->Obj = new LayananPangkatFungsional();
        
        $id = $_GET['id'];
        $data['sk_pangkat'] = $this->Obj->GetSkPangkatById($id);
        if(empty($data['sk_pangkat'])) {
            echo 'Data surat kenaikan pangkat tidak ditemukan. Silahkan isi form terlebih dahulu.';
            exit;
        }
        
        $contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/template_layanan_pangkat_fungsional.rtf");
        $contents = str_replace(array("\r\n", "\n", "\r"), "", $contents);
        
        $sk = $data['sk_pangkat'];
        
        $sk['old_pngkt_text'] = '';
        if(isset($sk['old_pngkt']) && $sk['old_pngkt'] != '')
            $sk['old_pngkt_text'] = $sk['old_pngkt_nama'].' ('.$sk['old_pngkt'].') / '.$this->date2string($sk['old_pngkt_tmt']);
        
        $replace = array(
            'nama'				=> $sk['nama_gelar'],
            'tgl_lahir'			=> $this->date2string($sk['tgl_lahir']),
            'nip'				=> $sk['nip'],
            'pendidikan'		=> $sk['pendidikan'],
            'old_pngkt_text'	=> $sk['old_pngkt_text'],
            'old_jab'			=> $sk['old_jab_nama'],
            'satker'			=> $sk['satker'],
            'new_pngkt'			=> $sk['id_pngkt'],
            
            'no_sk'				=> $sk['no_sk'],
            'agree_no'			=> $sk['agree_no'],
            'agree_date'		=> $this->date2string($sk['agree_date']),
            'old_ak'			=> (isset($sk['old_ak']) && $sk['old_ak'] != '' ? ' AK : '.$sk['old_ak'] : ''),
            'start_sk'			=> $this->date2string($sk['start_sk']),
            'new_pngkt_name'	=> $sk['new_pngkt_name'],
            'name_new_jab'		=> $sk['new_jab'],
            'new_ak'			=> (isset($sk['new_ak']) && $sk['new_ak'] != '' ? ' AK : '.$sk['new_ak'] : ''),
            'mk_tahun'			=> str_pad($sk['mk_thn'], 2, '0', STR_PAD_LEFT),
            'mk_bulan'			=> str_pad($sk['mk_bln'], 2, '0', STR_PAD_LEFT),
            'new_gaji'			=> $this->rupiah($sk['GjPokok']),
            'issue_place'		=> $sk['issue_place'],
            'issue_date'		=> $this->date2string($sk['issue_date']),
            'official_sk'		=> strtoupper($sk['official_sk']),
            'tembusan_txt'		=> (isset($sk['tembusan_sk']) && $sk['tembusan_sk'] != '' ? 'Tembusan' : ''),
            'tembusan'			=> (isset($sk['tembusan_sk']) && $sk['tembusan_sk'] != '' ? 'Keputusan ini disampaikan kepada, Yth. :\par '.str_replace(array("\r\n", "\n", "\r"), '\par ', $sk['tembusan_sk']) : ''),
        );
        
        foreach($replace as $key => $value) {
            $contents = str_replace('['.strtoupper($key).']', $value, $contents);
        }
        
        $nama = str_replace(" ", "_", $sk['nama']);
        header("Content-type: application/msword");
        header("Content-disposition: inline; filename=layanan_pangkat_fungsional_".$nama.".rtf");
        header("Content-length: " . strlen($contents));
        print $contents;
    }
    
    function date2string($date) {
         if($date == '0000-00-00')
              return '00-00-0000';
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
    
    function rupiah($money) {
        return number_format($money, 0, ',', '.');
    }
}

?>