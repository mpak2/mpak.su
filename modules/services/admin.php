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

/*if ($_GET['f']){
	$tables = array('0'=>"{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}", '1'=>"{$conf['db']['prefix']}{$arg['modpath']}_tmp");
	list($k, $v) = each($_GET['f']);
	mpfile(mpql(mpqw("SELECT ".addslashes($k)." FROM ".$tables[ (int)$_GET['r'] ]." WHERE id=".(int)$v), 0, $k));
}
*/

$conf['settings'] += array(
	"{$arg['modpath']}_obj"=>"Сервисы",
	"{$arg['modpath']}_desc"=>"Описания",
	"{$arg['modpath']}_img"=>"Изображения",
	"{$arg['modpath']}_list"=>"Лист",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_obj"){
	$_GET['where']['pid'] = (int)$_GET['where']['pid'];
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => 'pid='.(int)$_GET['where'], # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
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

			'edit'=>'list',
			'title' => array('pid'=>'Родитель', 'img'=>'Логотип', 'chld'=>'Вложения', 'name'=>'Имя', 'count'=>'Подробно', 'photo'=>'Фото', 'sort'=>'Сорт'), # Название полей
			'etitle' => array('pid'=>'Родитель', 'img'=>'Логотип', 'name'=>'Имя', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'chld'=>spisok("SELECT oo.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS oo, {$conf['db']['prefix']}{$arg['modpath']}_obj AS ot WHERE oo.id=ot.pid GROUP BY oo.id"),
				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&where[pid]={f:id}>{f:{f}}</a>"),
				'count'=>array('*'=>"<a href=/?m[services]=admin&r=1&where[oid]={f:id}>Нет</a>")+spisok("SELECT o.id, CONCAT('<a href=/?m[services]=admin&r=1&where[oid]=', o.id, '>', COUNT(*), '_стр</a>') AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE o.id=d.oid GROUP BY o.id"),
				'photo'=>array('*'=>"<a href=/?m[services]=admin&r=2&where[oid]={f:id}>Нет</a>")+spisok("SELECT o.id, CONCAT('<a href=/?m[services]=admin&r=2&where[oid]=', o.id, '>', COUNT(*), '_фото</a>') AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE o.id=i.oid GROUP BY o.id"),
//				("c". $fn = 'img')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/tn:obj/{f:id}/w:100/h:100/null/img.jpg>"),
				'description'=>array('*'=>"<a target=_blank href=/{$arg['modpath']}:desc/{f:id}>{f:{f}}</a>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'admin' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin")),
				'pid' =>array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY sort", 20)),
			),
			'default' => array(
				'pid'=>array('*'=>max($_GET['where']['pid'], $_POST['pid'])),
				'lid'=>array('*'=>max($_GET['where']['lid'], $_POST['lid'])),
			), # Значение полей по умолчанию
			'maxsize' => array('description'=>'100'), # Максимальное количество символов в поле
		)
	);
//	print_r(spisok("SELECT o.id, COUNT(*) AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE o.id=d.oid GROUP BY o.id"));
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_desc"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
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

			'edit'=>'list',
			'title' => array('oid'=>'Обьект', 'name'=>'Имя', 'img'=>'Фото', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'etitle' => array('oid'=>'Обьект', 'name'=>'Имя', 'img'=>'Фото', 'sort'=>'Сортировка', 'description'=>'Описание', 'text'=>'Текст'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/tn:desc/{f:id}/w:100/h:100/null/img.jpg>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'admin' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin")),
				'oid' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj")),
			),
			'default' => array('oid'=>array('*'=>max($_GET['where']['oid'], $_POST['oid']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_img"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
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

//			'edit'=>'list',
			'title' => array('oid'=>'Обьект', 'img'=>'Фото', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/tn:img/{f:id}/w:120/h:100/null/img.jpg>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'admin' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin")),
				'oid' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj")),
			),
			'default' => array('oid'=>array('*'=>max($_GET['where']['oid'], $_POST['oid']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}else{ echo "{$_GET['r']}:". __LINE__;
	stable(
		array(
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
			'order' => 'id DESC', # Сортировка вывода таблицы
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
		)
	);
}

?>