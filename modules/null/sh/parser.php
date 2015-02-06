<?

if(file_exists("/../../../mpak.phar")){
	include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
	include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
	include "/srv/www/vhosts/mpak.cms/include/func.php";
	include __DIR__. "/../../../include/config.php";
	$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: dirname(dirname(dirname(dirname(__FILE__)))));
}else{
	include "phar://../../../mpak.phar/include/mpfunc.php";
	include mpopendir("include/mpfunc.php");
	include mpopendir("include/parse/simple_html_dom.php");
	include "../../../include/config.php";
} $html = new simple_html_dom();

$conf['db']['conn'] = mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']); # Соединение с базой данных

$arg['modpath'] = basename(dirname(dirname(__FILE__)));
$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));
chdir(dirname(__FILE__)); # Изменяем текущую директорию для запуска из крона

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
