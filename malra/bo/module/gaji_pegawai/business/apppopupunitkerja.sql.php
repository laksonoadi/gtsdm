<?php
$sql['get_count_data_unitkerja'] = "
	SELECT 
		COUNT(satkerId) as total
	FROM 
    pub_satuan_kerja
  %s
";
$sql['get_data_unitkerja']="
	SELECT 
	  satkerId as id,
		satkerNama as nama
	FROM 
    pub_satuan_kerja
  %s
	ORDER BY nama
	LIMIT %s, %s
";
/*
$sql['get_data_unitkerja'] = 
"
SELECT 
		(if(tempUnitId IS NULL,unitkerjaId,unitkerjaId)) AS id,
		(if(tempUnitKode IS NULL,unitkerjaKode,unitkerjaKode)) AS kodeunit,
		(if(tempUnitNama IS NULL,unitkerjaNama,unitkerjaNama)) AS unit,
		tipeunitNama as tipeunit,
		unitkerjaParentId AS parentId
	FROM unit_kerja_ref
		LEFT JOIN 
			(SELECT 
				unitkerjaId AS tempUnitId,
				unitkerjaKode AS tempUnitKode,
				unitkerjaNama AS tempUnitNama,
				unitkerjaParentId AS tempParentId,
				tipeunitNama AS tempTipeunitNama
			FROM unit_kerja_ref 
			LEFT JOIN tipe_unit_kerja_ref ON (tipeunitId = unitkerjaTipeunitId)
			WHERE unitkerjaParentId = 0) tmpUniKerja ON(unitkerjaParentId=tempUnitId)
		LEFT JOIN tipe_unit_kerja_ref ON (tipeunitId = unitkerjaTipeunitId)
	WHERE 
		unitkerjaKode LIKE '%s' 
		OR unitkerjaNama LIKE '%s' 
		OR tempUnitKode LIKE '%s' 
		OR tempUnitNama LIKE '%s'
		%s
	LIMIT %s, %s
";
*/
/*
$sql['get_data_unitkerja'] = 
   "SELECT 
      ukr.unitkerjaId				as unitkerja_id,
	  ukr.unitkerjaKode				as unitkerja_kode,
	  ukr.unitkerjaNama				as unitkerja_nama,
	  ukr.unitkerjaNamaPimpinan		as unitkerja_pimpinan,
	  tukr.tipeunitNama				as tipeunit_nama
   FROM 
      unit_kerja_ref ukr
	  LEFT JOIN tipe_unit_kerja_ref tukr ON (tukr.tipeunitId = ukr.unitkerjaTipeunitId)
	WHERE 
		ukr.unitkerjaKode LIKE '%s'
		AND ukr.unitkerjaNama LIKE '%s'
		AND ukr.unitkerjaParentId <> 0
		%s
		%s
   ORDER BY 
	  ukr.unitkerjaNama
   LIMIT %s, %s";

//untuk combo box

$sql['get_data_tipe_unit'] = 
   "SELECT 
      tipeunitId		as id,
	  tipeunitNama		as name
   FROM 
      tipe_unit_kerja_ref
   ORDER BY 
      tipeunitNama";*/
?>