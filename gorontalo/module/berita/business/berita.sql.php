<?php
$sql['list_berita_terbaru'] = "
SELECT 
   beritaId as ID,
   beritaNama as TITLE,
   beritaArtikel as RINGKAS,
   beritaTanggalBerita as DATE_POSTED
FROM 
   w_berita
WHERE 
  beritaIsAktif=1
ORDER BY beritaTanggalPosting DESC, beritaTanggalBerita DESC
LIMIT 1
";

$sql['list_beberapa_berita'] = "
SELECT 
   beritaId as ID,
   beritaNama as TITLE,
   beritaArtikel as RINGKAS,
   beritaTanggalBerita as DATE_POSTED
FROM 
   w_berita
WHERE 
  beritaIsAktif=1
ORDER BY beritaTanggalPosting DESC, beritaTanggalBerita DESC
LIMIT 1,5
";

$sql['list_beberapa_berita2'] = "
SELECT 
   beritaId as ID,
   beritaNama as TITLE,
   beritaArtikel as RINGKAS,
   beritaTanggalBerita as DATE_POSTED
FROM 
   w_berita
WHERE 
  beritaIsAktif=1
ORDER BY beritaTanggalPosting DESC, beritaTanggalBerita DESC
LIMIT %d
";

$sql['list_berita'] = "
SELECT 
   beritaId as ID,
   beritaNama as TITLE,
   beritaArtikel as RINGKAS,
   beritaTanggalBerita as DATE_POSTED
FROM 
   w_berita
WHERE 
  beritaIsAktif=1
ORDER BY beritaTanggalPosting DESC, beritaTanggalBerita DESC
";

$sql['get_berita_by_id'] = "
SELECT 
   beritaId as ID,
   beritaNama as TITLE,
   beritaArtikel as ARTIKEL,
   beritaTanggalBerita as DATE_POSTED,
   beritaPengirim as PENGIRIM,
   beritaFoto as FOTO,
   beritaCaptionFoto as CAPTION_FOTO
FROM 
   w_berita
WHERE 
  beritaId=%s
"; 
 
?>