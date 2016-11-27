<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot') . 'module/ref_potongan_pph/business/PotonganPph.class.php';
require_once GTFWConfiguration::GetValue('application', 'docroot') . 'main/function/date.php';

class ViewExcelPotongan extends XlsResponse {
   var $mWorksheets = array('Data');
   
   function GetFileName() {
      // name it whatever you want
      return 'EksportPotonganPph21.xls';
   }

   function ProcessRequest() {
   	$potonganObj = new PotonganPph();
   	
      if($_POST || isset($_GET['cari'])) {
			if(isset($_POST['cari'])) {
				$cari = $_POST['cari'];
			} elseif(isset($_GET['cari'])) {
				$cari = Dispatcher::Instance()->Decrypt($_GET['cari']);
			} else {
				$cari = '';
			}
		}
		
	  $dataPotongan= $potonganObj->GetDataExcelPph($cari);
      $data['data']=$dataPotongan;
	  
      if (empty($data['data'])) {
         $this->mWorksheets['Data']->write(0, 0, 'Data kosong');
      } else {
         $h1 = $this->mrWorkbook->add_format();
         $h1->set_bold();
         $h1->set_size(14);
         $h1->set_align('vcenter');
         
         $h2 = $this->mrWorkbook->add_format();
         $h2->set_bold();
         $h2->set_size(12);
         $h2->set_align('center');
         $h2->set_align('vcenter');
         
         $strong = $this->mrWorkbook->add_format();
         $strong->set_bold();
         
         $sign = $this->mrWorkbook->add_format();
         $sign->set_size(10);
         $sign->set_align('center');
         
         $left = $this->mrWorkbook->add_format();
         $left->set_size(10);
         $left->set_align('left');
         
         #set colom
         $th = $this->mrWorkbook->add_format();
         $th->set_border(1);
         $th->set_bold();
         $th->set_size(10);
         $th->set_align('center');
         $th->set_align('vcenter');
         $th->set_text_wrap();
         
         
         $td = $this->mrWorkbook->add_format();
         $td->set_border(1);
         $td->set_size(10);         
         $td->set_align('left');
         
         $tdCenter = $this->mrWorkbook->add_format();
         $tdCenter->set_border(1);
         $tdCenter->set_size(10);         
         $tdCenter->set_align('center');
         
         $tdRight = $this->mrWorkbook->add_format();
         $tdRight->set_border(1);
         $tdRight->set_size(10);         
         $tdRight->set_align('right');
         
         $collWidth = 4;
         #set header
         $row = 0;
         $this->mWorksheets['Data']->write($row, 1, GTFWConfiguration::GetValue('application', 'company_name'), $h1);
         $this->mWorksheets['Data']->merge_cells($row, 1, 1, $collWidth);
         
         $row+=3;
         $this->mWorksheets['Data']->write($row, 0, 'List Range Potongan Pph-21', $h2);
         $this->mWorksheets['Data']->merge_cells($row, 0, $row+1, $collWidth);
         
         $row+=3;


         //set header
			$row+=1;
			$coll=0;
         $this->mWorksheets['Data']->write($row, $coll, 'No', $th);
			$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
			$coll++;
			$this->mWorksheets['Data']->write($row, $coll, 'Nama', $th);
			$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
			$coll++;
			$this->mWorksheets['Data']->write($row, $coll, 'Nominal Maximal', $th);
			$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
			

			$dataGrid=$data['data'];
			
			for($i=0;$i<count($dataGrid);$i++){
			$dataGrid[$i]['pphrp_nominal'] = number_format($dataGrid[$i]['pphrp_nominal'], 2, ',', '.');
				$row+=1;
				$coll=0;
				$this->mWorksheets['Data']->write($row, $coll, $i+1, $td);
				$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
				$coll++;
				$this->mWorksheets['Data']->write($row, $coll, $dataGrid[$i]['pphrp_nama'], $td);
				$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
				$coll++;
				$this->mWorksheets['Data']->write($row, $coll, 'Rp. '.$dataGrid[$i]['pphrp_nominal'], $td);
				$this->mWorksheets['Data']->merge_cells($row, $coll, $row, $coll);
				
			}
			
			
      }
      
   }
}
?>
