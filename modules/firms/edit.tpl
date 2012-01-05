<form method="post" action="/<?=$arg['modpath']?>:edit/tn:<?=$_GET['tn']?>">
	<? if((int)$conf['tpl']['edit']['id']): ?>
		<input type="hidden" name="id" value="<?=(int)$conf['tpl']['edit']['id']?>">
	<? endif; ?>
	<? foreach(array_diff_key($conf['tpl']['edit'], array_flip(array('id', 'uid', 'time'))) as $k=>$v): ?>
		<div style="clear:both;">
			<div style="float:left; min-width:160px;">
				<span><?=($conf['settings'][$fn = "{$arg['modpath']}_{$_GET['tn']}_$k"] ?: $fn)?></span>
			</div>
			<span style="margin-left:10px;">
				<? if(substr($k, -3) == '_id'): ?>
					<select name="<?=$k?>">
						<? foreach($conf['tpl'][$k] as $n=>$z): ?>
							<option value="<?=$n?>"<?=($v == $n ? " selected" : '')?>><?=$z?></option>
						<? endforeach; ?>
					</select>
				<? else: ?>
					<? if($conf['tpl']['field'][ $k ] == 'text'): ?>
						<textarea name="<?=$k?>" style="width:300px;"><?=$v?></textarea>
					<? else: ?>
						<input type="text" name="<?=$k?>" value="<?=$v?>" style="width:<?=(substr($conf['tpl']['field'][ $k ], 0, 3) == 'int' ? 2: 3)?>00px;"<?=(($arg['access'] <= 2) ? " disabled" : '')?>>
					<? endif; ?>
				<? endif; ?>
			</span>
		</div>
	<? endforeach; ?>
	<div style="text-align:right; margin:10px; width:500px;">
		<input type="submit" value="Отправить">
<!--		<input type="button" style="modalCloseImg simplemodal-close" value="Закрыть"> -->
	</div>
</form>