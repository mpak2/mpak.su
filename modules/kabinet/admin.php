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

//if ($m[(int)$_GET['r']] == 'Модули'){

	mpmenu();

	if ($_GET['f']){
		$table_name = "{$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}";
		list($k, $v) = each($_GET['f']);
		mpfile(mpql(mpqw("SELECT ".addslashes($k)." FROM $table_name WHERE id=".(int)$v), 0, $k));
	}

	stable(
		array(
			'dbconn' => $GLOBALS['conf']['db']['conn'],
			'url' => "?m[kabinet]=admin", //&r={$_GET['r']}", # Ссылка для редактирования
			'name' => "{$GLOBALS['conf']['db']['prefix']}kabinet", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('img'=>'Изображение', 'name'=>'Название', 'link'=>'Ссылка', 'description'=>'Описание'), # Название полей
			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'link'=>array('*'=>"<a href='{f:{f}}' target='_blank'>{f:{f}}</a>"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('folder', 'description'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled', 'access'), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'admin' => spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}admin"),
//				'time' => $time,
//			),
//			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
//}

?>