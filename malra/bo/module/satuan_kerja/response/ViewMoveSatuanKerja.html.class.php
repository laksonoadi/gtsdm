<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/satuan_kerja/business/satuan_kerja.class.php';

class ViewMoveSatuanKerja extends HtmlResponse {
   var $Data;
   var $Pesan;
   var $Op;
   
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot') .
         'module/satuan_kerja/template');
      $this->SetTemplateFile('view_move_satuan_kerja.html');
   }
   
   function ProcessRequest() {
        $msg = Messenger::Instance()->Receive(__FILE__);
        $post = $_POST->AsArray();
        $get = $_GET->AsArray();
        $messenger = isset($msg[0][0]) ? $msg[0][0] : NULL;
        $this->Pesan = isset($msg[0][1]) ? $msg[0][1] : NULL;
        
        if(!empty($messenger['satkerIds'])) {
            $ids = $messenger['satkerIds'];
        } elseif(!empty($post['satkerIds'])) {
            $ids = $post['satkerIds'];
        } elseif(!empty($get['satkerIds'])) {
            $ids = $get['satkerIds'];
        } else {
            Messenger::Instance()->Send('satuan_kerja', 'satuanKerja', 'view', 'html', array(NULL, 'Pilih data yang akan dipindah terlebih dahulu.', 'notebox-alert'), Messenger::NextRequest);
            $this->RedirectTo(Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html'));
        }
        
        $return['ids'] = array();
        if(is_array($ids)) {
            $tmpArr = $ids;
        } else {
            $tmpArr = explode('|', $ids);
        }
        
        foreach($tmpArr as $k => $v) {
            $return['ids'][] = $v;
        }
        
        $ObjSatker = new SatuanKerja();
        $return['tree'] = $ObjSatker->GetSatuanKerjaStructure();
        return $return;
   }
   
   function ParseTemplate($data = NULL) {
      if (isset ($this->Pesan)) {
         $this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
         $this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $this->Pesan);
      }
      
      $this->mrTemplate->addVar("content", "SATKER_TREE_STR", $this->getTree($data['tree'], $data['ids']));
      foreach($data['ids'] as $id) {
          $this->mrTemplate->addVar("satker_ids", "ID", $id);
          $this->mrTemplate->parseTemplate("satker_ids", "a");
          $this->mrTemplate->addVar("satker_opento", "ID", $id);
          $this->mrTemplate->parseTemplate("satker_opento", "a");
      }
	  
	  $this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('satuan_kerja', 'moveSatuanKerja', 'do', 'html'));
	  $this->mrTemplate->AddVar('content', 'URL_BACK', Dispatcher::Instance()->GetUrl('satuan_kerja', 'satuanKerja', 'view', 'html'));
	  
	  $this->mrTemplate->AddVar('content', 'TITLE', 'REFERENSI SATUAN KERJA');
   }
   
    function getTree($tree, $disabled, $_level = 0) {
        $result = '';
        
        if($_level < 1) {
            $result .= '<ul>';
        } else {
            $result .= '<ul class="sub-tree">';
        }
        
        $no = 1;
        $num_display = TRUE;
        $num_separator = ')';
        foreach($tree as $item) {
            if(!empty($item['children'])) {
                $html_child = $this->getTree($item['children'], $disabled, $_level + 1);
            } else {
                $html_child = '';
            }
            
            if($num_display) {
                $name = $no . $num_separator .'&nbsp;'. $item['nama'];
            } else {
                $name = $item['nama'];
            }
            
            if(in_array($item['id'], $disabled)) {
                $result .= '<li id="satker_item_'.$item['id'].'" rel="item-disabled">';
            } else {
                $result .= '<li id="satker_item_'.$item['id'].'">';
                $result .= '<input type="radio" name="parentId" class="jstree-checkbox-raw" value="'.$item['id'].'" />&nbsp;';
            }
            $result .= '<a class="item-title" title="'.$item['nama'].'">'.$name.'</a>';
            $result .= $html_child;
            $result .= '</li>';
            $no++;
        }
        $result .= '</ul>';
        return $result;
    }
}

?>