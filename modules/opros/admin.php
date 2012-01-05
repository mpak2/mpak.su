<? die;

$conf['settings'] += array(
	"{$arg['modpath']}"=>"Опросы",
	"{$arg['modpath']}_anket"=>"Анкеты",
	"{$arg['modpath']}_result"=>"Результаты",
	"{$arg['modpath']}_variant"=>"Варианты",
	"{$arg['modpath']}_vopros"=>"Вопросы",
);

foreach(mpql(mpqw($sql = "SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

if ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
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
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'table' => "<table cellspacing='0' cellpadding='3' border='1'>",
//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => array('name'=>'Название', 'sort'=>'Сортировка', 'vopros'=>'Вопросов', 'description'=>'Описание'), # Название полей
			'type' => array('sort'=>'sort', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'name'=>array('*'=>'<a href=/?m[opros]=admin&r=1&where[oid]={f:id}>{f:{f}}</a>'),
//				'count'=>spisok("SELECT o.id, COUNT(*) AS count FROM {$conf['db']['prefix']}{$arg['modpath']} AS o, {$conf['db']['prefix']}{$arg['modpath']}_vopros AS v WHERE o.id=v.oid GROUP BY o.id"),
				($fn = 'vopros')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[oid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[oid]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.oid GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
//			'spisok' => array( # Список для отображения и редактирования
//				'type' => array('*'=> array('checkbox'=>'Много', 'radio'=>'Один', 'text'=>'Текст', 'textarea'=>'Поле')),
//				'sort'=>array('*' => range(0, 100)),
//				'parent' => array('*' => array(0=>'..') + (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}news_kat")),
//				'time' => $time,
//			),
//			'default' => array('vid'=>array('*'=>$_GET['where']['vid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_vopros"){
	if (!isset($_GET['order'])) $_GET['order'] = 'sort';
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
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

			'title' => array('oid'=>'Опрос', 'vopros'=>'Вопрос', 'variant'=>'Вариант', 'type'=>'Тип', 'sort'=>'Сорт'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
//				'vopros'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&r=".array_search('Варианты', $m)."&where[vid]={f:id}'>{f:{f}}</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
				($fn = 'variant')=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[vid]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[vid]=', r.id, '>', COUNT(*), '_{$fn}</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}_{$fn} AS fn WHERE r.id=fn.vid GROUP BY r.id"),
			),
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'type' => array('*'=> array('radio'=>'Выбор', 'select'=>'Список', 'text'=>'Текст', 'textarea'=>'Поле')),
				'oid' => array('*' => spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}", 30)),
//				'time' => $time,
			),
			'default' => array('oid'=>array('*'=>max($_GET['where']['oid'], $_POST['oid']))), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_variant"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => 'sort', # Сортировка вывода таблицы
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

//			'title' => array('vid'=>'Вопрос', 'variant'=>'Вариант', 'sort'=>'Сорт'), # Название полей
			'type' => array('sort'=>'sort'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
			'set' => $_GET['where']['vid'] ? array('vid'=>$_GET['where']['vid']) : null, # Значение которое всегда будет присвоено полю. Исключает любое изменение
//			'shablon' => array('vopros'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&r=".array_search('Варианты', $m)."&where[vid]={f:id}'>{f:{f}}</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
			'disable' => $_GET['where']['vid'] ? array('vid') : null, # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
				'vid' => array('*' => (array)spisok("SELECT id, vopros FROM {$conf['db']['prefix']}{$arg['modpath']}_vopros", 30)),
//				'sort'=>array('*' => range(0, 100)),
			),
			'default' => array('vid'=>array('*'=>$_GET['where']['vid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_anket"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
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
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'edit'=>'list',
			'title' => array('num'=>'Номер', 'time'=>'Время', 'oid'=>'Анкета', 'sid'=>'Сессия', 'uid'=>'Пользователь', 'status'=>'Статус', 'view'=>'Просмотр'), # Название полей
			'etitle'=>array('time'=>'Время', 'oid'=>'Заявка', 'sid'=>'Номер сессии', 'uid'=>'Пользователь', 'status'=>'Статус'),
			'type' => array('time'=>'timestamp'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => $_GET['where']['vid'] ? array('vid'=>$_GET['where']['vid']) : null, # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array(
				'num'=>array('*'=>'#{f:id}'),
				'view'=>array('*'=>"<a href=/{$arg['modpath']}/{f:oid}/aid:{f:id}>Смотреть</a>"),
//				'vopros'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&r=".array_search('Варианты', $m)."&where[vid]={f:id}'>{f:{f}}</a>")
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => $_GET['where']['vid'] ? array('vid') : null, # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
 			'spisok' => array( # Список для отображения и редактирования
 				'uid'=>array('*' => (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}users", 30)),
				'oid'=>array('*' => (array)spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}")),
				'status'=>array('*'=>array('0'=>'новый', '1'=>'обработка', '2'=>'отменен', '3'=>'выполнен')),
 			),
// 			'default' => array('vid'=>array('*'=>$_GET['where']['vid'])), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
 		)
	);
}elseif ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_result"){
	stable(
		array(
			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
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
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

//			'title' => array('uchastnik'=>'Участник'), # Название полей
//			'type' => array('img'=>'file', 'description'=>'textarea'), # Тип полей
//			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('name'=>'kanal'), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => array('sess'=>array('*'=>"<a href='?m[{$arg['modpath']}]=admin&r=".array_search('Результат', $m)."&where[sess]={f:{f}}'>{f:{f}}</a>")), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('img'), # Выключенные для записи поля
//			'hidden' => array(), # Скрытые поля
			'spisok' => array( # Список для отображения и редактирования
//				'uchastnik' => array('*' => (array)spisok("SELECT id, vopros FROM {$conf['db']['prefix']}{$arg['modpath']}_result", 30)),
				'vid' => array('*' => (array)spisok("SELECT id, vopros FROM {$conf['db']['prefix']}{$arg['modpath']}_vopros", 30)),
//				'vtid' => array('*' => (array)spisok("SELECT id, variant FROM {$conf['db']['prefix']}{$arg['modpath']}_variant", 30)),
//				'value' => array('*' => (array)spisok("SELECT id, variant FROM {$conf['db']['prefix']}{$arg['modpath']}_variant", 30)),
			),
			'default' => array('parent'=>$_POST['parent']), # Значение полей по умолчанию
//			'maxsize' => array('bdesc'=>'50', 'sdesc'=>'50'), # Максимальное количество символов в поле
		)
	);
}

?>