<?

/*// Подключаем консоль
if(empty($conf) && !($include = call_user_func(function(){
		if(file_exists($INCLUDE[] = "modules/sqlanaliz/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "../../../modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "phar://../../../index.phar/modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(file_exists($INCLUDE[] = "phar://../../../mpak.cms/phar/index.phar/modules/null/sh/cli.php") && !print_r("Подключаем", include ($include = array_pop($INCLUDE)))){ print_r("Ошибка подключения {$include}");
		}elseif(empty($include) && !call_user_func(function(){
				if(!$dir = opendir($folder = ".")){ print_r("Ошибка открытия текущей директории");
				}elseif(!$DIR = call_user_func(function($DIR = []) use($dir){ while($file = readdir($dir)){ $DIR[] = $file; } return $DIR; })){ print_r("Ошибка чтения текущей директории");
				}else{ print_r("Ошибка подключения файлов\n"); echo "<pre>"; print_r($INCLUDE); echo "</pre>"; print_r("Библиотека консоли не найдена `{$folder}`\n"); echo "<pre>"; print_r($DIR); echo "</pre>"; }
			})){ print_r("\n\tОшибка отображения списка файлов текущей директории");
		}else{ return $include; }
	}))){ print_r("\n\tОшибка подключения консоли");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
}elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
}elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
}elseif(!isset($argv)){ pre("Запуск из веб интерфейса");
}elseif(!chdir(dirname(dirname(__DIR__)))){
}elseif(file_exists($config = "../include/config.php") && !include($config)){ mpre("Ошибка подключения параметров БД");
}elseif(!chdir(__DIR__)){ mpre("Ошибка установки текущей директории");
}elseif(!$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC))){ mpre("Ошибка подключения БД");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
}elseif(array_search($cmd["deed"] = "Отобразить список сигналов", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));
	if(!$html->load($data = mpde(mpcurl($w = "http://ru.aliexpress.com/")))){ pre("Ошибка открытия страницы ", $w);
	}else{ mpre($data); }
}else{ mpre($cmd);} */

if(!empty($conf)){ mpre("Жираф уже подключен");
}elseif(isset($argv)){ print_r("Запускаем не из консоли", $argv);
}elseif(!chdir($dir = "/var/www/html")){ print_r("Ошибка установки корневой директории");
}elseif(!$phar = "phar://{$dir}/index.phar"){ print_r("Установка пути до фара");
}elseif(!include("{$phar}/include/mpfunc.php")){ mpre("Библиотека функций");
}elseif(!include("{$phar}/include/config.php")){ mpre("Конфиг");
}elseif(file_exists($config = "{$dir}/include/config.php") && !include($config)){ mpre("Параметры доступа к БД");
}else{// define ('INCLUDE_PATH', 'phar:///var/www/html/index.phar');

//	$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
//	$arg['modpath'] = basename(dirname(dirname(__FILE__)));
//	$conf['fs']['path'] = $dirname;

//	unset($conf['db']['pass']); $conf['db']['sql'] = array();
}

