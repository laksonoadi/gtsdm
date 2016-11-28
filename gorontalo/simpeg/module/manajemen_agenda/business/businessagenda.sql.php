<?php
$sql['list_agenda'] ="
   SELECT
      agId AS ID,
      agNama AS TITLE,
      agArtikel AS ARTICLE,
      agTanggalMulai AS START_DATE,
      agTanggalSelesai AS END_DATE,
      agTempat AS LOCATION,
      agUrl AS URL,
      agFoto AS FOTO,
      agCaptionFoto AS CAPTION,
      agIsSticky AS STATUS,
      agPengirim AS SENDER,
      DATE_FORMAT(agTanggalPosting,'%s') AS DATE_POSTED,
      agJumlahDibaca AS READED
   FROM w_agenda
   WHERE agPengirim='%s'
   ORDER BY agIsSticky DESC, agTanggalMulai DESC, agTanggalPosting DESC
   LIMIT %d, %d
";

#deprecated
$sql['list_agenda_aktif'] ="
   SELECT
      agId AS ID,
      agNama AS TITLE,
      agArtikel AS ARTICLE,
      agUrl AS URL,
      agFoto AS FOTO,
      agCaptionFoto AS CAPTION,
      agIsAktif AS STATUS,
      agPengirim AS SENDER,
      agTanggalPosting AS DATE_POSTED,
      agJumlahDibaca AS READED,
      agTanggalAgenda AS DATE
   FROM w_agenda
   WHERE agIsAktif = '1' AND agPengirim='%s'
   ORDER BY agTanggalMulai DESC, agTanggalPosting DESC
   LIMIT %d, %d
";

$sql['count_agenda'] = "
   SELECT
      COUNT(agId) AS NUMBER
   FROM w_agenda
   WHERE agPengirim='%s'
";

$sql['count_agenda_aktif'] = "
   SELECT
      COUNT(agId) AS NUMBER
   FROM w_agenda
   WHERE agTanggalMulai < CURRENT_DATE() AND agPengirim='%s'
";

$sql['add_agenda'] = "
   INSERT INTO
      w_agenda
   SET
      agNama = '%s',
      agArtikel = '%s',
      agTanggalMulai = '%s',
      agTanggalSelesai = '%s',
      agTempat = '%s',
      agFoto = '%s',
      agCaptionFoto = '%s',
      agIsSticky = '%s',
      agPengirim = '%s',
      agTanggalPosting = NOW(),
      agJumlahDibaca = 0
";

$sql['update_agenda'] = "
   UPDATE
      w_agenda
   SET
      agNama = '%s',
      agArtikel = '%s',
      agTanggalMulai = '%s',
      agTanggalSelesai = '%s',
      agTempat = '%s',
      agFoto = '%s',
      agCaptionFoto = '%s',
      agIsSticky = '%s',
      agPengirim = '%s',
      agTanggalPosting = NOW()
   WHERE
      agId = '%s'
";

$sql['delete_agenda'] = "
   DELETE FROM
      w_agenda
   WHERE
      agId = '%s'
";

$sql['get_agenda_by_id'] = "
   SELECT
      agId AS ID,
      agNama AS TITLE,
      agArtikel AS ARTICLE,
      agTanggalMulai AS START_DATE,
      agTanggalSelesai AS END_DATE,
      agTempat AS LOCATION,
      agUrl AS URL,
      agFoto AS FOTO,
      agCaptionFoto AS CAPTION,
      agIsSticky AS STATUS,
      agPengirim AS SENDER,
      agTanggalPosting AS DATE_POSTED,
      agJumlahDibaca AS READED
   FROM w_agenda
   WHERE agId = '%s'
";
?>