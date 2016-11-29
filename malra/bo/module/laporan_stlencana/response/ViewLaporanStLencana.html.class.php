<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_stlencana/business/laporanSL.class.php';
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';
   
class ViewLaporanStLencana extends HtmlResponse{
  function TemplateModule(){
    $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_stlencana/'.GTFWConfiguration::GetValue('application', 'template_address').'');
    $this->SetTemplateFile('view_laporan_stlencana.html');
  }
   
  function GetLabelFromCombo($ArrData,$Nilai){
    for ($i=0; $i<sizeof($ArrData); $i++){
      if ($ArrData[$i]['id']==$Nilai) return $ArrData[$i]['name'];
    }
    return '--Semua--';
  }
   
  function ProcessRequest(){
    $this->Obj=new LaporanSL;
    $this->ObjSatker = new SatuanKerja();
    
    $this->Obj->getVariabelGlobal();
    $this->judul=$this->Obj->judul;
    $this->tanggal=$this->Obj->IndonesianDate(date('Y-m-d'),"YYYY-MM-DD");
    
      if(isset($_POST['cari'])) {
      for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
        $this->Obj->filter[$this->Obj->varFilter[$i]] = strval($_POST[$this->Obj->varFilter[$i]]);
      }
      
      $this->Obj->berdasarkan = strval($_POST['periode_kerja']);
      $this->Obj->urutan = strval($_POST['urutan']);
      
      }elseif(isset($_GET['cari'])) {
      for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
        $this->Obj->filter[$this->Obj->varFilter[$i]] = Dispatcher::Instance()->Decrypt(strval($_GET[$this->Obj->varFilter[$i]]));
      }
      
      $this->Obj->berdasarkan = strval($_GET['berdasarkan']);
      $this->Obj->urutan = strval($_GET['urutan']);
      
      }else {
        for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
        $this->Obj->filter[$this->Obj->varFilter[$i]] = 'all';
      }
      $this->Obj->berdasarkan = 'nama';
      $this->Obj->urutan = 'ASC';
      }
    
    $this->Obj->getVariabelGlobal();
    
    $field=array_keys($this->Obj->field);
    $this->lebarKolom=20;
    
    $filterfieldget = '&berdasarkan='.$this->Obj->berdasarkan.'&urutan='.$this->Obj->urutan;

    for ($i=0; $i<sizeof($field)-1; $i++){
      $this->field[$i]['nama']=$field[$i];
      $this->field[$i]['caption']=$this->Obj->caption[$field[$i]][0];
      
      if ((strval($_POST[$field[$i]])=='on')&&(isset($_POST['cari']))) {
        $this->showKolom['v'.$field[$i]]='';
        $this->field[$i]['checked']='checked=true';
        $this->lebarKolom += 50;
      }elseif ((strval($_GET[$field[$i]])=='on')&&(isset($_GET['cari']))) {
        $this->showKolom['v'.$field[$i]]='';
        $this->field[$i]['checked']='checked=true';
        $this->lebarKolom += 50;
      }elseif(!isset($_GET['cari']) && !isset($_POST['cari'])){
        $this->showKolom['v'.$field[$i]]='';
        $this->field[$i]['checked']='checked=true';
        $this->lebarKolom += 50;
      }else{
        $this->showKolom['v'.$field[$i]]='none';
        $this->field[$i]['checked']='';
      }
      
      if ($this->field[$i]['checked']!=''){
        $filterfieldget .='&'.$field[$i].'=on';
      }
    }
    
    
    //create paging 
    $this->filterget='&cari=' . Dispatcher::Instance()->Encrypt(1);
    for ($i=0; $i<sizeof($this->Obj->varFilter); $i++){
      $this->filterget .='&'.$this->Obj->varFilter[$i].'='.Dispatcher::Instance()->Encrypt($this->Obj->filter[$this->Obj->varFilter[$i]]);
    }
    
    $this->filterget .= $filterfieldget;
    // $this->Obj->SetDebugOn();
    $totalData = $this->Obj->GetCountDaftarPegawai();
    // print_r($totalData);exit();
    // echo $this->Obj->GetLastError();
      $itemViewed = 15;
      $currPage = 1;
      $startRec = 0 ;
      if(isset($_GET['page'])) {
        $currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
        $startRec =($currPage-1) * $itemViewed;
      }
      
      $dataPegawai = $this->Obj->GetDaftarPegawai($startRec, $itemViewed);
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType 
        .$this->filterget);
  
      Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$totalData, $url, $currPage), Messenger::CurrentRequest);
    //create paging end here
    $periode = $_POST['periode_kerja'];
    $return['periode'] = $periode;
    $return['dataPegawai']= $dataPegawai;
        $return['startRec']=$startRec;
      return $return;
   }
   
   function ParseTemplate($data = NULL)
   {
    if($this->Pesan){
      $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
      $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $this->css);
    }
        
    $this->mrTemplate->AddVar('content', 'JUDUL_UNIT_KERJA', $this->label_unit_kerja);
    $this->mrTemplate->AddVar('content', 'JUDUL_PANGKAT_GOLONGAN', $this->label_pangkat_golongan);
    $this->mrTemplate->AddVar('content', 'JUDUL_JENIS_KELAMIN', $this->label_jenis_kelamin);
    $this->mrTemplate->AddVar('content', 'JUDUL_JENIS_PEGAWAI', $this->label_jenis_pegawai);
    if($data['periode']=='30'){
    $this->mrTemplate->AddVar('content', 'SELECTED_30', 'CHECKED=""');
    }else if($data['periode']=='20'){
      $this->mrTemplate->AddVar('content', 'SELECTED_20', 'CHECKED=""');
    }else{
      $this->mrTemplate->AddVar('content', 'SELECTED_10', 'CHECKED=""');
    }
    
    $this->mrTemplate->AddVars('content', $this->showKolom, '');
    $this->mrTemplate->AddVar('content', 'LEBAR_KOLOM',$this->lebarKolom);
    $this->mrTemplate->AddVar('content', 'SELECTED_'.$this->Obj->urutan,'SELECTED');
    $this->mrTemplate->AddVar('content', 'SELECTED_'.strtoupper($this->Obj->berdasarkan),'SELECTED');
    
    for ($i=0; $i<sizeof($this->field); $i++) {
        $this->mrTemplate->AddVars('field_item', $this->field[$i], 'FIELD_');
        $this->mrTemplate->parseTemplate('field_item', 'a');   
      }
      
    $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('laporan_stlencana', 'laporanStLencana', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'URL_EXCEL', Dispatcher::Instance()->GetUrl('laporan_stlencana', 'laporanStLencana', 'view', 'xls')
        .$this->filterget);
      
      
        if (empty($data['dataPegawai'])) {
          $this->mrTemplate->AddVar('table_list', 'EMPTY', 'YES');
      } else {
          $decPage = Dispatcher::Instance()->Decrypt($_REQUEST['page']);
          $encPage = Dispatcher::Instance()->Encrypt($decPage);
          $this->mrTemplate->AddVar('table_list', 'EMPTY', 'NO');
          $dataPegawai = $data['dataPegawai'];
          $total=0;
          for ($i=0; $i<sizeof($dataPegawai); $i++) {
            $no = $i+1+$data['startRec'];
            $dataPegawai[$i]['no'] = $no;
            if ($no % 2 != 0) {
            $dataPegawai[$i]['class_name'] = 'table-common-even';
          }else{
                $dataPegawai[$i]['class_name'] = '';
              }
          
          $this->mrTemplate->AddVars('table_item', $this->showKolom, '');
            $this->mrTemplate->AddVars('table_item', $dataPegawai[$i], '');
            $this->mrTemplate->parseTemplate('table_item', 'a');   
          }
    }      
   }
}
   

?>