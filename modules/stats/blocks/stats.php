<? # Статистика

if ((int)$arg['confnum']){
	# Сохранение и востановление параметров модуля
	if (isset($_POST['param'])){
		$param = $_POST['param'];
		$sql = "UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}";
		mpqw($sql);
	}else{
		if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
			$param = unserialize($res[0]['param']);
	}

	echo "<form action='{$_SERVER['REQUEST_URI']}' method='post'>";
	echo "Отображение статистики: <select name='param[activ]'>";
		echo "<option value='1'>Отображать</option>";
		echo "<option value='0'".(!$param['activ'] ? 'selected' : '').">Скрывать</option>";
	echo "</select>";
	echo "<p><input type='submit' value='Сохранить'>";
	echo "</form>";
	return;
}

# Выбор параметров
if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"))))
	$param = @unserialize($res[0]['param']);

if(eregi("Googlebot", $_SERVER["HTTP_USER_AGENT"])) $browser = "Googlebot";
elseif(eregi("StackRambler", $_SERVER["HTTP_USER_AGENT"])) $browser = "Rambler";
elseif(eregi("Twiceler", $_SERVER["HTTP_USER_AGENT"])) $browser = "Twiceler";
elseif(eregi("msnbot", $_SERVER["HTTP_USER_AGENT"])) $browser = "msnbot";
elseif(ereg("Firefox",$_SERVER["HTTP_USER_AGENT"])) $browser = "Firefox";
elseif((ereg("Nav", $_SERVER["HTTP_USER_AGENT"]))
	|| (ereg("Gold", $_SERVER["HTTP_USER_AGENT"]))
	|| (ereg("X11", $_SERVER["HTTP_USER_AGENT"]))
	|| (ereg("Mozilla", $_SERVER["HTTP_USER_AGENT"]))
	|| (ereg("Netscape", $_SERVER["HTTP_USER_AGENT"]))
		AND (!ereg("MSIE", $_SERVER["HTTP_USER_AGENT"]))
		AND (!ereg("Konqueror", $_SERVER["HTTP_USER_AGENT"]))) $browser = "Netscape";
    // Opera needs to be above MSIE as it pretends to be an MSIE clone
elseif(ereg("Opera", $_SERVER["HTTP_USER_AGENT"])) $browser = "Opera";
elseif(ereg("MSIE", $_SERVER["HTTP_USER_AGENT"])) $browser = "MSIE";
elseif(ereg("Firefox",$_SERVER["HTTP_USER_AGENT"])) $browser = "Firefox";	
elseif(ereg("Lynx", $_SERVER["HTTP_USER_AGENT"])) $browser = "Lynx";
elseif(ereg("WebTV", $_SERVER["HTTP_USER_AGENT"])) $browser = "WebTV";
elseif(ereg("Konqueror", $_SERVER["HTTP_USER_AGENT"])) $browser = "Konqueror";
else $browser = "Other";

if(ereg("Win", $_SERVER["HTTP_USER_AGENT"])) $os = "Windows";
elseif((ereg("Mac", $_SERVER["HTTP_USER_AGENT"])) || (ereg("PPC", $_SERVER["HTTP_USER_AGENT"]))) $os = "Mac";
elseif(ereg("Linux", $_SERVER["HTTP_USER_AGENT"])) $os = "Linux";
elseif(ereg("FreeBSD", $_SERVER["HTTP_USER_AGENT"])) $os = "FreeBSD";
elseif(ereg("SunOS", $_SERVER["HTTP_USER_AGENT"])) $os = "SunOS";
elseif(ereg("IRIX", $_SERVER["HTTP_USER_AGENT"])) $os = "IRIX";
elseif(ereg("BeOS", $_SERVER["HTTP_USER_AGENT"])) $os = "BeOS";
elseif(ereg("OS/2", $_SERVER["HTTP_USER_AGENT"])) $os = "OS/2";
elseif(ereg("AIX", $_SERVER["HTTP_USER_AGENT"])) $os = "AIX";
else $os = "Other";

# Статистика
$modules = spisok("SELECT folder, 'true' FROM {$GLOBALS['conf']['db']['prefix']}modules");
foreach($_GET['m'] as $k=>$v){
//	echo "$k=>$v";
	if (!strlen($modules[$k])) continue;
	if ($k != 'admin' && $v != 'admin')
		$m .= (strlen($m) ? ', ' : '').$k;
	else
		$m = 'admin';
}

$stat = array('modules'=>$m, 'browser'=>$browser, 'os'=>$os, 'day_of_week'=>date('w'), 'month'=>date('n'), 'hours'=>date('g'));
if (strlen($_SERVER['HTTP_REFERER']) && substr($_SERVER['HTTP_REFERER'], 0, strlen('http://'.$_SERVER['HTTP_HOST'])) != 'http://'.$_SERVER['HTTP_HOST'] && substr($_SERVER['HTTP_REFERER'], 0, strlen('http://'.$_SERVER['HTTP_HOST'])) != 'http://www.'.$_SERVER['HTTP_HOST'])
	$stat += array('referer'=>"<a href=\"{$_SERVER['HTTP_REFERER']}\" target=_blank>".(ereg ("(http://([-a-zA-Z0-9._]{3,64}))", $_SERVER['HTTP_REFERER'], $host) && strlen($host[2]) ? $host[1] : htmlspecialchars($_SERVER['HTTP_REFERER']))."</a> => <a href=\"".htmlspecialchars(basename($_SERVER['REQUEST_URI']))."\" target=_blank>".substr($_SERVER['REQUEST_URI'], 0, 30).(strlen(basename($_SERVER['REQUEST_URI'])) > 30 ? ' ...' : '')."</a>");

if ($param['activ']) echo "<table width='100%'><tr><td><b>Параметр</b></td><td><b>Значение</b></td><td><b>Количество</b></td></tr>";
foreach($stat as $k=>$v){
	if (!count($res = mpql(mpqw("SELECT id, count FROM {$GLOBALS['conf']['db']['prefix']}stats WHERE param = '$k' AND value = '$v'")))){
		mpqw("INSERT INTO {$GLOBALS['conf']['db']['prefix']}stats (param, value, count) VALUE ('$k', '$v', 1)");
	}
	if ($param['activ']) echo "<tr><td>$k</td><td>$v</td><td>{$res['0']['count']}</td></tr>";
	mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}stats SET count = count + 1 WHERE param = '$k' AND value = '$v'");
}
if ($param['activ']) echo "</table>";

?>
