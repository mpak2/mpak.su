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
// Original Author of file: Krivoshlykov Evgeniy (mpak) +7 9291140042
// Purpose of file:
// ----------------------------------------------------------------------

$conf['settings'] += array(
	"{$arg['modpath']}_index"=>$conf['modules'][ $arg['modname'] ]['name'],
	"{$arg['modpath']}_cat"=>"Категории",
);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$val = ($conf['settings'][$fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen($conf['db']['prefix']))] ?: $fn);
	$m["{$conf['db']['prefix']}". $fn] = $val;
} mpmenu($m); if(!$_GET['r']) $_GET['r'] = array_shift(array_keys($m));

$tn = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"), strlen($_GET['r']));
$etitle = $shablon = $spisok = array();
if(!empty($conf['settings'][ $s = $arg['modpath']. "=>spisok" ]) && ($fn = explode(",", $conf['settings'][ $s ]))){
	foreach($fn as $f){# Загрузка всех справочников
		$spisok += array("{$f}_id" => array("*"=>array("")+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$f} ORDER BY name")));
	}
} foreach($m as $table=>$v){
	$columns = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($table). ""), 'Field');
	foreach($columns as $f=>$fields){
		if((substr($f, -3, 3) == "_id") && (substr($f, 0, -3) == $tn)){
			$fn = substr($table, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"), strlen($table));
			$etitle += array($fn=>$conf['settings'][ "{$arg['modpath']}_{$fn}" ]);
			$shablon[ $fn ] = array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id");
		}
	}
}// mpre($shablon);

if(/*true ||*/ $_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_comments"){// echo "<div style=float:right;color:#bbb;>{$_GET['r']}:". __LINE__. "</div>";
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => ($conf['settings']["{$arg['modname']}_{$tn}=>order"] ?: 'id DESC'), # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'edit'=>'title',
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => (!empty($conf['settings']["{$arg['modpath']}_{$tn}=>title"]) ? explode(",", $conf['settings']["{$arg['modpath']}_{$tn}=>title"]) : null), # Название полей
			'etitle'=> array('dismissed'=>'timestamp', 'time'=>'Время', 'uid'=>'Пользователь', 'count'=>'Количество', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'sum'=>'Сумма', 'name'=>'Название', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'up'=>'Обновление', 'description'=>'Описание', 'text'=>'Текст') + $etitle,
			'type' => array('time'=>'timestamp', 'up'=>'timestamp', 'dismissed'=>'Уволен', 'sort'=>'sort', 'img'=>'file', 'duration'=>'timecount', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => $shablon + array(
//				($fn = "spisok"). "_id" => (array("*"=>"<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}\">{spisok:{f}}</a>")),
//				'num'=>array('*'=>'<a target=_blank href=http://stom-firms.ru/clinics.php?i={f:{f}}>http://stom-firms.ru/clinics.php?i={f:{f}}</a>'),
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
				($fn = 'img')=>array('*'=>"<img src='/{$arg['modpath']}:{$fn}/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/w:120/h:100/null/img.jpg' title='{f:{f}}' alt='{f:{f}}'>"),
				($fn = 'file')=>array('*'=>"<a href='/{$arg['modpath']}:{$fn}/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/null/{f:name}' title='{f:{f}}' alt='{f:{f}}'>{f:{f}}</a>"),
//				($fn = "link"). "_id"=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}'>{f:{f}}</a>"),
//				(($fn = 'cnt'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id"),
//				(($tn = "people"). ($fn = '_index_performers'). ($prx = ''))=>array('*'=>"<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id"). "]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$tn}{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$tn}{$fn} AS fn WHERE r.id=fn.". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id")." GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => $spisok += array( # Список для отображения и редактирования
				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
/*				($fn = "works"). "_id" => array("*"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
				($fn = "tasks_status"). "_id" => array("*"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
				($fn = "projects"). "_id" => array("*"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),*/
//				(($tn = "users"). $fn = "_sity") => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$tn}{$fn}")),
//				'metro_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_metro")),
//				'kuzov_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type")),
//				'zagruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_zagruzka_type")),
//				'vygruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vygruzka_type")),
				'hide'=>array('*'=>array(0=>'Доступно', 1=>'Скрыто')),
			),
			'default' => array(
				'uid'=>array('*'=>$conf['user']['uid']),
				'time'=>array('*'=>date('Y.m.d H:i:s')),
//				($f = 'type_id')=>array('*'=>max($_GET['where'][$f], $_POST[$f])),
			), # Значение полей по умолчанию
			'maxsize' => array('description'=>150, 'text'=>250), # Максимальное количество символов в поле
		)
	);
}else if(true || $_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_index"){ echo "<div style=float:right;color:#bbb;>{$_GET['r']}:". __LINE__. "</div>";
	stable(
		array(
//			'dbconn' => $conf['db']['conn'],
			'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
			'name' => $_GET['r'], # Имя таблицы базы данных
//			'where' => '', # Условия отбора содержимого
			'order' => ($conf['settings']["{$arg['modname']}_{$tn}=>order"] ?: 'id DESC'), # Сортировка вывода таблицы
//			'debug' => false, # Вывод всех SQL запросов
			'acess' => array( # Разрешение записи на таблицу
				'add' => array('*'=>true), # Добавление
				'edit' => array('*'=>true), # Редактирование
				'del' => array('*'=>true), # Удаление
				'cp' => array('*'=>true), # Копирование
			),
			'edit'=>'title',
//			'count_rows' => 12, # Количество записей в таблице
//			'page_links' => 10, # Количество ссылок на страницы в обе стороны

//			'top' => array('tr'=>'<tr>', 'td'=>'<td>', 'result'=>'<b><center>{result}</center></b>'), # Формат заголовка таблицы
//			'middle' => array('tr'=>'<tr>', 'td'=>'<td>', 'shablon'=>"<tr><td>{sql:name}</td><td>&nbsp;{sql:img}</td><td>&nbsp;{sql:description}</td><td align='right'>{config:row-edit}</td></tr>"), # Формат записей таблицы
//			'bottom' => array('tr'=>'<tr>', 'td'=>"<td valign='top'>", 'shablon'=>'<tr><td>{config:url}</td></tr>'), # Формат записей таблицы

			'title' => (!empty($conf['settings']["{$arg['modpath']}_{$tn}=>title"]) ? explode(",", $conf['settings']["{$arg['modpath']}_{$tn}=>title"]) : null), # Название полей
			'etitle'=> array('dismissed'=>'timestamp', 'time'=>'Время', 'uid'=>'Пользователь', 'count'=>'Количество', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'sum'=>'Сумма', 'name'=>'Название', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', 'price'=>'Цена', "premium"=>"Наценка", 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'up'=>'Обновление', 'description'=>'Описание', 'hide'=>'Скрыт', 'text'=>'Текст') + $etitle,
			'type' => array('time'=>'timestamp', 'up'=>'timestamp', 'dismissed'=>'Уволен', 'sort'=>'sort', 'img'=>'file', 'duration'=>'timecount', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
			'ext' => array('img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')),
//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
			'shablon' => $shablon + array(
//				($fn = "spisok"). "_id" => (array("*"=>"<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}\">{spisok:{f}}</a>")),
//				'num'=>array('*'=>'<a target=_blank href=http://stom-firms.ru/clinics.php?i={f:{f}}>http://stom-firms.ru/clinics.php?i={f:{f}}</a>'),
//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
				($fn = 'img')=>array('*'=>"<img src='/{$arg['modpath']}:{$fn}/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/w:120/h:100/null/img.jpg' title='{f:{f}}' alt='{f:{f}}'>"),
//				($fn = 'file')=>array('*'=>"<a href='/{$arg['modpath']}:{$fn}/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/null/{f:name}' title='{f:{f}}' alt='{f:{f}}'>{f:{f}}</a>"),
//				($fn = "link"). "_id"=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}'>{f:{f}}</a>"),
//				(($fn = 'cnt'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id"),
//				(($tn = "people"). ($fn = '_index_performers'). ($prx = ''))=>array('*'=>"<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id"). "]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$tn}{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$tn}{$fn} AS fn WHERE r.id=fn.". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id")." GROUP BY r.id"),
			), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
//			'disable' => array('orderby'), # Выключенные для записи поля
//			'hidden' => array('name', 'enabled'), # Скрытые поля
			'spisok' => $spisok += array( # Список для отображения и редактирования
				'uid' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
/*				($fn = "works"). "_id" => array("*"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
				($fn = "tasks_status"). "_id" => array("*"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
				($fn = "projects"). "_id" => array("*"=>array("")+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
				($fn = "workers"). "_id" => array("*"=>array("")+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),*/
//				(($tn = "users"). $fn = "_sity") => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$tn}{$fn}")),
//				'metro_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_metro")),
//				'kuzov_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type")),
//				'zagruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_zagruzka_type")),
//				'vygruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vygruzka_type")),
				'hide'=>array('*'=>array(0=>'Доступно', 1=>'Скрыто')),
			),
			'default' => array(
				'uid'=>array('*'=>$conf['user']['uid']),
				'time'=>array('*'=>date('Y.m.d H:i:s')),
//				($f = 'type_id')=>array('*'=>max($_GET['where'][$f], $_POST[$f])),
			), # Значение полей по умолчанию
			'maxsize' => array('description'=>150, 'text'=>250), # Максимальное количество символов в поле
		)
	);
}

?>