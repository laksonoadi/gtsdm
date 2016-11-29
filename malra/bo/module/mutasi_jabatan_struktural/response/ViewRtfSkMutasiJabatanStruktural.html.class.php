<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
    
class ViewRtfSkMutasiJabatanStruktural extends HtmlResponse
{
    function GetLabelFromCombo($ArrData,$Nilai){
        for ($i=0; $i<sizeof($ArrData); $i++){
          if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
        }
        return '--Semua--';
    }
    
    function ProcessRequest()
    {
        $this->Obj = new MutasiJabatanStruktural();
        
        $pegId = $_GET['id'];
        $jabstrukId = $_GET['dataId'];
        $mutasi = $this->Obj->GetDataPegawaiByMutasiId($jabstrukId, $pegId);
        $data['jabstruk'] = $mutasi[0];
        if(empty($pegId) || empty($jabstrukId) || empty($data['jabstruk'])) {
            echo 'Data Jabatan Struktural tidak ditemukan.';
            exit;
        }
        
        $file_path = GTFWConfiguration::GetValue('application', 'docroot').GTFWConfiguration::GetValue('application', 'template_anjab').$data['jabstruk']['jabstruk_template'];
        if(empty($data['jabstruk']['jabstruk_template']) || (!empty($data['jabstruk']['jabstruk_template']) && !file_exists($file_path))) {
            echo 'Template SK Analisa Jabatan tidak ditemukan.';
            exit;
        }
        
        $contents = file_get_contents($file_path);
        $contents = str_replace(array("\r\n", "\n", "\r"), "", $contents);
        
        $js = $data['jabstruk'];
        
        $replace = array(
            'jabatan'			=> $js['jabstruk'],
            'unit_kerja'		=> $js['satker'],
        );
        
        foreach($replace as $key => $value) {
            $contents = str_replace('['.strtoupper($key).']', $value, $contents, $how_many);
            if($how_many === 0) {
                // Use fallback if not found
                // Regex is SLOW, use it only as fallback
                $pattern = "#\[((?:\\\\\w+)*\s*?)*".preg_quote(strtoupper($key))."((?:\\\\\w+)*\s*?)*\]#";
                $contents = preg_replace($pattern, $value, $contents, -1, $how_many);
                if($how_many === 0) {
                    // Do something, fallback again here, may not be needed
                }
            }
        }
        
        $nama = str_replace(" ", "_", $js['satker']);
        header("Content-type: application/msword");
        header("Content-disposition: inline; filename=anjab_".$nama.".rtf");
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
}

?>