<style>
	.dv {
		width: 100%;
	}
</style>
<form method="post">
	<div><input name="asql[sql]" type="text" value="INSERT INTO tablename SET name='{0}';" class="dv"></div>
	<div>
		<textarea name="asql[text]" style="height:200px;" class="dv">
<?=($_POST['asql']['text'] ?: "Какие\nто\nданные")?>
</textarea>
	</div>
	<div>
		<select name="asql[delemiter]">
			<? foreach($conf['tpl']['delemiter'] as $k=>$v): ?>
				<option value="<?=$k?>"><?=$v?></option>
			<? endforeach; ?>
		</select>
		<input type="submit" value="Сформировать">
	</div>
</form>

<? if($conf['tpl']['result']): ?>
	<textarea class="dv" style="height:200px;">
<?=implode("\n", $conf['tpl']['result'])?>
	</textarea>
<? endif; ?>