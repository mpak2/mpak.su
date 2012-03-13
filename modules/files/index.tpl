<form method="post" enctype="multipart/form-data">
	<div>
		<input type="text" name="description">
	</div>
	<div>
		<span style="float:right;">
			<input type="submit" value="Добавить файл">
		</span>
		<input type="file" name="file">
	</div>
</form>
<? if($tpl['files']): ?>
	<div>
		<? foreach($tpl['files'] as $cat_id=>$files): ?>
			<h2 style="margin-left:20px;"><?=$tpl['cat'][ $cat_id ]['name']?></h2>
			<div>
				<? foreach($files as $k=>$v): ?>
					<div class="files" files_id="<?=$v['id']?>" style="overflow:hidden;">
						<span><?=date("d.m.Y H:i:s", $v['time'])?></span>
						<a href="/<?=$arg['modpath']?>/<?=$v['id']?>/null/<?=($fm = mpue($v['description'] ?: 'Без_описания'). ".". array_pop(explode(".", $v['name'])))?>"><?=$fm?></a>
					</div>
				<? endforeach; ?>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>