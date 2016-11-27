<?php
require_once GTFWConfiguration::GetValue('application', 'docroot').'module/'.Dispatcher::Instance()->mModule.'/business/BudgetRef.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot').'module/user_unit_kerja/business/UserUnitKerja.class.php';

class ViewImportCSV extends HtmlResponse
{
	function TemplateModule ()
   {
		$this->SetTemplateBasedir(GTFWConfiguration::GetValue('application','docroot').'module/'.Dispatcher::Instance()->mModule.'/template');
		$this->SetTemplateFile('view_import_csv.html');
	}
	
	function ProcessRequest ()
   {
		$Obj = new BudgetRef;
      
      // Generate ComboBox
      $ComboTahunAnggaran = $Obj->GetComboTahunAnggaran();
      $TahunAnggaranAktif = $Obj->GetTahunAnggaranAktif();
      Messenger::Instance()->SendToComponent('combobox', 'Combobox', 'view', 'html', 'budgetThanggarid',
         array('budgetThanggarid', $ComboTahunAnggaran, $TahunAnggaranAktif['thanggarId'], false, ''), Messenger::CurrentRequest);
      // ---------
      
      // Generate URL
      $return['url']['action'] = Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule,'importCSV','do','html');
      // ---------
      
      if (isset($_GET['id']))
      {
         $return['mode'] =  'Edit';
         $return['dataGrid']['editReadonly'] = 'readonly';
         $return['dataGrid']['editDisabled'] = 'disabled';
      }
      else $return['mode'] =  'Tambah';
      
      return $return;
	}
	
	function ParseTemplate ($data = NULL)
   {
      extract ($data);
      
      // Render URL dan Variabel
      $this->mrTemplate->addVars('content', $data['url'], 'URL_');
      if (Dispatcher::Instance()->mSubModule != 'importCSV' OR !isset($_GET['ascomponent']))
			$this->mrTemplate->SetAttribute('BudgetRefTitle', 'visibility', 'visible');
      // ---------
      
	}
}
?>
