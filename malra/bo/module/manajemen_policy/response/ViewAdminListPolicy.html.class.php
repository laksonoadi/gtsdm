<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/manajemen_policy/business/ManejemenPolicy.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';


class ViewAdminListPolicy extends HtmlResponse {
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/manajemen_policy/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_admin_list_policy.html');
   }
   
   function ProcessRequest() {
      $objectPolicy = new ManejemenPolicy();
      $objectSatuanKerja = new SatuanKerja();
      
      if(isset($_GET['page'])){
         $page = $_GET['page']->Raw();
      }else{
         $page = 1;
      }
      
      if($_POST || isset($_GET['cari'])) {
  			if(isset($_POST['nama_deskripsi'])) {
  				$nama_deskripsi = $_POST['nama_deskripsi'];
  				$satuan_kerja = $_POST['satuan_kerja'];
				  $jenis_policy = $_POST['jenis_policy'];
  			} elseif(isset($_GET['nama_deskripsi'])) {
  				$nama_deskripsi = Dispatcher::Instance()->Decrypt($_GET['nama_deskripsi']);
  				$satuan_kerja = Dispatcher::Instance()->Decrypt($_GET['satuan_kerja']);  
				  $jenis_policy = Dispatcher::Instance()->Decrypt($_GET['jenis_policy']);    
  			} else {
  				$nama_deskripsi = '';
  				$satuan_kerja = 'all';  
				  $jenis_policy = 'all';  
  			}
  		}
      
      $count = $objectPolicy->CountPolicy($nama_deskripsi, $satuan_kerja, $jenis_policy);
      
      $itemViewed = 20;
  		$currPage = 1;
  		$startRec = 0 ;
  		if(isset($_GET['page'])) {
  			$currPage = (string)$_GET['page']->StripHtmlTags()->SqlString()->Raw();
  			$startRec =($currPage-1) * $itemViewed;
  		}
  		
      $GLOBALS['parameters_set'] = array(
         'itemviewed' => 20/*GTFWConfiguration::GetValue('application', 'paging')*/,
         'totitems' => $count,
         'pagingurl' => Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,
                        Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction,
                        Dispatcher::Instance()->mType),
         'page' => $page
      );
      $offset = $GLOBALS['parameters_set']['itemviewed']*($page-1);
      $limit = $GLOBALS['parameters_set']['itemviewed'];
      $data['list_policy'] = $objectPolicy->ListPolicy($nama_deskripsi, $satuan_kerja, $jenis_policy, $startRec, $itemViewed);
 
      $url = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, Dispatcher::Instance()->mSubModule, Dispatcher::Instance()->mAction, Dispatcher::Instance()->mType .
        '&nama_deskripsi=' . Dispatcher::Instance()->Encrypt($nama_deskripsi).
        '&satuan_kerja=' . Dispatcher::Instance()->Encrypt($satuan_kerja).
	      '&jenis_policy=' . Dispatcher::Instance()->Encrypt($jenis_policy).
        '&cari=' . Dispatcher::Instance()->Encrypt(1));
  
  		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed,$count, $url, $currPage), Messenger::CurrentRequest);
	    //create paging end here
	
	    $arrSatuanKerja = $objectSatuanKerja->GetComboSatuanKerja();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'satuan_kerja', array('satuan_kerja', $arrSatuanKerja, $satuan_kerja, 'true', ''), Messenger::CurrentRequest);

	    $arrJenisPolicy = $objectPolicy->GetComboJenisPolicy();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'jenis_policy', array('jenis_policy', $arrJenisPolicy, $jenis_policy, 'true', ''), Messenger::CurrentRequest);

      $nav[0]['url']='';
      $nav[0]['menu']='';
      $title = "Policy & Regulation History";
      
      Messenger::Instance()->SendToComponent('breadcrump', 'breadcrump', 'view', 'html', 'breadcrump', array($title,$nav,'breadcrump','hidden',''), Messenger::CurrentRequest);
      
      $data['search']['nama_deskripsi'] = $nama_deskripsi;
      $data['search']['satuan_kerja'] = $satuan_kerja;
      $data['search']['jenis_policy'] = $jenis_policy;

      return $data;

   }
   
   function ParseTemplate($data = NULL) {
      $objectPolicy = new ManejemenPolicy();
      
      if (($_GET['op']=='edit')&&($_GET['sukses']==1)){
          $message='Perubahan data berhasil dilakukan';
          $css='notebox-done';
      }else if (($_GET['op']=='edit')&&($_GET['sukses']==0)){
          $message='Perubahan data gagal dilakukan';
          $css='notebox-warning';
      }else if (($_GET['op']=='add')&&($_GET['sukses']==1)){
          $message='Penambahan data berhasil dilakukan';
          $css='notebox-done';
      }else if (($_GET['op']=='add')&&($_GET['sukses']==0)){
          $message='Penambahan data gagal dilakukan';
          $css='notebox-warning';
      }else if (($_GET['op']=='delete')&&($_GET['sukses']==1)){
          $message='Penghapusan data berhasil dilakukan';
          $css='notebox-done';
		}else if (($_GET['op']=='delete')&&($_GET['sukses']==1)){
			 $message='Maaf Data Tidak Dapat Dihapus, Hapus File Terlebih Dahulu';
          $css='notebox-warning';
      }else if (($_GET['op']=='delete')&&($_GET['sukses']==0)){
          $message='Penghapusan data gagal dilakukan';
          $css='notebox-warning';
      }
	  
	  
      
      if (!empty($message)){
         $this->mrTemplate->setAttribute('message','visibility','visible');
         $this->mrTemplate->addVar('message','MESSAGE',$message);
         $this->mrTemplate->addVar('message','CSS',$css);
      }
      
      $search = $data['search'];
      
		  $this->mrTemplate->AddVar('content', 'NAMA_DESKRIPSI', $search['nama_deskripsi']);
      
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('manajemen_policy', 'adminListPolicy', 'view', 'html').'&nama_deskripsi=' . Dispatcher::Instance()->Encrypt($search['nama_deskripsi'])
            .'&cari=' . Dispatcher::Instance()->Encrypt(1)
            .'&satuan_kerja=' . Dispatcher::Instance()->Encrypt($search['satuan_kerja'])
	          .'&jenis_policy=' . Dispatcher::Instance()->Encrypt($search['jenis_policy']));
	     
      if(!empty($data['list_policy'][0]['policyId'])){
         $this->mrTemplate->setAttribute('list_policy','visibility','visible');
         $no = 1;
         foreach($data['list_policy'] as $policy){
            $policy['no']=$no;
            $policy['policyIsAktif'] = $policy['policyIsAktif'] == '1' ? 'Active' : 'Not Active';
            $policy['policyTanggalPolicy'] = $objectPolicy->IndonesianDate($policy['policyTanggalPolicy'],'YYYY-MM-DD');
            $tanggal=explode(' ',$policy['policyTanggalPosting']);
            $policy['policyTanggalPosting'] = $tanggal[1].'<br/>'.$objectPolicy->IndonesianDate($tanggal[0],'YYYY-MM-DD');
            
            $urlEdit = Dispatcher::Instance()->GetUrl('manajemen_policy','UpdatePolicy','View','html').'&id='.$policy['policyId'];
            $this->mrTemplate->addVar('list_policy','URL_EDIT',$urlEdit);
            $urlDetail = Dispatcher::Instance()->GetUrl('manajemen_policy','DetailPolicy','View','html').'&id='.$policy['policyId'];
            $this->mrTemplate->addVar('list_policy','URL_DETAIL',$urlDetail);
            $urlDelete = Dispatcher::Instance()->GetUrl('manajemen_policy','DeletePolicy','View','html').'&id='.$policy['policyId'];
				$this->mrTemplate->clearTemplate('empty_file_upload');
            if ($policy['total_file'] != '0') {
				$this->mrTemplate->setAttribute('empty_file_upload','visibility','hidden');
					}else{
				$this->mrTemplate->setAttribute('empty_file_upload','visibility','visible');
				}
				$this->mrTemplate->addVar('empty_file_upload','URL_DELETE',$urlDelete);
            $this->mrTemplate->addVar('list_policy','NO',$no);
            $this->mrTemplate->addVar('list_policy','SATKERNAMA',$policy['satkerNama']);
            $this->mrTemplate->addVar('list_policy','JNSPOLICYNAMA',$policy['jnspolicyNama']);
            $this->mrTemplate->addVar('list_policy','POLICYNAMA',$policy['policyNama']);
            $this->mrTemplate->addVar('list_policy','POLICYTANGGALPOLICY',$policy['policyTanggalPolicy']);
            $this->mrTemplate->addVar('list_policy','POLICYISAKTIF',$policy['policyIsAktif']);
            $this->mrTemplate->addVar('list_policy','POLICYKETERANGAN',$policy['policyKeterangan']);
            $this->mrTemplate->addVar('list_policy','TOTAL_FILE',$policy['total_file']);
            #$this->mrTemplate->addVars('list_policy',$policy,'');
            
            $this->mrTemplate->parseTemplate('list_policy','a');
            $no++;
         }
      }else{
         $this->mrTemplate->setAttribute('empty_policy','visibility','visible');
      }
      
      $urlAdd = Dispatcher::Instance()->GetUrl('manajemen_policy','AddPolicy','View','html');
      $this->mrTemplate->addVar('toolbar','URL_ADD',$urlAdd);
      $this->mrTemplate->parseTemplate('toolbar');
   }
}
?>