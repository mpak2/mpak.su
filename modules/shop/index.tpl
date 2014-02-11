<? if($$arg['fe'] = $tpl[ $arg['fe'] ][ $_GET['id'] ]): ?>
	<div class="<?=$arg['modpath']?>_<?=$arg['fe']?>">
		<?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?>
		<div>
			<h1><?=${$arg['fe']}['name']?></h1>
			<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:100/h:100/null/img.png" alt="<?=$index['name']?>">
		</div>
		<div style="font-weight:bold; margin:10px 0;"><?=${$arg['fe']}['description']?></div>
		<div><?=$index['text']?></div>
	</div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>

	<div id="list">
		<ul>
			<? foreach($tpl['index'] as $index): ?>
				<li>
					<a href="/<?=$arg['modname']?>/<?=$index['id']?>">
						<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:100/h:100/null/img.png" alt="<?=$index['name']?>">
					</a>
					<p><a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a></p>
					<small><?=$index['description']?></small>
					<div class="price">
						<strong><?=$index['price']?></strong> руб.
					</div>
				</li>
			<? endforeach; ?>
		</ul>
	</div>

	<div><?=$tpl['mpager']?></div>
<? endif; ?>
