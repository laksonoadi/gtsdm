<?php
$sql['get_count_data'] = "
	SELECT
      COUNT(*) as total
   FROM 
      sdm_ref_kepakaran
   WHERE
      kepakaranBidangIlmurId='%s'
";
$sql['get_data']="
	SELECT
      kepakaranrId as id,
      kepakaranrNama as nama,
      d.bidangilmurNama as bidang
   FROM 
      sdm_ref_kepakaran
   JOIN sdm_ref_kepakaran_bidang_ilmu d ON (d.bidangilmurId=kepakaranBidangIlmurId)
   WHERE
      kepakaranBidangIlmurId='%s'
	ORDER BY kepakaranrNama ASC
	LIMIT %s, %s
";


$sql['get_combo_bidang'] = "
   SELECT
      bidangilmurId as id,
      bidangilmurNama as name
   FROM
      sdm_ref_kepakaran_bidang_ilmu
";

?>