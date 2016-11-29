<?php
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'module/berita/business/Berita.class.php';

class ViewMainBeritaList extends HtmlResponse {
   function TemplateModule() {
      $this->SetTemplateBasedir(GTFWConfiguration::GetValue('application', 'docroot') .
         'module/berita/template');
      $this->SetTemplateFile('view_main_berita_list.html');
   }

   function ProcessRequest() {
      $numDataPerPage = 5; //GTFWConfiguration::GetValue('application', 'paging');
      $page = (isset($_GET['opac_page'])) ? $_GET['opac_page'] : 1;
      $offset = ($page - 1) * $numDataPerPage;
      $list = new Berita();
      
      $return['headline']=$list->ListBeritaTerbaru();
      $return['list']=$list->ListBeberapaBerita();
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
		
		return $text.' ........';
   } 

   function ParseTemplate($data) {
      $list = new Berita();
      $item=$data['headline'];
	  $item['RINGKAS'] = htmlentities($this->ringkasan($item['RINGKAS'],100));
      $this->mrTemplate->AddVar("content","TITLE",$item['TITLE']);
      $this->mrTemplate->AddVar("content","RINGKAS",$item['RINGKAS']);
      $this->mrTemplate->AddVar("content","URL_BERITA",Dispatcher::Instance()->GetUrl('berita','Berita','View','html').'&id='.$item['ID']);
      $this->mrTemplate->AddVar("content","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD'));
      
      $data=$data['list'];
      if(!empty($data)){
         foreach ($data as $item) {
            $this->mrTemplate->AddVar("beritalist","TITLE",$item['TITLE']);
            $this->mrTemplate->AddVar("beritalist","URL_BERITA",Dispatcher::Instance()->GetUrl('berita','Berita','View','html').'&id='.$item['ID']);
            $this->mrTemplate->AddVar("beritalist","DATE_POSTED",$list->IndonesianDate($item['DATE_POSTED'],'YYYY-MM-DD'));
            $this->mrTemplate->ParseTemplate('beritalist', 'a');
         }
      }else{
         
      }
   }
   
   
}
?>
