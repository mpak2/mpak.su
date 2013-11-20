<?

include "/srv/www/vhosts/mpak.cms/include/parse/simple_html_dom.php";
include "/srv/www/vhosts/mpak.cms/include/mpfunc.php";
include "/srv/www/vhosts/mpak.cms/include/func.php";

include __DIR__. "/../../../config/config.php";
$html = new simple_html_dom();
$html2 = new simple_html_dom();

$conf['db']['conn'] = mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']); # Соединение с базой данных

$arg['modpath'] = basename(dirname(dirname(__FILE__)));
$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));

if (strlen($conf['db']['error'] = mysql_error())){
#		echo "Ошибка соединения с базой данных<p>";
}else{
	mysql_select_db($conf['db']['name'], $conf['db']['conn']);
	mpqw("SET NAMES 'utf8'");
} unset($conf['db']['pass']); $conf['db']['sql'] = array();

###################################################################################################################################################

$html->load(mpde($data = mpcurl("http://ya.ru")));

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
