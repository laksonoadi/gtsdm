<?php
/*$sql['list_policy'] ="
   SELECT
      policyId,
      satkerNama,
      jnspolicyNama,
      policyNama,
      policyTanggalPolicy,
      policyIsAktif,
      policyKeterangan,
      count(filepolicyId) as total_file
   FROM 
    w_policy
    LEFT JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
    LEFT JOIN w_policy_file ON filepolicyPolicyId=policyId
    LEFT JOIN w_ref_satuan_kerja_policy ON (policySatkerpolicyId=satkerpolicyId)
    LEFT JOIN pub_satuan_kerja ON (satkerpolicySatkerId=satkerId) 
    GROUP BY policyId
   ORDER BY policyTanggalPolicy DESC, policyTanggalPosting DESC
   LIMIT %d, %d
";*/

$sql['list_policy'] ="
   SELECT
      policyId,
      satkerNama,
      jnspolicyNama,
      policyNama,
      policyTanggalPolicy,
      policyIsAktif,
      policyKeterangan,
      count(filepolicyId) as total_file
   FROM 
    w_policy
    LEFT JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
    LEFT JOIN w_policy_file ON filepolicyPolicyId=policyId
    LEFT JOIN w_ref_satuan_kerja_policy ON (policySatkerpolicyId=satkerpolicyId)
    LEFT JOIN pub_satuan_kerja ON (satkerpolicySatkerId=satkerId)
   %s
   GROUP BY policyId
   ORDER BY policyTanggalPolicy DESC, policyTanggalPosting DESC
   LIMIT %d, %d
";

$sql['list_policy_aktif'] ="
   SELECT
      *
   FROM 
      w_policy
      INNER JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
      INNER JOIN w_ref_satuan_kerja_policy ON (policySatkerpolicyId=satkerpolicyId)
      LEFT JOIN pub_satuan_kerja ON (satkerpolicySatkerId=satkerId)
   WHERE policyIsAktif = '1'
   ORDER BY policyTanggalPolicy DESC, policyTanggalPosting DESC
   LIMIT %d, %d
";

$sql['list_type_policy'] ="
   SELECT
      *
   FROM 
      w_ref_jenis_policy
   ORDER BY jnspolicyTgl DESC
";

/*$sql['count_policy'] = "
   SELECT
      COUNT(policyId) AS NUMBER
   FROM w_policy
";*/

$sql['count_policy'] = "
   SELECT
      COUNT(policyId) AS NUMBER
   FROM w_policy
   LEFT JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
   LEFT JOIN w_policy_file ON filepolicyPolicyId=policyId
   LEFT JOIN w_ref_satuan_kerja_policy ON (policySatkerpolicyId=satkerpolicyId)
   LEFT JOIN pub_satuan_kerja ON (satkerpolicySatkerId=satkerId)
   WHERE
    policyIsAktif<>2
    %s
";

$sql['count_policy_aktif'] = "
   SELECT
      COUNT(policyId) AS NUMBER
   FROM w_policy
   WHERE policyIsAktif = '1'
";

$sql['get_id_max'] = "
   SELECT
      MAX(policyId) AS IDMAX
   FROM w_policy
";

$sql['get_combo_jenis_policy']="
SELECT 
   jnspolicyId as id,
   jnspolicyNama as name
FROM w_ref_jenis_policy
ORDER BY jnspolicyNama ASC
";

$sql['get_combo_satuan_kerja']="
   SELECT
      satkerpolicyId as id,
      satkerNama as name
   FROM w_ref_satuan_kerja_policy
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpolicySatkerId
   WHERE
    satkerpolicyStatus='Aktif'
";

$sql['get_combo_tipe'] = "
   SELECT
      jnspolicyId as id,
      jnspolicyNama as name
   FROM w_ref_jenis_policy
   ORDER BY jnspolicyNama ASC
";

$sql['get_combo_satker_policy'] = "
   SELECT
      satkerpolicyId as id,
      satkerNama as name
   FROM w_ref_satuan_kerja_policy
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpolicySatkerId
   WHERE
    satkerpolicyStatus='Aktif'
";

$sql['add_policy'] = "
   INSERT INTO w_policy (
              policySatkerpolicyId,
              policyJnspolicyId,
              policyNama,
              policyKeterangan,
              policyUrl,
              policyIsAktif,
              policyPengirim,
              policyTanggalPosting,
              policyTanggalPolicy,
              policyJumlahDibaca,
              policyUserId
            )
   VALUES
      ('%s','%s','%s','%s','%s','%s','%s',now(),'%s',0,'%s')
";

$sql['add_detail_policy'] = "
   INSERT INTO w_policy_pegawai (
			  policyPegawaiPolicyId,
			  policyPegawaiPegId
            )
   VALUES
      ('%s','%s')
";

$sql['add_file_policy'] = "
   INSERT INTO w_policy_file (
              filepolicyPolicyId,
              filepolicyFile,
              filepolicyTgl,
              filepolicyStatus,
              filepolicyIsDownload
            )
   VALUES
      ('%s','%s',now(),'%s','%s')
";

$sql['add_type'] = "
   INSERT INTO w_ref_jenis_policy (
              jnspolicyNama,
              jnspolicyTgl
            )
   VALUES
      ('%s',now())
";

$sql['update_policy'] = "
   UPDATE
      w_policy
   SET
      policySatkerpolicyId='%s',
      policyJnspolicyId='%s',
      policyNama='%s',
      policyKeterangan='%s',
      policyUrl='%s',
      policyIsAktif='%s',
      policyPengirim='%s',
      policyTanggalPosting=now(),
      policyTanggalPolicy='%s',
      policyUserId='%s'
   WHERE
      policyId = '%s'
";

$sql['update_type'] = "
   UPDATE
      w_ref_jenis_policy
   SET
      jnspolicyNama='%s',
      jnspolicyTgl=now()
   WHERE
      jnspolicyId = '%s'
";

$sql['update_file_policy'] = "
   UPDATE
      w_policy_file
   SET
      filepolicyPolicyId='%s',
      filepolicyFile='%s',
      filepolicyTgl=now(),
      filepolicyStatus='%s',
      filepolicyIsDownload='%s'
   WHERE
      filepolicyId = '%s'
";

$sql['update_status_file_policy'] = "
   UPDATE
      w_policy_file
   SET
      filepolicyStatus='%s'
   WHERE
      filepolicyId = '%s'
";

$sql['update_is_download_file_policy'] = "
   UPDATE
      w_policy_file
   SET
      filepolicyIsDownload='%s'
   WHERE
      filepolicyId = '%s'
";

$sql['delete_pegawai_policy'] = "
   DELETE FROM
      w_policy_pegawai
   WHERE
      policyPegawaiPolicyId = '%s'
";

$sql['delete_pegawai_id_policy'] = "
   DELETE FROM
      w_policy_pegawai
   WHERE
      policyPegawaiId = '%s'
";

$sql['delete_policy'] = "
   DELETE FROM
      w_policy
   WHERE
      policyId = '%s'
";

$sql['delete_type'] = "
   DELETE FROM
      w_ref_jenis_policy
   WHERE
      jnspolicyId = '%s'
";

$sql['delete_file_policy'] = "
   DELETE FROM
      w_policy_file
   WHERE
      filepolicyId = '%s'
";

$sql['get_policy_by_id'] = "
   SELECT
      *
   FROM 
      w_policy
      INNER JOIN w_ref_jenis_policy ON (policyJnspolicyId=jnspolicyId)
      INNER JOIN w_ref_satuan_kerja_policy ON (policySatkerpolicyId=satkerpolicyId)
      LEFT JOIN pub_satuan_kerja ON (satkerpolicySatkerId=satkerId)
   WHERE policyId = '%s'
";

$sql['get_pegawai_policy_by_id'] = "
	SELECT
		policyPegawaiId 		AS id,
		policyPegawaiPolicyId 	AS idPolicy,
		pegId 					AS pegId,
		pegKodeResmi 			AS nip,
		pegNama 				AS namaPeg,
	    IF(pktgolStatus='Aktif',CONCAT(pktgolPktgolrId,' - ',pktgolrNama),(SELECT CONCAT(pktgolrId,' - ',pktgolrNama) FROM sdm_ref_pangkat_golongan WHERE pktgolrId=(SELECT pktgolPktgolrId FROM sdm_pangkat_golongan WHERE pktgolPegKode=pegId AND pktgolStatus='Aktif'))) AS pktgol,
	    IF(satkerpegAktif='Aktif',satkerNama,(SELECT satkerNama FROM pub_satuan_kerja WHERE satkerId=(SELECT satkerpegSatkerId FROM sdm_satuan_kerja_pegawai WHERE satkerpegPegId=pegId AND satkerpegAktif='Aktif'))) AS satker,
	    IF(a.jbtnStatus='Aktif',jabstrukrNama,(SELECT jabstrukrNama FROM sdm_ref_jabatan_struktural WHERE jabstrukrId=(SELECT jbtnJabstrukrId FROM sdm_jabatan_struktural WHERE jbtnPegKode=pegId AND jbtnStatus='Aktif'))) AS jabstruk,
	    IF(b.jbtnStatus='Aktif',jabfungrNama,(SELECT jabfungrNama FROM pub_ref_jabatan_fungsional WHERE jabfungrId=(SELECT jbtnJabfungrId FROM sdm_jabatan_fungsional WHERE jbtnPegKode=pegId AND jbtnStatus='Aktif'))) AS jabfung
	FROM
		w_policy_pegawai
	LEFT JOIN pub_pegawai ON pegId = policyPegawaiPegId
	LEFT JOIN sdm_pegawai_detail ON pegdtPegId = pegId
	LEFT JOIN sdm_pangkat_golongan ON pktgolPegKode=pegId AND pktgolStatus='Aktif'
	LEFT JOIN sdm_ref_pangkat_golongan ON pktgolrId=pktgolPktgolrId
	LEFT JOIN sdm_satuan_kerja_pegawai ON satkerpegPegId=pegId AND satkerpegAktif='Aktif'
	LEFT JOIN pub_satuan_kerja ON satkerId=satkerpegSatkerId
	LEFT JOIN sdm_jabatan_struktural a ON a.jbtnPegKode=pegId AND a.jbtnStatus='Aktif'
	LEFT JOIN sdm_ref_jabatan_struktural ON jabstrukrId=a.jbtnJabstrukrId
	LEFT JOIN sdm_jabatan_fungsional b ON b.jbtnPegKode=pegId AND b.jbtnStatus='Aktif'
	LEFT JOIN pub_ref_jabatan_fungsional ON jabfungrId=b.jbtnJabfungrId
	WHERE policyPegawaiPolicyId = '%s'
	GROUP BY pegId
";

$sql['get_file_policy_by_policy_id'] = "
   SELECT
      *
   FROM 
      w_policy_file
   WHERE filepolicyPolicyId = '%s'
";

$sql['get_file_policy_by_id'] = "
   SELECT
      *
   FROM 
      w_policy_file
   WHERE filepolicyId = '%s'
";

$sql['get_type_policy_by_id'] = "
   SELECT
      *
   FROM 
      w_ref_jenis_policy
   WHERE jnspolicyId = '%s'
";

//Policy Department

$sql['list_satker_policy'] ="
   SELECT
      satkerpolicyId,
      satkerpolicySatkerId,
      satkerpolicyDeskripsi,
      satkerpolicyStatus,
      satkerpolicyTgl,
      satkerpolicyUserId,
      satkerNama
   FROM 
    w_ref_satuan_kerja_policy
    LEFT JOIN pub_satuan_kerja ON satkerId=satkerpolicySatkerId
   ORDER BY satkerNama
   LIMIT %d, %d
";

$sql['count_satker_policy'] = "
   SELECT
      COUNT(satkerpolicyId) AS NUMBER
   FROM w_ref_satuan_kerja_policy
";

$sql['get_combo_satker'] = "
   SELECT
      satkerId as id,
      satkerNama as name
   FROM pub_satuan_kerja
";

$sql['add_satker_policy'] = "
   INSERT INTO w_ref_satuan_kerja_policy (
              satkerpolicySatkerId,
              satkerpolicyDeskripsi,
              satkerpolicyStatus,
              satkerpolicyTgl,
              satkerpolicyUserId
            )
   VALUES
      ('%s','%s','%s','%s','%s')
";

$sql['update_satker_policy'] = "
   UPDATE
      w_ref_satuan_kerja_policy
   SET
      satkerpolicySatkerId='%s',
      satkerpolicyDeskripsi='%s',
      satkerpolicyStatus='%s',
      satkerpolicyTgl='%s',
      satkerpolicyUserId='%s'
   WHERE
      satkerpolicyId = '%s'
";

$sql['delete_satker_policy'] = "
   DELETE FROM
      w_ref_satuan_kerja_policy
   WHERE
      satkerpolicyId = '%s'
";

$sql['get_satker_policy_by_id'] = "
   SELECT
      satkerpolicyId,
      satkerpolicySatkerId,
      satkerpolicyDeskripsi,
      satkerpolicyStatus,
      satkerpolicyTgl,
      satkerpolicyUserId,
      satkerNama
   FROM 
      w_ref_satuan_kerja_policy
   LEFT JOIN pub_satuan_kerja ON satkerId=satkerpolicySatkerId
   WHERE satkerpolicyId = '%s'
";
?>