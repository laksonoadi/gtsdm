<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/berita/business/Berita.class.php';

class ViewBeritaList extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/berita/template');
        $this->SetTemplateFile('view_berita_list.html');
    }

   function ProcessRequest() {
        $list = new Berita();
        return $list->ListBerita();
    }

    function ParseTemplate($data) {
      $list = new Berita();
      if(!empty($data)) {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'NO');
         foreach ($data as $item) {
            $this->mrTemplate->AddVar("item", "TITLE", $item['TITLE']);
            $this->mrTemplate->AddVar("item", "URL_BERITA", Dispatcher::Instance()->GetUrl('berita', 'Berita', 'View', 'html').'&id='.$item['ID']);
            $this->mrTemplate->AddVar("item", "DATE_POSTED", $list->IndonesianDate($item['DATE_POSTED'], 'YYYY-MM-DD'));
            $this->mrTemplate->ParseTemplate('item', 'a');
         }
      } else {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'YES');
      }
    }
}
?>
