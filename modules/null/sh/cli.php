<?

// Подключаем консоль
if(!$conf = call_user_func(function($conf = []){ // Подключение компонентов
		if(array_key_exists('conf', $GLOBALS)){ $conf = $GLOBALS["conf"]; // mpre("Используем соединение основной системы");
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
		}elseif(!$short_open_tag = ini_get("short_open_tag")){ pre("Установите параметр `short_open_tag`\nsed -i \"s/short_open_tag = .*/short_open_tag = On/\" /etc/php/7.0/cli/php.ini");
		}elseif(!$inc($f = "include/config.php")){ pre("Ошибка подключения `{$f}`");
		}elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
		}elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
		}elseif(isset($argv)){ pre("Запуск из веб интерфейса");
		}elseif(!$GLOBALS["conf"] = $conf){ mpre("ОШИБКА сохранеения данных в глобальной переменной");
		}elseif(!$conf['db']['conn'] = conn()){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
		}else{ mpre("Установлено соединение");
		} return $conf;
	})){ mpre("ОШИБКА подключения компонентов");
}elseif(!$argv = call_user_func(function($argv = ""){
		if(array_key_exists('argv', $GLOBALS)){ $argv = $GLOBALS['argv'];
		}elseif(!$argv = array_merge([0=>basename(__FILE__)], (array)get($_GET, 'argv'))){ mpre("ОШИБКА получения аргументов адреса");
		}else{// mpre($argv);
		} return $argv;
	})){ mpre("ОШИБКА получения аргументов");
}elseif(array_search($cmd["phpinfo"] = "Информация php", $cmd) == get($argv, 1)){ phpinfo();
}elseif(array_search($cmd["parse"] = "Парсинг", $cmd) == get($argv, 1)){// pre("Метод", get($argv, 1));
	if(!include("phar://index.phar/include/class/simple_html_dom.php")){ mpre("ОШИБКА подключения класса simple_html_dom");
	}elseif(!$html = new simple_html_dom()){ mpre("Ошибка создания обьекта парсера");
	}elseif(!$VIDEO_SOURCES = rb("video-sources")){ mpre("ОШИБКА получения адресов видеостраниц");
	}elseif(!array_map(function($video_sources) use($html){
			if(!$href = get($video_sources, "href")){ mpre("ОШИБКА адрес страницы видео не задан");
			}elseif(!$data = file_get_contents($h = $href)){ mpre("ОШИБКА загрузки страницы {$h}");
			}elseif(!$html->load($data)){ mpre("ОШИБКА разбора дом обьектов странциы");
			}elseif(!$items = $html->find('h3')){ mpre("ОШИБКА выборки всех видео страницы <a href='{$href}'>{$href}</a>");
			}else{ mpre($items);
				mpre($video_sources);
			}
		}, $VIDEO_SOURCES)){ mpre("ОШИБКА парсинга страниц");
	}else{ mpre("Расчет окончен", $VIDEO_SOURCES);
	}
}else{ mpre($argv, $cmd); }

