<?
$path = mpopendir("img/". last(explode("/", $_SERVER['REQUEST_URI'])));// exit(mpre($path));

if(get($_GET, 'w') && get($_GET, 'h') && file_exists($f = mpopendir("img/". basename($_GET[''])))){
	header("Content-type: ". ($ext = get($conf['defaultmimes'], last(explode(".", $f)))) ?: "image/png");
	echo mprs($f, get($_GET, 'w'), get($_GET, 'h'), get($_GET, 'c'));
}elseif(($ext = strtolower(last(explode('.', $path)))) && array_key_exists($ext, $conf['defaultmimes']) && file_exists($path)){
	if(!ob_get_length()){
		header("Content-type: ". (get($conf['defaultmimes'], $ext) ?: $conf['defaultmimes']['default']));
	} $f = fopen($path, "rb");
	while (!feof($f)) {
		echo fread($f, 256);
	} exit(0);
}else{
	header("HTTP/1.1 404 Not Found");
	exit("HTTP/1.1 404 Not Found");
}