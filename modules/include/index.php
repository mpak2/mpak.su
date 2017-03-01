<?

$defaultmimes = array(
	'aif' => 'audio/x-aiff',
	'aiff' => 'audio/x-aiff',
	'arc' => 'application/octet-stream',
	'arj' => 'application/octet-stream',
	'art' => 'image/x-jg',
	'asf' => 'video/x-ms-asf',
	'asx' => 'video/x-ms-asf',
	'avi' => 'video/avi',
	'bin' => 'application/octet-stream',
	'bm' => 'image/bmp',
	'bmp' => 'image/bmp',
	'bz2' => 'application/x-bzip2',
	'css' => 'text/css',
	'doc' => 'application/msword',
	'dot' => 'application/msword',
	'dv' => 'video/x-dv',
	'dvi' => 'application/x-dvi',
	'eps' => 'application/postscript',
	'exe' => 'application/octet-stream',
	'gif' => 'image/gif',
	'gz' => 'application/x-gzip',
	'gzip' => 'application/x-gzip',
	'htm' => 'text/html',
	'html' => 'text/html',
	'ico' => 'image/x-icon',
	'jpe' => 'image/jpeg',
	'jpg' => 'image/jpeg',
	'jpeg' => 'image/jpeg',
	'js' => 'application/x-javascript',
	'log' => 'text/plain',
	'mid' => 'audio/x-midi',
	'mov' => 'video/quicktime',
	'mp2' => 'audio/mpeg',
	'mp3' => 'audio/mpeg3',
	'mpg' => 'audio/mpeg',
	'pdf' => 'aplication/pdf',
	'png' => 'image/png',
	'rtf' => 'application/rtf',
	'tif' => 'image/tiff',
	'tiff' => 'image/tiff',
	'txt' => 'text/plain',
	'xml' => 'text/xml',
);

if(!$uri = implode("/", $ar = array_filter(explode("/", $_SERVER['REQUEST_URI'])))){ mpre("Путь до файла не найден");
}elseif(!$uri = first(explode("?", $uri))){ mpre("Путь отделения динамической части адреса");
}elseif(!$path = mpopendir($uri)){ pre("Ошибка определения пути к файлу <b>{$uri}</b>");
	header("HTTP/1.1 404 Not Found");
}elseif(!$keys = array_keys($ar = explode('.', $path))){ mpre("Ошибка составления массива элементов пути");
}elseif(!$ext = strtolower($ar[max($keys)])){ mpre("Ошибка поиска расширения файла");
}elseif(!array_key_exists($ext, $defaultmimes)){ mpre("Тип файла не определен");
}elseif(!file_exists($path)){ mpre("Файл не найден");
	header("HTTP/1.1 404 Not Found");
}else{ # Вывод содержимого файла
	if(!ob_get_length()){
		header("Content-type: {$defaultmimes[$ext]}");
	}

	$f = fopen($path, "rb");
	while (!feof($f)) {
		echo fread($f, 256);
	}
}
