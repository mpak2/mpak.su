<? if($$arg['fe'] = rb($arg['fe'], "id", $_GET['id'])): # Верстка страницы элемента ?>
	<div class="<?=$arg['modpath']?>_<?=$arg['fe']?>">
		<span style="float:right;">
			<a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>">Весь список</a>
		</span>
		<span style="float:right;"><?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?></span>
		<div>
			<h2><?=${$arg['fe']}['name']?></h2>
		</div>
		<img src="/<?=$arg['modname']?>:img/<?=${$arg['fe']}['id']?>/tn:<?=$arg['fe']?>/fn:img/w:120/h:100/null/img.png" style="float:right;">
		<div style="font-weight:bold; margin:20px 0;"><?=${$arg['fe']}['description']?></div>
		<div><?=${$arg['fe']}['text']?></div>
	</div>
<? else: # Список всех элементов если не выбран конкретный ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach(rb($arg['fe']) as $$arg['fe']): ?>
		<div><a href="/<?=$arg['modname']?><?=($arg['fe'] == "index" ? "" : ":{$arg['fe']}")?>/<?=${$arg['fe']}['id']?>"><?=${$arg['fe']}['name']?></a></div>	
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
