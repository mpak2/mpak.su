<?

// ----------------------------------------------------------------------
// Жираф cms Content Management System
// Copyright (C) 2007-2010 by the mpak.
// (Link: http://mpak.su)
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 929 1140042
// ----------------------------------------------------------------------

ini_set('display_errors', 1); error_reporting(E_ALL /*& ~E_NOTICE & ~E_STRICT*/);
date_default_timezone_set('Europe/Moscow');
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control:no-cache, must-revalidate;");
setlocale (LC_ALL, "Russian"); putenv("LANG=ru_RU");

if(strpos(__DIR__, "phar://") === 0){ # Файл index.php внутри phar архива
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

$conf['settings'] = array(
	'http_host' => strtolower(function_exists("idn_to_utf8") ? idn_to_utf8($_SERVER['HTTP_HOST']) : $_SERVER['HTTP_HOST']),
	'access_array' => array('0'=>'Запрет', '1'=>'Чтение', '2'=>'Добавл', '3'=>'Запись', '4'=>'Модер', '5'=>'Админ'),
	'microtime' => microtime(true),
);

if(!function_exists('mp_require_once')){
	function mp_require_once($link){
		global $conf, $arg, $tpl;
		foreach(explode('::', $conf["db"]["open_basedir"]) as $k=>$v){
			if(!file_exists($file_name = "$v/$link")) continue;
			require_once $file_name; return;
		}
	}
}
mp_require_once("include/config.php"); # Конфигурация
mp_require_once("include/mpfunc.php"); # Функции системы

cache();

if(!$guest = ['id'=>0, "uname"=>"гость", "pass"=>"nopass", "reg_time"=>0, "last_time"=>time()]){ mpre("Ошибка создания пользователя");
}elseif(!$sess = array('id'=>0, 'uid'=>$guest['id'], "refer"=>0, 'last_time'=>time(), 'count'=>0, 'count_time'=>0, 'cnull'=>0, 'sess'=>($_COOKIE["sess"] ?: md5("{$_SERVER['REMOTE_ADDR']}:".microtime())), 'ref'=>mpquot(mpidn(urldecode($_SERVER['HTTP_REFERER']))), 'ip'=>mpquot($_SERVER['REMOTE_ADDR']), 'agent'=>mpquot($_SERVER['HTTP_USER_AGENT']), 'url'=>mpquot(urldecode($_SERVER['REQUEST_URI'])))){ pre("Ошибка создания сессии");
}

try{
	if($conf['db']['type'] == "sqlite"){
		$conf['db']['conn'] = new PDO("{$conf['db']['type']}:". mpopendir($conf['db']['name']));
		$conf['db']['conn']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conf['db']['conn']->exec('PRAGMA foreign_keys=ON');
	}else{
		ini_set("default_socket_timeout", 0.1);
		$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_TIMEOUT=>0.1));
		$conf['db']['conn']->exec("set names utf8"); # Prior to PHP 5.3.6, the charset option was ignored
	}// return $conf['db']['conn'];
}catch(Exception $e){ cache(0);
	pre("Ошибка подключения к базе данных");
} if((!array_key_exists('null', $_GET) && !empty($conf['db']['error'])) || !tables()){
	exit(inc('include/install.php'));
} $_REQUEST += $_GET += mpgt($_SERVER['REQUEST_URI']);

if(!$_POST && !get($_COOKIE, "sess")){// print_r("Сессия выключена");
}elseif(!$sess = call_user_func(function($sess) use($conf, $guest){
		setcookie("sess", $sess['sess'], 0, "/");
		if(!$_sess = ql($sql = "SELECT * FROM {$conf['db']['prefix']}sess WHERE `ip`='{$sess['ip']}' AND last_time>=".(time()-86400)." AND `agent`=\"{$sess['agent']}\" AND ". ($_COOKIE["sess"] ? "sess=\"{$sess['sess']}\"" : "uid=". $guest['id'])." ORDER BY id DESC", 0)){
			qw($sql = "INSERT INTO {$conf['db']['prefix']}sess (`". implode("`, `", array_keys(array_diff_key($sess, array_flip(['id'])))). "`) VALUES ('". implode("', '", array_values(array_diff_key($sess, array_flip(['id'])))). "')");
			$sess = ['id'=>($conf['db']['conn']->lastInsertId())] + $sess; return $sess;
		}else{ return $_sess; }
	}, $sess)){ pre("Ошибка создания сессии");
}elseif(array_key_exists('null', $_REQUEST)){ mpre("Отключено обновление сессии для ресурсов");
}else{
	qw("UPDATE {$conf['db']['prefix']}sess SET count_time = count_time+".time()."-last_time, last_time=".time().", ".(isset($_GET['null']) ? 'cnull=cnull' : 'count=count')."+1, sess=\"". mpquot($sess['sess']). "\" WHERE id=". (int)$sess['id']);
}

$conf['settings'] += array_column(rb("{$conf['db']['prefix']}settings"), "value", "name");

if(isset($_GET['logoff'])){ # Если пользователь покидает сайт
	qw("UPDATE {$conf['db']['prefix']}sess SET sess = '!". mpquot($sess['sess']). "' WHERE id=". (int)$sess['id'], 'Выход пользователя');
	setcookie("{$conf['db']['prefix']}modified_since", "", 0, "/");
	if(!empty($_SERVER['HTTP_REFERER'])){
		exit(header("Location: ". ($conf['settings']['users_logoff_location'] ? $conf['settings']['users_logoff_location'] : $_SERVER['HTTP_REFERER'])));
	} # Стираем просроченные сессии
	qw($sql = "DELETE FROM {$conf['db']['prefix']}sess WHERE last_time < ".(time() - $conf['settings']['sess_time']), 'Удаление сессий');
	qw($sql = "DELETE FROM {$conf['db']['prefix']}sess_post WHERE time < ".(time() - $conf['settings']['sess_time']), 'Удаление данных сессии');
}elseif(!$_POST || (get($_POST, 'reg') != 'Аутентификация')){// pre("Нет запроса на аутентификацию");
}elseif(!strlen($_POST['name'])){ pre("Имя не задано");
}elseif(!strlen($_POST['pass'])){ pre("Пароль не задан");
}elseif(!$mphash = mphash($_POST['name'], $_POST['pass'])){pre("Ошибка получения хэша пароля");
}elseif(!$user = rb("{$conf['db']['prefix']}users", "type_id", "name", "pass", 1, "[". mpquot($_POST['name']). "]", "[{$mphash}]")){ pre("Не верный пароль");
	sleep(1);
}elseif(!$sess = fk("{$conf['db']['prefix']}sess", ['id'=>$sess['id']], null, ['uid'=>$user['id']])){ mpre("Ошибка редактирования сессии");
}elseif(!$user = fk("{$conf['db']['prefix']}users", ['id'=>$user['id']], null, ['last_time'=>time()])){ mpre("Ошибка установки времени входа пользователю");
	if(get($_POST, 'HTTP_REFERER')){
		exit(header("Location: {$_POST['HTTP_REFERER']}"));
	} setcookie("{$conf['db']['prefix']}modified_since", "1", 0, "/");
}

if($sess['uid'] <= 0){ mpre("Посетитель является гостем");
	$conf['user'] = $guest + ['sess'=>['id'=>0, 'uid'=>0]];
}elseif(!$conf['user'] = ql($sql = "SELECT *, id AS uid, name AS uname FROM {$conf['db']['prefix']}users WHERE id=". (int)$sess['uid'], 0)){ mpre("Информация о пользователе не найдена");
}else{// mpre("Информация о пользователе", $conf['user']);
	if(($conf['settings']['users_uname'] = $conf['user']['uname']) == $conf['settings']['default_usr']){
		$conf['user']['uid'] = -$sess['id'];
	} $conf['settings']['users_uid'] = $conf['user']['uid'];

	$conf['db']['info'] = 'Получаем информацию о группах в которые входит пользователь';
	$conf['user']['gid'] = array_column(qn("SELECT g.id, g.name FROM {$conf['db']['prefix']}users_grp as g, {$conf['db']['prefix']}users_mem as m WHERE (g.id=m.grp_id) AND m.uid=". (int)$sess['uid']), "name", "id");
	$conf['user']['sess'] = $sess;
} if(!get($conf, 'settings', 'admin_usr')){
	exit(inc('include/install.php'/*, array('conf'=>$conf)*/));
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

if($conf['settings']['start_mod'] && !array_key_exists("m", $_GET)){ # Главная страница
	if(strpos($conf['settings']['start_mod'], "http://") === 0){
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
	}elseif($conf['settings']['start_mod'] == $_SERVER['REQUEST_URI']){ # Заглавная страница
		$conf['settings']['canonical'] = "/";
	}elseif(!array_key_exists("404", $conf['settings']) || ($_404 = $conf['settings']['404'])){ # Если не прописан адрес 404 ошибки, то его обработку оставляем для init.php
		$keys = array_keys($ar = array_keys($_GET['m']));
		if(!get($conf, 'modules',  $ar[min($keys)] , 'folder')){
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}
	}

	if($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$_SERVER['REQUEST_URI']}]")){
		if($seo_location['location_status_id'] && ($seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id']))){
			if(get($seo_location, "index_id") && ($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location['index_id']))){
				header("Debug info:". __FILE__. ":". __LINE__);
				header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
				exit(header("Location: {$seo_index['name']}"));
			}
		}
	}
}


if(array_key_exists('theme', $_GET)){
	$conf['user']['sess']['theme'] = $conf['settings']['theme'] = basename($_GET['theme']);
}elseif(get($conf, 'user', 'theme')){ # Изменяем тему, если для пользователя установлен другой шаблон
	$conf['user']['sess']['theme'] = $conf['settings']['theme'] = $conf['user']['theme'];
}

if(!(array_key_exists("m", $_GET) ? (list($m) = array_keys($_GET['m'])) : "pages")){ mpre("Модуль не установлен");
}elseif((!$conf['settings']['modpath'] = $modpath = ((!empty($m) && array_key_exists($m, $conf['modules'])) ? $conf['modules'][ $m ]['folder'] : "")) &0){ mpre("Модуль не определен");
}elseif((array_key_exists("m", $_GET) ? (list($f) = array_values($_GET['m'])) : ($f = "index")) &0){ mpre("Страница не установлена");
}elseif(!$conf['settings']['fn'] = $fn = ((!empty($f) && ($f != "index")) ? $f : "index")){ mpre("Страница не определена");
}elseif(!$fn = $conf['settings']['fn']){ mpre("Имя файла не определенено");
}elseif($theme = get($conf, 'settings', $w = "theme/{$modpath}:{$fn}")){// mpre("Тема {$w} {$theme}");
	$conf['settings']['theme'] = $theme;
}elseif($theme = get($conf, 'settings', $w = "theme/*:{$fn}")){// mpre("Тема {$w} {$theme}");
	$conf['settings']['theme'] = $theme;
//}elseif(mpre("theme/{$modpath}:*")){
}elseif($theme = get($conf, 'settings', $w = "theme/{$modpath}:*")){// mpre("Тема {$w} {$theme}");
	$conf['settings']['theme'] = $theme;
} inc("include/init.php", array("arg"=>array("modpath"=>"admin", "fn"=>"init"), "content"=>($conf["content"] = "")));

if(get($conf, "settings", "themes_index")){ # Включение режима мультисайт
	inc("modules/admin/admin_multisite.php", array("content"=>($conf["content"] = "")));
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
	if(strpos($error, "doesn't exist")){
		qw(mpre("ALTER TABLE {$conf['db']['prefix']}modules_uaccess RENAME {$conf['db']['prefix']}modules_index_uaccess"));
	}
})) as $k=>$v){
	if ($conf['user']['uid'] == $v['uid'] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
		$conf['modules'][ $v['mid'] ]['admin_access'] = $v['admin_access'];
}
if((strpos($conf['settings']['fn'], "admin") === 0) && $conf['settings']["theme/*:admin"]){ # Изменяем тему админ страницы
	$conf['settings']['theme'] = $conf['settings']["theme/*:admin"];
} if(isset($_GET['m']['sqlanaliz'])){
	$zblocks = blocks();
	$conf["content"] = modules($conf["content"]);
}else{
	$conf["content"] = modules($conf["content"]);
	$zblocks = blocks();
}

if(($t = mpopendir($f = "themes/{$conf['settings']['theme']}/". (get($_GET, 'index') ?: (get($conf, 'settings', 'index') ?: "index")) . ".html")) || array_key_exists('null', $_GET)){
	if(get($conf, 'settings', 'theme_exec')){
		ob_start(); inc($f); $tc = ob_get_contents(); ob_clean();
	}else{
		$tc = file_get_contents($t);
	}
}else{ die(pre(__LINE__, "Шаблон {$f} не найден")); }

if(!array_key_exists('null', $_GET)){
	$conf["content"] = str_replace('<!-- [modules] -->', $conf["content"], $tc);
} $conf["content"] = strtr($conf["content"], (array)$zblocks);

$conf['settings']['microtime'] = substr(microtime(true)-$conf['settings']['microtime'], 0, 8);

$conf["content"] = array_key_exists("null", $_GET) ? $conf["content"] : strtr($conf["content"], mpzam($conf['settings'], "settings", "<!-- [", "] -->"));

cache($conf["content"]);
echo $conf["content"];
