<? # Свойства

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));


/*foreach(mpql(mpqw("DESC {$conf['db']['prefix']}{$arg['modpath']}")) as $k=>$v){
	$f[$v['Field']] = $v['Type'];
}*/

$conf['tpl']['user'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE ". ($arg['uid'] > 0 ? " id=". (int)$arg['uid'] : " name=\"{$conf['settings']['default_usr']}\"")), 0);
$conf['tpl']['fields'] = array_diff_key($conf['tpl']['user'], array_flip(array('id', 'tid', 'img', 'pass', 'email', 'reg_time', 'last_time', 'param', 'ref', 'refer', 'flush')));

foreach($conf['tpl']['fields'] as $k=>$v){
	if(substr($k, -3) == '_id'){
		$id[$k] = spisok($sql = "SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3). " ORDER BY name");
	}
}

?>
<!-- [settings:foto_lightbox] -->
<? if($conf['user']['uid'] == $conf['tpl']['user']['id']): ?>
	<div>
		<a href="/<?=$arg['modname']?>:edit" style="">Редактировать контакт</a>
	</div>
<? endif; ?>
<table id="Table1" style="font-size: 11px; background-color: rgb(204, 204, 204); height: 100%;" width="100%" cellpadding="5" cellspacing="1">
	<tbody>
			<? if(isset($conf['tpl']['user']['img'])): # Если поле фотографии есть в базе ?>
				<tr>
					<td style="float:right;">
						<div id="gallery" style="margin:10px;">
						<a href="/<?=$arg['modname']?>:img/<?=$arg['uid']?>/tn:index/w:600/h:500/null/img.jpg" title="<?=$conf['tpl']['user']['name']?>" alt="<?=$conf['tpl']['user']['name']?>" onClick="return false;">
							<img src="/<?=$arg['modname']?>:img/<?=$arg['uid']?>/tn:index/w:150/h:220/null/img.jpg">
						</a>
						</div>
					</td>
					<td>
						<div>Зарегистрирован на сайте:</div>
						<div style="text-align:right;"><?=date('d.m.Y H:m:i', $conf['tpl']['user']['reg_time'])?></div>
						<div>Последний вход на сайт:</div>
						<div style="text-align:right;"><?=($conf['tpl']['user']['last_time'] ? date('d.m.Y H:m:i', $conf['tpl']['user']['last_time']) : '')?></div>
						<a href="/messages:send/uid:<?=$_GET['id']?>">Отправить сообщение</a>
					</td>
				</tr>
			<? endif; ?>
			<? foreach(array_intersect_key($conf['tpl']['user'], $conf['tpl']['fields']) as $k=>$v): ?>
				<tr>
					<td style="background-color: rgb(245, 248, 236); width: 40%;">
						<span><?=($conf['settings']["users_field_$k"] ? $conf['settings']["users_field_$k"] : "users_field_$k")?></span>
					</td>
					<td bgcolor="white">
						<span style="color: Black;">
							<? if(array_key_exists($k, (array)$id) && substr($k, -3) == '_id'): ?>
								<?=$id[ $k ][ $v ]?>
							<? else: ?>
								<?=$v?>
							<? endif; ?>
						</span>
					</td>
				</tr>
			<? endforeach; ?>
	</tbody>
</table>
