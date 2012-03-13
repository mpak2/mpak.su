<? if($tpl['files']): ?>
	<div>
		<? foreach($tpl['files'] as $cat_id=>$files): ?>
			<h2><?=$tpl['cat'][ $cat_id ]['name']?></h2>
			<div style="margin-left:10px;">
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