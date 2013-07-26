<? die;
// ----------------------------------------------------------------------
// mpak Content Management System
// Copyright (C) 2007 by the mpak.
// (Link: http://mp.s86.ru)
// ----------------------------------------------------------------------
// LICENSE and CREDITS
// This program is free software and it's released under the terms of the
// GNU General Public License(GPL) - (Link: http://www.gnu.org/licenses/gpl.html)http://www.gnu.org/licenses/gpl.html
// Please READ carefully the Docs/License.txt file for further details
// Please READ the Docs/credits.txt file for complete credits list
// ----------------------------------------------------------------------
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 3462 634132
// Purpose of file:
// ----------------------------------------------------------------------

mpmenu($m = array('Модули', 'Доступ групп', 'Доступ пользователей', 'Скрипты'));

if ($m[(int)$_GET['r']] == 'Модули'){ # Добавление модуля


/*	$modules_list = array_flip( spisok("SELECT id, folder FROM {$conf['db']['prefix']}modules") );
	foreach(mpreaddir("modules", 1) as $k=>$file_name){
		if ( !isset($modules_list[$file_name]) ){
			if (substr($file_name, 0, 1) != '.' && $file_name != 'null'){
				if(($f = mpopendir("modules/$file_name")) && is_link($f)){
					echo "<br>". mpopendir("modules/". readlink($f). "/init.php"). "</br>";
				}else{
					echo "<br>". mpopendir("modules/$file_name/init.php"). "</br>";
				}
//				echo "<h2>{$file_name}</h2> {modules/$file_name/info.php}";
				mpct("modules/$file_name/info.php", array('modpath'=>$file_name));
				mpre($conf['modversion']);
				$modlist[] = "<a title=\"{$conf['modversion']['description']}\" alt=\"{$conf['modversion']['description']}\" href='?m[modules]=admin".($_GET['r'] ? "&r={$_GET['r']}" : '')."&install=$file_name'>{$conf['modversion']['name']}</a>";
			}
		}
	}*/

	if($_GET['del'] && !mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id = {$_GET['del']}"), 0)){
		if (file_exists($del = mpopendir("modules/{$mod['folder']}")."/del.php")){
			echo "<p>Сценарий удаления модуля<p>";
			echo mpct($del, array('modpath'=>$mod['folder']));
		}else{
			echo "<p><font color=red>Удаление модуля {$mod['folder']} не возможно.</font>";
			unset($_GET['del']);
		}
	}else{
		# Формирование списка неустановленных модулей
		$modules_list = array_flip( spisok("SELECT id, folder FROM {$conf['db']['prefix']}modules") );
		foreach(mpreaddir("modules", 1) as $k=>$file_name){
			if ( !isset($modules_list[$file_name]) ){
				if (substr($file_name, 0, 1) != '.' && $file_name != 'null'){
					if(($f = mpopendir("modules/$file_name")) && is_link($f)){
//						echo "<div>". mpopendir("modules/". readlink($f). "/info.php"). "</div>";
						mpct("modules/". readlink($f). "/info.php", array('modpath'=>readlink($f)));
					}else{
//						echo "<br />modules/$file_name/info.php";
						mpct("modules/$file_name/info.php", array('modpath'=>$file_name));
					}// mpre($conf['modversion']);
					$modlist[] = "<a title=\"{$conf['modversion']['description']}\" alt=\"{$conf['modversion']['description']}\" href='/?m[modules]=admin".($_GET['r'] ? "&r={$_GET['r']}" : '')."&install=$file_name'>{$conf['modversion']['name']}</a>";
				}
				if ($_GET['install'] == $file_name){
					if (file_exists( mpopendir($infofile = "modules/$file_name/info.php") ) ){
						if(($f = mpopendir("modules/$file_name")) && is_link($f) && ($infofile = $f)){
							# Ссылка на рездел
						} mpct($infofile, array('modpath'=>$file_name));
					}else{
						echo "<p>Информация о версии не найдена";
					}
					echo "<p><font color=red>Добавлен модуль</font>: {$conf['modversion']['name']} [$file_name]<br>";
					mpfdk("{$conf['db']['prefix']}modules", null, $w = $conf['modversion']+array('folder'=>$file_name));
					if(mpopendir("modules/$file_name/init.php")){
						echo mpct("modules/$file_name/init.php", array('modpath'=>$file_name));
					}
					if(mpopendir("modules/$file_name/sql.php")){
						echo mpct("modules/$file_name/sql.php", array('modpath'=>$file_name));
					}
				}
			}
		}
	}

	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>false), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>false), # Удаление
			),
			'edit'=>'list',
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('folder'=>'Папка', 'name'=>'Имя', 'author'=>'Автор', 'contact'=>'Контакт', 'enabled'=>'Активность', 'access'=>'Доступ', 'admin'=>'Админраздел', 'settings'=>'Настройки', 'groups'=>'Группы', 'users'=>'Польз'), # Название полей //, 'md5'=>'Хеш'
//			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'groups'=>array('*'=>'<a href=/?m[modules]=admin&r=1&where[mid]={f:id}>Нет</a>')+spisok("SELECT m.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r=1&where[mid]=', m.id, '>прав: ', COUNT(*), '</a>') FROM {$conf['db']['prefix']}{$arg['modpath']} AS m, {$conf['db']['prefix']}{$arg['modpath']}_gaccess AS g WHERE m.id=g.mid GROUP BY m.id"),
				'users'=>array('*'=>'<a href=/?m[modules]=admin&r=2&where[mid]={f:id}>Нет</a>')+spisok("SELECT m.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r=2&where[mid]=', m.id, '>прав: ', COUNT(*), '</a>') FROM {$conf['db']['prefix']}{$arg['modpath']} AS m, {$conf['db']['prefix']}{$arg['modpath']}_uaccess AS u WHERE m.id=u.mid GROUP BY m.id"),
				'name'=>array('*'=>'<div title="Версия: {f:version}">{f:{f}}</div>'),
				'settings'=>array('*'=>'<a href="/?m[settings]=admin&where[modpath]={f:folder}">Нет</a>')+spisok("SELECT m.id, CONCAT('<a href=/?m[settings]=admin&where[modpath]=', m.folder, '>парам:', COUNT(*), '</a>') FROM mp_settings as s, mp_modules as m WHERE m.folder=s.modpath GROUP BY s.modpath"), 'folder'=>array('*'=>"<div title='{f:md5}'>{f:{f}}</div>")
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('folder'), # Выключенные для записи поля
			'hidden' => array('admin', 'contact'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'charset'=>array('*'=>array(''=>'Дефолт', 'cp1251'=>'cp1251', 'utf8'=>'utf8')),
				'enabled' => array('*' => array('2'=> '<font color=green>Включен</font>', '1'=>'<font color=blue>Выключен</font>', '0'=>'<font color=red>Удален</font>')),
				'access' => array('*' => $conf['settings']['access_array']),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('description'=>'20'), # Максимальное количество символов в поле
		)
	);
	echo "<p>Доступные модули: ".@implode(', ', $modlist);

//	mpre(array('values'=>array('*'=>'<a href="/?m[settings]=admin&where[modpath]={f:folder}">Нет</a>')+spisok("SELECT m.id, COUNT(*) FROM mp_settings as s, mp_modules as m WHERE m.folder=s.modpath GROUP BY s.modpath")));
}elseif($m[(int)$_GET['r']] == 'Доступ групп'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[modules]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_gaccess", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Удаление
			),
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('mid'=>'Модуль', 'gid'=>'Группа', 'access'=>'Доступ', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>'{name}'), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('folder'), # Выключенные для записи поля
//			'hidden' => array('admin', 'contact', 'access'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'mid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}modules")),
				'gid' => array('*'=> spisok("SELECT id, name FROM {$conf['db']['prefix']}users_grp")),
				'access' => array('*' => $conf['settings']['access_array']),
			),
			'default' => array(
				'mid'=>max($_GET['where']['mid'], $_POST['mid']),
				'gid'=>max($_GET['where']['gid'], $_POST['gid']),
			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($m[(int)$_GET['r']] == 'Доступ пользователей'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[modules]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}modules_uaccess", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Удаление
			),
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('mid'=>'Модуль', 'uid'=>'Пользователь', 'access'=>'Доступ', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>'{name}'), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('folder'), # Выключенные для записи поля
//			'hidden' => array('admin', 'contact', 'access'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'mid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}modules")),
				'uid' => array('*'=> spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
				'access' => array('*' => $conf['settings']['access_array']),
			),
			'default' => array('mid'=>max($_GET['where']['mid'], $_POST['mid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'Скрипты'){
	echo "<p>";
	$tab = spisok("SHOW TABLES;");
	foreach($tab as $table_name=>$k){
		$tables[$table_name]['mp_table_name'] = $table_name;
		$tables[$table_name]['create_script'] = mpql(mpqw("SHOW CREATE TABLE $table_name"), 0);
//		$tables[$table_name]['create_script'] = $tables[$table_name]['create_script'][ "Create ". array_search($table_name, $tables[$table_name]['create_script']) ];
//if($table_name == 'mp_forum_cmess' || $table_name == 'mp_forum_mess'){
//	mpre(mpql(mpqw("SHOW CREATE TABLE $table_name")));
//}
		if($tables[$table_name]['create_script']["Create Table"]){
			$tables[$table_name]['create_script']["Create Table"] = preg_replace("/ AUTO_INCREMENT=\d{1,10}/e", " ", $tables[$table_name]['create_script']["Create Table"]);
		}

		if($tables[$table_name]['create_script']["Create View"]){
			$tables[$table_name]['create_script']["Create View"] = preg_replace("/ALGORITHM=UNDEFINED DEFINER=(.*) SQL SECURITY DEFINER /e", "", $tables[$table_name]['create_script']["Create View"]);
		}
	}// mpre($tables);
	foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules")) as $k=>$v){
		$readdir = $_SERVER['SERVER_NAME'] == 'mpak.su' ? "modules/{$v['folder']}" : mpopendir("modules/{$v['folder']}");
		echo "<br>Модуль ";
		mpct("$readdir/info.php");
//		echo "\$md5(@file_get_contents(\"$readdir/admin.php\"). @file_get_contents(\"$readdir/index.php\"))";
		$md5 = md5(@file_get_contents("$readdir/admin.php"). @file_get_contents("$readdir/index.php"));
		if ($v['md5'] != $md5){
			if ($conf['modversion']['version'] > $v['version']){
				mpqw("UPDATE {$conf['db']['prefix']}modules SET version={$conf['modversion']['version']}, md5='".($v['md5'] = $conf['modversion']['version'])."' WHERE id={$v['id']}");
			}else{
				echo "<b>{$v['name']}</b> Версия обновлена до <b>".($v['version'] += 0.0001)."</b>";
				mpqw("UPDATE {$conf['db']['prefix']}modules SET version={$v['version']}, md5='".($v['md5'] = $md5)."' WHERE id={$v['id']}");
			}
		}else{
			echo "<b>{$v['name']}-{$v['version']}</b>";
		}

		$info = $init = $del = chr(60)."? die;";
		foreach($tables as $table_name=>$mess){
			if (strpos($table_name, "{$conf['db']['prefix']}{$v['folder']}_") === 0 || $table_name == "{$conf['db']['prefix']}{$v['folder']}"){
				$mess['create_script'] = str_replace("`{$conf['db']['prefix']}{$v['folder']}" , '`{$conf[\'db\'][\'prefix\']}{$arg[\'modpath\']}', $mess['create_script']);
				$mess['mp_table_name'] = str_replace("{$conf['db']['prefix']}{$v['folder']}" , '{$conf[\'db\'][\'prefix\']}{$arg[\'modpath\']}', $mess['mp_table_name']);

				if($type = array_search($table_name, $mess['create_script'])){
					$init .= "\n\necho '<p>'.\$sql = \"". $mess['create_script']["Create $type"]. "\";\nmpqw(\$sql);";
					$del .= "\n\necho '<p>'.\$sql = \"DROP $type {$mess['mp_table_name']}\";\nmpqw(\$sql);";
				} $tab[$table_name] = true;
			}
		}
		$settings = mpql(mpqw("SELECT * FROM mp_settings WHERE modpath='".($v['folder'] == 'settings' ? '' : $v['folder'])."'"));
		foreach($settings as $n=>$z){
			if (!$n) $init .= "\n";
			$fields = $values = array();
			foreach($z as $fil=>$val){
				if ($fil == 'id') continue;
				$fields[] = "`$fil`";
				$values[] = "'".((($arg['access'] < $z['aid']) && ($fil == 'value')) ? '' : strtr(mpquot($val), array('$'=>'\\$')))."'";
			}
			$init .= "\nmpqw(\"INSERT INTO `{\$conf['db']['prefix']}settings` (".implode(', ', $fields).") VALUES (".implode(', ', $values).")\");";
		}
		if (count($settings)) $del .= "\n\nmpqw(\"DELETE FROM `{\$conf['db']['prefix']}settings` WHERE `modpath`='{$v['folder']}'\");";

		$info .= "\n\n\$conf['modversion']=array(";
		foreach($v as $n=>$z){
			if ($n == 'id' || $n == 'folder') continue;
			$info .= "\n\t'$n'=>'$z',";
		}
		$info .= "\n);";
		$init .= "\n\n?".chr(62); $del .= "\n\n?".chr(62);  $info .= "\n\n?".chr(62);

		if (array_search($v['folder'], array('admin', 'modules', 'blocks', 'users', 'settings', 'sess', 'themes')) === false){
			if (is_readable($fn = mpopendir($file_name = "$readdir/del.php"))){
				$cdel = file_get_contents($fn);
			}// mpre($fn);
			if ($cdel != $del){
				if(is_writable($file_name)){
					echo "<ul>Перезаписан скрипт удаления <b>$file_name</b></ul>";
					file_put_contents($file_name, $del);
				}else{
					echo "<p><div>Файл доступен только для чтения <b>$file_name</b></div>";
					echo "<pre style='border: 1px solid gray;'>".htmlspecialchars($del)."</pre>";
				}
			}
		}
		if($fn = mpopendir($file_name = "$readdir/init.php"))
			$cinit = file_get_contents($fn);
		if ($cinit != $init){
			if(is_writable($file_name)){
					echo "<ul>Перезаписан скрипт установки<b>$file_name</b></ul>";
					file_put_contents($file_name, $init);
			}else{
				echo "<p><div>Нет доступа к файлу <b>$file_name</b></div><pre style='border: 1px solid gray;'>". htmlspecialchars($init). "</pre>";
			}
		}

		if ( ($file_name = mpopendir("$readdir/info.php")) && ($cinfo = file_get_contents($file_name)) && ($cinfo != $info) ){
			if (is_writable($file_name)){
				echo "<ul>Перезаписан информационный файл <b>$file_name</b></ul>";
				file_put_contents($file_name, $info);
			}else{
				echo "<p><div>Файл доступен только для чтения  $file_name</div><pre style='background-color: #eeeeee'>". htmlspecialchars($info). "</pre>";
//				echo "<pre style='background-color: #eeeeee'>". htmlspecialchars($cinfo). "</pre>";
			}
		}
	}
	foreach($tab as $k=>$v){
		if (!$v) echo "<br>Потеряна таблица: <a href='?m[sqlanaliz]=admin&r=1&query=DROP TABLE $k'>$k</a>";
	}
//	echo "<pre>"; print_r(mpreaddir("modules", 1)); echo "</pre>";
}

?>