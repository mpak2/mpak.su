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
	'news_post'=>'Статьи',
	'news_kat'=>'Категории',
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_kat"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
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

			'title' => array('img'=>'Логотип', 'name'=>'Название', 'post'=>'Статей', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'name'=>array('*'=>"<a href='/{$arg['modname']}/kid:{f:id}'>{f:{f}}</a>"),
//				'count'=>spisok("SELECT k.id, CONCAT('<a href=\"/?m[{$arg['modpath']}]=admin&where[kid]=', k.id, '\">', COUNT(*), '_нов</a>') AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_kat AS k, {$conf['db']['prefix']}{$arg['modpath']}_post AS p WHERE k.id=p.kid GROUP BY k.id"),
				'post'=>spisok("SELECT k.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_post&where[kid]=', k.id, '>', COUNT(p.id), '_стат</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_kat AS k LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_post AS p ON k.id=p.kid GROUP BY k.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'parent' => array('*' => array(0=>'..') + (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}news_kat")),
//				'time' => $time,
			),
			'default' => array(
				'time'=>array('*'=>date('Y.m.d H:i:s')),
			), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_post"){
	if($_POST){
		mpre($_POST);
		echo $max = mpql(mpqw("SELECT MAX(id) AS max FROM {$conf['db']['prefix']}{$arg['modpath']}_post"), 0, 'max');
		mpevent("Новая новость", $_POST['tema'], $conf['user']['uid'], array("id"=>$max+1)+$_POST);
	}
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
			'where' => $arg['access'] >= 5 ? '' : 'uid='.(int)$conf['user']['uid'], # Условия отбора содержимого
			'order' => 'id DESC', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'count_rows' => 5, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

			'edit'=>'list',
//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы
//			'bottom' => array('shablon'=>"<td>{spisok:kid:*}</td><td><input type='text' name='time' value='{edit:time}' style='width:100%'></td><td><input type='file' name='img'></td><td colspan=3><input type='text' name='tema' style='width:100%' value='{edit:tema}' style='width:100%'></td></tr><tr bgcolor='#eeeeee'><td colspan='6'><table width='100%'><tr><td><textarea name='text' style='width:100%; height:400px'>{edit:text}</textarea></td></tr></table></td></tr><tr align='right' bgcolor='#eeeeee'><td colspan='5'>"), # Формат записей таблицы

//			'bottom' => array('shablon'=>"<td>{spisok:kid:*}</td><td><input type='text' name='time' value='{edit:time}' style='width:100%'></td><td><input type='file' name='img'></td><td colspan=3><input type='text' name='tema' style='width:100%' value='{edit:tema}' style='width:100%'></td></tr><tr><td colspan=6>".mpwysiwyg('text', $_GET['edit'] ? mpql(mpqw("SELECT text FROM {$conf['db']['prefix']}news_post WHERE id=".$_GET['edit']), 0, 'text') : '')."</td></tr><tr bgcolor='#eeeeee'><td colspan='5'>&nbsp;</td>"),

//			<table border=1 width=100%><tr align='right' bgcolor='#eeeeee'><td colspan='5'>&nbsp;"), # Формат записей таблицы <table width='100%' border=1><tr><td><textarea name='text' style='width:100%; height:400px'>{edit:text}</textarea></td></tr></table>

			'title' => array('kid'=>'Категория', 'time'=>'Время', 'img'=>'Изображение', 'tema'=>'Тема', 'text'=>'Текст'), # Название полей
			'etitle' => array('kid'=>'Категория', 'sort'=>'Сорт', 'uid'=>'Пользователь', 'count'=>'Просмотров', 'time'=>'Время', 'img'=>'Изображение', 'tema'=>'Тема', 'text'=>'Текст'), # Название полей
			'type' => array('text'=>'wysiwyg', 'time'=>'timestamp', 'img'=>'file'), # Тип полей

//			'bottom' => array('shablon'=>bottom(array('title'=>$title, 'type'=>array('kid'=>'spisok')+$type, 'colspan'=>array('text'=>0, 'tema'=>3)), array('text'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1).">".mpwysiwyg('text', $_GET['edit'] ? mpql(mpqw("SELECT text FROM {$conf['db']['prefix']}{$arg['modpath']}_post WHERE id=".$_GET['edit']), 0, 'text') : '')."</td></tr><tr bgcolor='#eeeeee'><td colspan=".count($title).">&nbsp;</td>"))),

			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
			'set' => array('uid'=>$conf['user']['uid']), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array( # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
				'img'=>array('*'=>"<img src=\"/{$arg['modpath']}:img/{f:id}/w:120/h:100/null/img.jpg\">"),
				'tema'=>array('*'=>"<a href='/{$arg['modname']}/{f:id}'>{f:{f}}</a>"),
//				'uid'=>array('*'=>"<a href='?m[news]=admin&r={$_GET['r']}&where[{f}]={f:{f}}'>{spisok:{f}:{f:{f}}}</a>"),
				'kid'=>spisok("SELECT p.id, k.name FROM {$conf['db']['prefix']}news_kat as k, {$conf['db']['prefix']}news_post as p WHERE k.id = p.kid"),
			),
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array('count'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'kid' => array('*' => (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}news_kat")),
//				'uid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
			),
			'default' => array( # Значение полей по умолчанию
				'time'=>date('Y.m.d H:i:s'),
			),
			'maxsize' => array('text'=>'150'), # Максимальное количество символов в поле
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