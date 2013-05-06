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
		$spisok += array("{$f}_id" => array("*"=>array("")+spisok("SELECT id, CONCAT('<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$f}&where[id]=', id, '\">', CONVERT(`name` USING UTF8), '</a>') FROM {$conf['db']['prefix']}{$arg['modpath']}_{$f}")));
	}
} if(!empty($conf['settings'][ $s = $arg['modpath']. "=>espisok" ]) && ($fn = explode(",", $conf['settings'][ $s ]))){
	foreach($fn as $v){
		$spisok += array(
			(($t = array_shift(explode("_", $v))). $fn = "_". implode("_", array_slice(explode("_", $v), 1))) => array('*'=>array("")+spisok("SELECT id, CONCAT('<a href=\"/?m[{$t}]=admin&r={$conf['db']['prefix']}{$t}{$fn}&where[id]=', id, '\">', CONCAT('<span style=color:blue;>#', id, '</span>'), '</a>&nbsp;', CONVERT(`name` USING UTF8)) AS name FROM {$conf['db']['prefix']}{$t}{$fn}")),
		);
		if($conf['settings'][ $v ]){
			$etitle[ $v ] = $conf['settings'][ $v ];
		}else if(implode("_", array_slice(explode("_", $v), 1)) == "index"){
			$etitle[ $v ] = $conf['modules'][ array_shift(explode("_", $v)) ]["name"];
		}
	}
} foreach($m as $table=>$v){
	$columns = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($table). ""), 'Field');
	if(!empty($columns['sort']) && empty($conf['settings'][ "{$arg['modpath']}_{$tn}=>order" ])){
		$conf['settings'][ "{$arg['modpath']}_{$tn}=>order" ] = "sort";
	} foreach($columns as $f=>$fields){
		if((substr($f, -3, 3) == "_id") && (substr($f, 0, -3) == $tn)){
			$fn = substr($table, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"), strlen($table));
			$etitle += array($fn=>$conf['settings'][ "{$arg['modpath']}_{$fn}" ]);
			$shablon[ $fn ] = array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). "&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). "&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '". ($fn ? "_" : ""). ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id");
		}
	}
} if($et = $conf['settings']["{$arg['modpath']}=>etitle"]){ # Названия полей из админнастроек раздела
	foreach(explode(",", $et) as $v){
		$ex = explode(":", $v);
		$etitle[array_shift($ex)] = array_pop($ex);
	}
}// mpre($title);

if(true || $_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_index"){ echo "<div style=float:right;color:#bbb;><a href=\"/?m[sqlanaliz]=admin&r=1&tab={$_GET['r']}\">{$_GET['r']}</a>:". __LINE__. "</div>";
	if($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_index"){
/*		$shablon += array(
			"name"=>array("*"=>"<a href=\"/{$arg['modname']}/{f:id}\">{f:{f}}</a>"),
			($fn = 'img2')=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/w:120/h:100/null/img.jpg' title='{f:{f}}' alt='{f:{f}}'>"),
		);
	}else if($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}_". ($t = "staff")){
		$shablon += array(
			"name" => spisok("SELECT o.id, od.name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t} AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_{$t}_data AS od ON (o.id=od.{$t}_id AND {$t}_fields_id=1)")
		);*/
	}  $t = implode("_", array_slice(explode("_", $_GET['r']), 1));
	if(qn("SHOW TABLES LIKE '{$_GET['r']}'")){
		stable(
			array(
	//			'dbconn' => $conf['db']['conn'],
				'url' => "/?m[{$arg['modpath']}]=admin&r={$_GET['r']}", # Ссылка для редактирования
				'name' => $_GET['r'], # Имя таблицы базы данных
				'where' => (empty($_GET['where']) && $conf['settings']["{$t}=>where_empty"] ? $conf['settings']["{$t}=>where_empty"] : $conf['settings']["{$t}=>where"]), # Условия отбора содержимого
				'order' => ($conf['settings'][substr($_GET['r'], strlen($conf['db']['prefix'])). "=>order"] ?: 'id DESC'), # Сортировка вывода таблицы
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
				'etitle'=> array('time'=>'Время', 'up'=>'Обновление', 'uid'=>'Пользователь', 'count'=>'Количество', 'level'=>'Уровень', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'img2'=>'Изображение2', 'hide'=>'Видим', 'sum'=>'Сумма', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'name'=>'Название', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'log'=>'Лог', 'max'=>'Макс', 'min'=>'Мин', 'own'=>'Владелец', 'period'=>'Период', "from"=>"С", "to"=>"До", 'description'=>'Описание', 'text'=>'Текст') + $etitle,
				'type' => array('time'=>'timestamp', 'up'=>'timestamp', 'period'=>"timecount", 'sort'=>'sort', 'img'=>'file', 'img2'=>'file', 'duration'=>'timecount', 'description'=>'textarea', 'text'=>'wysiwyg'), # Тип полей
				'ext' => array(
					'img'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
					'img2'=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
				),
	//			'set' => array('orderby'=>$orderby), # Значение которое всегда будет присвоено полю. Исключает любое изменение
				'shablon' => $shablon + array(
	//				($fn = "spisok"). "_id" => (array("*"=>"<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}\">{spisok:{f}}</a>")),
	//				'num'=>array('*'=>'<a target=_blank href=http://stom-firms.ru/clinics.php?i={f:{f}}>http://stom-firms.ru/clinics.php?i={f:{f}}</a>'),
	//				'name'=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[rid]={f:id}>{f:{f}}</a>"),
					($fn = 'img')=>array('*'=>"<img src='/{$arg['modpath']}:img/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/w:120/h:100/null/img.jpg' title='{f:{f}}' alt='{f:{f}}'>"),
	//				($fn = 'file')=>array('*'=>"<a href='/{$arg['modpath']}:{$fn}/{f:id}/tn:". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "/fn:{$fn}/null/{f:tmp_name}' title='{f:{f}}' alt='{f:{f}}'>{f:{f}}</a>"),
	//				($fn = "link"). "_id"=>array('*'=>"<a href='/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[id]={f:{f}}'>{f:{f}}</a>"),
	//				(($fn = 'cnt'). ($prx = ''))=>array('*'=>"<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$fn}&where[". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$arg['modpath']}_{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$arg['modpath']}". ($fn ? "_{$fn}" : ""). " AS fn WHERE r.id=fn.". (substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))). "{$prx}_id GROUP BY r.id"),
	//				(($tn = "people"). ($fn = '_index'). ($prx = ''))=>array('*'=>"<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). "_id]={f:id}>Нет</a>")+spisok("SELECT r.id, CONCAT('<a href=/?m[{$tn}]=admin&r={$conf['db']['prefix']}{$tn}{$fn}&where[". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id"). "]=', r.id, '>', COUNT(*), '_". ($conf['settings']["{$tn}{$fn}"] ?: $fn). "</a>') FROM {$_GET['r']} AS r, {$conf['db']['prefix']}{$tn}{$fn} AS fn WHERE r.id=fn.". ($tn ? $arg['modpath']. "_" : ""). substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")). ($tn ? "" : "_id")." GROUP BY r.id"),
				), # Шаблон вывода в замене участвуют только поля запроса имеен приоритет перед полем set
	//			'disable' => array('orderby'), # Выключенные для записи поля
	//			'hidden' => array('name', 'enabled'), # Скрытые поля
				'spisok' => $spisok += array( # Список для отображения и редактирования
					'uid' => array('*'=>array("")+spisok("SELECT id, name FROM {$conf['db']['prefix']}users")),
	//				($fn = "staff")=>array("*"=>array("")+spisok("SELECT id,name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}")),
	//				($fn = "data_id")=>array("*"=>array("")+spisok("SELECT s.id ,d.name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($fn, 0, -3). " AS s LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_". substr($fn, 0, -3). "_data AS d ON (s.id=d.{$fn} AND d.". substr($fn, 0, -3). "_fields_id=1)")),
	//				(($tn = "users"). $fn = "_sity") => array('*'=>array("")+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$tn}{$fn}")),
	//				'metro_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_metro")),
	//				'kuzov_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type")),
	//				'zagruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_zagruzka_type")),
	//				'vygruzka_type_id' => array('*'=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_vygruzka_type")),
					'hide'=>array('*'=>array(1=>'Скрыто', 0=>'Доступно')),
				),
				'default' => array(
					'uid'=>array('*'=>$conf['user']['uid']),
					'time'=>array('*'=>date('Y.m.d H:i:s')),
	//				($f = 'type_id')=>array('*'=>max($_GET['where'][$f], $_POST[$f])),
				), # Значение полей по умолчанию
				'maxsize' => array('description'=>150, 'text'=>250), # Максимальное количество символов в поле
			)
		);
	}else{
		echo <<<EOF
	<script>
		$(function(){
			if(confirm("Таблицы '{$_GET['r']}' не существует создать?")){
				$.get("/?m[sqlanaliz]=admin&r=1&new={$_GET['r']}", function(){
					document.location.reload(true);
				});
			}else{ document.location.href = "/?m[{$arg['modpath']}]=admin";}
		});
	</script>
EOF;
	}
}

?>