<? if($conf['tpl']['mysql_fetch_rows']): ?>
	<div style="margin:100px; text-align:center;">
		Информация сохранена <a href="/<?=$arg['modname']?>/<?=$conf['tpl']['page']['id']?>">Статья</a> или <a href="/users/<?=$conf['user']['uid']?>">Кабинет</a>
	</div>
<? else: ?>
	<form action="" method="post">
		<input type="hidden" name="id" value="<?=$conf['tpl']['page']['id']?>">
		<table style="margin-right:50px; width:300px;">
			<tr>
				<td style="width:1px; padding:10px 0;">
					<span>
						<select name="kid">
							<? foreach($conf['tpl']['cat'] as $k=>$v): ?>
								<option value="<?=$v['id']?>"><?=$v['name']?></option>
							<? endforeach; ?>
						</select>
					</span>
				</td>
			</tr>
			<tr>
				<td style="padding:10px 0;">
					<input type="text" name="name" style="width:100%;" title="Заголовок" value="<?=$conf['tpl']['page']['name']?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?=mpwysiwyg('text', $conf['tpl']['page']['text'])?>
				</td>
			</tr>
		</table>
		<div style="text-align:right; margin:10px;"><input type="submit"></div>
	</form>
<? endif; ?>