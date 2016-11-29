<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/satuan_kerja/business/satuan_kerja.class.php';

class Laporan extends Database {

   protected $mSqlFile= 'module/laporan_struktur_organisasi/business/laporan.sql.php';
   
   function __construct($connectionNumber=0) {
      parent::__construct($connectionNumber);   
      //  
   }
   

	function GetComboUnitKerja(){
		$sql=$this->mSqlQueries['get_combo_unit_kerja'];
		$result=$this->Open($sql,array());
		return $result;
	}
    
	function GetComboUserUnitKerja(){
        $Satker = new SatuanKerja();
        $result = $Satker->GetComboUserUnitKerja();
        return $result;
	}

	function GetNameStruktur($unit_kerja){
		$sql=$this->mSqlQueries['get_struktur_organisasi_name'];
		$result=$this->Open($sql,array($unit_kerja));
		return $result;
	}

	// function GetParentStruktur($unit_kerja){
	// 	$sql=$this->mSqlQueries['get_parent_struktur'];
	// 	$result=$this->Open($sql,array($unit_kerja));
	// 	return $result;
	// }

	function GetDataStruktur($unit_kerja){
        $search = '';

        $search .= "AND a.satkerId = '$unit_kerja' ";

        $query = $this->mSqlQueries['get_struktur_organisasi'];
        $query = str_replace('--search--', $search, $query);
        $result = $this->Open(stripslashes($query), array());
       
		return $result;
	}


    public function GetDataStrukturTree($id)
    {
        $children = $this->GetDataStrukturChildren($id);
        $tree = array();
        foreach($children as $row) {
            $row['child'] = $this->GetDataStrukturTree($row['id']);
            $tree[] = $row;
        }
        return $tree;
    }

    public function GetDataStrukturChildren($id = 0)
    {

        $search = '';

        if($id)
        $search .= "AND a.satkerParentId = $id ";

        $query = $this->mSqlQueries['get_struktur_organisasi'];
        $query = str_replace('--search--', $search, $query);
        $result = $this->Open(stripslashes($query), array());

    return $result;
    }

   
  
}
?>
