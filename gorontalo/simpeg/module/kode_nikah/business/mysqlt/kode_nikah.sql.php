<?php
$sql['get_count_kode_nikah'] = "
   SELECT
      count(kodenikahId) AS total
   FROM sdm_ref_kode_nikah   
";

$sql['get_list_kode_nikah'] = "
   SELECT 
      p.kodenikahId AS id,
      p.kodenikahKode AS kode,
      p.kodenikahNama AS nama,
      CONCAT(k.kompgajidtKode,' - ',k.kompgajidtNama) as kompgaji
   from sdm_ref_kode_nikah p
   LEFT JOIN sdm_ref_komponen_gaji_detail k ON (k.kompgajidtId=p.kodenikahKompGajiDetId)
   WHERE p.kodenikahNama LIKE '%s'  
   ORDER by p.kodenikahNama
   LIMIT %s, %s  
";


$sql['get_kode_nikah_by_id'] = "
  SELECT 
      p.kodenikahId as id,
      p.kodenikahKode AS kode,
      p.kodenikahNama AS nama,
      p.kodenikahKompGajiDetId as kompid,
      CONCAT(kompgajidtKode,' - ',kompgajidtNama) as kompnama
   from sdm_ref_kode_nikah p
   LEFT JOIN sdm_ref_komponen_gaji_detail k ON (k.kompgajidtId=p.kodenikahKompGajiDetId)
   WHERE p.kodenikahId =%s
";

//=================DO=======================//

$sql['do_add_kode_nikah'] ="
   INSERT INTO sdm_ref_kode_nikah
   (kodenikahKode, kodenikahKode, kodenikahKompGajiDetId)
   VALUES
   ('%s','%s','%s')
";

$sql['do_update_kode_nikah'] = "
   UPDATE sdm_ref_kode_nikah
   SET
      kodenikahKode = '%s',
      kodenikahNama = '%s',
      kodenikahKompGajiDetId = '%s'
   WHERE
      kodenikahId = %d
";

$sql['do_delete_kode_nikah_by_array_id'] = "
   DELETE FROM sdm_ref_kode_nikah    
   WHERE
      kodenikahId IN(%s)

";

$sql['do_delete_kode_nikah'] = "
   DELETE FROM sdm_ref_kode_nikah    
   WHERE
      kodenikahId='%s'

";

?>
