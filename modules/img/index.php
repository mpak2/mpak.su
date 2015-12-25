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


$keys = array_keys($ar = explode("/", $_SERVER['REQUEST_URI']));
$path = mpopendir("img/". $ar[max($keys)]);

$keys = array_keys($ar = explode('.', $path));
if(($ext = strtolower($ar[max($keys)])) && array_key_exists($ext, $defaultmimes) && file_exists($path)){
	if(!ob_get_length()){
		header("Content-type: {$defaultmimes[$ext]}");
	} $f = fopen($path, "rb");
	while (!feof($f)) {
		echo fread($f, 256);
	} exit();
}elseif(file_exists($f = "img/". basename($_GET['']))){
	header("Content-type: {$defaultmimes[$ext]}");
	echo mprs($f, $_GET['w'], $_GET['h'], $_GET['c']);
}else{
	header("HTTP/1.1 404 Not Found");
	echo "HTTP/1.1 404 Not Found";
}
