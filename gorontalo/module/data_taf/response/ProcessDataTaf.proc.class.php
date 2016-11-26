<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/data_taf/business/taf.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/email/business/Email.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/data_pegawai/business/data_pegawai.class.php';

class Process
{
   var $POST;
   var $user;
   var $Obj;
   var $cssAlert = "notebox-alert";
   var $cssDone = "notebox-done";
   var $cssFail = "notebox-warning";
   var $pageInput;
   var $decId;
   var $decId2;
   var $pageView;
   
   function __construct() {
    $this->Obj = new Taf();
    $this->ObjEmail = new Email();
    $this->pegawaiObj = new DataPegawai();
	  $this->user=Security::Authentication()->GetCurrentUser()->GetUserId();
	  $this->pageView = Dispatcher::Instance()->GetUrl('data_taf', 'historyDataTaf', 'view', 'html');
	  $this->pageHistory = Dispatcher::Instance()->GetUrl('data_taf', 'historyDataTaf', 'view', 'html');
    $this->decId = $_GET['dataId2']->Integer()->Raw();
    $this->decId2 = $_GET['dataId']->Integer()->Raw();
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
	}
	
	
	function SetPost($param){
      $this->POST = $param->AsArray();
      $this->POST['total_allowance']=$this->Obj->num_toprocess($this->POST['total_allowance']);
      
      //Total Hari
      $this->POST['total_hari_keseluruhan']=0;
      for ($i=0; $i<sizeof($this->POST['data']['travel']['total_days']); $i++){
          $this->POST['total_hari_keseluruhan'] += $this->POST['data']['travel']['total_days'][$i];
      }
      
      //Allowance
      for ($i=0; $i<sizeof($this->POST['data']['travel']['tujuan']); $i++){
          $tujuan=$this->POST['data']['travel']['tujuan'][$i];
          $this->POST['data']['allowance'][$tujuan]['sub_total']=0;
          for ($ii=0; $ii<sizeof($this->POST['data']['allowance'][$tujuan]['total']); $ii++){
              $total=$this->Obj->num_toprocess($this->POST['data']['allowance'][$tujuan]['total'][$ii]);
              $this->POST['data']['allowance'][$tujuan]['total'][$ii]=$total;
              $this->POST['data']['allowance'][$tujuan]['sub_total'] +=$total;
          }
      }
      
      //Total Budget
      $this->POST['total_budget']=0;
      for ($i=0; $i<sizeof($this->POST['data']['budget']['amount']); $i++){
          $this->POST['data']['budget']['amount'][$i]=$this->Obj->num_toprocess($this->POST['data']['budget']['amount'][$i]);
          $this->POST['total_budget'] += $this->POST['data']['budget']['amount'][$i];   
      }
      
      $this->a=$this->POST['tgl_aju_year'].'-'.$this->POST['tgl_aju_mon'].'-'.$this->POST['tgl_aju_day'];
  }
  
  function Check (){
    if (isset($this->POST['btnbatal'])){
      if($_GET['op'] == 'add'){
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->POST['id'];
      } elseif($_GET['op'] == 'edit') {
        $return = $this->pageHistory;
        $return .= "&dataId=".$this->POST['idPeg'];
      }elseif($_POST['op'] == 'add'){
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->POST['id'];
      } elseif($_POST['op'] == 'edit') {
        $return = $this->pageHistory;
        $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    } 
    if($this->POST['tgl_aju_day'] == "00" or $this->POST['tgl_aju_mon'] == "00" or $this->POST['tgl_aju_year'] == "0000"){
      $error = $this->msgReqDataEmpty;
    } elseif(trim($this->POST['alasan']) == ''){
      $error = $this->msgReqDataEmpty;
    }elseif(trim($this->POST['tipe']) == ''){
      $error = $this->msgReqDataEmpty;
    }
    
    if (isset($error)){
      $msg = array($this->POST, $error, $this->cssAlert);
      Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', $msg, Messenger::NextRequest);
      
      $return = $this->pageView;
      if ($this->POST['op']=="edit"){ 
        $return = $this->pageHistory;
        $return .= "&dataId2=".$_GET['dataId2'];
      }
      if (isset($this->POST['idPeg'])){ 
      $return .= "&dataId=".$this->POST['idPeg'];
      }
      return $return;
    }
    return true;
  }
  
  
  function AddDataTaf(){
    $pegawai=$this->pegawaiObj->GetDataPegawaiByUserName($_SESSION['username']);
    $userId=$pegawai['peguserUserId'];
    
    $array=array( 'no_taf'=>$this->POST['no_taf'],
                  'peg_id'=>$this->POST['idPeg'],
                  'jenis_taf'=>$this->POST['tipe'],
                  'alasan'=>$this->POST['alasan'],
                  'tgl_aju'=>$this->a,
                  'total_hari'=>$this->POST['total_hari_keseluruhan'],
                  'total_anggaran'=>$this->POST['total_budget'],
                  'anggaran_transport'=>$this->POST['total_budget'],
                  'anggaran_budget'=>$this->POST['total_budget'],
                  'user_id'=>$userId
                );
    
    
    $this->Obj->StartTrans();
    $result = $this->Obj->Add($array);
    
    if ($result) {
      $tafId = $this->Obj->GetLastId();
      $tafId = $tafId[0]['last_id'];
      
      //Tujuan dan Allowance
      for ($i=0; $i<sizeof($this->POST['data']['travel']['tujuan']); $i++){
          $tujuan=$this->POST['data']['travel']['tujuan'][$i];
          $tgl_awal=$this->POST['data']['travel']['tanggal_mulai'][$i];
          $tgl_selesai=$this->POST['data']['travel']['tanggal_selesai'][$i];
          $total_hari=$this->POST['data']['travel']['total_days'][$i];
          
          $result1 = $this->Obj->AddTujuan(array($tafId,$tujuan,$tgl_awal,$tgl_selesai,$total_hari,$userId));
          
          if ($result1) {
              $taftujuanId = $this->Obj->GetLastIdTujuan();
              $taftujuanId = $taftujuanId[0]['last_id'];
              for ($ii=0; $ii<sizeof($this->POST['data']['allowance'][$tujuan]['total']); $ii++){
                  $kebijakanId=$this->POST['data']['allowance'][$tujuan]['kebijakan_taf_id'][$ii];
                  $total_hari=$this->POST['data']['allowance'][$tujuan]['total_hari'][$ii];
                  $anggaran=$this->POST['data']['allowance'][$tujuan]['nilai'][$ii];
                  $currId=$this->POST['data']['allowance'][$tujuan]['currency'][$ii];
                  $total_anggaran=$this->POST['data']['allowance'][$tujuan]['total'][$ii];
                  $catatan=$this->POST['data']['allowance'][$tujuan]['catatan'][$ii];
                  
                  $array1=array('taf_id'=>$tafId,
                                'taf_tujuan_id'=>$taftujuanId,
                                'kebijakan_id'=>$kebijakanId,
                                'total_hari'=>$total_hari,
                                'anggaran'=>$anggaran,
                                'currId'=>$currId,
                                'total_anggaran'=>$total_anggaran,
                                'catatan'=>$catatan,
                                'user_id'=>$userId
                              );
                  $result2 = $this->Obj->AddAnggaran($array1);
              }
          }
      }
      
      //Transport
      for ($i=0; $i<sizeof($this->POST['data']['transport']['tujuan']); $i++){
          $jenis=$this->POST['data']['transport']['tipe'][$i];
          $tujuan=$this->POST['data']['transport']['tujuan'][$i];
          $tgl_awal=$this->POST['data']['transport']['etd_tanggal'][$i];
          $tgl_akhir=$this->POST['data']['transport']['eta_tanggal'][$i];
          $jam_awal=$this->POST['data']['transport']['etd_waktu'][$i];
          $jam_akhir=$this->POST['data']['transport']['eta_waktu'][$i];
          $nama=$this->POST['data']['transport']['nama'][$i];
          $anggaran=0;
          $catatan=$this->POST['data']['transport']['catatan'][$i];
          
          $array2=array('jenis_transport'=>$jenis,
                        'taf_id'=>$tafId,
                        'tgl_awal'=>$tgl_awal,
                        'tgl_akhir'=>$tgl_akhir,
                        'tujuan'=>$tujuan,
                        'nama'=>$nama,
                        'jam_awal'=>$jam_awal,
                        'jam_akhir'=>$jam_akhir,
                        'anggaran'=>$anggaran,
                        'catatan'=>$catatan,
                        'user_id'=>$userId
                      );
          
          $result3 = $this->Obj->AddTransportasi($array2);
      }
      
      //Budget
      for ($i=0; $i<sizeof($this->POST['data']['budget']['budget_id']); $i++){
          $budgetId=$this->POST['data']['budget']['budget_id'][$i];
          $bulan=$this->POST['data']['budget']['periode_bulan'][$i];
          $tahun=$this->POST['data']['budget']['periode_tahun'][$i];
          $anggaran=$this->POST['data']['budget']['amount'][$i];
          
          $array3=array('taf_id'=>$tafId,
                        'budget_id'=>$budgetId,
                        'bulan'=>$bulan,
                        'tahun'=>$tahun,
                        'anggaran'=>$anggaran,
                        'user_id'=>$userId
                      );
          
          $result4 = $this->Obj->AddBudget($array3);
      }
    }
    
    $this->Obj->EndTrans($result);
    
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function UpdateDataTaf(){ 
    $pegawai=$this->pegawaiObj->GetDataPegawaiByUserName($_SESSION['username']);
    $userId=$pegawai['peguserUserId'];
    $tafId =$this->POST['id'];
    
    //Melist daftar ID Travel yang di edit yang tidak dihapus
    $arrId['travel']='';
    $count=sizeof($this->POST['data']['travel']['tujuan']);
    for ($i=0; $i<$count; $i++){
      $id=$this->POST['data']['travel']['id'][$i];
      if (!empty($id)) {
        $arrId['travel'] .= $id.',';
      }    
    }
    $arrId['travel'] .= '0';
    
    //Melist daftar ID Transportation yang di edit yang tidak dihapus
    $arrId['transport']='';
    $count=sizeof($this->POST['data']['transport']['tujuan']);
    for ($i=0; $i<$count; $i++){
      $id=$this->POST['data']['transport']['id'][$i];
      if (!empty($id)) {
        $arrId['transport'] .= $id.',';
      }    
    }
    $arrId['transport'] .= '0';
    
    //Melist daftar ID Budget yang di edit yang tidak dihapus
    $arrId['budget']='';
    $count=sizeof($this->POST['data']['budget']['budget_id']);
    for ($i=0; $i<$count; $i++){
      $id=$this->POST['data']['budget']['id'][$i];
      if (!empty($id)) {
        $arrId['budget'] .= $id.',';
      }    
    }
    $arrId['budget'] .= '0';
    
    $array=array( 'no_taf'=>$this->POST['no_taf'],
                  'peg_id'=>$this->POST['idPeg'],
                  'jenis_taf'=>$this->POST['tipe'],
                  'alasan'=>$this->POST['alasan'],
                  'tgl_aju'=>$this->a,
                  'total_hari'=>$this->POST['total_hari_keseluruhan'],
                  'total_anggaran'=>$this->POST['total_budget'],
                  'anggaran_transport'=>$this->POST['total_budget'],
                  'anggaran_budget'=>$this->POST['total_budget'],
                  'user_id'=>$userId,
                  'taf_id'=>$tafId
                );
    
    $this->Obj->StartTrans();
    $result = $this->Obj->Update($array);
    
    if ($result) {
      //Delete Tujuan dan Allowance yang dihapus di form
      $delete = $this->Obj->DeleteAllowanceMassal($tafId,$arrId['travel']);
      $delete = $this->Obj->DeleteTravelMassal($tafId,$arrId['travel']);
      
      //Delete Transport yang dihapus di form
      $delete = $this->Obj->DeleteTransportMassal($tafId,$arrId['transport']);
      
      //Delete Budget yang dihapus di form
      $delete = $this->Obj->DeleteBudgetMassal($tafId,$arrId['budget']);
      
      //Tujuan dan Allowance
      for ($i=0; $i<sizeof($this->POST['data']['travel']['tujuan']); $i++){
          $id=$this->POST['data']['travel']['id'][$i];
          $tujuan=$this->POST['data']['travel']['tujuan'][$i];
          $tgl_awal=$this->POST['data']['travel']['tanggal_mulai'][$i];
          $tgl_selesai=$this->POST['data']['travel']['tanggal_selesai'][$i];
          $total_hari=$this->POST['data']['travel']['total_days'][$i];
          
          if (empty($id)){
              //Menambah Tujuan Jika Belum Ada
              $result1 = $this->Obj->AddTujuan(array($tafId,$tujuan,$tgl_awal,$tgl_selesai,$total_hari,$userId));
              
              if ($result1) {
                  $taftujuanId = $this->Obj->GetLastIdTujuan();
                  $taftujuanId = $taftujuanId[0]['last_id'];
                  for ($ii=0; $ii<sizeof($this->POST['data']['allowance'][$tujuan]['total']); $ii++){
                      $kebijakanId=$this->POST['data']['allowance'][$tujuan]['kebijakan_taf_id'][$ii];
                      $total_hari=$this->POST['data']['allowance'][$tujuan]['total_hari'][$ii];
                      $anggaran=$this->POST['data']['allowance'][$tujuan]['nilai'][$ii];
                      $currId=$this->POST['data']['allowance'][$tujuan]['currency'][$ii];
                      $total_anggaran=$this->POST['data']['allowance'][$tujuan]['total'][$ii];
                      $catatan=$this->POST['data']['allowance'][$tujuan]['catatan'][$ii];
                      
                      $array1=array('taf_id'=>$tafId,
                                    'taf_tujuan_id'=>$taftujuanId,
                                    'kebijakan_id'=>$kebijakanId,
                                    'total_hari'=>$total_hari,
                                    'anggaran'=>$anggaran,
                                    'currId'=>$currId,
                                    'total_anggaran'=>$total_anggaran,
                                    'catatan'=>$catatan,
                                    'user_id'=>$userId
                                  );
                      $result2 = $this->Obj->AddAnggaran($array1);
                  }
              }
          }else{
              //Mengedit Tujuan Jika Sudah Ada
              $result1 = $this->Obj->UpdateTujuan(array($tafId,$tujuan,$tgl_awal,$tgl_selesai,$total_hari,$userId,$id));
              
              if ($result1) {
                  $taftujuanId = $id;
                  for ($ii=0; $ii<sizeof($this->POST['data']['allowance'][$tujuan]['total']); $ii++){
                      $id=$this->POST['data']['allowance'][$tujuan]['id'][$ii];
                      $kebijakanId=$this->POST['data']['allowance'][$tujuan]['kebijakan_taf_id'][$ii];
                      $total_hari=$this->POST['data']['allowance'][$tujuan]['total_hari'][$ii];
                      $anggaran=$this->POST['data']['allowance'][$tujuan]['nilai'][$ii];
                      $currId=$this->POST['data']['allowance'][$tujuan]['currency'][$ii];
                      $total_anggaran=$this->POST['data']['allowance'][$tujuan]['total'][$ii];
                      $catatan=$this->POST['data']['allowance'][$tujuan]['catatan'][$ii];
                      
                      if (empty($id)){
                          //Menambah Anggaran
                          $array1=array('taf_id'=>$tafId,
                                        'taf_tujuan_id'=>$taftujuanId,
                                        'kebijakan_id'=>$kebijakanId,
                                        'total_hari'=>$total_hari,
                                        'anggaran'=>$anggaran,
                                        'currId'=>$currId,
                                        'total_anggaran'=>$total_anggaran,
                                        'catatan'=>$catatan,
                                        'user_id'=>$userId
                                      );
                          $result2 = $this->Obj->AddAnggaran($array1);
                      }else{
                          //Mengedit Anggaran
                          $array1=array('taf_id'=>$tafId,
                                        'taf_tujuan_id'=>$taftujuanId,
                                        'kebijakan_id'=>$kebijakanId,
                                        'total_hari'=>$total_hari,
                                        'anggaran'=>$anggaran,
                                        'currId'=>$currId,
                                        'total_anggaran'=>$total_anggaran,
                                        'catatan'=>$catatan,
                                        'user_id'=>$userId,
                                        'id'=>$id
                                      );
                          $result2 = $this->Obj->UpdateAnggaran($array1);
                      }
                  }
              }
          }
      }
      
      //Transport
      for ($i=0; $i<sizeof($this->POST['data']['transport']['tujuan']); $i++){
          $id=$this->POST['data']['transport']['id'][$i];
          $jenis=$this->POST['data']['transport']['tipe'][$i];
          $tujuan=$this->POST['data']['transport']['tujuan'][$i];
          $tgl_awal=$this->POST['data']['transport']['etd_tanggal'][$i];
          $tgl_akhir=$this->POST['data']['transport']['eta_tanggal'][$i];
          $jam_awal=$this->POST['data']['transport']['etd_waktu'][$i];
          $jam_akhir=$this->POST['data']['transport']['eta_waktu'][$i];
          $nama=$this->POST['data']['transport']['nama'][$i];
          $anggaran=0;
          $catatan=$this->POST['data']['transport']['catatan'][$i];
          
          if (empty($id)){
              //Menambah Transportasi
              $array2=array('jenis_transport'=>$jenis,
                            'taf_id'=>$tafId,
                            'tgl_awal'=>$tgl_awal,
                            'tgl_akhir'=>$tgl_akhir,
                            'tujuan'=>$tujuan,
                            'nama'=>$nama,
                            'jam_awal'=>$jam_awal,
                            'jam_akhir'=>$jam_akhir,
                            'anggaran'=>$anggaran,
                            'catatan'=>$catatan,
                            'user_id'=>$userId
                          );
              
              $result3 = $this->Obj->AddTransportasi($array2);
          }else{
              //Mengedit Transportasi
              $array2=array('jenis_transport'=>$jenis,
                            'taf_id'=>$tafId,
                            'tgl_awal'=>$tgl_awal,
                            'tgl_akhir'=>$tgl_akhir,
                            'tujuan'=>$tujuan,
                            'nama'=>$nama,
                            'jam_awal'=>$jam_awal,
                            'jam_akhir'=>$jam_akhir,
                            'anggaran'=>$anggaran,
                            'catatan'=>$catatan,
                            'user_id'=>$userId,
                            'id'=>$id
                          );
              
              $result3 = $this->Obj->UpdateTransportasi($array2);
          }
          
      }
      
      //Budget
      for ($i=0; $i<sizeof($this->POST['data']['budget']['budget_id']); $i++){
          $id=$this->POST['data']['budget']['id'][$i];
          $budgetId=$this->POST['data']['budget']['budget_id'][$i];
          $bulan=$this->POST['data']['budget']['periode_bulan'][$i];
          $tahun=$this->POST['data']['budget']['periode_tahun'][$i];
          $anggaran=$this->POST['data']['budget']['amount'][$i];
          
          if (empty($id)){
              //Menambah Budget
              $array3=array('taf_id'=>$tafId,
                            'budget_id'=>$budgetId,
                            'bulan'=>$bulan,
                            'tahun'=>$tahun,
                            'anggaran'=>$anggaran,
                            'user_id'=>$userId
                          );
              
              $result4 = $this->Obj->AddBudget($array3);
          }else{
              //Mengedit Budget
              $array3=array('taf_id'=>$tafId,
                            'budget_id'=>$budgetId,
                            'bulan'=>$bulan,
                            'tahun'=>$tahun,
                            'anggaran'=>$anggaran,
                            'user_id'=>$userId,
                            'id'=>$id
                          );
              
              $result4 = $this->Obj->UpdateBudget($array3);
          }
      }
    }
    
    $this->Obj->EndTrans($result);
    
    //exit;
    if ($result){
      return $result;
    }else{
      return false;
    }
  }
	
	function InputDataTaf(){
      $check = $this->Check();
      /*echo "<pre>";
	    print_r($this->POST);
	    echo "</pre>"; exit();*/
      if ($check !== true) return $check;
      if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'add')) {
        $rs_add = $this->AddDataTaf();
        
        if ($rs_add==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
          $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtDirSpv']);
          $cc=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtMor']);
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
          $arrBody[2]['replace']='{TAF_TIPE}'; $arrBody[2]['with']=$this->Obj->GetJenisTafById($this->POST['tipe']);
          $arrBody[3]['replace']='{TAF_TGL_AJU}'; $arrBody[3]['with']=$this->ObjEmail->IndonesianDate($this->a,'YYYY-MM-DD');
          $arrBody[4]['replace']='{TAF_TOTAL_HARI}'; $arrBody[4]['with']=$this->POST['total_hari_keseluruhan'];
          $arrBody[5]['replace']='{TAF_TOTAL_ANGGARAN}'; $arrBody[5]['with']=$this->Obj->num_todisplay($this->POST['total_budget']);
          $arrBody[6]['replace']='{TAF_ALASAN}'; $arrBody[6]['with']=$_POST['alasan'];
          $arrBody[7]['replace']='{TAF_CURRENCY}'; $arrBody[7]['with']=$this->Obj->GetCurrByJenisTafId($this->POST['tipe']);
          $arrBody[8]['replace']='{TAF_NUMBER}'; $arrBody[8]['with']=$_POST['no_taf'];
          
          $body=$this->ObjEmail->getBodyEmail('email_taf_request',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_taf_request');
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
        }
        
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg'];
        if($rs_add == true){
           Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgAddSuccess."<br/>".$kirim,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgAddFail."<br/>".$kirim,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
        
      }else if ((isset($this->POST['btnsimpan'])) && ($this->POST['op'] == 'edit')) {
        $rs_update = $this->UpdateDataTaf();
        
        if ($rs_update==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
          $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtDirSpv']);
          $cc=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtMor']);
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
          $arrBody[2]['replace']='{TAF_TIPE}'; $arrBody[2]['with']=$this->Obj->GetJenisTafById($this->POST['tipe']);
          $arrBody[3]['replace']='{TAF_TGL_AJU}'; $arrBody[3]['with']=$this->ObjEmail->IndonesianDate($this->a,'YYYY-MM-DD');
          $arrBody[4]['replace']='{TAF_TOTAL_HARI}'; $arrBody[4]['with']=$this->POST['total_hari_keseluruhan'];
          $arrBody[5]['replace']='{TAF_TOTAL_ANGGARAN}'; $arrBody[5]['with']=$this->Obj->num_todisplay($this->POST['total_budget']);
          $arrBody[6]['replace']='{TAF_ALASAN}'; $arrBody[6]['with']=$_POST['alasan'];
          $arrBody[7]['replace']='{TAF_CURRENCY}'; $arrBody[7]['with']=$this->Obj->GetCurrByJenisTafId($this->POST['tipe']);
          $arrBody[8]['replace']='{TAF_NUMBER}'; $arrBody[8]['with']=$_POST['no_taf'];
          
          $body=$this->ObjEmail->getBodyEmail('email_taf_request_edited',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_taf_request_edited');
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
        }
        
        $return = $this->pageView;
        $return .= "&dataId=".$this->POST['idPeg']."&dataId2=".$this->decId;
        if($rs_update == true){
           Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgUpdateSuccess."<br/>".$kirim,$this->cssDone),Messenger::NextRequest);
           return $return;
        }else{
           Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgUpdateFail."<br/>".$kirim,$this->cssFail),Messenger::NextRequest);
           return $return;
        }
      }
   }
   
   function Delete(){
   
    $dataTaf=$this->Obj->GetDataTafDet($this->POST['idDelete']);
    $this->Obj->StartTrans();
    $result1 = $this->Obj->DeleteAllowance($this->POST['idDelete']);
    $result2 = $this->Obj->DeleteTujuan($this->POST['idDelete']);
    $result3 = $this->Obj->DeleteTransportasi($this->POST['idDelete']);
    $result4 = $this->Obj->DeleteBudget($this->POST['idDelete']);
    $deleteData = $this->Obj->Delete($this->POST['idDelete']);
    $this->Obj->EndTrans($deleteData); 
    
    if ($deleteData==true){
          //Block Untuk Kirim Email
          $dataPegawai=$this->pegawaiObj->GetDataPegawaiByUserName();
          $from=GTFWConfiguration::GetValue('application', 'email_pengirim');
          $to=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtDirSpv']);
          $cc=$this->pegawaiObj->GetEmailById($dataPegawai['pegdtMor']);
          
          $arrBody[0]['replace']='{NAMA_PEGAWAI}'; $arrBody[0]['with']=$dataPegawai['pegNama'];
          $arrBody[1]['replace']='{NIP_PEGAWAI}'; $arrBody[1]['with']=$dataPegawai['pegKodeResmi'];
          $arrBody[2]['replace']='{TAF_TIPE}'; $arrBody[2]['with']=$this->Obj->GetJenisTafById($dataTaf[0]['tafJnstafId']);
          $arrBody[3]['replace']='{TAF_TGL_AJU}'; $arrBody[3]['with']=$this->ObjEmail->IndonesianDate($dataTaf[0]['tafTglPengajuan'],'YYYY-MM-DD');
          $arrBody[4]['replace']='{TAF_TOTAL_HARI}'; $arrBody[4]['with']=$dataTaf[0]['tafTotalHariKeseluruhan'];
          $arrBody[5]['replace']='{TAF_TOTAL_ANGGARAN}'; $arrBody[5]['with']=$dataTaf[0]['tafTotalAnggaran'];
          $arrBody[6]['replace']='{TAF_ALASAN}'; $arrBody[6]['with']=$dataTaf[0]['tafAlasan'];
          $arrBody[7]['replace']='{TAF_CURRENCY}'; $arrBody[7]['with']=$this->Obj->GetCurrByJenisTafId($dataTaf[0]['tafJnstafId']);
          $arrBody[8]['replace']='{TAF_NUMBER}'; $arrBody[8]['with']=$dataTaf[0]['tafNo'];
          
          $body=$this->ObjEmail->getBodyEmail('email_taf_request_cancel',$arrBody);
          $subject=$this->ObjEmail->getSubjectEmail('email_taf_request_cancel');
          $kirim=$this->ObjEmail->kirimEmail($to,$cc,$bcc,$from,$subject,$body);
          //Akhir Kirim Email
    }
    
    if($deleteData == true) {
			Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgDeleteSuccess."<br/>".$kirim, $this->cssDone),Messenger::NextRequest);   
		}else{
			Messenger::Instance()->Send('data_taf', 'historyDataTaf', 'view', 'html', array($this->POST,$this->msgDeleteFail."<br/>".$kirim, $this->cssFail),Messenger::NextRequest);   
		}
    $return = $this->pageView;
    $return .= "&dataId=".$this->decId2;
		return $return;
   }
}

?>