<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').
   'module/persetujuan_benefit/business/app_benefit.class.php';
   
class ViewInputAppBenefit extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').
         'module/persetujuan_benefit/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_input_app_benefit.html');
   }
   
   function ProcessRequest()
   {
      if ($_GET['dataId'] != '') {
      $pegId = $_GET['dataId']->Integer()->Raw();
      $Obj = new AppBenefit();
      $rs = $Obj->GetDataById($pegId);
      $dat['detail'] = $rs[0][0];
      $dat['spv'] = $rs[1][0];
      $dat['mor'] = $rs[2][0];

        if ($_GET['dataId2'] != '') {
          $benId = $_GET['dataId2']->Integer()->Raw();
          $pegId = $_GET['dataId']->Integer()->Raw();
          $dataBenefitDet = $Obj->GetDataAppBenefitDet($benId);
          $result=$dataBenefitDet[0];
          $detailPegawai = $Obj->GetDetailPegawaiById($pegId);

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
            $return['input']['tgl_status'] = $result['tgl_status'];
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
            $return['input']['tgl_status'] = '';
            $return['input']['status'] = '';
            $return['input']['user_id'] = '';
          }
          
          //detail data benefit balance          
          $dataBalance = $Obj->GetBalanceBenefitLeft($pegId);
          $result1=$dataBalance;
          
          $return['input1'] = $result1;
          
          //detail referensi klaim
          $dataKlaim = $Obj->GetDataKlaimFromBenefitId($benId);
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
      $status[0]['id'] = "approved";
      $status[0]['name'] = "approved";
      $status[1]['id'] = "rejected";
      $status[1]['name'] = "rejected";
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'status', 
      array('status',$status,$result['status'],'',' style="width:100px;"'), Messenger::CurrentRequest);
      
      $akhirT=date('Y')+4;
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_klaim', 
      array(date("Y-m-d"),'2009',$akhirT,'',''), Messenger::CurrentRequest);
      Messenger::Instance()->SendToComponent('tanggal', 'tanggal', 'view', 'html', 'tgl_status', 
      array(date("Y-m-d"),'2009',$akhirT,'',''), Messenger::CurrentRequest);
         
      $msg = Messenger::Instance()->Receive(__FILE__);
  		$this->Pesan = $msg[0][1];
  		$this->css = $msg[0][2];
  		
  		$return['dataPegawai'] = $dat['detail'];
  		$return['dataPegawaiSpv'] = $dat['spv'];
  		$return['dataPegawaiMor'] = $dat['mor'];
  		$return['detailPegawai'] = $detailPegawai;
  		$return['idPegawai'] = $pegId;
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
      $dataPegawai = $data['dataPegawai'];
      $dataPegawaiSpv = $data['dataPegawaiSpv'];
      $dataPegawaiMor = $data['dataPegawaiMor'];
      $detailPegawai = $data['detailPegawai'];
      $dat = $data['input'];
      $dat1 = $data['input1'];
      $dat2 = $data['input2'];
      
      if($this->Pesan)
      {
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
      }
      
      $buttonlang=GTFWConfiguration::GetValue('application', 'button_lang');
       if ($buttonlang=='eng'){
         $this->mrTemplate->AddVar('content', 'TITLE', 'BENEFIT CLAIM APPROVAL');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Update' : 'Add');
       }else{
         $this->mrTemplate->AddVar('content', 'TITLE', 'PERSETUJUAN KLAIM BENEFIT');
         $this->mrTemplate->AddVar('content', 'LABEL_ACTION', isset($_GET['dataId2']) ? 'Ubah' : 'Tambah');  
       }
      
      if(isset($_GET['dataId2'])){
        $op="edit";
        if ($buttonlang=='eng'){
          $oo=" Cancel ";
        }else{
          $oo=" Batal ";
        }
      }else{
        $op="add";
        $oo=" Reset ";
      }
      $this->mrTemplate->AddVar('content', 'OP', $op);
      $this->mrTemplate->AddVar('content', 'BUTTON', $oo);
      $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('persetujuan_benefit', 'appDataBenefit', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'URL_AKSI', Dispatcher::Instance()->GetUrl('persetujuan_benefit', 'inputAppBenefit', 'do', 'html')); 
      
      
      $this->mrTemplate->AddVar('content', 'DATA_ID', $dataPegawai['id']);
      $this->mrTemplate->AddVar('content', 'DATA_NIP', $dataPegawai['nip']);
      $this->mrTemplate->AddVar('content', 'DATA_NAMA', $dataPegawai['nama']);
      $this->mrTemplate->AddVar('content', 'DATA_SPV', $dataPegawaiSpv['spv']);
      $this->mrTemplate->AddVar('content', 'DATA_MOR', $dataPegawaiMor['mor']);
      $this->mrTemplate->AddVar('content', 'DATA_ALAMAT', $dataPegawai['alamat']);
      $this->mrTemplate->AddVar('content', 'DATA_SATKER', $dataPegawai['satker']);
      
      if (!file_exists(GTFWConfiguration::GetValue( 'application', 'photo_save_path').$dataPegawai['foto']) | empty($dataPegawai['foto'])) { 
		 	  $this->mrTemplate->AddVar('content', 'FOTO2', 'unknown.gif');
	  	}else{
        $this->mrTemplate->AddVar('content', 'FOTO2', $dataPegawai['foto']);
      }
      
      if ($buttonlang=='eng'){
         $dat['tgl_submit'] = $this->periode2stringEng($dat['tgl_submit']);
         $dat['tgl_klaim'] = $this->periode2stringEng($dat['tgl_klaim']);
         $dat['tgl_status'] = $this->periode2stringEng($dat['tgl_status']);
       }else{
         $dat['tgl_submit'] = $this->periode2string($dat['tgl_submit']);
         $dat['tgl_klaim'] = $this->periode2string($dat['tgl_klaim']);
         $dat['tgl_status'] = $this->periode2string($dat['tgl_status']);
       }
      
      if($detailPegawai['jns_kelamin'] == 'L'){
        $detailPegawai['jns_kelamin'] = "Male";
      } else {
        $detailPegawai['jns_kelamin'] = "Female";
      }
      $this->mrTemplate->AddVars('detail_pegawai', $detailPegawai, 'DATA_');
      
      $dat['tgl_benefit'] = $this->periode2stringEng($dat['tgl_benefit']);
      $this->mrTemplate->AddVars('content', $dat, 'DATA_');
      
      //tampilkan detail benefit balance
      $dataBalance = $dat1;
      if (empty($dataBalance)) {
  			$dataBalance['data_balance'] = "--";
        $this->mrTemplate->AddVar('content', 'URL_TAMBAH_BENEFIT', Dispatcher::Instance()->GetUrl('data_benefit', 'historyDataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId);  
  		} else {
  		  $dataBalance['data_balance'] = number_format($dataBalance['data_balance'], 2, ',', '.');
        $this->mrTemplate->AddVar('content', 'URL_TAMBAH_BENEFIT', Dispatcher::Instance()->GetUrl('data_benefit', 'dataBenefit', 'view', 'html') . "&dataId=" . $this->encDataId.'&op=add');  
  		}
  		$this->mrTemplate->AddVar('data_balance', 'CLASS_BALANCE', $this->css);
  		$this->mrTemplate->AddVar('data_balance', 'DATA_BALANCE', $dataBalance['data_balance']);
      
      //tampilkan detail referensi klaim
  		if (empty($dat2)) {
  			$this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'YES');
  			
  		} else {
  			$decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
  			$encPage = Dispatcher::Instance()->Encrypt($decPage);
  			$this->mrTemplate->AddVar('tpl_claim_list', 'CLAIM_LIST_EMPTY', 'NO');
  			$dataKlaim = $dat2;

  			for ($i=0; $i<sizeof($dataKlaim); $i++) {

          $no = $i+$data['start'];
  				$dataKlaim[$i]['number'] = $no;
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
   
   function time2string($time) {
	   $hour = array(
	        '00'  => '0',
					'01'  => '1',
					'02'  => '2',
					'03'  => '3',
					'04'  => '4',
					'05'  => '5',
					'06'  => '6',
					'07'  => '7',
					'08'  => '8',
					'09'  => '9',
					'10' => '10',
					'11'  => '11',
					'12'  => '12',
					'13'  => '13',
					'14'  => '14',
					'15'  => '15',
					'16'  => '16',
					'17'  => '17',
					'18'  => '18',
					'19'  => '19',
					'20' => '20',
          '21'  => '21',
					'22'  => '22',
					'23'  => '23'					
	               );
	   $jam = substr($time,0,2);
	   $menit = substr($time,-2);
	   return $jam[(int)$hour].' jam '.$menit.' menit';
	}
	
	function time2stringEng($time) {
	   $hour = array(
	        '00'  => '0',
					'01'  => '1',
					'02'  => '2',
					'03'  => '3',
					'04'  => '4',
					'05'  => '5',
					'06'  => '6',
					'07'  => '7',
					'08'  => '8',
					'09'  => '9',
					'10' => '10',
					'11'  => '11',
					'12'  => '12',
					'13'  => '13',
					'14'  => '14',
					'15'  => '15',
					'16'  => '16',
					'17'  => '17',
					'18'  => '18',
					'19'  => '19',
					'20' => '20',
          '21'  => '21',
					'22'  => '22',
					'23'  => '23'					
	               );
	   $jam = substr($time,0,2);
	   $menit = substr($time,-2);
	   return $jam[(int)$hour].' hour '.$menit.' minutes';
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