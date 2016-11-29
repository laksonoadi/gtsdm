<?php

$sql['get_count_data_template']="
   SELECT 
      Count(*) AS total
   FROM 
      cetak_template";

$sql['get_data_template']=
   "SELECT 
      CtkTmplId AS template_id,
      CtkTmplNama AS template_nama,
      IFNULL(CtkTmplPath,'Belum Ada Template') AS template_path,
      case CtkTmplAktif 
         when 'Ya' then 'Aktif'
         else
         'Tidak Aktif'
      end
      AS template_status,
      case CtkTmplAktif 
         when 'Ya' then 'Non Aktifkan'
         else
         'Aktifkan'
      end
      AS status_tombol,
      CtkTmplIsDefault AS template_is_default      
   FROM 
      cetak_template";
   
$sql['get_data_template_by_id']="
   SELECT 
      CtkTmplId AS template_id,
      CtkTmplNama AS template_nama,
      IFNULL(CtkTmplPath,'Belum Ada Template') AS template_path,
      CtkTmplIsDefault AS template_is_default
   FROM
      cetak_template
   WHERE
      CtkTmplId='%s'";

$sql['get_variable_cetak']=
   "SELECT 
      CtkVarId AS var_cetak_id,
      CtkVarNama AS var_cetak_nama
   FROM
      cetak_variable";
      
 //====do=======
 
 $sql['do_update_status']=
 "UPDATE cetak_template
	SET
		CtkTmplAktif='%s'
	WHERE
		CtkTmplId='%s'";

      
$sql['do_add_template']="INSERT INTO cetak_template 
         (CtkTmplNama, 
         CtkTmplPath)
         VALUES			
         ('%s', 
         '%s')";
$sql['do_update_template']=
  "UPDATE cetak_template
   SET 
      CtkTmplNama='%s',
      CtkTmplPath='%s'
   WHERE
   CtkTmplId='%s'";
   
$sql['do_delete_template_by_id']=
   "DELETE FROM cetak_template
   WHERE CtkTmplId='%s'";

$sql['do_delete_template_by_array_id']=
   "DELETE FROM cetak_template
   WHERE CtkTmplId IN('%s')";

?>