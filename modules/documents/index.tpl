<? if(!$_GET['id']): ?>
	<div style="color:#2C79B3;">
		<? foreach($conf['tpl']['index'] as $k=>$v): ?>
			<? if($cat_id != $v['cat_id']): $cat_id = $v['cat_id']; ?>
				<h2><?=$conf['tpl']['cat'][ $v['cat_id'] ]?></h2>
			<? endif; ?>
			<div style="overflow:hidden;">
				<div>
					<a href="/<?=$arg['modpath']?>/<?=$v['id']?>/null/<?=$v['name']?>.<?=array_pop(explode('.', $v['document']))?>">
						<?=($v['name'] ?: $v['document'])?>
					</a>
				</div>
				<div style="margin:10px; size:80%;"><?=$v['description']?></div>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>