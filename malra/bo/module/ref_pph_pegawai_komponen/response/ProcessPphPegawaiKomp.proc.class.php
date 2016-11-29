<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_pph_pegawai_komponen/business/PphPegawaiKomp.class.php';
class ProcessPph {

	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	//css hanya dipake di view
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";
    var $cssAlert = "notebox-alert";
	
	var $return;
	var $decId;
	var $encId;

	function __construct() {
		$this->Obj = new PphPegawaiKomp();
		$this->_POST = $_POST->AsArray();
		$this->decId = Dispatcher::Instance()->Decrypt($_REQUEST['dataId']);
		$this->encId = Dispatcher::Instance()->Encrypt($this->decId);
		$this->pageView = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'pphPegawaiKomp', 'view', 'html');
		$this->pageInput = Dispatcher::Instance()->GetUrl('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html');
		$this->idUser = Security::Instance()->mAuthentication->getcurrentuser()->GetUserId();

		if(!empty($this->_POST['idDelete'])){
		$IdDelete = $this->_POST['idDelete'];
		$pisah=explode("|",$IdDelete);
		$this->idKomponen=$pisah[0];
		$this->idPegawai=$pisah[1];
		$this->idPot=$pisah[2];
		$this->nama=$pisah[3];
		}
		else
		{$this->idPegawai=$this->_POST['dataId'];}
		
		$totalGaji=$this->Obj->GetTotalGaji($this->idPegawai);
		$this->totalGaji=$totalGaji[0]['total_gaji'];
		$gajiPokok=$this->Obj->GetGajiPokok($this->idPegawai);
		$this->gajiPokok=$gajiPokok[0]['gaji_pokok'];
		$komponenGajiPeg = $this->Obj->GetKomponenGajiPegawai($this->idPegawai);
		$formula = $this->Obj->GetFormulaPph($_POST['formula']);
		
    $maxValue=$this->Obj->GetMaxValue($_POST['formula']);

		for($i=0;$i<count($komponenGajiPeg);$i++){
			$kode[$i] = '['.$komponenGajiPeg[$i]['kompgajiKode'].']';
			$value[$i] = $komponenGajiPeg[$i]['nominal'];	
		}
		$penghasilanBruto = ($this->totalGaji)+($this->gajiPokok);		
    
    $formula = str_replace("[BRUTO]",$penghasilanBruto,$formula);
    $formula = str_replace("[BS]",$this->gajiPokok,$formula);
    #print_r($formula);exit;
    $newSql = str_replace($kode,$value,$formula);

      ob_start(); 
			eval("\$value = $newSql;");
			$ret = ob_get_contents();
			ob_end_clean();
	
			#if (preg_match("/Parse error/",$ret)) 
			#$value = 0;

		if($value>=$maxValue[0]['max_value']){
      $this->nominal=$maxValue[0]['max_value'];
    }else if ($value<$maxValue[0]['max_value']){
      $this->nominal=$value;
    }
		$this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
		if ($this->lang=='eng'){
       			$this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       			$this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       			$this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       			$this->msgReqDataEmpty='Please select at least one tax component.';
     		}else{
       			$this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       			$this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       			$this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       			$this->msgReqDataEmpty='Silakan pilih komponen Pph';
     		}
	}

	function Check() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['formula']) == "") {
				return "empty";
			} else {
				return true;
			}
		}
		return false;
	}
	
	function CheckProses() {
		if (isset($_POST['btnsimpan'])) {
			if(trim($this->_POST['potId']) == "") {
				return "add";
			} else {
				return "update";
			}
		}
		return false;
	}

	function Add() {
		$cek = $this->Check();

		if($cek === true) {
			$s = date('d');
	    $periode_gaji = $this->_POST['periode_tahun'].'-'.$this->_POST['periode_bulan'].'-'.$s;

      $addPphPegawaiKomp = $this->Obj->DoAddPphPegawaiKomp($this->_POST['dataId'], $this->_POST['formula'], $this->nominal, $periode_gaji, $this->idUser);
			
			//---hitung2an-------------------------------------------------------------------------------------------------------------------------------------
			$getJumlahNominal= $this->Obj->GetJumlahNominal($this->_POST['dataId'],$this->_POST['periode_tahun'],$this->_POST['periode_bulan']);
			#echo "potongan = ";print_r($getJumlahNominal);echo "<br/>";
			
      $bulanMasaKerja = $this->Obj->GetBulanMasaKerjaPerTahun($this->_POST['dataId']);
      #echo "bulan kerja = ";print_r($bulanMasaKerja);echo "<br/>";
      $nominalBulan=($this->totalGaji + $this->gajiPokok) - $getJumlahNominal[0]['jumlah'];
			#$nominalBulan = ($this->_POST['nominal']) - $getJumlahNominal[0]['jumlah'];
      #echo "netto bulan = ";print_r($nominalBulan);echo "<br/>";
			$nominalTahun=$nominalBulan * 12;//(12/$bulanMasaKerja);
			#echo "netto tahun = ";print_r($nominalTahun);echo "<br/>";
			if($this->_POST['kelamin'] == 'Male'){
        $this->_POST['kelamin'] = 'L';
      } elseif($this->_POST['kelamin'] == 'Female'){
        $this->_POST['kelamin'] = 'P';
      }
      $dataPtkp = $this->Obj->GetDataKompPtkpByPegId($this->_POST['dataId'],$this->_POST['kelamin']);
      for ($i=0; $i<sizeof($dataPtkp); $i++) {
			  $totalPtkpNominal +=$dataPtkp[$i]['nominal'];
			}
			#echo "total ptkp = ";print_r($totalPtkpNominal);echo "<br/>";			
      $nominalTahun = $nominalTahun - ($totalPtkpNominal * 12);
			#echo "netto tahun 2 = ";print_r($nominalTahun);echo "<br/>";
			$getDataPotongan=$this->Obj->GetDataPotongan();
	    #echo "persen pph = ";print_r($getDataPotongan);echo "<br/>";
			$hasil=$nominalTahun - $getDataPotongan[0]['nominalMax'];
			#echo "cek jatah persen = ";print_r($hasil);echo "<br/>";
			if($hasil<0){
        $hasilHitung=($nominalTahun*$getDataPotongan[0]['persenPotongan'])/100;
        #echo "hasil hitung 1 = ";print_r($hasilHitung);echo "<br/>";
			}
			else if ($hasil>0){ 
  			for ($i=0; $i<sizeof($getDataPotongan); $i++) {
          $hitung +=($getDataPotongan[$i]['nominalMax'] * $getDataPotongan[$i]['persenPotongan']/100);
  				#echo "hitung = ";print_r($hitung);echo "<br/>";
          $break +=$getDataPotongan[$i]['nominalMax'];
          #echo "break = ";print_r($break);echo "<br/>";
  				$sisa=($nominalTahun-$break)*$getDataPotongan[$i]['persenPotongan']/100;
  				#echo "sisa = ";print_r($sisa);echo "<br/>";
          $hasilHitung=$hitung+$sisa;
  				#echo "hasil hitung 2 = ";print_r($hasilHitung);echo "<br/>";
  				if ($break > $nominalTahun) {break;}
  			}		
		  }	
      #echo "hasil hitung akhir = ";print_r($hasilHitung);echo "<br/>";
			$hasilAkhir=($hasilHitung)/12;//*$bulanMasaKerja)/12;
      #echo "hasil akhir = ";print_r($hasilAkhir);
      $hasilNoNPWP = $hasilAkhir * 0.2;
      #echo "hasil tidak ada npwp = ";print_r($hasilNoNPWP);
      #exit;
			//-------------------------------------------------------------------------------------------------------------------------------------------------------------
			$cekProses = $this->CheckProses();

			if($cekProses == "add"){
        if($this->_POST['statusnpwp'] == 'Ya'){
          $hasilNoNPWP = NULL;
          $addPegawaiPotongan = $this->Obj->DoAddPegawaiPotongan($this->_POST['dataId'],$hasilAkhir,$hasilNoNPWP,$periode_gaji);
        }elseif($this->_POST['statusnpwp'] == 'Tidak'){
          $addPegawaiPotongan = $this->Obj->DoAddPegawaiPotongan($this->_POST['dataId'],$hasilAkhir,$hasilNoNPWP,$periode_gaji);
        }
      }	elseif ($cekProses =="update"){
        if($this->_POST['statusnpwp'] == 'Ya'){
          $hasilNoNPWP = NULL;
          $addPegawaiPotongan = $this->Obj->DoUpdatePegawaiPotongan($this->_POST['dataId'],$hasilAkhir, $hasiNoNPWP, $periode_gaji, $this->_POST['potId']);
        }elseif($this->_POST['statusnpwp'] == 'Tidak'){
          $addPegawaiPotongan = $this->Obj->DoUpdatePegawaiPotongan($this->_POST['dataId'],$hasilAkhir, $hasiNoNPWP, $periode_gaji, $this->_POST['potId']);
        }
      }

			if ($addPphPegawaiKomp === true) {
				Messenger::Instance()->Send('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html', array($this->_POST,$this->msgAddSuccess, $this->cssDone),Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html', array($this->_POST,$this->msgAddFail, $this->cssFail),Messenger::NextRequest);
			}
		} elseif($cek == "empty") {
			Messenger::Instance()->Send('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html', array($this->_POST,$this->msgReqDataEmpty, $this->cssAlert),Messenger::NextRequest);
			return $this->pageInput.'&dataId='.$this->decId .'&nama='.$this->_POST['nama'];
		}
		return $this->pageInput .'&dataId='.$this->decId .'&nama='.$this->_POST['nama'];
	}

	function Delete() {
		$delete = $this->Obj->DoDeletePphPegawaiKompById($this->idKomponen);
		$s = date('d');
	  $periode_gaji = $this->_POST['periode_tahun'].'-'.$this->_POST['periode_bulan'].'-'.$s;
		//---hitung2an-------------------------------------------------------------------------------------------------------------------------------------
			$getJumlahNominal= $this->Obj->GetJumlahNominal($this->idPegawai,$this->_POST['periode_tahun'],$this->_POST['periode_bulan']);
			$nominalBulan=($this->totalGaji + $this->gajiPokok)-$getJumlahNominal[0]['jumlah'];
			$nominalTahun=$nominalBulan * 12;
			$getDataPotongan=$this->Obj->GetDataPotongan();
	
				$hasil=$nominalTahun - $getDataPotongan[0]['nominalMax'];
				
					if($getJumlahNominal[0]['jumlah']==0){
					$hasilHitung=0;
					}
					elseif($hasil<0){
					$hasilHitung=($nominalTahun*$getDataPotongan[0]['persenPotongan'])/100;
					}
					else if ($hasil>0){
							for ($i=0; $i<sizeof($getDataPotongan); $i++) {
							$hitung +=($getDataPotongan[$i-1]['nominalMax'] * $getDataPotongan[$i-1]['persenPotongan']/100);
							$break +=$getDataPotongan[$i-1]['nominalMax'];
							$sisa=($nominalTahun-$break)*$getDataPotongan[$i]['persenPotongan']/100;
							$hasilHitung=$hitung+$sisa;
							if ($break > $nominalTahun) {break;}
							}		
					}	
				$hasilAkhir=($hasilHitung)/12;
			//-------------------------------------------------------------------------------------------------------------------------------------------------------------
			$addPegawaiPotongan = $this->Obj->DoUpdatePegawaiPotongan($this->idPegawai,$hasilAkhir, $periode_gaji, $this->idPot);
		
		if($delete === true) {
			Messenger::Instance()->Send('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html', array($this->_POST,$this->msgDeleteSuccess, $this->cssDone),Messenger::NextRequest);
		} else {
			Messenger::Instance()->Send('ref_pph_pegawai_komponen', 'inputPphPegawaiKomp', 'view', 'html', array($this->_POST, $gagal . $this->msgDeleteFail, $this->cssFail),Messenger::NextRequest);
		}
		return $this->pageInput.'&dataId='.$this->decId .'&nama='.$this->nama;
	}
}
?>