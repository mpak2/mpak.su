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

//if ($_GET['img'])
//	getfile(mpql(mpqw("SELECT img FROM {$GLOBALS['conf']['db']['prefix']}randimg WHERE id=".(int)$_GET['img'])));
mp_require_once("include/func.php"); # Функции таблиц
mpmenu($menu = array('Темы', 'Блоки', 'svg', 'Дамп'));

if ($menu[(int)$_GET['r']] == 'Темы'){
	stable(
		array(
//			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}", # Имя таблицы базы данных
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
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title'=>$title=array('modpath'=>'Модуль', 'type'=>'Тип', 'name'=>'Название', 'theme'=>'Шаблон'), # Название полей
			'type'=>$type=array('img'=>'file', 'orderby'=>'sort'), # Тип полей
			'bottom'=>array(
				'shablon'=>bottom(array('title'=>$title, 'type'=>array('modpath'=>'spisok', 'type'=>'spisok')+$type, 'colspan'=>array('name'=>3)), array('theme'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1)."><textarea style='width:100%;height:300px;' name='theme'>".@htmlspecialchars(mpql(mpqw("SELECT theme FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['edit']), 0, 'theme'))."</textarea></td></tr><tr bgcolor='#eeeeee'><td colspan=".count($title).">&nbsp;</td>"))
			),
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'name'=>array('*'=>"<a href='/themes/{f:id}/null'>{f:{f}}</a>"),
				'type'=>spisok("SELECT id, CONCAT('<a style=\'font-weight: bold;\' href={$GLOBALS['conf']['settings']['start_mod']}/theme:', id, '>', type, '</a>') FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} WHERE type='text/html'")//array('*'=>"<a href={$GLOBALS['conf']['settings']['start_mod']}/theme:{f:id}>{f:{f}}</a>"),
//				'theme'=>array('*'=>"<a href=\"/{$arg['modpath']}/{f:id}/null\">{f:{f}}</a>")
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'modpath'=>array('*'=>array(''=>'*')+spisok("SELECT folder, name FROM {$GLOBALS['conf']['db']['prefix']}modules")),
				'type' => array('*'=>array('text/html'=>'text/html', 'text/css'=>'text/css')),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
			'maxsize' => array('theme'=>'500'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Блоки'){
	stable(
		array(
//			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_blk", # Имя таблицы базы данных
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
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title'=>$title=array('tid'=>'Тема', 'sort'=>'Сорт', 'name'=>'Название', 'theme'=>'Шаблон'), # Название полей
			'type'=>$type=array('sort'=>'sort'), # Тип полей
			'bottom'=>array(
				'shablon'=>bottom(
					array(
						'title'=>$title,
						'type'=>array(
							'tid'=>'spisok',
						)+$type,
						'colspan'=>array(
							'name'=>3
						),
					),
					array(
						'theme'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1)."><textarea style='width:100%;height:300px;' name='theme'>".htmlspecialchars(mpql(mpqw("SELECT theme FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_blk WHERE id=".(int)$_GET['edit']), 0, 'theme'))."</textarea></td></tr><tr bgcolor='#eeeeee'><td colspan=".count($title).">&nbsp;</td>"
					)
				)
			),
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('name'=>array('*'=>"<a href=/{$arg['modpath']}/{f:id}/null><img src=/img/copy.png border=0></a>&nbsp;<a href={$GLOBALS['conf']['settings']['start_mod']}/theme:{f:id}>{f:{f}}</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'tid'=>array('*'=>spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} WHERE type='text/html'")),
				'type' => array('*'=>array('text/html'=>'text/html', 'text/css'=>'text/css')),
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
			'maxsize' => array('theme'=>'500'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'svg'){
	stable(
		array(
//			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_svg", # Имя таблицы базы данных
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
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title'=>$title=array('tid'=>'Тема', 'url'=>'Ссылка', 'svg'=>'Код изображения'), # Название полей
			'type'=>$type=array('svg'=>'textarea'), # Тип полей
//			'bottom'=>array(
//				'shablon'=>bottom(array('title'=>$title, 'type'=>array('modpath'=>'spisok')+$type, 'colspan'=>array('name'=>3)), array('theme'=>"</tr><tr bgcolor='#eeeeee'><td colspan=".(count($title)+1)."><textarea style='width:100%;height:300px;' name='theme'>".htmlspecialchars(mpql(mpqw("SELECT theme FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['edit']), 0, 'theme'))."</textarea></td></tr><tr bgcolor='#eeeeee'><td colspan=".count($title).">&nbsp;</td>"))
//			),
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('url'=>array('*'=>"<a href=/?m[{$arg['modpath']}]&svg={f:id}&null>/?m[{$arg['modpath']}]&svg={f:id}&null</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'tid'=>array('*'=>array('0'=>'*')+spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}")),
//				'time' => $time,
			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
			'maxsize' => array('svg'=>'500'), # Максимальное количество символов в поле
		)
	);
}elseif ($menu[(int)$_GET['r']] == 'Дамп'){

echo "<p>&lt;? die;<br /><br />";
foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}")) as $k=>$v){
	if ($v['id'] < 3) continue;
	echo "<p><pre>\$data = &lt;&lt;&lt;EOF\n";
	echo htmlspecialchars($v['theme']);
	echo "\nEOF;</pre>";
	echo "mpqw(\"INSERT INTO mp_themes (`id` ,`modpath`, `type` ,`name` ,`theme`) VALUES ('{$v['id']}' ,'', '{$v['type']}' ,'{$v['name']}' ,\\\"\".str_replace('\"', '\\\"', \$data).\"\\\")\");";
}
foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_blk")) as $k=>$v){
	echo "<p><pre>\$data = &lt;&lt;&lt;EOF\n";
	echo htmlspecialchars($v['theme']);
	echo "\nEOF;</pre>";
	echo "mpqw(\"INSERT INTO mp_themes_blk (`id` ,`tid`, `sort` ,`name` ,`theme`) VALUES ('{$v['id']}' ,'{$v['tid']}', '{$v['sort']}' ,'{$v['name']}' ,\\\"\".str_replace('\"', '\\\"', \$data).\"\\\")\");";
}
echo "<br /><br />?&gt;";

}

?>