<?

if(isset($_GET[''])) $_GET['q'] = $_GET[''];

if(isset($_GET['q'])){
	$keys = array_keys($ar = explode('.', $_GET['q']));
	$ext = $ar[max($keys)];
	$tn = "themes/".basename($_GET['theme'] ? $_GET['theme'] : $conf['settings']['theme']);
	$res_name = $tn ."/".strtr($_GET['q'], array('..'=>''));
	if(!($res = mpopendir($res_name))){
		if($conf['settings']['themes_file_not_exists_event']){
			if($themes_resources = $conf['settings']['themes_resources']){
				if(file_exists($dir = mpopendir($tn). "/". dirname($_GET['q'])) || mkdir($dir, 0777, true)){
					if(copy(($dst = "{$themes_resources}/{$_GET['q']}"), $fl = $tn. "/". $_GET['q'])){
						mpevent("Закачка файла из внешнего ресурса", $res_name);
						exit(header("Location: {$_GET['q']}"));
					}else{
						header("HTTP/1.0 404 Not Found");
						exit("Ошибка скачивания {$dst} в {$fl}");
					}
				}else{ mpre("Ошибка создания директории {$dir} в теме {$_GET['theme']}"); }
			}
		}
		exit(header("HTTP/1.0 404 Not Found"));
		mpevent("Файл темы не найден ошибка 404", $res_name);
	}else if($filectime = filectime($res)){
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filectime)){
			header('HTTP/1.0 304 Not Modified');
		}else{
			header("Cache-Control: public");
			header('Last-Modified: '. gmdate("r", $filectime));
			header('Expires: '.gmdate('r', time() + 86400*10));
//			mpre($filectime, gmdate("r", $filectime), time());
			$defaultmimes = array('otf'=>'font/opentype', 'cur'=>'application/octet-stream', 'ani'=>'application/x-navi-animation', 'aif' => 'audio/x-aiff', 'aiff' => 'audio/x-aiff', 'arc' => 'application/octet-stream', 'arj' => 'application/octet-stream', 'art' => 'image/x-jg', 'asf' => 'video/x-ms-asf', 'asx' => 'video/x-ms-asf', 'avi' => 'video/avi', 'bin' => 'application/octet-stream', 'bm' => 'image/bmp', 'bmp' => 'image/bmp', 'bz2' => 'application/x-bzip2', 'css' => 'text/css', 'doc' => 'application/msword', 'dot' => 'application/msword', 'dv' => 'video/x-dv', 'dvi' => 'application/x-dvi', 'eps' => 'application/postscript', 'exe' => 'application/octet-stream', 'gif' => 'image/gif', 'gz' => 'application/x-gzip', 'gzip' => 'application/x-gzip', 'htm' => 'text/html', 'html' => 'text/html', 'ico' => 'image/x-icon', 'jpe' => 'image/jpeg', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'js' => 'application/x-javascript', 'log' => 'text/plain', 'mid' => 'audio/x-midi', 'mov' => 'video/quicktime', 'mp2' => 'audio/mpeg', 'mp3' => 'audio/mpeg3', 'mpg' => 'audio/mpeg', 'pdf' => 'aplication/pdf', 'png' => 'image/png', 'rtf' => 'application/rtf', 'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'txt' => 'text/plain', 'xml' => 'text/xml', 'ttf'=>'application/x-font-ttf', 'woff'=>'application/x-font-woff', 'svg'=>'image/svg+xml',);
			header("Content-type: ".($defaultmimes[$ext] ? $defaultmimes[$ext] : "text/$ext"));
			if((array_key_exists("w", $_GET) || array_key_exists("h", $_GET)) && array_search($ext, array(1=>'jpg', 'png', 'gif'))){
				echo mprs($res, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
			}else{
				if($f = fopen($res, "rb")){
					while(!feof($f)) {
						echo fread($f, 256);
					}
				}
			}
		}
	}
}
