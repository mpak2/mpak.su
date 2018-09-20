<?

if(array_key_exists("null", $_GET)){ exit(200);
}else{ ?>
	<html>
	<head>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
	</head>
	<body>
<? }

function perm2str($perm){
	$perm = decbin($perm);
	$str = bindec(substr($perm, -12, 3));
	$str .= bindec(substr($perm, -9, 3));
	$str .= bindec(substr($perm, -6, 3));
	$str .= bindec(substr($perm, -3, 3));
	return $str;
}

$files_array = [ # Список папок и их прав доступа
	'include/images'=>'0777',
	'include/files'=>'0777',
];


if('sqlite' == get($conf, 'db', 'type')){
	if(!$_POST){
		$form = <<<EOF
			<div>
				<form method="post" style="padding:150px; text-align:center;">
					<p><input type="text" name="admin_usr" placeholder="Имя администратора"></p>
					<p><input type="password" name="admin_pass" placeholder="Пароль администратора"></p>
					<p><button>Установить</button></p>
				</form>
			</div>
EOF;
		exit($form);
	}elseif(!$admin_usr = get($_POST, "admin_usr")){ pre("Ошибка добавления администратора. Проверьте права доступа к базе данных");
	}elseif(!$admin_pass = get($_POST, "admin_pass")){ pre("Задайте пароль администратору");
	}elseif(!$users = fk("users-", $w = ["name"=>$admin_usr, "pass"=>mphash($admin_usr, $admin_pass)], $w += ["type_id"=>1], $w)){ pre("ОШИБКА добавления адмистратора");
	}elseif(!$grp = rb("users-grp", "name", "[". get($conf, 'settings', 'user_grp'). "]")){ pre("Ошибка выборки группы пользователей", get($conf, 'settings'));
	}elseif(!$mem = fk("users-mem", $w = ["uid"=>$users['id'], "grp_id"=>$grp['id']], $w)){ pre("Ошибка добавления администратора в группу `{$grp["name"]}`");
	}elseif(!$grp = rb("users-grp", "name", "[". get($conf, 'settings', 'admin_grp'). "]")){ pre("Ошибка добавления группы администраторов");
	}elseif(!$mem = fk("users-mem", $w = ["uid"=>$users['id'], "grp_id"=>$grp['id']], $w)){ pre("Ошибка добавления пользователя в группу администраторов");
	}elseif(!$settings = fk("settings-", $w = array("name"=>"admin_usr"), $w += array("modpath"=>"users", "aid"=>5, "value"=>$users['name'], "description"=>"Корень"), $w)){ pre("Ошибка установки администратора сайта");
	}else{ exit(header("Location: /admin")); }
}elseif(!file_exists($cf = array_shift(explode(':', $conf['db']["open_basedir"])). '/include/config.php') || !is_writable(mpopendir("include/images")) || !empty($conf['db']['error'])){
	echo "<table border=0 width=100% height=100%><tr><td align=center>";
	echo "Создание необходимых файлов и установка прав доступа:<p>";
	echo "<table cellspacing=0 cellpadding=3 border=0>";

	foreach($files_array as $k=>$v){
		if(file_exists($k)){
			echo "<tr><td><b>".$k."</b> установлеты ".perm2str(fileperms(mpopendir($k)))."</td><td>";
			if(perm2str(fileperms(mpopendir($k))) == $v){
				echo "директория готова к работе";
			}else{
				echo "<span style=color:red;>для работы необходимы $v</span>";
			}
			echo "</td>";
		}else{
			echo "<tr><td>Создайте директорию ".$k." в ФС</td>";
			echo "<td>установите права доступа $v</td>";
		}
	}

	if(!file_exists($cf) || !empty($conf['db']['error'])){
		echo "<tr><td colspan=2 style=text-align:center;>Создайте конфигурационный файл: <b>include/config.php</b>. Содержащий параметры доступа к БД</td></tr>";
		echo "<tr><td colspan=2 style=text-align:center;color:red;>". (empty($conf['db']['error']) ? 'Доступ к БД выполнен' : $conf['db']['error']). "</td></tr>";
		echo "<tr><td colspan=2></td></tr>";
	}
	echo "</table>";
}elseif(empty($_POST['theme'])){
	foreach(mpreaddir($folder = 'themes', 1) as $k=>$fn){
		if (!file_exists($file_name = mpopendir("$folder/$fn/screen.png"))) continue;
		$list .= "<div style=\"float:right; width:200px;\">
						<input".(empty($screen) ? ' checked' : '')." type=\"radio\" name=\"theme\" value=\"$fn\" onChange=\"getElementById('screen').src='http://mpak.su/themes/theme:$fn/null/screen.png'\">
					$fn</div>";
		if (empty($screen)){
			$screen = "http://mpak.su/themes/theme:$fn/null/screen.png";
		}
	}
	echo <<<EOF
	<form method=post style="padding:150px;">
		<div>
			<div style='border: 1px dashed gray; padding: 10px; background-color: #eee; width:240px; float:left; margin: 0 10px 10px 0;'>
				<img id='screen' src='$screen'>
			</div>
			$list
			<div style="clear:both;">
			<div style="text-align:right; width:100%;"><input type="submit"><div>
		</div>
	</form>
EOF;
}elseif(empty($_POST['modules'])){
	$en = array(3=>'admin', 'modules', 'blocks', 'users', 'settings', 'themes', 'menu', 'seo', 'pages', 'tinymce');
	$rec = array(3=>'services', 'develop', 'opros', 'faq', 'news', 'gbook', 'comments', 'poll', 'sql', 'search', 'foto', 'messages');
	if(strpos($conf['db']['open_basedir'], "phar://")){
		$en += array(1=>"include", "img");
	}
	foreach(mpreaddir($folder = 'modules', 1) as $k=>$file){
		if($file == 'null') continue;
		inc("modules/$file/info.php");
		if(array_search($file, $en)) $modules .= "<input type='hidden' name='modules[$file]' value='true'>";
		$modules .= "<div style='float:left; width:200px;'><input type='checkbox' name='modules[$file]' value='true' class='".(array_search($file, $rec) ? 'rec' : ''). (array_search($file, $en) ? ' min' : '')."'><span title='{$conf['modversion']['description']}' alt='{$conf['modversion']['description']}'>{$conf['modversion']['name']}</span></div>";
	}
	echo <<<EOF
	<script type="text/javascript" src="http://mpak.su/include/jquery/jquery.js"></script>
	<script>
		$(document).ready(function(){
			$('.min').attr('checked', 'checked').attr('disabled', 'disabled');
			$('.rec').attr('checked', 'checked');
			$('#sel').change(function(){
				if($(this).find('option:selected').val() == 'min'){
					$('.min').attr('checked', 'checked');
					$('input:checkbox').not('.min').removeAttr('checked');
				}else if($(this).find('option:selected').val() == 'rec'){ //if
					$('input:checkbox').not('.min').not('rec').removeAttr('checked');
					$('.rec').attr('checked', 'checked');
				}else{
					$('input:checkbox').attr('checked', 'checked');
				}
			}).change();
		});
	</script>
	<form method=post style="padding:100px;">
		<select id="sel" style="margin:10px;">
			<option value="min">Минимальные установки</option>
			<option value="rec">Рекомендуемые</option>
		</select>
		<input type='hidden' name='theme' value='{$_POST['theme']}'>
		<div>
			$modules
			<div style="clear:both;">
			<div style="text-align:right; width:100%;"><input type="submit"><div>
		</div>
	</form>
EOF;
}elseif(empty($_POST['user']) || empty($_POST['user']) || empty($_POST['pass1']) || ($_POST['pass1'] != $_POST['pass2']) || $_POST['submit'] != 'Продолжить'){
	echo "<form method=post>";
	echo "<input type='hidden' name='theme' value='{$_POST['theme']}'>";
	foreach($_POST['modules'] as $k=>$v){
		echo "<input type='hidden' name='modules[$k]' value='$v'>";
	}
	echo "<table border=0 width=100% height=100%><tr><td align=center>";
	echo "<b>Задайте свойства сайта</b><p>";
	echo "<table cellpadding=7px>";
	echo "<tr><td align='right'>Заголовок сайта:</td><td><input type='text' name='title' value='Жираф' style='width: 100%;'></td></tr>";
	echo "<tr><td align='right'>Имя администратора:</td><td><input type=text name='user'></td></tr>";
	echo "<tr><td align='right'>Пароль администратора:</td><td><input type=password name='pass1'></td></tr>";
	echo "<tr><td align='right'>Повторите пароль:</td><td><input type=password name='pass2'></td></tr>";
	echo "<tr><td align='right'>&nbsp;</td><td><input type='submit' name='submit' value='Продолжить'></td></tr>";
	echo "</table></td></tr></table></form>";
}else{
	# Подключаем модули, запускаем портальную систему
	foreach(array('settings', 'modules') as $k=>$v){
		inc("modules/$v/init.php", array('arg'=>array('modpath'=>$v)));
		inc("modules/$v/sql.php", array('arg'=>array('modpath'=>$v)));
	}

	echo "<div style='margin:100px;'>Устанавливаются модули: <p>";
	foreach(mpreaddir($folder = 'modules', 1) as $k=>$file){
		if($file == '.' || $file == '..' || $file == 'index.html' || $file == 'null' || $file == '.htaccess' || empty($_POST['modules'][$file])) continue;
		if (file_exists(mpopendir($info = "modules/$file/info.php"))){
			inc("modules/$file/info.php");
			echo $conf['modversion']['description']. ', ';
			qw("INSERT INTO {$conf['db']['prefix']}modules_index (`folder`, `name`, `author`, `contact`, `version`, `description`, `enabled`, `admin_access`, `admin`) VALUES ('$file', '{$conf['modversion']['name']}', '{$conf['modversion']['author']}', '{$conf['modversion']['contact']}', '{$conf['modversion']['version']}', '{$conf['modversion']['description']}', 2, ".(strlen($conf['modversion']['admin_access']) ? $conf['modversion']['admin_access'] : '1').", {$conf['modversion']['admin']})");
		} if($file != 'settings' && $file != 'modules') $scripts[] = $file;
	}
	foreach($scripts as $k=>$file){
		inc("modules/$file/init.php", array('arg'=>array('modpath'=>$file)));
		inc("modules/$file/sql.php", array('arg'=>array('modpath'=>$file)));
	}

	qw("UPDATE {$conf['db']['prefix']}settings SET `value`='{$_POST['title']}' WHERE `name`='title'");
	qw("UPDATE {$conf['db']['prefix']}settings SET `value`='{$_POST['user']}' WHERE `name`='admin_usr'");
	qw("UPDATE {$conf['db']['prefix']}settings SET `value`='/pages/1' WHERE `name`='start_mod'");

	# Добавляем доступ группы Администратор к модулю админстраница
	$admin_grp_id = mpfdk("{$conf['db']['prefix']}users_grp", $w = array("name"=>"Администратор"), $w);
	qw("INSERT INTO {$conf['db']['prefix']}modules_gaccess (`mid`, `gid`, `admin_access`, `description`) VALUE ((SELECT id FROM {$conf['db']['prefix']}modules WHERE folder='admin'), ". (int)$admin_grp_id. ", 1, 'Доступ на чтение модуля админменю группе администраторов')");
	qw("UPDATE `{$conf['db']['prefix']}settings` SET `value`='{$_POST['theme']}' WHERE `name`='theme'");
	setcookie("{$conf['db']['prefix']}sess", ($sess = md5("{$_SERVER['REMOTE_ADDR']}:".microtime())));
	qw("INSERT INTO `{$conf['db']['prefix']}users_sess` SET uid=(SELECT id FROM {$conf['db']['prefix']}users WHERE name=\"".mpquot($_POST['user'])."\"), last_time=".time().", ip=\"".mpquot($_SERVER['REMOTE_ADDR'])."\", agent=\"".mpquot($_SERVER['HTTP_USER_AGENT'])."\", sess=\"$sess\"");
	echo "<p>Утановка завершена. <a href=/>Перейти на сайт</a></div>";
} echo "</body></html>";
