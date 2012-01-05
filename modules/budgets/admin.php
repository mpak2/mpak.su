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

mpmenu($m = array('Игры', 'Изображения', 'Категории', 'СписокКатегорий'));

if ($m[(int)$_GET['r']] == 'Игры'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_list", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'edit'=>'list',
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('cat'=>'Категории', 'name'=>'Название', 'img'=>'Изображение', 'sort'=>'Сорт', 'link'=>'Ссылка', 'count'=>'Просмотров', 'сimg'=>'Изображений'), # Название полей
			'etitle'=> array('name'=>'Название', 'name'=>'Название', 'img'=>'Изображение', 'link'=>'Ссылка', 'count'=>'Просмотров', 'description'=>'Описание'),
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Вкладка', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:list/w:120/h:100/null/img.jpg'>"),
				'сimg'=>array('*'=>"<a href='/?m[budgets]=admin&r=".array_search('Изображения', $m)."&where[lid]={f:id}'>Нет</a>")+spisok("SELECT l.id, CONCAT('<a href=/?m[budgets]=admin&r=".array_search('Изображения', $m)."&where[lid]={f:id}>', COUNT(*), '_изобр.</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_list AS l, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE l.id=i.lid GROUP BY l.id"),
				'cat'=>array('*'=>"<a href='/?m[budgets]=admin&r=".array_search('Категории', $m)."&where[lid]={f:id}'>Нет</a>")+spisok("SELECT l.id, CONCAT('<a href=/?m[budgets]=admin&r=".array_search('Категории', $m)."&where[lid]={f:id}>', COUNT(*), '_кат.</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_list AS l, {$conf['db']['prefix']}{$arg['modpath']}_list_cat AS c WHERE l.id=c.lid GROUP BY l.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'cid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
			),
//			'default' => array('time'=>date('Y.m.d H:i:s'), 'rid'=>array('*'=>max($_POST['rid'], $_GET['where']['rid']))), # Значение полей по умолчанию
			'maxsize' => array('description'=>'250'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'Изображения'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_img", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id DESC', # Сортировка вывода таблицы
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

//			'title' => array('img'=>'Пиктограмма', 'name'=>'Имя', 'link'=>'Ссылка', 'orderby'=>'Сорт', 'description'=>'Описание'), # Название полей
//			'etitle'=> array(),
			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Вкладка', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:img/w:120/h:100/null/img.jpg'>"),
//				'count'=>spisok("SELECT r.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_region AS r, {$conf['db']['prefix']}{$arg['modpath']} AS m WHERE r.id=m.rid GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'lid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_list")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
			),
			'default' => array('lid'=>array('*'=>max($_POST['lid'], $_GET['where']['lid']))), # Значение полей по умолчанию
//			'maxsize' => array('description'=>'250'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'Категории'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_list_cat", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id DESC', # Сортировка вывода таблицы
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

			'title' => array('lid'=>'Игры', 'cid'=>'Категория'), # Название полей
//			'etitle'=> array(),
//			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Вкладка', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
//				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:cat/w:120/h:100/null/img.jpg'>"),
//				'count'=>spisok("SELECT c.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c, {$conf['db']['prefix']}{$arg['modpath']}_list AS l WHERE c.id=l.cid GROUP BY c.id"),
//			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'lid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_list")),
				'cid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
			),
			'default' => array(
				'lid'=>array('*'=>max($_POST['lid'], $_GET['where']['lid'])),
				'cid'=>array('*'=>max($_POST['cid'], $_GET['where']['cid'])),
			), # Значение полей по умолчанию
//			'maxsize' => array('description'=>'250'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[(int)$_GET['r']] == 'СписокКатегорий'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_cat", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id DESC', # Сортировка вывода таблицы
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

			'title' => array('img'=>'Пиктограмма', 'type'=>'Тип', 'name'=>'Имя', 'count'=>'Количество', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
//			'etitle'=> array(),
			'type' => array('img'=>'file', 'sort'=>'sort', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Вкладка', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:cat/w:120/h:100/null/img.jpg'>"),
				'count'=>spisok("SELECT c.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c, {$conf['db']['prefix']}{$arg['modpath']}_list AS l WHERE c.id=l.cid GROUP BY c.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'lid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_list")),
				'type'=>array('*'=>array('0'=>'Компьютер', '1'=>'Телефон', '2'=>'Приставка')),
//				'time' => $time,
			),
//			'default' => array('lid'=>array('*'=>max($_POST['lid'], $_GET['where']['lid']))), # Значение полей по умолчанию
//			'maxsize' => array('description'=>'250'), # Максимальное количество символов в поле
		)
	);
}

?>