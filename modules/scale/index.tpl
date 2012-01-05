<? if ($_GET['id']): # Конкретное утверждение ?>
	<form method="post">
	<input type="hidden" name="id" value="<?=$conf['tpl']['scale']['id']?>">
	<table border=0 width=100% style="border-color: rgb( 100, 100, 100);">
		<tr>
			<td colspan=2 align=center>
				<div style="border: 1px solid gray; margin:5px; padding:15px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
					<?=$conf['tpl']['scale']['name']?>
					<div style="text-align:right; margin-top: 10px;">Источник: <a target=_blank href="<?=$conf['tpl']['scale']['src']?>"><?=$conf['tpl']['scale']['src']?></a></div>
				</div>
				<div style="text-align:right; margin:5px;"><a href="/<?=$arg['modpath']?>">Полный список</a></div>
			</td>
		</tr>
		<tr>
			<td align=right width=50%>Согласен <input type="radio" name='yes' value=1 checked></td>
			<td><input type="radio" name='yes' value=0> Не согласен</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
				<table style="width: 100%" border=0>
					<tr>
						<td style="width: 100px;">Представьтесь: </td>
						<td><input type="text" name="name" style="width: 70%;" title="Имя"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center><textarea name="mess" style="width: 100%;" title="Мнение"></textarea></td>
		</tr>
		<tr>
			<td style="text-align: right;" colspan="2"><input type="submit" value="Добавить"></td>
		</tr>
	</table>
	</form>

	<table cellpadding=5 callspacing=0 border=0>
		<tr>
			<td align=right style="font-style: italic; font-weight: bold;">Согласен (<?=$conf['tpl']['y']?>)</td>
			<td style="font-style: italic; font-weight: bold;">(<?=$conf['tpl']['n']?>) Не согласен</td>
		</tr>
		<? for($i = 0; $i < max(count($conf['tpl']['yes']), count($conf['tpl']['no'])); $i++): ?>
		<tr valign=top>
			<td width=50% align=right>
				<div style="float:right; padding: 0 10px 10px 30px; font-weight: bold;"><?=$conf['tpl']['yes'][$i]['name']?></div>
				<div style="font-style: italic;"><?=$conf['tpl']['yes'][$i]['description']?></div>
			</td>
			<td>
				<div style="float:left; padding: 0 30px 10px 10px; font-weight: bold;"><?=$conf['tpl']['no'][$i]['name']?></div>
				<div style="font-style: italic;"><?=$conf['tpl']['no'][$i]['description']?></div>
			</td>
		</tr>
		<? endfor; ?>
	</table>
<? else: # Выводим список утверждений ?>
	<div align=center><table border=1 cellspacing=0 cellpadding=3 width=95% style="border-color: #aaa; border-collapse: collapse;">
		<tr>
			<th>н/п</th>
			<th>Утверждение</th>
			<th>Согласен</th>
			<th>Не согласен</th>
		</tr>
		<? foreach($conf['tpl']['scale'] as $k=>$v): ?>
			<tr>
				<td><?=($k+1)?>.</td>
				<td><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></td>
				<td><?=$conf['tpl']['cy'][ $v['id'] ]?></td>
				<td><?=$conf['tpl']['cn'][ $v['id'] ]?></td>
			</tr>
		<? endforeach; ?>
	</table></div>
<? endif; ?>