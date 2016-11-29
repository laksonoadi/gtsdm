<?php
$sql['list_berita'] ="
   SELECT
      beritaId AS ID,
      beritaNama AS TITLE,
      beritaArtikel AS ARTICLE,
      beritaUrl AS URL,
      beritaFoto AS FOTO,
      beritaCaptionFoto AS CAPTION,
      beritaIsAktif AS STATUS,
      beritaPengirim AS SENDER,
      DATE_FORMAT(beritaTanggalPosting,'%%d-%%m-%%Y') AS DATE_POSTED,
      beritaJumlahDibaca AS READED,
      DATE_FORMAT(beritaTanggalBerita,'%%d-%%m-%%Y') AS DATE_NEWS
   FROM w_berita
   WHERE beritaPengirim='%s'
   ORDER BY beritaTanggalBerita DESC, beritaId DESC
   LIMIT %d, %d
";

$sql['list_berita_aktif'] ="
   SELECT
      beritaId AS ID,
      beritaNama AS TITLE,
      beritaArtikel AS ARTICLE,
      beritaUrl AS URL,
      beritaFoto AS FOTO,
      beritaCaptionFoto AS CAPTION,
      beritaIsAktif AS STATUS,
      beritaPengirim AS SENDER,
      DATE_FORMAT(beritaTanggalPosting,'%s') AS DATE_POSTED,
      beritaJumlahDibaca AS READED,
      DATE_FORMAT(beritaTanggalBerita,'%%d-%%m-%%Y') AS DATE_NEWS
   FROM w_berita
   WHERE beritaIsAktif = '1' AND beritaPengirim='%s'
   ORDER BY beritaTanggalBerita DESC, beritaId DESC
   LIMIT %d, %d
";

$sql['count_berita'] = "
   SELECT
      COUNT(beritaId) AS NUMBER
   FROM w_berita
   WHERE beritaPengirim='%s'
";

$sql['count_berita_aktif'] = "
   SELECT
      COUNT(beritaId) AS NUMBER
   FROM w_berita
   WHERE beritaIsAktif = '1' AND beritaPengirim='%s'
";

$sql['add_berita'] = "
   INSERT INTO
      w_berita
   SET
      beritaNama = '%s',
      beritaArtikel = '%s',
      beritaFoto = '%s',
      beritaCaptionFoto = '%s',
      beritaIsAktif = '%s',
      beritaPengirim = '%s',
      beritaTanggalPosting = NOW(),
      beritaJumlahDibaca = '0',
      beritaTanggalBerita = '%s'
";

$sql['update_berita'] = "
   UPDATE
      w_berita
   SET
      beritaNama = '%s',
      beritaArtikel = '%s',
      beritaFoto = '%s',
      beritaCaptionFoto = '%s',
      beritaIsAktif = '%s',
      beritaPengirim = '%s',
      beritaTanggalPosting = NOW(),
      beritaTanggalBerita = '%s'
   WHERE
      beritaId = '%s'
";

$sql['delete_berita'] = "
   DELETE FROM
      w_berita
   WHERE
      beritaId = '%s'
";

$sql['get_berita_by_id'] = "
   SELECT
      beritaId AS ID,
      beritaNama AS TITLE,
      beritaArtikel AS ARTICLE,
      beritaUrl AS URL,
      beritaFoto AS FOTO,
      beritaCaptionFoto AS CAPTION,
      beritaIsAktif AS STATUS,
      beritaPengirim AS SENDER,
      beritaTanggalPosting AS DATE_POSTED,
      beritaJumlahDibaca AS READED,
      DATE_FORMAT(beritaTanggalBerita,'%%d-%%m-%%Y') AS DATE_NEWS
   FROM w_berita
   WHERE beritaId = '%s'
";
?>