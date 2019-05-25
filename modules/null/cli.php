<?

// Подключаем консоль
if(!$conf = call_user_func(function($conf = []){ // Подключение компонентов
		if(array_key_exists('conf', $GLOBALS)){ $conf = $GLOBALS["conf"]; // mpre("Используем соединение основной системы");
		}elseif(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
		}elseif(!$offset = array_search($d = "bot.mpak.su", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
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
		}else{ mpre("Домашняя директория ". $folder);
		} return $conf;
	})){ mpre("ОШИБКА подключения компонентов");
}elseif(!$argv = call_user_func(function($argv = ""){
		if(array_key_exists('argv', $GLOBALS)){ $argv = $GLOBALS['argv'];
		}elseif(!$argv = array_merge([0=>basename(__FILE__)], (array)get($_GET, 'argv'))){ mpre("ОШИБКА получения аргументов адреса");
		}else{// mpre($argv);
		} return $argv;
	})){ mpre("ОШИБКА получения аргументов");
}elseif(!is_array($command = call_user_func(function($command = [], $cmd = "phpinfo", $name = "Отображение настроек системы и загруженых модулей") use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ //mpre("Пропускам выполнение команды {$cmd}");
		}else{ pre($name. " (". $cmd. ")");
			phpinfo();
		} return $command;
	}))){ mpre("ОШИБКА выполнения команды", $argv);
}elseif(!is_array($command = call_user_func(function($command, $cmd = "parse", $name = "Парсинг страниц") use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ //mpre("Пропускам выполнение команды {$cmd}");
		}else if(!include("phar://index.phar/include/class/simple_html_dom.php")){ mpre("ОШИБКА подключения класса simple_html_dom");
		}elseif(!$html = new simple_html_dom()){ mpre("Ошибка создания обьекта парсера");
		}elseif(!array_map(function($video_sources) use($html){
				if(!$href = get($video_sources, "href")){ mpre("ОШИБКА адрес страницы видео не задан");
				}elseif(!$data = file_get_contents($h = $href)){ mpre("ОШИБКА загрузки страницы {$h}");
				}elseif(!$html->load($data)){ mpre("ОШИБКА разбора дом обьектов странциы");
				}elseif(!$items = $html->find('h3')){ mpre("ОШИБКА выборки всех видео страницы <a href='{$href}'>{$href}</a>");
				}else{ mpre($items);
					mpre($video_sources);
				}
			}, $VIDEO_SOURCES)){ mpre("ОШИБКА парсинга страниц");
		}else{ pre($name. " (". $cmd. ")");
		} return $command;
	}, $command))){ mpre("ОШИБКА выполнения команды", $argv);
}elseif(!is_array($command = call_user_func(function($command, $cmd = "peer", $name = "Загрузка информации о каналах") use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ //mpre("Пропускам выполнение команды {$cmd}");
		}else if(!include($f = "include/madeline.php")){ mpre("ОШИБКА подключения библиотеки {$f}");
		}else if(!$api_info = ['api_id'=>827,'api_hash'=>"595"]){ mpre("ОШИБКА задания параметров api");
		}else if(!$MadelineProto = new \danog\MadelineProto\API(['app_info'=>$api_info, 'authorization'=>[ 'default_temp_auth_key_expires_in'=>86400]])){ mpre("ОШИБКА создание обьекта madeline");
		//}else if(!$MadelineProto->bot_login('818:AAGk')){ mpre("ОШИБКА авторизации ботом");
		}else if(!$MadelineProto->start()){ mpre("ОШИБКА подключения к api");
		}else if(!$TELEGRAM_PEER_NEW = rb("telegram-peer_new", "count", "id", array_flip(["NULL"]))){ mpre("ОШИБКА получения списка источников");
		}else if(!$TELEGRAM_PEER_NEW = array_map(function($telegram_peer_new) use($MadelineProto){ // Проверка свойств группы
				if(!$alias = get($telegram_peer_new, 'alias')){ mpre("Не указано peer значение", $telegram_peer_new);
				}else if(!$info = $MadelineProto->get_full_info($alias)){ mpre("ОШИБКА получения инфомрации о группе"); //https://docs.madelineproto.xyz/API_docs/types/InputChannel.html
				//}else if(!$peer = get($channels, "full", "participants_count")){ mpre("ОШИБКА определения направления");
				}else if(!$count = get($info, "full", "participants_count")){ mpre("ОШИБКА определения количества подписчиков");
				}else if(!$peer = get($info, "channel_id")){ mpre("ОШИБКА определения идентификатора");
				}else if(!$api = get($info, "bot_api_id")){ mpre("ОШИБКА определения идентификатора");
				}else if(!$type = get($info, "type")){ mpre("ОШИБКА определения идентификатора");
				}else if(!$name = get($info, "Chat", "title")){ mpre("ОШИБКА определния имени канала");
				}else if(!$telegram_peer_new = fk("telegram-peer_new", ["id"=>$telegram_peer_new["id"]], null, ["type"=>$type, "count"=>$count, "peer"=>$peer, "api"=>$api, "name"=>$name])){ mpre("ОШИБКА обновления группы");
				}else{ mpre("Группа", $telegram_peer_new);
					sleep(30);
				} return $telegram_peer_new;
			}, $TELEGRAM_PEER_NEW)){ mpre("ОШИБКА перебора списка групп");
		}else{ //mpre($TELEGRAM_PEER); //$me = $MadelineProto->get_self();
		} return $command;
	}, $command))){ pre($command. " (". $argv[1]. ")");
}else{ mpre("Список доступных параметров", $command); }

