<?

if(!is_array($ARGV =call_user_func(function($ARGV =[]){ # Входящие параметры из консоли
	if(!array_key_exists("argv" ,$GLOBALS)){ //mpre("Запуск не из консоли");
   }else if(!$argv =$GLOBALS["argv"]){ //mpre("Аргументы не указаны");
   }else if(!array_map(function($nn ,$arg)use(&$ARGV){ # Значения аргументов
		if(!$nn){ //print_r("Кроме нулевого элемента");
      }else if(is_null($pos =strpos($arg ,"="))){ mpre("Равенство не найдено в аргументе {$arg}");
      }else if(!is_string($key =is_numeric($pos) ?substr($arg ,0 ,$pos) :$arg)){ mpre("ОШИБКА Расчет ключа");
      }else if(!is_string($val =is_numeric($pos) ?substr($arg ,$pos +1) :"")){ mpre("ОШИБКА Расчет ключа");
      }else if(!is_string(is_numeric($pos) || (0 ===strpos($key ,"-")) ?($ARGV[$key] =$val) :($ARGV[""] =$key))){ mpre("Добавление аргумента к массиву");
      }else{ //print_r("Равенство в аргументе {$arg} позиция {$pos} nn:{$nn} pos={$pos} key={$key} val={$val}\n");
      }return $arg; } ,array_keys($argv) ,$argv)){ mpre("ОШИБКА получения значений аргументов");
   }else{ //mpre("Аргументы консоли" ,$argv ,$ARGV);
   }return $ARGV; }))){ mpre("ОШИБКА получения входящих консольных параметров");
//}else if(print_r($ARGV)){ 
/*}else if(!$conf = call_user_func(function($_conf = [])use($ARGV){ // Подключение компонентов
   if(array_key_exists('conf', $GLOBALS)){ $_conf = $GLOBALS["conf"]; // mpre("Используем соединение основной системы");
   }elseif(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
   }elseif(!$offset = array_search($d = "f188f47e-89d3-4f09-bc2b-560d9f2edbf4", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
   }elseif(!$folder = implode("/", array_slice($DIR, 0, $offset+1))){ print_r("Ошибка выборка директории");
   }elseif(!$dir = implode("/", array_slice($DIR, $offset+1))){ print_r("Ошибка выборка директории");
   }elseif(!chdir($folder)){ print_r("Ошибка установки директории `{$dir}`");
   }elseif(!$inc = function($file ,$to_phar =0) use(&$conf, $dir){
      if(!$to_phar &&($f = realpath($file)) && include($f)){ return $f;
      }elseif(($f = "phar://index.phar/{$file}") && file_exists($f) && include($f)){ return $f;
      }else{ return pre(null, "Директория не найдена `{$file}`"); }
   }){ print_r("Функция подключения файлов");
   }elseif(!$inc($f = "include/func.php")){ print_r("Ошибка подключения `{$f}`");
   }elseif(!$short_open_tag = ini_get("short_open_tag")){ pre("Установите параметр `short_open_tag`\nsed -i \"s/short_open_tag = .short_open_tag = On/\" /etc/php/7.0/cli/php.ini");
	//}else if(!mpre("Конфигурация" ,$conf)){ mpre("Уведомление");
   }elseif(!$inc($f = "include/config.php" ,1)){ pre("Ошибка подключения `{$f}`");
   }elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
   }elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
   }elseif(isset($argv)){ pre("Запуск из веб интерфейса");
	}else if(!$db =$ARGV[""]){ mpre("ОШИБКА имя БД не задано");
	}else if(!$_conf =$conf){ mpre("ОШИБКА получения локальной конфигурации");
   }elseif(!$_conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
   }elseif(!$GLOBALS["conf"] = $_conf){ mpre("ОШИБКА сохранеения данных в глобальной переменной");
   }elseif(!$_conf['db']['name'] ="{$modpath}/{$db}"){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
	}else if(!touch($_conf['db']['name'])){ mpre("ОШИБКА создания файла БД");
   }elseif(!is_string($_conf['db']['prefix'] ="")){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
	}else if(!$GLOBALS["conf"] =$_conf){ mpre("ОШИБКА устаноки глобальной переменной");
   }elseif(!$_conf['db']['conn'] = conn()){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
	//}else if(!mpre("Конфигурация {$modpath}" ,$conf)){ mpre("Уведомление");
   }else{ //mpre("Добашняя директория". $folder);
   }return $_conf; })){ print_r("ОШИБКА подключения к БД");*/
}else if(!$conf = call_user_func(function($_conf = [])use($ARGV){ // Подключение компонентов
   if(array_key_exists('conf', $GLOBALS)){ $_conf = $GLOBALS["conf"]; // mpre("Используем соединение основной системы");
	}else if(!$_dir =getcwd()){ mpre("Уведомление");
	//}else if(!$_dir =__DIR__){ mpre("ОШИБКА сохранения текущей директории");
	//}else if(!$conf["db"]["open_basedir"] = $_dir){ mpre("ОШИБКА установки текущей директории");
   }elseif(!$DIR = explode("/", __DIR__)){ print_r("Ошибка определения текущей директории");
   }elseif(!$offset = array_search($d = "git", $DIR)){ print_r("Директория с проектом не найдена `{$d}`"); print_r($DIR);
   }elseif(!$folder = implode("/", array_slice($DIR, 0, $offset+1))){ print_r("Ошибка выборка директории");
   }elseif(!$dir = implode("/", array_slice($DIR, $offset+1))){ print_r("Ошибка выборка директории");
   }elseif(!chdir($folder)){ print_r("Ошибка установки директории `{$dir}`");
   }elseif(!$inc = function($file ,$to_phar =0) use(&$conf, $dir){
      if(!$to_phar &&($f = realpath($file)) && include($f)){ return $f;
      }elseif(($f = "phar://index.phar/{$file}") && file_exists($f) && include($f)){ return $f;
      }else{ return pre(null, "Директория не найдена `{$file}`"); }
   }){ print_r("Функция подключения файлов");
   }elseif(!$inc($f = "include/func.php")){ print_r("Ошибка подключения `{$f}`");
   }elseif(!$short_open_tag = ini_get("short_open_tag")){ pre("Установите параметр `short_open_tag`\nsed -i \"s/short_open_tag = .*/short_open_tag = On/\" /etc/php/7.0/cli/php.ini");
   }elseif(!$inc($f = "include/config.php" ,1)){ pre("Ошибка подключения `{$f}`");
   }elseif(!$modpath = (isset($argv) ? basename(dirname(dirname(__FILE__))) : basename(dirname(__FILE__)))){ mpre("Ошибка вычисления имени модуля");
   }elseif(!$arg = ['modpath'=>$modpath, "fn"=>implode(".", array_slice(explode(".", basename(__FILE__)), 0, -1))]){ mpre("Установка аргументов");
   }elseif(isset($argv)){ pre("Запуск из веб интерфейса");
	}else if(!$db =$ARGV[""]){ mpre("ОШИБКА имя БД не задано");
	}else if(!$_conf =$conf){ mpre("ОШИБКА получения локальной конфигурации");
   }elseif(!$_conf['user']['gid'] = array(1=>"Администратор")){ mpre("Устанавливаем администратора");
   }elseif(!$GLOBALS["conf"] = $_conf){ mpre("ОШИБКА сохранеения данных в глобальной переменной");
   }elseif(!$_conf['db']['name'] =$db){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
	}else if(!touch($_conf['db']['name'])){ mpre("ОШИБКА создания файла БД");
	//}else if(!mpre("Директория" ,realpath($_conf['db']['name']))){ mpre("Уведомление");
	//}else if(!mpre("Создание файла" ,$_conf['db']['name'] ,$conf)){ mpre("Уведомление");
   }elseif(!is_string($_conf['db']['prefix'] ="")){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
	}else if(!$GLOBALS["conf"] =$_conf){ mpre("ОШИБКА устаноки глобальной переменной");
   }elseif(!$_conf['db']['conn'] = conn()){ mpre("Ошибка подключения БД попробуйте установить `apt install php-sqlite3`");
   }else{ //mpre("Добашняя директория". $folder);
   }return $_conf; })){ print_r("ОШИБКА подключения к БД");
}else if(!$microtime =microtime(true)){ mpre("ОШИБКА установки времени");
}else if(call_user_func(function()use($ARGV ,$conf){ # Загрузка данных
	if(call_user_func(function()use($ARGV){ # Обнуление базы данных
		if(!array_key_exists("--drop" ,$ARGV)){ //mpre("Не обнуляем базу данных");
		}else if(!mpre("Обнуляем базу данных")){ mpre("Уведомление");
		}else if(!qw(mpre("DROP TABLE IF EXISTS data;"))){ mpre("ОШИБКА удаления базы данных");
		}else if(!qw(mpre("DROP TABLE IF EXISTS value;"))){ mpre("ОШИБКА удаления базы данных");
		}else if(!qw(mpre("DROP TABLE IF EXISTS bit;"))){ mpre("ОШИБКА удаления базы данных");
		}else if(!qw(mpre("DROP TABLE IF EXISTS link;"))){ mpre("ОШИБКА создания таблицы данных");
		}else if(!qw(mpre("DROP TABLE IF EXISTS limb;"))){ mpre("ОШИБКА создания таблицы данных");
		}else{ //mpre("Обнуление базы данных" ,$sql);
		}})){ mpre("ОШИБКА обнуления базы данных");
	}else if(call_user_func(function()use($ARGV){ # Обнуление базы данных
		if(!array_key_exists("--clear" ,$ARGV)){ //mpre("Не обнуляем базу данных");
		}else if(!mpre("Обнуляем расчеты")){ mpre("Уведомление");
		}else if(!qw(mpre("DROP TABLE IF EXISTS limb;"))){ mpre("ОШИБКА создания таблицы данных");
		}else if(!qw(mpre("UPDATE bit SET limb_id=NULL WHERE limb_id IS NOT NULL;"))){ mpre("ОШИБКА удаления базы данных");
		}else if(!qw(mpre("UPDATE link SET limb_id=NULL WHERE limb_id IS NOT NULL;"))){ mpre("ОШИБКА удаления базы данных");
		}else{ //mpre("Обнуление результатов расчетов");
		}})){ mpre("ОШИБКА обнуления базы данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS data (id INTEGER PRIMARY KEY AUTOINCREMENT ,name TEXT, json TEXT);")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `data-name` ON data(`name`);")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!$count =ql("SELECT COUNT(*) AS cnt FROM data;" ,0)){ mpre("ОШИБКА выборки количеста данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS link (id INTEGER PRIMARY KEY AUTOINCREMENT ,data_id INT ,bit_id INT ,limb_id INT ,val EVAL(0,1) ,_bin TEXT);")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS value (id INTEGER PRIMARY KEY AUTOINCREMENT ,name TEXT ,learn EVAL(0,1) ,len INT, max TEXT);")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS bit (id INTEGER PRIMARY KEY AUTOINCREMENT ,name TEXT ,value_id INT ,nn INT ,limb_id INT ,UNIQUE(value_id ,nn));")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS limb (id INTEGER PRIMARY KEY AUTOINCREMENT ,bit_id INT ,limb_id INT ,val EVAL(0,1) ,lvl INT ,_bit_id INT ,bits TEXT ,UNIQUE(bit_id,limb_id,val));")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `bit-value_id-nn` ON bit(`value_id` ,`nn`);")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE UNIQUE INDEX IF NOT EXISTS `link-data_id-bit_id` ON link(data_id ,bit_id);")){ mpre("ОШИБКА установки уникального ключа");
	}else if(!$file = fopen("php://stdin", "r")){ mpre("ОШИБКА открытия файла");
	//}else if(!stream_set_blocking($file, 0)){ mpre("Отмена блокировки");
	}else if(!array_key_exists("--data" ,$ARGV)){ //mpre("Не загружаем данные");
	}else if(!mpre("Загрузка данных")){ mpre("Уведомление");
	}else if(!is_array($LINE =call_user_func(function($LINE =[])use($file){ # Построчная выборка
		while (false !== ($line = fgets($file))){ $LINE[] =$line;
		}return $LINE; }))){ mpre("Данных в STDIN не найдено");
	//}else if($count["cnt"]){ mpre("Данные уже загружены" ,$count);
	}else if(!$LINE){ //mpre("Ожидаются данные STDIN");
	}else if(!$json =implode("" ,$LINE)){ mpre("ОШИБКА получения json данных");
	}else if(!$_DATA =json_decode($json ,true)){ mpre("ОШИБКА получения данных из json" ,$json);
	}else if(!$DATA =array_map(function($_data ,$value =[]){ # Данные из консоли
		if(krsort($_data[0]) &&array_key_exists(1 ,$_data) &&krsort($_data[1]) &&0){ mpre("ОШИБКА сортировки данных");
		}else if(!$json =json_encode($_data ,JSON_UNESCAPED_UNICODE)){ mpre("Формироание данных");
		}else if(!$md5 =md5($json)){ mpre("Хеш данных");
		}else if($data =rb("data" ,"name" ,"[{$md5}]")){ mpre("Данные уже есть в базе {$md5}");
		}else if(!$data =fk("data" ,$w =["name"=>$md5] ,$w +=["json"=>$json] ,$w)){ mpre("ОШИБКА загрузки данных");
		}else if(!mpre("Сохранение данных data_id:{$data["id"]} {$md5}")){ mpre("Уведомление");
		}else{ //mpre("Данные {$md5}" ,$json ,$data ,$value);
		}return $data; } ,$_DATA)){ mpre("ОШИБКА загрузки данных в базу");
	}else if(!array_map(function($_data)use(&$SIGN){ # Статистика значений
		if(!$_value =array_merge($_data[0] ,$_data[1])){ mpre("ОШИБКА обьединения значений");
		}else if(!array_map(function($learn ,$VALS)use(&$SIGN ,$_data){ # Перебор наборов данных
			if(false){ mpre("Уведомление");
			}else if(!$_val =array_map(function($name ,$val ,$_val ="")use(&$SIGN ,$_data ,$learn){ # Расчет значений
				if($val !=bindec(decbin($val))){ mpre("ОШИБКА обратное приведение не равно исходному");
				}else if(!is_numeric($SIGN[$name]["max"] =(($val <($_val =get($SIGN ,$name ,"max"))) ?$_val :$val))){ mpre("ОШИБКА получения максимального значения {$name}=>{$val}");
				}else if(!is_numeric($SIGN[$name]["min"] =((is_null($_val =get($SIGN ,$name ,"min")) ||($val <=$_val)) ?$val :$_val))){ mpre("ОШИБКА получения минимального значения {$name}=>{$val}");
				}else if(!is_numeric($SIGN[$name]["learn"] =$learn)){ mpre("ОШИБКА установки признака обучения");
				}else{ //mpre("Расчет размера значений" ,$SIGN);
				}} ,array_keys($VALS) ,$VALS)){ mpre("ОШИБКА получения результаов");
			}else{ //mpre("Перебор наборов данных {$learn}" ,$VALS);
			}} ,array_keys($_data) ,$_data)){ mpre("ОШИБКА перебора наборов данных");
		}else{ //mpre("Обьединенные значения" ,$_data ,$_value ,$_val);
		}} ,$_DATA)){ mpre("ОШИБКА сбора статистики о значениях");
	}else if(!$VALUE =array_map(function($name ,$sign ,$value =[]){ # Сохранение максимальных значений
		if(!is_numeric($bin =decbin(get($sign ,"max") ?:0))){ mpre("ОШИБКА полученя значения");
		}else if(!is_array($value =rb("value" ,"name" ,"[$name]"))){ mpre("ОШИБКА выборки старого значения");
		}else if(!$len =$value ?max($value["len"] ,strlen($bin)) :strlen($bin)){ mpre("ОШИБКА расчета размера");
		}else if(!$value =fk("value" ,$w =["name"=>$name] ,$w +=["learn"=>$sign["learn"] ,"len"=>$len ,"min"=>$sign["min"] ,"max"=>$sign["max"]] ,$w)){ mpre("ОШИБКА сохраения значения");
		}else if(!array_map(function($nn)use($value ,$name){ # Добавление сигналов
			if(!$name ="{$value["name"]}:{$nn}"){ mpre("ОШИБКА формирования имени");
			}else if(!$bit =fk("bit" ,$w =["value_id"=>$value["id"] ,"nn"=>$nn] ,$w +=["name"=>$name])){ mpre("Уведомление");
			}else{ mpre("Добавление бита {$name} {$nn}");
			}} ,range(1 ,$value["len"]))){ mpre("ОШИБКА добавления сигналов");
		}else{ //mpre("Сохранение максимальных значений" ,$value);
		}return $value; } ,array_keys($SIGN) ,$SIGN)){ mpre("ОШИБКА сохранения максимальных значений");
	}else if(!qw("PRAGMA temp_store=MEMORY;")){ mpre("Указываем временное хранилище в памяти");
	}else if(!qw("PRAGMA SYNCHRONOUS=OFF;")){ mpre("Указываем синхронизацию");
	}else if(!$LINK =array_map(function($data)use($SIGN ,$VALUE){ array_map(function($value)use($data ,$SIGN){ # Формирование ссылок на результат
		if(!$json =strtr(get($data ,"json") ,["\"\""=>"\""])){ mpre("ОШИБКА получения строки json значений");
		}else if(!$vals =json_decode($json ,true)){ mpre("ОШИБКА получения значений");
		}else if(!is_numeric($dec =get($vals ,$value["learn"] ,$value["name"]))){ mpre("ОШИБКА получения десятеричного значения данных" ,$vals ,$value);
		}else if(!is_numeric($_bin =decbin($dec))){ mpre("ОШИБКА расчета из десятичного в двоичный");
		}else if(!is_numeric($bin =str_pad($_bin ,$value["len"] ,0 ,STR_PAD_LEFT))){ mpre("ОШИБКА добавления значения");
		}else if($value["len"] <strlen($bin)){ mpre("ОШИБКА значение по длинне больше максимально допустимого");
		}else if(!$BIT =rb("bit" ,"value_id" ,"id" ,$value["id"])){ mpre("ОШИБКА получения списка битов значения");
		}else if($value["len"] !=count($BIT)){ mpre("ОШИБКА количество бит отличается от бит значения" ,$value ,$BIT);
		}else if(!qw("BEGIN TRANSACTION;")){ mpre("ОШИБКА запуска транзакции");
		}else if(!$LEARN =array_map(function($bit ,$learn =[] ,$val ="")use($data ,$value ,$bin){ # Создание ссылок битов
			if(!is_numeric($pos =$value["len"] -$bit["nn"])){ mpre("ОШИБКА расчета ");
			}else if(!is_numeric($val =substr($bin ,$pos ,1))){ mpre("ОШИБКА выборки значения бита");
			}else if(!$value["learn"] &&!$val){ //mpre("Пропуск нулеого значения");
			}else if(!qw("INSERT OR IGNORE INTO link(data_id ,bit_id ,val) VALUES ({$data["id"]} ,{$bit["id"]} ,{$val})")){ mpre("ОШИБКА добавления значения");
			}else if(!$value["learn"]){ //mpre("Не расчитываем для исходного сигнала");
			}else if(!$bit["limb_id"]){ //mpre("Нулевой результат не задан");
			}else if(!$link =rb("link" ,"data_id" ,"bit_id" ,"val" ,$data["id"] ,$bit["id"] ,$val)){ mpre("ОШИБКА выборки ссылки");
			}else if($link["limb_id"]){ //mpre("Результат уже задан");
			}else if(!$limb =rb("limb" ,"id" ,$bit["limb_id"])){ mpre("ОШИБКА выборки нулевого результата");
			}else if(!$limb =call_user_func(function($pass =1)use($limb ,$data){ while(!($pass =!$pass)){ # Актуальный результат
				if(!$lvl =$limb ?$limb["lvl"] +1 :$_limb["lvl"] +1){ mpre("Уведомление");
				}else if(!is_numeric($val =call_user_func(function($val ="")use($limb ,$data){ # Расчет значения
					if(!$_bit_id =get($limb ,"_bit_id")){ mpre("ОШИБКА идентификатор бита не задан");
					}else if(!is_array($_link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$_bit_id))){ mpre("ОШИБКА выборки значения");
					}else if(!is_numeric($val =$_link ?1 :0)){ mpre("ОШИБКА расчета значения");
					}else{ //mpre("Расчетное значение limb_id:{$limb["id"]} {$val}");
					}return $val; }))){ mpre("ОШИБКА расчета значения");
				}else if(!$_limb =rb("limb" ,"bit_id" ,"limb_id" ,"lvl" ,"val" ,$limb["bit_id"] ,$limb["id"] ,$lvl ,$val)){ //mpre("Значение не найдено");
				}else if(!$limb =$_limb){ mpre("ОШИБКА установки текущего значения");
				}else if(!get($limb ,"_bit_id")){ //mpre("Крайний результат");
				}else if(!($pass =!$pass)){ mpre("ОШИБКА продолжения цикла");
				}else{ //mpre("Расчет дерева решений lvl:{$lvl} val:{$val}" ,$limb);
				}}return $limb; })){ mpre("ОШИБКА выборки актуального результата");
			}else if(!$link =fk("link" ,["id"=>$link["id"]] ,[] ,["limb_id"=>$limb["id"]])){ mpre("ОШИБКА обновления результата");
			}else{ //mpre("Расчет значения ссылки data_id:{$data["id"]} bit_id:{$bit["id"]} bit:{$bin} nn:{$bit["nn"]} val:{$val}" ,$link ,$limb);
			}return $learn; } ,$BIT)){ mpre("ОШИБКА создания ссылок битов");
		}else if(!qw("END TRANSACTION;")){ mpre("ОШИБКА запуска транзакции");
		}else{ //mpre("Загрузка значений data_id:{$data["id"]}");
		}} ,$VALUE); mpre("Загрузка значений data_id:{$data["id"]}"); } ,$DATA)){ mpre("ОШИБКА добавления ссылки на результат");
	}else if(!qw("PRAGMA SYNCHRONOUS=NORMAL;")){ mpre("Указываем синхронизацию");
	}else if(!qw("PRAGMA temp_store=DEFAULT;")){ mpre("Указываем временное хранилище в памяти");
	}else{ //mpre("Загрузка данных {$count["cnt"]}=>" .count($_DATA) ,$VALUE);
	}})){ mpre("ОШИБКА загрузки данных");
}else if(call_user_func(function()use($ARGV ,$conf){ # Обучение данных
	if(!$epoch =get($ARGV ,"epoch")){ //mpre("Не указано количество эпох");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-bit_id` ON link(bit_id);")){ mpre("ОШИБКА формирования уникального индекса");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-data_id` ON link(data_id);")){ mpre("ОШИБКА формирования уникального индекса");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-data_id-bit_id` ON link(data_id ,bit_id);")){ mpre("ОШИБКА формирования уникального индекса");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-data_id-bit_id_val` ON link(data_id ,bit_id ,val);")){ mpre("ОШИБКА формирования уникального индекса");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-bit_id-limb_id` ON link(bit_id ,limb_id);")){ mpre("Создание ключа");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-limb_id-val` ON link(limb_id ,val);")){ mpre("Создание ключа");
	}else if(!qw("CREATE INDEX IF NOT EXISTS `link-limb_id` ON link(limb_id);")){ mpre("Создание ключа");
	//}else if(!qw("CREATE TABLE IF NOT EXISTS limb (id INTEGER PRIMARY KEY AUTOINCREMENT ,bit_id INT ,limb_id INT ,lvl INT ,val EVAL(0,1) ,_bit_id INT ,bits TEXT ,_bin TEXT ,UNIQUE(bit_id,limb_id,val));")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!qw("CREATE TABLE IF NOT EXISTS limb (id INTEGER PRIMARY KEY AUTOINCREMENT ,bit_id INT ,limb_id INT ,lvl INT ,_bit_id INT ,bits TEXT ,UNIQUE(bit_id,limb_id,val));")){ mpre("ОШИБКА создания таблицы данных");
	}else if(!$DATA =rb("data")){ mpre("Данных для обучения не найдено data {$conf["db"]["name"]}");
	//}else if(!$DATA =qn("SELECT * FROM data ORDER BY id DESC")){ mpre("ОШИБКА выборки примеров в обратном значении");
	}else if(!$VALUE =rb("value")){ mpre("Данных для обучения не найдено");
	}else if(!$_VALUE =rb($VALUE ,"learn" ,"id" ,1)){ mpre("ОШИБКА выборки обучаеых значений");
	//}else if(!$_BIT =rb("bit" ,"value_id" ,"nn")){ mpre("ОШИБКА выборки списка битов");
	}else if(!$BIT =qn("SELECT * FROM bit ORDER BY id")){ mpre("ОШИБКА выборки списка бит");
	}else if(!$_BIT =rb($BIT ,"value_id" ,"nn")){ mpre("ОШИБКА получения списка бит");
	}else if(!array_map(function($_epoch)use($DATA ,$_VALUE ,$_BIT){ array_map(function($data)use($_epoch ,$_VALUE ,$_BIT){ array_map(function($value)use($_epoch ,$data ,$_BIT){ array_map(function($bit)use($_epoch ,$data ,$value){ # Перебор обучающих данных
		if(!$nn =$bit["nn"]){ mpre("ОШИБКА расчета номера сигнала" ,$bit);
		}else if(call_user_func(function()use($_epoch){ # Отображение эпохи
			if(get($GLOBALS ,"эпоха") ==$_epoch){ //mpre("Не отображаем эпоху");
			}else if(!$GLOBALS["эпоха"] =$_epoch){ mpre("Сохраняем эпоху");
			}else if(!$microtime =get($GLOBALS ,"microtime")){ mpre("ОШИБКА выборки времени начала работы скрипта");
			}else if(!is_numeric($time =number_format((microtime(true) -$microtime) /60 ,0 ,"." ,""))){ mpre("ОШИБКА расчета времени");
			}else{ mpre("Расчет эпоха: {$_epoch} Время: {$time} минут");
			}})){ mpre("ОШИБКА отображение эпохи");
		}else if(!$bit =rb("bit" ,"value_id" ,"nn" ,$value["id"] ,$nn)){ exit(mpre("ОШИБКА Бит найден в кеше"));
		}else if(!$link =call_user_func(function($link =[])use($data ,$bit ,$value){ # Выборка и проверка ссылки
			if(!$link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$bit["id"])){ exit(mpre("ОШИБКА получения ссылки" ,$data ,$bit ,$value));
			/*if(!$link =call_user_func(function($link =[])use($data ,$bit){ # Ссылка
				if($link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$bit["id"])){ #exit(mpre("ОШИБКА получения ссылки"));
				}else if(!$limb =rb("limb" ,"id" ,$bit["limb_id"])){ mpre("ОШИБКА нулевое решение бита не установлено");
				}else if(!$limb =call_user_func(function($pass =1)use($limb ,$data){ while(!($pass =!$pass)){ # Актуальный результат
					if(!$lvl =$limb ?$limb["lvl"] +1 :$_limb["lvl"] +1){ mpre("Уведомление");
					}else if(!is_numeric($val =call_user_func(function($val ="")use($limb ,$data){ # Расчет значения
						if(!$_bit_id =get($limb ,"_bit_id")){ mpre("ОШИБКА идентификатор бита не задан");
						}else if(!is_array($_link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$_bit_id))){ mpre("ОШИБКА выборки значения");
						}else if(!is_numeric($val =$_link ?1 :0)){ mpre("ОШИБКА расчета значения");
						}else{ //mpre("Расчетное значение limb_id:{$limb["id"]} {$val}");
						}return $val; }))){ mpre("ОШИБКА расчета значения");
					}else if(!$_limb =rb("limb" ,"bit_id" ,"limb_id" ,"lvl" ,"val" ,$limb["bit_id"] ,$limb["id"] ,$lvl ,$val)){ //mpre("Значение не найдено");
					}else if(!$limb =$_limb){ mpre("ОШИБКА установки текущего значения");
					}else if(!get($limb ,"_bit_id")){ //mpre("Крайний результат");
					}else if(!($pass =!$pass)){ mpre("ОШИБКА продолжения цикла");
					}else{ //mpre("Расчет дерева решений lvl:{$lvl} val:{$val}" ,$limb);
					}}return $limb; })){ mpre("ОШИБКА выборки актуального результата");
				//}else if(!$link =fk("link" ,["id"=>$link["id"]] ,[] ,["limb_id"=>$limb["id"]])){ mpre("ОШИБКА обновления результата");
				}else if(!$link =fk("link" ,$w =["data_id"=>$data["id"] ,"bit_id"=>$bit["id"]] ,$w +=["val"=>"0" ,"limb_id"=>$limb["id"]] ,$w)){ mpre("ОШИБКА добавления новой ссылки");
				}else{ mpre("Добавление новой ссылки итогового бита" ,$link);
				}return $link; })){ mpre("ОШИБКА получения ссылки");*/
			}else if(!is_array($_limb =($limb_id =$link["limb_id"]) ?rb("limb" ,"id" ,$limb_id) :[])){ mpre("ОШИБКА выборки нулевого результата");
			}else if(!$_limb &&$bit["limb_id"]){ exit(mpre("ОШИБКА значение не найдено" ,$link ,$_limb ,$bit));
			}else if(!$_bit_id =get($_limb ,"_bit_id")){ //mpre("Крайний результат");
			}else if(!is_array($_link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$_bit_id))){ mpre("ОШИБКА выборки значения бита");
			}else if(!is_numeric($val =$_link ?1 :0)){ mpre("ОШИБКА расчта значения бита результата");
			}else if(!$lvl =$_limb["lvl"] +1){ mpre("ОШИБКА расчета следующего уровня");
			}else if(!$_limb_ =rb("limb" ,"bit_id" ,"limb_id" ,"lvl" ,"val" ,$bit["id"] ,$_limb["id"] ,$lvl ,$val)){ //mpre("Уровень не найден bit_id:{$bit["id"]} limb_id:{$_limb["id"]} lvl:{$lvl} val:{$val}");
			}else{ exit(mpre("ОШИБКА найден нижестоящий уровень ссылки data_id:{$data["id"]} name:{$value["name"]} {$bit["name"]} limk_id:{$link["id"]} val:{$val} lvl:$lvl" ,$link ,$_link ,$_limb ,$_limb_));
			}return $link; })){ mpre("ОШИБКА выборки ссылки");
		}else if(!is_numeric($val =$link["val"])){ mpre("ОШИБКА значения не совпали {$val} !={$link["val"]} name:{$value["name"]} bin:{$bin}" ,$data ,$bit ,$link);
		}else if(!is_array($limb =rb("limb" ,"id" ,$link["limb_id"]))){ mpre("Нулевой результат уже создан");
		}else if(!is_string($_val =call_user_func(function($_val ="")use($limb){ # Расчет результата
			if(!$limb){ //mpre("Данных недостаточно для расчета результата");
			}else if(!is_numeric($_val =(string)($limb["lvl"] %2))){ mpre("ОШИБКА расчета результата");
			}else{ //mpre("Расчет результата \$_val={$_val}");
			}return $_val; }))){ mpre("ОШИБКА расчета результата");
		}else if(!is_numeric($lvl =get($limb ,"lvl") ?:0)){ mpre("ОШИБКА расчета уровня");
		}else if(!is_numeric($cnt =$limb ?ql("SELECT COUNT(*) AS cnt FROM link WHERE limb_id=" .$limb["id"] ,0 ,"cnt") :0)){ mpre("ОШИБКА выборки количества задействованных элементов");
		//}else if(!mpre()){ mpre("Уведомление");
		}else if(!$info ="Расчет epoch:{$_epoch} alg=" .get($GLOBALS ,"ARGV" ,"alg") ." data_id:{$data["id"]} value_id:{$value["id"]} {$bit["name"]} nn:{$nn} lvl:{$lvl} cnt:{$cnt} _val:{$_val} val:{$val} " .($val ==$_val ?"=== Совпадение ===" :"!!! Разница !!!")){ mpre("ОШИБКА установки информации");
		}else if(!is_array([$_bit ,$stat] =call_user_func(function($_bit =[] ,$stat =[])use($_epoch ,$info ,$data ,$limb ,$bit ,$link ,$cnt ,$val ,$_val){ # Расчет подходящего бита
			if($val ==$_val){ //mpre("Значения совпали не расчитываем подходящий бит");
			//}else if(!mpre($info)){ mpre("Уведомление");
			}else if(!$limb){ //mpre("Не расчитываем для нулевого сигнала");
			}else if($_bit =($_bit_id =get($limb ,"_bit_id")) ?rb("bit" ,"id" ,$_bit_id) :$_bit){ //mpre("Расчетный бит установлен ранее");
			}else if($limb["_bit_id"]){ //mpre("Бит разделитель установлен ранее");
			}else if(!is_array($_bits =explode("," ,(get($limb ,"bits") ?:"")))){ mpre("ОШИБКА получения списка битов");
			}else if(!$VALUE =rb("value" ,"learn" ,"id" ,0)){ mpre("ОШИБКА выборки обучаемых значений");
			}else if(!$BIT =rb("bit" ,"value_id" ,"id" ,$VALUE)){ mpre("ОШИБКА выборки бит обучения");
			}else if(!$microtime =microtime(true)){ mpre("ОШИБКА засечения времени");
			//}else if(!is_array($stat =[])){ mpre("ОШИБКА устанвоки массива статистики");
			}else if(!$STAT =array_map(function($_bit ,$_stat =[])use(&$stat ,$_epoch ,$data ,$bit ,$limb ,$_bits ,$cnt ,$microtime){ # Расчет бит
				if(is_numeric(array_search($_bit["id"] ,$_bits))){ //mpre("Пропускаем расчеты");
				}else if(100 ==get($stat ,"v")){ //mpre("Уже найдено лучшее значение");
				}else if(!$where =$limb ?"l1.limb_id={$limb["id"]}" :"TRUE"){ mpre("ОШИБКА установки условия выборки ссылок");
				}else if(!$sql =preg_replace("#\n\t+#" ," " ,"SELECT l1.data_id ,l1.val AS val1 , l0.val AS val0 ,SUM(l0.val =l1.val) AS sum ,
					SUM(IIF(1 =l0.val, 1, 0)) AS s1 ,
					SUM(IIF(l0.val IS NULL, 1, 0)) AS s0 ,
					SUM(IIF((1 =l0.val) & (1 =l1.val) ,1 ,0)) AS m11 ,
					SUM(IIF((1 =l0.val) & (0 =l1.val) ,1 ,0)) AS m10 ,
					SUM(IIF((l0.val IS NULL) & (1 =l1.val) ,1 ,0)) AS m01 ,
					SUM(IIF((l0.val IS NULL) & (0 =l1.val) ,1 ,0)) AS m00 ,
					SUM(IIF(1 =l1.val, 1, 0)) AS z1 ,
					SUM(IIF(0 =l1.val, 1, 0)) AS z0 ,
					COUNT(l1.id) AS count
					FROM link AS l1 LEFT JOIN link AS l0 ON (l1.data_id =l0.data_id) AND l0.bit_id=" .(int)$_bit["id"] ." WHERE {$where} AND l1.bit_id=" . (int)$bit["id"] ."")){ mpre("ОШИБКА составления запроса");
				}else if(!$_stat =ql($sql ,0)){ mpre("ОШИБКА выборки статистики");
				}else if(!$_stat["bit_id"] =$_bit["id"]){ mpre("ОШИБКА установки бита");
				}else if(!is_array($_stat =call_user_func(function($alg ="баланс")use($_stat){ # Расчет статистики статистики минимального сигнала ~19.38
					if(array_key_exists("v" ,$_stat)){ //mpre("Значение уже расчитано");
					}else if(($_alg =get($GLOBALS ,"ARGV" ,"alg")) &&($_alg !=$alg)){ //mpre("Выбран другой алгоритм");
					}else if(!is_numeric($_stat["v1"] =number_format(($_stat["m11"] -$_stat["m01"]) /max($_stat["m11"] ,$_stat["m01"]) *50 ,2 ,"." ,""))){ mpre("ОШИБКА расчета единицы");
					}else if(!is_numeric($_stat["v0"] =number_format(($_stat["m00"] -$_stat["m10"]) /max($_stat["m00"] ,$_stat["m10"]) *50 ,2 ,"." ,""))){ mpre("ОШИБКА расчета нуля");
					}else if(!is_numeric($_stat["_v"] =number_format($_stat["v0"] +$_stat["v1"] ,2 ,"." ,""))){ mpre("ОШИБКА расчета общего результата");
					}else if(!is_numeric($_stat["v"] =abs($_stat["_v"]))){ mpre("ОШИБКА расчета общего результата");
					//}else if(!is_numeric($_stat["_v1"] =$_stat["m11"] +$_stat["m10"])){ mpre("ОШИБКА расчета единицы");
					//}else if(!is_numeric($_stat["_v0"] =$_stat["m00"] +$_stat["m01"])){ mpre("ОШИБКА расчета нуля");
					//}else if(!is_string($_stat["v"] .=";-" .(floor(abs($_stat["_v0"] -$_stat["_v1"])) *$_stat["kf"] /100))){ mpre("ОШИБКА расчета общего результата");
					//}else if(!is_numeric($_stat["kf"] =is_numeric($kf =get($GLOBALS ,"ARGV" ,"kf")) ?$kf :10)){ mpre("ОШИБКА расчета коэффициента");
					}else{ //mpre("Расчет результата" ,$_stat);
					}return $_stat; }))){ mpre("ОШИБКА расчета статистики");
				/*}else if(!is_array($_stat =call_user_func(function($alg ="масса")use($_stat){ # Расчет статистики статистики минимального сигнала ~19.38
					if(array_key_exists("v" ,$_stat)){ //mpre("Значение уже расчитано");
					}else if(($_alg =get($GLOBALS ,"ARGV" ,"alg")) &&($_alg !=$alg)){ //mpre("Выбран другой алгоритм");
					}else if(!is_numeric($_stat["v1"] =$_stat["m11"] -$_stat["m10"])){ mpre("ОШИБКА расчета единицы");
					}else if(!is_numeric($_stat["v0"] =$_stat["m00"] -$_stat["m01"])){ mpre("ОШИБКА расчета нуля");
					}else if(!is_numeric($_stat["_v"] =$_stat["v0"] +$_stat["v1"])){ mpre("ОШИБКА расчета общего результата");
					}else if(!is_numeric($_stat["v"] =abs($_stat["_v"]))){ mpre("ОШИБКА расчета общего результата");
					}else if(!is_numeric($_stat["_v1"] =$_stat["m11"] +$_stat["m10"])){ mpre("ОШИБКА расчета единицы");
					}else if(!is_numeric($_stat["_v0"] =$_stat["m00"] +$_stat["m01"])){ mpre("ОШИБКА расчета нуля");
					//}else if(!is_numeric($_stat["kf"] =is_numeric($kf =get($GLOBALS ,"ARGV" ,"kf")) ?$kf :0)){ mpre("ОШИБКА расчета коэффициента");
					}else if(!is_string($_stat["v"] .=";-" .(floor(abs($_stat["_v0"] -$_stat["_v1"])) *$_stat["kf"] /100))){ mpre("ОШИБКА расчета общего результата");
					}else{ //mpre("Расчет результата" ,$_stat);
					}return $_stat; }))){ mpre("ОШИБКА расчета статистики");
				}else if(!is_array($_stat =call_user_func(function($alg ="вес")use($_stat){ # Расчет статистики статистики минимального сигнала ~20.38
					if(array_key_exists("v" ,$_stat)){ //mpre("Значение уже расчитано");
					}else if(($_alg =get($GLOBALS ,"ARGV" ,"alg")) &&($_alg !=$alg)){ //mpre("Выбран другой алгоритм");
					}else if(!is_numeric($_stat["v1"] =$_stat["m11"] -$_stat["m10"])){ mpre("ОШИБКА расчета единицы");
					}else if(!is_numeric($_stat["v0"] =$_stat["m00"] -$_stat["m01"])){ mpre("ОШИБКА расчета нуля");
					}else if(!is_numeric($_stat["_v"] =$_stat["v0"] +$_stat["v1"])){ mpre("ОШИБКА расчета общего результата");
					}else if(!is_numeric($_stat["v"] =abs($_stat["_v"]))){ mpre("ОШИБКА расчета общего результата");
					}else{ //mpre("Расчет результата" ,$_stat);
					}return $_stat; }))){ mpre("ОШИБКА расчета статистики");*/
				}else if(!array_key_exists("v" ,$_stat)){ exit(mpre("ОШИБКА выбора алгоритма расчета статистики alg=" .get($GLOBALS ,"ARGV" ,"alg")));
				}else if(!is_array($stat =call_user_func(function($_stat_ =[])use($data ,$stat ,$_stat){ # выбор значения
					if(!is_array($_stat_ =$stat ?:$_stat_)){ mpre("ОШИБКА Установка значения");
					}else if(!$_stat["s1"]){ //mpre("Старшее значение нулевое" ,$_stat);
					}else if(!$_stat["s0"]){ //mpre("Младшее значение нулевое" ,$_stat);
					}else if(!$_stat_ =$stat ?:$_stat){ mpre("ОШИБКА установки дефолтного значения");
					}else if(!$_V_ =explode(";" ,$_stat_["v"])){ mper("ОШИБКА разделения значений");
					}else if(!$_V =explode(";" ,$_stat["v"])){ mper("ОШИБКА разделения значений");
					}else if(!$_stat_ =(array_sum($_V) >array_sum($_V_)) ?$_stat :$_stat_){ //mpre("Выбранный уже текущего");
					}else{ //mpre("Выбор текущего" ,$_stat_ ,$_stat ,$_V_ ,$_V);
					}return $_stat_; }))){ mpre("ОШИБКА выбора подходящего значения data_id:{$data["id"]} _bit_id:{$_bit["id"]}" ,$_bits ,$_stat);
				//}else if(!array_key_exists("--verbose" ,$GLOBALS["ARGV"])){ //mpre("Не выводим подробную информацию");
				}else if((1000 >$cnt) &!array_key_exists("--verbose" ,$GLOBALS["ARGV"])){ //mpre("Не отображаем подробную статистику поиска бит");
				}else if(!is_string($count =$stat ?"{$stat["count"]}" :"")){ mpre("ОШИБКА расчета количества элементов");
				}else if(!is_string($v =$stat ?"{$stat["v"]}" :"")){ mpre("ОШИБКА расчета количества элементов");
				}else if(!is_string($sign =$stat ?"s1:{$stat["s1"]} s0:{$stat["s0"]} z1:{$stat["z1"]} z0:{$stat["z0"]} v1:{$stat["v1"]} v0:{$stat["v0"]} 11:{$stat["m11"]} 10:{$stat["m10"]} (1:{$stat["v1"]}) {$stat["_v"]} (0:{$stat["v0"]}) 01:{$stat["m01"]} 00:{$stat["m00"]}" :"")){ mpre("ОШИБКА статистики сигналов");
				}else if(!is_string($_sign =$_stat ?"s1:{$_stat["s1"]} s0:{$_stat["s0"]} z1:{$_stat["z1"]} z0:{$_stat["z0"]} v1:{$_stat["v1"]} v0:{$_stat["v0"]} 11:{$_stat["m11"]} 10:{$_stat["m10"]} (1:{$_stat["v1"]}) {$_stat["_v"]} (0:{$_stat["v0"]}) 01:{$_stat["m01"]} 00:{$_stat["m00"]}" :"")){ mpre("ОШИБКА статистики сигналов");
				}else if(!is_numeric($lvl =$limb ?$limb["lvl"] :0)){ mpre("ОШИБКА расчтеа уровня");
				}else if(!$time =call_user_func(function($time ="")use($microtime){ # Расчет времени
					if(!$_time =microtime(true) -$microtime){ mpre("ОШИБКА расчета времени");
					}else if(!is_numeric($min =floor($_time /60))){ mpre("ОШИБКА расчтеа минут");
					}else if(!is_numeric($sec =$_time %60)){ mpre("ОШИБКА расчета значения секунд");
					}else if(!$time =($min <10 ?"0" :"") .$min .":" .($sec <10 ?"0" :"") .$sec){ mpre("ОШИБКА расчета времени");
					}return $time; })){ mpre("ОШИБКА расчета времени");
				//}else if(!is_numeric($mtime =number_format(microtime(true) -get($GLOBALS ,"microtime"),2 ,"." ,""))){ mpre("ОШИБКА расчета времени");
				}else if(!$mtime =call_user_func(function($time =""){ # Расчет времени
					if(!$_time =microtime(true) -get($GLOBALS ,"microtime")){ mpre("ОШИБКА расчета времени");
					}else if(!is_numeric($min =floor($_time /60))){ mpre("ОШИБКА расчтеа минут");
					}else if(!is_numeric($sec =$_time %60)){ mpre("ОШИБКА расчета значения секунд");
					}else if(!is_numeric($hour =floor($_time /3600))){ mpre("ОШИБКА расчтеа минут");
					}else if(!$time =($hour <10 ?"0" :"") .$hour .":" .($min <10 ?"0" :"") .$min .":" .($sec <10 ?"0" :"") .$sec){ mpre("ОШИБКА расчета времени");
					}return $time; })){ mpre("ОШИБКА расчета времени");
				}else if(!mpre("Статистика: epoch:{$_epoch} data_id:{$data["id"]} {$bit["name"]} alg:" .get($GLOBALS ,"ARGV" ,"alg") ." _bit_id:{$_bit["id"]} {$time} lvl:{$lvl} {$cnt}/{$count}/{$_stat["v"]}/{$v} time: {$mtime}" ,$_sign ."\n\t" .$sign)){ mpre("Уведомление");
				//}else if(!mpre("Статистика:" ,$_stat ,$stat)){ mpre("Уведомление");
				//}else if(get($stat ,"bit_id") !=$_stat["bit_id"]){ //mpre("Не обновленное значение");
				//}else if(!$LINK =ql(strtr($sql ,["SUM"=>"" ,"COUNT"=>""]))){ mpre("ОШИБКА выборк данных");
				}else{ //mpre("Подходящее значение" ,$_stat ,$stat);
				}return $stat; } ,$BIT)){ mpre("ОШИБКА расчетав бит");
			}else if(!is_array($_bit =call_user_func(function($_bit =[])use($STAT ,$stat){ # Выбор подходящего
				if(!$stat){ mpre("Cтатистика не задана");
				}else if(!$_bit =rb("bit" ,"id" ,$stat["bit_id"])){ mpre("ОШИБКА выборки бит");
				}else if(!array_key_exists("--verbose" ,$GLOBALS["ARGV"])){ //mpre("Не выводим подробную информацию");
				}else{ //mpre("Наиболее подходящий" ,$stat);
				}return $_bit; }))){ mpre("ОШИБКА выборки подходящего");
			}else{ //mpre("Расчитываем подходящий бит" ,$_bits ,$_bit);
			}return [$_bit ,$stat]; }))){ mpre("ОШИБКА расчета подходящего бита");
		}else if(!is_array($_limb =call_user_func(function($_limb =[])use(&$link ,$info ,$stat ,$_epoch ,$data ,$limb ,$bit ,$_bit ,$val ,$_val){ # Обучение модели
			if($val ==$_val){ //mpre("Значения совпали не расчитываем подходящий бит");
			}else if($limb && !$_bit){ exit(mpre("ОШИБКА Сигнал для обучения не задан" ,rb("bit" ,"value_id" ,"id" ,rb("value" ,"learn" ,"id" ,0))));
			//}else if(!is_array($_bit =($_bit_id =get($limb ,"_bit_id")) ?rb("bit" ,"id" ,$_bit_id) :$_bit)){ mpre("Обновление расчетного бита");
			}else if(!is_numeric($lvl =$limb ?$limb["lvl"] +1 :0)){ mpre("ОШИБКА увеличение уровня результата");
			}else if(!is_string($_v =call_user_func(function($_v ="")use($limb ,$data ,$_bit){ # Расчет значения
				if(!$limb){ //mpre("Старший результат не задан");
				}else if(!$_bit){ mpre("ОШИБКА не расчитан бит разделитель");
				}else if(!is_array($_link =rb("link" ,"data_id" ,"bit_id" ,$data["id"] ,$_bit["id"]))){ mpre("ОШИБКА получения ссылку значения исходного результата" ,$data);
				}else if(!is_string($_v ="" .($_link ?1 :0))){ mpre("ОШИБКА расчета значения {$_v}" ,$_link);
				}return $_v; }))){ mpre("ОШИБКА расчета значения");
			}else if(!is_string($bits =($b =get($limb ,"bits")) .($b ?"," :"") .get($_bit ,"id"))){ mpre("ОШИБКА расчета списка битов");
			}else if(!qw("BEGIN TRANSACTION;")){ mpre("ОШИБКА запуска транзакции");
			}else if(!is_array($limb =$limb ?fk("limb" ,["id"=>$limb["id"]] ,[] ,["_bit_id"=>$_bit["id"]]) :$limb)){ mpre("ОШИБКА обновления родительского результата");
			}else if(!$_limb =fk("limb" ,$w =["bit_id"=>$bit["id"] ,"limb_id"=>get($limb ,"id") ,"lvl"=>$lvl ,"val"=>$_v] ,$w +=["bits"=>$bits])){ mpre("ОШИБКА установки нулевого результата");
			//}else if(!mpre("Расширение _bit_id:" .get($_bit ,"id") ,$_limb)){ mpre("Уведомление");
			}else if(!$bit =call_user_func(function()use($bit ,$_limb){ # Свойства бита
				if($bit["limb_id"]){ //mpre("Связь с битом установлена ранее");
				}else if(!$bit =fk("bit" ,["id"=>$bit["id"]] ,[] ,["limb_id"=>$_limb["id"]])){ mpre("ОШИБКА сохранения связи бита с результатом");
				}else{ //mpre("Установка связи бита с результатом" ,$bit);
				}return $bit; })){ mpre("ОШИБКА установки свойств бита");
			}else if(call_user_func(function()use($data ,$info ,$stat ,$bit ,$_bit ,$link ,$limb ,$_limb ,$_v){ # Согласование оставшихся ссылок
				if(false){ mpre("Уведомление");
				}else if(!is_numeric($_cnt =call_user_func(function($cnt =0)use($limb){ # Количество ссылок у результата
					if(!$limb){ //mpre("Результат не задан");
					}else if(!$count =ql("SELECT COUNT(*) AS cnt FROM link WHERE limb_id=" .(int)$limb["id"])){ mpre("ОШИБКА расчета количества элементов" ,$count);
					}else if(!is_numeric($cnt =get($count ,0 ,"cnt"))){ mpre("ОШИБКА результат не найден" ,$count);
					}else{ //mpre("Результат расчтета {$cnt}" ,$count);
					}return $cnt; }))){ mpre("ОШИБКА расчета количества ссылок");
				}else if(!is_string($sql =!$limb ?"UPDATE link SET limb_id=" .$_limb["id"] ." WHERE bit_id=" .$bit["id"] ." AND limb_id IS NULL" :"")){ mpre("Установка первоначального значения");
				}else if(!is_string($_sql =$limb ?"SELECT l1.id FROM link as l1 LEFT JOIN link AS l0 ON l1.data_id=l0.data_id AND l0.bit_id=" .$_bit["id"] ." WHERE l0.val IS" .($_v ?" NOT" :"") ." NULL AND l1.limb_id=" .$limb["id"] ."" :"")){ mpre("ОШИБКА составления условия выборки");
				}else if(!is_string($sql =$limb ?"UPDATE link SET limb_id=" .$_limb["id"] ." WHERE id IN ({$_sql})" :$sql)){ mpre("ОШИБКА запроса");
				}else if(!qw($sql)){ mpre("ОШИБКА обновления значений" ,$sql);
				}else if(!is_numeric($cnt =call_user_func(function($cnt =0)use($_limb){ # Количество ссылок у результата
					if(!$_limb){ //mpre("Результат не задан");
					}else if(!$count =ql("SELECT COUNT(*) AS cnt FROM link WHERE limb_id=" .(int)$_limb["id"])){ mpre("ОШИБКА расчета количества элементов" ,$count);
					}else if(!is_numeric($cnt =get($count ,0 ,"cnt"))){ mpre("ОШИБКА результат не найден" ,$count);
					}else{ //mpre("Результат расчтета {$cnt}" ,$count);
					}return $cnt; }))){ mpre("ОШИБКА расчета количества ссылок");
				/*}else if(call_user_func(function()use($cnt ,$_cnt ,$limb ,$_limb){ # Поиск пустых решений
					if($_cnt !=$cnt){ //mpre("Не пустой");
					}else if(!$LIMB =rb("limb" ,"limb_id" ,"id" ,$limb["id"])){ mpre("ОШИБКА выборки количества дочерних решений");
					}else if(2 ==count($LIMB)){ //mpre("Второе дочернее решение");
					}else{ exit(mpre("ОШИБКА пустое решение {$cnt} =>{$_cnt}" ,$limb ,$_limb));
					}})){ mpre("ОШИБКА Поиск пустых решений");*/
				}else if(!is_array($_limb_ =qn("SELECT * FROM limb WHERE limb_id=" .(int)$_limb["limb_id"] ." AND id<>" .(int)$_limb["id"]))){ mpre("Братское решение");
				}else if(!$mtime =call_user_func(function($time =""){ # Расчет времени
					if(!$_time =microtime(true) -get($GLOBALS ,"microtime")){ mpre("ОШИБКА расчета времени");
					}else if(!is_numeric($min =floor($_time /60))){ mpre("ОШИБКА расчтеа минут");
					}else if(!is_numeric($sec =$_time %60)){ mpre("ОШИБКА расчета значения секунд");
					}else if(!is_numeric($hour =floor($_time /3600))){ mpre("ОШИБКА расчтеа минут");
					}else if(!$time =($hour <10 ?"0" :"") .$hour .":" .($min <10 ?"0" :"") .$min .":" .($sec <10 ?"0" :"") .$sec){ mpre("ОШИБКА расчета времени");
					}return $time; })){ mpre("ОШИБКА расчета времени");
				}else{ mpre("{$info} {$_cnt} => {$cnt} ". ($_limb_ ?"(вторичное)" :"{$stat["v"]}%") ." {$mtime}");// sleep(1);
				}})){ mpre("ОШИБКА согласования оставшихся ссылок");
			}else if(!qw("END TRANSACTION;")){ mpre("ОШИБКА запуска транзакции");
			}else{ //mpre("Расширение модели _epoch:{$_epoch} data_id:{$data["id"]}" ,$link ,$limb ,$_limb); #
			}return $_limb; }))){ mpre("ОШИБКА расширения модели");
		}} ,$_BIT[$value["id"]]); } ,$_VALUE); } ,$DATA); } ,range(1 ,$epoch))){ mpre("ОШИБКА перебора эпох");
	}else{ //mpre("Обучение epoch={$epoch}" ,$GLOBALS["Статистика"]);
	}})){ mpre("ОШИБКА обучения");
}else if(call_user_func(function()use($ARGV){ # Расчет
	if(!$_data =call_user_func(function($_data =""){ # Данные из консоли
		if(!is_string($_data =get($GLOBALS ,"ARGV" ,"data") ?:"")){ //mpre("Значение для расчета не задано");
		}else if(!array_key_exists("--calc" ,$GLOBALS["ARGV"])){ //mpre("Данные не заданы");
		}else if(!$file = fopen("php://stdin", "r")){ mpre("ОШИБКА открытия файла");
		}else if(!is_array($LINE =call_user_func(function($LINE =[])use($file){ # Построчная выборка
			while (false !== ($line = fgets($file))){ $LINE[] =$line;
			}return $LINE; }))){ mpre("Данных в STDIN не найдено");
		}else if(!$_data =implode("" ,$LINE)){ mpre("ОШИБКА получения строки данных");
		}else{ //mpre("Строка пайп" ,$LINE);
		}return $_data; })){ //mpre("Данные консоли не заданы");
	}else if(!$DATA =json_decode($_data ,true)){ mpre("ОШИБКА парсинга входящих данных");
	}else if(!$VALUE =rb("value")){ mpre("ОШИБКА значения не найдены");
	//}else if(array_diff_key($data[0] ,rb($VALUE ,"name"))){ mpre("ОШИБКА указаны не все значения" ,$data);
	}else if(!mpre("Количество данных" ,count($DATA))){ mpre("Уведомление");
	}else if(!$BIT =rb("bit")){ mpre("ОШИБКА получения списка бит");
	}else if(!$CALC =array_map(function($nn ,$data ,$calc =[])use(&$count ,$VALUE ,$BIT){ # Расчет значений
		if(!is_array($count =is_array($count) ?$count :[])){ mpre("Уведомление");
		}else if(!$_VALUE =rb($VALUE ,"learn" ,"id" ,0)){ mpre("ОШИБКА получения списка исходных значений");
		}else if(!array_map(function($value)use(&$vals ,$VALUE ,$data){ # Двоичное представление
			if(!is_numeric($val =get($data ,0 ,$value["name"]))){ mpre("ОШИБКА получения значения {$value["name"]}" ,$data);
			}else if(!is_numeric($bin =decbin($val))){ mpre("ОШИБКА получения двоичного значения");
			}else if(!is_numeric($val =str_pad($bin ,$value["len"] ,0 ,STR_PAD_LEFT))){ mpre("ОШИБКА добавления значения");
			}else if(!is_numeric($vals[0][$value["name"]] =$val)){ mpre("ОШИБКА добавления значения");
			}else{ //mpre("Расчет двоичного значения" ,$value ,$val);
			}return $val; } ,$_VALUE)){ mpre("ОШИБКА получения списка данных");
		}else if(!$_VALUE =rb($VALUE ,"learn" ,"id" ,1)){ mpre("ОШИБКА выборки обучаемых значений");
		}else if(!$DEC =array_map(function($value ,$VAL =[])use($BIT ,$VALUE ,$vals){ # Расчет значений
			if(!$_BIT =rb($BIT ,"value_id" ,"nn" ,$value["id"])){ mpre("Уведомление");
			}else if(!krsort($_BIT)){ mpre("ОШИБКА сортировки");
			}else if(!$LIMB =array_map(function($bit)use($vals ,$VALUE ,$BIT){ //while(!($pass =!$pass)){ # Побитовый расчет
				if(!$limb =rb("limb" ,"bit_id" ,"id" ,$bit["id"] ,$bit["limb_id"])){ mpre("ОШИБКА выборки нулевого результата" ,$bit);
				}else if(!$limb =call_user_func(function($pass =1)use($limb ,$vals ,$VALUE ,$BIT){ while(!($pass =!$pass)){ # Актуальный результат
					if(!$lvl =$limb ?$limb["lvl"] +1 :$_limb["lvl"] +1){ mpre("Уведомление");
					}else if(!is_numeric($val =call_user_func(function($val ="")use($limb ,$VALUE ,$BIT ,$vals){ # Расчет значения
						//if(!is_numeric($val =$limb["lvl"] %2)){ mpre("ОШИБКА расчета значения" ,$limb);
						if(!$_bit_id =get($limb ,"_bit_id")){ mpre("ОШИБКА идентификатор бита не задан");
						}else if(!$_bit =rb($BIT ,"id" ,$_bit_id)){ mpre("ОШИБКА выборки бита");
						}else if(!$value =rb($VALUE ,"id" ,$_bit["value_id"])){ mpre("ОШИБКА значение бита не задано");
						}else if(!is_numeric($bin =get($vals ,0 ,$value["name"]))){ mpre("ОШИБКА выборки двоичного значения");
						}else if(!is_numeric($pos =strlen($bin) -$_bit["nn"])){ mpre("ОШИБКА расчета позиции");
						}else if(!is_numeric($val =substr($bin ,$pos ,1))){ mpre("ОШИБКА расчета значения bin:{$bin} pos:{$pos}");
						}else{ //mpre("Расчетное значение limb_id:{$limb["id"]} {$val}");
						}return $val; }))){ mpre("ОШИБКА расчета значения");
					}else if(!$_limb =rb("limb" ,"bit_id" ,"limb_id" ,"lvl" ,"val" ,$limb["bit_id"] ,$limb["id"] ,$lvl ,$val)){ //mpre("Значение не найдено");
					}else if(!$limb =$_limb){ mpre("ОШИБКА установки текущего значения");
					}else if(!get($limb ,"_bit_id")){ //mpre("Крайний результат");
					}else if(!($pass =!$pass)){ mpre("ОШИБКА продолжения цикла");
					}else{ //mpre("Расчет дерева решений lvl:{$lvl} val:{$val}" ,$limb);
					}}return $limb; })){ mpre("ОШИБКА выборки актуального результата");
				}else{ //mpre("Расчет" ,$limb);
				}return $limb; } ,$_BIT)){ mpre("ОШИБКА расчета побиам");
			//}else if(!mpre("Список результатов" ,$LIMB)){ mpre("Уведомление");
			}else if(!$VAL =array_map(function($limb ,$val =""){ # Расчет значений
				if(!is_numeric($val =$limb["lvl"] %2)){ mpre("ОШИБКА получения значения результата");
				}else{ //mpre("Расчет результата");
				}return $val; } ,$LIMB)){ mpre("ОШИБКА расчета значений");
			}else if(!is_numeric($bin =implode("" ,$VAL))){ mpre("ОШИБКА расчета двоичного числа");
			}else if(!is_numeric($dec =bindec($bin))){ mpre("ОШИБКА расчета десятичного числа");
			}else if(!is_numeric($dec =call_user_func(function()use($dec ,$value ,$BIT ,$_BIT){ # Контроль максимального значения
				if($dec <=$value["max"]){ //mpre("Значение в пределах нормы");
				}else if(!$_BIT_ =rb($_BIT ,"nn")){ mpre("ОШИБКА форматирования битов по ключу");
				}else if(!krsort($_BIT_)){ mpre("ОШИБКА сортировки по ключу");
				}else if(!$bit =first($_BIT_)){ mpre("ОШИБКА Значение бита с макисмальным значением");
				}else if(!$dec =($_dec =$dec) -pow(2 ,$bit["nn"] -1)){ mpre("ОШИБКА расчета обновленного значения");
				}else{ mpre("Значение выше максимального {$_dec} {$dec}");
				}return $dec; }))){ mpre("ОШИБКА контроля максимального значения");
			}else{ //mpre("Расчет bin:{$bin}" ,$LIMB ,$VAL);
			}return $dec; } ,$_VALUE)){ mpre("ОШИБКА расчета значений");
		}else if(!$calc =array_intersect_key($data ,array_flip([1]))){ mpre("ОШИБКА получения старого значения");
		}else if(!$calc["calc"] =array_map(function($value)use($DEC){ return $DEC[$value["id"]]; } ,rb($_VALUE ,"name"))){ mpre("ОШИБКА формирований структуры ответа");
		}else if(call_user_func(function()use($calc ,$nn ,&$count){ # Отображение данных
			if(!is_numeric($in =$calc[1]["label"])){ mpre("ОШИБКА получения исходного значения");
			}else if(!is_numeric($out =$calc["calc"]["label"])){ mpre("ОШИБКА получения исходного значения");
			}else if(!$count["count"] =(array_key_exists("count" ,$count) ?$count["count"] +1 :1)){ mpre("ОШИБКА счетчика количества");
			}else if(!is_numeric($sm =($in ==$out ?1 :0))){ mpre("ОШИБКА совпадение");
			}else if(!is_numeric($count["sm"] =(array_key_exists("sm" ,$count) ?$count["sm"] +$sm :$sm))){ mpre("ОШИБКА счетчика количества");
			//}else if(!$count =isset($count) ?$count +$sm :$sm){ mpre("ОШИБКА счетчика количества");
			}else if(!is_numeric($proc =number_format($count["sm"] ?($count["sm"] /$count["count"] *100) :0 ,2 ,"." ,""))){ mpre("ОШИБКА расчета процента");
			}else if(!mpre("Расчет примера {$nn} {$in} => {$out} count:{$count["count"]} sum:{$count["sm"]} proc: {$proc}")){ mpre("Уведомление");
			}else{
			}})){ mpre("ОШИБКА отображения данных");
		}else{
		}return $calc; } ,array_keys($DATA) ,$DATA)){ mpre("ОШИБКА расчета значений");
	}else if(call_user_func(function()use($CALC){ # Отображение результата
		if(!$calc =get($GLOBALS ,"ARGV" ,"--calc")){ //mpre("Не отображаем результат на входном массиве");
		}else{ mpre("Расчетные данные" ,$CALC);
		}})){ mpre("ОШИБКА вывода расчетных данных");
	}else{ mpre("Расчет модели");
	}})){ mpre("ОШИБКА расчета");
}else if(call_user_func(function()use($microtime){ # Количество правил
	//if(!get($GLOBALS ,"ARGV" ,"epoch")){ //mpre("Не выводим");
	if(!$count =ql("SELECT COUNT(*) AS cnt FROM limb")){ mpre("ОШИБКА получения количества правил");
	}else if(!$level =ql("SELECT MAX(lvl) AS lvl FROM limb")){ mpre("ОШИБКА получения количества правил");
	}else if(!$db =get($GLOBALS ,"ARGV" ,"")){ mpre("ОШИБКА получения пути до БД");
	}else if(!$filesize =filesize($db)){ mpre("ОШИБКА получения количества правил");
	}else if(!$size =number_format($filesize / pow(2 ,30) ,2 ,"." ,"")){ mpre("ОШИБКА расчета размера файла БД");
	}else if(!is_numeric($time =number_format((microtime(true) -$microtime) /60 ,0 ,"." ,""))){ mpre("ОШИБКА расчета времени");
	}else{ mpre("БД {$size}ГБ Количество решений: " .get($count ,0 ,"cnt") ." максимальный уровень: " .get($level ,0 ,"lvl") ." время: {$time} минут");
	}})){ mpre("ОШИБКА расчета количества правил");
}else{ //mpre("Без консоли");
}