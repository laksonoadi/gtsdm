<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewSatuanKerja extends HtmlResponse {
   var $unitkerjaId;
   var $Pesan;
   var $kerja;
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/satuan_kerja/template');
      $this->SetTemplateFile('view_satuan_kerja.html');
   }
   
   function ProcessRequest() {
      $msg = Messenger::Instance()->Receive(__FILE__);
      $this->Pesan = $msg[0][1];
      $this->CssPesan = $msg[0][2];
	  
	  $this->kerja = new SatuanKerja();
	  if (isset($_GET['satkerId'])){
         $satker_id = $_GET['satkerId']->Integer()->Raw();
         $return['satker_detail'] = $this->kerja->GetSatKerDetail($satker_id);//print_r($return['satker_detail']);
      }
	  $return['list'] = $this->kerja->GetListSatKerRefrensi();
      $return['tree'] = $this->kerja->GetSatuanKerjaStructure();
	  return $return;
   }
   
   function GetParentLevel($level){
      $return=$this->kerja->GetSatKerLevelRefrensi($level);
	  return $return['satkerId'];
   }
   
   function ParseTemplate($data = NULL) {
      $this->mrTemplate->AddVar('content', 'URL_SEARCH', Dispatcher::Instance()->GetUrl('satuan_kerja', 'SatuanKerja', 'view', 'html') );
      $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN KERJA');
      $this->mrTemplate->AddVar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('satuan_kerja', 'ListSatuanKerja', 'view', 'html') );

      $this->mrTemplate->AddVar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('satuan_kerja', 'inputSatuanKerja', 'view', 'html').'&op=add');
      $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_kerja|deleteSatuanKerja|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_kerja|satuanKerja|view|html').
         "&label=".Dispatcher::Instance()->Encrypt('Referensi Satuan Kerja');
	  if (isset($this->Pesan)){         
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
         $this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', (isset($this->CssPesan) ? $this->CssPesan : 'notebox-done'));
      }//print_r($data['satker_detail']);
	  if(!empty($data['satker_detail'])){
	    
	     $this->mrTemplate->SetAttribute('satker_detail', 'visibility', 'visible');
		 $this->mrTemplate->AddVar('satker_detail', 'URL_UBAH',Dispatcher::Instance()->GetUrl('satuan_kerja','inputSatuanKerja', 'view','html').'&satkerId='.$data['satker_detail']['satkerId']);
		 $this->mrTemplate->AddVar('satker_detail', 'UNIT', $data['satker_detail']['UnitName']);
		 $this->mrTemplate->AddVar('satker_detail', 'NAMA', $data['satker_detail']['satkerNama']);
		 $this->mrTemplate->AddVar('satker_detail', 'ID', $data['satker_detail']['satkerId']);
		 $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE', $url_delete.
		    "&id=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerId']).
            "&dataName=".Dispatcher::Instance()->Encrypt($data['satker_detail']['satkerNama']));
		 $this->mrTemplate->AddVar('satker_detail', 'URL_DELETE_JS', Dispatcher::Instance()->GetUrl('satuan_kerja', 'deleteSatuanKerja', 'do', 'html'));	
	  }
	  
	  if(empty($data['tree'])){
	     $this->mrTemplate->AddVar('satker', 'IS_EMPTY', 'YES');
	  }else{
	     $this->mrTemplate->AddVar('satker', 'IS_EMPTY', 'NO');
		 $this->mrTemplate->addVar("satker_tree", "SATKER_TREE_STR", $this->getTree($data['tree']));
         $this->mrTemplate->AddVar('satker_tree', 'URL_PINDAH', Dispatcher::Instance()->GetUrl('satuan_kerja', 'moveSatuanKerja', 'view', 'html'));
	  }
   
   }
   
    function getTree($tree, $_level = 0) {
        $result = '';
        
        if($_level < 1) {
            $result .= '<ul>';
        } else {
            $result .= '<ul class="sub-tree">';
        }
        
        $result .= '<li class="satker-item-checker" rel="item-disabled">';
        $result .= '<input type="checkbox" class="jstree-checkbox-raw check-all" title="Centang semua" />';
        $result .= '&nbsp;<a><small>&lt;Centang semua&gt;</small></a>';
        $result .= '</li>';
        
        $url_detail = Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html');
        $url_input = Dispatcher::Instance()->GetUrl('satuan_kerja', 'inputSatuanKerja', 'view', 'html');
        $url_delete = Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').
         "&label=".Dispatcher::Instance()->Encrypt('Referensi Satuan Kerja').
         "&urlDelete=".Dispatcher::Instance()->Encrypt('satuan_kerja|deleteSatuanKerja|do|html').
         "&urlReturn=".Dispatcher::Instance()->Encrypt('satuan_kerja|satuanKerja|view|html');
        
        $no = 1;
        $num_display = TRUE;
        $num_separator = ')';
        foreach($tree as $item) {
            $link_detail = $url_detail.'&unitId='.$item['unit_id'].'&satkerId='.$item['id'].'&smpn=';
            $link_edit = $url_input.'&satkerId='.$item['id'];
            $link_input = $url_input.'&satkerParentId='.$item['id'].'&op=add';
            $link_delete = $url_delete.'&id='.Dispatcher::Instance()->Encrypt($item['id'])."&dataName=".Dispatcher::Instance()->Encrypt($item['nama']);
            
            if(!empty($item['children'])) {
                $html_child = $this->getTree($item['children'], $_level + 1);
                $btn_delete = '';
            } else {
                $html_child = '';
                $btn_delete = ' <a class="xhr" href="'.$link_delete.'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
            }
            
            if($num_display) {
                $name = $no . $num_separator .'&nbsp;'. $item['nama'];
            } else {
                $name = $item['nama'];
            }
            
            $result .= '<li id="satker_item_'.$item['id'].'" data-id="'.$item['id'].'" data-parent-id="'.$item['parentId'].'">';
            $result .= '<input type="checkbox" name="satkerIds[]" class="jstree-checkbox-raw" value="'.$item['id'].'" />&nbsp;';
            $result .= '<a class="item-title" title="'.$item['nama'].'">'.$name.'</a>';
            
            $result .= '<div class="item-buttons">';
            $result .= ' <a class="xhr" href="'.$link_detail.'" title="Detail"><span class="glyphicon glyphicon-search"></span></a>';
            $result .= ' <a class="xhr" href="'.$link_edit.'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
            $result .= ' <a class="xhr" href="'.$link_input.'" title="Tambah Anak"><span class="glyphicon glyphicon-plus"></span></a>';
            $result .= $btn_delete;
            // $result .= ' <a class="btn-edit move_descendants" href="'.$url_move_kids.'" title="Move descendants?"></a>';
            $result .= '</div>';
            
            $result .= $html_child;
            $result .= '</li>';
            $no++;
        }
        $result .= '</ul>';
        return $result;
    }
}


?>