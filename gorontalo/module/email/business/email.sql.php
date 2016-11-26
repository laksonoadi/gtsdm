<?php
$sql['simpan_email'] = "
  INSERT INTO sdm_email (
      emailPengirim,emailPenerima,emailCc,emailBcc,emailSubject,emailIsi,emailDate
  ) values ('%s','%s','%s','%s','%s','%s',now())
";
 
?>