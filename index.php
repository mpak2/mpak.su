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

if(strpos(__DIR__, "phar://") === 0){ # Файл index.php внутри phar архива
	if(!isset($index) && ($index = './index.php') && file_exists($index)){
		include $index; if($content) die;
	} $conf["db"]["open_basedir"] = implode("/", array_slice(explode("/", dirname(__DIR__)), 2)). ":". __DIR__;
}else{ # Не в phar
	if(file_exists($phar = __DIR__. "/index.phar")){
		$conf["db"]["open_basedir"] = "phar://{$phar}";
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: __DIR__). ":". $conf["db"]["open_basedir"];
	}else{
		$conf["db"]["open_basedir"] = (ini_get("open_basedir") ?: __DIR__);
	}
}

if(!function_exists('mp_require_once')){
	function mp_require_once($link){
		global $conf, $arg, $tpl;
		foreach(explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) as $k=>$v){
			if (!file_exists($file_name = "$v/$link")) continue;
			require_once $file_name; return;
		}
	}
}

mp_require_once("include/config.php"); # Конфигурация
mp_require_once("include/mpfunc.php"); # Функции системы

//Обращение к файлу в корне (webroot)
if(preg_match("#\/[\w\d\-\_\%\.]+\.\w+$#i",$_SERVER['REDIRECT_URI'])){
	$REDIRECT_URL = urldecode(preg_replace("#\.\.\/#iu","",$_SERVER['REDIRECT_URL']));
	foreach(explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) as $v){
		if(file_exists("$v/webroot$REDIRECT_URL")){
			if(preg_match("#\.php$#iu",$REDIRECT_URL)){
				include("$v/webroot$REDIRECT_URL");				
			}else{
				file_download("$v/webroot$REDIRECT_URL");
			}
			exit();
		}
	}
}

header('Content-Type: text/html;charset=UTF-8');

if(!function_exists('__autoload')){
	function __autoload($class_name){#Автоподгрузка классов
		include_once mpopendir("/include/class/$class_name.php");	
	}
}
try{
	if($conf['db']['type'] == "sqlite"){
		$conf['db']['conn'] = new PDO("{$conf['db']['type']}:". mpopendir($conf['db']['name']));
		$tpl['tables'] = array_column(qn("SELECT * FROM sqlite_master WHERE type='table' AND name LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"", "name"), "name");
	}else{
		$conf['db']['conn'] = new PDO("{$conf['db']['type']}:host={$conf['db']['host']};dbname={$conf['db']['name']};charset=UTF8", $conf['db']['login'], $conf['db']['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
		$conf['db']['conn']->exec("set names utf8"); # Prior to PHP 5.3.6, the charset option was ignored
		$tpl['tables'] = array_column(ql("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\""), "Tables_in_{$conf['db']['name']}");
	} $_REQUEST += $_GET += mpgt($_SERVER['REQUEST_URI']);
}catch(Exception $e){
	pre("Ошибка подключения к базе данных");
} if ((!array_key_exists('null', $_GET) && !empty($conf['db']['error'])) || !$tpl['tables']){
	exit(mpopendir('include/install.php') ? mpct('include/install.php') : "Файл установки не найден");
}

$conf['db']['info'] = 'Загрузка свойств модулей';
$conf['settings'] = array('http_host'=>(function_exists("idn_to_utf8") ? idn_to_utf8($_SERVER['HTTP_HOST']) : $_SERVER['HTTP_HOST']))+array_column(rb("{$conf['db']['prefix']}settings"), "value", "name");
$conf['settings']['access_array'] = array('0'=>'Запрет', '1'=>'Чтение', '2'=>'Добавл', '3'=>'Запись', '4'=>'Модер', '5'=>'Админ');
$conf['settings']['microtime'] = microtime(true);
if($conf['settings']['users_log']) $conf['event'] = rb("{$conf['db']['prefix']}users_event", "name");

if($conf['settings']['del_sess']){
	$func = create_function('&$val, $key','$val = strtr(stripslashes($val), array("\\\\"=>"&#92;", \'"\'=>"&#34;", "\'"=>"&#39;"));');
	array_walk ($get = $_GET, $func); $post = $_POST;
	if (isset($post['pass'])) $post['pass'] = 'hide';
	if (isset($post['pass2'])) $post['pass2'] = 'hide';
//	array_walk ($post, $func); array_walk ($files = $_FILES, $func); array_walk ($server = $_SERVER, $func);
//	$request = serialize(array('$_POST'=>$post, '$_GET'=>$get, '$_FILES'=>$files, '$_SERVER'=>$server));
} setlocale (LC_ALL, "Russian"); putenv("LANG=ru_RU");// bindtextdomain("messages", "./locale"); textdomain("messages"); bind_textdomain_codeset('messages', 'CP1251'); //setlocale(LC_ALL, "ru_RU.CP1251")

if (!$guest_id = mpql(mpqw("SELECT id as gid FROM {$conf['db']['prefix']}users WHERE name='{$conf['settings']['default_usr']}'", "Получаем свойства пользователя гость"), 0, "gid")){ # Создаем пользователя в случае если его нет
	$guest_id = mpfdk("{$conf['db']['prefix']}users", $w = array("name"=>$conf['settings']['default_usr']), $w += array("pass"=>"nopass", "reg_time"=>time(), "last_time"=>time()));
	$guest_grp_id = mpfdk("{$conf['db']['prefix']}users_grp", $w = array("name"=>$conf['settings']['default_grp']), $w);
	$guest_mem_id = mpfdk("{$conf['db']['prefix']}users", $w = array("uid"=>$guest_id, "grp_id"=>$guest_grp_id), $w);
}

if(!($sess = ql($sql = "SELECT * FROM {$conf['db']['prefix']}sess WHERE `ip`='{$_SERVER['REMOTE_ADDR']}' AND last_time>=".(time()-$conf['settings']['sess_time'])." AND `agent`=\"".mpquot($_SERVER['HTTP_USER_AGENT']). "\" AND ". ($_COOKIE["{$conf['db']['prefix']}sess"] ? "sess=\"". mpquot($_COOKIE["{$conf['db']['prefix']}sess"]). "\"" : "uid=". (int)$guest_id)." ORDER BY id DESC", 0))){
	$sess = array('uid'=>$guest_id, 'sess'=>md5("{$_SERVER['REMOTE_ADDR']}:".microtime()), 'ref'=>mpidn(urldecode($_SERVER['HTTP_REFERER'])), 'ip'=>$_SERVER['REMOTE_ADDR'], 'agent'=>$_SERVER['HTTP_USER_AGENT'], 'url'=>$_SERVER['REQUEST_URI']);
	qw($sql = "INSERT INTO {$conf['db']['prefix']}sess (uid, ref, sess, last_time, ip, agent, url) VALUES (". (int)$guest_id. ", '". mpquot($sess['ref']). "', \"". mpquot($_COOKIE["{$conf['db']['prefix']}sess"]). "\", ".time().", '". mpquot($sess['ip']). "', '".mpquot($sess['agent'])."', '".mpquot($sess['url'])."')");
	$sess['id'] = $conf['db']['conn']->lastInsertId();
} if($_COOKIE["{$conf['db']['prefix']}sess"] != $sess['sess']){ # Если сессия с пришедшей кукисой не найдена
	$sess['sess'] = ($sess['sess'] ?: md5("{$_SERVER['REMOTE_ADDR']}:".microtime()));
	setcookie("{$conf['db']['prefix']}sess", $sess['sess'], 0, "/");
}

if(!isset($_REQUEST['NoUpSes']) OR !isset($_REQUEST['null'])){ # Обновление информации о сессии При запросе ресурса обязательна
	mpqw("UPDATE {$conf['db']['prefix']}sess SET count_time = count_time+".time()."-last_time, last_time=".time().", ".(isset($_GET['null']) ? 'cnull=cnull' : 'count=count')."+1, sess=\"". mpquot($sess['sess']). "\" WHERE id=". (int)$sess['id']);
}

if (strlen($_POST['name']) && strlen($_POST['pass']) && $_POST['reg'] == 'Аутентификация' && $uid = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}users WHERE type_id=1 AND name = \"".mpquot($_POST['name'])."\" AND pass='".mphash($_POST['name'], $_POST['pass'])."'", 'Проверка существования пользователя'), 0, 'id')){
	qw($sql = "UPDATE {$conf['db']['prefix']}sess SET uid=".($sess['uid'] = $uid)." WHERE id=". (int)$sess['id']);
	qw($sql = "UPDATE {$conf['db']['prefix']}users SET last_time=". time(). " WHERE id=".(int)$uid);
}elseif(isset($_GET['logoff'])){ # Если пользователь покидает сайт
	mpqw("UPDATE {$conf['db']['prefix']}sess SET sess = '!". mpquot($sess['sess']). "' WHERE id=". (int)$sess['id'], 'Выход пользователя');
	if(!empty($_SERVER['HTTP_REFERER'])){
		header("Debug info:". __FILE__. ":". __LINE__);
		header("Location: ". ($conf['settings']['users_logoff_location'] ? $conf['settings']['users_logoff_location'] : $_SERVER['HTTP_REFERER'])); exit;
	}// if($conf['settings']['del_sess'] == 0){ # Стираем просроченные сессии
	mpqw($sql = "DELETE FROM {$conf['db']['prefix']}sess WHERE last_time < ".(time() - $conf['settings']['sess_time']), 'Удаление сессий');
	mpqw($sql = "DELETE FROM {$conf['db']['prefix']}sess_post WHERE time < ".(time() - $conf['settings']['sess_time']), 'Удаление данных сессии');
//	}
}

$user = mpql(mpqw("SELECT *, id AS uid, name AS uname FROM {$conf['db']['prefix']}users WHERE id={$sess['uid']}", 'Проверка пользователя'));
list($k, $conf['user']) = each($user);
if(($conf['settings']['users_uname'] = $conf['user']['uname']) == $conf['settings']['default_usr']){
	$conf['user']['uid'] = -$sess['id'];
} $conf['settings']['users_uid'] = $conf['user']['uid'];

$conf['db']['info'] = 'Получаем информацию о группах в которые входит пользователь';
$conf['user']['gid'] = array_column(qn("SELECT g.id, g.name FROM {$conf['db']['prefix']}users_grp as g, {$conf['db']['prefix']}users_mem as m WHERE (g.id=m.grp_id) AND m.uid=". (int)$sess['uid']), "name", "id");
$conf['user']['sess'] = $sess;

foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE hide IN (0,2)", 'Информация о модулях', function($error){
	pre($error, $sql = "ALTER TABLE mp_modules CHANGE `enabled` `hide` smallint(6)"); qw($sql); # Исправляем таблицу, в случае если структура не верная.
})) as $modules){
	if (array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false) $modules['access'] = 5;
	$conf['modules'][ $modules['folder'] ] = $modules;
	$conf['modules'][ $modules['folder'] ]['modname'] = $modules['modname'] = (strpos($_SERVER['HTTP_HOST'], "xn--") !== false) ? mb_strtolower($modules['name'], 'UTF-8') : $modules['folder'];
	$conf['modules'][ $modules['modname'] ] = &$conf['modules'][ $modules['folder'] ];
	$conf['modules'][ mb_strtolower($modules['name']) ] = &$conf['modules'][ $modules['folder'] ];
	$conf['modules'][ $modules['id'] ] = &$conf['modules'][ $modules['folder'] ];
}

if($conf['settings']['start_mod'] && !array_key_exists("m", $_GET)){ # Главная страница
	if(strpos($conf['settings']['start_mod'], "http://") === 0){
		header("Debug info:". __FILE__. ":". __LINE__);
		header("Location: {$conf['settings']['start_mod']}"); exit;
	}elseif(($seo_index = rb("{$conf['db']['prefix']}seo_index", "name", "[/]")) /*&& array_key_exists("themes_index", $redirect)*/){
		if($index_type = rb("{$conf['db']['prefix']}seo_index_type", "id", $seo_index['index_type_id'])){
			$_REQUEST += $_GET = mpgt(/*$_SERVER['REQUEST_URI'] =*/ ($conf['settings']['canonical'] = $seo_index['name']));
		}else{
			$_REQUEST += $_GET = mpgt(/*$_SERVER['REQUEST_URI'] =*/ ($conf['settings']['canonical'] = $conf['settings']['start_mod']));
		}
	}else{
		$_REQUEST += $_GET = mpgt(/*$_SERVER['REQUEST_URI'] =*/ ($conf['settings']['canonical'] = $conf['settings']['start_mod']));
	} $_SERVER['SCRIPT_URL'] = "/";
}elseif(!array_key_exists("null", $_GET) /*&& !is_array($_GET['m'])*/ && $conf['modules']['seo']){
	if(array_key_exists(($p = (strpos(get($_SERVER, 'HTTP_HOST'), "xn--") === 0) ? "стр" : "p"), $_GET) && ($_GET['p'] = $_GET[$p])){
		$r = urldecode(preg_replace("#([\#\?].*)?$#",'',strtr($_SERVER['REQUEST_URI'], array("?{$p}={$_GET[$p]}"=>"", "&{$p}={$_GET[$p]}"=>"", "/{$p}:{$_GET[$p]}"=>""))));
	}else{
		$r = urldecode(preg_replace("#([\#\?].*)?$#",'',$_SERVER['REQUEST_URI']));
	} if($redirect = rb("{$conf['db']['prefix']}seo_index", "name", "[{$r}]")){
		// $redirect['name'] = preg_replace("#^{$redirect['from']}$#iu",$redirect['to'],$r);
		if(strpos($redirect['name'], "http://") === 0){
			header("Debug info:". __FILE__. ":". __LINE__);
			exit(header("Location: {$redirect['to']}"));
		}else if(get($redirect, 'index_id') && ($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $redirect['index_id']))){ # Перенаправление с внешнего на внешний адрес
			header('HTTP/1.1 301 Moved Permanently');
			exit(header("Location: {$seo_index['name']}"));
		}else if(get($redirect, 'location_id') && ($seo_location = rb("{$conf['db']['prefix']}seo_location", "id", $redirect['location_id']))){
			$_REQUEST = ($_GET = mpgt($conf['settings']['canonical'] = $seo_location['name'])+array_diff_key($_GET, array("m"=>"Устаревшие адресации"))+$_REQUEST);
			$conf['settings']['description'] = get($redirect, 'description') ?: $conf['settings']['description'];
			$conf['settings']['keywords'] = get($redirect, 'keywords') ?: $conf['settings']['keywords'];
			if($seo_index_type = rb("{$conf['db']['prefix']}seo_index_type", "id", $redirect['index_type_id'])){
				header("Content-Type: {$seo_index_type['name']}");
			}
		}
	}elseif($conf['settings']['start_mod'] == $_SERVER['REQUEST_URI']){ # Заглавная страница
		$conf['settings']['canonical'] = "/";
	}elseif(!array_key_exists("404", $conf['settings']) || ($_404 = $conf['settings']['404'])){ # Если не прописан адрес 404 ошибки, то его обработку оставляем для init.php
		$keys = array_keys($ar = array_keys($_GET['m']));
		if(!$conf['modules'][ $ar[min($keys)] ]['folder']){
			header("Debug info:". __FILE__. ":". __LINE__);
			header('HTTP/1.1 404 Not Found');
			exit(header("Location: /". ($_404 ?: "themes:404"). "{$_SERVER['REQUEST_URI']}"));
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

array_key_exists("m", $_GET) ? (list($m) = array_keys($_GET['m'])) : "pages";
array_key_exists("m", $_GET) ? (list($f) = array_values($_GET['m'])) : "index";

$conf['settings']['modpath'] = !empty($m) && array_key_exists($m, $conf['modules']) ? $conf['modules'][ $m ]['folder'] : "";
$conf['settings']['fn'] = (!empty($f) && ($f != "index")) ? $f : "index";

if(array_key_exists('theme', $_GET)){
	$conf['user']['sess']['theme'] = $conf['settings']['theme'] = basename($_GET['theme']);
}

if(empty($f)){
	$f = 'index';
} if(!empty($conf['settings']["theme/*:{$conf['settings']['fn']}"])){
	$conf['settings']['theme'] = $conf['settings']["theme/*:{$conf['settings']['fn']}"];
} if(!empty($conf['settings']["theme/{$conf['settings']['modpath']}:*"])){
	$conf['settings']['theme'] = $conf['settings']["theme/{$conf['settings']['modpath']}:*"];
} if(!empty($conf['settings']["theme/{$conf['settings']['modpath']}:{$conf['settings']['fn']}"])){
	$conf['settings']['theme'] = $conf['settings']["theme/{$conf['settings']['modpath']}:{$conf['settings']['fn']}"];
} inc("include/init.php", array("arg"=>array("modpath"=>"admin", "fn"=>"init"), "content"=>($content = "")));

foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_gaccess", 'Права доступа группы к модулю')) as $k=>$v){
	if(array_key_exists($v['gid'], $conf['user']['gid']) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
		$conf['modules'][ $v['mid'] ]['access'] = $v['access'];
}
foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_uaccess ORDER BY uid", 'Права доступа пользователя к модулю')) as $k=>$v){
	if ($conf['user']['uid'] == $v['uid'] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
		$conf['modules'][ $v['mid'] ]['access'] = $v['access'];
}

if (!empty($conf['settings']["theme/*:{$conf['settings']['fn']}"])) $conf['settings']['theme'] = $conf['settings']["theme/*:{$conf['settings']['fn']}"];
if (!empty($conf['settings']["theme/{$conf['settings']['modpath']}:*"])) $conf['settings']['theme'] = $conf['settings']["theme/{$conf['settings']['modpath']}:*"];
if (!empty($conf['settings']["theme/{$conf['settings']['modpath']}:{$conf['settings']['fn']}"])) $conf['settings']['theme'] = $conf['settings']["theme/{$conf['settings']['modpath']}:{$conf['settings']['fn']}"];

if ((strpos($conf['settings']['fn'], "admin") === 0) && $conf['settings']["theme/*:admin"]){
	$conf['settings']['theme'] = $conf['settings']["theme/*:admin"];
}
if(isset($_GET['theme']) && $_GET['theme'] != $conf['user']['sess']['theme']){
	$conf['user']['sess']['theme'] = $conf['settings']['theme'] = basename($_GET['theme']);
}
if(isset($_GET['m']['sqlanaliz'])){
	$zblocks = bcont();
	$content = mcont($content);
}else{
	$content = mcont($content);
	$zblocks = bcont();
}

if($t = mpopendir($f = "themes/{$conf['settings']['theme']}/". (array_key_exists("index", $conf['settings']) ? $conf['settings']['index'] : (array_key_exists("index", $_GET) ? basename($_GET['index']) : "index" )) . ".html")){
	if(get($conf, 'settings', 'theme_exec')){
		ob_start(); inc($f); $tc = ob_get_contents(); ob_clean();
	}else{
		$tc = file_get_contents($t);
	}
}else{ die("Шаблон {$f} не найден"); }

if(!array_key_exists('null', $_GET)){
	$content = str_replace('<!-- [modules] -->', $content, $tc);
} $content = strtr($content, (array)$zblocks);

$conf['settings']['microtime'] = substr(microtime(true)-$conf['settings']['microtime'], 0, 8);

/*foreach($conf['settings'] as $k=>$v){
	if(is_string($v)){
		$content = str_replace("<!-- [settings:$k] -->", $v, $content);
	}	
}*/ if(!array_key_exists("nocache", $_REQUEST)){
	if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
		exit(header('HTTP/1.0 304 Not Modified'));
	}else if(!array_search("Зарегистрированные", $conf['user']['gid'])){ # Исключаем админстраницу из кеширования
		header('Last-Modified: '. date("r"));
		header("Expires: ". gmdate("r", time()+(get($conf, 'settings', "themes_expires") ?: 86400)));
	}
} echo array_key_exists("null", $_GET) ? $content : strtr($content, mpzam($conf['settings'], "settings", "<!-- [", "] -->"));
