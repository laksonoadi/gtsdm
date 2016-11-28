<?php
require_once GTFWConfiguration::GetValue('application','docroot').'module/laporan_cv/business/laporan.class.php';
require_once GTFWConfiguration::GetValue('application','docroot').'module/data_pegawai/business/data_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pendidikan/business/mutasi_pendidikan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_organisasi_pegawai/business/mutasi_organisasi_pegawai.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pelatihan/business/mutasi_pelatihan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_seminar/business/mutasi_seminar.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penghargaan/business/mutasi_penghargaan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_penelitian/business/mutasi_penelitian.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_kunjungan/business/mutasi_kunjungan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_jabatan_struktural/business/mutasi_jabatan_struktural.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_pangkat_golongan/business/mutasi_pangkat_golongan.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/mutasi_bintang_tanda_jasa/business/MutasiBintangTandaJasa.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_istri_suami/business/istri_suami.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_anak/business/data_anak.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_orang_tua/business/data_orang_tua.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_saudara_kandung/business/data_saudara_kandung.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_mertua/business/data_mertua.class.php';
   
class ViewCetakSPT extends HtmlResponse
{
   
   function GetLabelFromCombo($ArrData,$Nilai){
      for ($i=0; $i<sizeof($ArrData); $i++){
        if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
      }
      return '--Semua--';
   }
   
   function ProcessRequest()
   {
      $this->Obj = new Laporan();  
      $this->ObjPegawai = new DataPegawai();
      $this->pend = new MutasiPendidikan();
      $this->org = new MutasiOrganisasiPegawai();
      $this->pel = new MutasiPelatihan();
      $this->sem = new MutasiSeminar();
      $this->peng = new MutasiPenghargaan();
      $this->penel = new MutasiPenelitian();
      $this->kunj = new MutasiKunjungan();
      $this->js = new MutasiJabatanStruktural();
      $this->pg = new MutasiPangkatGolongan();
      $this->btj = new MutasiBintangTandaJasa();
      $this->sutri = new IstriSuami();
      $this->anak = new Anak();
      $this->ortu = new OrangTua();
      $this->sdr = new SaudaraKandung();
      $this->mtua = new Mertua();
      
      if(isset($_GET['id'])){
         $pegId = $_GET['id'];
      }
      
      $data['dataPegawai']=$this->Obj->GetDataDetail($pegId);
      $data['dataPend'] = $this->pend->GetListMutasiPendidikan($pegId);
      $data['dataOrg'] = $this->org->GetListMutasiOrganisasiPegawai($pegId);
      $data['dataPel'] = $this->pel->GetListMutasiPelatihan($pegId);
      $data['dataSem'] = $this->sem->GetListMutasiSeminar($pegId);
      $data['dataPeng'] = $this->peng->GetListMutasiPenghargaan($pegId);
      $data['dataPenel'] = $this->penel->GetListMutasiPenelitian($pegId);
      $data['dataKunj'] = $this->kunj->GetListMutasiKunjungan($pegId);
      $data['dataJabs'] = $this->js->GetListMutasiJabatanStruktural($pegId);
      $data['dataPangg'] = $this->pg->GetListMutasiPangkatGolongan($pegId);
      $data['dataBint'] = $this->btj->GetListMutasi($pegId);
      $data['dataSutri'] = $this->sutri->GetDataIstri($pegId);
      $data['dataAnak'] = $this->anak->GetDataAnak($pegId);
      $data['dataOrtu'] = $this->ortu->GetDataOrtu($pegId);
      $data['dataSdr'] = $this->sdr->GetDataSdr($pegId);
      $data['dataMertua'] = $this->mtua->GetDataMertua($pegId);
      
      $data['spt'] = $this->ObjPegawai->GetDataPegawaiDetailSPT($pegId);

      $dataPegawai=$data['dataPegawai'];
      
  		
  		$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot')."doc/cetak_spt.rtf");
  		

      if(!empty($data['spt'])){
        $spt = $data['spt'];
        // print_r($spt);exit();
        $pegawai = $data['dataPegawai'];
        
        $contents = str_replace("[NOMOR_PANGKAT]",$spt['pubspt_nomor_golongan'], $contents); 
        $contents = str_replace("[NOMOR_SURAT]",$spt['pubspt_nomor_spt'], $contents); 
        $contents = str_replace("[NAMA]",$pegawai['nama'], $contents); 
        $contents = str_replace("[NIP]",$pegawai['nip'], $contents); 
        $contents = str_replace("[GOLONGAN]",$pegawai['pangkat_golongan'], $contents); 
        if(!empty($spt['pubspt_jabatanlama'])){
        $contents = str_replace("[JABATAN]",'Staf pada '.$spt['pubspt_jabatanlama'], $contents); 
        }else{
        $contents = str_replace("[JABATAN]",$spt['pubspt_jabatanlama'], $contents); 
        }
        $contents = str_replace("[JAB_BARU]",$spt['pubspt_jabatanbaru'], $contents); 
        $contents = str_replace("[TANGGAL_MULAI]",$this->date2string($spt['pubspt_tanggal']), $contents); 

        // print_r($spt['pubspt_tanggal']);exit();
        $contents = str_replace("[TANGGALSPT]",$this->date2string($spt['pubspt_tanggalttd']), $contents); 
        $contents = str_replace("[PENUGAS]",$spt['pubspt_sambutan'], $contents); 
        $contents = str_replace("[KOTA_SPT]",$spt['pubspt_kotattd'], $contents); 
        $contents = str_replace("[NIP_JABATAN_TTD]",$spt['pubspt_nipttd'], $contents); 
        $contents = str_replace("[NAMA_TTD]",$spt['pubspt_namattd'], $contents); 
        
        $contents = str_replace("[JABATAN_TTD]",$spt['jabfun'], $contents); 
        $contents = str_replace("[UNIT_TTD]",$spt['pubspt_sambutan'], $contents); 
        $contents = str_replace("[JABATAN_FUNGSI]",$spt['jabfun'], $contents); 


        $contents = str_replace("[TEMBUSAN4]",$spt['tembusan4'], $contents); 
        $contents = str_replace("[TEMBUSAN5]",$spt['tembusan5'], $contents); 
        $contents = str_replace("[TEMBUSAN6]",$spt['tembusan6'], $contents); 
        $contents = str_replace("[TEMBUSAN7]",$spt['tembusan7'], $contents); 
        $contents = str_replace("[TEMBUSAN8]",$spt['tembusan8'], $contents); 
      }
   
  		$nama=str_replace(" ","_",$dataPegawai['nama']);
  		header("Content-type: application/msword");
  		header("Content-disposition: inline; filename=daftar_riwayat_hidup_".$nama.".rtf");
  		header("Content-length: " . strlen($contents));
  		print $contents;
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
  	     return '     '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
  	   else
  	     return $arrtgl[0];
	}  
}
   

?>