<?

include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php"; $html = new simple_html_dom();
include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
include "/srv/www/vhosts/mpak.cms/include/func.php";
include "/srv/www/vhosts/mpak.cms/config/config.php";

mpre($conf['fs']['path']); exit;

include __DIR__. "/../../../config/config.php";

$conf['db']['conn'] = mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']); # Соединение с базой данных
if (strlen($conf['db']['error'] = mysql_error())){
}else{
	mysql_select_db($conf['db']['name'], $conf['db']['conn']);
	mpqw("SET NAMES 'utf8'");
} unset($conf['db']['pass']); $conf['db']['sql'] = array();

function cf($href, $post = null, $referer = null){
	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_PROXY, "1.2.3.4:123"); //если нужен прокси
	curl_setopt ($ch , CURLOPT_FOLLOWLOCATION , 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");//Из какого файла читать
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt"); //В какой файл записывать

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $href); //куда шлем
	if($post) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	if ($referer) curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; .NET CLR 1.1.4322)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	$result=curl_exec ($ch);
	curl_close ($ch);
	return $result;
}

?>