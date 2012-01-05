<? die;

if (strlen($_POST['mess']) && strlen($_POST['mess'])){
	mpqw("INSERT INTO {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_mess (time, uid, mess, kontakt) VALUES (".time().", {$GLOBALS['conf']['user']['uid']}, '".htmlspecialchars(mpquot($_POST['mess']))."', '".htmlspecialchars(mpquot($_POST['kontakt']))."')");
	header("Location: /{$arg['modpath']}");
	exit;
}

echo <<<EOF
<form method=post>
<table width=100%>
	<tr valign=top>
		<td width=100px; align=right></td>
		<td align=center>Для возможности удалить сообщение добавляйте его от имени зарегистрированного пользователя. В случае если объявление добавлено от имени гостя удаление и редактирование его будет не возможно.</td>
	</tr>
	<tr valign=top>
		<td width=100px; align=right>Сообщение:</td>
		<td><textarea name=mess style='width:100%'></textarea></td>
	</tr>
	<tr valign=top>
		<td align=right>Контакт:</td>
		<td><input type=text name=kontakt style='width:100%'></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align=right><input type=submit></td>
	</tr>
</table>
</form>
EOF;

?>