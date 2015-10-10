<?

function tmpopendir($file_name, $merge=1){
	global $conf;
	$prefix = $merge ? explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		$file = strtr("$v/$file_name", array('/modules/..'=>''));
		if (file_exists($file)){
//			echo "\n==>{$k}<==>{$v}<==>{$file}<==\n";
			return $file; break;
		}
	}
}

if($argv){
	if(file_exists(__DIR__. "/../../../index.phar")){
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: "phar:/". dirname(dirname(dirname(dirname(__FILE__)))). "/index.phar:". dirname(dirname(dirname(dirname(__FILE__)))));
		if($mpfunc = tmpopendir("include/mpfunc.php")){ include $mpfunc; }else{
			include "phar://../../../index.phar/include/mpfunc.php";
		} include "../../../include/config.php";
	}else{
		include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
		include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
		include "/srv/www/vhosts/mpak.cms/include/func.php";
		include __DIR__. "/../../../include/config.php";
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: dirname(dirname(dirname(dirname(__FILE__)))));
	}// pre($conf["db"]["open_basedir"]);

	$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
	$arg['modpath'] = basename(dirname(dirname(__FILE__)));
	$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));

	include "phar://../../../index.phar/include/parse/simple_html_dom.php";
	$html = new simple_html_dom();
} chdir(__DIR__); # Изменяем текущую директорию для запуска из крона

if (strlen($conf['db']['error'] = mysql_error())){
#		echo "Ошибка соединения с базой данных<p>";
}else{
	mysql_select_db($conf['db']['name'], $conf['db']['conn']);
	mpqw("SET NAMES 'utf8'");
} unset($conf['db']['pass']); $conf['db']['sql'] = array();

###################################################################################################################################################

$html->load($data = mpde(mpcurl("http://ya.ru")));

foreach($html->find("#id") as $e){
	echo "\n". $html = $e->outertext;

/*	do{ # Поиск следующего элемента
		mpre($e)
		$v['href'] = false;
		if($p = $html->find("#pages p>em", 0)){
			if($p = $p->parent()->next_sibling()){
				$v['href'] = $p->find("a", 0)->href;
			}
		}
	} while($v['href']);*/
}
