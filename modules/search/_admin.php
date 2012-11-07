<? die;

mpmenu();

stable(
	array(
//		'dbconn' => $conf['db']['conn'],
		'url' => "/?m[{$arg['modpath']}]=admin", # Ссылка для редактирования
		'name' => "{$conf['db']['prefix']}{$arg['modpath']}_index", # Имя таблицы базы данных
//		'where' => '', # Условия отбора содержимого
		'order' => 'time DESC', # Сортировка вывода таблицы
//		'debug' => false, # Вывод всех SQL запросов
		'acess' => array( # Разрешение записи на таблицу
			'add' => array('*'=>true), # Добавление
			'edit' => array('*'=>true), # Редактирование
			'del' => array('*'=>true), # Удаление
			'cp' => array('*'=>true), # Копирование
		),
//		'count_rows' => 12, # Количество записей в таблице
//		'page_links' => 10, # Количество ссылок на страницы в обе стороны

		'edit'=>'list',
//		'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//		'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//		'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//		'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

		'title' => array('uid'=>'Пользователь', 'time'=>'Время', 'num'=>'Блок', 'search'=>'Запрос', 'count'=>'Просм', 'pages'=>'Стр', 'ip'=>'IP Адрес'), # Название полей
		'type' => array('time'=>'timestamp', 'description'=>'textarea'), # Тип полей
//		'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//		'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
		'shablon' => array(
			'ip'=>array('*'=>"<a target=\"_blank\" href=\"http://geotool.servehttp.com/?ip={f:{f}}\">{f:{f}}</a>"),
			'search'=>array('*'=>"<a href='/?m[search]&search_block_num={f:num}&search={f:search}' target='_blank'>{f:search}</a>"),
		), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//		'disable' => array('img'), # Выключенные для записи поля
//		'hidden' => array(), # Скрытые поля
		'spisok' => array( # Список для отображения и редактирования
			'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
			'num'=>array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}blocks")),
		),
//		'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//		'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
	)
);

?>