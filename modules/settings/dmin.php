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

	mp_require_once("include/func.php"); # Функции таблиц
	mpmenu();

	foreach(mpreaddir('themes', 1) as $pathname) $values['theme'][$pathname] = $pathname;
//	$values['theme'] += spisok("SELECT id, name FROM {$GLOBALS['conf']['db']['prefix']}themes WHERE type='text/html'");

	$values['admin_grp'] = $values['$user_grp'] = $values['default_grp'] = spisok("SELECT name, name FROM {$conf['db']['prefix']}users_grp");
//	$values['admin_usr'] = $values['default_usr'] = spisok("SELECT name, name FROM {$conf['db']['prefix']}users");
	$values['del_sess'] = array('0'=>'Не сохранять', '1'=>'Сохранять все', '2'=>'Только рабочие сессии', '3'=>'Только метод POST');
	$values['microtime'] = array(''=>'Не отображать', '1'=>'Отображать');
	$values['block_edit'] = array('0'=>'Запрещено', '1'=>'Разрешено');

	foreach(mpql(mpqw("SELECT id, name, value FROM {$conf['db']['prefix']}settings")) as $k=>$v){
		if (isset($values[ $v['name'] ])){
			$spisok[$v['id']] = $values[ $v['name'] ];
			if ($values[ $v['name'] ][ $v['value'] ] ) $shablon[$v['id']] = $values[ $v['name'] ][ $v['value'] ];
		}
	}

	$conf['settings'][ $_POST['name'] ] = $_POST['value'];
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[settings]=admin", # Ссылка для редактирования
			'name' => "{$conf['db']['prefix']}settings", # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
//			'order' => 'id', # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
//			'acess' => $access,
			'count_rows' => 100, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('modpath'=>'Модуль', 'name'=>'Параметр', 'value'=>'Значение', 'aid'=>'Доступ', 'description'=>'Описание'), # Название полей
			'type' => array('value'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'value'=>$shablon,
				'name'=>array('*'=>"<div title='&#60;!--&nbsp;[settings:{f:{f}}]&nbsp;--&#62;'><a href=\"/?m[{$arg['modpath']}]=admin&where[name]={f:{f}}\">{f:{f}}</a></div>"),
//				'name'=>array('*'=>"&#60;!--&nbsp;[blocks:{f:{f}}]&nbsp;--&#62;"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('name'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array(
				'aid'=>array('*' => $conf['settings']['access_array']),
				'modpath'=>array('*'=>array(''=>'*')+spisok("SELECT folder, name FROM mp_modules")),
			)+array('value'=>$spisok),
			//array( # Список для отображения и редактирования
//				'value' => ,
//				'time' => $time,
//			),
			'default' => array(
				'aid'=>array("*"=>4),
				'modpath'=>array('*'=>$_GET['where']['modpath'])
			), # Значение полей по умолчанию
			'maxsize' => array('value'=>'50'), # Максимальное количество символов в поле
		)
	);

?>
