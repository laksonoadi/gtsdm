<?php
$sql['list_agenda_terbaru'] = "
SELECT 
   agId as ID,
   agNama as TITLE,
   agTanggalMulai as MULAI,
   agTanggalSelesai as SELESAI,
   agTempat as TEMPAT,
   agArtikel as RINGKAS,
   agTanggalPosting as DATE_POSTED
FROM 
   w_agenda
WHERE 
   1=1 
ORDER BY agTanggalMulai DESC, agTanggalPosting DESC
LIMIT 1
";

$sql['list_beberapa_agenda'] = "
SELECT 
   agId as ID,
   agNama as TITLE,
   agTanggalMulai as MULAI,
   agTanggalSelesai as SELESAI,
   agTempat as TEMPAT,
   agArtikel as RINGKAS,
   agTanggalPosting as DATE_POSTED,
   agIsSticky as STICKY
FROM 
   w_agenda
WHERE
  1=1 
ORDER BY agTanggalMulai DESC, agTanggalPosting DESC
LIMIT 1, 3
";

$sql['list_beberapa_agenda2'] = "
SELECT 
   agId as ID,
   agNama as TITLE,
   agTanggalMulai as MULAI,
   agTanggalSelesai as SELESAI,
   agTempat as TEMPAT,
   agArtikel as RINGKAS,
   agTanggalPosting as DATE_POSTED,
   agIsSticky as STICKY
FROM 
   w_agenda
WHERE
  1=1 
ORDER BY agIsSticky DESC, agTanggalMulai DESC, agTanggalPosting DESC
LIMIT %d
";
/*agTanggalSelesai<=now()*/

$sql['list_agenda'] = "
SELECT 
   agId as ID,
   agNama as TITLE,
   agTanggalMulai as MULAI,
   agTanggalSelesai as SELESAI,
   agTempat as TEMPAT,
   agArtikel as RINGKAS,
   agTanggalPosting as DATE_POSTED,
   agIsSticky as STICKY
FROM 
   w_agenda
WHERE 
   1=1 
ORDER BY agTanggalMulai DESC, agTanggalPosting DESC
";

$sql['get_agenda_by_id'] = "
SELECT 
   agId as ID,
   agNama as TITLE,
   agTanggalMulai as MULAI,
   agTanggalSelesai as SELESAI,
   agTempat as TEMPAT,
   agArtikel as ARTIKEL,
   agTanggalPosting as DATE_POSTED,
   agFoto as FOTO,
   agCaptionFoto as CAPTION_FOTO,
   agUrl AS URL,
   agIsSticky as STICKY
FROM 
   w_agenda
WHERE 
   1=1  AND agId=%s
"; 
 
?>