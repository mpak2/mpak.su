<? die; # Рассылка

if ((int)$arg['confnum']){
	# Востановление параметров модуля
	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
		$param = unserialize($res[0]['param']);

	$fields = array('url'=>'Шаблон', 'subject'=>'Тема', 'param'=>'Параметр');
	$default = array('url'=>'/\/[index.php]{0,}\?m\[news\]=admin(.*)/i', 'subject'=>'Тема', 'param'=>'Content-type: text/html; charset=cp1251;', 'text'=>"Содержание письма\nВозможные значение:\n{POST}, {POST:Параметр},\n{GET}, {GET:Параметр},\n{SERVER}, {SERVER:Параметр},\n{SETTINGS}, {SETTINGS:Параметр},\n{USER}, {USER:Параметр}");
	if ($_POST['blocks'][ $arg['confnum'] ]){
		foreach($default as $k=>$v)
			$znach[$k] = strtr(stripslashes($_POST['blocks'][ $arg['confnum'] ][$k]), array('\\'=>'&#92', '"'=>'&#34;', "'"=>'&#39;'));
		if (isset($_POST['blocks'][ $arg['confnum'] ]['id']) && isset($param[(int)$_POST['blocks'][ $arg['confnum'] ]['id']])){
			$param[ (int)$_POST['blocks'][ $arg['confnum'] ]['id'] ] = $znach;
		}else{
			$param[] = $znach;
		}
	}elseif(isset($_GET['edit'])){
		$edit = $param[(int)$_GET['edit']];
	}elseif(isset($_GET['del'])){
		unset($param[(int)$_GET['del']]);
	}
	echo "<a href='/?m[blocks]=admin&conf={$arg['confnum']}'>Обновить</a>";
	echo "<form action='?m[blocks]=admin&conf={$arg['confnum']}' method='post'>";
	echo isset($_GET['edit']) ? "<input type='hidden' name='blocks[{$arg['confnum']}][id]' value='{$_GET['edit']}'>" : '';
	foreach($fields as $k=>$v){
		$th .= "<td align=center><b>$v</b></td>";
		$tb .= "<td><input type=text name='blocks[{$arg['confnum']}][$k]' value='".($edit[$k] ? $edit[$k] : $default[$k])."' style='width:100%'></td>";
	}
	$th .= "<td align=center><b>Управление<b></td>";
	$tb .= "<td><input type='submit' value='".(count($edit) ? 'Изменить' : 'Добавить')."'></td>";
	echo "<table border=1 cellspacing=0 cellpadding=2><tr>$th</tr>";
	foreach($param as $k=>$v){
		echo "<tr><td>{$v['url']}</td><td>{$v['subject']}</td><td>{$v['param']}</td><td><a href=/?m[blocks]=admin&conf={$arg['confnum']}&del=$k><img src=/img/del.png border=0></a>&nbsp;<a href=/?m[blocks]=admin&conf={$arg['confnum']}&edit=$k><img src=/img/edit.png border=0></a></td></tr>";
	}
	echo "<tr>$tb</tr>";

	#Вывод дополнительных записай в таблице
	echo "<tr><td colspan=".(count($fields)+1)."><textarea style='width: 100%; height: 200px;' name='blocks[{$arg['confnum']}][text]'>".($edit['text'] ? $edit['text'] : $default['text'])."</textarea></td></tr>";

	echo "</table></form>";

	# Сохранение параметров модуля
	mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"))))
	$param = unserialize($res[0]['param']);

if ($_POST){
	foreach($param as $k=>$v){
		if (preg_match (strtr($v['url'], array('&#92'=>'\\', '&#34;'=>'"', '&#39;'=>"'")), $_SERVER['REQUEST_URI'])){
			$vals['{POST}'] = "\n\$_POST(";
			foreach($_POST as $n=>$z){
				$vals['{POST}'] .= "\n\t'$n'=>'$z',"; $vals["{POST:$n}"] = stripslashes($z);
			}
			$vals['{POST}'] .= "\n)";
			$vals['{GET}'] = "\n\$_GET(";
			foreach($_GET as $n=>$z){
				$vals['{GET}'] .= "\n\t'$n'=>'$z',"; $vals["{GET:$n}"] = stripslashes($z);
			}
			$vals['{GET}'] .= "\n)";
			$vals['{SERVER}'] = "\n\$_SERVER(";
			foreach($_SERVER as $n=>$z){
				$vals['{SERVER}'] .= "\n\t'$n'=>'$z',"; $vals["{SERVER:$n}"] = stripslashes($z);
			}
			$vals['{SERVER}'] .= "\n)";
			$vals['{SETTINGS}'] = "\n\$conf['settings'](";
			foreach($GLOBALS['conf']['settings'] as $n=>$z){
				$vals['{SETTINGS}'] .= "\n\t'$n'=>'$z',"; $vals["{SETTINGS:$n}"] = stripslashes($z);
			}
			$vals['{SETTINGS}'] .= "\n)";
			$vals['{USER}'] = "\n\$conf['user'](";
			foreach($GLOBALS['conf']['user'] as $n=>$z){
				$vals['{USER}'] .= "\n\t'$n'=>'$z',"; $vals["{USER:$n}"] = stripslashes($z);
			}
			$vals['{USER}'] .= "\n)";

//			mail($v['mail'], $v['subject'], strtr($v['text'], $vals));

			$admin_grp_id = mpql(mpqw("SELECT g.id FROM {$GLOBALS['conf']['db']['prefix']}settings as s, {$GLOBALS['conf']['db']['prefix']}users_grp as g WHERE s.name='admin_grp' AND s.value=g.name"), 0, 'id');

			if ($arg['access'] > 4){
				$deact = "\n\n{$GLOBALS['conf']['settings']['unsubscribe_mess']} http://{$_SERVER['HTTP_HOST']}/?m[subscribe]&unsubscribe=";
				foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}subscribe WHERE disable=0")) as $n=>$z){
					$md5 = md5($z['id'].":".$z['time'].":".$z['mail']);
					mail($z['mail'], strtr($v['subject'], array('&#92'=>'\\', '&#34;'=>'"', '&#39;'=>"'")), strtr($v['text'], $vals).$deact.$z['mail'].":".$md5, $v['param']);
				}
				mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}subscribe SET count=count+1 WHERE disable=0");
				echo "Список рассылки сформирован";
			}
		}
	}
}

/*if($_POST[ $arg['blocknum']]['mail']){
	echo "<center>";
	if(preg_match('/^[\._a-zA-Z0-9-]+@[\.a-zA-Z0-9-]+\.[a-z]{2,6}$/i', $_POST[$arg['blocknum']]['mail'])){
		if(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}subscribe WHERE `mail`='{$_POST[$arg['blocknum']]['mail']}'"))){
			echo "Почтовый ящик уже в базе";
		}else{
			mpqw("INSERT INTO {$GLOBALS['conf']['db']['prefix']}subscribe (`time`, `mail`) VALUES (".time().", '{$_POST[$arg['blocknum']]['mail']}')");
			echo "Почта добавлена в базу";
		}
	}else{
		echo "Введите почтовый ящик";
	}
	echo "</center>";
}elseif($_GET['unsubscribe']){
	$param = explode(':', $_GET['unsubscribe']);
	$z = mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}subscribe WHERE `mail`='{$param[0]}'"), 0);
	if ($param[1] == md5($z['id'].":".$z['time'].":".$z['mail'])){
		echo "<div>Рассылка выключена</div>";
		mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}subscribe SET disable=1 WHERE id={$z['id']}");
		if ($GLOBALS['conf']['settings']['delete_mail']){
			mpqw("DELETE FROM {$GLOBALS['conf']['db']['prefix']}subscribe WHERE id={$z['id']}");
		}
	}
}*/

//echo $_SERVER['HTTP_HOST'];
echo "<form action='/?m[subscribe]' method=post><table width=100%><tr align='center'>";
echo "<td><input type='text' name='subscribe[mail]' value='".($_POST['subscribe']['mail'] ? $_POST['subscribe']['mail'] : 'почтовыйящик@почта.рф')."'></td>";
echo "</tr><tr align='center'>";
echo "<td><input type='submit' value='Подписаться'></td>";
echo "</tr></table></form>";

?>