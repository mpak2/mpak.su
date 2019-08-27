<?

// Подключаем консоль
if(!$conf = call_user_func(function($conf = []){ // Подключение компонентов
		if(array_key_exists('conf', $GLOBALS)){ $conf = $GLOBALS["conf"]; // mpre("Используем соединение основной системы");
		}elseif(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
		}elseif(!$offset = array_search($d = "биморф.рф", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
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
}elseif(!$command = call_user_func(function($command = [], $cmd, $name) use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ //mpre("Пропускам выполнение команды {$cmd}");
		}else{ phpinfo();
			return [];
		} return $command;
	}, [], $c = "phpinfo", $n = "Отображение настроек системы и загруженых модулей")){ pre($c. " ($n)");
}elseif(!$command = call_user_func(function($command, $cmd, $name, $stats = []) use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ mpre("Пропускам выполнение команды {$cmd}");
		}else if(!$DATA = [
				["dano"=>["Один"=>0, "Два"=>0, "Три"=>0, "Четыре"=>0], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>0, "Три"=>1, "Четыре"=>0], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>1, "Три"=>0, "Четыре"=>0], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>1, "Три"=>1, "Четыре"=>0], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>0, "Три"=>0, "Четыре"=>0], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>0, "Три"=>1, "Четыре"=>0], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>1, "Три"=>0, "Четыре"=>0], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>1, "Три"=>1, "Четыре"=>0], "itog"=>["Дуэт"=>0, "Трио"=>1]],
				["dano"=>["Один"=>0, "Два"=>0, "Три"=>0, "Четыре"=>1], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>0, "Три"=>1, "Четыре"=>1], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>1, "Три"=>0, "Четыре"=>1], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>0, "Два"=>1, "Три"=>1, "Четыре"=>1], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>0, "Три"=>0, "Четыре"=>1], "itog"=>["Дуэт"=>1, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>0, "Три"=>1, "Четыре"=>1], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>1, "Три"=>0, "Четыре"=>1], "itog"=>["Дуэт"=>0, "Трио"=>0]],
				["dano"=>["Один"=>1, "Два"=>1, "Три"=>1, "Четыре"=>1], "itog"=>["Дуэт"=>0, "Трио"=>1]],
			]){ mpre("ОШИБКА подклчения данных", $d);
		}else if(!$hash = md5("Осложнееный вариант скрипта")){ mpre("ОШИБКА задания секретной фаразы");
		}else if(!$href = "http://xn--90aomiky.xn--p1ai/bmf/{$hash}"){ mpre("ОШИБКА установки адреса апи");
		//}else if(!$href = "http://xn--90aomiky.xn--p1ai/users:server"){ mpre("ОШИБКА установки адреса апи");
		}else if(!$ITOG = array_map(function($data) use($href, &$stats){
				if(!$itog = get($data, 'itog')){ mpre("ОШИБКА получения итогового значения");
				//}else if(!$data = array_diff_key($data, array_flip(["itog"]))){ mpre("ОШИБКА исключения обучающих данных");
				}else if(!$query = http_build_query($data)){ mpre("ОШИБКА подготовки данных к запросу");
				}else if(!$stream_context = stream_context_create(array('http' => array('method'  => 'POST','header'  => ['Pragma: no-cache', 'Content-type: application/x-www-form-urlencoded'],'content' => $query)))){ mpre("ОШИБКА формирования параметров запроса"); //; 
				}else if(!$microtime = microtime(true)){ mpre("ОШИБКА засечения времени начала отправки запроса");
				}else if(!$result = file_get_contents($href, false, $stream_context)){ mpre("ОШИБКА отправки запроса", $href);
				}else if(!$mtime = microtime(true)-$microtime){ mpre("ОШИБКА получения времени исполнения запроса");
				}else if(!$response = json_decode($result, true)){ mpre("ОШИБКА интерпритации ответа сервера", $result);
				}else if(!$stat = ($itog == get($response, 'itog') ? "Успех" : "Обучение")){ mpre("ОШИБКА определения результата");
 				}else if(!$test = number_format($mtime, 3). " c ($stat)"){ mpre("ОШИБКА получения результатов теста");
 				}else if(!$stats[$stat] = (get($stats, $stat) ? $stats[$stat]+1 : 1)){ mpre("Считаем статистику", $stats, (get($stat, $itog) ? $stat[$itog]+1 : 1));
				}else{ mpre($href, $data, $response, $test);
					//mpre($href, $data, $response, $test);
				}
			}, $DATA)){ mpre("ОШИБКА отправки запроса на обучение {$href}");
		}else{ mpre($stats);
			return [];
		} return $command;
	}, $command, $c = "post", $n = "Тест апи биморф")){ pre($c. " ($n)");
}else{ mpre("Список доступных параметров", $command); }

