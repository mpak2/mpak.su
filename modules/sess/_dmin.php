<?
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

$admin = $arg['access'] > 5;

mpmenu($m = array('Сессии')+($admin ? array(1=>'Содержание', 2=>'Значения') : array()));

if ($m[ (int)$_GET['r'] ] == 'Сессии'){
	if (!isset($_GET['order'])) $_GET['order'] = 'last_time DESC';
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[sess]=admin", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}sess", # Имя таблицы базы данных
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
			'edit'=>'list',

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('uid'=>'Пользователь', 'geoname_id'=>"Город", 'last_time'=>'Вход', 'count'=>'Запр', 'cnull'=>'Нуль', 'count_time'=>'Время', 'ip'=>'Адрес', 'agent'=>'Агент')+($admin ? array('sess'=>'Сессия') : array()), # Название полей
			'type' => array('last_time'=>'timestamp', 'count_time'=>'timecount'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'sess'=>array('*'=>'<a href=?m[sess]=admin&r=1&where[sid]={f:id}>{f:{f}}</a>'),
				'ip'=>array('*'=>'<nobr><a href=http://geotool.servehttp.com/?ip={f:{f}} target=_blank><img src=http://geo.s86.ru/?flag={f:{f}} border=0></a>&nbsp;{f:{f}}</nobr>'),
				'uid'=>spisok("SELECT s.id, CONCAT('<font color=red>', name, '</font>') FROM {$conf['db']['prefix']}users as u, {$conf['db']['prefix']}sess as s WHERE name = '{$conf['settings']['admin_usr']}' AND s.uid=u.id"),
				'agent'=>array('*'=>'<div title="{f:ref}"><a href="{f:ref}">{f:{f}}</a></div>'),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
			'disable' => ($admin ? array('img') : array()), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				($fn = "geoname"). "_id" => array('*'=>array('')+spisok("SELECT id, name FROM {$conf['db']['prefix']}users_{$fn}")),
				'uid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users WHERE name <> '{$conf['settings']['admin_usr']}' ORDER BY name")),
//				'time' => $time,
			),
//			'default' => array('uid'=>array('*'=>$conf['settings']['default_usr'])), # Значение полей по умолчанию
//			'maxsize' => array('agent'=>'11'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[ (int)$_GET['r'] ] == 'Содержание'){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[sess]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}sess_post", # Имя таблицы базы данных
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

			'title' => array('sid'=>'Сессия', 'url'=>'Адрес', 'time'=>'Время', 'method'=>'Метод', 'post'=>'Данные'), # Название полей
			'type' => array('time'=>'timestamp', 'post'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'sid'=>array('*'=>'<a href=?m[sess]=admin&where[id]={f:{f}}>Сессия&nbsp;#{f:{f}}</a>'),
				'url'=>array('*'=>'<a href=?{f:{f}}>{f:{f}}</a>'),
				'uid'=>spisok("SELECT p.id, u.name FROM {$conf['db']['prefix']}sess_post as p, {$conf['db']['prefix']}sess as s, {$conf['db']['prefix']}users as u WHERE u.id = s.uid AND s.id = p.sid"),
				'post'=>array('*'=>'<a href=/?m[sess]=admin&r=2&where[pid]={f:id}>{f:{f}}</a>'),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'uid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
//				'time' => $time,
//			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
			'maxsize' => array('post'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($m[ (int)$_GET['r'] ] == 'Значения'){
	if(!$_GET['where']['pid']){
		$_GET['where']['pid'] = mpql(mpqw("SELECT MAX(id) as max FROM {$conf['db']['prefix']}sess_post"), 0, 'max');
	}
	$sql = "SELECT * FROM {$conf['db']['prefix']}sess_post WHERE id=".(int)$_GET['where']['pid'];
//	echo htmlspecialchars(mpql(mpqw($sql), 0, 'post');
	$sess = mpql(mpqw($sql), 0);
	$post = unserialize($sess['post']);
	echo "<p>Сессия: ".date('Y.m.d H:i:s', $sess['time']);
//	mpre($post);
	foreach($post as $k=>$v){
		echo "<p><fieldset style='background-color: #eeeeee'><legend>{$k}</legend>";
//		echo $k;
//		echo "<fieldset><legend>{$k}</legend>";
//		mpre($v);
//		echo "</fieldset>";
		foreach($v as $n=>$z){
			echo "<fieldset style='background-color: #ffffff'><legend>{$n}</legend><textarea style='width: 100%; height: ".(18*count(explode("\n", gettype($z) == 'array' ? trim(mpre($z)) : $z))).";'>";
			echo (gettype($z) == 'array' ? mpre($z) : strtr($z, array('>'=>'&#62;', '<'=>'&#60;')));
			echo "</textarea></fieldset>";
		}
		echo "</fieldset>";
	}
//	echo ord("'");
}

?>
