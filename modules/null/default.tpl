<div>
	<? if($default = rb($arg['fn'], "id", get($_GET, 'id'))): ?>
		<?=aedit("/{$arg['modpath']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}?where[id]={$default['id']}")?>
		<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>"><?=$conf['settings']["{$arg['modname']}_{$arg['fn']}"]?></a> &raquo;
		<h1><?=$default['name']?></h1>
		<? if($img = get($default, 'img')): ?>
			<img src="/<?=$arg['modpath']?>:img/<?=$default['id']?>/tn:<?=$arg['fn']?>/fn:img/w:500/h:500/null/img.png" style="float:right;">
		<? endif; ?>
		<p><?=$default['description']?></p>
		<p><?=get($default, 'text')?></p>
		<? inc("modules/{$arg['modpath']}/default/{$arg['fn']}", array('default'=>$default)) ?>
	<? else: ?>
		<h1><?=$conf['settings']["{$arg['modname']}_{$arg['fn']}"]?></h1>
		<ul>
			<? foreach(rb($arg['fn']) as $default): ?>
				<li>
					<a href="/<?=$arg['modname']?>:<?=$arg['fn']?>/<?=$default['id']?>">
						<?=$default['name']?>
					</a>
					<p><?=$default['description']?></p>
				</li>
			<? endforeach; ?>
		</ul>
	<? endif; ?>
</div>
