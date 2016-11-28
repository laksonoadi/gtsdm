<?php
$sql['do_add_data'] = "
INSERT INTO
   pg_komponen_gaji_detil
   (
      kompGajiDetId,
      kompGajiDetNama,
      kompGajiNama
   )
VALUES 
   ('%s', '%s', '%s')
";

$sql['do_update_data'] = "
UPDATE 
   pg_komponen_gaji_detil
SET
   kompGajiDetNama = '%s',
   kompGajiNama = '%s'
WHERE 
   kompGajiDetId = '%s'
";

$sql['do_delete_data'] = "
DELETE FROM 
   pg_komponen_gaji_detil
WHERE 
   kompGajiDetId IN (%s)
";
?>
