<?php

class BudgetRef extends Database
{
   protected $mSqlFile;
   protected $budgetDetailCache = array();
   protected $budgetOwnerCache = array();
   protected $defaultAdminGroup = 2;
	
	function __construct ($connectionNumber=0)
   {
      $this->mSqlFile = 'module/'.Dispatcher::Instance()->mModule.'/business/BudgetRef.sql.php';
		parent::__construct($connectionNumber);
	}
	
	function GetItemUnit(){
	   return $this->Open($this->mSqlQueries['get_data_unit_item'],array());
   }
   
   function GetUserInfo ($userId)
   {
      $result = $this->Open($this->mSqlQueries['get_user_info'], array($userId));
      return $result[0];
   }
   
   function GetComboUnit ()
   {
      $result = $this->Open($this->mSqlQueries['get_combo_unit'], array());
      return $result;
   }
   
   function GetComboTahunAnggaran()
   {
      $result = $this->Open($this->mSqlQueries['get_combo_tahun_anggaran'], array());
      return $result;
   }
   
   function GetTahunAnggaranAktif()
   {
      $result = $this->Open($this->mSqlQueries['get_tahun_anggaran_aktif'], array());
      if (empty($result)) return array();
      else return $result[0];
   }
   
   function GetBudgetTree ($unitId, $groupId, $start = 0)
   {
      #
      if ($groupId != $this->defaultAdminGroup)
         $start = $this->GetPrivilegedBudget($unitId, $start);
      #
     
      $result = $this->Open($this->mSqlQueries['get_budget_tree'], array($start));
      return $result;
   }
   
   function GetBudgetExport ($unitId, $groupId, $start = 0)
   {
      if ($groupId != $this->defaultAdminGroup)
         $start = $this->GetPrivilegedBudget($unitId, $start);
     
      $result = $this->Open($this->mSqlQueries['get_budget_export'], array($start));
      return $result;
   }
   
   function GetPrivilegedBudget ($unitId, $start)
   {
      $result = $this->Open($this->mSqlQueries['get_privileged_budget'], array($start, $unitId));
      return $result[0]['start'];
   }
   
   function GetBudgetDetail ($idBudget)
   {
      if (isset($this->budgetDetailCache[$idBudget])) return $this->budgetDetailCache[$idBudget];
      $result = $this->Open($this->mSqlQueries['get_budget_detail'], array($idBudget));
      $this->budgetDetailCache[$idBudget] = $result[0];
      return $result[0];
   }
   
   function GetBudgetOwner ($idBudget)
   {
      if (isset($this->budgetOwnerCache[$idBudget])) return $this->budgetOwnerCache[$idBudget];
      $result = $this->Open($this->mSqlQueries['get_budget_owner'], array($idBudget));
      if (empty($result)) $result[0] = array
      (
         'unitkerjaId' => 0,
         'unitkerjaKode' => 'Undefined',
         'unitkerjaNama' => 'Undefined'
      );
      $this->budgetOwnerCache[$idBudget] = $result[0];
      return $result[0];
   }
   
   function GetBudgetKode ($kodeBudget)
   {
      $parent = explode('-', $kodeBudget);
      while (($current = array_pop($parent)) == '00') continue;
      if (empty($parent))
      {
         $parent = '';
         $base = $current;
      }
      else
      {
         $parent = implode('-', $parent);
         $base = "$parent-$current";
      }
      
      return compact ('base', 'current', 'parent');
   }
   
   function GetBudgetListBySearch ($filter, $start, $limit)
   {
      extract($filter);
      $result = $this->Open($this->mSqlQueries['get_budget_list_by_search'], array($input, $start, $limit));
      return $result;
   }
   
   function GetBudgetListBySearchCount ()
   {
      $result = $this->Open($this->mSqlQueries['get_budget_list_by_search_count'], array());
      return $result[0]['total'];
   }
   
   function KodeBugdetIsExist ($kode, $parent = null)
   {
      if ($parent > 0)
      {
         $result = $this->GetBudgetDetail($parent);
         $result = $this->GetBudgetKode($result['budgetKode']);
         $code = $result['base']."-$kode";
      }
      else $code = $kode;
      $result = $this->Open($this->mSqlQueries['kode_budget_is_exist'], array("$code%"));
      if ($result[0]['total'] == 0) return false;
      else return true;
   }
   
   function GetNextKodeSistem ($parentBudgetId)
   {
      if ($parentBudgetId == 0) $parentBudgetId = null;
		
      $result = $this->Open($this->mSqlQueries['get_next_kode_sistem'], array($parentBudgetId));
		#
      return $result[0]['parentKode'].($result[0]['childKode']+1);
   }
   
   /////////
   // Do Function
   /////////
   
   function AddBudget ($data)
   {
	
      $msg = array();
      extract ($data);
      if ($this->KodeBugdetIsExist($budgetKode, $budgetBudgetId))
         $msg[] = "Kode Budget ".$budgetKode." untuk induk ini sudah ada!";
      
      $userInfo = $this->GetUserInfo(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());
      $owner = $this->GetBudgetOwner($budgetBudgetId);
//      if ($userInfo['GroupId'] != $this->defaultAdminGroup && $userInfo['unitkerjaId'] != $owner['unitkerjaId'])
  //       $msg[] = "Budget induk bukan milik anda!";
      print_r($msg);
      if (!empty($msg)) return array('message' => $msg, 'status' => 'redo');
      
      if ($budgetBudgetId != 0)
      {
         $parent = $this->GetBudgetDetail($budgetBudgetId);
         $parentKode = $this->GetBudgetKode($parent['budgetKode']);
         $budgetKode = $parentKode['base'].'-'.$budgetKode;
      }
      $budgetKode = str_pad($budgetKode, 26, '-00');
      
      $arg = array
      (
         ($budgetBudgetId == '') ? null : $budgetBudgetId,
         $this->GetNextKodeSistem($budgetBudgetId),
         ($budgetUnitId == '') ? null : $budgetUnitId,
         $budgetKode,
         $budgetNama,
         $userInfo['UserId'],
         empty($itemUnit)?NULL:$itemUnit,
      );
      
      $result = $this->Execute($this->mSqlQueries['add_budget'], $arg);

      if ($result)
      {
         $return['status'] = 'success';
         $return['message'] = 'Penambahan Referensi Budget Berhasil!';
         $return['id'] = $this->Insert_ID();
      }
      else
      {
         $return['status'] = 'failed';
         $return['message'] = 'Penambahan Referensi Budget Gagal!';
      }
      exit;
      return $return;
   }
   
   function EditBudget ($id, $data)
   {
      $msg = array();
      extract ($data);
      
      $userInfo = $this->GetUserInfo(Security::Instance()->mAuthentication->GetCurrentUser()->GetUserId());
      $owner = $this->GetBudgetOwner($id);
		/*
      if ($userInfo['GroupId'] != $this->defaultAdminGroup && $userInfo['unitkerjaId'] != $owner['unitkerjaId'])
         return array('message' => array("Budget ini bukan milik anda!"), 'status' => 'failed', 'id' => $id);
      */
      $arg = array
      (
         ($budgetUnitId == '') ? null : $budgetUnitId,
         $budgetNama,
         $userInfo['UserId'],
         empty($itemUnit)?NULL:$itemUnit,
         $id
      );
      
      $result = $this->Execute($this->mSqlQueries['edit_budget'], $arg);
      
      if ($result)
      {
         $return['status'] = 'success';
         $return['message'] = 'Pengubahan Data Referensi Budget Berhasil!';
         $return['id'] = $id;
      }
      else
      {
         $return['status'] = 'failed';
         $return['message'] = 'Pengubahan Gagal!';
      }
      
      return $return;
   }
}
?>
