<?php
$sql['get_info'] = "
   SELECT 
      a.pegId as `id`,
      a.pegKodeResmi as `nip`,
      a.pegNama as `nama`,
      a.pegAlamat as `alamat`,
      a.pegNoHp as `hp`,
      a.pegNoTelp as `telp`,
      b.pegrekRekening as `rekening`,
      b.pegrekResipien as `resipien`,
      b.pegrekBankId as `bank`,
      c.bankNama as `bank_label`,
      IF(f.gajipegStatus=0,'Belum Dibayar','Sudah Dibayar') AS status,
		  e1.satkerNama AS `satker_unit`,
      d.mstgajiIsCash as cash,
      d.mstgajiTanggalGaji as tgl_gaji,
      d.mstgajiIsAktif as aktif
   FROM 
      pub_pegawai a
      LEFT JOIN pub_pegawai_rekening b ON (b.pegrekPegId = a.pegId)
      LEFT JOIN pub_ref_bank c ON (c.bankId = b.pegrekBankId)
      LEFT JOIN sdm_ref_master_gaji d ON (d.mstgajiPegId = a.pegId)
      LEFT JOIN sdm_satuan_kerja_pegawai e ON (e.satkerpegPegId = a.pegId)
      LEFT JOIN pub_satuan_kerja e1 ON (e1.satkerId = e.satkerpegSatkerId)
      LEFT JOIN sdm_gaji_pegawai f ON (f.gajipegPegId = a.pegId)
   WHERE
      a.pegId='%s'
";

$sql['get_data'] = "
   SELECT
      kompgajiId as id,
      kompgajiNama as nama,
      kompgajidtNama as nilai
   FROM 
      sdm_ref_komponen_gaji
      JOIN sdm_ref_komponen_gaji_detail ON (kompgajidtKompgajiId = kompgajiId)
      JOIN sdm_komponen_gaji_pegawai_detail ON (kompgajipegdtKompgajidtrId = kompgajidtId)
   WHERE
      kompgajipegdtPegId='%s'
   ORDER BY kompgajiNama,kompgajidtNama DESC
";

?>
