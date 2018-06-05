<?

// ----------------------------------------------------------------------
// Жираф cms Content Management System
// Copyright (C) 2007-2010 by the mpak.
// (Link: http://mpak.su)
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 911 9842884
// ----------------------------------------------------------------------

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if(!call_user_func(function(){
    ini_set('display_errors', 1);
    date_default_timezone_set('Europe/Moscow');
    header('Content-Type: text/html; charset=utf-8');
    header("Cache-Control:no-cache, must-revalidate;");
    setlocale (LC_ALL, "Russian"); putenv("LANG=ru_RU");
    return error_reporting(E_ALL /*& ~E_NOTICE & ~E_STRICT*/);
  })){ mpre("Установка системных переменных и уровня отчета ошибок");
}elseif(function_exists("mb_internal_encoding") && !mb_internal_encoding("UTF-8")){ mpre("Кодировки библиотеки корвертации");
}elseif(strpos(__DIR__, "phar://") === 0){ # Файл index.php внутри phar архива
  if(!isset($index) && ($index = './index.php') && file_exists($index)){
    include $index; if($conf) die;
  } $conf["db"]["open_basedir"] = implode("/", array_slice(explode("/", dirname(__DIR__)), 2)). "::". __DIR__;
}else{ # Не в phar
  if(file_exists($phar = __DIR__. DIRECTORY_SEPARATOR. "index.phar")){
    $conf["db"]["open_basedir"] = "phar://{$phar}";
    $conf["db"]["open_basedir"] = strtr(ini_get("open_basedir") ?: __DIR__, [':'=>'::']). "::". $conf["db"]["open_basedir"];
  }else{
    $conf["db"]["open_basedir"] = strtr(ini_get("open_basedir") ?: __DIR__, [':'=>'::']);
  }
}

if(!$conf = call_user_func(function($conf){
    return $conf;
  }, $conf)){ print_r("ОШИБКА установки переменных окружения");
}elseif(!$mp_require_once = function($link){
    global $conf, $arg, $tpl;
    foreach(explode('::', $conf["db"]["open_basedir"]) as $k=>$v){
      if(!file_exists($file_name = "$v/$link")) continue;
      require_once $file_name; return $file_name;
    } return $file_name;
  }){ mpre("Функция подключения ресурсов");
}elseif(!$mp_require_once("include/config.php")){ mpre("ОШИБКА подключения файла конфигурации");
}elseif(!$mp_require_once("include/func.php")){ mpre("ОШИБКА подключения функций системы");
}elseif(!empty($argv) && count($argv)>1 && !call_user_func(function($argv) use(&$conf){
    /* Запуск скрипта из консоли php -f index.php /pages:index/2 - Путь до скрипта в файловой системе */
    if(!$conf['user']['gid'] = array(1=>"Администратор")){ mpre("Установка прав администратора");
    }elseif(!$link = get($argv, 1)){ die(!mpre("Не указана ссылка консольной утилиты"));
    }elseif(!conn()){ die(!mpre("ОШИБКА соединения с базой данных"));
    }elseif(!preg_match("#^(/.*)$#iu", $link, $match)){ die(!mpre("ОШИБКА адрес исполняемого файла должен начинаться со слеша `{$link}`"));
      /* Не понял какой адрес мы тут используем */
      array_shift($argv);//выкидвыаем путь к файлу
      $mode = explode(":",array_shift($argv));
      if(!isset($mode[1])) $mode[1]='index'; //index
      foreach($argv as $k=>$item){
        $item = explode(":",$item);
        if(is_numeric($item[0]) AND !isset($item[1])){
          $_GET['id'] = $item[0];
        }else{
          $_GET[$item[0]] = get($item,1)?:"";
        }
      } /* Иначе адрес как и в адресной строке /pages:index/1 */
    }elseif(!$uri = get($match, 1)){ die(!mpre("ОШИБКА получения ссылки из параметров регулярного выржения"));
    }elseif(!chdir(__DIR__)){ mpre("ОШИБКА Установки текущей директории");
    }elseif(!$get = mpgt($uri)){ mpre("ОШИБКА получения параметров адресной строки");
    }elseif(!$m = get($get, 'm')){ mpre("ОШИБКА получения параметров адреса");
    }elseif(!list($mode[0], $mode[1]) = each($m)){ mpre("ОШИБКА получения имени модуля и исполняемого файла");
    }elseif(empty($mode[1]) && (!$mode[1] = "index")){ mpre("Используем имя файла index если не указан");
    }elseif(!is_array($_REQUEST = $_GET)){ mpre("ОШИБКА добавления параметров к реквесту");
    }elseif(!$arg =['modpath' => $mode[0], 'modname' => $mode[0], 'fn' => $mode[1], 'fe' => null, 'admin_access' => 5]){ mpre("Формирование аргументов страницы");
    }elseif(!$mode = "modules/".implode("/",$mode)){ mpre("ОШИБКА собираем путь к модулю");
    }else{// mpre("Запускаем `{$mode}`", $arg);
      inc($mode,['arg'=>$arg]);
    } exit();
  }, $argv)){ mpre("Запуск консольной утилиты");
}elseif(!$conf['settings']['http_host'] = strtolower(function_exists("idn_to_utf8") ? idn_to_utf8($_SERVER['HTTP_HOST']) : $_SERVER['HTTP_HOST'])){ pre("ОШИБКА конвертации имени хоста");
}elseif(!$conf['settings']['access_array'] = ['0'=>'Запрет', '1'=>'Чтение', '2'=>'Добавл', '3'=>'Запись', '4'=>'Модер', '5'=>'Админ']){ mpre("ОШИБКА установки уровней доступа");
}elseif(!$conf['settings']['microtime'] = microtime(true)){ mpre("Фиксация начала запуска скрипта");
}elseif(empty(get($conf, 'settings', 'users_cashe_disacled')) && ($cache = cache())){ exit($cache); mpre("Выдаем сохраненную версию если страница кеширована ранее");
}else{
}

if(!$conf['db']['conn'] = conn()){ pre("ОШИБКА подключения к базе данных");
}elseif(($conf['db']['type'] == 'sqlite') && !is_writable($conf['db']['name'])){ die(!pre("ОШИБКА Файл БД `{$conf['db']['name']}` не доступен для записи", "ERROR DB file `{$conf['db']['name']} ' error is not writable"));
}elseif(!empty($conf['db']['error'])){ pre("ОШИБКА подключения к базе данных", $conf['db']['error']);
}elseif(!array_key_exists('null', $_GET)){// pre("Список параметров");
}elseif(tables()){ pre("Установка уже завершена");
}else{ pre("Установка");
	exit(inc('include/install.php'));
} $_REQUEST += $_GET += mpgt($_SERVER['REQUEST_URI']);

$conf['settings'] += array_column(rb("{$conf['db']['prefix']}settings"), "value", "name");

if(!$sess = users_sess()){// pre("Добавляем сессию");
}elseif(!$guest = get($conf, 'settings', 'default_usr')){ mpre("Имя пользователя гость не указано");
}elseif(!is_array($conf['user'] = (rb('users-', 'id', $sess['uid']) ?: rb('users-', 'name', "[{$guest}]")))){ pre("ОШИБКА выборки пользователя");
}elseif(!$conf['user'] += ['uid'=>(($sess['uid'] > 0) ? get($conf, 'user', 'id') : -$sess['id']), 'sess'=>$sess]){ pre("ОШИБКА сохранения сессии в системных переменных");
//}elseif(true){ pre($conf['user']);
}elseif(isset($_GET['logoff'])){ # Если пользователь покидает сайт
  qw("UPDATE {$conf['db']['prefix']}users_sess SET sess = '!". mpquot($sess['sess']). "' WHERE id=". (int)$sess['id'], 'Выход пользователя');
  setcookie("{$conf['db']['prefix']}modified_since", "", 0, "/");
  if(!empty($_SERVER['HTTP_REFERER'])){
    exit(header("Location: ". ($conf['settings']['users_logoff_location'] ? $conf['settings']['users_logoff_location'] : $_SERVER['HTTP_REFERER'])));
  } qw($sql = "DELETE FROM {$conf['db']['prefix']}users_sess WHERE last_time < ".(time() - $conf['settings']['sess_time']), 'Удаление сессий');
}elseif(!$_POST && !get($_COOKIE, "sess")){// pre("Сессия выключена");
//}elseif(pre($conf['user']['sess']) &&0){
}elseif(!$_POST || (get($_POST, 'reg') != 'Аутентификация')){// pre("Нет запроса на аутентификацию");
}elseif(!strlen($_POST['name'])){ pre("Имя не задано");
}elseif(!strlen($_POST['pass'])){ pre("Пароль не задан");
}elseif(!$mphash = mphash($_POST['name'], $_POST['pass'])){pre("Ошибка получения хэша пароля");
}elseif(!$user = rb("{$conf['db']['prefix']}users", "type_id", "name", "pass", 1, "[". mpquot($_POST['name']). "]", "[{$mphash}]")){ pre("Не верный пароль");
  sleep(1);
}elseif(!$sess = fk("users-sess", ['id'=>$sess['id']], null, ['uid'=>$user['id']])){ pre("Ошибка редактирования сессии", $sess);
}elseif(!$conf['user']['sess'] = $sess){ pre("ОШИБКА сохранения сессии в системных переменных");
}elseif(!$user = fk("users-", ['id'=>$user['id']], null, ['last_time'=>time()])){ pre("Ошибка установки времени входа пользователю");
}else{ // pre($conf['user']['sess']);
  /*if(get($_POST, 'HTTP_REFERER')){
    exit(header("Location: {$_POST['HTTP_REFERER']}"));
  } setcookie("{$conf['db']['prefix']}modified_since", "1", 0, "/");*/
}

if($sess['uid'] <= 0){ mpre("Посетитель является гостем");
}elseif(!$conf['user'] = ql($sql = "SELECT *, id AS uid, name AS uname FROM {$conf['db']['prefix']}users WHERE id=". (int)$sess['uid'], 0)){ mpre("Информация о пользователе не найдена");
}else{// mpre("Информация о пользователе", $conf['user']);
  if(($conf['settings']['users_uname'] = $conf['user']['uname']) == $conf['settings']['default_usr']){
    $conf['user']['uid'] = -$sess['id'];
  } $conf['settings']['users_uid'] = $conf['user']['uid'];

  $conf['db']['info'] = 'Получаем информацию о группах в которые входит пользователь';
  $conf['user']['gid'] = array_column(qn("SELECT g.id, g.name FROM {$conf['db']['prefix']}users_grp as g, {$conf['db']['prefix']}users_mem as m WHERE (g.id=m.grp_id) AND m.uid=". (int)$sess['uid']), "name", "id");
  $conf['user']['sess'] = $sess;
} if(!get($conf, 'settings', 'admin_usr')){
  exit(inc('include/install.php')); // , array('conf'=>$conf)
}

foreach(mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index", "Список модулей", function($error) use($conf){
  if(strpos($error, "doesn't exist")){
    qw(pre("ALTER TABLE {$conf['db']['prefix']}modules RENAME {$conf['db']['prefix']}modules_index"));
  }else{ pre("Ошибка обработки ошибки", $error); }
})) as $modules){
  if(array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false) $modules['admin_access'] = 5;
  $conf['modules'][ $modules['folder'] ] = $modules;
  $conf['modules'][ $modules['folder'] ]['modname'] = $modules['modname'] = (strpos($_SERVER['HTTP_HOST'], "xn--") !== false) ? mb_strtolower($modules['name'], 'UTF-8') : $modules['folder'];
  $conf['modules'][ $modules['modname'] ] = &$conf['modules'][ $modules['folder'] ];
  $conf['modules'][ mb_strtolower($modules['name']) ] = &$conf['modules'][ $modules['folder'] ];
  $conf['modules'][ $modules['id'] ] = &$conf['modules'][ $modules['folder'] ];
}

if(($start_mod = get($conf, 'settings', 'start_mod')) && !array_key_exists("m", $_GET)){ # Главная страница
 	if((strpos($start_mod, "http://") === 0) || (strpos($start_mod, "//") === 0)){// mpre("Перенарпавление не другой сайт");
    exit(header("Location: {$conf['settings']['start_mod']}"));
  }elseif(($seo_index = rb("{$conf['db']['prefix']}seo_index", "name", "[/]")) /*&& array_key_exists("themes_index", $redirect)*/){
    if(get($seo_index, "location_id") && ($seo_location = rb("seo-location", "id", $seo_index['location_id']))){
      if($index_type = rb("{$conf['db']['prefix']}seo_index_type", "id", $seo_index['index_type_id'])){
        header("Content-Type: {$index_type['name']}; charset=utf-8");
      } $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $seo_location['name']);
    }else{ $_REQUEST += $_GET = mpgt(/*$_SERVER['REQUEST_URI'] =*/ ($conf['settings']['canonical'] = $conf['settings']['start_mod'])); }
    $conf['settings']['title'] = get($seo_index, 'title');
    $conf['settings']['description'] = get($seo_index, 'description');
    $conf['settings']['keywords'] = get($seo_index, 'keywords');
  }else{
    $_REQUEST += $_GET = mpgt(/*$_SERVER['REQUEST_URI'] =*/ ($conf['settings']['canonical'] = $conf['settings']['start_mod']));
  } $_SERVER['SCRIPT_URL'] = "/";
}elseif(!array_key_exists("null", $_GET) /*&& !is_array($_GET['m'])*/ && $conf['modules']['seo']){
  if(array_key_exists(($p = (strpos(get($_SERVER, 'HTTP_HOST'), "xn--") === 0) ? "стр" : "p"), $_GET) && ($_GET['p'] = $_GET[$p])){
    $r = urldecode(preg_replace("#([\#\?].*)?$#",'',strtr($_SERVER['REQUEST_URI'], array("?{$p}={$_GET[$p]}"=>"", "&{$p}={$_GET[$p]}"=>"", "/{$p}:{$_GET[$p]}"=>""))));
  }else{
    $r = urldecode(preg_replace("#([\#\?].*)?$#",'',$_SERVER['REQUEST_URI']));
  }
  if("/robots.txt" == $_SERVER['REQUEST_URI']){
    $_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/seo:robots"), 'name'), $_GET += ['null'=>true]);
  }elseif("/sitemap.xml" == $_SERVER['REQUEST_URI']){
    $_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/seo:sitemap"), 'name'), $_GET += ['null'=>true]);
  }elseif($redirect = rb("{$conf['db']['prefix']}seo_index", "name", "[{$r}]")){
    if(strpos($redirect['name'], "http://") === 0){
      header("Debug info:". __FILE__. ":". __LINE__);
      exit(header("Location: {$redirect['to']}"));
    }else if(get($redirect, 'index_id') && ($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $redirect['index_id']))){ # Перенаправление с внешнего на внешний адрес
      header('HTTP/1.1 301 Moved Permanently');
      exit(header("Location: {$seo_index['name']}"));
    }else if(get($redirect, 'location_id') && ($seo_location = rb("{$conf['db']['prefix']}seo_location", "id", $redirect['location_id']))){
      $_REQUEST = ($_GET = mpgt($conf['settings']['canonical'] = $seo_location['name'])+array_diff_key($_GET, array("m"=>"Устаревшие адресации"))+$_REQUEST);
      $conf['settings']['title'] = get($redirect, 'title');
      $conf['settings']['description'] = get($redirect, 'description');
      $conf['settings']['keywords'] = get($redirect, 'keywords');
      if($seo_index_type = rb("{$conf['db']['prefix']}seo_index_type", "id", $redirect['index_type_id'])){
        header("Content-Type: {$seo_index_type['name']}");
      }
    }else{ $_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $redirect, 'name'), $_GET); }
  }elseif(get($conf, 'settings', 'start_mod') == $_SERVER['REQUEST_URI']){ # Заглавная страница
    $conf['settings']['canonical'] = "/";
  }elseif(!array_key_exists("404", $conf['settings']) || ($_404 = $conf['settings']['404'])){ # Если не прописан адрес 404 ошибки, то его обработку оставляем для init.php
    $keys = array_keys($ar = array_keys($_GET['m']));
    if(!get($conf, 'modules',  $ar[min($keys)] , 'folder')){
      $_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
    }
  }

}

if(call_user_func(function($conf){ # Если прописана внутренняя страница и перенаправлениее ее на внешнюю делаем переход и отображаем об этом информацию
    if(!$seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$_SERVER['REQUEST_URI']}]")){// mpre("Адрес внутренней страници в админке не задан");
    }elseif(!$seo_location['location_status_id']){ mpre("Статус перенаправления не установлен");
    }elseif(!$seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id'])){ mpre("ОШИБКА выборки статуса перенаправления");
    }elseif(!get($seo_location, "index_id")){// mpre("Внешний адрес для перенаправления не установлен");
    }elseif(get($conf, 'settings', 'seo_meta_hidden')){// mpre("Скрываем сообщения для администраторов");
    }elseif(!$seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location['index_id'])){ mpre("ОШИБКА выборки адреса для перенаправления");
    }elseif(empty(get($conf, 'settings', 'seo_meta_hidden')) && ($gid = get($conf, 'user', 'gid')) && array_search("Администратор", $gid)){ mpre("Перенаправляем страницу на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
    }else{
      header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
      exit(header("Location: {$seo_index['name']}"));
    }
  }, $conf)){ mpre("Перенаправление страницы по внутреннему адерсу");
}elseif(!(array_key_exists("m", $_GET) ? (list($m) = array_keys($_GET['m'])) : "pages")){ mpre("Модуль не установлен");
}elseif((!$conf['settings']['modpath'] = $modpath = ((!empty($m) && array_key_exists($m, $conf['modules'])) ? $conf['modules'][ $m ]['folder'] : "")) &0){ mpre("Модуль не определен");
}elseif((array_key_exists("m", $_GET) ? (list($f) = array_values($_GET['m'])) : ($f = "index")) &0){ mpre("Страница не установлена");
}elseif(!$conf['settings']['fn'] = $fn = ((!empty($f) && ($f != "index")) ? $f : "index")){ mpre("Страница не определена");
}elseif(!$fn = $conf['settings']['fn']){ mpre("Имя файла не определенено");
}elseif(array_key_exists('theme', $_GET) && (!$conf['user']['sess']['theme'] = $conf['settings']['theme'] = basename($_GET['theme']))){ mpre("Ошибка установки темы из адреса");
}elseif(get($conf, 'user', 'theme') && (!$conf['user']['sess']['theme'] = $conf['settings']['theme'] = $conf['user']['theme'])){ mpre("Ошибка установки темы из настроек пользователя");
}elseif(($t = get($conf, 'settings', $w = "theme/{$modpath}:{$fn}")) && (!$conf['settings']['theme'] = $t)){ mpre("Ошибка установки темы по файлу и модулю `{$w}`");
}elseif(($t = get($conf, 'settings', $w = "theme/*:{$fn}")) && (!$conf['settings']['theme'] = $t)){ mpre("Ошибка установки темы по модулю `{$w}`");
}elseif(($t = get($conf, 'settings', $w = "theme/{$modpath}:*")) && (!$conf['settings']['theme'] = $t)){ mpre("Ошибка установки темы по файлу `{$w}`");
}elseif(((strpos($conf['settings']['fn'], "admin") === 0) && $conf['settings']["theme/*:admin"]) && (!$conf['settings']['theme'] = $conf['settings']["theme/*:admin"])){ mpre("Ошибка установки темы админ страницы");
}elseif(inc("include/init.php", array("arg"=>array("modpath"=>"admin", "fn"=>"init"), "content"=>($conf["content"] = "")))){ mpre("Ошибка подключения файла инициализации");
}elseif(get($conf, "settings", "themes_index") && inc("modules/admin/admin_multisite.php", array("content"=>($conf["content"] = "")))){ mpre("Ошибка включения режима мультисайта");
//}elseif(true){ die(!pre(get($conf, "settings", "themes_index")));
}else{
}

foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index_gaccess ORDER BY sort", 'Права доступа группы к модулю', function($error) use($conf){
  if(strpos($error, "Unknown column 'sort'")){
    qw(mpre("ALTER TABLE `mp_modules_index_gaccess` ADD `sort` int(11) NOT NULL  COMMENT '' AFTER `id`"));
    qw(mpre("UPDATE `mp_modules_index_gaccess` SET sort=id"));
  }elseif(strpos($error, "doesn't exist")){
    qw(mpre("ALTER TABLE {$conf['db']['prefix']}modules_gaccess RENAME {$conf['db']['prefix']}modules_index_gaccess"));
  }
})) as $k=>$v){
  if(array_key_exists($v['gid'], $conf['user']['gid']) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false){
    $conf['modules'][ $v['mid'] ]['admin_access'] = $v['admin_access'];
  }
}

foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index_uaccess ORDER BY uid", 'Права доступа пользователя к модулю', function($error) use($conf){
  if(strpos($error, "doesn't exist")){ qw(mpre("ALTER TABLE {$conf['db']['prefix']}modules_uaccess RENAME {$conf['db']['prefix']}modules_index_uaccess"));
	}else{ pre("Неустановленная ошибка при выборки прав доступа пользователей"); }
})) as $k=>$v){
  if ($conf['user']['uid'] == $v['uid'] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
    $conf['modules'][ $v['mid'] ]['admin_access'] = $v['admin_access'];
}



if(!is_array($zblocks = call_user_func(function() use(&$conf){
		if(array_key_exists('blocks', $_GET['m']) && ($_GET['m']['blocks'] == "index") && !get($_GET, 'id')){// pre($_GET);
			$conf["content"] = modules($conf["content"]);
			$zblocks = [];
		}elseif(isset($_GET['m']['sql'])){
			$zblocks = blocks();
			$conf["content"] = modules($conf["content"]);
		}else{
			$conf["content"] = modules($conf["content"]);
			$zblocks = blocks();
		} return $zblocks;
	})) and !get($conf,"deny")){ mpre("Ошибка установки порядка следования расчетов блоков");
}elseif(!$ind = (get($_GET, 'index') ?: (get($conf, 'settings', 'index') ?: "index"))){ mpre("Ошибка определения имени главного файла");
}elseif(!$t = mpopendir($f = "themes/{$conf['settings']['theme']}/{$ind}.html")){
}elseif(array_key_exists('null', $_GET)){// mpre("Аякс запросу шаблон не обязателен");
}elseif(!get($conf, 'settings', 'theme_exec') && (!$tc = file_get_contents($t))){ mpre("Ошибка получения содержимого файла шаблона");
}else{
  if($teme_config=mpopendir("themes/{$conf['settings']['theme']}/config.json") AND $teme_config=file_get_contents($teme_config) AND isJSON($teme_config)){
    $teme_config = json_decode($teme_config,true);
    if(get($teme_config,'less_compile')){
      MpLessCompile(mpopendir("themes/{$conf['settings']['theme']}/"));
    }
    if(get($teme_config,'js_auto_mini')){
      MpJsAutoMini(mpopendir("themes/{$conf['settings']['theme']}/"));
    }
  }
  ob_start();
    inc($f);
  $tc = ob_get_clean();
}

if(array_key_exists('null', $_GET)){ echo $conf["content"];
}elseif(!$conf["content"] = str_replace('<!-- [modules] -->', $conf["content"], $tc)){ mpre("Ошибка замены содержимого модуля");
}elseif(!$conf["content"] = strtr($conf["content"], (array)$zblocks)){ mpre("Ошибка установки содержимоого блоков");
}elseif(!$conf['settings']['microtime'] = substr(microtime(true)-$conf['settings']['microtime'], 0, 8)){ mpre("Ошибка расчета времени генерирования страницы");
}elseif(!$conf["content"] = array_key_exists("null", $_GET) ? $conf["content"] : strtr($conf["content"], mpzam($conf['settings'], "settings", "<!-- [", "] -->"))){ mpre("Ошибка установки переменных в текст");
}elseif(empty(get($conf, 'settings', 'users_cashe_disacled')) && cache($conf["content"]) &&0){ mpre("Ошибка кеширования содержимого страницы");
}else{ echo $conf["content"]; }
