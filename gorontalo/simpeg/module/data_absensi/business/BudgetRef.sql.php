<?php
$sql['get_user_info'] ="
SELECT
   UserId,
   RealName,
   UserName,
   Password,
   NoPassword,
   Active,
   ForceLogout,
   PhoneNumber,
   GroupId,
   GroupName,
   unitkerjaId,
   unitkerjaKode,
   unitkerjaNama,
   unitkerjaTipeunitId,
   unitkerjaNamaPimpinan,
   unitKerjaUnitStatusId,
   roleId,
   roleName,
   roleKeterangan
FROM
   gtfw_user
   LEFT JOIN gtfw_group USING (GroupId)
   LEFT JOIN user_unit_kerja ON userunitkerjaUserId = UserId
   LEFT JOIN unit_kerja_ref ON unitkerjaId = userunitkerjaUnitkerjaId
   LEFT JOIN gtfw_role ON roleId = userunitkerjaRoleId
WHERE
   UserId = %s
LIMIT 1
";

$sql['get_combo_unit'] = "
SELECT
   unitkerjaId AS id,
   unitkerjaNama AS name
FROM
   unit_kerja_ref
ORDER BY
   unitkerjaNama ASC
";

$sql['get_combo_tahun_anggaran'] = "
SELECT
   thanggarId AS id,
   thanggarNama AS name
FROM
   tahun_anggaran
ORDER BY
   thanggarBuka DESC
";

$sql['get_tahun_anggaran_aktif'] = "
SELECT
   thanggarId,
   thanggarNama
FROM
   tahun_anggaran
WHERE
   thanggarIsAktif = 'Y'
";

$sql['get_budget_tree'] = "
SELECT
   budgetId,
   budgetId AS id,
   budgetBudgetId,
   budgetKode,
   budgetNama,
   CONCAT('[',budgetKode,']',budgetNama) AS name
FROM
   finansi_bg_ref_budget
-- WHERE
   -- budgetKodeSistem LIKE (SELECT CONCAT(budgetKodeSistem,'%%') FROM finansi_bg_ref_budget WHERE budgetId = %s)
ORDER BY
   budgetKodeSistem ASC
";

$sql['get_budget_export'] = "
SELECT
   budgetKode,
   budgetNama
FROM
   finansi_bg_ref_budget
-- WHERE
   -- budgetKodeSistem LIKE (SELECT CONCAT(budgetKodeSistem,'%%') FROM finansi_bg_ref_budget WHERE budgetId = %s)
ORDER BY
   budgetKode ASC
";

$sql['get_privileged_budget'] = "
SELECT
   budgetId AS start
FROM
   finansi_bg_ref_budget,
   (SELECT CONCAT(SUBSTRING_INDEX(budgetKode,'-00',1),'%%') AS param1 FROM finansi_bg_ref_budget WHERE budgetId = %s) AS param
WHERE
   (
      CONCAT(SUBSTRING_INDEX(budgetKode,'-00',1),'%%') LIKE param1 OR
      param1 LIKE CONCAT(SUBSTRING_INDEX(budgetKode,'-00',1),'%%')
   )
   AND budgetUnitId = %s
";

$sql['get_budget_detail'] = "
SELECT
   budgetId,
   budgetBudgetId,
   (SELECT budgetNama FROM finansi_bg_ref_budget WHERE budgetId = child.budgetBudgetId) AS budgetBudgetName,
   budgetKode,
   budgetNama,
   budgetUnitId,
   unitkerjaId,
   unitkerjaKode,
   unitkerjaNama,
   bgsatuanNama,
   budgetUnitItemId
FROM
   finansi_bg_ref_budget AS child
   LEFT JOIN unit_kerja_ref ON unitkerjaId = budgetUnitId
   LEFT JOIN finansi_bg_ref_satuan ON budgetUnitItemId=bgsatuanId 
WHERE
   budgetId = %s
LIMIT 1
";

$sql['get_budget_owner'] = "
SELECT
   unitkerjaId,
   unitkerjaKode,
   unitkerjaNama
FROM
   unit_kerja_ref
   JOIN finansi_bg_ref_budget ON budgetUnitId = unitkerjaId
WHERE
   (SELECT budgetKodeSistem FROM finansi_bg_ref_budget WHERE budgetId = %s LIMIT 1) LIKE CONCAT(budgetKodeSistem,'%%')
ORDER BY
   budgetKodeSistem DESC
LIMIT 1
";

$sql['get_budget_list_by_search'] = "
SELECT SQL_CALC_FOUND_ROWS
   budgetId,
   budgetKode,
   budgetNama,
   unitkerjaNama
FROM
   finansi_bg_ref_budget AS child
   LEFT JOIN unit_kerja_ref ON unitkerjaId = budgetUnitId
   JOIN (SELECT IFNULL(%s,'') AS keyword) param
WHERE
   budgetKode LIKE CONCAT('%%',keyword,'%%') OR
   budgetNama LIKE CONCAT('%%',keyword,'%%')
ORDER BY
   budgetKodeSistem ASC
LIMIT %s, %s
";

$sql['get_budget_list_by_search_count'] = "
SELECT FOUND_ROWS() AS total
";

$sql['kode_budget_is_exist'] = "
SELECT
   COUNT(*) AS total
FROM
   finansi_bg_ref_budget
WHERE
   budgetKode LIKE %s
";

$sql['get_next_kode_sistem'] = "
SELECT
   CONCAT(parent.budgetKodeSistem,'.') AS parentKode,
   SUBSTRING_INDEX(child.budgetKodeSistem,'.',-1) AS childKode
FROM
   (SELECT %s AS budgetId) AS param
   LEFT JOIN finansi_bg_ref_budget AS parent ON IF(param.budgetId IS NULL, parent.budgetId IS NULL, parent.budgetId = param.budgetId)
   LEFT JOIN finansi_bg_ref_budget AS child ON IF(param.budgetId IS NULL, child.budgetBudgetId IS NULL, child.budgetBudgetId = param.budgetId)
ORDER BY
   child.budgetId DESC
LIMIT 1
";

$sql['get_data_unit_item'] = "
   SELECT 
      bgsatuanId AS id, 
      bgsatuanNama AS name
   FROM 
      finansi_bg_ref_satuan
";

/////////
// Do Query
/////////

$sql['add_budget'] = "
INSERT INTO
   finansi_bg_ref_budget
   (
      budgetBudgetId,
      budgetKodeSistem,
      budgetUnitId,
      budgetKode,
      budgetNama,
      budgetUserId,
      budgetUnitItemId,
      budgetTglUbah
   )
VALUES
(
   %s,
   %s,
   %s,
   %s,
   %s,
   %s,
   %s,
   NOW()
)
";

$sql['edit_budget'] = "
UPDATE
   finansi_bg_ref_budget
SET
   budgetUnitId = %s,
   budgetNama = %s,
   budgetUserId = %s,
   budgetUnitItemId = '%s',
   budgetTglUbah = NOW()
WHERE
   budgetId = %s
";
?>
