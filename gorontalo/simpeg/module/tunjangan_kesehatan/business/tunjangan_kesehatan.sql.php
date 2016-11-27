<?php
//===GET===
$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   sdm_ref_plafon_kesehatan a
   LEFT JOIN sdm_ref_jenis_tunjangan_kesehatan b ON a.pfkJtkId = b.jtkId 
%s
   GROUP BY a.pfkId
   ";   

$sql['get_data']="
SELECT 
   a.pfkId as 'id',
   c.statnkhNama as 'nikah',
   b.jtkNama as 'jenis',
   a.pfkPlafonUang as 'pla_uang',
   a.pfkPlafonPersen as 'pla_persen',
   a.pfkMaxKlaim as 'maks',
   a.pfkKlaimKe as 'klaim',
   a.pfkResetPerTahun as 'periode'
FROM 
   sdm_ref_plafon_kesehatan a
   LEFT JOIN sdm_ref_jenis_tunjangan_kesehatan b ON a.pfkJtkId = b.jtkId
   LEFT JOIN pub_ref_status_nikah c ON a.pfkKodeNikahId = c.statnkhId
%s
ORDER BY 
   a.pfkId
LIMIT %s,%s
"; 

$sql['get_dattun_detail']="
SELECT 
   pfkId as 'id',
   pfkKodeNikahId as 'nikah',
   pfkJtkId as 'jenis',
   pfkPlafonUang as 'pla_uang',
   pfkPlafonPersen as 'pla_persen',
   pfkMaxKlaim as 'maks',
   pfkKlaimKe as 'klaim',
   pfkResetPerTahun as 'periode'
FROM 
   sdm_ref_plafon_kesehatan
WHERE 
   pfkId = '%s'
";

$sql['get_jenis_tun']="
SELECT 
   jtkId as id,
   jtkNama as name
FROM
   sdm_ref_jenis_tunjangan_kesehatan
ORDER BY jtkNama ASC
";

$sql['get_stat_nikah']="
SELECT 
   statnkhId as id,
   statnkhNama as name
FROM
   pub_ref_status_nikah
ORDER BY statnkhNama ASC
";

// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_plafon_kesehatan
   (pfkJtkId,pfkKodeNikahId,pfkMaxKlaim,pfkKlaimKe,pfkResetPerTahun,pfkPlafonUang,
    pfkPlafonPersen)
VALUES('%s','%s','%s','%s','%s','%s','%s')
";

$sql['do_update'] = "
UPDATE sdm_ref_plafon_kesehatan
SET pfkJtkId = '%s',
  pfkKodeNikahId = '%s',
  pfkMaxKlaim = '%s',
	pfkKlaimKe = '%s',
	pfkResetPerTahun = '%s',
	pfkPlafonUang = '%s',
	pfkPlafonPersen = '%s'
WHERE 
	pfkId = %s
";  

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_plafon_kesehatan
WHERE 
   pfkId = %s   
";

?>
