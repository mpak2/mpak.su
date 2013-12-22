<? if($$arg['fe'] = $tpl[ $arg['fe'] ][ $_GET['id'] ]): ?>
	<div class="<?=$arg['modpath']?>_<?=$arg['fe']?>">
		<span style="float:right;">
			<a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>">Весь список</a>
		</span>
		<span style="float:right;"><?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?></span>
		<div>
			<h2><?=${$arg['fe']}['name']?></h2>
		</div>
		<div style="font-weight:bold; margin:20px 0;"><?=${$arg['fe']}['description']?></div>
		<div><?=${$arg['fe']}['text']?></div>
	</div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach($tpl[ $arg['fe'] ] as $$arg['fe']): ?>
		<div><a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>/<?=${$arg['fe']}['id']?>"><?=${$arg['fe']}['name']?></a></div>	
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
