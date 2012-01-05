<? die;

# Список полей для редактирования
$edit = array('email'=>'Почта');

if (strlen($_POST['edit']['old_pass']) && strlen($_POST['edit']['new_pass']) && ($_POST['edit']['new_pass'] == $_POST['edit']['duble_pass'])){
	$pass = mpql(mpqw("SELECT pass FROM {$GLOBALS['conf']['db']['prefix']}users WHERE id = {$GLOBALS['conf']['user']['uid']}"));
	if ($pass['0']['pass'] == mphash($GLOBALS['conf']['user']['uname'], $_POST['edit']['old_pass'])){
		mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}users SET pass = '".mphash($GLOBALS['conf']['user']['uname'], $_POST['edit']['new_pass'])."' WHERE id = {$GLOBALS['conf']['user']['uid']}");
		echo "<p><center><font color=red>Пароль изменен</font></center><p>";
	}else{
		echo "<p><center><font color=red>Пароль не совпадает</font></center><p>";
	}
}

if (count($_POST['edit'])){
	echo "<center><font color=green>Данные сохранены</font></center>";
	foreach($_POST['edit'] as $k=>$v){
		if ($k == 'email' && $_SERVER['REMOTE_ADDR'] == '217.8.80.220'){
			echo "{$GLOBALS['conf']['user']['reg_time']}:$v";
			mail($v, 'Подтверждение почтового ящика', "Данное сообщения имеет цель подтвердить принадлежность данного электронного ящика человеку которые пытается его использовать. Если вы хотите использовать этот адрес на сайте <a>http://surgut.info</a> нажмите на ссылку для подтверждения <a href=>http://surgut.info/?m[kabinet]=edit&m=$v&s=".md5("{$GLOBALS['conf']['user']['reg_time']}:$v")."</a>");
		}
		if (isset($edit[$k])) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}users SET $k = '$v' WHERE id = {$GLOBALS['conf']['user']['uid']}");
	}
}

# Подписки
$subscribe = count(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}users_mem WHERE gid = (SELECT id FROM {$GLOBALS['conf']['db']['prefix']}users_grp WHERE name = 'Рассылка') AND uid = {$GLOBALS['conf']['user']['uid']}"))) ? 1 : 0;
//echo $subscribe;
if(strlen($_POST['edit']['subscribe'])){
	if ($_POST['edit']['subscribe'] && !$subscribe){
		$sql = "INSERT INTO {$GLOBALS['conf']['db']['prefix']}users_mem (gid, uid) VALUE ((SELECT id FROM {$GLOBALS['conf']['db']['prefix']}users_grp WHERE name = 'Рассылка'), {$GLOBALS['conf']['user']['uid']})";
		mpqw($sql);
		$subscribe = 1;
	}elseif(!$_POST['edit']['subscribe'] && $subscribe){
		$sql = "DELETE FROM {$GLOBALS['conf']['db']['prefix']}users_mem WHERE gid = (SELECT id FROM {$GLOBALS['conf']['db']['prefix']}users_grp WHERE name = 'Рассылка') AND uid = {$GLOBALS['conf']['user']['uid']}";
		mpqw($sql);
		$subscribe = 0;
	}
}

$line = mpql(mpqw("SELECT ".implode(', ', array_flip($edit))." FROM {$GLOBALS['conf']['db']['prefix']}users WHERE id = {$GLOBALS['conf']['user']['uid']}"));

//echo "<p><b><center>Редактирование свойств</center></b>";
echo "<p><form method='post'><div align='center'><table border='0' cellspacing='0' cellpadding='3'>";
foreach($edit as $k=>$v)
	echo "<tr><td width='50%' align='right'>$v:</td><td><input type='text' name='edit[$k]' value='{$line[0][$k]}'></td></tr>";
echo "<tr><td align='right'>Старый пароль:</td><td><input type='password' name='edit[old_pass]'></td></tr>";
echo "<tr><td align='right'>Новый пароль:</td><td><input type='password' name='edit[new_pass]'></td></tr>";
echo "<tr><td align='right'>Дубль пароль:</td><td><input type='password' name='edit[duble_pass]'></td></tr>";
# Рассылка
echo "<tr><td align='right'>Рассылка:</td><td><select name='edit[subscribe]'><option value='0'>Выключена</option><option value='1'".($subscribe ? ' selected' : '').">Включена</option></select></td></tr>";

echo "<tr><td colspan=2><input type='submit' style='width:100%' value='Изменить'></td></tr>";
echo "</table></div></form>";

?>