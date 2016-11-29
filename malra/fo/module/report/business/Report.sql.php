<?php

$sql['get_query'] ="
   SELECT 
      b.`queryNama` AS query_nama, 
		b.`queryDesc` AS query_desc, 
		b.`queryKoneksi` AS query_koneksi, 
		b.`queryParam` AS query_param, 
		b.`querySql` AS query_sql, 
		b.`queryId` AS query_id
   FROM
      builder_query b
   ORDER BY
      queryNama";

$sql['get_query_by_nama_asli'] ="
   SELECT 
      queryId,
      queryNama,
      queryDesc,
      querySql,
      queryKoneksi,
      queryParam
   FROM
      builder_query
   WHERE
      UPPER(queryNama) LIKE UPPER('%s') OR
      UPPER(queryDesc) LIKE UPPER('%s')
   ORDER BY
      queryNama
   LIMIT %d, %d";

$sql['get_total_query'] ="
   SELECT 
      COUNT(queryId) as total
   FROM 
      builder_query
   WHERE 
      UPPER(queryNama) LIKE UPPER('%s') OR
      UPPER(queryDesc) LIKE UPPER('%s')";
      
$sql['get_query_by_id'] ="
   SELECT 
		b.`queryId` AS query_id,
		b.`queryNama` AS query_nama, 
		b.`queryDesc` AS query_desc, 
		b.`queryKoneksi` AS query_koneksi, 
		b.`queryParam` AS query_param, 
		b.`querySql`  AS query_sql
	FROM 
		builder_query b
   WHERE
      b.queryId=%d";
      
$sql['get_retribusi_by_id'] ="
   SELECT 
      *
   FROM
      builder_retribusi_jenis_izin
   WHERE
      brji_id=%d";

$sql['do_insert_query'] = "
   INSERT INTO
      builder_query(queryNama, queryDesc, querySql, queryParam, queryKoneksi)
   VALUES
	  ('%s', '%s', '%s', '%s', '%s')";

$sql['do_update_query'] = "
   UPDATE
      builder_query
   SET
      queryNama = '%s',
      queryDesc = '%s',
      querySql = '%s',
      queryParam = '%s',
      queryKoneksi = '%s'
   WHERE
      queryId='%s'";

$sql['do_delete_query'] ="
   DELETE FROM
      builder_query
   WHERE
      queryId=%d";

$sql['show_tables'] ="
   SHOW TABLES";

$sql['show_colums_tables'] ="
   DESCRIBE %s";

//table
$sql['get_table'] ="
   SELECT 
      tableId AS table_id,
      tableNama AS table_nama,
      tablePhpCode AS table_php_code,
      tableIsGraphic AS table_is_graphic,
      tableParam AS table_param
   FROM
      builder_table";

$sql['get_table_by_nama_asli'] ="
   SELECT 
      tableId,
      tableNama,
      tablePhpCode,
      tableIsGraphic,
      tableParam
   FROM
      builder_table
   WHERE
      UPPER(tableNama) LIKE UPPER('%s')
   ORDER BY
      tableNama
   LIMIT %d, %d";

$sql['get_total_table'] ="
   SELECT 
      count(tableId) as total
   FROM
      builder_table
   WHERE
      upper(tableNama) like upper('%s')";

$sql['get_table_by_id'] ="
   SELECT 
		b.`tableId`, 
		b.`tableNama` AS table_nama, 
		b.`tableIsGraphic`, 
		b.`tablePhpCode`, 
		b.`tableParam` 
	FROM 
		builder_table b
   WHERE
      tableId=%d";
      
$sql['do_insert_table'] = "
   INSERT INTO
      builder_table (tableNama,tablePhpCode,tableIsGraphic,tableParam)
   VALUES
	  ('%s', '%s', '%s', '%s')";

$sql['do_update_table'] = "
   UPDATE
      builder_table
   SET
      tableNama = '%s',
      tablePhpCode = '%s',
      tableIsGraphic = '%s',
      tableParam = '%s'
   WHERE
      tableId=%d";

$sql['doDelete_table'] ="
   DELETE FROM
      builder_table
   WHERE
      tableId=%d";

//===

$sql['get_layout'] ="
   SELECT 
      a.*, b.menuMenu AS dmmenuname, c.tableNama
   FROM
      builder_layout a
      LEFT JOIN builder_menu b ON a.layoutId=b.MenuId
      LEFT JOIN builder_table c ON a.layoutTemplate=c.tableId";

$sql['get_layout_by_nama_asli'] ="
   SELECT 
      a.*, b.dummy_menu, c.tableNama
   FROM
      builder_layout a
      LEFT JOIN builderDummy_menu b ON a.layoutDummy_id=b.dummy_id
      LEFT JOIN builder_table c ON a.layoutTemplate=c.tableId
   WHERE
      UPPER(layoutJudul) LIKE UPPER('%s') OR UPPER(dummy_menu) LIKE UPPER('%s')
   ORDER BY
      tableNama
   LIMIT %d, %d";
   
$sql['get_total_layout'] ="
   SELECT 
      count(layoutId) as total
   FROM
      builder_layout a
      LEFT JOIN builderDummy_menu b ON a.layoutDummy_id=b.dummy_id
   WHERE
      upper(layoutJudul) like upper('%s') OR upper(dummy_menu) like upper('%s')";

$sql['get_layout_by_id'] ="
   SELECT 
      a.*, b.*, d.dummy_parent_menuId, d.dummy_icon_path, d.dummy_id, d.dummy_menu, d.dummy_order AS urutan
   FROM
      builder_layout a
      left join builder_table b ON a.layoutTemplate=b.tableId
      left join builderDummy_menu d ON a.layoutDummy_id=d.dummy_id
   WHERE
      layoutId=%d";
      
$sql['get_bridge_by_id'] ="
   SELECT 
      *
   FROM
      builder_bridge
   WHERE
      bridge_layoutId=%d";

$sql['do_insert_layout'] = "
   INSERT INTO
      builder_layout(layoutJudul, layoutTemplate, layoutDummy_id)
   VALUES
	  ('%s', '%s', '%s')";

$sql['do_update_layout'] = "
   UPDATE
      builder_layout
   SET
      layoutJudul = '%s',
      layoutTemplate = '%s'
   WHERE
      layoutId='%s'";

$sql['do_delete_layout'] ="
   DELETE FROM
      builder_layout
   WHERE
      layoutId=%d";
      
$sql['do_delete_menu'] ="
   DELETE FROM
      dummy_menu
   WHERE
      DmMenuId=%d";

$sql['get_sub_menu'] ="
   select 
      menuId AS dmmenuid, menuMenu AS dmmenuname
   from 
      builder_menu
   where 
      menuParentMenuId=0 and menuIsShow ='Yes'
   order by 
      menuMenu";

$sql['do_insert_menu'] ="
   insert into 
      builderDummy_menu (dummy_parent_menuId,	dummy_menu,	dummy_module_id, dummy_isShow,	dummy_icon_path,	
         dummy_order, dummy_aksi)
	values ('%s', '%s', 888, 'Yes', '%s', %d, '1|28|32')";
	  
$sql['do_insert_bridge'] ="
	insert into 
      builder_bridge(bridge_group_menuId, bridge_layoutId)
   select max(dmmenuid), max(layoutId) from builder_layout,dummy_menu";
   
$sql['do_update_menu'] ="
   UPDATE 
      builderDummy_menu
   SET 
      dummy_parent_menuId = %s,	dummy_menu = %s, dummy_icon_path = %s,	dummy_order = %s
   WHERE
      dummy_id=%d";
      
//== Grafik

$sql['get_graphic_by_nama_asli'] ="
   SELECT * FROM (
   SELECT rownum AS no, a.* FROM
   (
   SELECT 
      a.*, b.tableNama, c.layoutJudul
   FROM
      builder_graphic a
      LEFT JOIN builder_table b ON a.graphic_tableId=b.tableId
      LEFT JOIN builder_layout c ON a.graphic_layoutId=c.layoutId
   WHERE
      upper(graphicJudul) like upper('%s')
   ORDER BY
      graphicJudul
   ) a ) WHERE no BETWEEN %d AND %d";

$sql['get_total_graphic'] ="
   SELECT 
      count(graphicId) as total
   FROM
      builder_graphic
   WHERE
      upper(graphicJudul) like upper('%s')";

$sql['get_graphic_by_id'] ="
   SELECT 
      a.*, b.tableNama, c.layoutJudul
   FROM
      builder_graphic a
      left join builder_table b ON a.graphicTableId=b.tableId
      left join builder_layout c ON a.graphicLayoutId=c.layoutId
   WHERE
      graphicId=%d";

$sql['do_insert_graphic'] ="
	insert into 
      builder_graphic(graphicJudul, graphic_tableId, graphic_layoutId)
   VALUES ('%s', '%s', '%s')";
   
$sql['do_update_graphic'] ="
   UPDATE 
      builder_graphic
   SET 
      graphicJudul='%s',
      graphic_tableId='%s',
      graphic_layoutId = '%s'
   WHERE
      graphic_id=%d";

$sql['do_delete_graphic'] ="
   DELETE FROM
      builder_graphic
   WHERE
      graphic_id=%d";
      
//Parameter
      
$sql['count_param'] ="
   SELECT 
      count(param_id) AS total
   FROM 
      builderParam
   WHERE 
      upper(paramNama) like upper('%s')";

$sql['list_param'] ="
   SELECT
      *
   FROM
      builderParam
   WHERE
      UPPER(paramNama) LIKE UPPER('%s')
   ORDER BY
      paramNama
   LIMIT %d, %d";      
      
$sql['get_param_by_id'] ="
   SELECT 
      *
   FROM
      builderParam
   WHERE
      param_id=%d";

$sql['do_insert_param'] ="
   INSERT INTO
      builderParam
   VALUES 
      (%d, '%s', '%s', '%s')";
       
$sql['do_update_param'] ="
   UPDATE 
      builderParam
   SET
      paramNama = '%s',
      paramElementForm = '%s',
      param_php_code = '%s'
   WHERE
      param_id = %d";
      
$sql['do_delete_param'] ="
   DELETE FROM
      builderParam
   WHERE
      param_id = %d";
      
$sql['do_insert_dummy_module'] ="
   INSERT INTO 
      dummy_module (DummyModuleId, DmMenuId, ModuleId) 
   VALUES 
      ((SELECT MAX(DummyModuleId)+1 FROM dummy_module), %s, %d)";

$sql['do_delete_dummy_module'] ="
   DELETE FROM
      dummy_module
   WHERE
      DmMenuId = %d AND	ModuleId = %d";
      
$sql['get_graphic_by_id_layout'] ="
   SELECT 
      a.*, b.tableNama, c.layoutJudul, b.tableParam
   FROM
      builder_graphic a
      left join builder_table b ON a.graphic_tableId=b.tableId
      left join builder_layout c ON a.graphic_layoutId=c.layoutId
   WHERE
      graphic_layoutId=%d";

$sql['get_menu_builder_group'] ="
   SELECT 
      menuId
   FROM
      builder_menu a
      LEFT JOIN builder_layout b ON a.dummyDummy_id=b.layoutDummy_id
   WHERE
     layoutId = %d AND menu_group_id = %d";

$sql['get_builder_retribusi'] ="
   SELECT 
      a.brt_id as tarifId, a.brtNama as label, b.brfNama as tarif, b.brf_nilai as tarifNilai
   FROM
      builder_retribusi_tabel a
      LEFT JOIN builder_retribusiField b ON a.brt_id=b.brf_brt_id
      LEFT JOIN builder_retribusi_jenis_izin c ON a.brt_brji_id=c.BRJI_ID
   WHERE
      brji_jenisizinid = %d
   ORDER BY
      brt_id, brf_id";

$sql['get_builder_retribusi_by_brji_id'] ="
   SELECT 
      a.brt_id as tarifId, a.brtNama as label, b.brfNama as tarif, b.brf_nilai as tarifNilai
   FROM
      builder_retribusi_tabel a
      LEFT JOIN builder_retribusiField b ON a.brt_id=b.brf_brt_id
      LEFT JOIN builder_retribusi_jenis_izin c ON a.brt_brji_id=c.brji_id
   WHERE
      BRJI_ID = %d
   ORDER BY
      BRT_ID, BRF_ID";
//----------------------------------------------------------------dsesuaikan---------------------------------------------------------------------------------- 
//graphic sql-----
$sql['get_graphic_by_nama'] ="
   SELECT 
   graphicJudul,
   tableNama,
   layoutJudul,
   graphicId,
   tableId,
   graphicLayoutId
   FROM
   builder_graphic
   LEFT JOIN builder_table ON graphicTableId = tableId
   LEFT JOIN builder_layout ON graphicLayoutId = layoutId 
   WHERE
   graphicJudul LIKE '%s'
   ORDER BY graphicJudul

   LIMIT %s, %s
   ";
//layout table-----
$sql['get_layout_by_nama'] ="
   SELECT 
   layoutJudul AS layout_judul,
   layoutId AS layout_id,
   menuMenu,
   tableNama,
   layoutMenuIdd,
   layoutTemplate
   FROM
   builder_layout
      LEFT JOIN builder_menu  ON layoutMenuId=menuId
      LEFT JOIN builder_table ON layoutTemplate=tableId
   WHERE
   layoutJudul LIKE '%s'
   ORDER BY layoutJudul
   LIMIT %s, %s"; 

$sql['get_table_by_nama'] ="
   
   SELECT 
      tableId AS table_id,
      tableNama AS table_nama,
      tablePhpCode AS table_php_code,
      tableIsGraphic AS table_is_graphic,
      tableParam AS table_param
   FROM
      builder_table
   WHERE
      tableNama LIKE '%s'
   ORDER BY
      tableNama
   LIMIT %s, %s";  
   
$sql['get_query_by_nama'] ="
   
   SELECT 
      queryId AS query_id,
      queryNama AS query_nama,
      queryDesc AS query_desc,
      querySql AS query_sql,
      queryKoneksi AS query_koneksi,
      queryParam AS query_param
   FROM
      builder_query
   WHERE
     queryNama LIKE '%s'
   ORDER BY
      queryNama
  LIMIT %s, %s"; 
  
?>
