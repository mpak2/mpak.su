<?

/*// Подключаем консоль
if(!empty($conf)){// mpre("Жираф уже подключен");
}elseif(file_exists($INCLUDE[] = "modules/sqlanaliz/sh/cli.php")){ print_r("Подключаем", $include = array_pop($INCLUDE)); include $include;
}elseif(file_exists($INCLUDE[] = "modules/null/sh/cli.php")){ print_r("Подключаем", $include = array_pop($INCLUDE)); include $include;
}elseif(file_exists($INCLUDE[] = "../../../modules/null/sh/cli.php")){ print_r("Подключаем", $include = array_pop($INCLUDE)); include $include;
}elseif(file_exists($INCLUDE[] = "phar://../../../index.phar/modules/null/sh/cli.php")){ print_r("Подключаем", $include = array_pop($INCLUDE)); include $include;
}elseif(!$dir = opendir($folder = ".")){ print_r("Ошибка открытия текущей директории");
}elseif(!$DIR = call_user_func(function($DIR = []) use($dir){ while($file = readdir($dir)){ $DIR[] = $file; } return $DIR; })){ print_r("Ошибка чтения текущей директории");
}else{
	print_r("Ошибка подключения файлов\n");
	echo "<pre>"; print_r($INCLUDE); echo "</pre>";
	print_r("Список файлов директории `{$folder}`\n");
	echo "<pre>"; print_r($DIR); echo "</pre>";
} */

if(!empty($conf)){ mpre("Жираф уже подключен");
//	include "include/mpfunc.php";
}else{
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

	if(empty($argv)){ print_r("Запускаем не из консоли");
	}elseif(!chdir("/var/www/html/")){ print_r("Ошибка установки корневой директории");
	}else{
		if(file_exists("index.phar")){
			$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: "phar:/". dirname(dirname(dirname(dirname(__FILE__)))). "/index.phar:". dirname(dirname(dirname(dirname(__FILE__)))));
			if($mpfunc = tmpopendir("include/mpfunc.php")){ include $mpfunc; }else{
				include "phar://index.phar/include/mpfunc.php";
			} include "phar://index.phar/include/config.php";
		}else{ //exit(phpinfo());
			include "include/mpfunc.php";
			include "include/parse/simple_html_dom.php";
			include "include/func.php";
			include "include/config.php";
			$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: dirname(dirname(dirname(dirname(__FILE__)))));
		}// pre($conf["db"]["open_basedir"]);

		$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
		$arg['modpath'] = basename(dirname(dirname(__FILE__)));
		$conf['fs']['path'] = dirname(dirname(dirname(dirname(__FILE__))));

		if($simple_html_dom = mpopendir("include/class/simple_html_dom.php")){
			include $simple_html_dom;
			$html = new simple_html_dom();
		}
	} $conf['user']['gid'] = array(1=>"Администратор");

	unset($conf['db']['pass']); $conf['db']['sql'] = array();
} ###################################################################################################################################################

/*if(empty($argv)){// mpre("Запуск из веб интерфейса");
}elseif(!array_search($cmd["deed"] = "Отобразить список сигналов", $cmd) == get($argv, 1)){ mpre($cmd);
}elseif(!$html->load($data = mpde(mpcurl($w = "http://ru.aliexpress.com/")))){ mpre("Ошибка открытия страницы ", $w);
}else{
	foreach($html->find(".categories-list-box > dl") as $dl){
		echo "\n". $html = $dl->outertext;
	}
}*/
