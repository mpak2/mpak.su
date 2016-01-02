<? if($f = rb($arg['fn'], "id", $_GET['id'])): ?>
	<?=aedit("/{$arg['modname']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}?where[id]={$f['id']}")?>
	<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>"><?=$conf['settings']["{$arg['modname']}_{$arg['fn']}"]?></a> &raquo;
	<h1><?=$f['name']?></h1>
	<? if(array_key_exists('img', $f) && $f['img']): ?>
		<img src="/<?=$arg['modname']?>:img/<?=$f['id']?>/tn:<?=$arg['fn']?>/fn:img/w:200/h:200/null/img.png" style="float:right;">
	<? endif; ?>
	<p><?=(array_key_exists('description', $f) ? $f['description'] : "")?></p>
	<p><?=$f['text']?></p>
<? else: ?>
	<h1><?=$conf['settings']["{$arg['modname']}_{$arg['fn']}"]?></h1>
	<ul>
		<? foreach(rb($arg['fn']) as $f): ?>
			<li>
				<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$f['id']?>">
					<?=$f['name']?>
				</a>
				<p><?=$f['description']?></p>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
