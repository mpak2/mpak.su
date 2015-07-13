<? if($f = rb($arg['fn'], "id", $_GET['id'])): ?>
	<?=aedit("/{$arg['modname']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}?where[id]={$f['id']}")?>
	<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>"><?=$conf['settings']["{$arg['modname']}_{$arg['fn']}"]?></a> &raquo;
	<h1><?=$f['name']?></h1>
	<? if($f['img']): ?>
		<img src="/<?=$arg['modname']?>:img/<?=$f['id']?>/tn:<?=$arg['fn']?>/fn:img/w:200/h:200/null/img.png" style="float:right;">
	<? endif; ?>
	<? if($f['description']): ?>
		<p><?=$f['description']?></p>
	<? endif; ?>
	<? if($f['text']): ?>
		<p><?=$f['text']?></p>
	<? endif; ?>

	<? foreach(array_column(ql("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modname']}%\""), "Tables_in_{$conf['db']['name']}") as $tables): ?>
		<? if(array_key_exists("{$arg['fn']}_id", qn("SHOW COLUMNS FROM `$tables`", "Field"))): ?>
			<? if($el = substr($tables, strlen("{$conf['db']['prefix']}{$arg['modpath']}")+1)): ?>
				<b><?=($conf['settings']["{$arg['modpath']}_{$el}"] ?: $conf['modules'][$arg['modpath']]['name'])?></b>
				<ul>
					<? foreach(rb($tables, 20, "{$arg['fn']}_id", "id", $f['id']) as $e): ?>
						<li><a href="/<?=$arg['modname']?><?=($el != "index" ? ":{$el}" : "")?>/<?=$e['id']?>"><?=$e['name']?></a></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		<? endif; ?>
	<? endforeach; ?>
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