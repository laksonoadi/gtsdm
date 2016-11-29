<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/laporan_struktur_organisasi/business/laporan.class.php';

// require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewLaporanStrukturOrganisasi extends HtmlResponse
{
   function TemplateModule()
   {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/laporan_struktur_organisasi/'.GTFWConfiguration::GetValue('application', 'template_address').'');
      $this->SetTemplateFile('view_laporan_struktur_organisasi.html');
   }

   function ProcessRequest()
   {
      $this->Obj= new Laporan;

      // $objSatker = new SatuanKerja();
	    
  		if(isset($_POST['unit_kerja'])) {
  				$this->unit_kerja = $_POST['unit_kerja'];
  		} else {
  				$this->unit_kerja = '0';
  		}
      // print_r($this->unit_kerja);
      // print_r($_SESSION);
  		
  		//Ini yang mengatur multi unit by Wahyono
  // 		if ($_SESSION['unit_id']==1) {
		// 	$true='true';
		// }else{
		// 	if ($this->unit_kerja=='all') $this->unit_kerja=$_SESSION['unit_kerja'];
		// }
		
  		$this->ComboUnitKerja = $this->Obj->GetComboUserUnitKerja();
      
  		Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'unit_kerja', 
        array('unit_kerja', $this->ComboUnitKerja, $this->unit_kerja, 'false', 'style="width:700px"'), Messenger::CurrentRequest);
  		
  		$struktur = $this->Obj->GetDataStruktur($this->unit_kerja);

      if(isset($struktur[0]['id']))
      $getTree = $this->Obj->GetDataStrukturTree($struktur[0]['id']);
      
      $judul_struktur = $this->Obj->GetNameStruktur($this->unit_kerja);

  		$return['struktur'] = $struktur;
      $return['struktree'] = $getTree;
      $return['judul_struktur'] = $judul_struktur;
        
  		return $return;
   }
   
   function ParseTemplate($data = NULL)
   {

    $this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('laporan_struktur_organisasi', 'LaporanStrukturOrganisasi', 'view', 'html') );
    $this->mrTemplate->AddVar('content', 'ORG', isset($data['judul_struktur'][0]['name'])?$data['judul_struktur'][0]['name']:'');

      if (empty($data['struktur'])) {
         $this->mrTemplate->AddVar('struktur', 'DATA_EMPTY', 'YES');
      } else {
         
         $this->mrTemplate->AddVar('struktur', 'DATA_EMPTY', 'NO');

      $var = '';
         
      // foreach ($data['struktur'] as $key1 => $val) {
        $this->mrTemplate->AddVar('data','LIDATA', '<a href="#"><b>'.$data['struktur']['0']['satker'].'<br/>'.$data['struktur']['0']['jabatan'].'</b><br/> '.$data['struktur']['0']['nama'].'<br/> '.$data['struktur']['0']['nip'].'</a>');

          /* $var .=  '<ul>';
          foreach ($data['struktree'] as $key => $v2) {
            
            $var .= '<li>';

            $var .= '<a href="#"><b>'.$v2['satker'].'<br/>'.$v2['jabatan'].'</b><br/> '.$v2['nama'].'<br/> '.$v2['nip'].'</a>';          

            $child = $v2['child'];
            if(count($child) > 0){
              $var .= '<ul>';
              foreach ($child as $keys => $v3) {
                     $var .= '<li><a href="#"><b>'.$v3['satker'].'<br/>'.$v3['jabatan'].'</b><br/> '.$v3['nama'].'<br/> '.$v3['nip'].'</a></li>';   
              }
              $var .= '</ul>';
            }
            $var .= '</li>';
            
          }

          $var .= '</ul>'; */

        // $this->mrTemplate->AddVar('data','LITWODATA', $var);
        $this->mrTemplate->AddVar('data','LITWODATA', $this->getStrukturTree($data['struktree']));
        // print_r($data['struktree']);exit();
        $this->mrTemplate->parseTemplate('data', 'a'); 

      // }
         // foreach ($data['struktur'] as $key => $val) {

         // $this->mrTemplate->parseTemplate('data', 'a'); 
         // }

      }
   }
   
   function getStrukturTree($items, $_level = 1)
   {
      $html = '';
      
      $html .= '<ul>';
      foreach($items as $item) {
         $html .= '<li>';
         $html .= '<a href="'.Dispatcher::Instance()->GetUrl('analisa_jabatan', 'Anggota', 'view', 'html').'&id='.$item['id'].'"><b>'.$item['satker'].'<br/>'.$item['jabatan'].'</b><br/> '.$item['nama'].'<br/> '.$item['nip'].'</a>';
         if(!empty($item['child'])) {
            $html .= $this->getStrukturTree($item['child'], $_level + 1);
         }
         $html .= '</li>';
      }
      $html .= '</ul>';
      return $html;
   }
}
   

?>