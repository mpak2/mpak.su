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
	"{$arg['modpath']}_region"=>"Меню",
	"{$arg['modpath']}"=>"Ссылки",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_region"){ // 'Меню'
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
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

			'title' => array('name'=>'Имя', 'region'=>'Ссылки', 'description'=>'Описание'),
//			'etitle'=> array(),
//			'type' => array('img'=>'file', 'orderby'=>'sort'),
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Содержимое', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
//				'link'=>array('*'=>"<a href='{f:{f}}'>{f:{f}}</a>"),
//				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/null/{f:id}?w=120&h=120'>"),
//				'count'=>spisok("SELECT r.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_region AS r, {$conf['db']['prefix']}{$arg['modpath']} AS m WHERE r.id=m.rid GROUP BY r.id"),
				(($fn = 'region'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}&where[rid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}&where[rid]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']} AS fn WHERE r.id=fn.rid GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'field' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$conf['db']['prefix']}")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
//			),
//			'default' => array('kid'=>max($_POST['']$_GET['where']['kid']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}"){ //'Содержимое'
//	$_GET['where']['pid'] = (int)$_GET['where']['pid'];
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => 'pid='.(int)$_GET['where']['pid'], # Условия отбора содержимого
			'order' => 'orderby', # Сортировка вывода таблицы
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

			'title' => array('rid'=>'Меню', 'pid'=>'Родитель', 'img'=>'Логотип', 'name'=>'Имя', 'menu'=>'Вложений', 'link'=>'Ссылка', 'orderby'=>'Сортировка'),
			'etitle'=>array('rid'=>'Регион', 'pid'=>'Родитель', 'img'=>'Логотип', 'name'=>'Имя', 'link'=>'Ссылка', 'orderby'=>'Сортировка', 'description'=>'Описание'),
//			'etitle'=> array(),
			'type' => array('img'=>'file', 'orderby'=>'sort', 'description'=>'textarea'),
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/w:100/h:80/null/img.jpg'>"),
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=1&where[rid]={$_GET['where']['rid']}&where[pid]={f:id}>{f:{f}}</a>"),
//				'count'=>spisok("SELECT m1.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']} AS m1, {$conf['db']['prefix']}{$arg['modpath']} AS m2 WHERE m1.id=m2.pid GROUP BY m1.id"),
				(($fn = 'menu'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}&where[pid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}&where[pid]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']} AS fn WHERE r.id=fn.pid GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'pid'=>array('*'=>array('0'=>'')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}".($_GET['rid'] ? " WHERE rid=".(int)$_GET['rid'] : ''))),
				'rid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
			),
			'default' => array(
				'pid'=>array('*'=>max($_POST['pid'], $_GET['where']['pid'])),
				'rid'=>array('*'=>max($_POST['rid'], $_GET['where']['rid'])),
			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}

?>