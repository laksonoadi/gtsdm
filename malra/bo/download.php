<?php
/*
@Author : Wahyono  (wahyono@gamatechno.com)
@Vers : V.0.1
@Year : 2011
*/
	$task = $_REQUEST['task'];
	$file = $_REQUEST['file'];
	$jenis = $_REQUEST['jenis'];
	switch($task) {
		case 'download':
			// lokasi file
			$file_path = 'upload_file/'.$jenis.'/'.$file;
			
			if (file_exists($file_path)){
			
				$type = strtolower($file);
				while (strstr($type,'@')!=false){
					$type = strstr($type,'@');
					$type = substr ($type, 1);
				}
				
				$arrType = array(
								'zip' => 'application/zip',
								'tar' => 'application/zip',
								'rar' => 'application/zip',
								'pdf' => 'application/pdf',
								'txt' => 'text/plain',
								'js'  => 'text/plain',
								'css' => 'text/plain',
								'php' => 'text/plain',
								'asp' => 'text/plain',
								'rtf' => 'application/msword',
								'doc' => 'application/msword',
								'docx' => 'application/msword',
								'xls' => 'application/vnd.ms-excel',
								'xlsx' => 'application/vnd.ms-excel',
								'ppt' => 'application/vnd.ms-powerpoint',
								'exe' => 'application/octet-stream',
								'gif' => 'image/gif',
								'png' => 'image/png',
								'jpeg'=> 'image/jpeg',
								'jpg' => 'image/jpeg',
								'bmp' => 'image/jpeg',
								'mpeg'=> 'audio/mpeg'
							);
				// fungsi untuk mengambil nama file tanpa path
				$file_name = basename($file_path);

				// ambil ukuran file
				$fsize = filesize($file_path);

				// set headers
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Type: ".$arrType[$type]);
				header('Content-Disposition: attachment; filename="' . $file_name . '"');
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $fsize);

				// mulai men-download dari sini
				$file = @fopen($file_path,"rb");
				if ($file) {
					while(!feof($file)) {
						print(fread($file, 1024*8));
						flush();
						if (connection_status()!=0) {
							@fclose($file);
							die();
						}
					}
					@fclose($file);
				}
			}else{
				echo 'File Tidak Ditemukan, Silahkan Kontak Administrator Website';
			}
			break;
		default:
			echo 'File Tidak Ditemukan';
			break;
	}
?>