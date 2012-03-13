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

#Загрузка файлов

/*if ((int)$_GET['name']){
	$v = mpql(mpqw("SELECT name, description FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE id=".(int)$_GET['name']), 0);
	mpfile($v['name'], $v['description']);
}*/

if ($_GET['f']){
	$table_name = "{$conf['db']['prefix']}{$arg['modpath']}_files";
	list($k, $v) = each($_GET['f']);
	mpfile(mpql(mpqw("SELECT ".addslashes($k)." FROM $table_name WHERE id=".(int)$v), 0, $k));
}

$conf['settings'] += array(
	"{$arg['modpath']}_index"=>$arg['modname'],
	"{$arg['modpath']}_cat"=>"Категории",
	"{$arg['modpath']}_files"=>"Файлы",
	"{$arg['modpath']}_ext"=>".Расширения",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_cat"){//'Категории'
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
			'shablon' => array(
//				'name'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&where[cat]={f:id}'>{f:{f}}</a>"),
				(($fn = 'files'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id"),
				'rand'=>array('*'=>"<a href=\"/{$arg['modpath']}/cat_id:{f:id}/null/images/cat.jpg\">images/cat.jpg</a>"),
			),
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('parent'=>'Владелец', 'name'=>'Категория', 'files'=>'Файлы', 'rand'=>'Случайные'), # Название полей
//			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'parent' => array('*' => array('0'=>'.') + (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_files"){ //'Вложения'
	if (!$arg['access'] > 4) $_GET['where']['uid'] = $conf['user']['uid'];
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
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
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('time'=>'Время', 'cat_id'=>'Категория', 'activ'=>'Доступ', 'count'=>'Счет', 'w'=>'Шир', 'h'=>'Выс', 'c'=>'Кроп', 'name'=>'Файл', 'description'=>'Описание'), # Название полей
			'type' => array('name'=>'file', 'time'=>'timestamp', 'description'=>'textarea'), # Тип полей
//			'ext' => array('name'=>(array)spisok("SELECT name, ext FROM {$conf['db']['prefix']}{$arg['modpath']}_ext")),
			'ext' => array('name'=>array("*"=>"*")),
//			'set' => $adm ? null : array('uid'=>$conf['user']['uid']), # Значение которое всегда будет присвоено полю.
			'shablon' => array(
				'name'=>array('*'=>"<a href=\"/{$arg['modpath']}/{f:id}/null/{f:{f}}\">{f:{f}}</a>"),
//				'name'=>spisok("SELECT id, '<a href=/?m[{$arg['modpath']}]&id={f:id}>Скачать</a>' FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE name<>''"),
//				'cat'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&where[{f}]={f:cat}'>{spisok:{f}:*:{f:{f}}}</a>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'shablon' => array('name'=>array('*'=>"<a href='?m[{$arg['modpath']}]&id={f:id}'>Скачать</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'cat_id' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
				'uid' => array('*' => array('0'=>'*')+spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
				'activ' => array('*' => array('0'=>'Выкл', '1'=>'Включ')),
				'c'=>array('*'=>array(0=>'', 1=>'Обрез')),
			),
			'default' => array('uid'=>array('*'=>0), 'time'=>date("Y.m.d H:i:s"), 'activ'=>'1'), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}

?>