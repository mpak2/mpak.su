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

mpmenu($m = array('Опрос', 'Значения', 'Голоса'));

if ($m[(int)$_GET['r']] == 'Опрос'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[poll]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}", # Имя таблицы базы данных
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

			'title' => array('poll'=>'Вопрос', 'count'=>'Ответов', 'gid'=>'Группа', 'mult'=>'Ответ', 'golos_time'=>'Интервал'), # Название полей
			'type' => array('poll'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'poll'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&r=1&where[pid]={f:id}'>{f:{f}}</a>"),
				'count'=>spisok("SELECT p.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']} AS p, {$conf['db']['prefix']}{$arg['modpath']}_value AS v WHERE p.id=v.pid GROUP BY v.pid"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'gid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users_grp")),
				'golos_time' => array('*'=>array(60=>'1 минута', 30*60=>'30 минут', 60*60=>'1 час', 12*60*60=>'12 часов', 24*60*60=>'1 сутки', 3*24*60*60=>'3 суток', 7*24*60*60=>'1 неделя', 15*24*60*60=>'15 суток', 30*24*60*60=>'30 суток', 999999999=>'один раз')),
				'mult' => array('*' => array('0'=>'Один', '1'=>'Много')),
			),
			'default' => array('golos_time'=>60*60*24), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'Значения'){
	foreach((array)spisok("SELECT id, poll FROM {$conf['db']['prefix']}poll") as $k=>$v)
		$poll[$k] = substr($v, 0, 30).(strlen($v) > 30 ? "..." : '');

	$color = array();
	$c = array('00', '40', '80', 'b0', 'f0');
	foreach($c as $k=>$r)
		foreach($c as $k=>$g)
			foreach($c as $k=>$b)
				$color["#$r$g$b"] = "#$r$g$b";
		
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[poll]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_value", # Имя таблицы базы данных
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

			'title' => array('pid'=>'Вопрос', 'value'=>'Вариант', 'color'=>'Цвет', 'result'=>'Результат'), # Название полей
//			'type' => array('value'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
			'set' => (int)$_GET['where']['pid'] ? array('pid'=>$_GET['where']['pid']) : null, # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array( # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
				'color'=>array('*'=>"<font color='{f:{f}}'>{f:{f}}</font>"),
				'pid'=>array('*'=>"<a href='?m[poll]=admin&r=1&where[{f}]={f:{f}}'>{spisok:{f}:*:{f:{f}}}</a>"),
			),
			'disable' => (int)$_GET['where']['pid'] ? array('pid') : null, # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'pid' => array('*' => $poll),
				'color' => array('*' => $color),
			),
			'default' => array('pid'=>array('*'=>max($_GET['where']['pid'], $_POST['pid'])), 'color'=>array('*'=>'#0000f0')), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'Голоса'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[poll]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}poll_post", # Имя таблицы базы данных
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

			'title' => array('time'=>'Время', 'uid'=>'Пользователь', 'ip'=>'IP Адрес', 'poll'=>'Опрос', 'result'=>'Результат'), # Название полей
			'type' => array('time'=>'timestamp'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('color'=>"<font color='{sql:color}'>{sql:color}</font>", 'pid'=>"<a href='?m[poll]=admin&r=1&where[pid]={sql:pid}'>{spisok:{sql:pid}}</a>"), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'uid' => array('*' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}users")),
				'poll' => array('*' => spisok("SELECT id, poll FROM {$GLOBALS['conf']['db']['prefix']}poll", 20)),
				'result' => array('*' => spisok("SELECT id, value FROM {$GLOBALS['conf']['db']['prefix']}poll_value")),
			),
			'default' => array('ip'=>'127.0.0.1', 'time'=>date('Y.m.d H:i:s')), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}

?>