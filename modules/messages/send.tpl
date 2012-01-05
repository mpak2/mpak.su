<? if ($_POST): ?>
	<div style="margin: 100px;" align="center">
		<?=$conf['tpl']['error']?> <a href="/messages">Входящие</a>
	</div>
<? else: ?>
	<? $mess = $conf['tpl']['mess']; ?>
	<div style="margin: 10px 0;"><a href="/<?=$arg['modname']?>">Входящие</a></div>
	<form method="post">
		<table width=100% border=0 cellspacing=0 cellpadding=7px>
			<tr>
				<td width=100px>
					<? if($mess):?>
						<input type='hidden' name='addr' value='<?=(int)$mess['uid']?>'>
						<?=($mess ? $mess['name'] : $conf['settings']['default_usr'])?>
					<? else: ?>
						<input name="uname" title="Адресат" type="text">
					<? endif; ?>
				</td>
				<td>
					<input name="title" title="Заголовок" type="text" style="width: 100%;" value="<?=(!empty($mess['title']) && (strpos($mess['title'], 'Re:') !== 0) ? "Re: {$mess['title']}" : $mess['title'])?>">
				</td>
			</tr>
			<tr>
				<td colspan=2 valign="top" style="padding: 3px;">
					<textarea name="text" title="Текст сообщения" style="width: 100%; height: 300px;"><?=htmlspecialchars($mess['text'])?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan=2><input type="submit" value="Отправить"></td>
			</tr>
		</table>
	</form>
<? endif; ?>
