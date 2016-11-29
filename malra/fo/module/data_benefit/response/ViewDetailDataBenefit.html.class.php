<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/data_benefit/business/benefit.class.php';
   
class ViewDetailDataBenefit extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/data_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_detail_data_benefit.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new Benefit();
      $rs = $Obj->GetDataById($pegId);
      
        if ($_GET['dataId2'] != '') {
          $benefitId = $_GET['dataId2']->Integer()->Raw();
          //detail benefit
          $dataBenefitDet = $Obj->GetDataBenefitDet($benefitId);
          $result=$dataBenefitDet[0];
          if(!empty($result)){
            $return['input']['id'] = $result['id'];
            $return['input']['no'] = $result['no'];
            $return['input']['balancebenefit_id'] = $result['balancebenefit_id'];
            $return['input']['peg_id'] = $result['peg_id'];
            $return['input']['nama_pasien'] = $result['nama_pasien'];
            $return['input']['relasi_pasien'] = $result['relasi_pasien'];
            $return['input']['benefit_id'] = $result['benefit_id'];
            $return['input']['tipe_klaim'] = $result['tipe_klaim'];
            $return['input']['tgl_benefit'] = $result['tgl_benefit'];
            $return['input']['tgl_submit'] = $result['tgl_submit'];
            $return['input']['tempat'] = $result['tempat'];
            $return['input']['total_klaim'] = $result['total_klaim'];
            $return['input']['alasan'] = $result['alasan'];
            $return['input']['tgl_klaim'] = $result['tgl_klaim'];
            $return['input']['status'] = $result['status'];
            $return['input']['user_id'] = $result['user_id'];
          }else{
            $return['input']['id'] = '';
            $return['input']['no'] = '';
            $return['input']['balancebenefit_id'] = '';
            $return['input']['peg_id'] = '';
            $return['input']['nama_pasien'] = '';
            $return['input']['relasi_pasien'] = '';
            $return['input']['benefit_id'] = '';
            $return['input']['tipe_klaim'] = '';
            $return['input']['tgl_benefit'] = '';
            $return['input']['tgl_submit'] = '';
            $return['input']['tempat'] = '';
            $return['input']['total_klaim'] = '';
            $return['input']['alasan'] = '';
            $return['input']['tgl_klaim'] = '';
            $return['input']['status'] = '';
            $return['input']['user_id'] = '';
          }
          //detail pegawai
          $dataPegawaiDet = $Obj->GetDetailPegawaiById($pegId);
          $result1=$dataPegawaiDet;
          if(!empty($result1)){
            $return['input1']['nama_pegawai'] = $result1['nama_pegawai'];
            $return['input1']['jns_kelamin'] = $result1['jns_kelamin'];
            $return['input1']['status_nikah'] = $result1['status_nikah'];
            $return['input1']['jns_pegawai'] = $result1['jns_pegawai'];
            $return['input1']['jabatan_struktural'] = $result1['jabatan_struktural'];
          } else {
            $return['input1']['nama_pegawai'] = '';
            $return['input1']['jns_kelamin'] = '';
            $return['input1']['status_nikah'] = '';
            $return['input1']['jns_pegawai'] = '';
            $return['input1']['jabatan_struktural'] = '';
          }
          //detail referensi klaim
          $dataKlaim = $Obj->GetDataKlaimFromBenefitId($benefitId);
          $result2=$dataKlaim;
          $return['input2'] = $result2;
          /*if(!empty($result2)){
            $return['input2']['id'] = $result2['id'];
            $return['input2']['benefit_id'] = $result2['benefit_id'];
            $return['input2']['jnsklaim_id'] = $result2['jnsklaim_id'];
            $return['input2']['tipe_klaim'] = $result2['tipe_klaim'];
            $return['input2']['nilai_klaim'] = $result2['nilai_klaim'];
            $return['input2']['file_klaim'] = $result2['file_klaim'];
          } else {
            $return['input2']['id'] = '';
            $return['input2']['benefit_id'] = '';
            $return['input2']['jnsklaim_id'] = '';
            $return['input2']['tipe_klaim'] = '';
            $return['input2']['nilai_klaim'] = '';
            $return['input2']['file_klaim'] = '';
          }*/
        }
      }
      
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $rs;
  		$return['dataBenefitDet'] = $dataBenefitDet;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataBenefitDet = $data['dataBenefitDet'];
      $dat = $data['input'];
      $dat1 = $data['input1'];
      $dat2 = $data['input2'];

      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $idEnc = Dispatcher::Instance()->Encrypt($data['idPegawai']);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html') . '&dataId=' . $idEnc );
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai['alamat']);
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'DATA_FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'DATA_FOTO2', $dataPegawai['foto']);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'BENEFIT CLAIM DATA');
         $dat['tgl_benefit'] = $this->periode2stringEng($dat['tgl_benefit']);
         $dat['tgl_submit'] = $this->periode2stringEng($dat['tgl_submit']);
         $dat['tgl_klaim'] = $this->periode2stringEng($dat['tgl_klaim']);
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'DATA KLAIM BENEFIT');
         $dat['tgl_benefit'] = $this->periode2string($dat['tgl_benefit']);
         $dat['tgl_submit'] = $this->periode2string($dat['tgl_submit']);
         $dat['tgl_klaim'] = $this->periode2string($dat['tgl_klaim']);
       }
      
      $dat['total_klaim'] = number_format($dat['total_klaim'], 2, ',', '.');
      $this->mrTemplate->AddVars('content', $dat, 'DATA_');
      if($dat1['jns_kelamin'] == 'L'){
        $dat1['jns_kelamin'] = "Male";
      } else {
        $dat1['jns_kelamin'] = "Female";
      }
      
      $this->mrTemplate->AddVars('detail_pegawai', $dat1, 'DATA_');
      
      //tampilkan history benefit
  		if (empty($dat2)) {
  			$this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'YES');
  			
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'NO');
  			$dataKlaim = $dat2;

  			for ($i=0; $i<sizeof($dataKlaim); $i++) {

          $no = $i+$data['start'];
  				$dataKlaim[$i]['number'] = $no+1;
  				if ($no % 2 != 0) {
            $dataKlaim[$i]['class_name'] = 'table-common-even';
          }else{
            $dataKlaim[$i]['class_name'] = '';
          }
  				
  				if($i == 0) $this->mrTemplate->AddVar('content', 'FIRST_NUMBER', $no);
  				if($i == sizeof($dataKlaim)-1) $this->mrTemplate->AddVar('content', 'LAST_NUMBER', $no);

  				$idEnc = Dispatcher::Instance()->Encrypt($dataKlaim[$i]['id']);
          $dataKlaim[$i]['nilai_klaim'] = number_format($dataKlaim[$i]['nilai_klaim'], 2, ',', '.');
          $dataKlaim[$i]['nilai_klaim_disetujui'] = number_format($dataKlaim[$i]['nilai_klaim_disetujui'], 2, ',', '.');
          $this->mrTemplate->AddVars('tpl_claim_item', $dataKlaim[$i], 'DATA_');       
  				$this->mrTemplate->parseTemplate('tpl_claim_item', 'a');
	 
  			}   
  		}
   }
   
   function periode2string($date) {
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
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
	
	function periode2stringEng($date) {
	   $bln = array(
	        1  => 'January',
					2  => 'February',
					3  => 'March',
					4  => 'April',
					5  => 'May',
					6  => 'June',
					7  => 'July',
					8  => 'August',
					9  => 'September',
					10 => 'October',
					11 => 'November',
					12 => 'December'					
	               );
	   $tanggal = substr($date,8,2);
	   $bulan = substr($date,5,2);
	   $tahun = substr($date,0,4);
	   return (int)$tanggal.' '.$bln[(int)$bulan].' '.$tahun;
	}
}
   

?>