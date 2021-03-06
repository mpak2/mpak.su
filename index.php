<?

// ----------------------------------------------------------------------
// Жираф cms Content Management System
// Copyright (C) 2007-2018 by the mpak.
// (Link: http://mpak.su)
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 911 9842884
// ----------------------------------------------------------------------

if(!call_user_func(function(){ # Переменные окружения
    ini_set('display_errors', 1);
    date_default_timezone_set('Europe/Moscow');
    header('Content-Type: text/html; charset=utf-8');
    setlocale (LC_ALL, "Russian"); putenv("LANG=ru_RU");
    return error_reporting(E_ALL /*& ~E_NOTICE & ~E_STRICT*/);
  })){ mpre("ОШИБКА Установка системных переменных и уровня отчета ошибок");
}elseif(function_exists("mb_internal_encoding") && !mb_internal_encoding("UTF-8")){ mpre("Кодировки библиотеки корвертации");
}elseif(!$conf["db"]["open_basedir"] = call_user_func(function($open_basedir = null){ # Расчет пути до корня системы
		if(strpos(__DIR__, "phar://") === 0){ $open_basedir = implode("/", array_slice(explode("/", dirname(__DIR__)), 2)). "::". __DIR__; # Файл index.php внутри phar архива
		}elseif(file_exists($phar = __DIR__. DIRECTORY_SEPARATOR. "index.phar")){ $open_basedir = strtr(ini_get("open_basedir") ?: __DIR__, [':'=>'::']). "::phar://{$phar}"; # Не в phar
		}else{ $open_basedir = strtr(ini_get("open_basedir") ?: __DIR__, [':'=>'::']);
		} return $open_basedir;
	})){ print_r("ОШИБКА получения пути до корня системы");
}elseif(!isset($index) && ($index = './index.php') && file_exists($index)){ include $index; if($conf) die;
}elseif(!$mp_require_once = function($link){
		global $conf, $arg, $tpl;
		foreach(explode('::', $conf["db"]["open_basedir"]) as $k=>$v){
			if(!file_exists($file_name = "$v/$link")) continue;
			require_once $file_name; return $file_name;
		} return $file_name;
	}){ mpre("Функция подключения ресурсов");
}elseif(!$mp_require_once("include/config.php")){ mpre("ОШИБКА подключения файла конфигурации");
}elseif(!$mp_require_once("include/func.php")){ mpre("ОШИБКА подключения функций системы");
//}else if(true){ pre($conf["db"]["open_basedir"]);
}elseif(call_user_func(function(){ # Автоподгрузка классов
		function PHPClassAutoload($CN){
		//	if(!$dirname = __DIR__){ mpre("ОШИБКА установки текущей директории")
			foreach(explode("\\",$CN) as $class_name){
				//For example - include/mail/PHPMailerAutoload.php
				$file_project = mpopendir("include/class/$class_name/$class_name.php");
				$file_single  = mpopendir($file = "include/class/$class_name.php");
				$file_mail    = mpopendir("include/class/mail/class.".strtolower($class_name).".php");
				if($file_project){ include_once $file_project;
				}else if($file_single){ include_once $file_single;
				}elseif($file_mail){ include_once $file_mail;
				}elseif(in_array($class_name, array('Memcached'))){// mpre("Имя класса в массиве");
				}else{ mpre("Файл класса не найден {$file}");
				}
			}
		}

        if(version_compare(PHP_VERSION, '5.3.0', '>=')){
			spl_autoload_register('PHPClassAutoload', true, true);
		}else{
			spl_autoload_register('PHPClassAutoload');
		}
	})){ mpre("ОШИБКА установки пути к автозагрузке");
}elseif(call_user_func(function(){ # Отображение нефатальных ошибок только под администратором
		set_error_handler(function($errno, $errmsg, $filename, $linenum, $vars){
			global $conf;
			if(!$errortype = array(
					1   =>  "Ошибка",
					2   =>  "Предупреждение",
					4   =>  "Ошибка синтаксического анализа",
					8   =>  "Замечание",
					16  =>  "Ошибка ядра",
					32  =>  "Предупреждение ядра",
					64  =>  "Ошибка компиляции",
					128 =>  "Предупреждение компиляции",
					256 =>  "Ошибка пользователя",
					512 =>  "Предупреждение пользователя",
					1024=>  "Замечание пользователя",
					2048=> "Обратная совместимость",
				)){ mpre("ОШИБКА установки массива ошибок");
			}elseif(!$file_info = "{$filename}:{$linenum}"){ mpre("ОШИБКА получения информаци о файле и строке");
			}elseif(!$type_num = (($type = get($errortype, $errno)) ? "{$type} ({$errno})" : "Неустановленный тип ошибки ({$errno})")){ mpre("Тип ошибки не установлен");
			}elseif(!$pdo = (0 === strpos($errmsg, 'PDO::query():'))){ mpre($file_info, $type_num, $errmsg);
//			}elseif(!$_conn){ mpre("ОШИБКА определения соединения с базой данных");
			}elseif(!$conn = (is_array($c = $conf['db']['conn']) ? last($c) : $c)){ mpre("ОШИБКА получения соединения");
			}elseif(!$info = last($conf['db']['sql'])){ mpre("ОШИБКА получения запроса", $conf['db']);
			}elseif(!$error = (last($conn->errorInfo()) ?: $errmsg)){ mpre("Текст ошибки не установлен", $info['sql']);
			}else{ mpre($file_info, $type_num, $error, $info['sql']);
				mpevent($type, $error, ["Файл"=>$file_info, "Номер ошибки"=>$type_num, "Ошибка"=>$error, "Запрос"=>$info['sql']]);
			}
		});
	})){ mpre("ОШИБКА устанвоки режима вывода ошибок");
}else if(call_user_func(function() use(&$conf){ // Запуск скрипта из консоли php -f index.php /pages:index/2 - Путь до скрипта в файловой системе
		if(empty($argv)){// mpre("Только при запуске из консоли");
		}elseif(count($argv)>1){ mpre("Аргументы командной строки не указаны пример - php -f index.php /pages:index/2");
		}elseif(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Установка прав администратора");
		}else if(!conn()){ die(!mpre("ОШИБКА соединения с базой данных"));
		}elseif(!$link = get($argv, 1)){ die(!mpre("Не указана ссылка консольной утилиты"));
		}elseif(!$uri = get($match, 1)){ die(!mpre("ОШИБКА получения ссылки из параметров регулярного выржения"));
		}elseif(!chdir(__DIR__)){ mpre("ОШИБКА Установки текущей директории");
		}elseif(!$get = mpgt($uri)){ mpre("ОШИБКА получения параметров адресной строки");
		}elseif(!$m = get($get, 'm')){ mpre("ОШИБКА получения параметров адреса");
		}elseif(!list($mode[0], $mode[1]) = each($m)){ mpre("ОШИБКА получения имени модуля и исполняемого файла");
		}elseif(empty($mode[1]) && (!$mode[1] = "index")){ mpre("Используем имя файла index если не указан");
		}elseif(!is_array($_REQUEST = $_GET)){ mpre("ОШИБКА добавления параметров к реквесту");
		}elseif(!$arg =['modpath' => $mode[0], 'modname' => $mode[0], 'fn' => $mode[1], 'fe' => null, 'admin_access' => 5]){ mpre("Формирование аргументов страницы");
		}elseif(!$mode = "modules/".implode("/",$mode)){ mpre("ОШИБКА собираем путь к модулю");
		}else{ exit(inc($mode,['arg'=>$arg])); }
	})){ mpre("Консольный режим запуска страниц php -f index.php /pages:index/2");
}elseif(!$conf['settings']['http_host'] = call_user_func(function(){
		if(!$http_host = mb_strtolower($_SERVER['HTTP_HOST'], 'UTF-8')){ mpre("Ошибка определения хоста");
		}elseif(!$http_host = (strpos($http_host, "www.") === 0 ? substr($http_host, 4) : $http_host)){ mpre("Удаление www из начала страницы");
		}elseif(!$http_host = idn_to_utf8($http_host, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46)){ mpre("Руссификация хоста");
		}elseif(!$http_host = preg_replace("/[.]+$/", "", $http_host)){ mpre("Ошибка выризания точек в конце хоста. По стандартам можно ставить точку в конце адреса и это будет работать");
		}else{ return $http_host; }
	})){ mpre("ОШИБКА получения имени хоста");
}elseif(!$conf['settings']['microtime'] = microtime(true)){ mpre("Фиксация начала запуска скрипта");
}elseif($cache = call_user_func(function() use($conf){ # Проверяем существует ли кеш в базе. Если есть - выдаем закешированную страницу
		if(get($conf, 'settings', 'users_cashe_disacled')){ mpre("Кеш отключен");
		}elseif($_POST){ //exit(pre($_POST));
		}elseif(!$cache = cache()){ //pre("Кеш не найден в базе");
		}else if(call_user_func(function() use($cache){ // Кешированные данные
				ignore_user_abort(true);
				set_time_limit(0);
				ob_start();
					echo $cache["content"];
					header('Content-Encoding: gzip');
					header('Connection: close');
					header('Content-Length: '.ob_get_length());
				ob_end_flush();
				ob_flush();
				flush();
			})){ pre("ОШИБКА вывода кешированных данных");
		}else if(time() > $cache["time"]){ //pre("Обновляем страницу ", array_diff_key($cache, array_flip(["content"])));
		}else{ return $cache;
		}
	})){ exit(0); //mpre("ОШИБКА отображение сохраененного ранее кеша"); 
}elseif(!$conf['db']['conn'] = call_user_func(function($conn = null) use($conf){ # Подключение к базе данных
		if(!$conn = conn()){ pre("ОШИБКА подключения к базе данных");
		}elseif(!empty($conf['db']['error'])){ pre("ОШИБКА подключения к базе данных", $conf['db']['error']);
		}else{// mpre("Соединение с базой данных прошло успешно", $conn);
		} return $conn;
	}, $conf['db']['conn'])){ pre("ОШИБКА не удалось подключиться к базе данных");
}elseif(!$conf['settings'] += array_column(rb("settings-"), "value", "name")){ mpre("ОШИБКА загрузки свойств сайта");
}elseif(!$_SERVER["REQUEST_URI"] = call_user_func(function($REUEST_URI) use($conf){ # Изменяем адрес внутри вложенно директории
		if(!$PATH = explode("/", $REUEST_URI)){ pre("ОШИБКА разбора директорий");
		}else if("admin" == last($PATH)){ $REUEST_URI = "/admin";
		}else if(!$subdir = get($conf, "settings", "subdir")){// pre("Поддиректория файловой системы не задана");
		}else if(!$SUB = explode("/", $subdir)){ pre("ОШИБКА получения структуры субдиректорий");
		}else if(array_slice($PATH, 0, count($SUB)) != $SUB){// pre("Структуры адреса и субдиректорий не совпадают", array_slice($PATH, 0, count($SUB)), $SUB);
		}else if(!$REUEST_URI = implode("/", array_slice($PATH, 1))){// $REUEST_URI = "/";// pre("ОШИБКА получения адреса без первой директории");
		}else{// die(pre("REUEST_URI", $REUEST_URI));
		} return $REUEST_URI;
	}, $_SERVER["REQUEST_URI"])){ mpre("ОШИБКА установки адреса");
}elseif(!$tables = call_user_func(function($tables = []) use($conf){ # Определяем режим установки по наличию таблиы в базе данных и свойствам администратора
		if(array_key_exists('null', $_GET)){ return true; pre("Пропускаем проверку списка таблиц на ресурсах");
		}elseif(!$tables = tables()){ mpre("База данных не пуста");
		}elseif($conf["db"]["type"] != "sqlite"){ //pre("Для mysql установка только с пустой базой", $conf["db"]["type"]);
		}elseif(!get($conf, "settings", "admin_usr")){ return !mpre("Не установлен администратор");
		}else{ //mpre("Список таблиц базы", $tables);
		} return $tables;
	})){ exit(inc('include/install.php')); # Запускаем процесс установки
}elseif(!is_array($_REQUEST += $_GET += mpgt($_SERVER['REQUEST_URI']))){ mpre("ОШИБКА получения параметров адресной строки");
}elseif(!$sess = users_sess()){// pre("Добавляем сессию");
}elseif(!$guest = get($conf, 'settings', 'default_usr')){ mpre("Имя пользователя гость не указано");
}elseif(!is_array($conf['user'] = (rb('users-', 'id', $sess['uid']) ?: rb('users-', 'name', "[{$guest}]")))){ pre("ОШИБКА выборки пользователя");
}elseif(!$conf['user'] += ['uid'=>(($sess['uid'] > 0) ? get($conf, 'user', 'id') : -$sess['id']), 'sess'=>$sess, 'gid'=>[]]){ pre("ОШИБКА сохранения сессии в системных переменных");
}elseif(!$conf['settings'] += call_user_func(function($seo = []) use($conf){ # Устанавливаем свойства главной страницы
		if(array_key_exists("m", $_GET)){// mpre("Не главная страница");
		}elseif(!$seo_index = rb("seo-index", "name", "[/]")){ /*&& array_key_exists("themes_index", $redirect)*/
		}elseif(!is_array($seo_location = get($seo_index, "location_id") ? rb("seo-location", "id", $seo_index['location_id']) : [])){ mpre("ОШИБКА выборки внутреннего адреса");
		}elseif(!$canonical = ($seo_location ? $seo_location["name"] : $conf['settings']['start_mod'])){ mpre("ОШИБКА определения внутреннего адреса главной страницы");
		}elseif($_REQUEST += $_GET = mpgt($canonical)){ mpre("Установка входящих параметров страницы");
		}elseif(!$seo["title"] = get($seo_index, 'title') ?: $conf['settings']['title']){ mpre("ОШИБКА заголовок старницы не установлен");
		}elseif(!$seo["description"] = get($seo_index, 'description') ?: $conf['settings']['description']){ mpre("ОШИБКА описание старницы не установлено");
		}elseif(!$seo["keywords"] = get($seo_index, 'keywords') ?: $conf['settings']['keywords']){ mpre("ОШИБКА ключевики старницы не установлены");
		}else{// mpre("Свойства страницы", $seo);
		} return $seo;
	})){ mpre("ОШИБКА установки свойств главной страницы");
}else if(call_user_func(function() use($conf){ // Отключение авторизации на сайте
		if(!array_key_exists('logoff', $_GET)){ //pre("Признак выхода не найден");
		}else if(!qw("UPDATE {$conf['db']['prefix']}users_sess SET sess = '!". mpquot($sess['sess']). "' WHERE id=". (int)$sess['id'], 'Выход пользователя')){ pre("ОШИБКА удаления авторизации из таблицы сессий");
		//}else if(!setcookie("{$conf['db']['prefix']}modified_since", "", 0, "/")){ mpre("Установка флага запрета модификации");
		}else if(!setcookie('sess', null, -1, '/')){ pre("ОШИБКА удаления кукисы сессии");
		}else if(!$users_logoff_location = get($conf, 'settings', 'users_logoff_location') ?: $_SERVER['HTTP_REFERER']){ pre("ОШИБКА получения страницы перенаправления");
		}else{ //pre("Страница перенаправления", $users_logoff_location); //pre("Выход");
			exit(header("Location: {$users_logoff_location}"));
			//qw($sql = "DELETE FROM {$conf['db']['prefix']}users_sess WHERE last_time < ".(time() - $conf['settings']['sess_time']), 'Удаление старых сессий');
		}
	})){ mpre("ОШИБКА выключения сессии");
//}else if(true){ pre($_POST);
}elseif(!is_array($conf['user'] = call_user_func(function($user = []) use($sess){ # Авторизация
		if(!$_POST){// pre("Сессия выключена");
		}elseif(!$_POST || (get($_POST, 'reg') != 'Аутентификация')){// pre("Нет запроса на аутентификацию");
		}elseif(!strlen($_POST['name'])){ pre("Имя не задано");
		}elseif(!strlen($_POST['pass'])){ pre("Пароль не задан");
		}elseif(!$mphash = mphash($_POST['name'], $_POST['pass'])){ pre("Ошибка получения хэша пароля");
		}elseif(!$users_type = rb("users-type", "name", $w = "[Логин]")){ pre("Тип авторизации не найден {$w}");
		}elseif(!$user = rb("users-", "type_id", "name", $users_type["id"], $n = "[". mpquot($_POST['name']). "]")){ pre("ОШИБКА пользователь `{$_POST['name']}` не найден");
		}elseif($user["pass"] != $mphash){ sleep(1); pre("Не верный пароль `{$user['name']}`");
		}elseif(!$sess = fk("users-sess", ['id'=>$sess['id']], null, ['uid'=>$user['id']])){ pre("Ошибка редактирования сессии", $user);
		}elseif(!$user = fk("users-", ['id'=>$user['id']], null, ['last_time'=>time()])){ pre("Ошибка установки времени входа пользователю");
		}elseif(!$sess["uid"] = $user["id"]){ pre("ОШИБКА установки пользователя сессии");
		}elseif(!$user['sess'] = $sess){ pre("ОШИБКА сохранения сессии в системных переменных");
		}else{ exit(header("Location: {$_SERVER['REQUEST_URI']}")); // pre("Успешная авторизация");
		} return $user;
	}, $conf['user']))){ mpre("ОШИБКА авторизации"); // exit(header("Location: {$_SERVER['REQUEST_URI']}")); # При авторизации обновляем страницу (избавляемся от пост запроса)
}elseif(!is_array($conf['user'] = call_user_func(function($user) use($conf){ # Получаем свойства пользователя
		if(!$sess = get($user, "sess")){ pre("ОШИБКА получения сессии пользователя");
		}elseif(0 >= $sess["uid"]){// pre("Пользователь является гостем");
		}elseif(!$user = ql($sql = "SELECT *, `id` AS `uid`, `name` AS `uname` FROM `{$conf['db']['prefix']}users` WHERE `id`=". (int)$sess['uid'], 0)){ pre("ОШИБКА выборки пользователя сессии из базы");
		}elseif(!$user['uid'] = ($user['name'] == $conf['settings']['default_usr'] ? -$sess['id'] : $user['uid'])){ pre("Устанавливаем в идентификатор пользователя номер сессии с минусом");
		}elseif(!$user['sess'] = $sess){ pre("ОШИБКА восстановления сессии");
		}elseif(!$USERS_MEM = rb("users-mem", "uid", "id", $user['uid'])){ pre("ОШИБКА получения списка членства пользователя в группах");
		}elseif(!$USERS_GRP = rb("users-grp", "id", "id", rb($USERS_MEM, "grp_id"))){ pre("ОШИБКА получения списка групп пользователя");
		//}elseif(!$USERS_GRP += ((array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false) ? rb("users-grp", "name", "id", "[Администратор]") : [])){ pre("ОШИБКА устанвоки прав доступа группы Администратор");
		}elseif(!$user['gid'] = array_column($USERS_GRP, "name", "id")){ pre("ОШИБКА пользователь не состоит в группах");
		}else{ //pre($conf['user']); // pre("Авторизованный пользователь", $user);
		} return $user;
	}, $conf['user']))){ pre("ОШИБКА получения текущего пользователя");
}elseif(!$conf['modules'] = call_user_func(function() use($conf){ # Выборка свойств модулей и их свойств
		if(!$MODULES_INDEX = rb("modules-index")){ mpre("ОШИБКА выборки списка свойств сайта");
		}elseif(!$_MODULES = array_map(function($modules_index) use($conf){
				if(!$modules = $modules_index){ pre("ОШИБКА установки свойств модуля");
				}elseif(!$modules["modname"] = mb_strtolower((get($modules, 'name') ?: $modules['folder']), 'UTF-8')){ pre("Приведение к формату имени хоста");
				}elseif(!is_numeric($modules['admin_access'] = ((array_search(get($conf, 'user', 'uname'), explode(',', $conf['settings']['admin_usr'])) !== false) ? 5 : (int)$modules_index['admin_access']))){ pre("ОШИБКА установки прав доступа к разделу", $modules_index);
				}else{ return $modules; }
			}, $MODULES_INDEX)){ mpre("ОШИБКА получения свойств модулей");
		}elseif(!$MODULES = $_MODULES + rb($_MODULES, "folder") + rb($_MODULES, "modname")){ mpre("Варианты доступа к свойствам раздела");
		}else{// pre("Свойства модулей", $MODULES_INDEX, $MODULES);
		} return $MODULES;
	})){ pre("ОШИБКА выборки свойств сайта");
}elseif(call_user_func(function() use($conf){ # Устанавливаем адрес главной страницы
		if(!$href = ("/" == $_SERVER["REQUEST_URI"] ? get($conf, 'settings', 'start_mod') : $_SERVER["REQUEST_URI"])){ pre("Стартовая страница не задана");
		}elseif((strpos($href, "http://") === 0) || (strpos($href, "//") === 0)){ exit(header("Location: {$conf['settings']['start_mod']}")); // mpre("Формат адреса для перенаправления не совпал");
		}elseif(!is_array($mpgt = mpgt($href))){ mpre("ОШИБКА получения параметров адреса");
		}else{ $_GET += $mpgt; }
	})){ mpre("ОШИБКА перенаправления на другой сайт");
}elseif(call_user_func(function($conf){ # Если прописана внутренняя страница и перенаправлениее ее на внешнюю делаем переход и отображаем об этом информацию
    if(!$seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$_SERVER['REQUEST_URI']}]")){// mpre("Адрес внутренней страници в админке не задан");
    }elseif(!get($seo_location, 'location_status_id')){// mpre("Статус перенаправления не установлен");
    }elseif(!$seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id'])){ mpre("ОШИБКА выборки статуса перенаправления");
    }elseif(!get($seo_location, "index_id")){// mpre("Внешний адрес для перенаправления не установлен");
    }elseif(get($conf, 'settings', 'seo_meta_hidden')){// mpre("Скрываем сообщения для администраторов");
    }elseif(!$seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location['index_id'])){ mpre("ОШИБКА выборки адреса для перенаправления");
    }elseif(empty(get($conf, 'settings', 'seo_meta_hidden')) && ($gid = get($conf, 'user', 'gid')) && array_search("Администратор", $gid)){ mpre("Перенаправляем страницу на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
    }else{ header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
      exit(header("Location: {$seo_index['name']}"));
    }
  }, $conf)){ mpre("Перенаправление страницы по внутреннему адерсу");
}elseif(!$conf["settings"] += call_user_func(function($settings = []) use($conf){ # Определение исполняемых файлов сайта
		if(!(array_key_exists("m", $_GET) ? (list($m) = array_keys($_GET['m'])) : "pages")){ mpre("Модуль не установлен");
		}elseif((!$settings['modpath'] = ((!empty($m) && array_key_exists($m, $conf['modules'])) ? $conf['modules'][ $m ]['folder'] : "")) &0){ mpre("Модуль не определен");
		}elseif(!$settings['fn'] = ((!empty($f) && ($f != "index")) ? $f : "index")){ mpre("Страница не определена");
		}else{// mpre("Исполняемые раздел и файл", $settings);
		} return $settings;
	})){ mpre("ОШИБКА установки модуля и исполняемого файла");
}elseif(!is_array($conf['themes']['index'] = call_user_func(function($http_host, $themes_index = []) use($conf){ # Выборка хоста и добавление в случае необходимости
		if(!get($conf, "settings", "themes_index")){// mpre("Односайтовый режим");
		}elseif($themes_index = rb("themes-index", "name", "[$http_host]")){// mpre("Сайт найден в списке");
		}else{ mpre("Сайт в списке хостов не найден <a href='/themes:admin/r:themes-index'>{$http_host}</a>");
		} return $themes_index;
	}, $conf["settings"]["http_host"]))){ mpre("ОШИБКА выборки хоста сайта");
}elseif(!$conf['settings']['theme'] = call_user_func(function($theme) use($conf){ # Установка темы сайта
		if("admin" == last(explode("/", $_SERVER['REQUEST_URI']))){ $theme = "zhiraf";// mpre("Админ раздел внутри директории");
		}elseif(array_filter(array_flip($_GET['m']), function($v){ return ("admin" == $v); })){ $theme = "zhiraf"; // mpre("Мдуль админка");
		}elseif(array_filter($_GET['m'], function($fn){ return (0 === strpos($fn, "admin")); })){ $theme = "zhiraf"; // mpre("Страница админка");
		}elseif(get($_GET, "theme")){ $theme = $_GET["theme"]; //mpre("Ошибка установки темы из адреса");
		}elseif(get($conf, 'user', 'theme')){ $theme = $conf['user']['theme'];
		}elseif(get($conf, 'settings', $w = "theme/{$conf['settings']['modpath']}:*")){ $theme = get($conf, 'settings', $w);
		}elseif(get($conf, 'themes', 'index', 'theme')){ $theme = $conf['themes']['index']['theme'];
		}elseif(get($conf, 'settings', $w = "theme/{$conf['settings']['modpath']}:{$conf['settings']['fn']}")){ $theme = get($conf, 'settings', $w);
		}elseif(get($conf, 'settings', $w = "theme/*:{$conf['settings']['fn']}")){ $theme = get($conf, 'settings', $w);
		}elseif($_theme = call_user_func(function($theme = null) use($conf){
                if(!get($conf, 'themes', 'index')){// mpre("Хост не установлен");
                }else if(!$index_theme_id = get($conf, 'themes', 'index', "index_theme_id")){ mpre("Хост не связан с <a href='/themes:admin/r:themes-index_theme'>таблицей тем</a>");
                }else if(!$themes_index_theme = rb("themes-index_theme", "id", $index_theme_id)){ mpre("Тема не найдена в таблице тем");
                }else if(get($themes_index_theme, $n = "theme")){ $theme = $themes_index_theme[$n];
                }else if(get($themes_index_theme, $n = "name")){ $theme = $themes_index_theme[$n];
                }else{ mpre("ОШИБКА таблица тем не содержит полей с темой");
                } return $theme;
            })){ $theme = $_theme;// mpre("Установка темы из связанной таблицы");
		}else{// mpre("Определена тема сайта как", $theme);
		} return basename($theme);
	}, $conf['settings']['theme'])){ pre("ОШИБКА определения темы сайта");
}elseif(!$conf["db"]["open_basedir"] = call_user_func(function($open_basedir)use($conf){
		if(!$conf['themes']['index']){ // mpre("Дополнительная директория только для мультисайтового режима");
		}elseif(!$open_basedir = "/var/www/html/themes/{$conf['settings']['theme']}::{$open_basedir}"){  mpre("Ошибка перезагрузки директории мультихоста");
		}else{ // mpre("Меняем директорию для мультихоста");
		} return $open_basedir;
	}, $conf["db"]["open_basedir"])){ mpre("Ошибка модификации пути до корневой директории");
}elseif(inc("include/init.php", array("arg"=>array("modpath"=>"admin", "fn"=>"init"), "content"=>($conf["content"] = "")))){ mpre("Ошибка подключения файла инициализации");
}elseif(!$url = first(explode("?", urldecode($_SERVER['REQUEST_URI'])))){ mpre("Ошибка формирования исходного адреса страницы");
}elseif(call_user_func(function() use($conf){
		if(!$url = $_SERVER["REQUEST_URI"]){ pre("ОШИБКА получения URI адреса");
		}elseif(strlen($url) <= 1){// mpre("Похоже на главную страницу");
		}elseif(substr($url, -1) != "/"){// mpre("Страница оканчивается не на слеш");
//		}elseif(first(array_keys(get($get, 'm'))) == "webdav"){// mpre("Раздел исключений адресации со слешами");
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("Адрес заканчивается на правый слеш - перенаправляем без слеша <a href='". substr($url, 0, -1). "'>". substr($url, 0, -1). "</a>");
		}else{ exit(header("Location: ". substr($url, 0, -1)));
		}
	})){ pre("ОШИБКА обработки адресов заканчивающихся на правый слеш");
}elseif(call_user_func(function() use($url){
		if(!array_search($url, [1=>"/robots.txt", "/sitemap.xml"])){// mpre("Адрес не метафайла");
		}elseif(!$fn = first(explode(".", basename($url)))){ mpre("ОШИБКА установки имени файла");
		}elseif(!$filename = "modules/seo/{$fn}.php"){ mpre("ОШИБКА составления имени файла");
		}else{// pre($filename);
			die(inc($filename, ["arg"=>array("modpath"=>"seo", "fn"=>$url)]));
		}
	})){ mpre("ОШИБКА запуска файлов robots.txt");
}elseif(!$url = preg_replace("#\/(стр|p)\:[0-9]+#", "", $url)){ mpre("Ошибка избавления от номера страниц в адресе");
}elseif(!is_array($seo_index = rb("seo-index", "name", array_flip([$url])))){ mpre("ОШИБКА получения внешнего адреса страницы");
}elseif(!is_array($seo_index_themes = ($seo_index && get($conf, 'themes', 'index') ? rb("seo-index_themes", "themes_index", "index_id", $conf['themes']['index']['id'], $seo_index["id"]) : []))){ mpre("ОШИБКА выборки адресации");
}else if(!is_array($seo_location = call_user_func(function($seo_location = []) use($conf, $seo_index_themes, $seo_index, $url){ // Запись внутреннего адреса
		if(get($seo_index, "location_id")){ $seo_location = rb("seo-location", "id", $seo_index["location_id"]);// mpre("Односайтовый режим");
		}else if(empty($seo_index_themes)){ $seo_location = rb("seo-location", "name", "[{$url}]");// mpre("Адрес не установлен");
		}else{ $seo_location = rb("seo-location", "id", $seo_index_themes["location_id"]);
		} return $seo_location;
	}))){ mpre("ОШИБКА получения внутреннего адреса");
}elseif(call_user_func(function() use($conf, $seo_index, $seo_location, $seo_index_themes){ # Отображаем администратору или переходим по перенаправлению
		if($seo_index){// mpre("Для перенаправления не найден внешний адрес");
		}elseif(empty($seo_location)){// mpre("Для перенправления не утсановлен внутренний адрес");
		}elseif(!$themes_index = get($conf, "themes", "index")){ mpre("Хост не указан");
		}elseif(!$seo_location_themes = rb("seo-location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index["id"])){// mpre("Перенаправление не установлено");
		}elseif(!$_seo_index = rb("seo-index", "id", $seo_location_themes["index_id"])){ mpre("ОШИБКА выборки страницы перенаправления");
		}elseif(!$seo_index_themes){ mpre("Ошибка перенаправления на <a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>несуществующую страницу</a>");
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("<a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>Перенаправление</a> с внутреннего на внешний адрес <a href='{$_seo_index['name']}'>{$_seo_index['name']}</a>");
		}else{ header("HTTP/1.1 301 Moved Permanently");
			exit(header("Location: {$_seo_index['name']}"));
		}
	})){ mpre("ОШИБКА расчета перенаправления");
}elseif(!$conf['settings']['title'] = call_user_func(function($title) use($seo_index_themes, $seo_index){ // Получаем заголовок сайта
		if(get($seo_index_themes, 'title')){ $title = $seo_index_themes['title'];// mpre("Заголовок мультисайта");
		}else if(get($seo_index, 'title')){ $title = $seo_index['title'];// mpre("Заголовок внешнего адреса");
		}else{// mpre("Заголовок сайта не установлен в СЕО разделе - оставляем дефолтным");
		} return $title;
	}, $conf['settings']['title'])){ pre("ОШИБКА получения заголовка сайта");
}elseif(!is_string($conf['settings']['description'] = call_user_func(function($description = "") use($seo_index_themes, $seo_index){ // Получаем описание сайта
		if(get($seo_index_themes, 'description')){ $description = $seo_index_themes['description'];// mpre("Описание мультисайта");
		}else if(get($seo_index, 'description')){ $description = $seo_index['description'];// mpre("Описание внешнего адреса");
		}else{// mpre("Описание сайта не установлен в СЕО разделе - оставляем дефолтным");
		} return $description;
	}, (get($conf, 'settings', 'description') ?: "")))){ pre("ОШИБКА получения описания сайта");
}elseif(call_user_func(function() use($conf, $seo_index, $seo_index_themes){ # Установка мета информации сайта
		if(empty($seo_index_themes)){// mpre("Адресация не задана");
//		}elseif(!$conf['settings']['title'] = (get($seo_index_themes, 'title') ? htmlspecialchars($seo_index_themes['title']) : $conf['settings']['title'])){ mpre("ОШИБКА установки мета заголовка");
//		}elseif(!$conf['settings']['description'] = (get($seo_index_themes, 'description') ? htmlspecialchars($seo_index_themes['description']) : $conf['settings']['description'])){ mpre("ОШИБКА установки мета описания");
		}elseif(!$conf['settings']['keywords'] = (get($seo_index_themes, 'keywords') ? htmlspecialchars($seo_index_themes['keywords']) : $conf['settings']['keywords'])){ mpre("ОШИБКА установки <a href='/settings:admin/r:settings-?edit&where[name]=keywords'>мета ключевиков</a>");
		}else{// mpre("Адресация страницы", $seo_index_themes);
		}
	})){ pre("ОШИБКА перенаправления сайта");
}elseif(!$conf["settings"]["canonical"] = call_user_func(function($canonical = null) use($conf, $seo_index, $seo_location, $seo_index_themes){ # Расчет и установка каноникала
		if(!$canonical = ("/" == $_SERVER["REQUEST_URI"] ? $conf["settings"]["start_mod"] : $_SERVER["REQUEST_URI"])){ mpre("ОШИБКА нахождения адреса текущей страницы");
//		}elseif(true){ mpre($canonical);
		}elseif(!get($conf, 'themes', 'index')){// mpre("Односайтовый режим");
		}elseif(!$seo_index_themes){ //mpre("Битый адрес на странице");
		}elseif(!is_array($canonical = ($seo_location + $seo_index_themes))){ mpre("ОШИБКА формирования массива с каноникалам");
		}elseif(!is_array($canonical += ($seo_index_themes ? ["index_themes_id"=>$seo_index_themes['id']] : []))){ mpre("ОШИБКА добавления в мета информацию ссылки на заголовки");
		}else{// pre($canonical, $href, $get, $_GET, $mod, $seo_location, $url);
		} return $canonical;
	})){ mpre("ОШИБКА установки канонической мета информации");
}elseif(call_user_func(function() use($conf, $seo_location, $seo_index){ // Устанавливаем параметры запроса
		if(!is_string($href = get($seo_location, "name") ?: $conf["settings"]["canonical"])){ mpre("ОШИБКА расчета адреса");
//		}else if(true){ mpre($href, $seo_location, $seo_index);
		}elseif(!$get = mpgt($href, $_GET)){ mpre("ОШИБКА получения параметров адресной строки");
		}elseif(!$_GET = ($get + $_GET)){ mpre("ОШИБКА добавления параметров адресной строки");
		}else{// mpre("ГЕТ Параметры", $get);
		}
	})){ mpre("ОШИБКА установки параметров запроса");
}elseif(!is_array($conf["settings"] += call_user_func(function($modules = []) use($conf){
		foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index_gaccess ORDER BY sort", 'Права доступа группы к модулю', function($error) use($conf){
			if(strpos($error, "Unknown column 'sort'")){
				qw(mpre("ALTER TABLE `mp_modules_index_gaccess` ADD `sort` int(11) NOT NULL  COMMENT '' AFTER `id`"));
				qw(mpre("UPDATE `mp_modules_index_gaccess` SET sort=id"));
			}elseif(strpos($error, "doesn't exist")){
				qw(mpre("ALTER TABLE {$conf['db']['prefix']}modules_gaccess RENAME {$conf['db']['prefix']}modules_index_gaccess"));
			}
		})) as $k=>$v){
			if(array_key_exists($v['gid'], $conf['user']['gid']) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false){
				$modules[ $v['mid'] ]['admin_access'] = $v['admin_access'];
			}
		}

		foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index_uaccess ORDER BY uid", 'Права доступа пользователя к модулю', function($error) use($conf){
			if(strpos($error, "doesn't exist")){ qw(mpre("ALTER TABLE {$conf['db']['prefix']}modules_uaccess RENAME {$conf['db']['prefix']}modules_index_uaccess"));
			}else{ pre("Неустановленная ошибка при выборки прав доступа пользователей"); }
		})) as $k=>$v){
			if ($conf['user']['uid'] == $v['uid'] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false){
				$modules[ $v['mid'] ]['admin_access'] = $v['admin_access'];
			}
		} return $modules;
	}))){ mpre("ОШИБКА загрузки прав доступа к разделам и блокам");
}elseif(!is_array($zblocks = call_user_func(function() use(&$conf){
		if(!is_null(get($_GET, 'm', 'blocks')) && ($_GET['m']['blocks'] == "index") && !get($_GET, 'id')){// pre($_GET);
			$conf["content"] = modules($conf["content"]); $zblocks = [];
		}elseif(isset($_GET['m']['sql'])){
			$zblocks = blocks(); $conf["content"] = modules($conf["content"]);
		}else{
			$conf["content"] = modules($conf["content"]); $zblocks = blocks();
		} return $zblocks;
	})) and !get($conf,"deny")){ mpre("Ошибка установки порядка следования расчетов блоков");
}else if($content = call_user_func(function($content){
		if(!array_key_exists('null', $_GET)){ //mpre("Не ресурс");
		}else{ cache($content);
			return $content;
		}
	}, $conf["content"])){ echo $content;
}elseif(!$tc = call_user_func(function() use($conf){ # Содержимое шаблона
		if(!$ind = (get($_GET, 'index') ?: (get($conf, 'settings', 'index') ?: "index"))){ mpre("Ошибка определения имени главного файла");
		}elseif(!$file = mpopendir($f = "themes/{$conf['settings']['theme']}/{$ind}.html")){ mpre("Содержимоге файла не найдено", $f);
//		}elseif(true){ die($file);
		}elseif(!get($conf, 'settings', 'theme_exec') && (!$tc = file_get_contents($file))){ mpre("Ошибка получения содержимого файла шаблона");
		}else{// mpre("Запускаем шаблон на исполнение");
			ob_start(); inc($f); $tc = ob_get_clean();
		} return $tc;
	})){ mpre("ОШИБКА содржимого шаблона");
}else if(call_user_func(function() use($conf){ # Подключение LESS
		if(!get($conf, "settings", "themes_less_compile")){ // Параметр включения less
		}elseif(!$teme_config = mpopendir($json = "themes/{$conf['settings']['theme']}/config.json")){ mpre("Конфиг LESS не найден", $json);
		}elseif(!$teme_config = file_get_contents($teme_config)){ mpre("ОШИБКА подключения конфига LESS", $teme_config);
		}elseif(!isJSON($teme_config)){ mpre("Содержимое файла не определено как JSON", $teme_config);
		}else if(!$teme_config = json_decode($teme_config,true)){ mpre("ОШИБКА разбора содержимого конфига LESS", $teme_config);
		}else{
			if(get($teme_config,'less_compile')){
				MpLessCompile(mpopendir("themes/{$conf['settings']['theme']}/"));
			}
			if(get($teme_config,'js_auto_mini')){
				MpJsAutoMini(mpopendir("themes/{$conf['settings']['theme']}/"));
			} ob_start(); inc($f); $tc = ob_get_clean();
		}
	})){ mpre("ОШИБКА подключения less");
}elseif(!$conf['settings']['microtime'] = substr(microtime(true)-$conf['settings']['microtime'], 0, 8)){ mpre("Ошибка расчета времени генерирования страницы");
}elseif(!$content = call_user_func(function($content) use($conf, $tc, $zblocks){ # Установка параметров свойств сайта
		if(!$content = str_replace('<!-- [modules] -->', $content, $tc)){ mpre("Ошибка замены содержимого модуля");
		}elseif(!$content = strtr($content, (array)$zblocks)){ mpre("Ошибка установки содержимоого блоков");
		}elseif(!$content = array_key_exists("null", $_GET) ? $content : strtr($content, mpzam($conf['settings'], "settings", "<!-- [", "] -->"))){ mpre("Ошибка установки переменных в текст");
		}else{// mpre("Результат форматирования содержимого страницы", $content);
		} return $content;
	}, $conf["content"])){ mpre("ОШИБКА форматирования содержимого страницы");
}elseif(!$content = call_user_func(function() use($conf, $content){ # Если не запрещено перед выдачей кешируем страницу
		if(get($conf, 'settings', 'users_cashe_disacled')){ mpre("Кеширование запрещено");
		}else{// mpre("Кеширование старницы");
			cache($content);
		} return $content;
	})){ mpre("Ошибка кеширования содержимого страницы");
}else{ //header("Cache-Control:no-cache, must-revalidate;");
	//pre("test");
	echo $content;
}
