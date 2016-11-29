<?php
$sql['get_count_data'] = "
	SELECT
      COUNT(*) as total
   FROM 
      pub_ref_bank
   WHERE
      bankNama LIKE '%s'
";
$sql['get_data']="
	SELECT
      bankId as id,
      bankNama as nama
   FROM 
      pub_ref_bank
   WHERE
      bankNama LIKE '%s'
	ORDER BY bankNama
	LIMIT %s, %s
";

$sql['get_combo_bank']="
	SELECT
      bankId as id,
      bankNama as name
   FROM 
      pub_ref_bank
";
?>