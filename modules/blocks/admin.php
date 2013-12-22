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

$conf['settings'] += array(
	"{$arg['modpath']}"=>'Блоки',
	"{$arg['modpath']}_cache"=>'Кеш',
	"{$arg['modpath']}_gaccess"=>'Группы',
	"{$arg['modpath']}_reg"=>'Регион',
	"{$arg['modpath']}_reg_modules"=>'Модули',
	"{$arg['modpath']}_shablon"=>'Шаблоны',
	"{$arg['modpath']}_uaccess"=>'Пользователи',
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

$tn = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"), strlen($_GET['r']));
$etitle_id = $count_id = array();
foreach($m as $table=>$v){
	$columns = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($table). ""), 'Field');
	foreach($columns as $f=>$fields){
		if((substr($f, -3, 3) == "_id") && (substr($f, 0, -3) == $tn)){
			$fn = substr($table, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"), strlen($table));
			$etitle_id += array($fn=>$conf['settings'][ "{$arg['modpath']}_{$fn}" ]);
			$count_id[ $fn ] = array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). "&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). "&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '". ($fn ? "_" : ""). ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id");
		}
	}
}

if($_GET['r'] == "{$conf['db']['prefix']}blocks"){ #Блоки
	if ((int)$_GET['conf']){
		$bl = mpql(mpqw("SELECT name, file FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=". (int)$_GET['conf']));
		if(!array_key_exists("null", $_GET)) echo "<p>Свойства блока: <b>{$bl[0]['name']} [{$_GET['conf']}]</b><p><p>";
		echo mpeval("modules/{$bl[0]['file']}", array('confnum'=>$_GET['conf'], 'modpath'=>basename(dirname(dirname($bl[0]['file'])))));
	}else{
		$block[''] = '';
		$modules = array(0=>'../include')+spisok("SELECT id, folder FROM {$conf['db']['prefix']}modules WHERE enabled = '2'");
		foreach($modules as $k=>$f){
			foreach(mpreaddir("modules/$f/blocks", 1) as $k=>$file_name){
				$fd = fopen (mpopendir("modules/$f/blocks/$file_name"), 'r');
				$name = trim(substr($name = fgets($fd, 1024), strrpos($name, "#")+1));
//				$name = "<div title=\"".strtr("modules/$f/blocks/$file_name", array('../include/'=>''))."\">".basename($f). ">". substr($name, 0, 10). (strlen($name) > 10 ? '..' : ''). "</div>";
				$name = "<div title=\"".strtr("modules/$f/blocks/$file_name", array('../include/'=>''))."\">". basename($f). ":". array_shift(explode('.', basename($file_name))). "</div>";
				fclose ($fd);
				if ($name && !strpos($name, 'die;')){
					$blocks["$f/blocks/$file_name"] = "<div title='$f/blocks/$file_name'>$name</div>";
				}else{
					$blocks["$f/blocks/$file_name"] = "$f/blocks/$file_name";
				}
			}
		}

		$shablon = array();
		$themes = array(''=>'', "!{$conf['settings']['theme']}"=>"!{$conf['settings']['theme']}");
		foreach(mpreaddir('themes', 1) as $pathname) $themes[$pathname] = $pathname;
		$themes += spisok("SELECT id, name FROM {$conf['db']['prefix']}themes WHERE type='text/html'");

		$theme = mpql(mpqw("SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"theme\""), 0, 'value');
		if($glob = glob(mpopendir("themes"). "/*/block*.html")){
			foreach($glob as $k=>$v){
				$block[basename($v)] = basename($v);
			}// mpre($block);
		}
		if(!isset($_GET['order'])) $_GET['order'] = 'orderby';
		stable(
			array(
//				'dbconn' => $conf['db']['conn'],
				'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
				'name' => $_GET['r'], # Имя таблицы базы данных
//				'where' => '', # Условия отбора содержимого
//				'order' => 'id', # Сортировка вывода таблицы
//				'debug' => true, # Вывод всех SQL запросов
				'acess' => array( # Разрешение записи на таблицу
					'add' => array('*'=>true), # Добавление
					'edit' => array('*'=>true), # Редактирование
					'del' => array('*'=>true), # Удаление
					'cp' => array('*'=>true), # Копирование
				),
				'count_rows' => 1000, # Количество записей в таблице
//				'page_links' => 10, # Количество ссылок на страницы в обе стороны

//				'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//				'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//				'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//				'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

				'title' => array('theme'=>'Тема', 'file'=>'Тип', 'name'=>'Название', 'shablon'=>'Шаблон', 'access'=>'Доступ', "gacess"=>"Грп", "uacess"=>"Польз", 'rid'=>'Регион', 'orderby'=>'Сорт', 'enabled'=>'Актив'), # Название полей
				'type' => array('orderby'=>'sort'), # Тип полей
//				'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//				'set' => array('theme'=>$GLOBALS['conf']['settings']['theme']), # , 'orderby'=>$res['0']['max'] + 1 Значение которое всегда будет присвоено полю. Исключает любое изменение
				'shablon' => $count_id += array(
					"rid"=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_reg&where[id]={f:{f}}'>{spisok:{f}}</a>"),
					'name'=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&conf={f:id}'><img src='/img/block_conf.png' border='0'></a>&nbsp;{f:{f}}"),
//					'rid'=>array('*'=>"&#60;--!&nbsp;[blocks:{f:{f}}]&nbsp;--&#62;")+spisok("SELECT b.id, r.description FROM {$GLOBALS['conf']['db']['prefix']}blocks as b, {$GLOBALS['conf']['db']['prefix']}blocks_reg as r WHERE b.rid=r.id"),
//					'file'=>$blocks_config,
//					'orderby'=>array('*'=>"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'><a href='?m[blocks]=admin&dec={f:id}'><img src='modules/blocks/img/up.gif' border='0'></a></td><td align='center'><a href='?m[blocks]=admin&inc={f:id}'><img src='modules/blocks/img/down.gif' border='0'></a></td></tr></table>"), # <td align='center'>{f:{f}}</td>
					'access' => array('*' => "<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_gaccess&where[bid]={f:id}\">{spisok:{f}}</a>"),
					'gacess' => spisok("SELECT b.id, CONCAT('<a href=/?m[blocks]=admin&r=mp_blocks_gaccess&where[bid]=', b.id, '>', COUNT(DISTINCT g.id), '_grp</a>') FROM {$conf['db']['prefix']}{$arg['modpath']} AS b LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_gaccess AS g ON b.id=g.bid GROUP BY b.id"),
					'uacess' => spisok("SELECT b.id, CONCAT('<a href=/?m[blocks]=admin&r=mp_blocks_uaccess&where[bid]=', b.id, '>', COUNT(DISTINCT g.id), '_usr</a>') FROM {$conf['db']['prefix']}{$arg['modpath']} AS b LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_uaccess AS g ON b.id=g.bid GROUP BY b.id"),
				), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//				'disable' => array('orderby'), # Выключенные для записи поля
				'hidden' => array('param'), # Скрытые поля
				'spisok' => array( # Список для отображения и редактирования
					'file' => array('*' => $blocks),
					'theme' => array('*' => $themes),
					'shablon' => array('*'=>(array)$block+spisok("SELECT id, name FROM {$conf['db']['prefix']}blocks_shablon")),
					'rid'=>array('*'=>array('')+spisok("SELECT r1.id, CONCAT(r1.description, ' ', IF(r2.id, r2.description, '')) FROM {$conf['db']['prefix']}blocks_reg AS r1 LEFT JOIN {$conf['db']['prefix']}blocks_reg AS r2 ON (r1.reg_id=r2.id)")),
					'enabled' => array('*' => array('0'=>'Выкл', '1'=>'Вкл')),
					'access' => array('*' => array(-1=>"")+$conf['settings']['access_array']),
				),
				'default' => array(
					'enabled'=>array('*'=>1),
					'rid'=>array('*'=>max($_POST['rid'], $_GET['where']['rid'])),
					'name'=>'Название',
					'theme'=>array('*'=>"!{$conf['settings']['theme']}"),
					'access'=>array('*'=>-1),
				), # Значение полей по умолчанию
//				'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
			)
		);
	}
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_cache"){
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'edit'=>'list',
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

//			'title' => array('name'=>'Имя', 'cnt'=>'Клиник', 'description'=>'Описание'), # Название полей
//			'etitle'=> array(),
			'type' => array('time'=>'timestamp', 'img'=>'file', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => $count_id += array(
//				'num'=>array('*'=>'<a target=_blank href=http://stom-firms.ru/clinics.php?i={f:{f}}>http://stom-firms.ru/clinics.php?i={f:{f}}</a>'),
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
//				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/w:120/h:100/null/img.jpg'>"),
//				($fn = "link"). "_id"=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}'>{f:{f}}</a>"),
//				(($fn = 'cnt'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
				"bid" => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}")),
				"eid" => array('*'=>array('')+spisok("SELECT id, name FROM {$conf['db']['prefix']}users_event ORDER BY name")),
//				'metro_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_metro")),
//				'kuzov_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type")),
//				'zagruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_zagruzka_type")),
//				'vygruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vygruzka_type")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
			),
			'default' => array(
//				'uid'=>array('*'=>$conf['user']['id']),
//				'time'=>date('Y.m.d H:i:s'),
//				($f = 'type_id')=>array('*'=>max($_GET['where'][$f], $_POST[$f])),
			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_reg"){ #Регионы
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => 'sort', # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('term'=>"Условие", 'reg'=>"Вложений", '.name'=>'Метка', 'count'=>'Блоков', 'sort'=>'Сорт', 'reg_id'=>"Родитель", "reg_modules"=>"Модули", 'description'=>'Описание'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => $count_id += array(
				'.name'=>array('*'=>"&#60;!--&nbsp;[blocks:{f:id}]&nbsp;--&#62;"),
				'count'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Блоки', $m). "&where[rid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Блоки', $m). "&where[rid]=', r.id, '>', COUNT(*), '_блок</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_reg AS r, {$conf['db']['prefix']}{$arg['modpath']} AS b WHERE r.id=b.rid GROUP BY r.id"),
				($fn = 'reg')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'mid' => array('*'=>array('')+spisok("SELECT id, name FROM {$conf['db']['prefix']}modules ORDER BY name")),
				($fn = 'reg'). '_id' => array('*'=>array('')+spisok("SELECT id, description FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
//				'bid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE theme = '{$GLOBALS['conf']['settings']['theme']}' OR theme = '*' ORDER BY file")),
//				'gid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}users_grp")),
				'term' => array('*' =>array(0=>"", 1=>"Только", -1=>"Исключая")),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_reg_modules"){ #Модули
	$themes = array(''=>'', "!{$conf['settings']['theme']}"=>"!{$conf['settings']['theme']}");
	foreach(mpreaddir('themes', 1) as $pathname) $themes[$pathname] = $pathname;
	$themes += spisok("SELECT id, name FROM {$conf['db']['prefix']}themes WHERE type='text/html'");

	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => 'sort', # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

//			'title' => array('term'=>"Условие", 'mid'=>'Модуль', 'fn'=>'Файл', 'reg'=>"Вложений", '.name'=>'Метка', 'count'=>'Блоков', 'sort'=>'Сорт', 'reg_id'=>"Родитель", 'description'=>'Описание'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => $count_id += array(
//				'.name'=>array('*'=>"&#60;!--&nbsp;[blocks:{f:id}]&nbsp;--&#62;"),
//				'count'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Блоки', $m). "&where[rid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Блоки', $m). "&where[rid]=', r.id, '>', COUNT(*), '_блок</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_reg AS r, {$conf['db']['prefix']}{$arg['modpath']} AS b WHERE r.id=b.rid GROUP BY r.id"),
//				($fn = 'reg')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'theme' => array('*' => $themes),
				($fn = "modules_index")=>array("*"=>array("")+spisok("SELECT id,name FROM {$conf['db']['prefix']}modules")),
				($fn = "reg_id")=>array("*"=>array("")+spisok("SELECT id,description FROM {$conf['db']['prefix']}{$arg['modpath']}_reg")),
//				'mid' => array('*'=>array('')+spisok("SELECT id, name FROM {$conf['db']['prefix']}modules ORDER BY name")),
//				($fn = 'reg'). '_id' => array('*'=>array('')+spisok("SELECT id, description FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
//				'bid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE theme = '{$GLOBALS['conf']['settings']['theme']}' OR theme = '*' ORDER BY file")),
//				'gid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}users_grp")),
//				'term' => array('*' =>array(0=>"Включая", -1=>"Кроме")),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_shablon"){ #Шаблоны
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'edit'=>'list',
			'title' => array('name'=>'Имя', 'description'=>'Описание', 'shablon'=>'Шаблон'), # Название полей
			'etitle' => $etitle_id + array('name'=>'Имя', 'description'=>'Описание', 'shablon'=>'Шаблон'), # Название полей
			'type' => array('description'=>'textarea', 'shablon'=>'textarea'), # Тип полей

//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>"<a href='?m[news]=admin&where[kid]={f:id}'>{f:{f}}</a>"), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'bid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE theme = '{$GLOBALS['conf']['settings']['theme']}' OR theme = '*' ORDER BY file")),
//				'gid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}users_grp")),
//				'access' => array('*' => $GLOBALS['conf']['settings']['access_array']),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_gaccess"){ #Группа
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('bid'=>'Блок', 'gid'=>'Группа', 'access'=>'Доступ', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>"<a href='?m[news]=admin&where[kid]={f:id}'>{f:{f}}</a>"), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'bid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}blocks ORDER BY name")),
				'gid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users_grp")),
				'access' => array('*' => $conf['settings']['access_array']),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_uaccess"){ #Пользователь
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('bid'=>'Блок', 'uid'=>'Пользователь', 'access'=>'Доступ', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>"<a href='?m[news]=admin&where[kid]={f:id}'>{f:{f}}</a>"), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'bid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}blocks ORDER BY name")),
				'uid' => array('*' => array(0=>"<span style=color:red;>ВЛАДЕЛЕЦ</span>")+spisok("SELECT id, name FROM {$conf['db']['prefix']}users ORDER BY name")),
				'access' => array('*' => $conf['settings']['access_array']),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] /*== "{$conf['db']['prefix']}{$arg['modpath']}_type"*/){ echo "{$_GET['r']}:". __LINE__;
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'edit'=>'list',
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

//			'title' => array('name'=>'Имя', 'index'=>'Гостиниц', 'description'=>'Описание'), # Название полей
//			'etitle'=> array(),
//			'type' => array('img'=>'file', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array(
//				'num'=>array('*'=>'<a target=_blank href=http://stom-firms.ru/clinics.php?i={f:{f}}>http://stom-firms.ru/clinics.php?i={f:{f}}</a>'),
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
//				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:". (array_pop(explode('_', $_GET['r']))). "/w:120/h:100/null/img.jpg'>"),
//				($fn = 'index')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
//			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
//				($fn = 'index'). '_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
//				'metro_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_metro")),
//				'kuzov_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type")),
//				'zagruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_zagruzka_type")),
//				'vygruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vygruzka_type")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//			),
//			'default' => array(
//				($f = 'index_id')=>array('*'=>max($_GET['where'][$f], $_POST[$f])),
//			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}

?>