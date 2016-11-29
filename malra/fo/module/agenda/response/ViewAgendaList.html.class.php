<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/agenda/business/Agenda.class.php';

class ViewAgendaList extends HtmlResponse {
    function TemplateModule() {
        $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/agenda/template');
        $this->SetTemplateFile('view_agenda_list.html');
    }

   function ProcessRequest() {
        $list = new Agenda();
        return $list->ListAgenda();
    }

    function ParseTemplate($data) {
      $list = new Agenda();
      if(!empty($data)){
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'NO');
         foreach ($data as $item) {
            $this->mrTemplate->AddVar("item", "TITLE", $item['TITLE']);
            $this->mrTemplate->AddVar("item", "URL_AGENDA", Dispatcher::Instance()->GetUrl('agenda', 'Agenda', 'View', 'html').'&id='.$item['ID']);
            $this->mrTemplate->AddVar("item", "DATE_POSTED", $list->IndonesianDate($item['MULAI'], 'YYYY-MM-DD'));
            $this->mrTemplate->ParseTemplate('item', 'a');
         }
      } else {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'YES');
      }
    }
}
?>
