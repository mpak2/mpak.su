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
	$tables = array('0'=>"{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}", '1'=>"{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_tmp");
	list($k, $v) = each($_GET['f']);
	mpfile(mpql(mpqw("SELECT ".addslashes($k)." FROM ".$tables[ (int)$_GET['r'] ]." WHERE id=".(int)$v), 0, $k));
}
*/

mpmenu($menu = array('Категория', 'Предложения', 'Дополнительно', 'Поля', 'Варианты', 'Значения', 'Города', 'Стиль'));

if ($menu[(int)$_GET['r']] == 'Категория'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat", # Имя таблицы базы данных
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

			'title' => array('pid'=>'Родитель', 'aid'=>'Доступ', 'name'=>'Категория', 'dop'=>'Доп', 'description'=>'Описание'), # Название полей
//			'type' => array('img'=>'file', 'orderby'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('dop'=>spisok("SELECT k.id, COUNT(*) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat as k, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as kp WHERE k.id=kp.kid GROUP BY k.id"), 'name'=>array('*'=>'<a href=/?m[obyavlen]=admin&r=2&where[kid]={f:id}>{f:{f}}</a>')), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'pid' => array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat ORDER BY pid")),
				'aid' => array('*'=>$GLOBALS['conf']['settings']['access_array']),
			),
			'default' => array('aid'=>array('*'=>2)), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Предложения'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}", # Имя таблицы базы данных
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

			'title' => array('uid'=>'Пользователь', 'gid'=>'Город', 'kid'=>'Категория', 'dop'=>'Дополнительно', 'description'=>'Описание'), # Название полей
			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('dop'=>spisok("SELECT o.id, CONCAT('<a href=/?m[obyavlen]=admin&r=".array_search('Значения', $menu)."&where[oid]=', o.id, '>Ответов&nbsp;', COUNT(d.id) ,'</a>') FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} as o, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop as d WHERE o.id=d.oid GROUP BY o.id")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'tid' => array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_type")),
				'uid' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}users")),
				'kid' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>1")),
				'gid'=>array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_gorod ORDER BY name")),
			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Дополнительно'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'row,sort', # Сортировка вывода таблицы
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

			'title' => array('kid'=>'Категория', 'pid'=>'Поле', 'row'=>'Строка', 'sort'=>'Сортировка'), # Название полей
			'type' => array('row'=>'sort', 'sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('dop'=>spisok("SELECT k.id, COUNT(*) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kpole as k, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p WHERE k.pid=p.id GROUP BY k.kid")),
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'pid' => array('*'=>spisok("SELECT id, title FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole ORDER BY title")),
				'kid' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>1 ORDER BY name")),
//				'type' => array('*'=>array('text'=>'Строка', 'textarea'=>'Поле', 'select'=>'Список', 'radio'=>'Один', 'checkbox'=>'Много')),
			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Поля'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
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

			'title' => array('type'=>'Тип', 'sort'=>'Сорт', 'sravn'=>'Сравнение', 'title'=>'Название', 'var'=>'Вариантов'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('var'=>spisok("SELECT p.id, COUNT(*) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole as p, {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole as v WHERE p.id=v.pid GROUP BY p.id"), 'title'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=4&where[pid]={f:id}>{f:{f}}</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'sravn' => array('*'=>array('0'=>'Строка', '1'=>'Число')),
//				'tid' => array('*'=>array('1'=>'Продажа', '2'=>'Покупка', '3'=>'Обмен', '4'=>'Аренда')),
				'kid' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_kat WHERE aid>1 ORDER BY name")),
				'type' => array('*'=>array('text'=>'Строка', 'textarea'=>'Поле', 'select'=>'Список', 'radio'=>'Один', 'checkbox'=>'Много', 'img'=>'Фото')),
			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Варианты'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole", # Имя таблицы базы данных
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

			'title' => array('pid'=>'Поле', 'val'=>'Вариант'), # Название полей
//			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('orderby'=>array('*'=>"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'><a href='?m[menu]=admin&dec={f:id}'><img src='modules/menu/img/up.gif' border='0'></a></td><td align='center'><a href='?m[menu]=admin&inc={f:id}'><img src='modules/menu/img/down.gif' border='0'></a></td></tr></table>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'pid' => array('*'=>array('1'=>'Продажа', '2'=>'Покупка', '3'=>'Обмен', '4'=>'Аренда')),
				'pid' => array('*'=>spisok("SELECT id, title FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole ORDER BY title")),
//				'type' => array('*'=>array('text'=>'Строка', 'textarea'=>'Поле', 'select'=>'Список', 'radiobox'=>'Один', 'checkbox'=>'Много')),
			),
			'default' => array('pid'=>array('*'=>max($_GET['where']['pid'], $_POST['pid']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Значения'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_dop", # Имя таблицы базы данных
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

//			'title' => array('img'=>'Пиктограмма', 'name'=>'Имя', 'link'=>'Ссылка', 'orderby'=>'Сорт', 'description'=>'Описание'), # Название полей
//			'type' => array('description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('oid'=>array('*'=>"#{f:{f}}")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'oid' => array('*'=>spisok("SELECT id, CONCAT('#', id) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}")),
				'pid' => array('*'=>spisok("SELECT id, title FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole ORDER BY title")),
				'vid' => array('*'=>array('0'=>'')+spisok("SELECT id, val FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole")),
			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Стиль'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_style", # Имя таблицы базы данных
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

			'title' => array('name'=>'Имя', 'dob'=>'Добавление', 'vid'=>'Отображение', 'description'=>'Описание'), # Название полей
			'type' => array('dob'=>'textarea', 'vid'=>'textarea', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('orderby'=>array('*'=>"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'><a href='?m[menu]=admin&dec={f:id}'><img src='modules/menu/img/up.gif' border='0'></a></td><td align='center'><a href='?m[menu]=admin&inc={f:id}'><img src='modules/menu/img/down.gif' border='0'></a></td></tr></table>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'oid' => array('*'=>spisok("SELECT id, CONCAT('#', id) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}")),
//				'pid' => array('*'=>spisok("SELECT id, title FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole ORDER BY title")),
//				'vid' => array('*'=>array('0'=>'')+spisok("SELECT id, val FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole")),
//			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Города'){
	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_gorod", # Имя таблицы базы данных
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

//			'title' => array('name'=>'Имя', 'dob'=>'Добавление', 'vid'=>'Отображение', 'description'=>'Описание'), # Название полей
//			'type' => array('dob'=>'textarea', 'vid'=>'textarea', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('orderby'=>array('*'=>"<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td align='center'><a href='?m[menu]=admin&dec={f:id}'><img src='modules/menu/img/up.gif' border='0'></a></td><td align='center'><a href='?m[menu]=admin&inc={f:id}'><img src='modules/menu/img/down.gif' border='0'></a></td></tr></table>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'oid' => array('*'=>spisok("SELECT id, CONCAT('#', id) FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}")),
//				'pid' => array('*'=>spisok("SELECT id, title FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_pole ORDER BY title")),
//				'vid' => array('*'=>array('0'=>'')+spisok("SELECT id, val FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_vpole")),
//			),
//			'default' => array('kid'=>$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}

?>