<?php

/*$sql['get_count'] = "
SELECT 
   COUNT(*) AS TOTAL
FROM 
   pub_pegawai
 %s
   GROUP BY pegId
";   */
//===GET===
$sql['get_data']="
SELECT 
   pktgolrId as 'id',
   pktgolrNama as 'nama'
FROM 
   sdm_ref_pangkat_golongan
ORDER BY 
   pktgolrUrut
"; 

$sql['get_nama_pang']="
SELECT 
   pktgolrNama as 'nama'
FROM 
   sdm_ref_pangkat_golongan
WHERE
   pktgolrId = '%s'
"; 

$sql['get_gaji_pokok']="
SELECT
	a.gapokKompGajiDetId as 'id',
	a.gapokMasaKerja as 'masa',
	b.kompgajidtNama as 'komp1',
	c.kompgajiNama as 'komp2'
FROM
	sdm_ref_gaji_pokok a
	LEFT JOIN sdm_ref_komponen_gaji_detail b ON (b.kompgajidtId = a.gapokKompGajiDetId)
	LEFT JOIN sdm_ref_komponen_gaji c ON (c.kompgajiId = b.kompgajidtKompgajiId)
WHERE
	a.gapokPktgolrId = '%s'
ORDER BY
  a.gapokPktgolrId ASC
";

$sql['get_gapok_det']="
SELECT
	a.gapokKompGajiDetId as 'id',
	a.gapokMasaKerja as 'masa',
	b.kompgajidtId as 'komp_id',
	b.kompgajidtNama as 'komp_label'
FROM
	sdm_ref_gaji_pokok a
	LEFT JOIN sdm_ref_komponen_gaji_detail b ON (b.kompgajidtId = a.gapokKompGajiDetId)
WHERE
	a.gapokPktgolrId = '%s' and a.gapokKompGajiDetId = '%s'
ORDER BY
  a.gapokPktgolrId ASC
";


// DO-----------
$sql['do_add'] = "
INSERT INTO 
   sdm_ref_gaji_pokok
   (gapokPktgolrId ,gapokMasaKerja,gapokKompGajiDetId)
VALUES('%s','%s','%s')  
";

$sql['do_update'] = "
UPDATE sdm_ref_gaji_pokok
SET 
	gapokMasaKerja = '%s',
	gapokKompGajiDetId = '%s'
WHERE 
	gapokPktgolrId = '%s' and gapokKompGajiDetId = '%s'
";

$sql['do_delete'] = "
DELETE FROM
   sdm_ref_gaji_pokok
WHERE 
   gapokKompGajiDetId = %s  
";
?>
