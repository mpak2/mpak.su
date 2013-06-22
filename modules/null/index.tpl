<? if($$arg['fn'] = $tpl[ $arg['fn'] ][ $_GET['id'] ]): ?>
	<div style="overflow:hidden;">
		<div style="float:right;">
			<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:220/h:200/null/img.jpg">
		</div>
		<h1><?=$v['name']?></h1>
		<div style="margin-top:15px;"><?=$v['description']?></div>
		<div><?=$v['text']?></div>
	</div>
	<!-- [settings:comments'] -->
<? else: ?>
	<? foreach($conf['tpl'][ $arg['fn'] ] as $$arg['fn']): ?>
		<div><a href="/<?=$arg['modname']?><?=($arg['fn'] != 'index' ? ":{$arg['fn']}" : "")?>/<?=$v['id']?>"><?=$v['name']?></a></div>
	<? endforeach; ?>
<? endif; ?>