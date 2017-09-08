<?

/*// Подключаем консоль
if(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
}elseif(!$offset = array_search($d = "xn--90aomikyxn--p1ai", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
}elseif(!$folder = implode("/", array_slice($DIR, 0, $offset+1))){ print_r("Ошибка выборка директории");
}elseif(!$dir = implode("/", array_slice($DIR, $offset+1))){ print_r("Ошибка выборка директории");
}elseif(!chdir($folder)){ print_r("Ошибка установки директории `{$dir}`");
}elseif(!$inc = function($file) use(&$conf, $dir){
	if(($f = realpath($file)) && include($f)){ return $f;
	}elseif(($f = "phar://index.phar/{$file}") && file_exists($f) && include($f)){ return $f;
	}else{ return pre(null, "Директория не найдена `{$file}`"); }
}){ print_r("Функция подключения файлов");
}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
}elseif(!$inc($f = "include/mpfunc.php")){ print_r("Ошибка подключения `{$f}`");
}elseif(!$inc($f = "include/config.php")){ pre("Ошибка подключения `{$f}`");
}elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
}elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
}elseif(!isset($argv)){ pre("Запуск из веб интерфейса");
}elseif(!$conf['db']['conn'] = conn()){ mpre("Ошибка подключения БД");
}elseif(array_search($cmd["webhook"] = "Подключить вебхук", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));
	if(!$telegram_bot = rb("bim-telegram_bot", "name", $w = "[bimorphbot]")){ mpre("Значение по имени не найдено `{$w}`");
	}else{ mpre($telegram_bot);
	}
}else{ mpre($cmd);}
*/

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

