<? if($tpl['files']): ?>
	<div>
		<? foreach($tpl['files'] as $k=>$v): ?>
			<div>
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>/null/<?=mpue($v['description'])?>.<?=array_pop(explode(".", $v['name']))?>"><?=$v['description']?></a>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>