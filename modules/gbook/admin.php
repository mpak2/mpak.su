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

//foreach($m = array('Изображения', 'Категории') as $k=>$v){
//	echo " | <a href='?m[foto]=admin&r=$k'>".($k == $_GET['r'] ? "<font color='blue'>" : '')."$v".($k == $_GET['r'] ? "</font>" : '')."</a>";
//}

$conf['settings'] += array(
	"{$arg['modpath']}"=>"Сообщения",
	"{$arg['modpath']}_count"=>"Счетчик",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));


//	if (!isset($_GET['order'])) $_GET['order'] = 'id DESC';
if($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}"){
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
			'count_rows' => 5, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'edit' => 'list',
			'title' => array('otime'=>'Время', 'uid'=>'Пользователь', 'img'=>'Изображение', 'name'=>'Подпись', 'hide'=>'Скрыт', 'text'=>'Вопрос', 'otvet'=>'Ответ'), # Название полей
//			'etitle'=>array('uid'=>'Пользователь', 'name'=>'Имя', 'parent'=>'Родитель', 'vid'=>'Видимость', 'time'=>'Время', 'otime'=>'Время ответа', 'text'=>'Вопрос', 'otvet'=>'Ответ'),
			'type' =>array('otime'=>'timestamp', 'img'=>'file', 'time'=>'timestamp', 'text'=>'textarea', 'otvet'=>'textarea'), # Тип полей

//			'bottom' => array('shablon'=>bottom(array('title'=>$title, 'type'=>array('vid'=>'spisok', 'uid'=>'spisok')+$type, 'colspan'=>array('vid'=>4)), array('text'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1).">".mpwysiwyg('text', $_GET['edit'] ? mpql(mpqw("SELECT text FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".$_GET['edit']), 0, 'text') : '')."</td></tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1).">&nbsp;</td>", 'otvet'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1).">".mpwysiwyg('otvet', $_GET['edit'] ? mpql(mpqw("SELECT otvet FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".$_GET['edit']), 0, 'otvet') : '')."</td></tr><tr bgcolor='#eeeeee'><td colspan=".count($title).">&nbsp;</td>"))),
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('uid'=>$conf['user']['uid'], 'otime'=>time()), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/w:120/h:100/null/img.jpg'>"),
//				'otime'=>array('*'=>'Вопрос:<br>{f:time}<p>Ответ:<br>{f:{f}}')
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('uid'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'name' => array('*'=>array(''=>'CФ_Югрател')),
				'uid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
				'vid'=>array('*'=>array(1=>'Скрыт', 0=>'Видим')),
				'parent' => array('*' => array(0=>'') + (array)spisok("SELECT id, text FROM {$conf['db']['prefix']}gbook WHERE parent = 0 ORDER BY id DESC", 30)),
			),
			'default' => array(
				'time'=>date('Y.m.d H:i:s'),
				'vid'=>array('*'=>(int)$_GET['default']['vid']),
				'parent'=>array('*'=>(int)$_GET['default']['parent'])
			), # Значение полей по умолчанию
			'maxsize' => array('text'=>'250', 'otvet'=>'250'), # Максимальное количество символов в поле
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