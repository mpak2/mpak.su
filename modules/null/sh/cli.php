<?

// Подключаем консоль
if(call_user_func(function($conf = []){ // Подключение компонентов
		if(get($conf, 'db', 'conn')){// mpre("Используем соединение основной системы");
		}elseif(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
		}elseif(!$offset = array_search($d = "html", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
		}elseif(!$folder = implode("/", array_slice($DIR, 0, $offset+1))){ print_r("Ошибка выборка директории");
		}elseif(!$dir = implode("/", array_slice($DIR, $offset+1))){ print_r("Ошибка выборка директории");
		}elseif(!chdir($folder)){ print_r("Ошибка установки директории `{$dir}`");
		}elseif(!$inc = function($file) use(&$conf, $dir){
			if(($f = realpath($file)) && include($f)){ return $f;
			}elseif(($f = "phar://index.phar/{$file}") && file_exists($f) && include($f)){ return $f;
			}else{ return pre(null, "Директория не найдена `{$file}`"); }
		}){ print_r("Функция подключения файлов");
		}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
		}elseif(!$inc($f = "include/func.php")){ print_r("Ошибка подключения `{$f}`");
		}elseif(!$inc($f = "include/config.php")){ pre("Ошибка подключения `{$f}`");
		}elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
		}elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
		}elseif(!isset($argv)){ pre("Запуск из веб интерфейса");
		}elseif(!$conf['db']['conn'] = conn()){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
		}else{ mpre("Установлено соединение");
		}
	}, $conf)){ mpre("ОШИБКА подключения компонентов");
}elseif(!is_array($argv = (isset($argv) ? $argv : array_merge([0=>basename(__FILE__)], (array)get($_GET, 'argv'))))){ mpre("ОШИБКА получения аргументов адреса");
}elseif(array_search($cmd["grep"] = "Запуск с проверкой работы скрипта", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));
    if(!$file = get($argv, 0)){ mpre("ОШИБКА выборки имени сприпта");
    }elseif(!$cmd = "ps aux | grep {$file} | grep -v grep"){ mpre("ОШИКА получения комманды списка процессов");
    }elseif($list = `{$cmd}`){ mpre("В списке процессов имеется совпадающий по имени с текущим", "\n{$list}");
    }elseif(!$action = get($argv, 2)){ mpre("ОШИБКА выборки действия для запуска. Указывается в коммандной строке поледний аргументом");
    }elseif(!$cmd = "php ". __DIR__. "/{$file} {$action}"){ mpre("ОШИБКА составления запроса на запуск комманды");
    }else{// mpre("Параметры", __DIR__);
        passthru($cmd);
    }
}elseif(array_search($cmd["1e6"] = "Миллионники", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));
	if(!include("phar://index.phar/include/class/simple_html_dom.php")){ mpre("ОШИБКА подключения класса simple_html_dom");
	}elseif(!$html = new simple_html_dom()){ mpre("Ошибка создания обьекта парсера");
	}elseif(!$data = file_get_contents($h = "https://vk.com/catalog.php")){ mpre("ОШИБКА загрузки страницы {$h}");
	}elseif(!$html->load($data)){ mpre("ОШИБКА разбора дом обьектов странциы");
	}elseif(!$LINK = $html->find('nav.subpage_link a')){ mpre("ОШИБКА выборки всех ссылок регионов");
	}else{ mpre("Расчет окончен");
	}
# Запуск файлов
//}else if(!$cmd = "php -f modules/{$arg['modpath']}/sh/". basename($argv[0]). " bin {$vals}"){ mpre("Установка команды запуска");
//}else if(!mpre("Запуск обучения выходных знаков `{$cmd}`")){
//}else if(passthru($cmd)){ mpre("Выполнение команды");
}else{ mpre($cmd, $argv);}

/*
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
*/
