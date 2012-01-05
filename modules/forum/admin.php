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
	"{$arg['modpath']}_cmess"=>"Сообщений",
	"{$arg['modpath']}_mess"=>"Сообщения",
	"{$arg['modpath']}_vetki"=>"Ветки",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_vetki"){ //'Ветки'
	$_GET['where']['vetki_id'] = (int)$_GET['where']['vetki_id'];
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
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('vetki_id'=>'Родитель', 'name'=>'Имя', 'aid'=>'Доступ', 'vetki'=>'Веток', 'cmess'=>"СМес", 'mess'=>'Сообщ', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'vet'=>spisok("SELECT v1.id, COUNT(*) as count FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki as v1, {$conf['db']['prefix']}{$arg['modpath']}_vetki as v2 WHERE v1.id=v2.pid GROUP BY v1.id"),
//				'mess'=>spisok("SELECT v.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki as v, {$conf['db']['prefix']}{$arg['modpath']}_mess as m WHERE v.id=m.vid GROUP BY v.id"),
				($fn = 'vetki')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
				("c". $fn = 'mess')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id"). "]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id GROUP BY r.id"),
//				'name'=>array('*'=>'<a href=/?m[forum]=admin&r=1&where[vid]={f:id}>{f:{f}}</a>'),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'aid'=>array('*'=>array(0=>"Запрет", 1=>"Просмотр", 2=>"Создание обсуждений", 3=>"Обсуждение", 4=>"Модератор", 5=>"Администратор")), //$conf['settings']['access_array']
				'vetki_id' => array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki ORDER BY name")),
//				'time' => $time,
			),
			'default' => array(
				'vetki_id'=>array('*'=>max($_GET['where']['vetki_id'], $_POST['vetki_id'])),
			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_mess"){ //'Сообщения'
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

//			'title' => array('pid'=>'Родитель', 'name'=>'Имя', 'aid'=>'Доступ', 'vet'=>'Веток', 'tem'=>'Тем', 'description'=>'Описание'), # Название полей
			'type' => array('time'=>'timestamp', 'text'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('count'=>array('*'=>"SELECT v.id, ")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'uid'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users ORDER BY name", 30)),
				'vetki_id'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki ORDER BY name", 30)),
//				'pid' => array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vetki ORDER BY name", 30)),
//				'time' => $time,
			),
			'default' => array('time'=>date('Y.m.d H:i:s')), # Значение полей по умолчанию
			'maxsize' => array('text'=>250), # Максимальное количество символов в поле
		)
	);
//	print_r($conf['settings']['access_array']);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_cmess"){// echo "{$_GET['r']}:". __LINE__;
	stable(
		array(
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
			'order' => 'id DESC', # Сортировка вывода таблицы
			"title"=>array("uid"=>"Пользователь", "count"=>"Сообщений"),
			'shablon' => array(
				'count'=>array('*'=>"<a href=\"/?m[{$arg['modpath']}]=admin&r=mp_forum_mess&where[uid]={f:uid}\">{f:{f}} сообщений</a>"),
			),
			'spisok' => array( # Список для отображения и редактирования
				'uid'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users ORDER BY name", 30)),
			),
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