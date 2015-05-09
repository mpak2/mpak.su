<?
// ----------------------------------------------------------------------
// -mpak Content Management System
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

function stable($table){
	global $conf, $arg;
	if (!isset($_GET['order'])) $_GET['order'] = $table['order'];
	if (!isset($table['dbconn'])){
		global $dbconn;
		$table['dbconn'] = $dbconn;
	}
	$modname = basename(dirname(__FILE__));
	if (gettype($table['dbconn']) == 'object')
		$table['dbconn']->SetFetchMode(2);
	$hidden = array_flip( array_merge( array('id'), (array)$table['hidden'] ) ); # Меняем местами ключ=>значение и добавляем поле id
	$disable = array_flip( array_merge( array('id'), (array)$table['disable'] ) ); # Меняем местами ключ=>значение и добавляем поле id
	if (!strlen($table['name'])){
		echo "<p><font color=red>Не задано имя таблицы</font>";
		return;
	}

	# Создаем массив в $tables с описанием полей

	$table['_fields'] = array_column($table['full_fields'] = qn("SHOW FULL COLUMNS FROM {$table['name']}", "Field"), "Field", "Field");

/*	$sql = "SHOW FULL COLUMNS FROM {$table['name']}";
	if ($table['debug']) mpre($sql);
	$result = mpqw($sql);
	while($line = mysql_fetch_array($result, 1)){
		$table['_fields'][$line['Field']] = $line['Field'];
	} mpre($table['_fields']);*/

	foreach($table['_fields'] as $k=>$v){ # Отображаем возле названия поля язык
		$ar = explode("_", $k);
		$num = array_pop($ar);
		if(is_numeric($num)){
			if(empty($table['etitle'][ $k ])){
				if(empty($lang)) $lang = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}users_lang"));
				$table['etitle'][ $k ] = $table['etitle'][ implode("_", $ar) ]. "(". $lang[ $num ]['name']. ")";
				if($table['type'][ implode("_", $ar) ]){
					$table['type'][ $k ] = $table['type'][ implode("_", $ar) ];
				}
			}
		}
	}// mpre($table['etitle']);

	$url = $where = '';
	foreach((array)$_GET['where'] as $k=>$v){
		$where .= "$k".(strlen($v) > 1 && $v{0} == '!' ? '<>' : '=')."'".(strlen($v) > 1 && $v{0} == '!' ? mpquot(substr($v, 1)) : mpquot($v))."' AND ";
		$url .= "&where[$k]=$v";
	}
	foreach((array)$_GET['where'] as $k=>$v){
		$table['default'][$k] = array('*'=>$v);
	}

	$where = substr($where, 0, strlen($where) - 5);
	$table['_where'] = $where;
	$table['url'] .= $url;

	if (!$table['count_rows']) $table['count_rows'] = 12; # Количество записей на страницу
	if (!$table['page_links']) $table['page_links'] = 10; # Количество ссылок на страницы

	$sql = "SELECT COUNT(*) as count FROM {$table['name']}";	# Определяем количество полей в таблице

	if (strlen($table['where']) && strlen($table['_where'])){ # Добавляем условия отбора
		$sql .= " WHERE ".mpquot($table['where'])." AND {$table['_where']}";
	}elseif (strlen($table['where']) || strlen($table['_where'])){
		$sql .= " WHERE ".mpquot($table['where'])."{$table['_where']}";
	}// echo $sql;
	if ($table['debug']) mpre($sql);
	$result = mpqw($sql);
	$line = mysql_fetch_array($result, 1);
	$table['_count'] = $line['count'];

	if ($table['acess']){ # Разрешено редактирование таблицы
		if ($table['acess']['edit'] && isset($_GET['edit'])){ # Вывод редактируемых значений.
			$sql = "SELECT * FROM {$table['name']} WHERE `id` = '".(int)$_GET['edit']."'";
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) mpre($sql);
			$result = mpqw($sql);
			$edit = $_GET['edit'] ? mysql_fetch_array($result, 1) : true;
			foreach((array)$table['type'] as $k=>$v){
				if ($v == 'timestamp') @$edit[$k] = ($edit[$k] ? date('Y.m.d H:i:s', $edit[$k]) : "");
			}
		}elseif ($table['acess']['cp']['*'] && $_GET['cp']){ # Вывод копируемых значений.
			$sql = "SELECT * FROM {$table['name']} WHERE `id` = '".(int)$_GET['cp']."'";
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) mpre($sql);
			$result = mpqw($sql);
			$edit = mysql_fetch_array($result, 1);
			foreach((array)$table['type'] as $k=>$v)
				if ($v == 'timestamp') $edit[$k] = date('Y.m.d H:i:s', $edit[$k]);
		}elseif ($table['acess']['edit']['*'] && (int)$_POST['id']){
			loadfile($table, $_POST['id']); # Загружаем изображениe
			$sql = "UPDATE {$table['name']} SET";
			# Добавляем редактируемые поля
			foreach($table['_fields'] as $k=>$v){
				if ($table['type'][$k] == 'timestamp' && strlen($_POST[$k])){
					$dt = explode(' ', $_POST[$k]); $t = explode(':', $dt[1]); $d = explode('.', $dt[0]);
					$_POST[$k] = mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);
				}
				if (isset($_POST[$k]) && !isset($hidden[$k]) && !isset($disable[$k])  && !isset($table['set'][$k]))
					$sql .= " `$k` = '".mpquot($_POST[$k])."',";
				foreach((array)$table['set'] as $k=>$v)
					$sql .= " `$k` = '".mpquot($table['set'][$k])."',";
			}
			$sql = substr($sql, 0, strlen($sql) - 1)." WHERE `id` = '".(int)$_POST['id']."'";
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) mpre($sql);
			mpevent("Редактирование через админстраницу", $sql);
			mpqw($sql);
			if(!mysql_error())
				header("Location: {$_SERVER['REQUEST_URI']}");
		}elseif($table['acess']['del']['*'] && (int)$_GET['delfile'] && strlen($_GET['fil']) && !isset($hidden[$_GET['fil']]) && !isset($disable[$_GET['fil']]) && !isset($table['set'][$_GET['fil']])){ # Если стираем файл поле видимо, доступно для записи и не имеет значения для записи
			$sql = "SELECT {$_GET['fil']} FROM {$table['name']} WHERE id = '".(int)$_GET['delfile']."'";
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) echo mpre($sql);
			$result = mpqw($sql);
			if (mysql_num_rows($result)){
				$line = mysql_fetch_array($result, 1);
				unlink(strtr(mpopendir('include')."/{$line[$_GET['fil']]}", array('/..'=>'')));
				$sql = "UPDATE {$table['name']} SET {$_GET['fil']} = '' WHERE `id` = '".(int)$_GET['delfile']."'";
				if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
				if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
				if ($table['debug']) mpre($sql);
				mpqw($sql);
			}
		}elseif ($table['acess']['add']['*'] && isset($_POST['add'])){
			$fields = $value = '(';
			foreach($table['_fields'] as $k=>$v){
				if ($table['type'][$k] == 'timestamp' && strlen($_POST[$k])){ # Преобразование формата timestamp
					$dt = explode(' ', $_POST[$k]); $t = explode(':', $dt[1]); $d = explode('.', $dt[0]);
					$_POST[$k] = mktime ($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);
				}elseif($table['type'][$k] == 'sort'){
					$_POST[$k] = mpql(mpqw("SELECT MAX($k) + 1 as max FROM {$table['name']}"), 0, 'max');
				}elseif($table['type'][$k] == 'password'){
					$_POST[$k] = mpql(mpqw("SELECT PASSWORD('". mpquot($_POST[$k]). "') AS password"), 0, 'password');
				}
				if (isset($table['set'][$k])){ # Если есть $table['set'][$k] значение для записи
					$fields .= "`$k`, "; $value .= "'{$table['set'][$k]}', ";
				}elseif (isset($_POST[$k]) && !isset($hidden[$k]) && !isset($disable[$k])){ # Если нет $table['set'][$k]
					$fields .= "`$k`, "; $value .= "'".mpquot($_POST[$k])."', ";
				}
			}
			$sql = "INSERT INTO {$table['name']} ".substr($fields, 0, strlen($fields) - 2).") VALUE ".substr($value, 0, strlen($value) - 2).")";
			if ($table['debug']) mpre($sql);
			mpqw($sql);
			$table['_count']++;
			loadfile($table, mysql_insert_id()); # Загружаем изображения
			mpevent("Добавление через админстраницу", $sql);
			if(!mysql_error())
				header("Location: {$_SERVER['REQUEST_URI']}");
		}elseif ($table['acess']['del']['*'] && (int)$_GET['del']){ # Удаление записей
			$sql = "SELECT * FROM  {$table['name']} WHERE id = ".(int)$_GET['del'];
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) mpre($sql);
			$result = mpql(mpqw($sql), 0);
			if(!empty($result['img']) && /*array_key_exists('img', $result) &&*/ ($file_name = mpopendir("include/{$result['img']}"))){
				@mkdir("/tmp/del/", true);
				copy($file_name, "/tmp/del/". basename($file_name));
				unlink($file_name);
			}
/*			if ((gettype($table['dbconn']) == 'object' && $result->_numOfRows) || (gettype($table['dbconn']) == 'resource' && mysql_num_rows($result))){
mpre($_GET); exit;
				foreach(gettype($table['dbconn']) == 'object' ? $result->fields : mysql_fetch_array($result, 1) as $field=>$value){
					$file_name = strtr("include/$value", array('/..'=>''));
					if ($table['type'][$field] == 'file' && file_exists($file_name)){
						unlink($file_name);
					}
				}
			}*/
			$sql = "DELETE FROM {$table['name']} WHERE id = ".(int)$_GET['del'];
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			if ($table['debug']) mpre($sql);
			mpevent("Удаление через админстраницу", $sql);
			mpqw($sql);
			$table['_count']--;
		}elseif(count($_GET['inc']) || count($_GET['dec'])){
			$m = ($_GET['dec'] > $_GET['inc'] ? 'dec' : 'inc');
			list($k) = each($_GET[$m]);
			$sql = "SELECT id, $k FROM {$table['name']} WHERE $k ".($m == 'inc' ? '>=' : '<=')." (SELECT `$k` FROM {$table['name']} WHERE id=". (int)$_GET[$m][$k]. ")";
			if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
			if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
			$sql .= " ORDER BY $k".($m == 'dec' ? '  DESC' : '')." LIMIT 2";
			if ((((int)$_GET['inc'] || (int)$_GET['dec']) && count($res = mpql(mpqw($sql))) > 1)) {
				mpqw("UPDATE {$table['name']} SET $k = {$res['1'][$k]} WHERE id = ".(int)$res['0']['id']);
				mpqw("UPDATE {$table['name']} SET $k = {$res['0'][$k]} WHERE id = ".(int)$res['1']['id']);
			}
		}
	}

	if(empty($table['_fields']['img'])) unset($table['shablon']['img']);# Удаляем ссылку на изображение если его нет

	foreach($table['_fields'] as $_fields){ # Устанавливаем заголовки полей виде чтото_id берем названия из свойств
		if((substr($_fields, -3, 3) == "_id") && ($f = substr($_fields, 0, -3)) && !empty($conf['settings']["{$arg['modpath']}_{$f}"])){
			$table['etitle'][ $_fields ] = $conf['settings']["{$arg['modpath']}_{$f}"];
			if(substr($conf['settings'][ substr($_GET['r'], strlen($conf['db']['prefix']), 999) ], 0, 1) == '.'){
				if(empty($table['spisok'][ $_fields ])){
					$table['spisok'][ $_fields ] = array('*'=>array('')+spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$f} ORDER BY name"));
					$table['shablon'][ $_fields ] = array("*"=>"<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$f}&where[id]={f:{f}}\">{spisok:{f}}</a>");
				}
			}
		}
	}

	if(empty($table['title']) && array_key_exists("text", $table['_fields'])){
/*		$table['title'] = array();
		foreach(array_diff_key($table['_fields'], array("id"=>false, "text"=>false)) as $v){
			if(substr($v, -3) != "_id"){
				array_push($table['title'], $v);
			}
		}*/
		$table['title'] = array_values(array_diff_key($table['_fields'], array("id"=>false, "text"=>false)));
	} if(!empty($table['title'])){
		foreach($table['title'] as $k=>$v){
			if(is_numeric($k)){
				$table['title'][ $v ] = $table['etitle'][ $v ];
				unset($table['title'][ $k ]);
			}
		}
	}

	if($table['edit'] == "title" && $table['title']) $table['edit'] = "list";
	if($table['shablon'] && !array_key_exists('edit', $_GET)){
		$table['_fields'] += array_flip(array_keys($table['shablon']));
		if($table['title']){
//			$table['title'] += array_combine(array_keys($table['shablon']), array_keys($table['shablon']));
			$p0 = array_keys($table['shablon']);
			$p1 = array_keys(array_intersect_key((array)$table['etitle'], array_flip(array_keys($table['shablon']))))+$p0;//+array_keys($table['shablon']);
			$p2 = array_intersect_key((array)$table['etitle'], array_flip(array_keys($table['shablon'])))+array_combine($p0, $p0);//+array_keys($table['shablon']);
			if($p1 && $p2) $table['title'] = $table['title'] + array_combine($p1, $p2);
		}
	}
	if($table['etitle']){
		$table['etitle'] = array_intersect_key($table['etitle'] + array_combine(array_keys($table['_fields']), array_keys($table['_fields'])), $table['_fields']);
		$table['_fields'] = array_intersect_key($table['etitle'] + $table['_fields'], $table['_fields']);
	}

	# Делаем выборку из таблицы
	$sql = "SELECT * FROM {$table['name']}";
	if (strlen($table['where']) && strlen($table['_where'])){
		$sql .= " WHERE ".mpquot($table['where'])." AND {$table['_where']}";
	}elseif (strlen($table['where']) || strlen($table['_where'])){
		$sql .= " WHERE ".mpquot($table['where'])."{$table['_where']}";
	}
//	if (isset($table['_fields'][ strtr($_GET['order'], array(' DESC'=>'')) ]) && !isset($table['spisok'][ strtr($_GET['order'], array(' DESC'=>'')) ]['*'])) $sql .= " ORDER BY {$_GET['order']}";
	if (isset($table['_fields'][ strtr($_GET['order'], array(' DESC'=>'', ' desc'=>'')) ]) || !count(array_diff(explode(',', $_GET['order']), array_flip((array)$table['title'])))) $sql .= " ORDER BY {$_GET['order']}";
	$sql .= " LIMIT ".($_GET['p'] * $table['count_rows']).", {$table['count_rows']}";
	if ($table['debug']) mpre($sql);

	# Выводим ссылку на страницы
//	echo "<script src=include/openpopup.js></script><p>";

//	echo $table['_count']." ".$table['count_rows']." <br>";
	$tmp_row = 15;
	# Ссылка на первую страницу и предыдущий список страниц
	echo "<div style=\"clear:both;\"></div><div class=\"adpager\" style=\"margin:10px 30px;\">";
	if ($_GET['p'] > $tmp_row){
		echo "<a href='{$table['url']}{$table['_url']}".(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."'><<</a> .. ";
	}
		if ($table['_count'] > $table['count_rows']){
			for($page = max(0, min((int)$_GET['p']-$tmp_row, (int)($table['_count']/$table['count_rows'])-2*$tmp_row)); $page < min(ceil($table['_count']/$table['count_rows']), max((int)$_GET['p']+$tmp_row, $tmp_row*2)); $page++){
				echo " <a href='{$table['url']}".($page ? "&p=$page" : '')."{$table['_url']}".(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."'".($page == (int)$_GET['p'] ? " style='color: blue;'" : '').">".($page+1)."</a>";
			}
		}
	if ($_GET['p'] + $tmp_row < (int)($table['_count'] / $table['count_rows'])){
		echo " .. <a href='{$table['url']}&p=".ceil(($table['_count']) / $table['count_rows']-1)."{$table['_url']}".(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."'>>></a> ";
	} echo "</div>";

	#Подготовка замены
	foreach((array)$table['title'] as $k=>$v)
		$zamena["{title:$k}"] = $v;
	foreach($table as $k=>$v)
		if (gettype($v) == 'string')
			$zamena["{config:$k}"] = $v;
	function getspisok($name, $spisok){
		$result = array();
		if (gettype($spisok) == 'array'){
			foreach($spisok as $k=>$v)
				$result += getspisok($name.":$k", $v);
		}else{
			$result['{'.$name.'}'] = $spisok;
		}
		return $result;
	} $zamena += getspisok('spisok', $table['spisok']);

	$table_data = array();
	foreach(qn($sql) as $line){
		if (isset($_GET['order']) && isset($table['shablon'][ strtr($_GET['order'], array(' DESC'=>'')) ])){
			if (strlen($table['shablon'][ strtr($_GET['order'], array(' DESC'=>'')) ][ $line['id'] ])) $shablon[ count($table_data) ] = preg_replace("'<[^>]*?>'si", "", $table['shablon'][ strtr($_GET['order'], array(' DESC'=>'')) ][ $line['id'] ]);
		}elseif(isset($_GET['order']) && gettype($table['spisok'][ strtr($_GET['order'], array(' DESC'=>'')) ]) == 'array'){
			if (isset($table['spisok'][ strtr($_GET['order'], array(' DESC'=>'')) ]['*'][ $line[ strtr($_GET['order'], array(' DESC'=>'')) ] ])){
				if(isset($table['spisok'][ strtr($_GET['order'], array(' DESC'=>'')) ]['*'][ $line[ strtr($_GET['order'], array(' DESC'=>'')) ] ]))
					$spisok[ $line[ strtr($_GET['order'], array(' DESC'=>'')) ] ][] = count($table_data);
			}
		}
		$table_data[] = $line;
	}

//mpre($table['_fields']);
	# Вывод заголовков таблицы
	if($table['acess']['add'] && $table['edit'] == 'list') echo "<div style=\"margin:10px;\" class=\"button\"><a href={$table['url']}&edit>Добавить</a></div>";
	echo "<form name='mptable' action='{$table['url']}&p={$_GET['p']}{$table['_url']}".(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."' method='post' enctype='multipart/form-data' style='clear:both;'>";
	echo strlen($table['table']) ? $table['table'] : "<div class=\"cont\"><table class=\"MainTable\" cellspacing=\"0\" cellpadding=\"0\">";
	if (isset($table['top']['shablon'])){ # Обработка шаблона заголовка
		strtr($table['top']['shablon'], $zamena);
	}else{
		echo strlen($table['top']['tr']) ? $table['top']['tr'] : "<tr>";
//		foreach($table['_fields'] as $k=>$v){
		if ($table['edit'] == 'list' && $edit){
			echo "<th class=\"right\">Параметр</th><th>Значение</th>";
		}else{
			foreach($table['title'] ? $table['title'] : $table['_fields'] as $k=>$v){
				if (!isset($hidden[$k])){
					echo  strlen($table['top']['td']) ? $table['top']['td'] : "<th".($table['type'][$k] == 'file' ? ' style="min-width:200px;"' : '') .">";
					if($table['full_fields'][ $k ]['Comment']){
						echo "<span class='info'><div>{$table['full_fields'][ $k ]['Comment']}</div></span>";
					} if(!empty($conf['settings'][substr($table['name'], strlen("{$conf['db']['prefix']}")). ":{$k}"])){
						echo $conf['settings'][ substr($table['name'], strlen("{$conf['db']['prefix']}")). ":{$k}" ];
					}else if(isset($table['top']['result'])){ # Подстановка значений в результат
						echo str_replace("{result}", $table['title'][$k], $table['top']['result']);
					}else{ # Вывод содержимого в случае отсутствия результата заголовка
						echo "<a href='{$table['url']}".((int)$_GET['p'] ? "&p={$_GET['p']}" : '')."{$table['_url']}&order=$k".($_GET['order'] == $k ? '%20DESC' : '')."'>{$v}</a>";
					}
					echo "</th>";
				}
			}
			if ($table['acess']){
				echo  strlen($table['top']['td']) ? $table['top']['td'] : "<th style=\"width: 95px;\">";
				if (isset($table['top']['result'])){
					echo str_replace("{result}", 'Управление', $table['top']['result']);
				}else{
					echo "Управление";
				}
				echo "</td>";
			}
		}
		echo "</tr>";
	}

	if (!$edit){
	foreach($table_data as $nl=>$line){ # Вывод содержимого таблицы
		# Подготовка данных для замены шаблона значениями полей
		if ( isset($table['shablon']) && !$zamena_sql = array()){
			foreach($line as $sk=>$sv){
				if ($table['type'][$sk] == 'timestamp'){
					$zamena_sql["{f:$sk}"] = strtr(date('Y.m.d H:i:s', (int)$sv), array(' '=>'&nbsp;'));
				}elseif($table['type'][$sk] == 'idn'){
					require_once(mpopendir('include/idna_convert.class.inc'));
					if(!$IDN) $IDN = new idna_convert();
					$zamena_sql["{f:$sk}"] = $IDN->decode($sv);
				}else{
					$zamena_sql["{f:$sk}"] = ($table['maxsize'][$k] && $table['maxsize'][$k] < strlen($sv) ? "<div title='".htmlspecialchars(substr($sv, 0, $table['maxsize'][$k]))."'>".substr(htmlspecialchars($sv), 0, $table['maxsize'][$k])."...</div>" : htmlspecialchars($sv) ); //$sv;
				}// mpre($zamena_sql);
				if($table['spisok'][$sk]['*']){
					$zamena_sql["{spisok:$sk}"] = $table['spisok'][$sk]['*'][$sv];
				}// mpre($zamena_sql);
			}
		}
		# Управление записями таблицы
		$table_edit = '';
		if (isset($table['acess']['edit'][ $line['id'] ]) ? $table['acess']['edit'][ $line['id'] ] : $table['acess']['edit']['*'])
			$table_edit .= " <a href='{$table['url']}&edit={$line[id]}".((int)$_GET['p'] ? "&p={$_GET['p']}" : '').$table['_url'].(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."' class=\"ed\"></a>";
		if (isset($table['acess']['cp'][ $line['id'] ]) ? $table['acess']['cp'][ $line['id'] ] : $table['acess']['cp']['*'])
			$table_edit .= " <a href='{$table['url']}&cp={$line[id]}".((int)$_GET['p'] ? "&p={$_GET['p']}" : '').$table['_url'].(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."' class=\"copy\"></a>";
		if (isset($table['acess']['del'][ $line['id'] ]) ? $table['acess']['del'][ $line['id'] ] : $table['acess']['del']['*'])
			$table_edit .=  "<a href='{$table['url']}&del={$line['id']}".((int)$_GET['p'] ? "&p={$_GET['p']}" : '').$table['_url'].(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."' onclick=\"javascript: if (confirm('Вы уверены?')){return obj.href;}else{return false;}\" class=\"del\"></a>";

		# Подготовка данных для замены шаблона значениями из списка
		if (isset($table['middle']['shablon'])){
			$string = $table['middle']['shablon'];
			$string = strtr($string, $zamena + $zamena_sql);
			$string = str_replace('{config:row-edit}', $table_edit, $string);
//			if(isset($table['spisok'][$k])) $string = strtr($string, $zamena_spiska[$k]);
			echo $string;
		}else{
			echo  strlen($table['middle']['tr']) ? $table['top']['tr'] : "<tr".($nl%2 == 0 ? "  class='even'" : '').">";
//			foreach($line as $k=>$v){
			foreach($table['title'] ? $table['title'] : $table['_fields'] as $k=>$v){
				isset($line[$k]) ? $v = $line[$k] : $disable[$k] = !$v = '';
				$zamena['{f}'] = $k; # Конструкция шаблона
				if ($k == 'id') $id = $v;
				if (!isset($hidden[$k])){
					echo  strlen($table['middle']['td']) ? $table['top']['td'] : "<td>";
					if (isset($table['shablon'][$k]['*']) || isset($table['shablon'][$k][ $line['id'] ])){ # Если есть шаблон для поля в списке
						$string = isset($table['shablon'][$k][ $line['id'] ]) ? $table['shablon'][$k][ $line['id'] ] : $table['shablon'][$k]['*'];
						do{
							$old_string = $string;
							$string = strtr($string, (array)$zamena + (array)$zamena_sql);
						}while($string != $old_string);
						echo $string;
					}elseif((gettype($table['spisok'][$k]['*']) == 'array')){ # Задан списоk на все поле
						$string = array_key_exists($v, $table['spisok'][$k]['*']) ? "{$table['spisok'][$k]['*'][$v]}" : $v;
						do{
							$old_string = $string;
							$string = strtr($string, (array)$zamena + (array)$zamena_sql);
						}while($string != $old_string);
						echo (!isset($table['spisok'][$k]['*'][$v]) ? "<span style=color:#a00; title=\"$v\">" : "<span title=\"$v\">"). $string. "</span>";
					}elseif((gettype($table['spisok'][$k][$id]) == 'array')){# Задан список для поля и id
						$string = array_key_exists($v, $table['spisok'][$k][$id]) ? "{$table['spisok'][$k][$id][$v]}" : $v;
						do{
							$old_string = $string;
							$string = strtr($string, $zamena + $zamena_sql);
						}while($string != $old_string);
						echo $string;
					}else{ # Если нет списка
						if ($table['type'][$k] == 'file'){
							if ( strlen($v) ) {
								if ($table['acess'] && !isset($disable[$k]) && !isset($table['set'][$k]))
									echo "<a onclick=\"javascript: if (confirm('Стереть файл?')){return obj.href;}else{return false;}\" href='{$table['url']}&delfile={$line[id]}&fil=$k&p={$_GET['p']}{$table['_url']}".(strlen($_GET['order']) ? "&order={$_GET['order']}" : '')."'><img src='/img/del.png' border='0'></a>";
								echo basename($v);
								# Кнопка удаления файла если не выключено поле и нет значения для записи
								# Ссылка на просмотр графического файла во всплывающем окне
								$img_ext = array_flip(array('.png', '.gif', '.jpg', 'tif', '.bmp'));
								if (isset($img_ext[substr($v, -4)])){
									list($w, $h) = getimagesize (mpopendir('include')."/$v");
									echo "&nbsp;<a href=\"javascript:openPopup('{$table['url']}&f[$k]={$line['id']}', 'Вложенный файл', $w, $h);\"><img src='/img/view.png' border='0'></a>";
								}
							}else{
								echo "&nbsp;";
							}
						}elseif($table['type'][$k] == 'timecount'){
							echo is_numeric($v) ? "<div title='$v'>".((int)($v / 86400) ? (int)($v / 86400)."&nbsp;" : '').gmdate('H:i:s', $v)."</div>" : htmlspecialchars($v);
						}elseif($table['type'][$k] == 'timestamp'){
							if(is_numeric($v)){
								echo ($v > 0 ? "<div title='$v'>".str_replace(' ', '&nbsp;', date('Y.m.d H:i:s', $v))."</div>" : "");
							}else{
								echo htmlspecialchars($v);
							}
						}elseif($table['type'][$k] == 'sort'){
							echo "<nobr><table><tr><td><a href='{$table['url']}&p={$_GET['p']}".($table['order'] ? "&order={$_GET['order']}" : '')."&dec[$k]={$line['id']}' class='".(strpos($table['order'], 'DESC') || strpos($table['order'], 'desc') ? 'down' : 'up')."'><img src='/img/n.gif'></a></td><td>$v</td><td><a href='{$table['url']}&p={$_GET['p']}".($table['order'] ? "&order={$_GET['order']}" : '')."&inc[$k]={$line['id']}' class='".(!strpos($table['order'], 'DESC') && !strpos($table['order'], 'desc') ? 'down' : 'up')."'><img src=/img/n.gif></a></td></tr></table></nobr>";
						}elseif($table['type'][$k] == 'bsize'){
							echo "<div title='$v Б'>".cusr($v)."</div>";
						}elseif($table['type'][$k] == 'idn'){
							require_once(mpopendir('include/idna_convert.class.inc'));
							if(!$IDN) $IDN = new idna_convert();
							echo "<div title=\"$v\">". $IDN->decode($v). "</div>";
						}elseif(substr($k, -3) == "_id" && is_numeric($v)){
							if($sel = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_". ($f = substr($k, 0, strlen($k)-3)). " WHERE id=". (int)$v), 0)){
								echo "<a href=\"/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$f}&where[id]={$v}\">#{$sel['id']}</a>&nbsp;{$sel['name']}";
							}else{
								echo $v;
							}
						}else{
							$v = strlen($v) ? htmlspecialchars($v) : '&nbsp;';
							echo ($table['maxsize'][$k] && $table['maxsize'][$k] < strlen($v) ? "<div title='".htmlspecialchars(substr($v, 0, $table['maxsize'][$k]))."'>".substr($v, 0, $table['maxsize'][$k])."...</div>" : $v );
						}
					} echo "</td>";
				}
			}
			if ($table['acess']){ # Редактирование таблицы
				echo  strlen($table['middle']['td']) ? $table['top']['td'] : "<td class=\"tools\">";
				echo "$table_edit</td>";
			}
			echo "</tr>";
		}
	}
	}

	# Форма для редактирования и добавления данных
	if (((int)$_GET['cp'] && $table['acess']['cp']) || ((int)$_GET['edit'] && $table['acess']['edit']) || ($table['acess']['add']['*'] && ($edit || $table['edit'] != 'list'))){
//		 echo "<form class='StarsiteWysiwygEditor'>";
		echo $_GET['edit'] ? "<input type='hidden' name='id' value='{$_GET['edit']}'>" : ''; // class='StarsiteWysiwygEditor'
		echo  strlen($table['bottom']['tr']) ? $table['top']['tr'] : "<tr>";
		if (isset($table['bottom']['shablon'])){
			$string = $table['bottom']['shablon'];
			$string = strtr($string, $zamena);
			# Установка выбора содержимого списка
			foreach($table['spisok'] as $f=>$m){
				$select = "<select name='$f' style='width:100%'>";
				if (gettype($m['*']) == 'array')
					foreach($m['*'] as $k=>$v){
						$select .= "<option value='$k'".($table['default'][$f] == $k || (isset($_GET['edit']) && $edit[$f] == $k) ? ' selected' : '').">$v</option>";
					}
				$select .= "</select>";
				$string = str_replace("{spisok:$f:*}", $select, $string);
			}
			#Установка значений edit
			foreach($table['_fields'] as $k=>$v)
				$string = str_replace("{edit:$k}", strlen($edit[$k]) ? $edit[$k] : $table['default'][$k], $string);
			echo $string;
		}else{ # Вывод таблицы с данными
//			foreach($table['_fields'] as $k=>$v){
			$title = ($table['edit'] == 'list' && $edit ? $table['etitle'] : $table['title']);
			foreach($title ?: $table['_fields'] as $k=>$v){
				if ($k == 'id') $sid = $v['id'];
				if (!isset($hidden[$k])){
					echo  strlen($table['bottom']['td']) ? $table['bottom']['td'] : ($table['edit'] == 'list' && $edit ? "<tr><td style=\"width:15%; text-align:right;\" title=\"{$k}\">".(!empty($conf['settings'][substr($table['name'], strlen("{$conf['db']['prefix']}")). ":{$k}"]) ? $conf['settings'][substr($table['name'], strlen("{$conf['db']['prefix']}")). ":{$k}"] : ($title[$k] ? $title[$k] : $k))."</td>" : '')."<td>";

					if (gettype($table['spisok'][$k][(int)$_GET['edit']]) == 'array'){ # Для каждого id свой список
						echo "<select name='$k' style='width:100%'".($disable[$k] || $table['set'][$k] ? ' disabled' : '').">";
						foreach($table['spisok'][$k][(int)$_GET['edit']] as $id=>$value)
							echo "<option value='$id'".( (($_GET['edit'] || $_GET['cp']) && $id == $edit[$k]) || (!$_GET['edit'] && $id == $table['default'][$k][$_GET['edit']]) ? ' selected' : '').">$value</option>";
						echo "</select>";
					}elseif (gettype($table['spisok'][$k]['*']) == 'array'){
						echo "<select name='$k' style='width:100%'".($disable[$k] || $table['set'][$k] ? ' disabled' : '').">";
						if($edit[$k] && !isset($table['spisok'][$k]['*'][ $edit[$k] ])){
							echo "<option value='". $edit[$k]. "'>". $edit[$k]. "</option>";
						}//elseif(!isset($table['spisok'][$k]['*'][ $edit[$k] ])){
//							echo "<option value='0'></option>";
//						}
						foreach($table['spisok'][$k]['*'] as $id=>$value)
							echo "<option value='$id'".( (($_GET['edit'] || $_GET['cp']) && $id == $edit[$k]) || (!$_GET['edit'] && $id == $table['default'][$k]['*']) ? ' selected' : '').">$value</option>";
						echo "</select>";
					}else{
						if ($table['type'][$k] == 'textarea' || ($table['type'][$k] == 'wysiwyg' && !$edit)){
							$default = @htmlspecialchars(strtr((gettype($table['default'][$k]) == 'array') && isset($table['default'][$k]['*']) ? $table['default'][$k]['*'] : $table['default'][$k], array('\''=>'&#39;')));
							echo "<textarea name='$k' style='width:100%; height:100px;'".($disable[$k] || $table['set'][$k] ? ' disabled' : '').">".($_GET['edit'] || $_GET['cp'] ? htmlspecialchars($edit[$k]) : ($table['set'][$k] ? htmlspecialchars($table['set'][$k]) : $default))."</textarea>";
						}elseif ($table['type'][$k] == 'file'){
							echo "<input type='file' name='$k'".($_GET['edit'] || $_GET['cp'] ? " value='".htmlspecialchars($edit[$k])."'" : "")." style='width:100%'".($disable[$k] || $table['set'][$k] ? ' disabled' : '').">";
						}elseif($table['type'][$k] == 'wysiwyg'){
							echo mpwysiwyg($k, $edit[$k]);
						}else{
							echo "<input type='text' name='$k'".($_GET['edit'] || $_GET['cp'] ? " value='".strtr(htmlspecialchars($edit[$k]), array('\''=>'&#39;'))."'" : " value='".@htmlspecialchars(strtr((gettype($table['default'][$k]) == 'array') && isset($table['default'][$k]['*']) ? $table['default'][$k]['*'] : $table['default'][$k], array('\''=>'&#39;')))."'")." style='width:100%'".($disable[$k] || $table['set'][$k] || empty($table['_fields'][$k]) ? ' disabled' : '').">";
						}
					}
					echo "</td>".($table['edit'] == 'list' && $edit ? '</tr>' : '');
				}
			}
		}
		echo  strlen($table['bottom']['td']) ? $table['bottom']['td'] : "<td colspan=\"2\">";
		echo "<div class=\"button\" style=\"float:left\"><input type='submit' ".($_GET['edit'] ? "name='edit' value='сохранить'" : "name='add' value='добавить'")."></div> </td></tr>
		";
	}
	echo "</table></div></form>";
}

function loadfile($table, $id){
	$disable = array_flip( array_merge( array('id'), (array)$table['disable'] ) ); # Меняем местами ключ=>значение и добавляем поле id
	$modname = basename(dirname(__FILE__));
	foreach($_FILES as $fields=>$v){
		if ($table['type'][$fields] == 'file' && $v['size'] && !isset($disable[$fields])){
			if (isset($table['ext'][$fields][$v['type']]) || isset($table['ext'][$fields]['*'])){
				$file_name = "images/{$table['name']}_{$fields}_{$id}".(empty($table['ext'][$fields][$v['type']]) ? ".".array_pop(explode('.', $v['name'])) : $table['ext'][$fields][$v['type']]);
				$folder_name = mpopendir("include");
				if (@copy($v['tmp_name'], "$folder_name/$file_name")){ # Перемещение файла в папку назначения
					$sql = "UPDATE {$table['name']} SET $fields='$file_name' WHERE id = '$id'";
					foreach (glob(ini_get('upload_tmp_dir'). "/{$_SERVER['SERVER_NAME']}/*_{$table['name']}_{$fields}_{$id}*") as $fn) {
						unlink($fn);
					}
					if (strlen($table['where'])) $sql .= " AND ".mpquot($table['where']);
					if (strlen($table['_where'])) $sql .= " AND {$table['_where']}";
					if ($table['debug']) mpre($sql);
					$result = mpqw($sql);
				}else{ # Ошибка копирования файла
					echo "<p><font color=red>Ошибка сохранения файла в $folder_name/$file_name Убедитесь в существовании директории и правах доступа к ней</font><p>";
				}
			}else{
				echo "<p><font color=red>Запрещенное для загрузки расширение файла</font> {$v['type']}"; // >>> <a href=>Добавить расширение</a>";
			}
		}
	}
}

function dspisok($sql){
	global $dbconn;
	$spisok = array();
	$result = $dbconn->Execute($sql);
	while(!$result->EOF){
		list($id, $name, $parent) = $result->fields;
		$spisok[$id]['name'] = $name;
		$spisok[$id]['parent'] = $parent;
		$result->MoveNext();
	}
	$dspisok = array();
	$tmp = dr(
		array(
			'key'=>0,
			'prefix'=>'',
			'spisok'=>&$spisok,
			'dspisok'=>array(),
		)
	);
	return $tmp['dspisok'];
}

function bottom($data=array(), $zamena=array()){
	$return = '';
	foreach($data['title'] as $k=>$v){
		if (isset($zamena[ $k ])){
			$return .= $zamena[ $k ];
		}elseif (!isset($data['colspan'][ $k ]) || $data['colspan'][ $k ]){
			$return .= "<td".($data['colspan'][ $k ] ? " colspan='{$data['colspan'][ $k ]}'" : '').">";
			if ($data['type'][ $k ] == 'file'){
				$return .= "<input type='file' name='$k' style='width: 100%;'>";
			}elseif ($data['type'][ $k ] == 'spisok'){
				$return .= "{spisok:$k:*}";
/*			}elseif ($data['type'][ $k ] == 'sort'){
				$return .= "<nobr><a href='{$table['url']}&p={$_GET['p']}".($table['order'] ? "&order={$_GET['order']}" : '')."&dec[123]={$line['id']}'><img src=/img/".(strpos($table['order'], 'DESC') || strpos($table['order'], 'desc') ? 'down' : 'up').".gif border=0></a>&nbsp;$v&nbsp;<a href='{$table['url']}&p={$_GET['p']}".($table['order'] ? "&order={$_GET['order']}" : '')."&inc[123]={$line['id']}'><img src=/img/".(!strpos($table['order'], 'DESC') && !strpos($table['order'], 'desc') ? 'down' : 'up').".gif border=0></a></nobr>";*/
			}else{
				$return .= "<input type='text' name='$k' value='{edit:$k}' style='width: 100%;'>";
			}
			$return .= "</td>";
		}
	}
	return $return;
}

function dr($param){
	foreach($param['spisok'] as $k=>$v){
		if ($param['key'] == $v['parent']){
			$param['dspisok'][$k] = $param['prefix'].$v['name'];
			dr(array('key'=>$k, 'prefix'=>$param['prefix'].str_repeat('&nbsp;', 3), 'spisok'=>&$param['spisok'], 'dspisok'=>&$param['dspisok']));
		}
	}
	return $param;
}

function cusr($size){
	$abbr = array('0'=>'Б', '3'=>'КБ', '6'=>'МБ', '9'=>'ГБ', '12'=>'ТБ');
	if ($size >= 1024){
		$return = $size / 1024; $pr = 3;
	}else{
		$return = $size;
	}
	while($return >= 1024){
		$return = $return / 1024; $pr += 3;
	} $return = (int)($return * 100) / 100;
	$return .= " ".$abbr[ $pr ];
	return $return;
}


?>