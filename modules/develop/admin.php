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
	"{$arg['modpath']}_golos"=>"Голоса",
	"{$arg['modpath']}_kat"=>"Категории",
	"{$arg['modpath']}_plan"=>"Планы",
	"{$arg['modpath']}_work"=>"Работа",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_plan"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => 'sort ', # Условия отбора содержимого
			'order' => 'sort DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'count_rows' => 15, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны
			'shablon' => array(
//				'procent'=>spisok("SELECT DISTINCT p.id, CONCAT(w.procent, ' %') FROM {$conf['db']['prefix']}{$arg['modpath']}_plan as p, {$conf['db']['prefix']}{$arg['modpath']}_work as w WHERE p.id = w.pid"),
//				'plan'=>array('*'=>"<a href=?m[{$arg['modpath']}]=admin&r=2&where[pid]={f:id}>{f:{f}}</a>"),
			),
			'edit'=>'list',
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('kid'=>'Категория', 'uid'=>'Пользователь', 'plan'=>'План', 'time'=>'Время', 'procent'=>'Процент', 'sort'=>'Сорт'), # Название полей
			'type' => array('plan'=>'textarea', 'time'=>'timestamp', 'sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'kid'=>array('*'=>array('0'=>'Новая')+(array)spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kat ORDER BY sort")),
				'uid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
			),
			'default' => array('time'=>date('Y.m.d H:i:s'), 'kid'=>max($_GET['where']['kid'], $_POST['kid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_work"){
	for($i = 5; $i <= 100; $i+=5)
		$procent[$i] = "$i %";
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'time DESC', # Сортировка вывода таблицы
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
			'shablon' => array(
				'uid' => spisok("SELECT w.id, u.name FROM {$conf['db']['prefix']}users as u, {$conf['db']['prefix']}{$arg['modpath']}_work as w, {$conf['db']['prefix']}{$arg['modpath']}_plan as p WHERE u.id=p.uid AND p.id=w.plan_id"),
//				'uid'=>array('procent'=>'{f:{f}} %'),
			),
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('plan_id'=>'План', 'uid'=>'Пользователь', 'description'=>'Описание', 'time'=>'Время', 'procent'=>'Процент'), # Название полей
			'type' => array('description'=>'textarea', 'time'=>'timestamp'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'procent' => array('*'=>$procent),
				'plan_id'=>array('*'=>array('0'=>'Вне планов')+(array)spisok("SELECT id, plan FROM {$conf['db']['prefix']}{$arg['modpath']}_plan", 30)),
			),
			'default' => array('time'=>date('Y.m.d H:i:s'), 'pid'=>array('*'=>$_GET['where']['pid']), 'procent'=>$_GET['where']['pid'] ? mpql(mpqw("SELECT MAX(procent) as max FROM {$conf['db']['prefix']}{$arg['modpath']}_work WHERE pid={$_GET['where']['pid']}"), 0, 'max') : null), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
//	mpre(spisok("SELECT w.id, u.name FROM {$conf['db']['prefix']}users as u, {$conf['db']['prefix']}{$arg['modpath']}_work as w, {$conf['db']['prefix']}{$arg['modpath']}_plan as p WHERE u.id=p.uid AND p.id=w.pid"));
//	echo $_GET['where']['pid'];
//	echo $_GET['where']['pid'] ? mpql(mpqw("SELECT MAX(procent) as max FROM {$conf['db']['prefix']}{$arg['modpath']}_work WHERE pid={$_GET['where']['pid']}"), 0, 'max') : null;
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_kat"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
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
			'shablon' => array(
				'name'=>array('*'=>"<a href=?m[{$arg['modpath']}]=admin&where[kid]={f:id}>{f:{f}}</a>"),
				'count'=>spisok("SELECT k.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_kat AS k, {$conf['db']['prefix']}{$arg['modpath']}_plan AS p WHERE k.id=p.kid GROUP BY k.id"),
			),
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('name'=>'Категория', 'count'=>'Количество', 'sort'=>'Сорт', 'description'=>'Описание'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'procent' => array('*'=>$procent),
//				'pid'=>array('*'=>array('0'=>'Вне планов')+spisok("SELECT id, plan FROM {$conf['db']['prefix']}{$arg['modpath']}_plan", 30)),
//			),
//			'default' => array('time'=>date('Y.m.d H:i:s'), 'pid'=>array('*'=>$_GET['where']['pid']), 'procent'=>$_GET['where']['pid'] ? mpql(mpqw("SELECT MAX(procent) as max FROM {$conf['db']['prefix']}{$arg['modpath']}_work WHERE pid={$_GET['where']['pid']}"), 0, 'max') : null), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_golos"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'time DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны
//			'shablon' => array(
//				'name'=>array('*'=>"<a href=?m[{$arg['modpath']}]=admin&where[kid]={f:id}>{f:{f}}</a>"),
//				'count'=>spisok("SELECT k.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_kat AS k, {$conf['db']['prefix']}{$arg['modpath']}_plan AS p WHERE k.id=p.kid GROUP BY k.id"),
//			),
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

//			'title' => array('time'=>'Время', 'pid'=>'Задача', 'uid'=>'Пользователь'), # Название полей
			'type' => array('time'=>'timestamp'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'pid'=>array('*'=>array('0'=>'Вне планов')+(array)spisok("SELECT id, plan FROM {$conf['db']['prefix']}{$arg['modpath']}_plan", 30)),
//				'procent' => array('*'=>$procent),
				'uid'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users", 30)),
			),
//			'default' => array('time'=>date('Y.m.d H:i:s'), 'pid'=>array('*'=>$_GET['where']['pid']), 'procent'=>$_GET['where']['pid'] ? mpql(mpqw("SELECT MAX(procent) as max FROM {$conf['db']['prefix']}{$arg['modpath']}_work WHERE pid={$_GET['where']['pid']}"), 0, 'max') : null), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r']){ echo "{$_GET['r']}:". __LINE__;
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
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

//			'title' => array('name'=>'Имя', 'img'=>'Изображение', 'description'=>'Описание'), # Название полей
//			'etitle'=> array(),
//			'type' => array('img'=>'file', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
//				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:index/w:120/h:100/null/img.jpg'>"),
//				'cnt'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[kid]={f:id}>Нет</a>")+spisok("SELECT c.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[kid]=', c.id, '>', COUNT(*), '_фото</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE c.id=i.kid GROUP BY c.id"),
//			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
//			),
//			'default' => array(
//				($fn = 'type_id')=>array('*'=>max($_GET['where'][$fn], $_POST[$fn])),
//			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}

?>