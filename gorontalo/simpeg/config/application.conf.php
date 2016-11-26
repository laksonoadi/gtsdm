<?php
error_reporting(0);
//=============ApplicationId========================
$application['application_id'] = 300; //BackOffice SDM
$application['application_portal_id'] = 301; //Portal Kepegawaian
//=============directory============================

// do not edit this config
$application['gtfw_base'] = GTFW_BASE_DIR;
$application['docroot'] = GTFW_APP_DIR;
///

$application['basedir'] = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']); // with trailling slash
$application['baseaddress'] = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://" . $_SERVER['HTTP_HOST']; // without trailling slash
$application['domain'] = NULL; // name of domain

// $application['basedir'] = 'egov/trunk/devel/proyek/2015/gorontalo/gtsdm_gto/bkd_gto/bo'; // with trailling slash
// $application['baseaddress'] = 'http://localhost'; // without trailling slash
// $application['domain'] = ''; // name of domain
//$application['domain'] = NULL; // name of domain

//============database============================
// connection number 0
$application['db_conn'][0]['db_driv'] = 'adodb';
$application['db_conn'][0]['db_type'] = 'mysqlt';
$application['db_conn'][0]['db_host'] = 'localhost';
$application['db_conn'][0]['db_user'] = 'bkdgtlok_sdm';
$application['db_conn'][0]['db_pass'] = 'b90mww30XQ';
$application['db_conn'][0]['db_name'] = 'bkdgtlok_sdm';
$application['db_conn'][0]['db_result_cache_lifetime'] = '';
$application['db_conn'][0]['db_result_cache_path'] = '';
$application['db_conn'][0]['db_debug_enabled'] = 'true';
$application['db_conn'][0]['db_port'] = '3306';

// connection number 1 untuk gtAkademik
// $application['db_conn'][1]['db_driv'] = 'adodb';
// $application['db_conn'][1]['db_type'] = 'mysqlt';
// $application['db_conn'][1]['db_host'] = 'localhost';
// $application['db_conn'][1]['db_user'] = 'root';
// $application['db_conn'][1]['db_pass'] = 'root';
// $application['db_conn'][1]['db_name'] = 'stip_gtakademik_devel';
// $application['db_conn'][1]['db_result_cache_lifetime'] = '';
// $application['db_conn'][1]['db_result_cache_path'] = '';
// $application['db_conn'][1]['db_debug_enabled'] = 'true';
// $application['db_conn'][1]['db_port'] = '3307';

// // connection number 2 untuk gtFinansi
// $application['db_conn'][2]['db_driv'] = 'adodb';
// $application['db_conn'][2]['db_type'] = 'mysqlt';
// $application['db_conn'][2]['db_host'] = 'localhost';
// $application['db_conn'][2]['db_user'] = 'root';
// $application['db_conn'][2]['db_pass'] = '';
// $application['db_conn'][2]['db_name'] = 'produk_gtfinansi_devel';
// $application['db_conn'][2]['db_result_cache_lifetime'] = '';
// $application['db_conn'][2]['db_result_cache_path'] = '';
// $application['db_conn'][2]['db_debug_enabled'] = 'true';
// $application['db_conn'][2]['db_port'] = '3306';

//============integrasi config============================
//Integrasi gtAkademik
$application['status_integrasi_gtakademik']=false; //Status apakah terintegrasi dengan gtakademik
$application['nomor_koneksi_gtakademik']=1;
//Integrasi gtfinansi
$application['status_integrasi_gtfinansi']=false; //Status apakah terintegrasi dengan gtfinansi
$application['nomor_koneksi_gtfinansi']=2;

//============session============================
$application['use_session'] = TRUE;
$application['session_name'] = 'GTFWSessID';
$application['session_save_path'] = NULL; ///TODO: should not be here!!!, and pelase, support NULL value to fallback to PHP INI's session save path
$application['session_expire'] = 180; // in minutes
$application['session_cookie_params']['lifetime'] = 60 * $application['session_expire']; // in seconds
$application['session_cookie_params']['path'] = $application['basedir'];
$application['session_cookie_params']['domain'] = $application['domain'];
$application['session_cookie_params']['secure'] = FALSE; // needs secure connection?

//============default page============================
$application['default_module'] = 'login_default';
$application['default_submodule'] = 'login';
$application['default_action'] = 'view';
$application['default_type'] = 'html';

// $application['default_page']['mod'] = 'home';
// $application['default_page']['sub'] = 'home';
// $application['default_page']['act'] = 'view';
// $application['default_page']['typ'] = 'html';
$application['default_page']['mod'] = 'dashboard';
$application['default_page']['sub'] = 'dashboard';
$application['default_page']['act'] = 'view';
$application['default_page']['typ'] = 'html';

//============security===========================
$application['enable_security'] = TRUE;
$application['default_user'] = 'nobody';
$application['enable_url_obfuscator'] = FALSE;
$application['url_obfuscator_exception'] = array('soap'); // list of exeption request/response type
$application['url_type'] = 'Long'; // type: Long or Short
$application['login_method'] = 'default';

//============development============================
$application['debug_mode'] = FALSE;

//=========== Single Sign On ========================
$application['system_id'] = 'com.gamatechno.gtfw';
$application['sso_group'] = 'com_gamatechno_academica'; //FIXME: what if this system is associated with more than one sso group

//=========== Single Sign On Server ========================
$application['sso_ldap_connection'] = 3; // connection number available for ldap access, see db_conn above

//============== syslog =============================
$application['syslog_category'] = array(); // what category permitted to be printed out, array() equals all category
$application['syslog_enabled'] = FALSE;
$application['syslog_io_engine'] = 'std'; //tcp, file, std
$application['syslog_log_path'] = '';
$application['syslog_tcp_host'] = 'localhost';
$application['syslog_tcp_port'] = 9777;

//================ soapgateway ========================
$application['wsdl_use_cache'] = false; // use cached wsdl if available
$application['wsdl_cache_path'] = '/tmp/'; // use cached wsdl if available
$application['wsdl_cache_lifetime'] = 60 * 60 * 24 /* one day!*/; // invalidate wsdl cache every x seconds

//================ additional config =====================
$application['company_name'] ="Badan Kepegawaian Daerah";
$application['company_address'] ="Jalan Merdeka, Kota Gorontalo";
$application['company_city'] ="Kota Gorontalo";
$application['application_name'] ="Sistem Informasi Kepegawaian";
$application['tahun'] ="2015";
$application['menu_version'] = "2";
$application['now'] = date('d m Y');

//================ photo_save_path =====================
$application['bo_save_path']="/devel/gtsdm/bo";
$application['bo_download_path']="/devel/gtsdm/bo/";

$application['file_berita'] = "upload_file/file_berita/";
$application['file_agenda'] = "upload_file/file_agenda/";
$application['photo_save_path'] = "upload_file/photo/";
$application['klaim_save_path'] = "upload_file/file_klaim/";
$application['policy_save_path'] = "upload_file/file_policy/";
$application['file_save_path'] = "upload_file/file/";
$application['template_email_path'] = "module/email/template/";

$application['berita_download_path'] = "upload_file/file_berita/";
$application['agenda_download_path'] = "upload_file/file_agenda/";
$application['klaim_download_path'] = "upload_file/file_klaim/";
$application['policy_download_path'] = "upload_file/file_policy/";
$application['file_download_path'] = "upload_file/file/";
$application['photo_download_path'] = "upload_file/photo/";
//================Language=====================
$application['button_lang'] = "ind";//ind or eng  
$application['template_address'] = "template";//template or template_eng 
//================Gaji=====================
$application['set_gaji'] = "negeri";//negeri or swasta

//================Email=====================
$application['send_email'] = false; //Status apakah ada pengiriman email atau tidak isi dengan false atau true
$application['smtp_host'] = 'mail.gamatechno.com';
$application['smtp_port'] = '25';
$application['smtp_auth'] = true;
$application['smtp_username'] = 'email_tester@gamatechno.com';
$application['smtp_password'] = 'gamatechno';

$application['email_notifications'] = true;
$application['email_pengirim'] = 'gtSdm Notification <no-reply@xxx.ac.id>'; 
$application['email_hrd1'] = 'Zaenal Arifin <zaenal@gamatechno.com>';  
$application['email_hrd2'] = 'Zaenal Arifin <zpiderboi@yahoo.com>';
$application['email_footer'] = "\n\r---\n\rgtSDM Human Resource Portal System"; 
$application['email_presubject'] = '[gtSDM System]'; 

$application['year'] = date('Y');
?>