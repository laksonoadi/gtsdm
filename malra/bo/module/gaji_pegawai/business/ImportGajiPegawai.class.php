<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') .
   'module/'.Dispatcher::Instance()->mModule.'/business/Integrasi.class.php';

class ImportGajiPegawai extends Database {

	protected $mSqlFile= 'module/gaji_pegawai/business/importgajipegawai.sql.php';
   
	function __construct($connectionNumber=0) {
		parent::__construct($connectionNumber);
	}

   function GetCountKomponenGaji(){
      $result = $this->Open($this->mSqlQueries['count_komponen_gaji_ref'],array());
      return $result[0]['count'];
   }

	function CheckBiodataById($id) {
      #printf($this->mSqlQueries['get_biodata_pegawai_by_id'],$id);
		$result = $this->Open($this->mSqlQueries['get_biodata_pegawai_by_id'], array($id));
		if(!empty($result))
         return true;
      else
         return false;
	}

   function AddPegawai($nip,$nidn,$nid,$nama,$alamat,$nohp,$notelp,$unitId,$status){
      $result = $this->Execute($this->mSqlQueries['add_biodata_pegawai'], array($nip,$nidn,$nid,$nama,$alamat,$nohp,$notelp,$unitId,$status));
   }

   function UpdatePegawai($nip,$nidn,$nid,$nama,$alamat,$nohp,$notelp,$unitId,$status){
      $result = $this->Execute($this->mSqlQueries['update_biodata_pegawai'], array($nidn,$nid,$nama,$alamat,$nohp,$notelp,$unitId,$status,$nip));
   }

   function SetDetailKomponen($arrData){

      foreach ($this->KomponenGaji as $colNumber=>$komponenGajiId)
         $komponenGaji[$komponenGajiId] = $arrData[$colNumber];
      
      if(!empty($komponenGaji)){
         //check duplicate item and move into history if exist
         $this->Execute($this->mSqlQueries['copy_to_history'], array($arrData[2]));
         if ($this->Affected_Rows())
            $this->Execute($this->mSqlQueries['delete_kom_gaji_pegawai'], array($arrData[2]));
         // --------
         
         $this->InsertIntoPegawaiDetail($komponenGaji,$arrData['2']);
      }
   }

   function InsertIntoPegawaiDetail($komponenGaji,$id){

      $result = true;
      $j = 0;
      foreach ($komponenGaji as $komponenGajiId=>$kode){
       $tmpResult = $this->Execute($this->mSqlQueries['insert_into_detail_pegawai'],array($komponenGajiId, $kode, $id));
         if($tmpResult == false)
            $result = $tmpResult; 
      }
      return $result;
   }

   function SetQuery($sql,$komponenGaji){
      $str = '\'%s\'';
      for($i=0;$i<count($komponenGaji)-1;$i++){
         $str .=',\'%s\'';
      }
      return sprintf($sql,$str,'%s');
   }

   function Import($arrData){
      if($this->CheckBiodataById($arrData['2'])){
         $result =  $this->UpdatePegawai($arrData['2'],$arrData['3'],$arrData['4'],$arrData['5'],$arrData['6'],$arrData['7'],$arrData['8'],$arrData['9'],$arrData['10']);
         $this->SetDetailKomponen($arrData);
         return $result;
      }else{
         $result =  $this->AddPegawai($arrData['2'],$arrData['3'],$arrData['4'],$arrData['5'],$arrData['6'],$arrData['7'],$arrData['8'],$arrData['9'],$arrData['10']);
         $this->SetDetailKomponen($arrData);
         return $result;
      }
   }
   
   function CreateKomponenGajiId($data)
   {
      $errLevel = error_reporting(); error_reporting(0);
      $sql = str_replace("'%s'", implode(',', array_fill(0, count($data), "'%s'")), $this->mSqlQueries['get_komponen_gaji_id_by_label']);
      $arg = array_merge($data, $data);
      $result = $this->Open($sql, $arg);
      
      foreach ($result as $value)
      {
         $komponenGaji[$value['kompgajiKode']] = $value['kompgajiId'];
         $komponenGaji[$value['kompgajiNama']] = $value['kompgajiId'];
      }
      
      foreach ($data as $key=>$value)
         if (array_key_exists($value, $komponenGaji))
            $this->KomponenGaji[$key] = $komponenGaji[$value];
      error_reporting($errLevel);
   }
   
   function ImportFromGtSdm ()
   {
      $Obj = new Integrasi (4); // application_id dari gtSdm adalah 4
      $this->StartTrans();
      
      foreach ($Obj->gtSdmGetDataPegawai() as $value)
      {
         extract($value);
         $komponenGaji = array();
         if ($pegdtKompgajidtId1 > 0) $komponenGaji[] = $pegdtKompgajidtId1;
         if ($pegdtKompgajidtId2 > 0) $komponenGaji[] = $pegdtKompgajidtId2;
         if ($pegdtKompgajidtId3 > 0) $komponenGaji[] = $pegdtKompgajidtId3;
         if ($this->CheckBiodataById($bdtpegNip))
         {
            $result = $this->Execute($this->mSqlQueries['update_biodata_pegawai'],array($bdtpegNidn,'-',$bdtpegNama,$bdtpegAlamat,'-',$bdtpegNoTelp,NULL,'Ya',$bdtpegNip));
            if ($result) $result = $this->Execute($this->mSqlQueries['delete_kom_gaji_pegawai'], array($bdtpegNip));
         }
         else $result = $this->Execute($this->mSqlQueries['add_biodata_pegawai'],array($bdtpegNip,$bdtpegNidn,'-',$bdtpegNama,$bdtpegAlamat,'-',$bdtpegNoTelp,NULL,'Ya'));
         
         foreach ($komponenGaji as $komponenGajiId) if ($result)
            $result = $this->Execute($this->mSqlQueries['insert_kom_gaji_pegawai'],array($komponenGajiId, $bdtpegNip));
         if (!$result) break;
      }
      
      $this->EndTrans($result);
      return $result;
   }
}
?>
