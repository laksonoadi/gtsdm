<?php
require_once GTFWConfiguration::GetValue( 'application', 'docroot').'module/layanan_sk_kgb/business/layanankgb.class.php';

class ViewCetakSkLayananKgb extends HtmlResponse {
	public function ProcessRequest(){
		$id = Dispatcher::Instance()->Decrypt($_GET['id']->Raw());
		if(!empty($id)){
			$obj = new LayananKgb();
			
			$res = $obj->getSkKgbById($id);
			if(!empty($res)){
				$contents = file_get_contents(GTFWConfiguration::GetValue( 'application', 'docroot').'doc/template_layanan_kenaikan_gaji.rtf');
				$contents = str_replace('}{\lang1035\langfe255\langnp1035\insrsid13501539\charrsid7032298 ]}',']', $contents);
				
				$data = array(
					'NO_SK'				=> $res['no_sk'],
					'TGL_SK'			=> $this->date2string($res['tgl_sk']),
					'NAMA'				=> $res['nama_gelar'],
					'NIP'				=> $res['nip'],
					'PANGKAT_NAMA'		=> $res['pngkt_nama'],
					'PANGKAT'			=> $res['pngkt'],
					'SATKER'			=> $res['satker'],
					'OLD_GAJI'			=> $this->rupiah($res['gaji_kgb']),
					'OLD_PEJABAT'		=> $res['pjbt_kgb_old'],
					'OLD_KGB_TGL'		=> $this->date2string($res['tgl_kgb_lalu']),
					'OLD_KGB_NO'		=> $res['no_kgb_old'],
					'OLD_KGB_MULAI'		=> $this->date2string($res['kgb_berlaku_old']),
					'OLD_MK_THN'		=> (int) $res['mk_thn_kgb_old'],
					'OLD_MK_BLN'		=> (int) $res['mk_bln_kgb_old'],
					
					'NEW_GAJI'			=> $this->rupiah($res['new_gaji']),
					'NEW_MK_THN'		=> (int) $res['masa_kerja_tahun'],
					'NEW_MK_BLN'		=> (int) $res['masa_kerja_bulan'],
					'START_SK'			=> $this->date2string($res['start_sk']),
					'NEXT_SK'			=> $this->date2string($res['next_sk']),
					'PEJABAT_JABATAN'	=> $res['pejabat_jbtn_sk'],
					'PEJABAT_NAMA'		=> $res['pejabat_sk'],
					'PEJABAT_PANGKAT'	=> $res['pejabat_pngkt_sk'],
					'PEJABAT_NIP'		=> $res['pejabat_nip_sk'],
					'TEMBUSAN_TXT'		=> (isset($res['tembusan_sk']) && $res['tembusan_sk'] != '' ? 'TEMBUSAN' : ''),
					'TEMBUSAN'			=> (isset($res['tembusan_sk']) && $res['tembusan_sk'] != '' ? 'disampaikan kepada Yth :\par '.str_replace(array("\r\n", "\n", "\r"), '\par ', $res['tembusan_sk']) : ''),
				);
				$nama = str_replace(" ", "_", $res['nama']);
				$filename = "layanan_sk_kgb_".$nama.".rtf";
				
				$tagStart = '[';
				$tagEnd = ']';
				foreach($data as $patt => $value){
					$contents = str_replace($tagStart . $patt . $tagEnd, $value, $contents);
				}
				$contents = preg_replace('/\[[^\]]\]/', '', $contents);
				// header('Content-type: ' . $this->getMimeType('rtf'));
				header('Content-type: application/msword');
				header('Content-Length: ' . strlen($contents));
				$this->setHeader($filename);
				echo $contents;
			}else{
				echo 'Data not found';
			}
		}else{
			echo 'Data not found';
		}
		die;   
	}
	
	private function setHeader($filename){
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		header('Connection: keep-alive');
		header('Content-Disposition: inline; filename="' . $filename . '"');
	}
	
	private function getMimeType($type='txt'){
		$mime = array(
			'3dm'	=> 'x-world/x-3dmf',
			'3dmf'	=> 'x-world/x-3dmf',
			'a'		=> 'application/octet-stream',
			'aab'	=> 'application/x-authorware-bin',
			'aam'	=> 'application/x-authorware-map',
			'aas'	=> 'application/x-authorware-seg',
			'abc'	=> 'text/vnd.abc',
			'acgi'	=> 'text/html',
			'afl'	=> 'video/animaflex',
			'ai'	=> 'application/postscript',
			'aif'	=> 'audio/x-aiff',
			'aifc'	=> 'audio/x-aiff',
			'aiff'	=> 'audio/x-aiff',
			'aim'	=> 'application/x-aim',
			'aip'	=> 'text/x-audiosoft-intra',
			'ani'	=> 'application/x-navi-animation',
			'aos'	=> 'application/x-nokia-9000-communicator-add-on-software',
			'aps'	=> 'application/mime',
			'arc'	=> 'application/octet-stream',
			'arj'	=> 'application/octet-stream',
			'art'	=> 'image/x-jg',
			'asf'	=> 'video/x-ms-asf',
			'asm'	=> 'text/x-asm',
			'asp'	=> 'text/asp',
			'asx'	=> 'application/x-mplayer2',
			'asx'	=> 'video/x-ms-asf',
			'au'	=> 'audio/x-au',
			'avi'	=> 'video/avi',
			'bcpio'	=> 'application/x-bcpio',
			'bin'	=> 'application/octet-stream',
			'bm'	=> 'image/bmp',
			'bmp'	=> 'image/bmp',
			'boo'	=> 'application/book',
			'book'	=> 'application/book',
			'boz'	=> 'application/x-bzip2',
			'bsh'	=> 'application/x-bsh',
			'bz'	=> 'application/x-bzip',
			'bz2'	=> 'application/x-bzip2',
			'c'		=> 'text/plain',
			'c++'	=> 'text/plain',
			'cat'	=> 'application/vnd.ms-pki.seccat',
			'cc'	=> 'text/plain',
			'ccad'	=> 'application/clariscad',
			'cco'	=> 'application/x-cocoa',
			'cdf'	=> 'application/x-cdf',
			'cer'	=> 'application/x-x509-ca-cert',
			'cha'	=> 'application/x-chat',
			'chat'	=> 'application/x-chat',
			'class'	=> 'application/java',
			'com'	=> 'text/plain',
			'conf'	=> 'text/plain',
			'cpio'	=> 'application/x-cpio',
			'cpp'	=> 'text/x-c',
			'cpt'	=> 'application/x-cpt',
			'crl'	=> 'application/pkcs-crl',
			'crt'	=> 'application/x-x509-ca-cert',
			'csh'	=> 'text/x-script.csh',
			'css'	=> 'text/css',
			'cxx'	=> 'text/plain',
			'dcr'	=> 'application/x-director',
			'deepv'	=> 'application/x-deepv',
			'def'	=> 'text/plain',
			'der'	=> 'application/x-x509-ca-cert',
			'dif'	=> 'video/x-dv',
			'dir'	=> 'application/x-director',
			'dl'	=> 'video/x-dl',
			'doc'	=> 'application/msword',
			'dot'	=> 'application/msword',
			'dp'	=> 'application/commonground',
			'drw'	=> 'application/drafting',
			'dump'	=> 'application/octet-stream',
			'dv'	=> 'video/x-dv',
			'dvi'	=> 'application/x-dvi',
			'dwf'	=> 'model/vnd.dwf',
			'dwg'	=> 'image/vnd.dwg',
			'dxf'	=> 'image/vnd.dwg',
			'dxr'	=> 'application/x-director',
			'el'	=> 'text/x-script.elisp',
			'elc'	=> 'application/x-bytecode.elisp (compiled elisp)',
			'elc'	=> 'application/x-elc',
			'env'	=> 'application/x-envoy',
			'eps'	=> 'application/postscript',
			'es'	=> 'application/x-esrehber',
			'etx'	=> 'text/x-setext',
			'evy'	=> 'application/x-envoy',
			'exe'	=> 'application/octet-stream',
			'f'		=> 'text/plain',
			'f77'	=> 'text/x-fortran',
			'f90'	=> 'text/x-fortran',
			'fdf'	=> 'application/vnd.fdf',
			'fif'	=> 'image/fif',
			'fli'	=> 'video/x-fli',
			'flo'	=> 'image/florian',
			'flx'	=> 'text/vnd.fmi.flexstor',
			'fmf'	=> 'video/x-atomic3d-feature',
			'for'	=> 'text/x-fortran',
			'fpx'	=> 'image/vnd.fpx',
			'frl'	=> 'application/freeloader',
			'funk'	=> 'audio/make',
			'g'		=> 'text/plain',
			'g3'	=> 'image/g3fax',
			'gif'	=> 'image/gif',
			'gl'	=> 'video/x-gl',
			'gsd'	=> 'audio/x-gsm',
			'gsm'	=> 'audio/x-gsm',
			'gsp'	=> 'application/x-gsp',
			'gss'	=> 'application/x-gss',
			'gtar'	=> 'application/x-gtar',
			'gz'	=> 'application/x-gzip',
			'gzip'	=> 'application/x-gzip',
			'h'		=> 'text/plain',
			'hdf'	=> 'application/x-hdf',
			'help'	=> 'application/x-helpfile',
			'hgl'	=> 'application/vnd.hp-hpgl',
			'hh'	=> 'text/plain',
			'hlb'	=> 'text/x-script',
			'hlp'	=> 'application/x-helpfile',
			'hpg'	=> 'application/vnd.hp-hpgl',
			'hpgl'	=> 'application/vnd.hp-hpgl',
			'hqx'	=> 'application/binhex',
			'hta'	=> 'application/hta',
			'htc'	=> 'text/x-component',
			'htm'	=> 'text/html',
			'html'	=> 'text/html',
			'htmls'	=> 'text/html',
			'htt'	=> 'text/webviewhtml',
			'htx'	=> 'text/html',
			'ice'	=> 'x-conference/x-cooltalk',
			'ico'	=> 'image/x-icon',
			'idc'	=> 'text/plain',
			'ief'	=> 'image/ief',
			'iefs'	=> 'image/ief',
			'iges'	=> 'application/iges',
			'igs'	=> 'application/iges',
			'ima'	=> 'application/x-ima',
			'imap'	=> 'application/x-httpd-imap',
			'inf'	=> 'application/inf',
			'ins'	=> 'application/x-internett-signup',
			'ip'	=> 'application/x-ip2',
			'isu'	=> 'video/x-isvideo',
			'it'	=> 'audio/it',
			'iv'	=> 'application/x-inventor',
			'ivr'	=> 'i-world/i-vrml',
			'ivy'	=> 'application/x-livescreen',
			'jam'	=> 'audio/x-jam',
			'jav'	=> 'text/x-java-source',
			'java'	=> 'text/x-java-source',
			'jcm'	=> 'application/x-java-commerce',
			'jfif'	=> 'image/jpeg',
			'jfif-tbnl'	=> 'image/jpeg',
			'jpe'	=> 'image/jpeg',
			'jpeg'	=> 'image/jpeg',
			'jpg'	=> 'image/jpeg',
			'jps'	=> 'image/x-jps',
			'js'	=> 'application/javascript',
			'jut'	=> 'image/jutvision',
			'kar'	=> 'audio/midi',
			'ksh'	=> 'application/x-ksh',
			'la'	=> 'audio/nspaudio',
			'lam'	=> 'audio/x-liveaudio',
			'latex'	=> 'application/x-latex',
			'lha'	=> 'application/lha',
			'lhx'	=> 'application/octet-stream',
			'list'	=> 'text/plain',
			'lma'	=> 'audio/nspaudio',
			'log'	=> 'text/plain',
			'lsp'	=> 'application/x-lisp',
			'lst'	=> 'text/plain',
			'lsx'	=> 'text/x-la-asf',
			'ltx'	=> 'application/x-latex',
			'lzh'	=> 'application/octet-stream',
			'lzx'	=> 'application/lzx',
			'm'		=> 'text/plain',
			'm1v'	=> 'video/mpeg',
			'm2a'	=> 'audio/mpeg',
			'm2v'	=> 'video/mpeg',
			'm3u'	=> 'audio/x-mpequrl',
			'man'	=> 'application/x-troff-man',
			'map'	=> 'application/x-navimap',
			'mar'	=> 'text/plain',
			'mbd'	=> 'application/mbedlet',
			'mc$'	=> 'application/x-magic-cap-package-1.0',
			'mcd'	=> 'application/mcad',
			'mcf'	=> 'image/vasa',
			'mcp'	=> 'application/netmc',
			'me'	=> 'application/x-troff-me',
			'mht'	=> 'message/rfc822',
			'mhtml'	=> 'message/rfc822',
			'mid'	=> 'audio/midi',
			'midi'	=> 'audio/midi',
			'mif'	=> 'application/x-mif',
			'mime'	=> 'www/mime',
			'mjf'	=> 'audio/x-vnd.audioexplosion.mjuicemediafile',
			'mjpg'	=> 'video/x-motion-jpeg',
			'mm'	=> 'application/base64',
			'mme'	=> 'application/base64',
			'mod'	=> 'audio/mod',
			'moov'	=> 'video/quicktime',
			'mov'	=> 'video/quicktime',
			'movie'	=> 'video/x-sgi-movie',
			'mp2'	=> 'audio/mpeg',
			'mp3'	=> 'audio/mpeg3',
			'mpa'	=> 'audio/mpeg',
			'mpc'	=> 'application/x-project',
			'mpe'	=> 'video/mpeg',
			'mpeg'	=> 'video/mpeg',
			'mpg'	=> 'video/mpeg',
			'mpga'	=> 'audio/mpeg',
			'mpp'	=> 'application/vnd.ms-project',
			'mpt'	=> 'application/x-project',
			'mpv'	=> 'application/x-project',
			'mpx'	=> 'application/x-project',
			'mrc'	=> 'application/marc',
			'ms'	=> 'application/x-troff-ms',
			'mv'	=> 'video/x-sgi-movie',
			'my'	=> 'audio/make',
			'mzz'	=> 'application/x-vnd.audioexplosion.mzz',
			'nap'	=> 'image/naplps',
			'naplps'	=> 'image/naplps',
			'nc'	=> 'application/x-netcdf',
			'ncm'	=> 'application/vnd.nokia.configuration-message',
			'nif'	=> 'image/x-niff',
			'niff'	=> 'image/x-niff',
			'nix'	=> 'application/x-mix-transfer',
			'nsc'	=> 'application/x-conference',
			'nvd'	=> 'application/x-navidoc',
			'o'		=> 'application/octet-stream',
			'oda'	=> 'application/oda',
			'omc'	=> 'application/x-omc',
			'omcd'	=> 'application/x-omcdatamaker',
			'omcr'	=> 'application/x-omcregerator',
			'p'		=> 'text/x-pascal',
			'p10'	=> 'application/pkcs10',
			'p12'	=> 'application/pkcs-12',
			'p7a'	=> 'application/x-pkcs7-signature',
			'p7c'	=> 'application/pkcs7-mime',
			'p7m'	=> 'application/pkcs7-mime',
			'p7r'	=> 'application/x-pkcs7-certreqresp',
			'p7s'	=> 'application/pkcs7-signature',
			'part'	=> 'application/pro_eng',
			'pas'	=> 'text/pascal',
			'pbm'	=> 'image/x-portable-bitmap',
			'pcl'	=> 'application/vnd.hp-pcl',
			'pct'	=> 'image/x-pict',
			'pcx'	=> 'image/x-pcx',
			'pdb'	=> 'chemical/x-pdb',
			'pdf'	=> 'application/pdf',
			'pfunk'	=> 'audio/make',
			'pgm'	=> 'image/x-portable-graymap',
			'pic'	=> 'image/pict',
			'pict'	=> 'image/pict',
			'pkg'	=> 'application/x-newton-compatible-pkg',
			'pko'	=> 'application/vnd.ms-pki.pko',
			'pl'	=> 'text/plain',
			'plx'	=> 'application/x-pixclscript',
			'pm'	=> 'image/x-xpixmap',
			'pm4'	=> 'application/x-pagemaker',
			'pm5'	=> 'application/x-pagemaker',
			'png'	=> 'image/png',
			'pnm'	=> 'application/x-portable-anymap',
			'pot'	=> 'application/vnd.ms-powerpoint',
			'pov'	=> 'model/x-pov',
			'ppa'	=> 'application/vnd.ms-powerpoint',
			'ppm'	=> 'image/x-portable-pixmap',
			'pps'	=> 'application/vnd.ms-powerpoint',
			'ppt'	=> 'application/vnd.ms-powerpoint',
			'ppz'	=> 'application/mspowerpoint',
			'pre'	=> 'application/x-freelance',
			'prt'	=> 'application/pro_eng',
			'ps'	=> 'application/postscript',
			'psd'	=> 'application/octet-stream',
			'pvu'	=> 'paleovu/x-pv',
			'pwz'	=> 'application/vnd.ms-powerpoint',
			'py'	=> 'text/x-script.phyton',
			'pyc'	=> 'applicaiton/x-bytecode.python',
			'qcp'	=> 'audio/vnd.qcelp',
			'qd3'	=> 'x-world/x-3dmf',
			'qd3d'	=> 'x-world/x-3dmf',
			'qif'	=> 'image/x-quicktime',
			'qt'	=> 'video/quicktime',
			'qtc'	=> 'video/x-qtc',
			'qti'	=> 'image/x-quicktime',
			'qtif'	=> 'image/x-quicktime',
			'ra'	=> 'audio/x-realaudio',
			'ram'	=> 'audio/x-pn-realaudio',
			'ras'	=> 'application/x-cmu-raster',
			'rast'	=> 'image/cmu-raster',
			'rexx'	=> 'text/x-script.rexx',
			'rf'	=> 'image/vnd.rn-realflash',
			'rgb'	=> 'image/x-rgb',
			'rm'	=> 'application/vnd.rn-realmedia',
			'rmi'	=> 'audio/mid',
			'rmm'	=> 'audio/x-pn-realaudio',
			'rmp'	=> 'audio/x-pn-realaudio',
			'rng'	=> 'application/vnd.nokia.ringing-tone',
			'rnx'	=> 'application/vnd.rn-realplayer',
			'roff'	=> 'application/x-troff',
			'rp'	=> 'image/vnd.rn-realpix',
			'rpm'	=> 'audio/x-pn-realaudio-plugin',
			'rt'	=> 'text/vnd.rn-realtext',
			'rtf'	=> 'application/rtf',
			'rtx'	=> 'application/rtf',
			'rv'	=> 'video/vnd.rn-realvideo',
			's'		=> 'text/x-asm',
			's3m'	=> 'audio/s3m',
			'saveme'	=> 'application/octet-stream',
			'sbk'	=> 'application/x-tbook',
			'scm'	=> 'video/x-scm',
			'sdml'	=> 'text/plain',
			'sdp'	=> 'application/x-sdp',
			'sdr'	=> 'application/sounder',
			'sea'	=> 'application/x-sea',
			'set'	=> 'application/set',
			'sgm'	=> 'text/x-sgml',
			'sgml'	=> 'text/x-sgml',
			'sh'	=> 'application/x-sh',
			'shar'	=> 'application/x-shar',
			'shtml'	=> 'text/html',
			'sid'	=> 'audio/x-psid',
			'sit'	=> 'application/x-sit',
			'skd'	=> 'application/x-koan',
			'skm'	=> 'application/x-koan',
			'skp'	=> 'application/x-koan',
			'skt'	=> 'application/x-koan',
			'sl'	=> 'application/x-seelogo',
			'smi'	=> 'application/smil',
			'smil'	=> 'application/smil',
			'snd'	=> 'audio/basic',
			'snd'	=> 'audio/x-adpcm',
			'sol'	=> 'application/solids',
			'spc'	=> 'text/x-speech',
			'spl'	=> 'application/futuresplash',
			'spr'	=> 'application/x-sprite',
			'sprite'	=> 'application/x-sprite',
			'src'	=> 'application/x-wais-source',
			'ssi'	=> 'text/x-server-parsed-html',
			'ssm'	=> 'application/streamingmedia',
			'sst'	=> 'application/vnd.ms-pki.certstore',
			'step'	=> 'application/step',
			'stl'	=> 'application/vnd.ms-pki.stl',
			'stp'	=> 'application/step',
			'sv4cpio'	=> 'application/x-sv4cpio',
			'sv4crc'	=> 'application/x-sv4crc',
			'svf'	=> 'image/vnd.dwg',
			'svr'	=> 'application/x-world',
			'swf'	=> 'application/x-shockwave-flash',
			't'		=> 'application/x-troff',
			'talk'	=> 'text/x-speech',
			'tar'	=> 'application/x-tar',
			'tbk'	=> 'application/toolbook',
			'tbk'	=> 'application/x-tbook',
			'tcl'	=> 'application/x-tcl',
			'tcsh'	=> 'text/x-script.tcsh',
			'tex'	=> 'application/x-tex',
			'texi'	=> 'application/x-texinfo',
			'texinfo'	=> 'application/x-texinfo',
			'text'	=> 'text/plain',
			'tgz'	=> 'application/gnutar',
			'tgz'	=> 'application/x-compressed',
			'tif'	=> 'image/x-tiff',
			'tiff'	=> 'image/x-tiff',
			'tr'	=> 'application/x-troff',
			'tsi'	=> 'audio/tsp-audio',
			'tsp'	=> 'audio/tsplayer',
			'tsv'	=> 'text/tab-separated-values',
			'turbot'	=> 'image/florian',
			'txt'	=> 'text/plain',
			'uil'	=> 'text/x-uil',
			'uni'	=> 'text/uri-list',
			'unis'	=> 'text/uri-list',
			'unv'	=> 'application/i-deas',
			'uri'	=> 'text/uri-list',
			'uris'	=> 'text/uri-list',
			'ustar'	=> 'application/x-ustar',
			'uu'	=> 'application/octet-stream',
			'uue'	=> 'text/x-uuencode',
			'vcd'	=> 'application/x-cdlink',
			'vcs'	=> 'text/x-vcalendar',
			'vda'	=> 'application/vda',
			'vdo'	=> 'video/vdo',
			'vew'	=> 'application/groupwise',
			'viv'	=> 'video/vnd.vivo',
			'vivo'	=> 'video/vnd.vivo',
			'vmd'	=> 'application/vocaltec-media-desc',
			'vmf'	=> 'application/vocaltec-media-file',
			'voc'	=> 'audio/x-voc',
			'vos'	=> 'video/vosaic',
			'vox'	=> 'audio/voxware',
			'vqe'	=> 'audio/x-twinvq-plugin',
			'vqf'	=> 'audio/x-twinvq',
			'vql'	=> 'audio/x-twinvq-plugin',
			'vrml'	=> 'application/x-vrml',
			'vrt'	=> 'x-world/x-vrt',
			'vsd'	=> 'application/x-visio',
			'vst'	=> 'application/x-visio',
			'vsw'	=> 'application/x-visio',
			'w60'	=> 'application/wordperfect6.0',
			'w61'	=> 'application/wordperfect6.1',
			'w6w'	=> 'application/msword',
			'wav'	=> 'audio/x-wav',
			'wb1'	=> 'application/x-qpro',
			'wbmp'	=> 'image/vnd.wap.wbmp',
			'web'	=> 'application/vnd.xara',
			'wiz'	=> 'application/msword',
			'wk1'	=> 'application/x-123',
			'wmf'	=> 'windows/metafile',
			'wml'	=> 'text/vnd.wap.wml',
			'wmlc'	=> 'application/vnd.wap.wmlc',
			'wmls'	=> 'text/vnd.wap.wmlscript',
			'wmlsc'	=> 'application/vnd.wap.wmlscriptc',
			'word'	=> 'application/msword',
			'wp'	=> 'application/wordperfect',
			'wp5'	=> 'application/wordperfect',
			'wp6'	=> 'application/wordperfect',
			'wpd'	=> 'application/wordperfect',
			'wpd'	=> 'application/x-wpwin',
			'wq1'	=> 'application/x-lotus',
			'wri'	=> 'application/mswrite',
			'wrl'	=> 'application/x-world',
			'wrz'	=> 'model/vrml',
			'wsc'	=> 'text/scriplet',
			'wsrc'	=> 'application/x-wais-source',
			'wtk'	=> 'application/x-wintalk',
			'xbm'	=> 'image/x-xbm',
			'xdr'	=> 'video/x-amt-demorun',
			'xgz'	=> 'xgl/drawing',
			'xif'	=> 'image/vnd.xiff',
			'xl'	=> 'application/excel',
			'xla'	=> 'application/excel',
			'xlb'	=> 'application/excel',
			'xlc'	=> 'application/excel',
			'xld'	=> 'application/excel',
			'xlk'	=> 'application/excel',
			'xll'	=> 'application/excel',
			'xlm'	=> 'application/excel',
			'xls'	=> 'application/vnd.ms-excel',
			'xlt'	=> 'application/excel',
			'xlv'	=> 'application/excel',
			'xlw'	=> 'application/vnd.ms-excel',
			'xm'	=> 'audio/xm',
			'xml'	=> 'application/xml',
			'xmz'	=> 'xgl/movie',
			'xpix'	=> 'application/x-vnd.ls-xpix',
			'xpm'	=> 'image/xpm',
			'x-png'	=> 'image/png',
			'xsr'	=> 'video/x-amt-showrun',
			'xwd'	=> 'image/x-xwd',
			'xyz'	=> 'chemical/x-pdb',
			'z'		=> 'application/x-compressed',
			'zip'	=> 'multipart/x-zip',
			'zoo'	=> 'application/octet-stream',
			'zsh'	=> 'text/x-script.zsh',
			''		=> 'application/octet-stream'
		);
		return $mime[$type];
	}
	
	function date2string($date) {
		 if($date == '0000-00-00')
			  return '00-00-0000';
		$bln = array(
					1  => 'Januari',
							2  => 'Februari',
							3  => 'Maret',
							4  => 'April',
							5  => 'Mei',
							6  => 'Juni',
							7  => 'Juli',
							8  => 'Agustus',
							9  => 'September',
							10 => 'Oktober',
							11 => 'November',
							12 => 'Desember'
						);
		$arrtgl = explode('-',$date);
		if (sizeof($arrtgl)>2)
		  return $arrtgl[2].' '.$bln[(int) $arrtgl[1]].' '.$arrtgl[0];
		else
		  return $arrtgl[0];
	}
	
	function rupiah($money) {
		return number_format($money, 0, ',', '.');
	}
}
?>