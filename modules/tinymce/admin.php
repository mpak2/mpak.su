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

/*mpmenu($m = array('Вкладка'));

if ($m[(int)$_GET['r']] == 'Вкладка'){
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}{$arg['modpath']}_index", # Имя таблицы базы данных
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
			'type' => array('img'=>'file', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=".array_search('Вкладка', $m)."&where[rid]={f:id}>{f:{f}}</a>"),
				'img'=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:index/w:120/h:100/null/img.jpg'>"),
//				'cnt'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Изображения', $m). "&where[kid]={f:id}>Нет</a>")+spisok("SELECT c.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r=". array_search('Изображения', $m). "&where[kid]=', c.id, '>', COUNT(*), '_фото</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c, {$conf['db']['prefix']}{$arg['modpath']}_img AS i WHERE c.id=i.kid GROUP BY c.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
//				'select'=>array('*'=>array('0'=>'Скрыто', '1'=>'Доступно')),
//				'time' => $time,
			),
			'default' => array('uid'=>array('*'=>$conf['user']['uid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>array('*'=>'50'),), # Максимальное количество символов в поле
		)
	);
}*/

?>