<? die; # Кабинет

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$conf['tpl']['sobst_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_sobst");
$conf['tpl']['prof_id'] = spisok("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_prof");
$conf['tpl']['tz_id'] = spisok("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_tz");
$conf['tpl']['mop_id'] = spisok("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mop");

$firms = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$arg['uid']. " LIMIT 10"));

?>

<div class="AspNet-FormView-Data">
	<div style="float:left;"><a href="/<?=$arg['modpath']?>:edit/tn:index">Добавить фирму</a></div>
	<table cellspacing="1" cellpadding="5" width="100%" style="border-spacing: 1px; border-collapse: separate; font-size: 11px; background-color: rgb(204, 204, 204); margin-top: 5px; clear:both;" class="kabbb">
	<tbody>
		<? foreach($firms as $k=>$v): ?>
			<tr>
					<td colspan=2 style="text-align:right;"><a href="/<?=$arg['modpath']?>:edit/tn:index/<?=$v['id']?>">Редактировать</a></td>
			</tr>
			<? foreach(array_diff_key($v, array_flip(array('id', 'uid'))) as $n=>$z): ?>
				<tr cssclass="DivTableRow">
					<td style="background-color: rgb(245, 248, 236);">
						<span><?=($conf['settings'][$fn = "{$arg['modpath']}_index_{$n}"] ?: $fn)?></span>
					</td>
					<td bgcolor="white">
						<span style="color: Black;"><?=(!empty($conf['tpl'][ $n ]) ? $conf['tpl'][ $n ][ $z ] : $z)?></span>
					</td>
				</tr>
			<? endforeach; ?>
		<? endforeach; ?>
	</tbody></table>      
</div>