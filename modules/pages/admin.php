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
	"{$arg['modpath']}_cat"=>"Категории",
	"{$arg['modpath']}_index"=>"Статьи",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_index"){ //Страницы
	$access = spisok("SELECT id, 'true' FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=".(int)$conf['user']['uid']);
	if($arg['access'] >= 5) $access['*'] = true;
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
			'where' => $arg['access'] > 4 ? '' : "uid=".(int)$conf['user']['uid'], # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => $access, # Удаление
				'cp' => $access, # Копирование
			),
			'count_rows' => 5, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" class=\"EditTbl\">",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('shablon'=>"<td>{spisok:kid:*}</td><td>{spisok:uid:*}</td><td><input type='text' name='date' value='{edit:date}' style='width:100%'></td><td colspan=3><input type='text' name='title' style='width:100%' value='{edit:title}' style='width:100%'></td></tr><tr bgcolor='#eeeeee'><td colspan='6'><table width='100%'><tr><td>".mpwysiwyg('text', $_GET['edit'] ? mpql(mpqw("SELECT text FROM {$conf['db']['prefix']}{$arg['modpath']}_post WHERE id=".$_GET['edit']), 0, 'text') : '')."</td></tr></table></td></tr><tr align='right' bgcolor='#eeeeee'><td colspan='5' align='left'></td>"), # Формат записей таблицы
//			'bottom' => array('shablon'=>"<td>{spisok:*:kid}</td><td>{spisok:*:uid}</td><td colspan=3><input type='text' name='date' value='".date('Y.m.d H:i:s')."'></td></tr><tr>"), # Формат записей таблицы

			'edit'=>'list',
			'title' => array('kid'=>'Категория', 'uid'=>'Владелец', 'time'=>'Время', 'name'=>'Заголовок', 'text'=>'Текст'), # Название полей
			'etitle' => array('kid'=>'Категория', 'uid'=>'Владелец', 'time'=>'Время', 'name'=>'Заголовок', 'keywords'=>'Ключевые слова', 'description'=>'Описание', 'text'=>'Текст'), # Название полей
			'type' => array('time'=>'timestamp', 'text'=>'wysiwyg'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => $admin ? null : array('uid'=>$conf['user']['uid']), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array( # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
				'kid'=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$_GET['r']}&where[{f}]={f:{f}}'>{spisok:{f}:*:{f:{f}}}</a>"),
				'uid'=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$_GET['r']}&where[{f}]={f:{f}}'>{spisok:{f}:*:{f:{f}}}</a>"),
				'name'=>array('*'=>"<a href='/{$arg['modname']}/{f:id}/{f:name}'>{f:{f}}</a>"),
			),
//			'disable' => ($adm ? null : array('uid')), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'kid' => array('*' => (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
				'uid' => array('*' => array('0'=>'*')+(array)spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
//				'time' => $time,
			),
			'default' => array(
				'time'=>date('Y.m.d H:i:s'),
				'uid'=>array('*'=>$conf['user']['uid']),
			), # Значение полей по умолчанию
			'maxsize' => array('name'=>'250', 'text'=>'250'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_cat"){ //'Разделы'
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => $arg['access'] > 4 ? '' : "uid=".(int)$conf['user']['uid'], # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>$arg['access'] > 4), # Добавление
				'edit' => array('*'=>$arg['access'] > 4), # Редактирование
				'del' => array('*'=>$arg['access'] > 4), # Удаление
				'cp' => array('*'=>$arg['access'] > 4), # Копирование
			),
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('parent'=>'Родитель', 'name'=>'Раздел', 'prefix'=>'Префикс', 'count'=>'Статей'), # Название полей
//			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'name'=>array('*' => "<a href='/{$arg['modpath']}:list/cid:{f:id}'>{f:{f}}</a>"),
				'count'=>array('*' => "<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[kid]={f:id}>0_стат</a>")+spisok("SELECT c.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[kid]=', c.id, '>', COUNT(*), '_стат</a>') as count FROM {$conf['db']['prefix']}{$arg['modpath']}_cat as c, {$conf['db']['prefix']}{$arg['modpath']}_index as p WHERE c.id=p.kid GROUP BY c.id")
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'parent' => array('*' => array(0=>'.') + (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
//				'time' => $time,
			),
			'default' => array('uid'=>$conf['user']['uid']), # Значение полей по умолчанию
//			'maxsize' => array('text'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] /*== "{$conf['db']['prefix']}{$arg['modpath']}_type"*/){ echo "{$_GET['r']}:". __LINE__;
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