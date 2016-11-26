<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/agenda/business/Agenda.class.php';

class ViewMainAgendaList extends HtmlResponse {
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/agenda/template');
      $this->SetTemplateFile('view_main_agenda_list.html');
   }

   function ProcessRequest() {
      $numDataPerPage = 5; //GTFWConfiguration::GetValue('application', 'paging');
      $page = (isset($_GET['opac_page'])) ? $_GET['opac_page'] : 1;
      $offset = ($page - 1) * $numDataPerPage;
      $list = new Agenda();
      
      $return['headline']=$list->ListAgendaTerbaru();
      $return['list']=$list->ListBeberapaAgenda();
      return $return;
   }
   
   function ringkasan($text,$jkata){
		$text = preg_replace(
					array(
						// Remove invisible content
						'@<head[^>]*?>.*?</head>@siu',
						'@<style[^>]*?>.*?</style>@siu',
						'@<script[^>]*?.*?</script>@siu',
						'@<object[^>]*?.*?</object>@siu',
						'@<embed[^>]*?.*?</embed>@siu',
						'@<applet[^>]*?.*?</applet>@siu',
						'@<noframes[^>]*?.*?</noframes>@siu',
						'@<noscript[^>]*?.*?</noscript>@siu',
						'@<noembed[^>]*?.*?</noembed>@siu',
						// Add line breaks before and after blocks
						'@</?((address)|(blockquote)|(center)|(del))@iu',
						'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
						'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
						'@</?((table)|(th)|(td)|(caption))@iu',
						'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
						'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
						'@</?((frameset)|(frame)|(iframe))@iu',
						),
						array(
						' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
						"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
						"\n\$0", "\n\$0",
						),
						$text );
		$text=strip_tags(str_replace( '&nbsp' , '' , strip_tags( $text ) ));
		$kata = explode(' ', $text);
		if (sizeof($kata)<=$jkata) { 
			$jkata=sizeof($kata);
		}
		
		$text='';
		for ($i=0; $i<$jkata; $i++){
			$text.= ' '.$kata[$i];
		}
		
		return $text.'';
   }

   function ParseTemplate($data) {
      $list = new Agenda();
      $item=$data['headline'];
      $this->mrTemplate->AddVar("content","TITLE",$item['TITLE']);
      $this->mrTemplate->AddVar("content","RINGKAS",$this->ringkasan($item['RINGKAS'],50));
      $this->mrTemplate->AddVar("content","TEMPAT",$item['TEMPAT']);
      $this->mrTemplate->AddVar("content","MULAI",$list->IndonesianDate($item['MULAI'],'YYYY-MM-DD'));
      $this->mrTemplate->AddVar("content","SELESAI",$list->IndonesianDate($item['SELESAI'],'YYYY-MM-DD'));
      $this->mrTemplate->AddVar("content","URL_AGENDA",Dispatcher::Instance()->GetUrl('agenda','Agenda','View','html').'&id='.$item['ID']);
      $waktu=explode(' ',$item['DATE_POSTED']);
      $item['DATE_POSTED']=$waktu[0];
      $this->mrTemplate->AddVar("content","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD').' '.$waktu[1]);

      $data=$data['list'];
      if(!empty($data)){
         foreach ($data as $item) {
            $this->mrTemplate->AddVar("agendalist","TITLE",$item['TITLE']);
            $this->mrTemplate->AddVar("agendalist","URL_AGENDA",Dispatcher::Instance()->GetUrl('agenda','Agenda','View','html').'&id='.$item['ID']);
            $waktu=explode(' ',$item['DATE_POSTED']);
            $item['DATE_POSTED']=$waktu[0];
            //$this->mrTemplate->AddVar("agendalist","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD').' '.$waktu[1]);
            $this->mrTemplate->AddVar("agendalist","DATE_POSTED",$list->IndonesianDate($item['MULAI'],'YYYY-MM-DD'));
            $this->mrTemplate->ParseTemplate('agendalist', 'a');
         }
      }else{
         
      }
   }
   
   
}
?>
