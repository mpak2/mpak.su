<? die;

$tpl['options_proposals'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_options_proposals WHERE uid=". ($arg['uid'] ?: $conf['user']['uid']));

?>
<? if($tpl['options_proposals']): ?>
	<div>
		<? foreach($tpl['options_proposals'] as $options_proposals): ?>
			<div>
				<a href="/<?=$arg['modname']?>:options_proposals/<?=$options_proposals['id']?>"><?=$options_proposals['description']?></a>
			</div>
		<? endforeach; ?>
	</div>
<? endif; ?>