<?php

class Integrasi extends Database
{
	protected $mSqlFile;
   
   // argument sesuai dengan table gtfw_application
	function __construct($connectionNumber = 0){
		$this->mSqlFile = 'module/'.Dispatcher::Instance()->mModule.'/business/Integrasi.sql.php';
		parent::__construct($connectionNumber);
	}
   
	function gtSdmGetDataPegawai (){
		return $this->Open($this->mSqlQueries['gtSDM_get_data_pegawai'], array());
	}
	
	function GetLastTransaksiGaji (){
		$result=$this->Open($this->mSqlQueries['get_last_transaksi_gaji'], array());
		return $result[0]['last_id'];
	}
	
	function InsertTransaksiGajiToFinansi($data){
		$result = $this->Open($this->mSqlQueries['get_sql_generate_number'], array('payroll'));
		$result =  $this->open($result['0']['formatNumberFormula'],array());
		$no_transaksi=$result['0']['number'];
		
		$param=array(
					'unit_kerja'=>1,
					'no_referensi'=>$no_transaksi,
					'catatan'=>$data['catatan'],
					'nilai'=>$data['nilai'],
					'penaggung_jawab'=>'Bagian Kepegawaian'
				);
				
		$result = $this->Execute($this->mSqlQueries['insert_transaksi_gaji_to_gtfinansi'], $param);
		
		return $result;
	}
	
	function InsertTransaksiGajiDetailToFinansi($data){
		$transId=$this->GetLastTransaksiGaji();
		$param=array(
					'transdtgajiTransId'=>$transId,
					'transdtgajiNIP'=>$data['nip'],
					'transdtgajiNama'=>$data['nama'],
					'transdtgajiTanggalGaji'=>date('Y-m-d'),
					'transdtgajiTanggalPeriodeGaji'=>$data['periode'],
					'transdtgajiNominalGaji'=>$data['nominal']
				);
				
		$result = $this->Execute($this->mSqlQueries['insert_transaksi_gaji_detail_to_gtfinansi'], $param);
		
		return $result;
	}
}
?>
