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
}elseif(!$command = call_user_func(function($cmd, $name, $command = []) use($argv){ // Связывание новой таблицы и списка источников
		if(array_key_exists($cmd, $command)){ mpre("Дублирование команды {$cmd}");
		}else if(array_search($command[$cmd] = $name, $command) != get($argv, 1)){ //mpre("Пропускам выполнение команды {$cmd}");
		}else{ phpinfo();
			return [];
		} return $command;
	}, $c = "phpinfo", $n = "Отображение настроек системы и загруженых модулей")){ pre($c. " ($n)");
}elseif(!call_user_func(function($name, $cmd, $command) use($argv){ // Связывание новой таблицы и списка источников
		if(array_search($name, $command) != get($argv, 1)){ return $command; //mpre("Пропускам выполнение команды {$cmd}");
		}else if(!$file_from = get($argv, 2)){ mpre("ОШИБКА получения файл источника csv");
		}else if(!is_string($fields = get($argv, 3) ?: "")){ mpre("ОШИБКА получения списка строк итога");
		}else if(!file_exists($file_from)){ mpre("Файл источник для конвертации не найден `{$file_from}`");
		}else if(!$if = fopen($file_from, "r")){ mpre("ОШИБКА открытия файла источника");
		}else if(!$titles = fgetcsv($if, 1000, ",")){ mpre("ОШИБКА получения заголовков полей");
		}else if(!$CSV = call_user_func(function($CSV = []) use($if, $titles){ while($data = fgetcsv($if, 1000, ",")){ // Построчная обработка данных входящего файла
				if(count($titles) != count($data)){ mpre("Количество параметров отлично от количества заголовоков");
				}else if(!$csv = array_combine($titles, $data)){ mpre("ОШИБКА совмещения заголовок во значениями");
				}else if(!$CSV[] = $csv){ mpre("ОШИБКА добалвения строки к результирующему списку");
				}else{ mpre("Строка csv добавляем к списку", $csv);
				}
			} return $CSV; })){ mpre("ОШИБКА получения списка файла csv");
		}else if(!$fields_split = explode(",", $fields)){ mpre("ОШИБКА разделения номеров полей итога");
		}else if(!$TITLES_ITOG = array_map(function($split)use($titles){ return array_slice($titles, $split, 1); }, $fields_split)){ mpre("ОШИБКА получения списка полей итога");
		}else if(!$titles_itog = array_flip(call_user_func_array("array_merge", $TITLES_ITOG))){ mpre("ОШИБКА получения списка полей в одноуровневом массиве");
		}else if(!$DATA = array_map(function($csv, $data = []) use($titles_itog){ // Формирование стркутуры данных
				if(!$itog = array_intersect_key($csv, $titles_itog)){ mpre("ОШИБКА получения итоговых параметров");
				}else if(!$dano = array_diff_key($csv, $titles_itog)){ mpre("ОШИБКА получения исходных данных");
				}else if(!$data = ["dano"=>$dano, "itog"=>$itog]){ mpre("ОШИБКА получения строки данных");
				}else{ //mpre("Данные", $dano, $itog);
				} return $data;
			}, $CSV)){ mpre("ОШИБКА получения структуры данных");
		}else if(!$JSON = array_map(function($data){ return json_encode($data, 256); }, $DATA)){ mpre("Формирование json");
		}else if(!$data = "[\n\t". implode(",\n\t", $JSON). "\n]"){ mpre("ОШИБКА получения строки данных");
		}else if(!$file_to = implode(".", array_slice(explode(".", $file_from), 0, -1)). ".json"){ mpre("ОШИБКА получения имени выходного файла");
		}else if(!file_put_contents($file_to, $data)){ mpre("ОШИБКА записи в файл `{$file_to}`");
		}else{ mpre("Конвертация `{$file_to}`");
		}
	}, $command[$cmd = "csv2json"] = $name = "Корвертирование csv2json", $cmd, $command)){ pre($cmd. " ($name)");
}elseif(!$command = call_user_func(function($name, $cmd, $command, $stats = []) use($argv){ // Связывание новой таблицы и списка источников
		if(array_search($name, $command) != get($argv, 1)){ mpre("Пропускам выполнение команды {$cmd}");
		}else if(!$DATA = [
				array("dano"=>array("Один"=>0, "Два"=>0, "Три"=>0, "Четыре"=>0), "itog"=>array("Дуэт"=>0, "Трио"=>0)),
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
		}else if(!$hash = md5("Новая сеть охренная обалденная")){ mpre("ОШИБКА задания секретной фаразы");
		}else if(!$href = "http://xn--90aomiky.xn--p1ai/bmf/{$hash}"){ mpre("ОШИБКА установки адреса апи");
		}else if(!$ITOG = array_map(function($data) use($href, &$stats){
				if(!$itog = get($data, 'itog')){ mpre("ОШИБКА получения итогового значения");
				}else if(!$query = http_build_query($data)){ mpre("ОШИБКА подготовки данных к запросу");
				}else if(!$stream_context = stream_context_create(array('http' => array('method'  => 'POST','header'  => ['Pragma: no-cache', 'Content-type: application/x-www-form-urlencoded'],'content'=>$query)))){ mpre("ОШИБКА формирования параметров запроса"); //; 
				}else if(!$microtime = microtime(true)){ mpre("ОШИБКА засечения времени начала отправки запроса");
				}else if(!$result = file_get_contents($href, false, $stream_context)){ mpre("ОШИБКА отправки запроса", $href);
				}else if(!$mtime = microtime(true)-$microtime){ mpre("ОШИБКА получения времени исполнения запроса");
				}else if(!$response = json_decode($result, true)){ mpre("ОШИБКА интерпритации ответа сервера", $result);
				}else if(!$stat = (get($response, 'stats', 'change') ? "Обучение" : "Успех")){ mpre("ОШИБКА определения результата");
 				}else if(!$test = number_format($mtime, 3). " c ($stat)"){ mpre("ОШИБКА получения результатов теста");
 				}else if(!$stats[$stat] = (get($stats, $stat) ? $stats[$stat]+1 : 1)){ mpre("Считаем статистику", $stats, (get($stat, $itog) ? $stat[$itog]+1 : 1));
				}else{ mpre($href, $data, $response, $test);
					//mpre($href, $data, $response, $test);
				}
			}, $DATA)){ mpre("ОШИБКА отправки запроса на обучение {$href}");
		}else{ mpre($stats);
		} return $command;
	}, $command, $c = "post", $n = "Тест апи биморф")){ pre($c. " ($n)");
}else{ mpre("Список доступных параметров", $command); }

