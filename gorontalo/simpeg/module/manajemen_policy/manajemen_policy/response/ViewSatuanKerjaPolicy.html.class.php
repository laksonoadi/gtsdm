<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';


class ViewSatuanKerjaPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_satuan_kerja_policy.html');
   }
   
   function ProcessRequest() {
      $objectPolicy = new ManejemenPolicy();
      
      if(isset($_GET['page'])){
         $page = $_GET['page']->Raw();
      }else{
         $page = 1;
      }
      
      
      $count = $objectPolicy->CountSatkerPolicy();
      $GLOBALS['parameters_set'] = array(
         'itemviewed' => 10/*GTFWConfiguration::GetValue('application', 'paging')*/,
         'totitems' => $count,
         'pagingurl' => Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,
                        Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction,
                        Dispatcher::Instance()->mType),
         'page' => $page
      );
      $offset = $GLOBALS['parameters_set']['itemviewed']*($page-1);
      $limit = $GLOBALS['parameters_set']['itemviewed'];
      $data['list_satker_policy'] = $objectPolicy->ListSatkerPolicy($offset,$limit);
      
      $nav[0]['url']='';
      $nav[0]['menu']='';
      $title = "Regulation Department History";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','hidden',''), Messenger::CurrentRequest);
      
      return $data;

   }
   
   function ParseTemplate($data = NULL) {
      $objectPolicy = new ManejemenPolicy();
      
      if (($_GET['op']=='edit')&&($_GET['sukses']==1)){
          $message='Edit Data Successfully';
          $css='notebox-done';
      }else if (($_GET['op']=='edit')&&($_GET['sukses']==0)){
          $message='Edit Data Failure';
          $css='notebox-warning';
      }else if (($_GET['op']=='add')&&($_GET['sukses']==1)){
          $message='Add Data Successfully';
          $css='notebox-done';
      }else if (($_GET['op']=='add')&&($_GET['sukses']==0)){
          $message='Add Data Failure';
          $css='notebox-warning';
      }else if (($_GET['op']=='delete')&&($_GET['sukses']==1)){
          $message='Delete Data Successfully';
          $css='notebox-done';
      }else if (($_GET['op']=='delete')&&($_GET['sukses']==0)){
          $message='Delete Data Failure';
          $css='notebox-warning';
      }
      
      if (!empty($message)){
         $this->mrTemplate->setAttribute('message','visibility','visible');
         $this->mrTemplate->addVar('message','MESSAGE',$message);
         $this->mrTemplate->addVar('message','CSS',$css);
      }
      
      if(!empty($data['list_satker_policy'][0]['satkerpolicyId'])){
         $this->mrTemplate->setAttribute('list_satker_policy','visibility','visible');
         $no = 1;
         foreach($data['list_satker_policy'] as $satkerpolicy){
            $satkerpolicy['no']=$no;
            $satkerpolicy['satkerpolicyStatus'] = $satkerpolicy['satkerpolicyStatus'] == 'Aktif' ? 'Aktif' : 'Non Aktif';
            $satkerpolicy['satkerpolicyTgl'] = $objectPolicy->IndonesianDate($satkerpolicy['satkerpolicyTgl'],'YYYY-MM-DD');
            $tanggal=explode(' ',$satkerpolicy['satkerpolicyTgl']);
            $this->mrTemplate->addVars('list_satker_policy',$satkerpolicy,'');
            
            $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_policy','UpdateSatuanKerjaPolicy','View','html').'&id='.$satkerpolicy['satkerpolicyId'];
            $this->mrTemplate->addVar('list_satker_policy','URL_EDIT',$urlEdit);
            $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_policy','DeleteSatuanKerjaPolicy','View','html').'&id='.$satkerpolicy['satkerpolicyId'];
            $this->mrTemplate->addVar('list_satker_policy','URL_DELETE',$urlDelete);
            
            $this->mrTemplate->parseTemplate('list_satker_policy','a');
            $no++;
         }
      }else{
         $this->mrTemplate->setAttribute('empty_satker_policy','visibility','visible');
      }
      
      $urlAdd = Dispatcher::Instance()->GetUrl('manajemen_policy','AddSatuanKerjaPolicy','View','html');
      $this->mrTemplate->addVar('toolbar','URL_ADD',$urlAdd);
      $this->mrTemplate->parseTemplate('toolbar');
   }
}
?>