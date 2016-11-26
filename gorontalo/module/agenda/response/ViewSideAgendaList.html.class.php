<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/agenda/business/Agenda.class.php';

class ViewSideAgendaList extends HtmlResponse {
   function TemplateModule() {
         $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
            'module/agenda/template');
         $this->SetTemplateFile('view_side_agenda_list.html');
   }

   function ProcessRequest() {
         $params = $this->mComponentParameters;
         
         // Display 5 by default
         $num = isset($params['num']) ? (int)$params['num'] : 5;
         
         $list = new Agenda();
         
         return $list->ListBeberapaAgenda2($num);
   }

   function ParseTemplate($data) {
      $list = new Agenda();
      if(!empty($data)){
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'NO');
         foreach ($data as $item) {
            $item['url_agenda'] = Dispatcher::Instance()->GetUrl('agenda', 'Agenda', 'View', 'html').'&id='.$item['ID'];
            $item['date_posted'] = $list->IndonesianDate($item['MULAI'], 'YYYY-MM-DD');
            $this->mrTemplate->AddVars("item", $item);
            $this->mrTemplate->ParseTemplate('item', 'a');
         }
      } else {
         $this->mrTemplate->addVar('list', 'IS_EMPTY', 'YES');
      }
   }
   
   
}
?>
