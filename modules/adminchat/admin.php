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

mpmenu();

stable(
	array(
		'dbconn' => $GLOBALS['conf']['db']['conn'],
		'url' => "?m[admin_chat]=admin", # Ссылка для редактирования
		'name' => "{$GLOBALS['conf']['db']['prefix']}admin_chat", # Имя таблицы базы данных
//		'where' => '', # Условия отбора содержимого
//		'order' => 'id', # Сортировка вывода таблицы
//		'debug' => false, # Вывод всех SQL запросов
		'acess' => array( # Разрешение записи на таблицу
			'add' => array('*'=>true), # Добавление
			'edit' => array('*'=>true), # Редактирование
			'del' => array('*'=>true), # Удаление
			'cp' => array('*'=>true), # Копирование
		),
//		'count_rows' => 12, # Количество записей в таблице
//		'page_links' => 10, # Количество ссылок на страницы в обе стороны

//		'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//		'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//		'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//		'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

		'title' => array('time'=>'Время', 'user'=>'Пользователь', 'text'=>'Текст'), # Название полей
		'type' => array('text'=>'textarea', 'time'=>'timestamp'), # Тип полей
//		'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//		'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
//		'shablon' => array('name'=>"<a href='?m[news]=admin&where[kid]={f:id}'>{f:{f}}</a>"), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//		'disable' => array('img'), # Выключенные для записи поля
//		'hidden' => array(), # Скрытые поля
//		'spisok' => array( # Список для отображения и редактирования
//			'parent' => array('*' => array(0=>'..') + spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}news_kat"), 20),
//		),
//		'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//		'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
	)
);


?>