<?php
$sql['get_count_data'] = "
	SELECT
      COUNT(*) as total
   FROM 
      sdm_ref_komponen_gaji_detail
   WHERE
      kompgajidtKompgajiId='%s'
";
$sql['get_data']="
	SELECT
      kompgajidtId as id,
      kompgajidtKode as kode,
      d.kompgajiNama as komp,
      CONCAT(kompgajidtKode,' - ',kompgajidtNama) as nama
   FROM 
      sdm_ref_komponen_gaji_detail
   JOIN sdm_ref_komponen_gaji d ON (d.kompgajiId=kompgajidtKompgajiId)
   WHERE
      kompgajidtKompgajiId='%s'
	ORDER BY kompgajidtNama
	LIMIT %s, %s
";

$sql['get_combo_komponen'] = "
   SELECT
      kompgajiId as id,
      kompgajiNama as name
   FROM
      sdm_ref_komponen_gaji
   WHERE
	  kompgajiIsAuto=0 AND kompgajiId>1;
";
?>