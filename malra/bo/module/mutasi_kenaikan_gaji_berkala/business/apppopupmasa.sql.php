<?php
$sql['get_count_data'] = "
	SELECT
      COUNT(*) as total
   FROM 
      sdm_ref_gaji_pokok
   WHERE
      gapokPktgolrId='%s'
";
$sql['get_data']="
	SELECT 
  	a.gapokKompGajiDetId as 'id',
  	a.gapokMasaKerja as 'nama',
  	b.kompgajidtNominal as 'nominal'
  FROM
  	sdm_ref_gaji_pokok a
  	LEFT JOIN sdm_ref_komponen_gaji_detail b ON (b.kompgajidtId=a.gapokKompGajiDetId)
  WHERE 
     a.gapokPktgolrId='%s'
	ORDER BY a.gapokMasaKerja ASC
	LIMIT %s, %s
";

?>