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

ini_set('display_errors', 1); error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
header('Content-Type: text/html;charset=UTF-8');

if(!function_exists('mp_require_once')){
	function mp_require_once($link){
		global $conf, $arg, $tpl;
		foreach(explode(':', $conf['fs']['path'], 2) as $k=>$v){
			if (!file_exists($file_name = "$v/$link")) continue;
			include_once($file_name); return;
		}
	}
}
require_once("config/config.php"); # Конфигурация
mp_require_once("config/config.php"); # Конфигурация
mp_require_once("include/mpfunc.php"); # Функции системы

$_REQUEST += $_GET += mpgt($_SERVER['REQUEST_URI'], $_GET);

if (!isset($index) && file_exists($index = array_shift(explode(':', $conf['fs']['path'], 2)). '/index.php')){
	include($index); if($content) die;
}

if(!empty($_GET['m']) && array_search('admin', (array)$_GET['m']))
	mp_require_once("include/func.php"); # Функции таблиц

if(!function_exists('mysql_connect')){
	echo "no function mysql"; die;
}else if(empty($conf['db']['conn'])){ # При подключении копии файла повторное подключение базы даных не требуется
	$conf['db']['conn'] = @mysql_connect($conf['db']['host'], $conf['db']['login'], $conf['db']['pass']); # Соединение с базой данных
}
if (strlen($conf['db']['error'] = mysql_error())){
#	echo "Ошибка соединения с базой данных";
}else{
	mysql_select_db($conf['db']['name'], $conf['db']['conn']);
	mpqw("SET NAMES 'utf8'");
} unset($conf['db']['pass']); $conf['db']['sql'] = array();


if ((!array_key_exists('null', $_GET) && !empty($conf['db']['error'])) || !count(mpql(mpqw("SHOW TABLES", 'Проверка работы базы')))){ echo mpct('include/install.php'); die; }

if(array_key_exists('themes', (array)$_GET['m']) && empty($_GET['m']['themes']) && array_key_exists('null', $_GET)){
//	if($_SERVER['HTTP_IF_MODIFIED_SINCE']) exit(header('HTTP/1.1 304 Not Modified'));
	if(empty($_GET['theme'])){
		$_GET['theme'] = mpql(mpqw("SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"theme\""), 0, 'value');
	} $ex = array('otf'=>'font/opentype', 'css'=>'text/css', 'js'=>'text/javascript', 'swf'=>'application/x-shockwave-flash', 'ico' => 'image/x-icon', 'woff'=>'application/x-font-woff', 'svg'=>'font/svg+xml', 'tpl'=>'text/html');
	$fn = "themes/{$_GET['theme']}/{$_GET['']}";
	$ext = array_pop(explode('.', $fn));
	header("Content-type: ". ($ex[$ext] ? $ex[$ext] : "image/$ext"));
	if($ex[$ext]){
		readfile(mpopendir($fn));
	}else{
		echo mprs(mpopendir($fn), $_GET['w'], $_GET['h'], $_GET['c']);
	} die;
}

$conf['db']['info'] = 'Загрузка свойств модулей';
$conf['settings'] = array('http_host'=>$_SERVER['HTTP_HOST'])+spisok("SELECT `name`, `value` FROM `{$conf['db']['prefix']}settings`");
if($conf['settings']['users_log']) $conf['event'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}users_event"), "name");

$conf['settings']['access_array'] = array('0'=>'Запрет', '1'=>'Чтение', '2'=>'Добавл', '3'=>'Запись', '4'=>'Модер', '5'=>'Админ');
if ($conf['settings']['microtime']) $conf['settings']['microtime'] = microtime();

if($conf['settings']['del_sess']){
	$func = create_function('&$val, $key','$val = strtr(stripslashes($val), array("\\\\"=>"&#92;", \'"\'=>"&#34;", "\'"=>"&#39;"));');
	array_walk ($get = $_GET, $func); $post = $_POST;
	if (isset($post['pass'])) $post['pass'] = 'hide';
	if (isset($post['pass2'])) $post['pass2'] = 'hide';
	array_walk ($post, $func); array_walk ($files = $_FILES, $func); array_walk ($server = $_SERVER, $func);
	$request = serialize(array('$_POST'=>$post, '$_GET'=>$get, '$_FILES'=>$files, '$_SERVER'=>$server));
} setlocale (LC_ALL, "Russian"); putenv("LANG=ru_RU");// bindtextdomain("messages", "./locale"); textdomain("messages"); bind_textdomain_codeset('messages', 'CP1251'); //setlocale(LC_ALL, "ru_RU.CP1251")

if (!$guest_id = mpql(mpqw("SELECT id as gid FROM {$conf['db']['prefix']}users WHERE name='{$conf['settings']['default_usr']}'", "Получаем свойства пользователя гость"), 0, "gid")){ # Создаем пользователя в случае если его нет
	$guest_id = mpfdk("{$conf['db']['prefix']}users", $w = array("name"=>$conf['settings']['default_usr']), $w += array("pass"=>"nopass", "reg_time"=>time(), "last_time"=>time()));
	$guest_grp_id = mpfdk("{$conf['db']['prefix']}users_grp", $w = array("name"=>$conf['settings']['default_grp']), $w);
	$guest_mem_id = mpfdk("{$conf['db']['prefix']}users", $w = array("uid"=>$guest_id, "grp_id"=>$guest_grp_id), $w);
}

$sess = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}sess WHERE `ip`='{$_SERVER['REMOTE_ADDR']}' AND last_time>=".(time()-$conf['settings']['sess_time'])." AND `agent`=\"".mpquot($_SERVER['HTTP_USER_AGENT']). "\" AND (". ($_COOKIE["{$conf['db']['prefix']}sess"] ? "sess=\"". mpquot($_COOKIE["{$conf['db']['prefix']}sess"]). "\"" : "uid=". (int)$guest_id).") ORDER BY id DESC", 'Получаем свойства текущей сессии'), 0);
if(!$sess){
	$sess = array('uid'=>$guest_id, 'sess'=>md5("{$_SERVER['REMOTE_ADDR']}:".microtime()), 'ref'=>mpidn(urldecode($_SERVER['HTTP_REFERER'])), 'ip'=>$_SERVER['REMOTE_ADDR'], 'agent'=>$_SERVER['HTTP_USER_AGENT'], 'url'=>$_SERVER['REQUEST_URI']);
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}sess (uid, ref, sess, last_time, ip, agent, url) VALUES (". (int)$guest_id. ", '". mpquot($sess['ref']). "', \"". mpquot($_COOKIE["{$conf['db']['prefix']}sess"]). "\", ".time().", '". mpquot($sess['ip']). "', '".mpquot($sess['agent'])."', '".mpquot($sess['url'])."')");
	$sess['id'] = mysql_insert_id();
}

if($_COOKIE["{$conf['db']['prefix']}sess"] != $sess['sess']){
	$sess['sess'] = md5("{$_SERVER['REMOTE_ADDR']}:".microtime());
	setcookie("{$conf['db']['prefix']}sess", $sess['sess'], 0, "/");
}

if ($conf['settings']['del_sess'] && ($conf['settings']['del_sess'] != 3 || $_SERVER['REQUEST_METHOD'] != 'GET' )){
	mpqw("INSERT INTO {$conf['db']['prefix']}sess_post (sid, url, time, method, post) VALUE ({$sess['id']}, '{$_SERVER['QUERY_STRING']}', ".time().", '{$_SERVER['REQUEST_METHOD']}', '$request')", 'Обновляем свойства сессии');
}

//if(!array_key_exists("null", $_GET)){ # Обновление информации о сессии При запросе ресурса обязательна
	mpqw("UPDATE {$conf['db']['prefix']}sess SET count_time = count_time+".time()."-last_time, last_time=".time().", ".(isset($_GET['null']) ? 'cnull=cnull' : 'count=count')."+1, sess=\"". mpquot($sess['sess']). "\" WHERE id=". (int)$sess['id']);
//}

if (strlen($_POST['name']) && strlen($_POST['pass']) && $_POST['reg'] == 'Аутентификация' && $uid = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}users WHERE type_id=1 AND name = \"".mpquot($_POST['name'])."\" AND pass='".mphash($_POST['name'], $_POST['pass'])."'", 'Проверка существования пользователя'), 0, 'id')){
	mpqw($sql = "UPDATE {$conf['db']['prefix']}sess SET uid=".($sess['uid'] = $uid)." WHERE id=". (int)$sess['id']);// echo $sql;
	mpqw("UPDATE {$conf['db']['prefix']}users SET last_time=". time(). " WHERE id=".(int)$uid);
//	header("Location: ". $_SERVER['REQUEST_URI']); exit;
}elseif(isset($_GET['logoff'])){ # Если пользователь покидает сайт
	mpqw("UPDATE {$conf['db']['prefix']}sess SET sess = '!". mpquot($sess['sess']). "' WHERE id=". (int)$sess['id'], 'Выход пользователя');
	if(!empty($_SERVER['HTTP_REFERER'])){
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
$conf['user']['gid'] = spisok("SELECT g.id, g.name FROM {$conf['db']['prefix']}users_grp as g, {$conf['db']['prefix']}users_mem as m WHERE (g.id=m.grp_id) AND m.uid = {$sess['uid']}");
$conf['user']['sess'] = $sess;



if ($conf['settings']['start_mod'] && !$_GET['m']){
	if (strpos($conf['settings']['start_mod'], 'array://') === 0){
		$_GET = unserialize(substr($conf['settings']['start_mod'], 8));
	}else if(strpos($conf['settings']['start_mod'], "http://") === 0){
		header("Location: {$conf['settings']['start_mod']}"); exit;
	}else{
		$_GET = mpgt($_SERVER['REQUEST_URI'] = $conf['settings']['start_mod']);
	}
} $content = (($init = mpopendir("include/init.php")) ? mpct($init, $arg = array("access"=>(array_search($conf['settings']['admin_grp'], $conf['user']['gid']) ? 5 : 1))) : ""); # Установка предварительных переменных

foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE enabled = 2", 'Информация о модулях')) as $k=>$v){
	if (array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false) $v['access'] = 5; # Права суперпользователя
	$conf['modules'][ $v['folder'] ] = $v;
	$conf['modules'][ $v['folder'] ]['modname'] = (strpos($_SERVER['HTTP_HOST'], "xn--") !== false) ? mb_strtolower($v['name']) : $v['folder'];
	$conf['modules'][ $v['name'] ] = &$conf['modules'][ $v['folder'] ];
	$conf['modules'][ mb_strtolower($v['name']) ] = &$conf['modules'][ $v['folder'] ];
	$conf['modules'][ $v['id'] ] = &$conf['modules'][ $v['folder'] ];
}

if(!array_key_exists("null", $_GET) && $conf['modules']['seo']){
	if($_GET['p']){
		$r = strtr($_SERVER['REQUEST_URI'], array("?p={$_GET['p']}"=>"", "&p={$_GET['p']}"=>"", "/p:{$_GET['p']}"=>""));
	}else{ $r = $_SERVER['REQUEST_URI']; }// exit($r);
	if($redirect = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}seo_redirect WHERE `from`=\"". $r. "\""), 0)){
		$_GET = mpgt($redirect['to'])+array_diff_key($_GET, array("m"=>"Устаревшие адресации"));
	}
}

list($m, $f) = (array)each($_GET['m']); # Отображение меню с выбором раздела для модуля администратора

$conf['settings']['modpath'] = $conf['modules'][ array_shift(array_keys($_GET['m'])) ]['folder'];
$conf['settings']['fn'] = array_shift(array_values($_GET['m'])) ? array_shift(array_values($_GET['m'])) : "index";

//print_r(array_shift(array_keys($_GET['m'])));
if($_GET['id'] && $conf['settings']['modules_default'] && empty($conf['modules'][ ($mp = array_shift(array_keys($_GET['m']))) ])){
	$_GET['m'] = array($conf['settings']['modules_default']=>$_GET['m'][ $mp ]);
} # Устанавливаем дефолтный раздел. Если нет среди установленных то он считает что страничка оттуда


foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_gaccess", 'Права доступа группы к модулю')) as $k=>$v){
	if ( $conf['user']['gid'][ $v['gid'] ] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
		$conf['modules'][ $v['mid'] ]['access'] = $v['access'];
}
foreach((array)mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_uaccess ORDER BY uid", 'Права доступа пользователя к модулю')) as $k=>$v){
	if ($conf['user']['uid'] == $v['uid'] && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) === false)
		$conf['modules'][ $v['mid'] ]['access'] = $v['access'];
}

if (!function_exists('bcont')){
	function bcont($bid = null){# Загружаем список блоков и прав доступа
		global $theme, $conf;
		$conf['db']['info'] = "Выборка шаблонов блоков";
		$shablon = spisok("SELECT id, shablon FROM {$conf['db']['prefix']}blocks_shablon");

		$blocks = qn("SELECT * FROM {$conf['db']['prefix']}blocks WHERE enabled=1". ($bid ? " AND id=". (int)$bid : " ORDER BY orderby"));
		$blocks_reg = qn("SELECT * FROM {$conf['db']['prefix']}blocks_reg");
		$blocks_reg_modules = qn("SELECT * FROM {$conf['db']['prefix']}blocks_reg_modules ORDER BY sort");

		foreach($_GET['m'] as $k=>$v){
			$md[ $k ] = $v ? $v : "index";
		}

		foreach($blocks_reg as $r){
			if($r["term"] == 0){ # Условия выключены
				$reg[ $r['id'] ] = $r;
			}else{
				$br = array_shift($brm = rb($blocks_reg_modules, "reg_id", "id", $r['id']));
				if($br['name']){ # Если стоит страница
					$br = array_shift($brm = rb($brm, "name", "id", array_flip($md)));
				} if($br['modules_index']){
					$br = array_shift($brm = rb($brm, "modules_index", "id", rb($conf['modules'], "folder", "id", $md)));
				} if($br['theme']){ # Условие на тему
					$br = array_shift($brm = rb($brm, "theme", "id", array_flip(array($conf['settings']['theme']))));
				} if($br['uri']){ # Адрес страницы в системе. Всегда не главная. (может быть не равен $_SERVER['REDIRECT_URL'])
					$br = array_shift($brm = rb($brm, "uri", "id", array_flip(array($_SERVER['REQUEST_URI']))));
				} if($br['url']){ # Адрес страницы из адресной строки браузера работает если нужно поставил условием главную страницу
					$br = array_shift($brm = rb($brm, "url", "id", array_flip(array($_SERVER['REDIRECT_URL']))));
				}// mpre(array_flip($md)); mpre($br);
				if(!empty($brm) && ($r["term"] > 0)){ # Условие только
					$reg[ $r['id'] ] = $r;
				}elseif(empty($brm) && ($r["term"] < 0)){ # Исключающее условие
					$reg[ $r['id'] ] = $r;
				}
			} # Условие исключая не срабатывает
		}

		$gt = mpgt(urldecode(array_pop(explode("/{$_SERVER['HTTP_HOST']}", $_SERVER['HTTP_REFERER']))));
		$uid = array_key_exists('blocks', $_GET['m']) ? $gt['id'] : $_GET['id'];
		$uid = (array_intersect_key(array($conf['modules']['users']['folder']=>1, $conf['modules']['users']['modname']=>2), (array_key_exists('blocks', $_GET['m']) ? (array)$gt['m'] : array())+$_GET['m']) && $uid) ? $uid : $conf['user']['uid'];


		foreach(rb($blocks, "rid", "id", $reg) as $k=>$v){
			$conf['blocks']['info'][$v['id']] = $v;
			if(($v['access'] < 0)){
				$conf['blocks']['info'][ $v['id'] ]['access'] = (int)$conf['modules'][array_shift(explode('/', $v['file']))]['access'];
			}
		}

		foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}blocks_gaccess ORDER BY id", 'Права доступа группы к блоку')) as $k=>$v)
			if ($conf['user']['gid'][ $v['gid'] ]) $conf['blocks']['info'][ $v['bid'] ]['access'] = $v['access'];

		foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}blocks_uaccess ORDER BY id", 'Права доступа пользователя к блоку')) as $k=>$v)
			if ($conf['user']['uid'] == $v['uid'] || (!$v['uid'] && ($conf['user']['uid'] == $uid)))
				$conf['blocks']['info'][ $v['bid'] ]['access'] = $v['access'];

		foreach(rb($blocks, "rid", "id", $reg) as $k=>$v){
			$conf['db']['info'] = "Блок '{$conf['blocks']['info'][ $v['id'] ]['name']}'";
			$mod = $conf['modules'][ $modpath = basename(dirname(dirname($v['file']))) ];
			$modname = $mod['modname'];
			if ($conf['blocks']['info'][ $v['id'] ]['access'] && strlen($cb = mpeval("modules/{$v['file']}", $arg = array('blocknum'=>$v['id'], 'modpath'=>$modpath, 'modname'=>$modname, 'fn'=>basename(array_shift(explode('.', $v['file']))), 'uid'=>$uid, 'access'=>$conf['blocks']['info'][ $v['id'] ]['access']) ))){
				if($bid){ $result = $cb; }else{
					if (!is_numeric($v['shablon']) && file_exists($file_name = mpopendir("themes/{$conf['settings']['theme']}/". ($v['shablon'] ? $v['shablon'] : "block.html")))){
						$shablon[ $v['shablon'] ] = file_get_contents($file_name);
					}
					$cb = strtr($shablon[ $v['shablon'] ], $w = array(
						'<!-- [block:content] -->'=>$cb,
						'<!-- [block:id] -->'=>$v['id'],
						'<!-- [block:name] -->'=>$v['name'],
						'<!-- [block:modpath] -->'=>$arg['modpath'],
						'<!-- [block:fn] -->'=>$arg['fn'],
						'<!-- [block:title] -->'=>$v['name']
					));
					$section = array("{modpath}"=>$arg['modpath'],"{modname}"=>$arg['modname'], "{name}"=>$v['name'], "{fn}"=>$arg['fn'], "{id}"=>$v['id']);
					$result["<!-- [blocks:". (int)$v['rid'] . "] -->"] .= strtr($conf['settings']['blocks_start'], $section). $cb. strtr($conf['settings']['blocks_stop'], $section);
					$result["<!-- [blocks:". (int)$reg[ $v['rid'] ]['reg_id']. "] -->"] .= strtr($conf['settings']['blocks_start'], $section). $cb. strtr($conf['settings']['blocks_stop'], $section);
				}
			}
		} return $result;
	}
}

if (!function_exists('mcont')){
	function mcont($content){ # Загрузка содержимого модуля
		global $conf, $arg, $tpl;
		foreach($_GET['m'] as $k=>$v){ $k = urldecode($k);
			$mod = $conf['modules'][ $k ];
			$mod['link'] = (is_link($f = mpopendir("modules/{$mod['folder']}")) ? readlink($f) : $mod['folder']);
			ini_set("include_path" ,mpopendir("modules/{$mod['link']}"). ":./modules/{$mod['link']}:". ini_get("include_path"));
			if($conf['settings']['modules_title']){
				$conf['settings']['title'] = $conf['modules'][ $k ]['name']. ' : '. $conf['settings']['title'];
			}

			$v = $v != 'del' && $v != 'init' && $v != 'sql' && strlen($v) ? $v : 'index';
			if ( ((strpos($v, 'admin') === 0) ? $conf['modules'][$k]['access'] >= 4 : $conf['modules'][$k]['access'] >= 1) ){
				$conf['db']['info'] = "Модуль '". ($name = $mod['name']). "'";

				if(preg_match("/[a-z]/", $v, $arg)){
					$g = "/*{$v}.*php";
				}else{
					$g = "/*.{$v}.php";
				}
				if(($glob = glob($gb = (mpopendir("modules/{$mod['link']}"). $g)))
					|| ($glob = glob($gb = ("modules/{$mod['link']}". $g)))){
					$glob = basename(array_pop($glob));
					$g = explode(".", $glob);
					$v = array_shift($g);
				}// print_r($mod); exit;

				$fe = ((strpos($_SERVER['HTTP_HOST'], "xn--") !== false) && (count($g) > 1)) ? array_shift($g) : $v;
				$arg = array('modpath'=>$mod['folder'], 'modname'=>$mod['modname'], 'fn'=>$v, "fe"=>$fe, 'access'=>$mod['access']);

				if($glob){
					$content .= mpct("modules/{$mod['link']}/$glob", $arg);
				}elseif(($tmp = mpct("modules/{$mod['link']}/". ($fn = $v). ".php", $arg)) === false){
					$content .= mpct("modules/{$mod['link']}/". ($fn = "default"). ".php", $arg);
				}else{
					$content .= $tmp;
				} if(!empty($tpl) || !empty($conf['tpl'])) $tpl = array_merge((array)$tpl, (array)$conf['tpl']); $conf['tpl'] = $tpl;

				ob_start();
				if (mpopendir("modules/{$mod['link']}/$v.tpl")){# Проверяем модуль на файл шаблона
					mp_require_once("modules/{$mod['link']}/$v.tpl");
				}else if(mpopendir("modules/{$mod['link']}/$v.js")){
					if(array_key_exists("null", $_GET)) header("Content-type: application/x-javascript");
					mp_require_once("modules/{$mod['link']}/$v.js");
				}else if(mpopendir("modules/{$mod['link']}/$v.css")){
					if(array_key_exists("null", $_GET)) header("Content-type: text/css");
					mp_require_once("modules/{$mod['link']}/$v.css");
				}else{# Дефолтный файл
					mp_require_once("modules/{$mod['link']}/$fn.tpl");
				} $content .= ob_get_contents(); ob_end_clean();

			}else{
				if (file_exists(mpopendir("modules/{$mod['link']}/deny.php"))){
					$content = mpct("modules/{$mod['link']}/deny.php", $conf['arg'] = array('modpath'=>$mod['folder']));
				}else if(!array_key_exists("themes", $_GET)){
					if(!array_key_exists('null', $_GET) && ($_SERVER['REQUEST_URI'] != "/admin")){
						header('HTTP/1.0 404 Unauthorized');
//						echo "<div style='margin:100px 0;text-align:center'>Доступ запрещен</div>";
						header("Location: /themes:404{$_SERVER['REQUEST_URI']}");// header("Location: /admin");
					}
				}
			}
		}
		return $content;
	}
}

$m = array_shift(array_keys($_GET['m']));
$f = array_shift(array_values($_GET['m']));

if (empty($f)) $f = 'index';
if (!empty($conf['settings']["theme/*:$f"])) $conf['settings']['theme'] = $conf['settings']["theme/*:$f"];
if (!empty($conf['settings']["theme/$m:*"])) $conf['settings']['theme'] = $conf['settings']["theme/$m:*"];
if (!empty($conf['settings']["theme/$m:$f"])) $conf['settings']['theme'] = $conf['settings']["theme/$m:$f"];

if ((strpos($f, "admin") === 0) && $conf['settings']["theme/*:admin"])
	$conf['settings']['theme'] = $conf['settings']["theme/*:admin"];

if(isset($_GET['theme']) && $_GET['theme'] != $conf['user']['sess']['theme']){
	$conf['user']['sess']['theme'] = $conf['settings']['theme'] = basename($_GET['theme']);
}elseif($conf['user']['sess']['theme']){
	$conf['settings']['theme'] = $conf['user']['sess']['theme'];
}

if (is_numeric($conf['settings']['theme'])){
	$sql = "SELECT b.theme as btheme, t.* FROM mp_themes as t LEFT JOIN mp_themes_blk as b ON t.id=b.tid WHERE t.id=".(int)$conf['settings']['theme']." ORDER BY b.sort";
	$theme = mpql(mpqw($sql, 'Запрос темы'), 0);
	$tc = $theme['theme'];
}else{
	$tc = file_get_contents(mpopendir("themes/{$conf['settings']['theme']}/". ($_GET['index'] ? basename($_GET['index']) : "index"). ".html"));
}// $tpl = array(1);

if (!array_key_exists('null', $_GET) || !empty($_GET['m']['users'])){
	if (isset($_GET['m']['sqlanaliz'])) $zblocks = bcont();
} $content .= mcont($content);
if (!array_key_exists('null', $_GET) || !empty($_GET['m']['users'])){
	if (!isset($_GET['m']['sqlanaliz'])) $zblocks = bcont();
//	if (strpos($tc, '<!-- [modules] -->')){
		if(!array_key_exists('null', $_GET)){
			$content = str_replace('<!-- [modules] -->', $content, $tc);
		} $content = strtr($content, (array)$zblocks);
//	}
}// echo $content;

if ($conf['settings']['microtime']){
	$conf['settings']['microtime'] = (substr(microtime(), strpos(microtime(), ' ')) - substr($conf['settings']['microtime'], strpos($conf['settings']['microtime'], ' ')) + microtime() - $conf['settings']['microtime']);
}

$aid = spisok("SELECT id, aid FROM {$conf['db']['prefix']}settings");
foreach($conf['settings'] as $k=>$v){
	$content = str_replace("<!-- [settings:$k] -->", $v, $content);
} echo $content;
