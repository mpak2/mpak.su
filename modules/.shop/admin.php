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

mpmenu($menu = array('Каталог', 'Товары', 'Фото', 'Производители', 'Заказы', 'Выбор'));

if ($menu[(int)$_GET['r']] == 'Каталог'){
	$_GET['where']['pid'] = (int)$_GET['where']['pid'];
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_obj", # Имя таблицы базы данных
//			'where' => 'pid='.(int)$_GET['where'], # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
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

			'edit'=>'list',
			'title' => $arg['access'] > 4 ? array('pid'=>'Родитель', 'img'=>'Логотип', 'chld'=>'Вложения', 'name'=>'Имя', 'count'=>'Товаров', 'sort'=>'Сорт') : array('pid'=>'Родитель', 'img'=>'Логотип', 'chld'=>'Вложения', 'name'=>'Имя', 'count'=>'Товаров'), # Название полей
			'etitle' => array('pid'=>'Родитель', 'img'=>'Логотип', 'name'=>'Имя', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'chld'=>spisok("SELECT oo.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS oo, {$conf['db']['prefix']}{$arg['modpath']}_obj AS ot WHERE oo.id=ot.pid GROUP BY oo.id"),
				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&where[pid]={f:id}>{f:{f}}</a>"),
				'count'=>array('*'=>"<a href=/?m[services]=admin&r=1&where[oid]={f:id}>Нет</a>")+spisok("SELECT o.id, CONCAT('<a href=/?m[services]=admin&r=1&where[oid]=', o.id, '>', COUNT(*), '_стр</a>') AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE o.id=d.oid GROUP BY o.id"),
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/{f:id}/w:100/h:100/null/img.jpg>"),
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
}elseif ($menu[(int)$_GET['r']] == 'Товары'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_desc", # Имя таблицы базы данных
			'order' => 'id DESC', # Условия отбора содержимого
			'where' => $arg['access'] > 4 ? '' : 'uid='.(int)$conf['user']['uid'], # Сортировка вывода таблицы
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
			'title' => array('uid'=>'Пользователь', 'name'=>'Имя', 'price'=>'Цена', 'img'=>'Фото', 'itogo'=>'Cклад', 'photo'=>'Альбом'), # Название полей
			'etitle' => array('pid'=>'Производитель', 'uid'=>'Владелец', 'oid'=>'Каталог', 'name'=>'Имя', 'price'=>'Цена', 'img'=>'Фото', 'disable'=>'Активность', 'itogo'=>'Количество на складе', 'sort'=>'Сорт', 'description'=>'Описание', 'text'=>'Текст'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/{f:id}/tn:1/w:100/h:100/null/img.jpg>"),
				'photo'=>array('*'=>"<a href=/?m[services]=admin&r=2&where[did]={f:id}>Нет</a>")+spisok("SELECT d.id, CONCAT('<a href=/?m[services]=admin&r=2&where[did]=', d.id, '>', COUNT(*), '_стр</a>') AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE d.id=i.did GROUP BY d.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'sity_id'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_sity ORDER BY name", 20)),
				'photo' => array('*'=>spisok("SELECT d.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE d.id=i.did GROUP BY d.id")),
				'oid' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY name", 20)),
				'uid' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users ORDER BY name", 20)),
				'disable'=>array('*'=>array('0'=>'Видим', '1'=>'Скрыт')),
				'pid' =>array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_producer", 20)),
			),
			'default' => array(
				'uid'=>array('*'=>$conf['user']['uid']),
				'oid'=>array('*'=>max($_GET['where']['oid'], $_POST['oid'])),
				'pid'=>array('*'=>max($_GET['where']['pid'], $_POST['pid'])),
			), # Значение полей по умолчанию
//			'maxsize' => array('*'=>array('oid'=>'10')), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Фото'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_img", # Имя таблицы базы данных
			'where' =>$arg['access'] >= 5 ? '' : "did IN (SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE uid=".(int)$conf['user']['uid'].")", # Условия отбора содержимого
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
			'title' => array('did'=>'Товар', 'img'=>'Фото', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/{f:id}/tn:2/w:120/h:100/null/img.jpg>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'admin' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin")),
				'did' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_desc")),
			),
			'default' => array('did'=>array('*'=>max($_GET['where']['did'], $_POST['did']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Производители'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_producer", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
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

			'edit'=>'list',
			'title' => array('img'=>'Логотип', 'name'=>'Название', 'count'=>'Количество', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/{f:id}/tn:3/w:120/h:100/null/img.jpg>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'admin' => array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin")),
//				'did' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_desc")),
//			),
//			'default' => array('oid'=>array('*'=>max($_GET['where']['did'], $_POST['did']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Выбор'){

	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_order", # Имя таблицы базы данных
			'where' => $arg['access'] < 5 ? "bid IN (SELECT o.bid FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE d.uid=". (int)$conf['user']['uid']. " GROUP BY o.bid)" : '', # Условия отбора содержимого
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

//			'edit'=>'list',
			'title' => array('time'=>'Время', 'bid'=>'Заказ', 'did'=>'Товар', 'count'=>'Количество'), # Название полей
			'type' => array('time'=>'timestamp'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'bid'=>array('*'=>'# {f:bid}'),
//				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/null/{f:id}?tn=3&w=120&h=100>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'did' =>array('*'=>spisok("SELECT d.id, CONCAT(d.name, ' (', u.name, ')') FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d LEFT JOIN {$conf['db']['prefix']}users AS u ON d.uid=u.id")),
			),
//			'default' => array('oid'=>array('*'=>max($_GET['where']['did'], $_POST['did']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Заказы'){
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_basket", # Имя таблицы базы данных
			'where' => $arg['access'] > 4 ? '' : "id IN (SELECT o.bid FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE d.uid=". (int)$conf['user']['uid']. ")", # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => true, # Вывод всех SQL запросов
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

			'edit'=>'list',
			'title' => array('time'=>'Время', 'sum'=>'Сумма', 'sid'=>'Сессия', 'uid'=>'Пользователь', 'count'=>'Товары', 'close'=>'Статус'), # Название полей
			'type' => array('time'=>'timestamp', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'close'=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$_GET['r']}&edit={f:id}'>{spisok:close}</a>"),
				'sid'=>array('*'=>"<a href=/?m[sess]=admin&where[id]={f:id}>{f:{f}}</a>"),
				'count'=>spisok("SELECT b.id, CONCAT('<a href=/?m[services]=admin&r=5&where[bid]=', b.id, '>', COUNT(*), '_наим</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_basket AS b, {$conf['db']['prefix']}{$arg['modpath']}_order AS o WHERE b.id=o.bid GROUP BY b.id"),
//				'img'=>array('*'=>"<img src=/{$arg['modpath']}:img/null/{f:id}?tn=3&w=120&h=100>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'uid' =>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
				'close'=>array('*'=>array('0'=>'Новый', '1'=>'Оформлен', '2'=>'Оплачен', '3'=>'Отменен', '4'=>'Доставлен')),
			),
//			'default' => array('oid'=>array('*'=>max($_GET['where']['did'], $_POST['did']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
//	mpre(mpql(mpqw()));
//	echo mysql_error();
}

?>