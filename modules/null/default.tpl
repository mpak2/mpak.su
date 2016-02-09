<? if($default = rb($arg['fn'], "id", get($_GET, "id"))): ?>
	<?=aedit("/{$arg['modpath']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}?&where[id]={$default['id']}")?>
	<h1><?=($conf['settings']['title'] = ($n = get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}")). " &laquo;{$default['name']}&raquo;")?></h1>
	<p><?=$default['text']?></p>
	<div style="margin-left:20px;">
		<? inc("modules/{$arg['modpath']}/default/{$arg['fn']}.tpl", array('default'=>$default)) ?>
	</div>
<? endif; ?>
