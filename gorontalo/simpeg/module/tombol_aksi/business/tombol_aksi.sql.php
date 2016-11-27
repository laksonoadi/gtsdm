<?php
$sql['get_referensi_tombol_aksi_1']="
SELECT 
	count(verdataValue) as total
FROM
	sdm_verifikasi_data
	INNER JOIN sdm_verifikasi_ref ON verdataVerifikasiId=verifikasiId
	LEFT JOIN sdm_verifikasi_ref_status ON verdataStatus=verstatId
WHERE
	verstatIsApproved=1 AND verifikasiNamaTabel='%s' AND verdataValue='%s'
";
?>
