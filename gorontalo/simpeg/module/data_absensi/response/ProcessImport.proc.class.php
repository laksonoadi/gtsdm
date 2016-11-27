<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_absensi/business/ImportAbsensiHarian.class.php';

class ProcessImport
{
	var $_POST;
	var $Obj;
	var $pageView;
	var $pageInput;
	var $POST;
   var $user;

	//css hanya dipake di view
	var $cssAlert = "notebox-alert";
	var $cssDone = "notebox-done";
	var $cssFail = "notebox-warning";

	var $return;
	var $decId;
	var $encId;
   var $ext;
   
	function __construct()
   {
		$this->Obj = new ImportAbsensiHarian;
		$this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_absensi', 'rekapAbsensiHarian', 'view', 'html');
	  $this->pageAbsence = Dispatcher::Instance()->GetUrl('data_absensi', 'absensiHarian', 'view', 'html');
    $this->pageBack = Dispatcher::Instance()->GetUrl('data_absensi', 'importAbsensiHarian', 'view', 'html');
    $this->lang = GTFWConfiguration::GetValue('application', 'button_lang');
	   if ($this->lang=='eng'){
       $this->msgAddSuccess='Data added successfully';$this->msgAddFail='Data addition failed';
       $this->msgUpdateSuccess='Data updated successfully';$this->msgUpdateFail='Data update failed';
       $this->msgDeleteSuccess='Data deleted successfully';$this->msgDeleteFail='Data delete failed';
       $this->msgReqDataEmpty='All field marked with * and date field must be filled';
     }else{
       $this->msgAddSuccess='Penambahan data berhasil dilakukan';$this->msgAddFail='Penambahan data gagal dilakukan';
       $this->msgUpdateSuccess='Perubahan data berhasil dilakukan';$this->msgUpdateFail='Perubahan data gagal dilakukan';
       $this->msgDeleteSuccess='Penghapusan data berhasil dilakukan';$this->msgDeleteFail='Penghapusan data gagal dilakukan';
       $this->msgReqDataEmpty='Semua data bertanda * dan data tanggal harus diisi';
     }
		$this->_POST = $_POST->AsArray();
	}
	 
   function GenerateReturnParam ($return)
   {
      if ($return['status'] == 'success')
         $return['message'] = array(1 => $return['message'], 'notebox-done');
      elseif ($return['status'] == 'failed')
         $return['message'] = array(1 => $return['message'], 'notebox-warning');
      else $return['message'] = array($this->_POST, $return['message'], 'notebox-alert');
      
      return $return;
   }
   
   function ImportCSV()
   {
      set_time_limit(120);
      
      if (isset($this->_POST['cancel']))
      {
         $return['status'] = 'canceled';
         return $this->pageView;
         return $return;
        
      }
      
      $returnTruncate = $this->Obj->TruncateAbsensiHarianTemp();

      foreach (file($_FILES['CSV_File']['tmp_name']) as $value)
      {
         $value = trim($value);
         if (empty($value)) continue;
         $value = substr($value, 1, strlen($value) - 2); 
         $value = explode(',',$value);
         $count = count($value);

         for ($i = 0; $i < $count; $i++)
         {
            if (empty($value[$i])) continue;
            $this->_POST['CSV_Data'][$i] = array
            (
               'absensitempPegKodeGateAccess' => $value[$i+5],
               'absensitempPegNama' => trim($value[$i+6]),
               'absensitempTgl' => ($value[$i+1] . " " . $value[$i+2])
            );            
            #$return = $this->Obj->AddAbsensiHarian($value[$i+5], trim($value[$i+6]), $value[$i+1] . " " . $value[$i+2]);
          }
         $return = $this->Obj->AddAbsensiHarian($value[5], trim($value[6]), $value[1] . " " . $value[2]);
      }
      
       $returnDel = $this->Obj->DoDeleteAbsensiHarianTemp();
      if (!empty($msg))
      {
         $return['status'] = 'redo';
         $return['message'] = array($this->_POST, $msg, 'notebox-alert');
         return $return;
      } else {
         return $this->pageAbsence;
      }
      if ($return){
        return $this->pageAbsence;
      }else{
        return $this->pageView;
      }
      #return $this->Import();

      return $this->GenerateReturnParam($return);
      if ($return){
        return $this->pageAbsence;
      }else{
        return $this->pageView;
      }
   }
   
   function ImportXls()
   {
	  set_time_limit(0);
      $this->Obj->StartTrans();
      if ($return['status'] == 'failed') return $this->GenerateReturnParam($return);
      foreach ($this->_POST['CSV_Data'] as $value)
      {
         $return = $this->Obj->AddAbsensiHarian($value['absensitempPegKodeGateAccess'], $value['absensitempPegNama'], $value['absensitempTgl']);
         $returnDel = $this->Obj->DoDeleteAbsensiHarianTemp();
         Messenger::Instance()->Send('data_absensi', 'importAbsensiHarian', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
           
         if ($return['status'] != 'success')
         {
            $this->Obj->EndTrans(false);
            return $this->GenerateReturnParam($return);
         }
         
         $level[$value['level']] = $return['id'];
      }
      
      $return['message'] = 'Proses import data berhasil!';
      return $this->GenerateReturnParam($return);
      if ($return){
        return $this->pageAbsence;
      }else{
        return $this->pageView;
      }
   }
   
   function ProsesAbsensiHarian(){
      set_time_limit(120);
      
      $pegawai = $this->Obj->GetAllGateaccessFromAbsensiTemp();
 
      foreach ($pegawai as $value){

        $absensi = $this->Obj->GetAbsensiHarianTempByGateaccess($value['gateaccess']);

        $gateaccess = $absensi[0]['gateaccess'];
        $nama = $absensi[0]['nama'];
        $tgl_masuk = $absensi[0]['tgl_masuk'];
        $tgl_keluar = $absensi[0]['tgl_keluar'];
        $arr = array(
                'gateaccess' => $gateaccess,
                'nama' => $nama,
                'tgl_masuk' => $tgl_masuk,
                'tgl_keluar' => $tgl_keluar
                );

        $rs_add = $this->Obj->AddRekapAbsensiHarian($arr['gateaccess'], $arr['nama'], $arr['tgl_masuk'], $arr['tgl_keluar']);
      }
      
      $return = $this->pageView;
      #$return .= "&dataId=".$this->POST['idPeg'];
      if($rs_add == true){
        Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array($this->POST,$this->msgAddSuccess,$this->cssDone),Messenger::NextRequest);
        return $return;
      }else{
        Messenger::Instance()->Send('data_absensi', 'rekapAbsensiHarian', 'view', 'html', array($this->POST,$this->msgAddFail,$this->cssFail),Messenger::NextRequest);
        return $return;
      }
   }
}
?>